<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

abstract  class Model {
  	public $_db;
	public $_tablename;
	public $_pk;
	public $_lifetime;
	public $_replace = 0;
	public static  $_where = NULL;
	public static $_instance = null;
	public function __construct($table_name=null){
		$this->_db = Database::instance();
		$this->_tablename = '`'.DBNAME.'`.`'.TABLEPRE . $table_name.'`';
	}
	/**
	 * @param string $table_name 表名 ,不需要加表前缀
	 * ,表名为keke_witkey_link 可以写为wiktye_link
	 * @return Model
	 */
  	public static function factory($table_name){
  		if(self::$_instance[$table_name] == null){
			$class =  TABLEPRE . $table_name;
			self::$_instance[$table_name] =  new $class();
		}
		
  		return 	self::$_instance[$table_name];
      	
	} 
    /**
     * @return Model  
     */
	abstract public function setWhere($where);
	
	/**
	 * 字段设值
	 * @param $array  字段健值对数组
	 * @return Model
	 */
	abstract public function setData($array);
	abstract public function create();
	abstract public function update();
	/**
	 * 
	 * @param string $fields  查询字段，默认值为*
	 * @param int  $cache_time  null 表示默认缓存,0 表示不缓存，1，表示缓存1秒钟
	 * @param array
	 */
	abstract public function query($fields,$cache_time);
	abstract public function del();
	abstract public function count();
	
	function reset() {
		self::$_where = NULL;
	}
	
}
