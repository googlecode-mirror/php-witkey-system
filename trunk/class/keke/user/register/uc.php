<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );

/**
 * 整全UCenter 用户注册
 * @author Michael
 * @version 2.2 2012-11-08
 *
 */
require S_ROOT.'client/ucenter/client.php';

class Keke_user_register_uc  extends Keke_user_register{

    	
	function reg(){
		if(($res = $this->check_username($this->_username))!==1){
			return $res;
		}
		if(($res = $this->check_email($this->_email))!==1){
			return $res; 
		}
		$uid = uc_user_register($this->_username, $this->_pwd, $this->_email);
		if($uid<=0){
			return $uid;
		}
		$this->complete_reg($uid, $this->_username);
		$html = $this->syn_login($uid);
		return $html;
	}
	
	function check_email($email){
		return uc_user_checkemail($email);
	}
	function check_username($username){
		return uc_user_checkname($username);
	}
	function syn_login($uid){
		return uc_user_synlogin($uid);
	}
}