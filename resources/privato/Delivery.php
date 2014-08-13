<?php

	class Delivery {
		public $tracking_code;
		public $delivery_code;
		public $sender_address;
		public $sender_info;
		public $sender_email;
		public $recipient_address;
		public $recipient_info;
		public $recipient_email;
		public $state;
		public $pickup_time;
		public $delivery_time;
		public $recip_sign;
		public $pickup_time_est;
		public $delivery_time_est;
		public $agent;

		function __construct($sender_inf, $sender_email, $sender_add, $recipient_inf, $recipient_email, $recipient_add) {
			$this->sender_address = $sender_add;
			$this->sender_info = $sender_inf;
			$this->sender_email = $sender_email;
			$this->recipient_address = $recipient_add;
			$this->recipient_info = $recipient_inf;
			$this->recipient_email = $recipient_email;
			$this->state = "waiting";
			$this->tracking_code = md5($this->sender_email . time() . $this->sender_address . rand());
			$this->delivery_code = md5($this->recipient_email . time() . $this->recipient_address . rand());
		}
		
		/*public function generateTrackingCode() {
			$this->tracking_code = md5($this->sender_email . time() . $this->sender_address . rand());
		}
		
		public function generateDeliveryCode() {
			$this->delivery_code = md5($this->recipient_email . time() . $this->recipient_address . rand());
		}*/

		public function toString() {
			return "Tracking code : " . $this->tracking_code . "\n" .
					"Delivery code : " . $this->delivery_code . "\n" .
					"Agent id : " . $this->agent . "\n";
		}
	}
?>