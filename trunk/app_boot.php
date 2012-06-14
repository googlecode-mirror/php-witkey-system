<?php
/**
 * this not free,powered by keke-tech
 * @version kppw 2.0
 * @auther ¾Å½­
 * 
 */
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set ( 'PRC' );
define ( "S_ROOT", dirname ( __FILE__ ).DIRECTORY_SEPARATOR);
ini_set('unserialize_callback_func', 'spl_autoload_call');
require (dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'class/keke/core.php');

$exec_time_traver = Keke::exec_js('get');
(!isset($exec_time_traver)||$exec_time_traver<time()) and $exec_time_traver = true or $exec_time_traver = false;

isset($_GET) and extract($_GET);
isset($_POST) and extract($_POST);

isset($inajax) and $_K['inajax']= $_GET['inajax']; 
unset ( $uid, $username);
