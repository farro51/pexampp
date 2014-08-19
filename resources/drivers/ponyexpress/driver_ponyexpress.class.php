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

Restos::using('drivers.phpmailer');
Restos::using('privato.GoogleApi');

/**
 * Class Driver_acsql
 *
 * @author Federico Arroyave <farroyave51@gmail.com>
 * @version 0.1
 */
class Driver_ponyexpress {
    
    /**
     * 
     * Properties of the driver, with application level
     * @var object
     */
    private $_properties;
    
    /**
     * 
     * Object to manage SQL conexions and queries 
     * @var Connector_relationaldb
     */
    private $_connection;
    
    /**
     *
     * Table name prefix
     * @var string
     */
    private $_prefix = '';
    
    /**
     * 
     * ConfigFilePath property is required and the file need exist, if not exist an exception is generated
     * 
     * @param object $properties
     * @throws Exception - ConnectionString is required.
     * @throws Exception - Others in PEAR MDB2.
     */
    public function __construct($properties){
        if(!is_object($properties) || !isset($properties->ConnectionString)){
            throw new Exception('ConnectionString is required.');
        }

        $this->_properties = $properties;
        
        $options = array();
        if (!empty($properties->Options)) {
            $options = (array)$properties->Options;
        }

        if (property_exists($properties, 'Prefix')) {
            $this->_prefix = $properties->Prefix;
        }

        Restos::using('data_handlers.relationaldb.connector_relationaldb');
        $connector = new Connector_relationaldb($properties->ConnectionString, $options);
        
        $this->_connection = $connector;
    }
  
    /**
     * 
     * Login
     * @param $username user identifier
     * @param $password password user
	 * @param &$id agent id
     * @return boolean true if can be logged
     */
    public function login($username, $password, $gcm_id, &$id){
		$sql = 'SELECT id FROM ' . $this->_prefix . 'agent WHERE mail=' . $this->_connection->quote($username, 'varchar') 
				. ' AND password=' . $this->_connection->quote($password, 'varchar') . ' AND status=' . $this->_connection->quote('unlogged', 'varchar');
		$agent = $this->_connection->getRow($sql);
		if($agent){
			$id = $agent->id;
			$fields = array('status'=>'logged', 'gmc_id'=>$gcm_id);
			$where = array('id'=>$agent->id);
			return $this->_connection->update_record($this->_prefix.'agent', $fields, $where);
		}
		return false;
    }
    
    /**
     * 
     * Logout
     * @param $username user identifier
     * @return boolean true if success
     */
    public function logout($username){
        $fields = array('status'=>'unlogged');
		$where = array('id'=>$this->_connection->quote($username, 'integer'), 
						'status'=>'logged');
        return $this->_connection->update_record($this->_prefix.'agent', $fields, $where);
    }
    
    public function getQuestions() {
    	$sql = 'SELECT * FROM ' . $this->_prefix . 'question';
		$results = $this->_connection->getList($sql);
		return $results;
    }

    public function updateAgent($fields, $id){
		$where = array('id'=>$this->_connection->quote($id, 'varchar'));
        if ($this->_connection->update_record($this->_prefix.'agent', $fields, $where)) {
			if ($this->_connection->get_affected_rows() > 0) {
				return true;
			}
		}
		return false;
    }
    
	public function sendMail($recipent_email, $title, $body) {
		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "ponyexpress052014@gmail.com";
		$mail->Password = "12378945";
		$mail->SetFrom("ponyexpress052014@gmail.com");
		$mail->Subject = $title;
		$mail->Body = $body;
		$mail->AddAddress($recipent_email);
		if(!$mail->Send()){
			file_put_contents('./log.txt', "Mailer Error: " . $mail->ErrorInfo . PHP_EOL, FILE_APPEND);
			return false;//"Mailer Error: " . $mail->ErrorInfo;
		}
		else{
			return true;
		}
	}

