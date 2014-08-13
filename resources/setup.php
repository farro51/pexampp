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
 * Core function library and params setup
 *
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.WebServices.Restos
 * @version 0.1
 */

/**GENERAL CONFIGURATION*/
define('RESTOS_DEBUG_MODE', true);




/*CODE*/
if (RESTOS_DEBUG_MODE) {
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL);
}

spl_autoload_register('restos__autoload');

$file_properties_name = 'properties.json';

Restos::initProperties($file_properties_name);


//Default __autoload function
function restos__autoload($class_name) {

    $class_name = strtolower($class_name);

    //Normal classes
    if (file_exists('classes/' . $class_name . '.class.php')) {
        include_once 'classes/' . $class_name . '.class.php';
    }
    //Other cases as special classes and drivers
    else {
        
        switch ($class_name) {
            case 'drivermanager':
                include_once 'drivers/drivermanager.class.php';
                break;
            case 'specificationentity':
            case 'specificationnamespace':
                include_once 'resources/specificationentity.class.php';
                break;
            case 'restmapping':
                include_once 'resources/restmapping.class.php';
                break;
            //Class RestResource is include before in the resource condition, this case is by order
            case 'restresource':
                include_once 'resources/restresource.class.php';
                break;
            default:
                $pos = strpos($class_name, '_');
                
                if ($pos !== null) {
                    $type = substr($class_name, 0, $pos);
                    $name = substr($class_name, $pos + 1);
                    
                    switch ($type){
                        case 'driver':
                            if (file_exists('drivers/' . $name . '/' . $class_name . '.class.php')) {
                                include_once 'drivers/' . $name . '/' . $class_name . '.class.php';
                            }
                            break;
                        case 'restresource':
                            if (file_exists('resources/' . $name . '/' . $class_name . '.class.php')) {
                                include_once 'resources/' . $name . '/' . $class_name . '.class.php';
                            }
                        break;
                    }
                }
        }
    }
}
