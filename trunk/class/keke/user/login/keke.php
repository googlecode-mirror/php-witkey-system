<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * 客客系统的站内登录
 * @author Michael	
 * @version 2.2 
 * 2012-11-06
 *
 */

class Keke_user_login_keke extends Keke_user_login {
    
 
    /**
     * 用户登录
     * @param int $type (登录方式1,2,3 分别表示，用户名，手机号，邮箱地址 )
     * @return int -1账号不对,-2密码不对
     */
	function login($type=1){
		
		//密码为空
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
			//更新登录时间，登录IP地址
			$this->update_login_time($uid);
			$this->remember_me($uid, $username, $this->_pwd);
		    $this->complete_login($uid, $username);
		    //登录成功
			return TRUE;
		}else{
			//密码错误
			return -2;
		}
	}
	/**
	 * 登出系统
	 *
	 * @return boolean
	 */
	function logout($destroy = FALSE) {
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
		// 检查登出是否成功
		return ! $this->logged_in ();
	}
	/**
	 * 判断账号是否存在
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
	   //账号不存在
		if(!Keke_valid::not_empty($username)){
			return -1;
		}
		if($status==2){
			//账号被冻结
			return -3;
		}elseif($status==3){
			//账号未激活
			return -4;
		}
		return $username;
	}

	
	
}
