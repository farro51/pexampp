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
						$name = null;
						$agents->authorization = $this->_queryDriver->login($parameters['email'], 
									$parameters['password'], $parameters['reg_id'], $id_agent, $name);
						if(!$agents->authorization) {
							$message = "User or password incorrect!";
							$agents = false;
							break;
						}
						$agents->id_user = $id_agent;
						$agents->name = $name;
						$agents->questions = $this->_queryDriver->getQuestions();
					}
                    break;
                case 'logout':
                	if(empty($parameters['id_user'])) {
                		$message = "Wrong user id";
                		$agents = false;
                		break;
                	}
                	if(count($this->_queryDriver->getAgentDestinations($parameters['id_user'])) > 0) {
                		$response = $this->_queryDriver->logout($parameters['id_user'], true);
                		$message = "There are deliveries to give";
                		$agents = false;
                		break;
                	}
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
					$path = array();
					foreach($data as $loc) {
						$position = new stdClass();
						if (strpos($loc->id_service, 'p') === false) {
							$position->type = 0;
						}
						else {
							$position->type = 1;
						}
						$position->id_delivery = substr($loc->id_service, 0, -1);
						$position->latitude = $loc->latitude;
						$position->longitude = $loc->longitude;
						$path[] = $position;
					}
					$result = $this->_queryDriver->updatePathAgent($path, $parameters['agent']);
					if($result === true) {
						$agents = new stdClass();
						$agents->successful = true;
					}
					else {
						$agents = false;
						$message = $result;
					}
				break;
				case 'update':
					$agents = new stdClass();
					$agents->successful = false;
					$fields = array();
					
					$pass = null;
					
					if (!empty($parameters['password'])) {
						$fields['password'] = $parameters['new_password'];
						$pass = $parameters['password'];
					}
					
					if (!empty($parameters['latitude'])) {
						$fields['last_position_lat'] = $parameters['latitude'];
					}
					
					if (!empty($parameters['longitude'])) {
						$fields['last_position_lon'] = $parameters['longitude'];
					}
					if (count($fields) > 0) {
						if($this->_queryDriver->updateAgent($fields, $parameters['id_user'], $pass)) {
							$agents->successful = true;
						}
						else {
							$agents = false;
							$message = "Can't update the agent";
						}
					}
					else {
						$agents = false;
						$message = "There isn't data to update";
					}
				break;
            }
            
        }
        else {
            return false;
        }
        
        if(!$agents) {
            $this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
			$this->_restGeneric->RestResponse->Type = 'Text';
			$this->_restGeneric->RestResponse->Content = $message;
        }
        else {
		
            $this->_restGeneric->RestResponse->Content = $agents;
        }

        return true;
    }
    
}
