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
	 * @param string $table_name ���� ,����Ҫ�ӱ�ǰ׺
	 * ,����Ϊkeke_witkey_link ����дΪwiktye_link
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
	 * �ֶ���ֵ
	 * @param $array  �ֶν�ֵ������
	 * @return Model
	 */
	abstract public function setData($array);
	abstract public function create();
	abstract public function update();
	/**
	 * 
	 * @param string $fields  ��ѯ�ֶΣ�Ĭ��ֵΪ*
	 * @param int  $cache_time  null ��ʾĬ�ϻ���,0 ��ʾ�����棬1����ʾ����1����
	 * @param array
	 */
	abstract public function query($fields,$cache_time);
	abstract public function del();
	abstract public function count();
	
	function reset() {
		self::$_where = NULL;
	}
	
}
