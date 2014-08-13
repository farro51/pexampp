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
 * Class to manage the files resources
 *
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.WebServices.Restos
 * @version 0.1
 */
class RestResource_Files extends RestResource {
    
    public function __construct($rest_generic) {
        
        parent::__construct($rest_generic);

        $data = $rest_generic->getDriverData("Files");

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
        
		$res = null;
        if ($resources->isSpecificResources()){
			try {
            	$res = $this->_queryDriver->getDocumentData($resources->getResourceId());
			}
			catch (Exception $e) {
				$res_message = $e->getMessage();
			}
        }
        else {
            return false;
        }

        if(!$res) {
            $this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
			if (!empty($res_message)) {
            	$this->_restGeneric->RestResponse->Content = $res_message;
			}
        }
        else {
            Restos::using('resources.files.restmapping_files');
            $mapping = new RestMapping_Files($res);

            $data = $mapping->getMapping($this->_restGeneric->RestResponse->Type);
			
			if ($data != null) {
				$this->_restGeneric->RestResponse->Content = $data;
			}
			else {
	            $this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('404'));
			}
        }

        return true;
    }
}
