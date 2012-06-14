<?php
abstract class Keke_database {
	const SELECT = 1;
	const INSERT = 2;
	const UPDATE = 3;
	const DELETE = 4;
	public static $default = 'mysql';
	public static $instances = array ();
	/**
	 *
	 * @param $name string
	 *       	 mysql,mysqli,sqlite ...
	 * @param $config array       	
	 * @return Keke_driver_mysql
	 */
	public static function instance($name = null, $config = null) {
		if ($name === null) {
			$name = Database::$default;
		}
		if (isset ( Database::$instances [$name] )) {
			return Database::$instances [$name];
		}
		$class = "Keke_driver_{$name}";
		//Keke::keke_require_once ( S_ROOT . 'base' . DIRECTORY_SEPARATOR . 'db_factory' . DIRECTORY_SEPARATOR . $class . '.php' );
		Database::$instances [$name] = new $class ( $config );
		return Database::$instances [$name];
	}
	/**
	 * ִ�в���
	 *
	 * @param $tablename string
	 *       	 ����
	 * @param $insertsqlarr array
	 *       	 �ֶ�����
	 * @param $returnid bool
	 *       	 ���� last_insert_id
	 * @param $replace bool
	 *       	 �Ƿ��滻
	 * @return int last_insert_id or Ӱ�������
	 */
	abstract public function insert($tablename, $insertsqlarr, $returnid = 0, $replace = false);
	/**
	 * ִ�и���
	 *
	 * @param $tablename string       	
	 * @param $setsqlarr array       	
	 * @param $wheresqlarr array       	
	 * @return int Ӱ�������
	 */
	abstract public function update($tablename, $setsqlarr, $wheresqlarr);
	/**
	 * ִ��SQL���
	 *
	 * @param $sql string       	
	 * @return int Ӱ�������
	 */
	abstract public function execute($sql);
	/**
	 * sql��ѯ������
	 */
	abstract public function get_query_num();
	/**
	 * ���ֶ�ִ��sql
	 *
	 * @param $fileds string
	 *       	 �ֶ�
	 * @param $table string
	 *       	 ����
	 * @param $where string
	 *       	 ����
	 * @param $order string
	 *       	 ����
	 * @param $group string
	 *       	 ����
	 * @param $limit string
	 *       	 ����
	 * @param $pk string
	 *       	 ��ֵ������
	 * @return array
	 */
	abstract public function select($fileds = '*', $table = null, $where = '', $order = '', $group = '', $limit = '', $pk = '');
	/**
	 * ��ȡһ���ֶε�ֵ
	 *
	 * @param $sql string       	
	 * @param $row int
	 *       	 �ڼ���
	 * @param $filed string
	 *       	 �ֶ�����
	 */
	abstract public function get_count($sql, $row = 0, $filed = null);
	/**
	 * ��ȡһ������
	 *
	 * @param $sql string       	
	 * @return array
	 */
	abstract public function get_one_row($sql);
	/**
	 * ��ѯSql
	 *
	 * @param $sql string       	
	 * @param $is_unbuffer boolene       	
	 * @return array
	 */
	abstract public function query($sql, $type = Database::SELECT, $is_unbuffer = 0);
	/**
	 * ����ʼ
	 */
	abstract public function begin();
	/**
	 * �����ύ
	 */
	abstract public function commit();
	/**
	 * ����ع�
	 */
	abstract public function rollback();
	
	protected $_instance;
	protected $_config;
	protected function __construct($name, array $config) {
		$this->_instance = $name;
		$this->_config = $config;
		Database::$instances [$name] = $this;
	}
	
	final public function __destruct() {
		$this->disconnect ();
	}
	final public function disconnect() {
		unset ( Database::$instances [$this->_instance] );
		return true;
	}
	public function quote_field(&$value) {
		if ('*' == $value || false !== strpos ( $value, '(' ) || false !== strpos ( $value, '.' ) || false !== strpos ( $value, '`' )) {
		
		} else {
			$value = '`' . trim ( $value ) . '`';
		}
		return $value;
	}
	public function quote_string(&$value) {
		if ($value === NULL) {
			return 'NULL';
		} elseif ($value === TRUE) {
			return "'1'";
		} elseif ($value === FALSE) {
			return "'0'";
		}elseif (is_string($value)) {
			return sprintf("'%s'",$value);
		}  elseif (is_array ( $value )) {
			return  implode ( ', ', array_map (array($this,__FUNCTION__), $value ) ) ;
		} elseif (is_int ( $value )) {
			return ( int ) $value;
		} elseif (is_float ( $value )) {
			return sprintf ( '%F', $value );
		}
		
		return $this->escape ( $value );
	}
	abstract public function escape($value);

}
