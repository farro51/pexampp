$(document).ready(function(){
	$('#link_about, #link_more').click(function() {
		$('#templatemo_menu a').removeClass("selected");
		$('#link_about').addClass("selected");
		$('#templatemo_fw').hide();
		$('#templatemo_main').load('about.html');
	});
	
	$('#link_services').click(function() {
		$('#templatemo_menu a').removeClass("selected");
		$(this).addClass("selected");
		$('#templatemo_fw').hide();
		$('#templatemo_main').load('services.php');
	});
	
	$('#link_tracking').click(function() {
		$('#templatemo_menu a').removeClass("selected");
		$(this).addClass("selected");
		$('#templatemo_fw').hide();
		$('#templatemo_main').load('tracking.php');
	});
	
	$('#link_contact').click(function() {
		$('#templatemo_menu a').removeClass("selected");
		$(this).addClass("selected");
		$('#templatemo_fw').hide();
		$('#templatemo_main').load('contact.html');
	});
	
	$('#send_delivery_b').live('click', function() {

		var name_s = $('#name_sender_t').val();
		var email_s = $('#email_sender_t').val();
		var address_s = $('#address_sender_t').val();
		var name_r = $('#name_recipient_t').val();
		var email_r = $('#email_recipient_t').val();
		var address_r = $('#address_recipient_t').val();
		
		if((name_s == "") || (email_s == "") || (address_s == "") || (name_r == "") || 
			(email_r == "") || (address_r == "") || !isNaN(name_s) || !isNaN(email_s) || 
			!isNaN(address_s) || !isNaN(name_r) || !isNaN(email_r) || !isNaN(address_r)) {
			showMess("Insert all data");
			return false;
		}
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!email_s.match(regex)) {
			showMess("Sender's email wrong");
			return false;
		}
		if(!email_r.match(regex)) {
			showMess("Recipient's email wrong");
			return false;
		}
		/*if(!validateAddress(address_s)) {
			showMess("Not find sender's address");
			return false;
		}
		
		if(!validateAddress(address_r)) {
			showMess("Not find recipient's address");
			return false;
		}*/
		var params = "address_s=" + address_s + "&address_r=" + address_r;
		if(window.XMLHttpRequest){
			req = new XMLHttpRequest();
		}
		else{ 
			req = new ActiveXObject("Microsoft.XMLHTTP");
		}
		req.onreadystatechange=function(){
			if(req.readyState==4 && (req.status==200 || req.status==0)){
				var obj = jQuery.parseJSON(req.responseText);
				if (obj.successful == true) {
					$('#dialog').html("").append("The delivery price is: " + (obj.distance/1000) + " euros.</b>" +
								"Do you want to proceed?");
					/*$('#dialog').html("").append("The tracking code is: <b>" + obj.tracking_code +
													"</b><br>Sended too to the email provided: " + email_s);*/
					$('#dialog').dialog({
					   title: "Successful transaction",
					   height: 200,
					   width: 400,
					   closeOnEscape: false,
					   resizable: false,
					   modal: true,
					   buttons: [
						{
							text: "OK",
							click: function() {
								$('#dialog').html("").append("<div id=\"progressbar\"><div class=\"progress-label\">Loading...</div></div>");
								var progressbar = $( "#progressbar" ),
								progressLabel = $( ".progress-label" );
							    $('#dialog').dialog( "option", "buttons", [] );
								progressbar.progressbar({
									value: false
								});
								var params = "name_s=" + name_s + "&email_s=" + email_s + "&address_s=" + address_s + 
												"&name_r=" + name_r + "&email_r=" + email_r + "&address_r=" + address_r;
								if(window.XMLHttpRequest){
									req2 = new XMLHttpRequest();
								}
								else{ 
									req2 = new ActiveXObject("Microsoft.XMLHTTP");
								}
								req2.onreadystatechange=function(){
									if(req2.readyState==4 && (req2.status==200 || req2.status==0)){
										progressbar.progressbar("destroy");
										alert(req2.responseText);
										var obj = jQuery.parseJSON(req2.responseText);
										
										if (obj.successful == true) {
											$('#dialog').html("").append("The tracking code is: <b>" + obj.tracking_code +
																			"</b><br>Sended too to the email provided: " + email_s);
										}
										else {
											$('#dialog').html("").append("Error: " + obj.message);
										}
										$('#dialog').dialog({  
											   title: "Successful transaction",
											   height: 200,
											   width: 400,
											   modal: true,
											   buttons: [
													{
														text: "OK",
														click: function() {
															$( this ).dialog( "close" );
														}
													}
												]
											});
										$('#name_sender_t').val("");
										$('#email_sender_t').val("");
										$('#address_sender_t').val("");
										$('#name_recipient_t').val("");
										$('#email_recipient_t').val("");
										$('#address_recipient_t').val("");
									}
									else{
										if(req2.readyState==4) {
											progressbar.progressbar("destroy");
											$('#dialog').html("").append("Server error");
											$('#dialog').dialog( "option", "buttons", [
												{
														text: "OK",
														click: function() {
															$( this ).dialog( "close" );
														}
													}
											] );
										}
									}
								}
								req2.open("POST","resources/deliveries.json",true);
								req2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								req2.send(params);
							}
						},
						{
							text: "Cancel",
							click: function() {
								$( this ).dialog( "close" );
							}
						}
					  ]
					});
				}
				else {
					$('#dialog').html("").append("Error: " + obj.message);
					$('#dialog').dialog({  
					   title: "Successful transaction",
					   height: 200,
					   width: 400,
					   modal: true,
					   buttons: [
							{
								text: "OK",
								click: function() {
									$( this ).dialog( "close" );
								}
							}
						]
					});
				}
				
			}
		}
		req.open("POST","resources/deliveries/distance.json",true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send(params);
	});
	
	$('#send_tracking_b').live('click', function() {
		var code = $('#tracking_code_t').val();
		if(code == "") {
			showMess("Insert tracking code");
			return false;
		}
		var params = "code=" + code;
		
		if(window.XMLHttpRequest){
			req = new XMLHttpRequest();
		}
		else{ 
			req = new ActiveXObject("Microsoft.XMLHTTP");
		}
		req.onreadystatechange=function(){
			if(req.readyState==4 && (req.status==200 || req.status==0)){
				var obj = jQuery.parseJSON(req.responseText);
				if (obj.successful == true) {
					if (obj.tracking_info.state == "waiting") {
						$('#dialog').html("").append("The delivery's status is: <b>to be picked up</b><br>" +
								"The estimated pick up time is: " + obj.tracking_info.pickup_time_est);
					}
					if (obj.tracking_info.state == "delivering") {
						$('#dialog').html("").append("The delivery's status is: <b>" + obj.tracking_info.state + "</b><br>" +
								"The pick up time was: " + obj.tracking_info.pickup_time + "<br>" +
								"The estimated delivery time is: " + obj.tracking_info.delivery_time_est);
					}
					if (obj.tracking_info.state == "delivered") {
						$('#dialog').html("").append("The delivery's status is: <b>" + obj.tracking_info.state + "</b><br>" +
								"The pick up time was: " + obj.tracking_info.pickup_time + "<br>" +
								"The delivery time was: " + obj.tracking_info.delivery_time);
					}
				}
				else {
					$('#dialog').html("").append("Invalid tracking code");
				}
				$('#dialog').dialog({  
					   title: "Result",
					   height: 200,
					   width: 400,
					   buttons: [
							{
								text: "OK",
								click: function() {
									$( this ).dialog( "close" );
								}
							}
						],
					   modal: true
			   });
				$('#tracking_code_t').val("");
			}
		}
		req.open("POST","resources/deliveries/tracking.json",true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send(params);
	});
});

function showMess(mess) {
	$('#dialog').html("").append(mess);
	$('#dialog').dialog({  
			   title: "Validazione",
			   height: 200,
			   width: 200,
			   buttons: [
					{
						text: "OK",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				],
			   modal: true
			   
    });
}

function newDelivery(name_s, email_s, address_s, name_r, email_r, address_r) {
	
}

/*function validateAddress(address) {
	params = { address: address, components : 'locality:Torino', sensor: 'false' };
	params = $.param(params);
	if(window.XMLHttpRequest){
		req = new XMLHttpRequest();
	}
	else{ 
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	req.onreadystatechange=function(){
		if(req.readyState==4 && (req.status==200 || req.status==0)){
			var obj;
			try {
				obj = $.parseJSON(req.responseText);
			}catch(err) {
				alert("Malformed JSON\n" + err.message);
				ctrl = 1;
			}
			if(obj.results[0].types.indexOf("street_address") > 0) {
				ctrl = 2;
			}
			else {
				ctrl = 1;
			}
		}
	}
	req.open("GET","http://maps.googleapis.com/maps/api/geocode/json?" + params,true);
	req.send(null);
	var i = 0;
	while(ctrl = 0) {
		i++;
	}
	alert(i);
	if(ctrl == 1) {
		ctrl = 0;
		return false;
	}
	else {
		ctrl = 0;
		return true;
	}
}*/