	public function insertDelivery($delivery) {
		$result = false;
        
        $tracking_code = $this->_connection->quote($delivery->tracking_code, 'varchar');
		$delivery_code = $this->_connection->quote($delivery->delivery_code, 'varchar');
		$sender_address = $this->_connection->quote($delivery->sender_address, 'varchar');
		$sender_info = $this->_connection->quote($delivery->sender_info, 'varchar');
		$sender_email = $this->_connection->quote($delivery->sender_email, 'varchar');
		$recipient_address = $this->_connection->quote($delivery->recipient_address, 'varchar');
		$recipient_info = $this->_connection->quote($delivery->recipient_info, 'varchar');
		$recipient_email = $this->_connection->quote($delivery->recipient_email, 'varchar');
		$state = $this->_connection->quote($delivery->state, 'varchar');
		$agent = $this->_connection->quote($delivery->agent, 'integer');
        
        $fields = array('tracking_code'=>$tracking_code, 'delivery_code'=>$delivery_code, 'sender_address'=>$sender_address, 
						'sender_info'=>$sender_info, 'sender_email'=>$sender_email, 'recipient_address'=>$recipient_address, 
						'recipient_info'=>$recipient_info, 'recipient_email'=>$recipient_email, 'state'=>$state, 'agent_id'=>$agent);
        $result = $this->_connection->insert_record($this->_prefix.'delivery',$fields, true);
        return $result;
	}
	
	/**
	 * @todo Realizar una asignacion mas inteligente
	 */
	public function searchAgent($lat_s, $lon_s, $lat_r, $lon_r) {
		//Select the agents logged with their actual position
		$sql = 'SELECT id as id_agent, last_position_lat as latitude, last_position_lon as longitude, ' .
				'0 as arrival_time_est, 0 as p_order from agent where status=' . $this->_connection->quote('logged', 'varchar');
		
		//Select the destinations for the agents logged
		$sql2 = 'SELECT id_agent, longitude, latitude, arrival_time_est, p_order FROM ' . $this->_prefix . 
				'agent, path_agent WHERE status=' . $this->_connection->quote('logged', 'varchar') . ' AND id_agent=id';
		$results = $this->_connection->getList($sql);
		if(!$results) {
			return false;
		}
		if (count($results) == 1) {
			return $results[0]->id_agent;
		}
		$result2 = $this->_connection->getList($sql2);
		if($result2) {
			//Merge the agents actual position with their destinations  
			$results = array_merge($results, $result2);
		}
		
		//Sort the list of agents available for the nearest to sender location
		$agents_sort_s = $this->getListNearAgents($results, $lat_s, $lon_s);
		if (!$agents_sort_s) {
			return false;
		}	
		//Sort the list of agents available for the nearest to recipient location
		$agents_sort_r = $this->getListNearAgents($results, $lat_r, $lon_r);
		if (!$agents_sort_r) {
			return false;
		}
		
		return $this->getBestAgent($agents_sort_s, $agents_sort_r);
		
	}
	
	public function isAddress($address) {
		
		//Get info sender address with google geocode
	    $geoloc = GoogleApi::getAddressInfo($address);
	    $address_dec = json_decode($geoloc);
		if(!in_array("street_address", $address_dec->results[0]->types)) {
			return false;
		}
		return $address_dec;
	}
	
	public function getDistance($origin, $destination) {
		$distance = GoogleApi::getDistance($origin, $destination);
	    $dist_dec = json_decode($distance);
	    if (!$dist_dec || $dist_dec->status != "OK") {
	    	return -1;
	    }
		return $dist_dec->rows[0]->elements[0];
	}
	
