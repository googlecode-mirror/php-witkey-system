<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * Ucenter �û���¼
 * @author Michael
 * @version 2.2 2012-11-06
 */
require_once S_ROOT.'client/pw_client/uc_client.php';

class Keke_user_login_pw extends Keke_user_login{
     /**
      * phpWind�û���¼
      * @see Keke_user_login::login()
      * @return -1�û�������,-2�������
      */
	 function login($type=0){
        list($uid,$username,$status,$synlogin) = uc_user_login($this->_username, $this->_pwd, $type);
        if($status===1){
        	//�첽��¼����
        	return $synlogin;
        }else{
        	//��¼������ 
        	return $status;
        } 	
     }
     /**
      * phpWindow�û��˳�
      * @see Keke_user_login::logout()
      */
     function logout($destroy=FALSE){
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
     	return uc_user_synlogout();
     }
}
