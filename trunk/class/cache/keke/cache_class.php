<?php
/**
 * 
 * this is not free software
 * @example 
 * @author michael
 *
 */
abstract class keke_cache_class {
	const DEFAULT_CACHE_LIFE_TIME = 3600;
	public $_config = array ();
	public $_enable = false;
	public static $instances = array();
	public static $_cache_default = CACHE_TYPE;
	public static $_id;
	/**
	 * 
	 * construct cache calss 
	 * @var string $cache_type  -- 'file' ,'sqlite','eacc','apc','mem'
	 * @var array $config   --array(0=>array("host"=>"127.0.0.1","port"=>"11211"))
	 * @return cache obj 
	 */
	public static function instance($cache_driver = null, $config = null) {
		if ($cache_driver === null) {
			$cache_driver = keke_cache_class::$_cache_default;
		}
		if (isset ( keke_cache_class::$instances [$cache_driver] )) {
			return keke_cache_class::$instances [$cache_driver];
		}
		$class_name = "keke_cache_$cache_driver";
		keke_cache_class::$instances [$cache_driver] = new $class_name ( $config );
		return keke_cache_class::$instances [$cache_driver];
	}
	public function generate_id($id) {
		self::$_id=TABLEPRE . mb_strcut( md5 ( $id ), 24, 32 ,CHARSET);
		return $this;
	}
	
	abstract public function get($id);
	abstract public function set($id, $val, $expire = null);
	abstract public function del($id);
	abstract public function del_all();
	final public function __clone() {
		throw new keke_exception( 'Cloning of Cache objects is forbidden' );
	}
	public function get_id(){
		return self::$_id;
	}
	protected function sanitize_id($id)
	{
		return str_replace(array('/', '\\', ' '), '_', $id);
	}
}