	public function getDistanceMatrix($destinations) {
		/*$origins = array();
		foreach ($destinations as $dest) {
			$origins[] = $dest->latitude . "," . $dest->longitude;
		}
		$s_origins = implode("|", $origins);
		$distances = GoogleApi::getDistance($s_origins, $s_origins);
		file_put_contents('./log.txt', var_export("DISTANCES: " .  PHP_EOL . $distances, true) . PHP_EOL, FILE_APPEND);
	    $dist_dec = json_decode($distances);
	    if (!$dist_dec || $dist_dec->status != "OK") {
	    	return false;
	    }
	    $rows = $dist_dec->rows; */
	    $i = 0;
	    $j = 0;
		foreach($destinations as $orig) {
			foreach($destinations as $dest) {
				if ($i == $j) {
	    			$dist_matrix[$i][$j++] = 0;
	    			continue;
	    		}
				$dist = new stdClass();
				$dist->distance = sqrt(pow($dest->latitude - $orig->latitude, 2) + pow($dest->longitude - $orig->longitude, 2));
				$dist->pick_up_coor = $orig->latitude . "," . $orig->longitude;
				$dist->dest_coor = $dest->latitude . "," . $dest->longitude;
				$dist_matrix[$i][$j++] = $dist;
			}
			$j = 0;
			$i++;
		}
	    /*foreach($rows as $row) {
	    	foreach ( $row->elements as $element ) {
	    		if ($i == $j) {
	    			$dist_matrix[$i][$j++] = null;
	    			continue;
	    		}
				$dist = new stdClass();
				$dist->distance = $element->distance->value;
				$dist->time_est = $element->duration->value / 2;
				$dist_matrix[$i][$j++] = $dist;
			}
			$j = 0;
			$i++;
	    }*/
		return $dist_matrix;
	}
	
	public function getListNearAgents($agents, $lat, $lon) {
		$sort_dis = array();
		foreach ( $agents as $agent ) {
       		$dis = sqrt(pow($lat - $agent->latitude, 2) + pow($lon - $agent->longitude, 2));
       		if (array_key_exists($agent->id_agent, $sort_dis)) {
       			if ($sort_dis[$agent->id_agent] > $dis) {
       				$sort_dis[$agent->id_agent] = $dis;
       			}
       		}
       		else {
       			$sort_dis[$agent->id_agent] = $dis;
       		}
		}
		if (count($sort_dis) > 0) {
			if (asort($sort_dis, SORT_NUMERIC)) {
				return $sort_dis;
			}
		}
		return false;
	}
	
	public function getBestAgent($agents_s, $agents_r) {
		$count = count($agents_r);
		$i = 0;
		$agents = array();
		foreach ($agents_s as $agent_id=>$dis) {
			$agents[$agent_id] = $count - $i++;
		}
		$i = 0;
		foreach ($agents_r as $agent_id=>$dis) {
			$agents[$agent_id] += $count - $i++;
		}
		return array_search(max($agents), $agents);
	}
	
	public function getAgentPath($id) {
		$sql = 'SELECT sender_address, recipient_address, latitude, longitude, pick_up, id FROM ' . $this->_prefix . 'path_agent, ' . $this->_prefix . 'delivery WHERE id_agent=' . 
				$this->_connection->quote($id, 'integer') . ' AND id_delivery=id ORDER BY p_order';
		$agent_path = $this->_connection->getList($sql);
		if($agent_path){
			$destinations = array();
			foreach ($agent_path as $loc) {
				$des = new stdClass();
				if ($loc->pick_up == 1) {
					$des->address = $loc->sender_address;
					$type = 'p';
				}
				else {
					$des->address = $loc->recipient_address;
					$type = 'd';
				}
				$des->id_service = $loc->id . $type;
				$des->latitude = $loc->latitude;
				$des->longitude = $loc->longitude;
				$destinations[] = $des;
			}
			return $destinations;
		}
		return false;
	}
	
	public function getAgentListPositions($id, $lat_s, $lon_s, $lat_r, $lon_r) {
		$positions[] = $this->getAgentPosition($id);
		if(!$positions) {
			return false;
		}
		
		$pos_s = new stdClass();
		$pos_s->latitude = $lat_s;
		$pos_s->longitude = $lon_s;
		$pos_s->id_delivery = -1;
		$pos_s->pick_up = 1;
		
		$pos_r = new stdClass();
		$pos_r->latitude = $lat_r;
		$pos_r->longitude = $lon_r;
		$pos_r->id_delivery = -1;
		$pos_r->pick_up = 0;
		
		$destinations = $this->getAgentDestinations($id);
		if (!$destinations) {
			$pos_s->p_order = 1;
			$pos_r->p_order = 2;
			$positions[] = $pos_s;
			$positions[] = $pos_r;
			return $positions;
		}
		$positions = array_merge($positions, $destinations);
	
		$pos_s->p_order = count($positions);
		$pos_r->p_order = count($positions) + 1;
		$positions[] = $pos_s;
		$positions[] = $pos_r;
		return $positions;
	}
	
