<?php define ( "IN_KEKE", TRUE );
 
include 'app_boot.php';

 
//var_dump(class_exists('Control_admin_main'));
$sql = "select * from keke_witkey_space where uid =1 ";
//$res = DB::query($sql)->cached(5000)->execute();
$res = DB::select('*')->from('witkey_space')->where(' uid = 1 ')->cached(3600)->execute();
var_dump($res);