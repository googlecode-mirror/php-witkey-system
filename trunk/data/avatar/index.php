<?php  define('IN_KEKE', 1);
//Ucenter ͷ�����
include '../../app_boot.php';
$a = $_GET['a'];
if($a){
	$method = $a;
	$uid = $_GET['input'];
	$class = new Keke_user_avatar();
	echo $data=$class->$method($uid);
}else{
	exit('�ϴ���������!');
}