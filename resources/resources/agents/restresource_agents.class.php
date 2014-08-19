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
 * Class to manage the agents resources
 *
 * @author Federico Arroyave <farroyave51@gmail.com>
 * @version 0.1
 */
class RestResource_Agents extends RestResource {
    
    public function __construct($rest_generic) {
        
        parent::__construct($rest_generic);

        $data = $rest_generic->getDriverData("ACDefault");

        if($data != null) {
            $this->_queryDriver = DriverManager::getDriver($data->Name, $data->Properties);
        }
    }

    public function onGet() {
    	$resources = $this->_restGeneric->RestReceive->getResources();
		$results = new stdClass();
		$results->successful = true;
        if ($resources->isSpecificResources()){
        	$results->destinations_list = $this->_queryDriver->getAgentPath($resources->getResourceId());
        	$this->_restGeneric->RestResponse->Content = $results;
        	return true;
        }
        else {
            return false;
        }
        
    }
    
    /**
     * When request verb is post
     * @see resources/RestResource::onPost()
     * @return bool
     */
    public function onPost(){

        $resources = $this->_restGeneric->RestReceive->getResources();
        $parameters = $this->_restGeneric->RestReceive->getParameters();
        $agents = null;
        if ($resources->isSpecificResources()){
            switch($resources->getResourceId()){
                case 'login':
					$agents = new stdClass();
					$agents->successful = true;
					if(empty($parameters['email']) || empty($parameters['password']) || empty($parameters['reg_id'])){
						$agents = false;
						$message = "Insert all data";
                    }
					else {
						$id_agent = null;
						$agents->authorization = $this->_queryDriver->login($parameters['email'], 
									$parameters['password'], $parameters['reg_id'], $id_agent);
						if(!$agents->authorization) {
							$message = "User or password incorrect!";
							$agents = false;
							break;
						}
						$agents->id_user = $id_agent;
						$agents->questions = $this->_queryDriver->getQuestions();
					}
                    break;
                case 'logout':
                    $response = $this->_queryDriver->logout($parameters['id_user']);
                    if($response){
                        $agents = new stdClass();
                        $agents->user = $parameters['id_user'];
                        $agents->successful = true;
                    }
                    else{
                        $agents = false;
						$message = "Logout fail!";
                    }
                    break;
				case 'updatePath':
					$agents = new stdClass();
					if(empty($parameters['path'])) {
						$message = "Wrong arguments";
						$agents = false;
						break;
					}
					$data = json_decode($parameters['path'], false);
					if($data == NULL) {
						$message = "Bad json";
						$agents = false;
						break;
					}
					$result = $this->_queryDriver->updatePathAgent($data, $parameters['agent']);
					if($result === true) {
						$agents->successful = true;
					}
					else {
						$agents = false;
						$message = $result;
					}
				break;
            }
            
        }
        else {
            return false;
        }
        
        if(!$agents) {//https://maps.googleapis.com/maps/api/distancematrix/json?origins=45.0757,7.64835|45.076,7.6556|45.0764,7.66432|45.0741,7.66839|45.0698,7.66462|45.0672,7.6662&destinations=45.0757,7.64835|45.076,7.6556|45.0764,7.66432|45.0741,7.66839|45.0698,7.66462|45.0672,7.6662&mode=walking&sensor=false
            $this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
			$this->_restGeneric->RestResponse->Type = 'Text';
			$this->_restGeneric->RestResponse->Content = $message;
        }
        else {
		
            $this->_restGeneric->RestResponse->Content = $agents;
        }

        return true;
    }
    
    public function onPut() {
    	$resources = $this->_restGeneric->RestReceive->getResources();
    	$parameters = $this->_restGeneric->RestReceive->getProcessedContent();        
      	$parameters = array_merge($parameters, $this->_restGeneric->RestReceive->getParameters());
        $agents = null;
        if ($resources->isSpecificResources()){
        	$agents = new stdClass();
			$agents->successful = false;
			$fields = array();
			if (!empty($parameters['phone'])) {
				$fields['phone'] = $parameters['phone'];
			}
			
			if (!empty($parameters['name'])) {
				$fields['name'] = $parameters['name'];
			}
			
			if (!empty($parameters['password'])) {
				$fields['password'] = $parameters['password'];
			}
			
			if (!empty($parameters['mail'])) {
				$fields['mail'] = $parameters['mail'];
			}
			
			if (!empty($parameters['latitude'])) {
				$fields['last_position_lat'] = $parameters['latitude'];
			}
			
			if (!empty($parameters['longitude'])) {
				$fields['last_position_lon'] = $parameters['longitude'];
			}
			$message = "There isn't data to update";
			if (count($fields) > 0) {
				$agents->successful = $this->_queryDriver->updateAgent($fields, $resources->getResourceId());
				$message = "Can't update the agent";
			}
			if($agents->successful) {
				$this->_restGeneric->RestResponse->Content = $agents;
			}
			else {
				$this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
				$this->_restGeneric->RestResponse->Type = 'Text';
				$this->_restGeneric->RestResponse->Content = $message;
			}
        }
        else {
            return false;
        }
        return true;
    }
}
