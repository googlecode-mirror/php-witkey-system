<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 *
 * @copyright keke-tech
 * @author Michael
 * @version v 2.2
 *          2012-11-06
 */
class Control_login extends Controller {
	function action_index() {
		global $_K, $_lang;
		var_dump ( $_SESSION );
		require Keke_tpl::template ( 'login' );
	}
	function action_login() {
		global $_K;
		Keke::formcheck ( $_POST ['formhash'] );
		$_POST = Keke_tpl::chars ( $_POST );
		$account = $_POST ['txt_account'];
		$pwd = $_POST ['pwd_password'];
		$ins = array (
				1 => 'keke',
				2 => 'uc',
				3 => 'pw' 
		);
		$login_obj = Keke_user_login::instance ( $ins [$_K ['user_intergration']] );
		
		if ($_K ['user_intergration'] == 1) {
			if (Keke_valid::email ( $account )) {
				$login_obj->set_email ( $account );
			} elseif (Keke_valid::phone ( $account )) {
				$login_obj->set_mobile ( $account );
			} else {
				$login_obj->set_username ( $account );
			}
		} else {
			$login_obj->set_username ( $account );
		}
		
		$login_obj->set_pwd ( $pwd );
		
		$res = $login_obj->login ();
		
		$uri = 'login';
		if($res===-1){
			$msg = '用户名错误';
			$t = 'error';
		}else if($res===-2){
			$msg = '密码错误';
			$t = 'error';
		}else if($res===false){
			$msg = '密码为空';
			$t = 'error';
		}else if($res===true){
			$msg = '登录成功' . $res;
			$t = 'success';
			$uri = $this->request->referrer () ? $this->request->referrer () : '/user/index';
		}
		Keke::show_msg ( $msg, $uri, $t );
	}
}

/* $page_title=$_lang['login'].'- '.$_K['html_title'];
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
require  Keke_tpl::template ( $do ); */