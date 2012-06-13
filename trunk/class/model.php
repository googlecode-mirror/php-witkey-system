<?php

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
	 * @param string $table_name ����
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
	abstract public function query();
	abstract public function del();
	abstract public function count();
	
	function reset() {
		unset ($this->_tablename, $this->_where);
	}
	
}
