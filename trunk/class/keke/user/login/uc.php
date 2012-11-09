<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );

/**
 * Ucenter �û���¼
 * @author Michael
 * @version 2.2 2012-11-06
 */

require_once S_ROOT.'client/ucenter/client.php';

class Keke_user_login_uc extends Keke_user_login{
    const USERNAME = 0;
	/**
	 * Ucenter ��¼
	 * @see Keke_user_login::login()
	 */
	function login($type=self::USERNAME){
	   list ($uid, $username, $password, $email) =  uc_user_login($this->_username, $this->_pwd,$type);
	   //����0��ʾ��¼�ɹ�
	   if($uid>0){
	   		$res = $this->check_account($uid);
	   		if($res!==TRUE){
	   			return $res;
	   		}
	   		$this->complete_login($uid, $username);
	   		return uc_user_synlogin($uid);
	   }else{
	   	    return $uid;
	   }	
	}
    /**
     * �ǳ��Ľ��Ҫ�ڿ��Ʋ���ʾ
     * @see Keke_user_login::logout()
     */
	function logout($destroy = FALSE){
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
		return  uc_user_synlogout();
	}
	
	/**
	 * kekeϵͳ�ж��˺��Ƿ����
	 * @param int $uid
	 * @return string
	 */
	function check_account($uid){
        $where  = "uid ='$uid'";
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
		return TRUE;
	}

}//end
 