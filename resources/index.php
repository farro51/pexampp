<?php
session_start();
include 'setup.php';


if(!empty($_SESSION['imi.timer']) && $_SESSION['imi.timer'] < time() - 600){
    $_SESSION['imi.users'] = null;
}
$_SESSION['imi.timer'] = time();


Restos::$SlashURIs = false;

$rest = new RestGeneric(Restos::$Properties);

$receive  = new RestReceive($rest);
$response = new RestResponse($rest);

$format = $receive->ResourceFormat;
if (!empty($format)) {
    $response->Type = strtoupper($format);
}

//For global communication
$rest->RestReceive    = $receive;
$rest->RestResponse  = $response;

$resource_type = $receive->getPrincipalResource();
if (empty($resource_type)){
    if ($receive->isGet()) {
        $mapping = new RestMapping($rest->getResourceList());
        $response->setContent($mapping->getMapping($response->Type));
    }
    else {
        $response->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('400'));
    }
    $response->send();
    exit;
}

$resource_class = $receive->getPrincipalResourceClass();

if(!class_exists($resource_class)) {
    $response->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('501'));
    $response->send();
    exit;
}

$implemented = $rest->processResources();

if (!$implemented){
    $response->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('501'));
}

$response->send();
exit;

?>