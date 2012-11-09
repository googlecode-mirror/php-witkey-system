<?php   defined('IN_KEKE') or die('access fail');
/**
 * phpWind ÓÃ»§×¢²á
 * @author Michael
 * @version 2.2 2012-11-09
 */

require S_ROOT.'client/pw_client/uc_client.php';

class Keke_user_register_pw extends Keke_user_register {

	function reg(){
		if(($res=$this->check_username($this->_username))!==1){
			return $res;
		}
		if(($res=$this->check_email($this->_email))!==1){
			return $res;
		}
		$uid = uc_user_register($this->_username, $this->_pwd, $this->_email);
		if($uid<=0){
			return $uid;
		}
		return $this->complete_reg($uid, $this->_username);
		
	}
	
	function check_username($username){
		return uc_check_username($username);
	}
	
	function check_email($email){
		return uc_check_email($email);
	}
	
	function syn_login($uid){
		return uc_user_login($username, $password, $logintype);
	}
	
}
