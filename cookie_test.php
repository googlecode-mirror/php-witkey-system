<?php 

/**
 * cookie µÄ²âÊÔ
 * @var unknown_type
 */
define('IN_KEKE', TRUE);
include 'app_boot.php';
$response = Keke_response::getinstance()->send_headers();
$request = Keke_request::heades();
var_dump($response,$request);




die();










