<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * 客客系统的站内登录
 * @author Michael	
 * @version 2.2 
 * 2012-11-06
 *
 */

class Keke_user_login_keke extends Keke_user_login {
    
    const USERNAME = 1;
    
    const MOBILE = 2;
    
    const EMAIL = 3;

    protected $_pwd ;
    
    function set_username($username){
    	$this->_username = $username;
    	return $this;
    }
    /**
     * 手机账号
     */
    function set_mobile($mobile){
    	$this->_mobile = $mobile;
    	return $this;
    }
    /**
     * email账号
     */
    function set_email($email){
    	$this->_email = $email;
    	return $this;
    }
    function set_pwd($pwd){
    	$this->_pwd = md5($pwd);
    	return $this;
    }
    /**
     * 用户登录
     * @param int $type (登录方式1,2,3 分别表示，用户名，手机号，邮箱地址 )
     * @return int -1账号不对,-2密码不对
     */
	function login($type=NULL){
		
		if($type===NULL){
			$type = self::USERNAME;
		}
		if (empty($this->_pwd)){
			return FALSE;
		}	
		$username = $this->check_account($type);
		if(empty($username)){
			//账号不存在
			return -1;
		}
		$where = "username = '$username' and password = '$this->_pwd'";
		$uid = DB::select('uid')->from('witkey_member')->where($where)->get_count()->execute();
		if($uid){
		    $this->complete_login($uid, $username);
			return TRUE;
		}else{
			return -2;
		}
	}
	/**
	 * 判断账号是否存在
	 * @param int $type
	 * @return string 
	 */
	function check_account($type){
		if($type==1){
			$where = "username = '$this->_username'";
		}
		if($type==2){
			$where = "mobile = '$this->_mobile'";
		}
		if($type==3){
			$where = "email = '$this->_email'";
		}
		return DB::select('username')->from('witkey_space')->where($where)->get_count()->execute();
	}
	function check_pwd(){
		
	}
	
}
