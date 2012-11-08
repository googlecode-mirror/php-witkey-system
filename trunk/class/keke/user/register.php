<?php   defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * 
 * 用户注册
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
	 * 用户注册实例
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
	 * 完成注册
	 */
	function complete_reg($uid,$username){
		Keke_user_login::instance()->complete_login($uid, $username);
		$columns = array('uid','username','email','reg_time','reg_ip','last_login_time');
		$values = array($uid,$username,$this->_email,time(),Keke::get_ip(),time());
		return (bool)DB::insert('witkey_space')->set($columns)->value($values)->execute();
	}
	/**
	 * 生成安全码
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
	 * 注册用户信息
	 */
	abstract public function reg();
	
	/**
	 * 检查username
	 * @return -1,用户名不合法-2 用户名敏感了-3用户名已经存在
	 */
	abstract public function check_username($username);
	
	/**
	 * 检查email
	 * @return -4 格式错误,-5已被注册
	 */
	abstract public function check_email($email);
     /**
      * 同步登录
      */	
	abstract public function syc_login();
	
}
