<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * keke ϵͳ�û�ע��
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
	 * ����û���
	 * @see Keke_user_register::check_username()
	 * @return  
	 */
	function check_username($username){
		if(!Keke_valid::not_empty($username)){
			//����Ϊ��
			return -1;
		}
		if(Keke::k_strpos($username)){
			//�û�������
			return -2;
		}
		if((bool)DB::select('count(*)')->from('witkey_memeber')->where("username='$username'")->execute()){
			//�û�����
			return -3;
		}
		return TRUE;
	}
	
	function check_email($email){
		if(!Keke_valid::email($email)){
			//�����ʽ����
			return -4;
		}
		if((bool)DB::select('count(*)')->from('witkey_space')->where("email='$email'")->execute()){
			//�����Ѵ���
			return -5;
		}
		return TRUE;
	}
	
	function syc_login(){
		
	}
	
}
