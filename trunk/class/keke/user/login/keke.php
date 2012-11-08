<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * �Ϳ�ϵͳ��վ�ڵ�¼
 * @author Michael	
 * @version 2.2 
 * 2012-11-06
 *
 */

class Keke_user_login_keke extends Keke_user_login {
    
 
    /**
     * �û���¼
     * @param int $type (��¼��ʽ1,2,3 �ֱ��ʾ���û������ֻ��ţ������ַ )
     * @return int -1�˺Ų���,-2���벻��
     */
	function login($type=1){
		
		//����Ϊ��
		if (empty($this->_pwd)){
			return -5;
		}
		$username = $this->check_account($type);
		if($username<0){
			return $username;
		}
		$where = "username = '$username' and password = '$this->_pwd'";
		$uid = DB::select('uid')->from('witkey_member')->where($where)->get_count()->execute();
		if($uid){
			//���µ�¼ʱ�䣬��¼IP��ַ
			$this->update_login_time($uid);
			$this->remember_me($uid, $username, $this->_pwd);
		    $this->complete_login($uid, $username);
		    //��¼�ɹ�
			return TRUE;
		}else{
			//�������
			return -2;
		}
	}
	/**
	 * �ǳ�ϵͳ
	 *
	 * @return boolean
	 */
	function logout($destroy = FALSE) {
		if ($destroy === TRUE) {
			$this->_session->destroy();
		} else {
			// ɾ����¼�û��Ự
			$this->_session->delete ( 'uid' );
			$this->_session->delete ( 'username' );
			// �������ɻỰ
			$this->_session->regenerate ();
		}
		Cookie::delete('remember_me');
		// ���ǳ��Ƿ�ɹ�
		return ! $this->logged_in ();
	}
	/**
	 * �ж��˺��Ƿ����
	 * @param int $type
	 * @return string 
	 */
	function check_account($type){
		if($type==1){
			$where = "username = '$this->_username'";
		}elseif($type == 2){
			$where = "mobile = '$this->_username'";
		}elseif($type==3){
			$where = "email = '$this->_username'";
		}
		$res = DB::select('username,status')->from('witkey_space')->where($where)->get_one()->execute();
		list($username,$status) = array($res['username'],$res['status']);
	   //�˺Ų�����
		if(!Keke_valid::not_empty($username)){
			return -1;
		}
		if($status==2){
			//�˺ű�����
			return -3;
		}elseif($status==3){
			//�˺�δ����
			return -4;
		}
		return $username;
	}

	
	
}
