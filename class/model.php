<?php

abstract  class model {
  	public $_db;
	public $_tablename;
	public $_pk;
	public $_lifetime;
	public $_replace = 0;
	public $_where;
	public static $instances = array ();
	public function __construct($table_name=null){
		$this->_db = database::instance();
		$this->_tablename = '`'.DBNAME.'`.`'.TABLEPRE . $table_name.'`';
	}
	/**
	 * @param string $table_name ±íÃû
	 * @return Model
	 */
/*  	public static function instance($table_name){
		$this->_tablename = TABLEPRE . $table_name;
      	if(isset(Model::instance($this->_tablename))){
      		return Model::instance($this->_tablename);
      	}
       	$class = $this->_tablename.'class';
      	database::$instances [$this->_tablename] = new $class ( );
      	return database::$instances [$this->_tablename];
	}  */
	
	

	abstract public function create();
	abstract public function update();
	abstract public function query();
	abstract public function del();
	abstract public function count();
	
	function reset() {
		unset ($this->_tablename, $this->_where);
	}
	
}
