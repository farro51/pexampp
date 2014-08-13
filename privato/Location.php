<?php
	class Location {
		private $longitude;
		private $latitude;
		
		function __construct($lon, $lat) {
			$this->longitude = $lon;
			$this->latitude = $lat;
		}
		
		public function getLatitude() {
			return $this->latitude;
		}
		
		public function getLongitude() {
			return $this->longitude;
		}
	}
?>