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
 * Class Connector_relationaldb
 *
 * @author Cristina Madrigal <malevilema@gmail.com>
 * @version 0.1
 */
class Connector_relationaldb {
    
    /**
     * 
     * Show all error messages
     * @var integer
     */
    const E_ALL     = 10;
    
    /**
     * 
     * Show only debug error messages
     * @var integer
     */
    const E_DEBUG   = 20;
    
    /**
     * 
     * Show short description of error messages
     * @var integer
     */
    
    const E_NORMAL  = 50;
    
    /**
     * 
     * The Data Source Name
     * @var string
     */
    private $_DSN;
    
    /**
     * 
     * An associative array of option names and their values.
     * @var array
     */
    private $_options = array();
    
    /**
     * 
     * Object to execute query in database
     * @var MDB2
     */
    public $DB;
    
    /**
     * 
     * A level to display info about the error
     * The levels are:
     * - 0 : All
     * - 1 : develop
     * - 2 : short
     * 
     * @var integer
     */
    public static $ErrorLevel = 10;
    
    /**
     * 
     * Contruct
     * @param string $DNS The Data Source Name
     * @param array $options An associative array of option names and their values.
     */
    public function __construct($DSN, $options = array()){
        
        $this->_DSN = $DSN;
        
        if(is_array($options)){
            $this->_options = $options;
        }
        
        $mysqli = mysqli_connect($this->_DSN->server, $this->_DSN->dbuser, $this->_DSN->dbpass, $this->_DSN->dbname);

        if ($mysqli->connect_errno) {
            Connector_relationaldb::throwException($mysqli->connect_error, $mysqli->connect_errno);
        }
        
        $this->DB = $mysqli;
    }
    
    /**
     * 
     * Throw a new exception according to current error level
     * @param PEAR error $e
     * @throws Exception
     */
    public static function throwException($msg, $code = 0){
        switch (Connector_relationaldb::$ErrorLevel) {
            case Connector_relationaldb::E_ALL:
                $msg = $code.': '.$msg;

                break;
            default:
                $msg = 'database error';
        }

        Restos::throwException(new Exception($msg, $code));
    }
    
    /**
     * 
     * Throw a new exception according to current error level
     * @param PEAR error $e
     * @throws Exception
     */
    public function quote($var, $type = 'varchar'){
        switch ($type){
            case 'integer':
                $var = $var;
                break;
            default:
                if(substr($var, -1) != "'"){
                    $var = "'".$this->DB->real_escape_string($var)."'";
                }
                
        }
        return $var;
    }
    
    /**
     * 
     * Destruct
     * Disconnect the data base if this is connected
     */
    public function __destruct(){

        if ($this->DB && method_exists($this->DB,'disconnect')) {
            $this->DB->disconnect();
        }
    }
    
    /**
     * 
     * Fetch the first row of data returned from a query.
     * @param string $sql - the SQL query
     * @param array $types - that contains the types of the columns in the result set
     * @param array $params - if supplied, prepare/execute will be used with this array as execute parameters
     * @param array $param_types - that contains the types of the values defined in $params
     * @param int $fetch_mode - the fetch mode to use
     * 
     */
    public function getRow($sql) {
        
        $row = $this->DB->query($sql);
        if(!$row){
            return null;
        }
        $row_object = $row->fetch_object();
        
        if(!$row_object){
            return null;
        }
        
        return $row_object;
    }
    
    /**
     * 
     * Fetch all the rows returned from a query.
     * @param string $sql - the SQL query
     * @param array $types - that contains the types of the columns in the result set
     * @param array $params - if supplied, prepare/execute will be used with this array as execute parameters
     * @param array $param_types - that contains the types of the values defined in $params
     * @param int $fetch_mode - the fetch mode to use
     * @param $rekey - if set to true, the $all will have the first column as its first dimension
     * @param $force_array - used only when the query returns exactly two columns. If true, the values of the returned array will be one-element arrays instead of scalars.
     * @param $group - if true, the values of the returned array is wrapped in another array. If the same key value (in the first column) repeats itself, the values will be appended to this array instead of overwriting the existing values.
     * @return data on success, a MDB2 error on failure
     */
    public function getList ($sql) {
        
        $res = array();
        
        $row = $this->DB->query($sql);
        
        if(!$row){
            return null;
        }
        while ($row_object = $row->fetch_object()) {
            if($row_object){
                $res []= $row_object;
            }
        }
        
        return $res;
    }

    /**
     * 
     * Update only one record 
     * @param string $table
     * @param array $condition
     * @param array $fields
     */
    public function update_record($table, array $fields, array $condition){
        
        $where = '';
        $name_fields = '';
        foreach ($condition as $key=>$value) {
            $type = 'varchar';
            if(is_numeric($value)){
                $type = 'integer';
            }
            $where .= $key . ' = ' . $this->quote($value,$type) . ' AND ';
        }
        
        foreach ($fields as $key=>$value) {
            $type = 'varchar';
            if(is_numeric($value)){
                $type = 'integer';
            }
            $name_fields .= $key . ' = ' . $this->quote($value,$type) . ',';
        }
        $name_fields = rtrim($name_fields, ',');
        $where = rtrim($where, ' AND ');
        
        if (!empty($where)) {
            //to modify only one record
            $where .= ' LIMIT 1';
            $sql = "UPDATE $table SET $name_fields WHERE $where";

            $result = $this->DB->query($sql);
            
            if ($this->DB->errno) {
                Connector_relationaldb::throwException($this->DB->error, $this->DB->errno);
                return false;
            }
            
            return $result;
        }
        
        return false;
    }
    
    /**
     * 
     * Insert one record 
     * @param string $table
     * @param array $fields array('name'=>'leidy', 'age'=>24)
     */
    public function insert_record($table, array $fields, $return_id = false){
        $name_fields = '';
        $value_fields = '';
        foreach($fields as $key=>$field){
            $type = 'varchar';
            $name_fields .= $key.',';
            if(is_numeric($field)){
                $type = 'integer';
            }
            $value_fields .= $this->quote($field, $type).',';
        }
        
        $value_fields = rtrim($value_fields, ',');
        $name_fields = rtrim($name_fields, ',');
        
        $sql = "INSERT INTO $table ($name_fields) VALUES($value_fields) ";
        
        $result = $this->DB->query($sql);
        
        if ($this->DB->errno) {
            Connector_relationaldb::throwException($this->DB->error, $this->DB->errno);
            return false;
        }
        
        if($return_id){
            return  $this->DB->insert_id;
        }
        return true;
    }
    
    /**
     * 
     * Delete only one record 
     * @param string $table
     * @param array $condition
     */
    public function delete_record($table, array $condition){
        
        $where = '';
        foreach ($condition as $key=>$value) {
            $type = 'varchar';
            if(is_numeric($value)){
                $type = 'integer';
            }
            $where .= $key . ' = ' . $this->quote($value,$type) . ' AND ';
        }
        
        $where = rtrim($where, ' AND ');
        
        if (!empty($where)) {
            //to modify only one record
            $where .= ' LIMIT 1';
            $sql = "DELETE FROM $table WHERE $where";

            $result = $this->DB->query($sql);
            
            if ($this->DB->errno) {
                Connector_relationaldb::throwException($this->DB->error, $this->DB->errno);
                return false;
            }
            
            return $result;
        }
        
        return false;
    }
	
	public function get_affected_rows() {
		return $this->DB->affected_rows;
	}
}
