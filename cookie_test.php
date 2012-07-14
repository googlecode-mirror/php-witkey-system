<?php 

/**
 * cookie ╣д╡Бйт
 * @var unknown_type
 */
define('IN_KEKE', TRUE);
include 'app_boot.php';


//Cookie::set('test_cookie', 'test_cookie_value',3600);
$value = Cookie::get('test_cookie');
var_dump($value);
die();










