<?php

/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-27早上9:55:00
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$page_title=$_lang['login'].'- '.$_K['html_title'];
$uid and header ( "location:index.php" ); 
$open_api_arr = Keke::$_api_open;
$api_name = keke_global_class::get_open_api();
//初始化对象 
$login_obj = new keke_userlogin();  
$inter = Keke::$_sys_config ['user_intergration'];
if(isset($log_remember)){
	setcookie('log_account',$txt_account,time()+3600*24*30);
}else{
	if(isset($_COOKIE['log_account'])){
		setcookie('log_account','');
	} 
}

if (Keke::formcheck(isset($formhash))|| isset($login_type) ==3) {
	//登录之前的地址
	 isset($hdn_refer) and $_K['refer'] = $hdn_refer;  
	 $txt_code = isset($txt_code)?$txt_code:"";
	 $login_type = isset($login_type)?$login_type:"";
	 $ckb_cookie = isset($ckb_cookie)?$ckb_cookie:"";
	//用户登录 
 	$user_info = $login_obj->user_login($txt_account, md5($pwd_password),$txt_code,$login_type); 
 	//存储用户信息 
 	
	$login_obj->save_user_info($user_info, $ckb_cookie,$login_type); 
}
require  Keke_tpl::template ( $do );