<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );

/**
 * Ucenter 用户登录
 * @author Michael
 * @version 2.2 2012-11-06
 */

require_once S_ROOT.'keke_client/ucenter/client.php';

class Keke_user_login_uc extends Keke_user_login{
    const USERNAME = 0;
	/**
	 * Ucenter 登录
	 * @see Keke_user_login::login()
	 */
	function login($type=self::USERNAME){
	   list ($uid, $username, $password, $email) =  uc_user_login($this->_username, $this->_pwd,$type);
	   //大于0表示登录成功
	   if($uid>0){
	   	  	return uc_user_synlogin($uid);
	   }else{
	   		return $uid;
	   }	
	}
    /**
     * 登出的结果要在控制层显示
     * @see Keke_user_login::logout()
     */
	function logout($destroy = FALSE){
		if ($destroy === TRUE) {
			$this->_session->destroy();
		} else {
			// 删除登录用户会话
			$this->_session->delete ( 'uid' );
			$this->_session->delete ( 'username' );
			// 重新生成会话
			$this->_session->regenerate ();
		}
		Cookie::delete('remember_me');
		return  uc_user_synlogout();
	}

}//end
 