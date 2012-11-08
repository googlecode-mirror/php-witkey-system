<?php   defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * 
 * �û�ע��
 * @author Administrator
 *
 */
abstract class Keke_user_register extends Keke_user{
   
	protected $_username;
	protected $_pwd;
	protected $_email;
	protected $_salt;

	public static $_instance;
	
	/**
	 * �û�ע��ʵ��
	 * @param string $name   (keke,uc,pw)
	 * 
	 */
	public static function instance($name='keke'){
		if(isset(self::$_instance)){
			return self::$_instance;
		}
		$class = 'Keke_user_register_'.$name;
		self::$_instance = new $class;
		return self::$_instance;
	}
 	
	function set_username($var){
		$this->_username = $var;
		return $this;
	}
	function set_pwd($var){
		$this->_pwd = $var;
		return $this;
	}
	function set_email($var){
		$this->_email = $var;
		return $this;
	}
	/**
	 * ���ע��
	 */
	function complete_reg($uid,$username){
		Keke_user_login::instance()->complete_login($uid, $username);
		$columns = array('uid','username','email','reg_time','reg_ip','last_login_time');
		$values = array($uid,$username,$this->_email,time(),Keke::get_ip(),time());
		return (bool)DB::insert('witkey_space')->set($columns)->value($values)->execute();
	}
	/**
	 * ���ɰ�ȫ��
	 */
	function gen_secode($str,$salt=NULL){
		if($salt===NULL){
			$this->_salt = Keke::randomkeys(6);
		}else{
			$this->_salt = $salt;
		}
		return hash_hmac('md5', $str, $this->_salt);
	}
	/**
	 * ע���û���Ϣ
	 */
	abstract public function reg();
	
	/**
	 * ���username
	 * @return -1,�û������Ϸ�-2 �û���������-3�û����Ѿ�����
	 */
	abstract public function check_username($username);
	
	/**
	 * ���email
	 * @return -4 ��ʽ����,-5�ѱ�ע��
	 */
	abstract public function check_email($email);
     /**
      * ͬ����¼
      */	
	abstract public function syc_login();
	
}