	public function getAgentDestinations($id) {
		$sql = 'SELECT latitude, longitude, p_order, id_delivery, pick_up, arrival_time_est FROM ' . $this->_prefix . 'path_agent WHERE id_agent=' . 
				$this->_connection->quote($id, 'integer') . ' ORDER BY p_order';
		return $this->_connection->getList($sql);
	}
	
	public function getAgentPosition($id) {
		$sql = 'SELECT last_position_lat as latitude, last_position_lon as longitude, 0 as p_order, 0 as id_delivery, 0 as pick_up FROM ' . 
				$this->_prefix . 'agent WHERE id=' . $this->_connection->quote($id, 'integer');
		return $this->_connection->getRow($sql);
	}
	
	public function getAgentOrderPath($dist_matrix, $destinations) {
		$avail = array();
		for( $i = 0; $i < count($destinations); $i++ ) {
			if ($destinations[$i]->pick_up == 1) {
				$avail[$i] = true;
			}
			else {
				$avail[$i] = false;
			}
		}

		$tour = array(0);//new int[distancesMatrix.length];
		$closest = -1;
		for( $i = 1; $i < count($destinations); $i++ ) {
			$dist = 10000000;
			for( $j = 0; $j < count($destinations); $j++ ) {
				if ($tour[$i-1] == $j) {
					continue;
				}
				if( $avail[$j] && $dist_matrix[$tour[$i-1]][$j]->distance < $dist ){   
					$dist = $dist_matrix[$tour[$i-1]][$j]->distance;
					$closest = $j;
				}   // end if: new nearest neighbor
			}
			$tour[$i] = $closest;
			$avail[$closest] = false;
			if ($destinations[$closest]->pick_up == 1) {
				for( $k = 0; $k < count($destinations); $k++ ) {
					if ($k != $closest) {
						if ($destinations[$k]->id_delivery == $destinations[$closest]->id_delivery) {
							$avail[$k] = true;
							break;
						}
					}
				}
			}
		}
		return $tour;
	}
	
	public function insertPathAgent($destinations, $dist_matrix, $order_path, $agent_id) {
		$arr_est = 0;
		/*$fields = array('p_order'=>0, 'arrival_time_est'=>0);
		$where = array('id_agent'=>$agent_id);
		$this->_connection->DB->query("DELETE FROM " . $this->_prefix.'path_agent' . " WHERE id_agent=" . $agent_id);*/
		for($i = 1; $i < count($destinations); $i++) {
			$distance = $this->getDistance($dist_matrix[$order_path[$i-1]][$order_path[$i]]->pick_up_coor, $dist_matrix[$order_path[$i-1]][$order_path[$i]]->dest_coor);
			if(!$distance) {
				return false;
			}
			file_put_contents('./log.txt', "DISTANCE: " . $dist_matrix[$order_path[$i-1]][$order_path[$i]]->pick_up_coor . " " .
								$dist_matrix[$order_path[$i-1]][$order_path[$i]]->dest_coor . PHP_EOL . var_export($distance, true) . PHP_EOL, FILE_APPEND);
			$arr_est += $distance->duration->value;
			
			if ($order_path[$i] < (count($destinations) - 2)) {
				$fields = array('p_order'=>$i, 'arrival_time_est'=>$arr_est);
				$where = array('id_delivery'=>$destinations[$order_path[$i]]->id_delivery, 'pick_up'=>$destinations[$order_path[$i]]->pick_up);
		        if (!$this->_connection->update_record($this->_prefix.'path_agent', $fields, $where)) {
					return false;
				}
			}
			else {
				$fields = array('id_agent'=>$agent_id, 'p_order'=>$i, 'id_delivery'=>$destinations[$order_path[$i]]->id_delivery, 
					'pick_up'=>$destinations[$order_path[$i]]->pick_up, 'latitude'=>$destinations[$order_path[$i]]->latitude, 
					'longitude'=>$destinations[$order_path[$i]]->longitude, 'arrival_time_est'=>$arr_est);
				if(!$this->_connection->insert_record($this->_prefix.'path_agent',$fields)) {
					return false;
				}
			}
		}
		return true;
	}
	
