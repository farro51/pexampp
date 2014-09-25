<?php
	class GoogleApi {
		const URL_GOOGLE_GEOCODE = 'http://maps.googleapis.com/maps/api/geocode/json?';
		const URL_GOOGLE_DISTANCE = 'http://maps.googleapis.com/maps/api/distancematrix/json?';
		
		public static function getAddressInfo($address) {
			$url = GoogleApi::URL_GOOGLE_GEOCODE;
			$url .= http_build_query(array('address'=>$address, 'components'=>'locality:Torino', 'sensor'=>'false'));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$geoloc = curl_exec($ch);
			curl_close($ch);
			return $geoloc;
		}
		
		public static function getDistance($origin, $destination) {
			return GoogleApi::curl_get(GoogleApi::URL_GOOGLE_DISTANCE, array('origins'=>$origin, 'destinations'=>$destination, 'mode'=>'walking', 'sensor'=>'false'));
		}
		
		public function curl_post($url, array $post = NULL, array $options = array()){
	    	$defaults = array(
		        CURLOPT_POST => 1,
		        CURLOPT_HEADER => 0,
		        CURLOPT_URL => $url,
		        CURLOPT_FRESH_CONNECT => 1,
		        CURLOPT_RETURNTRANSFER => 1,
		        CURLOPT_FORBID_REUSE => 1,
		        CURLOPT_TIMEOUT => 4,
		        CURLOPT_POSTFIELDS => http_build_query($post)
    		);

		    $ch = curl_init();
		    curl_setopt_array($ch, ($options + $defaults));
		    if( ! $result = curl_exec($ch))
		    {
		        trigger_error(curl_error($ch));
		    }
		    curl_close($ch);
		    return $result;
		}

		/**
		 * Send a GET requst using cURL
		 * @param string $url to request
		 * @param array $get values to send
		 * @param array $options for cURL
		 * @return string
		 */
		public static function curl_get($url, array $get = NULL, array $options = array()) {   
		    $defaults = array(
		        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
		        CURLOPT_HEADER => 0,
		        CURLOPT_RETURNTRANSFER => TRUE,
		        CURLOPT_TIMEOUT => 4
		    );
		    $ch = curl_init();
		    curl_setopt_array($ch, ($options + $defaults));
		    if( ! $result = curl_exec($ch))
		    {
		        trigger_error(curl_error($ch));
		    }
		    curl_close($ch);
		    return $result;
		} 
	}
?>