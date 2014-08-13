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
 * Class Restos is a generic class to provide system methods
 *
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.WebServices.Restos
 * @version 0.1
 */
class Restos {
    
    const LOG_FATAL = 1;
    const LOG_ERROR = 2;
    const LOG_DEBUG = 3;
    const LOG_INFO = 4;
    
    public static $Properties;
    
    public static $IndexFileName = "";//"index.php";
    
    /**
     * If true: the Rest URI is make with resources in secuence separed with slah "/"
     * If false: the Rest URI is make with a GET parameter
     * 
     * @var bool 
     */
    public static $SlashURIs = true;

    public static function using ($file_path){
        $file_path = str_replace('.', '/', $file_path);
        
        if (file_exists($file_path) && !is_dir($file_path)){
            include_once($file_path);
        }
        else if (file_exists($file_path . '.php')){
            include_once($file_path . '.php');
        }
        else if (file_exists($file_path . '.class.php')){
            include_once($file_path . '.class.php');
        }
        else {
            return false;
        }
        return true;
    }
    
    /**
     * 
     * Load the default application properties
     */
    public static function initProperties($file_properties_name = 'properties.json') {

        //Hack for Windows paths
        $file_name = str_replace('\\', '/', __FILE__);
        $file_path = substr($file_name, 0, strrpos($file_name, "/classes/restos.class.php")) . "/";
        
        if (file_exists($file_path . $file_properties_name)) {
            $properties_string = file_get_contents($file_path . $file_properties_name);
            $properties = json_decode($properties_string);
        }
        else {
            $properties = new stdClass();
            $properties->Resources = array();
        }

        //ToDO: Utilizar expresiones regulares para extraer los componentes de las URI
        // La expresión ofcicial es:  ^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?
        // Cada posición de la expresión regular posee un dato de la URL 
        // tomada de http://labs.apache.org/webarch/uri/rfc/rfc3986.html#regexp
        if (!isset($properties->Protocol)) {
            $tmp_parts = explode('/', $_SERVER['SERVER_PROTOCOL']);
            
            if (is_array($tmp_parts) && count($tmp_parts) > 0) {
                $properties->Protocol = strtolower($tmp_parts[0]);
            }
            else {
                $properties->Protocol = 'http';
            }
        }
        
        if (!isset($properties->UriBase)) {
            $relative_path = ltrim($_SERVER['REQUEST_URI'], '/');
            
            if (isset($_SERVER['PATH_INFO'])) {
                $relative_path = rtrim($relative_path, $_SERVER['PATH_INFO']);
            }

            if (strpos($relative_path, '?') !== false) {
                $relative_path = substr($relative_path, 0, strpos($relative_path, '?'));
            }

            $relative_path = rtrim($relative_path, Restos::$IndexFileName);
            
            $properties->UriBase = $properties->Protocol . '://' . $_SERVER['SERVER_NAME'] . (empty($relative_path) ? '' : '/' . $relative_path);
        }
        
        if(!isset($properties->RestUriBase)) {
            $properties->RestUriBase = $properties->UriBase;
        }
        else if (substr($properties->RestUriBase, 0, 7) !== 'http://' && substr($properties->RestUriBase, 0, 8) !== 'https://') {
            $properties->RestUriBase = $properties->UriBase . $properties->RestUriBase;
        }
        
        if(!isset($properties->NamespaceUriBase)) {
            $properties->NamespaceUriBase = $properties->UriBase;
        }
        else if (substr($properties->NamespaceUriBase, 0, 7) !== 'http://' && substr($properties->NamespaceUriBase, 0, 8) !== 'https://') {
            $properties->NamespaceUriBase = $properties->UriBase . $properties->NamespaceUriBase;
        }

        Restos::$Properties = $properties;
    }
    
    /**
     * 
     * Return a formated URI namespace about a resource represented by $prefix
     * @param string $prefix
     * @param string $version
     * @return string
     */
    public static function URINamespace ($prefix, $version = '') {
        
        $version = empty($version) ? '' : '/' . $version;
        return Restos::$Properties->NamespaceUriBase . $prefix . $version;
    }
    
    /**
     * Make an URI as a Rest resource
     * 
     * Posible URIs:
     * - http://server.com/relative_path/resource/id?params
     * - http://server.com/relative_path/index.php/resource/id?params
     * 
     * @param string $resource
     * @return string 
     */
    public static function URIRest($resource) {
        
        $uri = Restos::$Properties->RestUriBase;
        
        if(Restos::$SlashURIs) {
            $uri = rtrim($uri, '/');
            $uri .= '/' . $resource;
        }
        else {
            $uri .= Restos::$IndexFileName . '/' . $resource;
        }
        
        return $uri;
    }
    
    /**
     * Make an OpenId from an rest resource. The OpenID is a Rest URI without protocol 
     * 
     * @example
     * - URI:    http://server.com/relative_path/resource/id?param=value
     * - OpenId: server.com/relative_path/resource/id
     * 
     * - URI:    http://server.com/relative_path/index.php/resource/id
     * - OpenId: server.com/relative_path/index.php/resource/id
     * 
     * 
     * @param string $resource
     * @return string 
     */
    public static function OpenIdRest($resource) {
        
        $uri = Restos::$Properties->RestUriBase;
        
        if(Restos::$SlashURIs) {
            $uri = rtrim($uri, '/');
            $uri .= '/' . $resource;
        }
        else {
            $uri .= Restos::$IndexFileName . '/' . $resource;
        }
        
        $uri = ltrim($uri, 'http://');
        $uri = ltrim($uri, 'https://');
        
        if (($pos = strpos($uri, '?')) !== false){
            $uri = substr($uri, 0, $pos);
        }
        
        return $uri;
    }

    /**
     * Make an URI from an OpenId. 
     * 
     * @example
     * - OpenId: server.com/relative_path/resource/id
     * - URI:    http://server.com/relative_path/resource/id
     * 
     * @param string $openid
     * @return string 
     */
    public static function OpenId2URI($openid) {
        
        return 'http://' . $openid;
    }
    
    /**
     * 
     * Throw a new exception according to current error level
     * @param Exception or string $e
     * @throws Exception
     */
    public static function throwException($e){

        if (is_string($e)){
            $msg = $e;
            $code = 9000;
        }
        else {
            $msg = $e->getMessage();
            $code = $e->getCode();
        }
        
        throw new Exception($msg, $code);
    }
    
    /**
     * 
     * Save a log message
     * @param int $level
     * @param string $message
     */
    public static function log($level, $message){

        //ToDo: save log
    }
}