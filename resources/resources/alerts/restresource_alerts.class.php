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
 * Class to manage the alerts resources
 *
 * @author Cristina Madrigal <malevilema@gmail.com>
 * @version 0.1
 */
class RestResource_Alerts extends RestResource {
    
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
            $alert = $this->_queryDriver->getAlert($resources->getResourceId());
        }
        else {
            $parameters = $this->_restGeneric->RestReceive->getParameters();
            if(!empty($parameters['action']) && $parameters['action'] == 'search'){
                if(empty($parameters['x']) || empty($parameters['y']) || empty($parameters['limit'])){
                    $this->_restGeneric->RestResponse->Content = RestosLang::get('alerts.get.coordinatesrequired');
                    return true;
                }
                $alert = $this->_queryDriver->searchAlerts($parameters['x'], $parameters['y'], $parameters['limit']);
            }
            else{
                $alert = $this->_queryDriver->getAlerts();
            }
        }
        
        if(!$alert) {
            $this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
        }
        else {
            Restos::using('resources.alerts.restmapping_alerts');
            $mapping = new RestMapping_Alerts($alert);

            $this->_restGeneric->RestResponse->Content = $mapping->getMapping($this->_restGeneric->RestResponse->Type);
        }

        return true;
    }
    
    /**
     * When request verb is post
     * @see resources/RestResource::onPost()
     * @return bool
     */
    public function onPost(){

        $resources = $this->_restGeneric->RestReceive->getResources();
        $parameters = $this->_restGeneric->RestReceive->getParameters();
        if ($resources->isSpecificResources()){
            return false;
        }
        else {
            if(empty($parameters['x']) || empty($parameters['y'])){
                $this->_restGeneric->RestResponse->Content = RestosLang::get('alerts.post.coordinatesrequired');
                return true;
            }
            
            $description = empty($parameters['description']) ? '' : $parameters['description'];
            try{
                $result = $this->_queryDriver->insertAlert($parameters['x'], $parameters['y'], $description);
                if($result){
                    $result = array('id'=>$result);
                }
            }
            catch(Exception $e){
                $result = $e->getMessage();
            }
        }
        $this->_restGeneric->RestResponse->Content = $result;

        return true;
    }
    
    /**
     * When request verb is delete
     * @see resources/RestResource::onDelete()
     * @return bool
     */
    public function onDelete(){

        $resources = $this->_restGeneric->RestReceive->getResources();

        if ($resources->isSpecificResources()){
            try{
                $result = $this->_queryDriver->deleteAlert($resources->getResourceId());
            }
            catch(Exception $e){
                $result = $e->getMessage();
            }
        }
        else {
            return false;
        }

        $this->_restGeneric->RestResponse->Content = $result;

        return true;
    }
    
}
