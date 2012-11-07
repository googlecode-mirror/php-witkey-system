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
		  
		require Keke_tpl::template ( 'login' );
	}
	function action_login() {
		global $_K;
		Keke::formcheck ( $_POST ['formhash'] );
		$_POST = Keke_tpl::chars ( $_POST );
		$account = $_POST ['txt_account'];
		$pwd = $_POST ['pwd_password'];
		$remember = (bool)$_POST['auto_login'];
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
		$login_obj->set_remember_me($remember);
		$res = $login_obj->login ();
		
		$uri = 'login';
		if($res===-1){
			$msg = 'ÓÃ»§Ãû´íÎó';
			$t = 'error';
		}else if($res===-2){
			$msg = 'ÃÜÂë´íÎó';
			$t = 'error';
		}else if($res===false){
			$msg = 'ÃÜÂëÎª¿Õ';
			$t = 'error';
		}else if($res===true){
			$msg = 'µÇÂ¼³É¹¦';
			$t = 'success';
			$uri = $this->request->referrer () ? $this->request->referrer () : '/user/index';
		}
		Keke::show_msg ( $msg, $uri, $t );
	}
} //end