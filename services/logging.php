<?php
	$is_auth = false;
	if (isset($_GET['username']) && isset($_GET['password']) && is_numeric(isset($_GET['username']) && preg_match)) {
		$user = $_GET['username'];
		$password = $_GET['password'];
		if (intval($user)) {
			
		include 'db/Database.php';
		$connection = new DataBase();
		$result = $connection->select('agent', 'id', "id=" . $_GET['username'] . " AND LIMIT 1");
		if($result != null) {
			if($result->num_rows == 1) {
			}
		}
	}
	}
	
?>