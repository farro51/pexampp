<?php

	include 'GoogleApi.php';
	
	class Agent {
		private $id;
		private $name;
		private $phone;
		private $status;
		private $last_position;
		private $last_update;
		
		function __construct($id_a, $last_pos) {
			$this->id = $id_a;
			$this->last_position = $last_pos;
		}
		
		/**
			$destinations Vector of Locations to visit
			return array with the times in seconds for go from a place to another
		*/
		public function estimaTimes($destinations) {
			$preview_loc = $this->last_position;
			$times = array();
			$i = 0;
			foreach($destinations as $des) {
				$origin = $preview_loc->getLatitude() . "," . $preview_loc->getLongitude();
				$destination = $des->getLatitude() . "," . $des->getLongitude();
				$distance = json_decode(GoogleApi::getDistance($origin, $destination));
				$times[$i] = $distance->rows[0]->elements[0]->duration->value / 4;
				$i++;
				$preview_loc = $des;
			}
			return $times;
		}
	}
?>