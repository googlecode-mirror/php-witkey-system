<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

abstract  class Model {
  	public $_db;
	public $_tablename;
	public $_pk;
	public $_lifetime;
	public $_replace = 0;
	public $_where;
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
		$class =  TABLEPRE . $table_name;
		return 	new $class ( );
      	
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
		unset ($this->_tablename, $this->_where);
	}
	
}
