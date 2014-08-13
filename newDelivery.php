<?php

	include 'privato/Delivery.php';
	include 'privato/GoogleApi.php';
	
	//Get info sender address with google geocode
    $geoloc = GoogleApi::getAddressInfo($_POST['address_s']);
    $address_sender = json_decode($geoloc);
	if(!in_array("street_address", $address_sender->results[0]->types)) {
		echo "Sender address not found";
		return;
	}
	
	//Get info recipient address with google geocode
	$geoloc = GoogleApi::getAddressInfo($_POST['address_s']);
	$address_recipient = json_decode($geoloc);
	if(!in_array("street_address", $address_recipient->results[0]->types)) {
		echo "Recipient address not found";
		return;
	}
	
	$delivery = new Delivery($_POST['name_s'], $_POST['email_s'], $_POST['address_s'], 
								$_POST['name_r'], $_POST['name_r'], $_POST['name_r']);
								
	$lat_s = $address_sender->results[0]->geometry->location->lat;
	$lon_s = $address_sender->results[0]->geometry->location->lng;
	$lat_r = $address_recipient->results[0]->geometry->location->lat;
	$lon_r = $address_recipient->results[0]->geometry->location->lng;
	if(!$delivery->setAgent($lat_s, $lon_s, $lat_r, $lon_r)) {
		echo "There aren't avaliable agents";
		return;
	}
	$delivery->generateTrackingCode();
	$delivery->generateDeliveryCode();
	echo $delivery->toString();
?>