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
 * @author Cristina Madrigal <malevilema@gmail.com>
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
     * When request verb is get
     * @see resources/RestResource::onGet()
     * @return bool
     */
    public function onGet(){

        $resources = $this->_restGeneric->RestReceive->getResources();

        if ($resources->isSpecificResources()){
            return false;
        }
        else {
            $parameters = $this->_restGeneric->RestReceive->getParameters();
            
            if(!empty($parameters['action']) && $parameters['action'] == 'search'){
                if(empty($parameters['x']) || empty($parameters['y']) || empty($parameters['limit'])){
                    $this->_restGeneric->RestResponse->Content = RestosLang::get('segments.get.coordinatesrequired');
                    return true;
                }
                $segment = $this->_queryDriver->searchSegments($parameters['x'], $parameters['y'], $parameters['limit']);
            }
            else{
                $segment = $this->_queryDriver->getSegments();
            }
        }
        
        if(!$segment) {
            $this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
        }
        else {
            Restos::using('resources.segments.restmapping_segments');
            $mapping = new RestMapping_Segments($segment);

            $this->_restGeneric->RestResponse->Content = $mapping->getMapping($this->_restGeneric->RestResponse->Type);
        }

        return true;
    }
    
    /**
     * When request verb is put
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
						return true;
					}
				
					//Get info recipient address with google geocode
					$address_recipient = $this->_queryDriver->isAddress($parameters['address_r']);
					if(!$address_recipient) {
						$result->successful = false;
						$result->message = "Recipient address not found";
						return true;
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
							$this->_restGeneric->RestResponse->Content = "Wrong code";
							return true;
						}
					}
                break;
				case 'feedback':
					$result->successful = true;
					$data = json_decode(file_get_contents('php://input'), true);
					var_dump($data);
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
				return true;
			}
		
			//Get info recipient address with google geocode
			$address_recipient = $this->_queryDriver->isAddress($parameters['address_r']);
			if(!$address_recipient) {
				$result->successful = false;
				$result->message = "Recipient address not found";
				return true;
			}
		
			$delivery = new Delivery($parameters['name_s'], $parameters['email_s'], $parameters['address_s'], 
									$parameters['name_r'], $parameters['email_r'], $parameters['address_r']);
									
			file_put_contents('./log.txt', 'name_s ' . $parameters['name_s'] . ' email_s ' . $parameters['email_s'] . ' address_s ' .
					$parameters['address_s'] . ' name_r ' . $parameters['name_r'] . ' email_r ' . $parameters['email_r'] . ' address_r ' .
					$parameters['address_r'] . PHP_EOL, FILE_APPEND);						
									
			$lat_s = $address_sender->results[0]->geometry->location->lat;
			$lon_s = $address_sender->results[0]->geometry->location->lng;
			$lat_r = $address_recipient->results[0]->geometry->location->lat;
			$lon_r = $address_recipient->results[0]->geometry->location->lng;
			
			$delivery->agent = $this->_queryDriver->searchAgent($lat_s, $lon_s, $lat_r, $lon_r);//Agent 1: 45.075597, 7.644060 (2 min) 45.075658, 7.648352 (3.3 min) 45.076022, 7.655604
			//(4.5 min) 45.076416, 7.664316 (3.5) 45.074143, 7.668393 (3.5) 45.069809, 7.664617 (2.5 min) 45.067172, 7.666205
			/**
			 * Agent2 45.051499, 7.674659 (4 min) 45.057168, 7.676805 (4.5 min) 45.062686, 7.678993 (2.5 min) 45.064383, 7.673672 (3 min) 45.067960, 7.676547
			 */
			file_put_contents('./log.txt', var_export($delivery->agent, true) . PHP_EOL, FILE_APPEND);
			if(!$delivery->agent) {
				$result->successful = false;
				$result->message = "There aren't avaliable agents";
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
			file_put_contents('./log.txt', var_export($destinations, true) . PHP_EOL, FILE_APPEND);
			$dist_matrix = $this->_queryDriver->getDistanceMatrix($destinations);
			file_put_contents('./log.txt', var_export($dist_matrix, true) . PHP_EOL, FILE_APPEND);
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
			file_put_contents('./log.txt', var_export($order_path, true) . PHP_EOL, FILE_APPEND);
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
			
			/*$this->_queryDriver->sendMail($delivery->sender_email, 'Tracking', $delivery->tracking_code);
			$this->_queryDriver->sendMail($delivery->recipient_email, 'Delivery', $delivery->delivery_code);*/
			//@TODO we must send a push notification to the agent phone, and save the new path with the new delivery in the last position
			
			$result->successful = true;
			$result->tracking_code = $delivery->tracking_code;
        }

        $this->_restGeneric->RestResponse->Content = $result;

        return true;
    }
    
    public function onPut(){

        $resources = $this->_restGeneric->RestReceive->getResources();
    	$parameters = $this->_restGeneric->RestReceive->getProcessedContent();        
      	$parameters = array_merge($parameters, $this->_restGeneric->RestReceive->getParameters());
		$result = new stdClass();
        if ($resources->isSpecificResources()){
            switch($resources->getResourceId()){
                case 'set_tracking_code':
					$result->successful = $this->_queryDriver->reachedDestination($parameters['code'], "tracking_code", "delivering", "pickup_time");
                break;
                case 'set_delivery_code':
					$result->successful = $this->_queryDriver->reachedDestination($parameters['code'], "delivery_code", "delivered", "delivery_time");
					//Guardar la imagen y las preguntas
                break;
				case 'feedback':
					$result->successful = true;
					$data = json_decode(file_get_contents('php://input'), true);
					var_dump($data);
				break;
            }
        }
        else {
        	return false;
        }
        $this->_restGeneric->RestResponse->Content = $result;

        return true;
    }
}
