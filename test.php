<?php define ( "IN_KEKE", TRUE );
 
include 'app_boot.php';


$user_info = keke_user_class::get_user_info(1);
var_dump($user_info);

 
//var_dump(class_exists('Control_admin_main'));
