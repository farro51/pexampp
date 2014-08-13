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
 * Class to manage the projects resources
 *
 * @author Cristina Madrigal <malevilema@gmail.com>
 * @version 0.1
 */
class RestResource_Projects extends RestResource {
    
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
			/*if(empty($parameters['title'])){
				$this->_restGeneric->RestResponse->Content = RestosLang::get('projects.get.titlerequired');
				return true;
			}*/
			$segment = $this->_queryDriver->getProjects();
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
            if(empty($parameters['title'])){
				$this->_restGeneric->RestResponse->Content = RestosLang::get('projects.get.titlerequired');
				return true;
			}            
            $description = empty($parameters['description']) ? null : $parameters['description'];
            try{
                $result = $this->_queryDriver->insertProject($parameters['title'], $parameters['description']);
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
     * When request verb is put
     * @see resources/RestResource::onPut()
     * @return bool
     */
    public function onPut(){

        $resources = $this->_restGeneric->RestReceive->getResources();
        $parameters = $this->_restGeneric->RestReceive->getProcessedContent();
        if ($resources->isSpecificResources()){
            if(empty($parameters['intensity'])){
                $this->_restGeneric->RestResponse->Content = RestosLang::get('segments.put.intensityrequired');
                return true;
            }
            try{
                $result = $this->_queryDriver->updateSegment($resources->getResourceId(), $parameters['intensity']);
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
