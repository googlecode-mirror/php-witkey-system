<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * keke 系统用户注册
 * @author Michael	
 * @version 2.2 2012-11-08
 *
 */

class Keke_user_register_keke extends Keke_user_register {

	
	function reg(){
		if(($res = $this->check_username($this->_username))<1){
			return $res;
		}
		if(($res= $this->check_email($this->_email))<1){
			return $res;
		}
		$columns = array('username','password','salt','sec_code');
		
		$scode = $this->gen_secode($this->_pwd);
		
		$values = array($this->_username,md5($this->_pwd),$this->_salt,$scode);
		
		$uid = DB::insert('witkey_member')->set($columns)->value($values)->execute();
		
		$this->complete_reg($uid, $this->_username);
		
		return $uid;
		
	}
	/**
	 * 检查用户名
	 * @see Keke_user_register::check_username()
	 * @return  
	 */
	function check_username($username){
		if(!Keke_valid::not_empty($username)){
			//用名为空
			return -1;
		}
		if(Keke::k_strpos($username)){
			//用户名敏感
			return -2;
		}
		if((bool)DB::select('count(*)')->from('witkey_memeber')->where("username='$username'")->execute()){
			//用户存在
			return -3;
		}
		return TRUE;
	}
	
	function check_email($email){
		if(!Keke_valid::email($email)){
			//邮箱格式不对
			return -4;
		}
		if((bool)DB::select('count(*)')->from('witkey_space')->where("email='$email'")->execute()){
			//邮箱已存在
			return -5;
		}
		return TRUE;
	}
	
	function syc_login(){
		
	}
	
}
