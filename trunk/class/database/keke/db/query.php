<?php  defined('IN_KEKE') OR die('access on priv!');
class Keke_db_query {
	// Query type
	protected $_type;
	protected $_where;
	// Execute the query during a cache hit
	protected $_force_execute = FALSE;
	
	// Cache lifetime
	protected $_lifetime = NULL;
	
	// SQL statement
	protected $_sql;
	
	// Quoted query parameters
	protected $_parameters = array ();
	
	// Return results as associative arrays or objects
	protected $_as_object = FALSE;
	
	// Parameters for __construct when using object results
	protected $_object_params = array ();
	//��ǰ׺
	protected $_tablepre = array();
	/**
	 *
	 * @var ��ѯ��ʽ (query,get_count,get_one_row);
	 */
	protected $_query_type = 'query';
	
	/**
	 * Creates a new SQL query of the specified type.
	 *
	 * @param   integer  query type: Database::SELECT, Database::INSERT, etc
	 * @param   string   query string
	 * @return  void
	 */
	public function __construct($sql, $type) {
		if($type===null){
			$type = Database::SELECT;
		}
		$this->_type = $type;
		$this->_sql = $sql;
	}
	final public function __toString() {
		try {
			// ����sql�ַ���
			return $this->compile ( Database::instance () );
		} catch ( Exception $e ) {
			return Keke_exception::text ( $e );
		}
	}
	/**
	 * ���ز�ѯ����
	 */
	public function type() {
		return $this->_type;
	}
	/**
	 * Enables the query to be cached for a specified amount of time.
	 * ������ѯ����,�����建�����������
	 * 
	 * @param  int lifetime �����ʱ��������0 ɾ����Ӧ�Ļ���
	 * @param  boolean �Ƿ����������в�ѯ
	 * @return keke_db_query
	 */
	public function cached($lifetime = NULL, $force = FALSE) {
		if ($lifetime === NULL) {
			// Ĭ�ϻ���ʱ��
			$lifetime = Cache::DEFAULT_CACHE_LIFE_TIME;
		}
		$this->_force_execute = $force;
		$this->_lifetime = $lifetime;
		
		return $this;
	}
	/**
	 * ����һ�е�ֵ
	 * @example select id,name from table ���ص�ֵΪarray('id'=>'xxx',name=>'xxxx');
	 * @return Keke_db_select
	 */
	public function get_one(){
		$this->_query_type = 'get_one_row';
		return $this;
	}
	/**
	 * ����ָ���ֶε�ֵ,һ������һ���ֶεĲ�ѯ
	 * @example select count(*) from ... ����һcount ��ֵ '222'
	 * @return Keke_db_select
	 */
	public function get_count(){
		$this->_query_type = 'get_count';
		return $this;
	
	}
	/**
	 * ���ع���������
	 * @return keke_db_query
	 */
	public function as_assoc() {
		$this->_as_object = FALSE;
		$this->_object_params = array ();
		return $this;
	}
	/**
	 * ��������
	 * @param string $where
	 * @return Keke_db_query
	 */
	public function where($where){
		if($where){
			$this->_where = $where;
		}
		return $this;
	}
	/**
	 * �������Ϊ���󷵻�
	 * @param string $class ���� TRUE for ����
	 * @param $params �������     	
	 * @return keke_db_query
	 */
	public function as_object($class = TRUE, array $params = NULL) {
		$this->_as_object = $class;
		if ($params) {
			// ��Ӷ���Ĳ���
			$this->_object_params = $params;
		}
		
		return $this;
	}
	
	/**
	 * ���ò�ѯ������ֵ
	 * @param  string $param key to replace
	 * @param  string $value to use
	 * @return keke_db_query
	 */
	public function param($param, $value) {
		// Add or overload a new parameter
		$this->_parameters [$param] = $value;
		
		return $this;
	}
	
	/**
	 * �󶨱����Ĳ�ѯ����
	 *
	 * @param string $param key to replace
	 * @param string $var variable to use
	 * @return keke_db_query
	 */
	public function bind($param, & $var) {
		// ��󶨵ı�����ֵ
		$this->_parameters [$param] = & $var;
		
		return $this;
	}
	
	/**
	 * ��Ӷ����ѯ�Ĳ���
	 * @param  array $params list of parameters
	 * @return keke_db_query
	 */
	public function parameters(array $params) {
		// �ϲ�����
		$this->_parameters = $params + $this->_parameters;
		
		return $this;
	}
	/**
	 * ��ǰ׺���壬����Ҫ�ӵ���
	 * @param string $param (:pre)
	 * @param $value (��ǰ׺)
	 */
	public function tablepre($param,$value=TABLEPRE){
		$this->_tablepre[$param] = $value;
		return $this;
	}
	/**
	 * ����SQL��ѯ�������������滻�󶨵Ĳ�����
	 * @param  object database instance
	 * @return string
	 */
	public function compile($db) {
		// ���뱾�ص�sql
		$sql = $this->_sql;
		if (! empty ( $this->_parameters )) {
			// ת�崦��sql �е�ֵ
			$values = array_map ( array ($db, 'quote_string' ), $this->_parameters );
			// �滻sql�е�ֵ
			$sql = strtr ( $sql, $values );
		}
		if(!empty($this->_tablepre)){
			$sql = strtr($sql, $this->_tablepre);
		}
		
		return $sql;
	}
	
	/**
	 * ִ�е�ǰ�Ĳ�ѯ
	 *
	 * @param  $db ���ݿ����
	 *       	 
	 * @param  string result object classname, TRUE for stdClass or FALSE for array
	 * @param	array result object constructor arguments
	 *       	
	 * @return object keke_db_query for SELECT queries
	 * @return mixed the insert id for INSERT queries
	 * @return integer number of affected rows for all other queries
	 */
	public function execute($db = NULL) {
		if (! is_object ( $db )) {
			// Get the database instance
			$db = Database::instance ( $db );
		}
		
		// ����sql���
		$sql = $this->compile ( $db );
		
		
		if ($this->_lifetime !== NULL and $this->_type === Database::SELECT) {
			// ʹ�����ݿ�ʵ����sql��Ϊ����ļ���
			$cache_key = Cache::instance()->generate_id( $sql)->get_id();
			//�ȶ�ȡ������ȥɾ��lifetime<=0 �Ļ���
			$result =  Cache::instance()->get($cache_key);
			if($result){
				return $result;
			}
		}
		// Execute the query
		$query = $this->_query_type;
		$result = $db->$query( $sql,$this->_type );
		
		if (isset ( $cache_key ) and $this->_lifetime > 0 and $this->_type === Database::SELECT) {
			// Cache the result array
			Cache::instance()->set($cache_key, $result, $this->_lifetime );
		}
		
		return $result;
	}

}
