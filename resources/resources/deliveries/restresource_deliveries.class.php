<?php
/*
 *  This file is part of Restos software
 * 
 *  Restos is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  Restos is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with Restos.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class to manage the segments resources
 *
 * @author Federico Arroyave <farroyave51@gmail.com>
 * @version 0.1
 */
class RestResource_Deliveries extends RestResource {
    
    public function __construct($rest_generic) {
        
        parent::__construct($rest_generic);

        $data = $rest_generic->getDriverData("ACDefault");

        if($data != null) {
            $this->_queryDriver = DriverManager::getDriver($data->Name, $data->Properties);
        }
    }
    
    /**
     * When request verb is post
     * @see resources/RestResource::onPut()
     * @return bool
     */
    public function onPost(){

    	$resources = $this->_restGeneric->RestReceive->getResources();
        $parameters = $this->_restGeneric->RestReceive->getParameters();
        $result = null;
        if ($resources->isSpecificResources()){
            switch($resources->getResourceId()){
                case 'tracking':
                	$result = new stdClass();
                	$delivery = $this->_queryDriver->getTrackingInfo($parameters['code']);
					if ($delivery) {
						$result->successful = true;
						$result->tracking_info = $delivery;
					}
					else {
						$result->successful = false;
					}
					break;
				case 'distance':
					Restos::using('privato.Delivery');
					$result = new stdClass();
					//Get info sender address with google geocode
					$address_sender = $this->_queryDriver->isAddress($parameters['address_s']);
					if(!$address_sender) {
						$result->successful = false;
						$result->message = "Sender address not found";
						break;
					}
				
					//Get info recipient address with google geocode
					$address_recipient = $this->_queryDriver->isAddress($parameters['address_r']);
					if(!$address_recipient) {
						$result->successful = false;
						$result->message = "Recipient address not found";
						break;
					}
											
					$lat_s = $address_sender->results[0]->geometry->location->lat;
					$lon_s = $address_sender->results[0]->geometry->location->lng;
					$lat_r = $address_recipient->results[0]->geometry->location->lat;
					$lon_r = $address_recipient->results[0]->geometry->location->lng;
					
					$origin = $lat_s . "," . $lon_s;
					$dest = $lat_r . "," . $lon_r;
					
					$result->successful = true;
					$dist = $this->_queryDriver->getDistance($origin, $dest);
					$result->distance = round($dist->distance->value, 2);
				break;
				case 'setcode':
                	$result = new stdClass();
					if($parameters['type'] == 'd') {
						if($this->_queryDriver->reachedDestination($parameters['code'], "delivery_code", "delivered", "delivery_time") > 0) {
							$result->successful = true;
						}
						else {
							$this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
							$this->_restGeneric->RestResponse->Type = 'Text';
							$this->_restGeneric->RestResponse->Content = "Wrong code";
							return true;
						}
					}
					else {
						if($this->_queryDriver->reachedDestination($parameters['code'], "tracking_code", "delivering", "pickup_time") > 0) {
							$result->successful = true;
						}
						else {
							$this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
							$this->_restGeneric->RestResponse->Type = 'Text';
							$this->_restGeneric->RestResponse->Content = "Wrong code";
							return true;
						}
					}
                break;
				case 'feedback':
					if(empty($parameters['data'])) {
						$this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
						$this->_restGeneric->RestResponse->Type = 'Text';
						$this->_restGeneric->RestResponse->Content = "Send all data";
						return true;
					}
					$data = json_decode($parameters['data'], false);
					if($data == NULL) {
						$this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
						$this->_restGeneric->RestResponse->Type = 'Text';
						$this->_restGeneric->RestResponse->Content = "Bad json format";
						return true;
					}
					$results = $this->_queryDriver->saveFeedback($data);
					if($results === true) {
						$result = new stdClass();
						$result->successful = true;
					}
					else {
						$this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
						$this->_restGeneric->RestResponse->Type = 'Text';
						$this->_restGeneric->RestResponse->Content = $result;
						return true;
					}
				break;
			}
        }
        else {
        	Restos::using('privato.Delivery');
        	$result = new stdClass();
        	//Get info sender address with google geocode
        	$address_sender = $this->_queryDriver->isAddress($parameters['address_s']);
			if(!$address_sender) {
				$result->successful = false;
				$result->message = "Sender address not found";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
		
			//Get info recipient address with google geocode
			$address_recipient = $this->_queryDriver->isAddress($parameters['address_r']);
			if(!$address_recipient) {
				$result->successful = false;
				$result->message = "Recipient address not found";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
		
			$delivery = new Delivery($parameters['name_s'], $parameters['email_s'], $parameters['address_s'], 
									$parameters['name_r'], $parameters['email_r'], $parameters['address_r']);
									
			$lat_s = $address_sender->results[0]->geometry->location->lat;
			$lon_s = $address_sender->results[0]->geometry->location->lng;
			$lat_r = $address_recipient->results[0]->geometry->location->lat;
			$lon_r = $address_recipient->results[0]->geometry->location->lng;
			
			$delivery->agent = $this->_queryDriver->searchAgent($lat_s, $lon_s, $lat_r, $lon_r);
			
			if($delivery->agent === false) {
				$result->successful = false;
				$result->message = "There aren't avaliable agents";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
			
			//We found the agent
			$destinations = $this->_queryDriver->getAgentListPositions($delivery->agent, $lat_s, $lon_s, $lat_r, $lon_r);
			if(!$destinations) {
				$result->successful = false;
				$result->message = "Problem with destinations";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
			$dist_matrix = $this->_queryDriver->getDistanceMatrix($destinations);
			if (!$dist_matrix) {
				$result->successful = false;
				$result->message = "Problem with distance matrix";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
			
			if(count($destinations) == 3) {
				$order_path = array(0,1,2);
			}
			else {
				$order_path = $this->_queryDriver->getAgentOrderPath($dist_matrix, $destinations);
			}
			if (!$order_path) {
				$result->successful = false;
				$result->message = "Problem with order path";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
			
			$id_delivery = $this->_queryDriver->insertDelivery($delivery);
			if (!$id_delivery) {
				$result->successful = false;
				$result->message = "Problem with insert delivery";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
			$dim = count($destinations);
			$destinations[$dim-1]->id_delivery = $id_delivery;
			$destinations[$dim-2]->id_delivery = $id_delivery;
			
			if(!$this->_queryDriver->insertPathAgent($destinations, $dist_matrix, $order_path, $delivery->agent)) {
				$result->successful = false;
				$result->message = "Problem with insert path agent";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
			$this->_queryDriver->sendMail($delivery->sender_email, 'Tracking credentials', 
						"Hi, this email is automatically send to you from ponyexpress.com because a new delivery was created.
						</br> The corrisponding Tracking code is: " . $delivery->tracking_code . "<br/><br/>");
			$this->_queryDriver->sendMail($delivery->recipient_email, 'Delivery credentials', 
						"Hi, this email is automatically send to you from ponyexpress.com because a new delivery was created.
						</br> The corrisponding Delivery code is: " . $delivery->delivery_code . "<br/><br/>");
			$res = $this->_queryDriver->sendNotificaPush($delivery->agent, "Update list of deliveries");
			if(!$res) {
				$result->successful = false;
				$result->message = "Problem with push notification";
				$this->_restGeneric->RestResponse->Content = $result;
				return true;
			}
			$result->successful = true;
			$result->tracking_code = $delivery->tracking_code;
        }

        $this->_restGeneric->RestResponse->Content = $result;

        return true;
    }

}
