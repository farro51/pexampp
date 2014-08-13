<?php
	class GoogleApi {
		//public static final $URL_GOOGLE_GEOCODE = 'http://maps.googleapis.com/maps/api/geocode/json?';
		//public static final $URL_GOOGLE_DISTANCE = 'https://maps.googleapis.com/maps/api/distancematrix/json?';
		
		public static function getAddressInfo($address) {
			$url = 'http://maps.googleapis.com/maps/api/geocode/json?';
			$url .= http_build_query(array('address'=>$address, 'components'=>'locality:Torino', 'sensor'=>'false'));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$geoloc = curl_exec($ch);
			curl_close($ch);
			return $geoloc;
		}
		
		public static function getDistance($origin, $destination) {
			$url = 'https://maps.googleapis.com/maps/api/distancematrix/json?';
			$url .= http_build_query(array('origins'=>$origin, 'destinations'=>$destinations, 'mode'=>'walking', 'sensor'=>'false'));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$distance = curl_exec($ch);
			curl_close($ch);
			return $distance;
		}
	}
?>