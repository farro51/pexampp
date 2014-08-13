<?php

	include 'db/DataBase.php';
	
	class Delivery {
		private $tracking_code;
		private $delivery_code;
		private $sender_address;
		private $sender_info;
		private $sender_email;
		private $recipient_address;
		private $recipient_info;
		private $recipient_email;
		private $state;
		private $submission_time;
		private $pickup_time;
		private $delivery_time;
		private $recip_sign;
		private $pickup_time_est;
		private $delivery_time_est;
		private $agent;

		function __construct($sender_add, $sender_inf, $sender_email, $recipient_add, $recipient_inf, $recipient_email) {
			$this->sender_address = $sender_add;
			$this->sender_info = $sender_inf;
			$this->sender_email = $sender_email;
			$this->recipient_address = $recipient_add;
			$this->recipient_info = $recipient_inf;
			$this->recipient_email = $recipient_email;
			$this->state = "waiting";
		}
		
		public function generateTrackingCode() {
			$this->tracking_code = md5($this->sender_email . time() . $this->sender_address . rand());
		}
		
		public function generateDeliveryCode() {
			$this->delivery_code = md5($this->recipient_email . time() . $this->recipient_address . rand());
		}
		
		public function setAgent($lat_s, $lon_s, $lat_r, $lon_r) {
			$connection = new DataBase();
			$result = $connection->select('agent', 'id', "status='logged' LIMIT 1");
			if($result != null) {
				if($result->num_rows == 1) {
					$row = $result->fetch_assoc();
					$this->agent = $row['id'];
				}
				else {
					$result->close();
					return false;
				}
			}
			else {
				$result->close();
				return false;
			}
			$result->close();
			return true;
		}
		
		public function toString() {
			return "Tracking code : " . $this->tracking_code . "\n" .
					"Delivery code : " . $this->delivery_code . "\n" .
					"Agent id : " . $this->agent . "\n";
		}
	}
?>