<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

/**
 * �û���¼����
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
	 * @var Ĭ�ϵ�¼��ʽ
	 */
	public static $_default = 'keke';
	/**
	 *
	 * @var ��¼ʵ��
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
	 * ��ȡ��¼�û�UID
	 * 
	 * @return Ambigous <string, multitype:>
	 */
	function get_user() {
		return $this->_session->get ( 'uid' );
	}
	/**
	 * ��ɵ�¼
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
	 * �ж��Ƿ��¼
	 * 
	 * @return boolean
	 */
	function logged_in() {
		return ($this->get_user () !== NULL);
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
		// ���ǳ��Ƿ�ɹ�
		return ! $this->logged_in ();
	}
}
