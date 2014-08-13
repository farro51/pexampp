	<?php
	/**
		Class DataBase per la gestione della base di dati, 
		si ha provato fare un modello singleton nella connessione
	*/
	
	define("SERVER_DB", 'localhost');
	define("USER_NAME_DB", 'ponyedb');
	define("PASSWORD_DB", 'Federico01#');
	define("DB", 'pony_mayo_11');
	
	class DataBase{
		public static $_connection;		
		
		public function __construct() {
		    if((!self::$_connection)||(!isset(self::$_connection))){
				$this->connect();
			}
	    }
		
		public function connect(){
			if(!self::$_connection){
				self::$_connection = new mysqli(SERVER_DB, USER_NAME_DB, PASSWORD_DB, DB);
				if(!self::$_connection){
					die('Errore nella connessione('.mysqli_connect_errno().')'.mysqli_connect_error());
				}
			}
		}
		
		public function insert($table, $fields, $values){
			if(!self::$_connection){
				$this->connect();
			}
			$query = "INSERT INTO " . $table . " (" . $fields . ") VALUES(" . $values . ");";
			return self::$_connection->query($query);
		}
		
		public function select($table, $fields = "*", $conditions = null, $distinct = false){
			if(!self::$_connection){
				$this->connect();
			}
			$query = "SELECT ";
			if($distinct) {
				$query .= "DISTINCT ";
			}
			$query .= $fields . " FROM " . $table;
			if($conditions != null){
				$query .= " WHERE " . $conditions . ";";
			}
			$result = self::$_connection->query($query);
			return $result;
		}
		
		public function update($table, $updFields, $conditions){
			if(!self::$_connection){
				$this->connect();
			}
			$query = "UPDATE " . $table . " SET " . $updFields;
			if($conditions != null){
				$query .= " WHERE " . $conditions;
			}
			return self::$_connection->query($query);
		}
		
		public function delete($table, $conditions){
			if(!self::$_connection){
				$this->connect();
			}
			$query = "DELETE FROM " . $table . " WHERE " . $conditions;
			return self::$_connection->query($query);
		}
		
		public function myQuery($query){
			return self::$_connection->query($query);
		}
	}
?>