	public function getTrackingInfo($code) {
		/*$sql = 'SELECT pickup_time, delivery_time, pickup_time_est, delivery_time_est, state, last_position_lat, ' .
				'last_position_lon, sender_address, recipient_address FROM ' . $this->_prefix . 'delivery, ' . $this->_prefix . 
				'agent as a WHERE agent_id=a.id AND tracking_code=' . $this->_connection->quote($code, 'integer');*/
		$sql = 'SELECT pickup_time, delivery_time, pickup_time_est, delivery_time_est, state FROM ' . 
				$this->_prefix . 'delivery WHERE tracking_code=' . $this->_connection->quote($code, 'varchar');
		$result = $this->_connection->getRow($sql);
		if($result) {
			$tracking_info = new stdClass();
			$tracking_info->state = $result->state;
			if ($tracking_info->state == "waiting") {
				$tracking_info->pickup_time_est = gmdate("Y-m-d H:i:s", $result->pickup_time_est + (30*60));
				return $tracking_info;
			}
			if ($tracking_info->state == "delivering") {
				$tracking_info->pickup_time = $result->pickup_time;
				$tracking_info->delivery_time_est = gmdate("Y-m-d H:i:s", $result->delivery_time_est + (30*60));
				return $tracking_info;
			}
			if ($tracking_info->state == "delivered") {
				$tracking_info->pickup_time = $result->pickup_time;
				$tracking_info->delivery_time = $result->delivery_time;
				return $tracking_info;
			}
		}
		return false;
	}
	
	public function reachedDestination($code, $type, $state, $time_type) {
		$sql = 'SELECT id, agent_id, arrival_time_est FROM ' . $this->_prefix . 'delivery, ' . $this->_prefix . 'path_agent WHERE ' . $type . '=' . 
				$this->_connection->quote($code, 'varchar') . ' AND id=id_delivery AND p_order=1';
		$result = $this->_connection->getRow($sql);
		if($result) {
	        $where = array('id_agent'=>$result->agent_id, 'p_order'=>1);
            if($this->_connection->delete_record($this->_prefix.'path_agent',$where)) {
		        $sql = 'UPDATE path_agent SET arrival_time_est = (arrival_time_est - ' . $result->arrival_time_est . '), p_order=(p_order - 1) WHERE id_agent=' . $result->agent_id;
		        $this->_connection->DB->query($sql);
				$where = array('id'=>$result->id);
				$fields = array($time_type=>time(), 'state'=>$state);
				if ($this->_connection->update_record($this->_prefix.'delivery', $fields, $where)) {
					if ($this->_connection->get_affected_rows() > 0) {
						return $result->id;
					}
				}
            }
		}
		return -1;
	}
	
	public function saveFeedback($info) {
		$where = array('id'=>$info->id_delivery);
		$fields = array('recip_sign'=>$info->sign);
        if ($this->_connection->update_record($this->_prefix.'delivery', $fields, $where)) {
			if ($this->_connection->get_affected_rows() > 0) {
				foreach($info->questions as $quest) {
					$fields = array('questionnaire_id'=>$info->id_delivery, 'vote'=>$quest->vote, 'question_id'=>$quest->question_id);
					if(!$this->_connection->insert_record($this->_prefix.'question_response',$fields)) {
						return "Database error. Insert question";
					}
				}
			}
			else {
				return "Database error. On update sign";
			}
		}
		return true;
	}
	
