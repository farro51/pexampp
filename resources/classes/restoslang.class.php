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
 * Class RestosLang is a generic class to translate funtionalities
 *
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.WebServices.Restos
 * @version 0.1
 */
class RestosLang {
    
    public static $CurrentLang = 'en';
    public static $DefaultLang = 'en';
    
    private static  $_strings = array();
    
    public static function get ($key, $type = 'restos', $params = null){

        if (!isset(RestosLang::$_strings[$type])) {
            $s = array();

            $file = 'langs/' . RestosLang::$CurrentLang . '/' . $type . '.php';
            
            if (!file_exists($file) || is_dir($file)) {
                $file = 'langs.' . RestosLang::$DefaultLang . '.' . $type;
                
                if (!file_exists($file) || is_dir($file)) {
                    return '{{' . $key . ':' . $type . '}}';
                }
            }
            
            include $file;
            
            if (count($s) == 0) {
                return '{{{' . $key . ':' . $type . '}}}';
            }
            
            RestosLang::$_strings[$type] = $s;
        }
        
        if (!isset(RestosLang::$_strings[$type][$key])) {
            return '{' . $key . ':' . $type . '}';
        }
        return RestosLang::$_strings[$type][$key];
    }
}