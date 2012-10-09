<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-27早上9:55:00
 */
class Control_ologin extends Controller{
	function action_index(){
		global $_K,$_lang;
		$api_open = unserialize($_K['oauth_api_open']);
		$api_name = keke_global_class::get_open_api();
		$type = $_GET['type'];
		if($type){
			Keke_oauth_weibo::instance($type)->get_login_info();
		}
		require Keke_tpl::template("oauth_login");
	}
	
	function action_login(){
		 global $_K,$ouri,$code;
	     $type = $_GET['type'];
	     $ouri = $_K['website_url'].'/index.php/ologin/login?back=1&type='.$type;
	     if($_GET['back']){
 	     	$code = $_GET['code'];
	     	Keke_oauth_weibo::instance($type)->get_access_token();
	     	header('Location:'.$_K['website_url'].'/index.php/ologin?type='.$type);
 	     	die;
 	     }else{
 	     	$to_url =  Keke_oauth_weibo::instance($type)->get_auth_url($ouri);
 	     	header("Location:".$to_url);
 	     	die;
 	     }
	    
 	     	
	}
	
}

/* $type or exit(Keke::show_msg($_lang['oprerate_notice'],"index.php?do=login",2,$_lang['type_no_empty'],"warning"));
$page_title=$_lang['login'].'- '.$_K['html_title'];
// 初始化信息
$oa = new keke_oauth_login_class ( $type );
$api_name = keke_global_class::get_open_api();
$login_obj = new keke_user_login_class ();
$oauth_obj = new Keke_witkey_member_oauth_class ();
$oauth_url = Keke::$_sys_config ['website_url'] . "/index.php?do=$do&type=$type";
//获取登录平台
$oauth_type_arr = keke_global_class::get_oauth_type ();

//oauth登录
if ($type && ! $_SESSION ['auth_' . $type] ['last_key']) {
	if ($type=='sina' && $error_code=='21330'){//当用户在sina平台上拒绝oauth登陆时,给出提示
		Keke::show_msg($_lang['notice_message'], Keke::$_sys_config ['website_url'].'/index.php?do=login',1,$_lang['login_in_fail'],"alert_right");
	}
	$oauth_vericode = $oauth_vericode;
	$oa->login ( $call_back, $oauth_url );
} else {
	$oauth_user_info = $oa->get_login_user_info ();
}
//var_dump($oauth_user_info);
//echo var_dump(Keke::submitcheck ($formhash ));die();
//oauth 绑定判断
$bind_info = keke_register_class::is_oauth_bind ( $type, $oauth_user_info ['account'] );
if ($oauth_user_info && $bind_info = keke_register_class::is_oauth_bind ( $type, $oauth_user_info ['account'] )) {
	$user_info = Keke::get_user_info ( $bind_info ['uid'] );
	$login_user_info = $login_obj->user_login ( $user_info ['username'], $user_info ['password'], null, 1 );
	$login_obj->save_user_info ( $login_user_info, 1 );

}
if (Keke::submitcheck($formhash)) {
	$login_user_info = $login_obj->user_login ( $txt_account,md5($pwd_password) , $txt_code, 1 );
	keke_register_class::register_binding ( $oauth_user_info, $login_user_info, $type );
	$login_obj->save_user_info ( $login_user_info, 1 );

	//联盟
/*	if ($unit) {
		$unit_obj = new kk_client ( $app_key, $app_secret );
		$task_url = $unit_obj->clientlogin ( $login_user_info ['uid'] );
		keke_function_class::curl_request ( $task_url, "get", null );
	}*/

// }

// require Keke_tpl::template ( $do ); 