	public function updatePathAgent($path, $id_agent) {
		$locations[] = $this->getAgentPosition($id_agent);
		if(!$locations[0]) {
			return "Can't find agent actual position";
		}
		$destinations = $this->getAgentDestinations($id_agent);
		$sinc_path = $this->checkPathAgent($path, $destinations);
		if(is_array($sinc_path)) {
			$equal = true;
			$time = 0;
			$locations = array_merge($locations, $sinc_path);
			$dest_emails = $this->getDestinationEmails($id_agent);
			for($i = 0; $i < count($path); $i++) {
				if(($path[$i]->id_delivery == $destinations[$i]->id_delivery) && ($path[$i]->type == $destinations[$i]->pick_up) && $equal) {
					continue;
				}
				if($equal && $i > 0) {
					$time = $destinations[$i-1]->arrival_time_est;
				}
				$equal = false;
				$dist = $this->getDistance($locations[$i]->latitude . ',' . $locations[$i]->longitude,
									$locations[$i + 1]->latitude . ',' . $locations[$i + 1]->longitude);
				$time += $dist->duration->value / 2;
				$where = array('id_delivery'=>$path[$i]->id_delivery, 'pick_up'=>$path[$i]->type);
				$fields = array('arrival_time_est'=>$time, 'p_order'=>($i + 1));
				if (!$this->_connection->update_record($this->_prefix . 'path_agent', $fields, $where)) {
					return "Database error";
				}
				$rec_email = null;
				foreach($dest_emails as $email) {
					if($email->id == $path[$i]->id_delivery) {
						$rec_email = $email->recipent_email;
					}
				}
				if($rec_email != null) {
					$title = 'Delivery expected time was changed';
					$body = "Hi, this email is automatically send to you from ponyexpress.com because the delivery was updated.
						</br> The new delivery expected time is: " . gmdate("Y-m-d H:i", (time() + $time + (30*60))) . "<br/><br/>";
					if(!$this->sendMail($rec_email, $title, $body)) {
						return "Error to send email to $rec_email";
					}
				}
				else {
					return "Problem to find recipient email";
				}
				
				/*$dist->distance = $element->distance->value;
				$dist->time_est = $element->duration->value / 2;*/
				//if ($this->_connection->update_record($this->_prefix.'delivery', $fields, $where)) {}
			}
			return true;
		}
		return $sinc_path;
	}
	
	public function checkPathAgent($path, $actual_path) {
		if(count($path) != count($actual_path)) {
			return "The new path has a different number of destinations that the actual path";
		}
		$sinc_path = array();
		if($path[count($path)-1]->type == 1) {
			return "Wrong path, the last destination can't be a pick_up";
		}
		$sinc_path = $this->isInActualPath($actual_path, $path);
		if (!$sinc_path) {
			return "A destination from the new path there is not in the actual path";
		}
		for($i = 0; $i < count($path); $i++) {
			$found = false;
			for($j = $i + 1; $j < count($path); $j++) {
				if($path[$i]->type == 0) {
					if($path[$j]->id_delivery == $path[$i]->id_delivery) {
						return "There is another destination for the delivery " . $path[$j]->id_delivery . " after the delivery";
					}
					$found = true;
				}
				else {
					if($path[$j]->id_delivery == $path[$i]->id_delivery) {
						if(($path[$j]->type == 0) && !$found){
							$found = true;
						}
						else {
							return "There is another destination for the delivery " . $path[$j]->id_delivery . " after the delivery";
						}
					}
				}
			}
			if(!$found && ($i < count($path) - 1)){
				return "The package was not delivered";
			}
		}
		return $sinc_path;
	}
	
	public function isInActualPath($actual_path, $new_path) {
		$found = false;
		$sinc_path = array();
		for($i = 0; $i < count($new_path); $i++) {
			for($j = 0; $j < count($actual_path); $j++) {
				if(($actual_path[$j]->id_delivery == $new_path[$i]->id_delivery) && ($actual_path[$j]->pick_up == $new_path[$i]->type)){
					$sinc_path[$i] = $new_path[$i];
					$actual_path[$j]->id_delivery = 0;
					$actual_path[$j]->pick_up = -1;
					$found = true;
					break;
				}
			}
			if (!$found) {
				return $found;
			}
			$found = false;
		}
		return $sinc_path;
	}
	
	public function getDestinationEmails($id_agent) {
		$sql = "SELECT id, recipient_email FROM " . $this->_prefix . "delivery WHERE agent_id=" . $id_agent .
				" AND state!='delivered'";
		return $this->_connection->getList($sql);
	}
}
