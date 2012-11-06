<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

/**
 * 用户登录基类
 * 
 * @author Michael
 * @version 2.2
 *          2012-11-06
 *         
 */
abstract class Keke_user_login extends Keke_user {
	protected $_username;
	protected $_mobile;
	protected $_email;
	protected $_session;
	/**
	 *
	 * @var 默认登录方式
	 */
	public static $_default = 'keke';
	/**
	 *
	 * @var 登录实例
	 */
	public static $_instance;
	/**
	 *
	 * @param string $name        	
	 * @return Keke_user_login_keke (keke,uc,pw)
	 */
	public static function instance($name = NULL) {
		if ($name === NULL) {
			$name = self::$_default;
		}
		if (isset ( self::$_instance )) {
			return self::$_instance;
		}
		$class = "Keke_user_login_" . $name;
		self::$_instance = new $class ();
		return self::$_instance;
	}
	public function __construct() {
		$this->_session = Keke_session::instance ();
	}
	abstract public function login();
	abstract public function check_pwd();
	/**
	 * 获取登录用户UID
	 * 
	 * @return Ambigous <string, multitype:>
	 */
	function get_user() {
		return $this->_session->get ( 'uid' );
	}
	/**
	 * 完成登录
	 * 
	 * @param int $uid        	
	 * @param string $username        	
	 * @return boolean
	 */
	function complete_login($uid, $username) {
		$this->_session->regenerate ();
		$this->_session->set ( 'uid', $uid );
		$this->_session->set ( 'username', $username );
		return TRUE;
	}
	/**
	 * 判断是否登录
	 * 
	 * @return boolean
	 */
	function logged_in() {
		return ($this->get_user () !== NULL);
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
		// 检查登出是否成功
		return ! $this->logged_in ();
	}
}
