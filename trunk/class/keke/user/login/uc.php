<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );

/**
 * Ucenter �û���¼
 * @author Michael
 * @version 2.2 2012-11-06
 */

require_once S_ROOT.'keke_client/ucenter/client.php';

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

}//end
 