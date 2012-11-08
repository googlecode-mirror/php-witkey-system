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
        	$res = $this->check_account($uid);
        	if($res!==TRUE){
        		return $res;
        	}
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
}
