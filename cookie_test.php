<?php 

/**
 * cookie �Ĳ���
 * @var unknown_type
 */
define('IN_KEKE', TRUE);
include 'app_boot.php';


// $s = Cookie::set('test_cookie', 'test_cookie_value,asdfasdfasd',3600);
$value = Cookie::get('test_cookie');

var_dump($value);
die();










