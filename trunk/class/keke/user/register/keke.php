<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * keke ϵͳ�û�ע��
 * @author Michael	
 * @version 2.2 2012-11-08
 *
 */

class Keke_user_register_keke extends Keke_user_register {

	/**
	 * kekeϵͳע��
	 * @see Keke_user_register::reg()
	 */
	function reg(){
		if(($res = $this->check_username($this->_username))<1){
			return $res;
		}
		if(($res= $this->check_email($this->_email))<1){
			return $res;
		}
		$uid = $this->complete_reg(NULL, $this->_username);
		
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
		if((bool)DB::select('count(*)')->from('witkey_member')->where("username='$username'")->get_count()->execute()){
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
		if((bool)DB::select('count(*)')->from('witkey_space')->where("email='$email'")->get_count()->execute()){
			//�����Ѵ���
			return -5;
		}
		return TRUE;
	}
	
	function syn_login($uid){
		
	}
	
}
