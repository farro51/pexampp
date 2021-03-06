<?php
	$hola;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pony Express Bike</title>
<meta name="keywords" content="Pony Express, Delivery, Bike, Torino" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link href="templatemo_style.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="scripts/swfobject/swfobject.js"></script>
    <script type="text/javascript">
      var flashvars = {};
      flashvars.cssSource = "css/piecemaker.css";
      flashvars.xmlSource = "piecemaker.xml";
		
      var params = {};
      params.play = "true";
      params.menu = "false";
      params.scale = "showall";
      params.wmode = "transparent";
      params.allowfullscreen = "true";
      params.allowscriptaccess = "always";
      params.allownetworking = "all";
	  
      swfobject.embedSWF('piecemaker.swf', 'piecemaker', '960', '500', '10', null, flashvars,    
      params, null);
    
    </script>

<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" />
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.10.3.custom.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="js/myScripts.js"></script>
<script type="text/javascript" src="scripts/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "templatemo_menu", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<style>
  .ui-progressbar {
    position: relative;
  }
  .progress-label {
    position: absolute;
    left: 50%;
    top: 4px;
    font-weight: bold;
    text-shadow: 1px 1px 0 #fff;
  }
  .ui-dialog-titlebar-close {
    display: none;
  }
  </style>
</head>
<body>

<div id="templatemo_wrapper">

	<div id="templatemo_header">
    
    	<div id="site_title"><h1><a href="http://localhost/ponyexpress/" target="_parent">Home</a></h1></div>
        
        <div id="templatemo_menu" class="ddsmoothmenu">
            <ul>
              	<li><a href="index.php" class="selected">Home</a></li>
          		<li><a id="link_about" href="#">About</a>
              	</li>
          		<li><a id="link_services" href="#">Services</a>
           	  </li>
              	<li><a id="link_tracking" href="#">Tracking</a>
                </li>
              	<li><a id="link_contact" href="#">Contact</a></li>
            </ul>
            <br style="clear: left" />
        </div> <!-- end of templatemo_menu -->
        
    </div> <!-- end of header -->
    
    <div id="templatemo_fw">
        <div id="piecemaker">
          <p>This is a placeholder of 3D Flash Slider. Feel free to put in any alternative content here.</p>
        </div>
	</div>
    
    <div id="templatemo_main">
    	<div class="col_fw">
        	<div class="col_w460 float_l">
           	  <h2>Un po' di Pony Express</h2>
                <img src="img/templatemo_image_01.png" alt="image 01" class="float_l" />
                <img src="img/templatemo_image_02.jpg" alt="image 02" height="178" class="float_r" />
                <div class="cleaner h20">
                  <p>&nbsp;</p>
                  <p>Da qualche anni <b>Pony Express</b> consegna buste, pacchi e plichi con rapidità e sicurezza a Torino e dintorni. I nostri clienti richiedono la consegna entro al massimo un'ora dal momento della loro chiamata e per questo motivo, oltre </span><span class="cf1 fs22 ff1">ad utilizzare un servizio computerizzato, con la possibilità di prenotare i ritiri online, ci avvaliamo di personale altamente selezionato, sempre in contatto con cellulare aziendale e approvato solo dopo attenta verifica. Affidarci un plico significa metterlo in mani sicure..</p>
                  <p><span class="post_inner"><a id="link_more" href="#" class="more float_r"></a></span><br />
                  </p>
              </div>
                <div class="cleaner h20"></div>
</div>
            <div class="col_w460 float_r">
            	<h2><img src="img/tracking-packages.jpg" width="379" height="377" /></h2>
            	<div class="col_w460">          	  </div>
            </div>
            <div class="cleaner"></div>
		</div>
    	<div class="cleaner"></div>
        </div>
    </div>
    
    <div class="cleaner"></div>
	<div id="dialog"></div>

<div id="templatemo_footer_wrapper">
    <div id="templatemo_footer">
        Copyright © 2013 - <a href="#">Pony Express </a>
        <div class="cleaner"></div>
    </div>
</div> 
</body>
</html>