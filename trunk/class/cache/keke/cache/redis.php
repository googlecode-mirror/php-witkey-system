<?php
/**
 * redis ��noSql��һ�֣�Ҳ�������������⣬����ֻ�����������洦����
 * phpredis ������
 * @author michael
 *
 */
final class Keke_cache_redis extends Keke_cache {
	protected static $_redis;
	function __construct($config=array()) {
		if(!extension_loaded('redis')) { 
			throw new Keke_exception( "Redis dosn't load ,please loaded!");
		}
		/**
		    $config = array('host'=>'localhost','port'=>'6379');
		    timeout: 0 �������ã�һ��Ĭ��Ϊ300��
		 */
		$this->_config = $config;
		$this->set_server();
	}
	/**
	 * ������ʱ��ֻ֧�ֵ�̨�������Ĳ������ڶ�̨��������ģʽ�½���
	 * ʹ��master,salveģʽ��һ�����ģʽ����master�첽���µ�salve
	 * ��salve ���־û�����.��Ҫ��master���־û������������Ӱ��
	 */
	public function set_server(){
		self::$_redis = new redis();
		if(is_array($this->_config)){
		   	self::$_redis->connect($this->_config['host'],$this->_config['port']);
		}else{
			self::$_redis->connect('127.0.0.1','6379');
		}
	}
	/**
	 * ��ȡ��ͨString����ֵ
	 * @see Keke_cache::get()
	 */
	public function get($id) {
		return self::$_redis->get($id);
	}
	/**
	 * @param array $ids 
	 * @param unknown_type $ids
	 */
	public function mget($ids) {
		return self::$_redis->mget($ids);
	}
	/**
	 * �����setֻ��String ��ֵ��Ч
	 * @see Keke_cache::set()
	 */
	public function set($id, $value, $expire = 0, $dependency = null) {
		return self::$_redis->set($id,$value,$expire);
	}
	
	/**
	 * redis ��ADD ������Ӧ��append(key,vale);
	 * ��ԭ����ֵ�Ͻ���׷�ӣ���ʱ���˲�����Ч.
	 * @param string $id
	 * @param string $value
	 * @param int $expire
	 * @param string $dependency
	 */
	public function add($id, $value, $expire = 0, $dependency = null) {
	     return self::$_redis->append($id,$value);
		
	}
	/**
	 * del('key1');
	 * del('key1','key2');
	 * del(array('key1','key2'));
	 * ֧����������ģʽ
	 * @param maxid $id �ַ���������������
	 * @see Keke_cache::del()
	 */
	public function del($id) {
		return self::$_redis->delete($id);
	}
	public function del_all() {
		return self::$_redis->flushall();
	}
	public function __destruct(){
		self::$_redis->close();
	}
}//end