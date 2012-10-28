<?php

defined ( 'IN_KEKE' ) or die ( 'Access Denied' );
class Keke_witkey_recharge extends Model {
	protected static $_data = array ();
	function __construct() {
		parent::__construct ( 'witkey_recharge' );
	}
	public function getRid() {
		return self::$_data ['rid'];
	}
	public function getType() {
		return self::$_data ['type'];
	}
	public function getBank() {
		return self::$_data ['bank'];
	}
	public function getOrder_id() {
		return self::$_data ['order_id'];
	}
	public function getUid() {
		return self::$_data ['uid'];
	}
	public function getUsername() {
		return self::$_data ['username'];
	}
	public function getPay_time() {
		return self::$_data ['pay_time'];
	}
	public function getCash() {
		return self::$_data ['cash'];
	}
	public function getStatus() {
		return self::$_data ['status'];
	}
	public function getPay_info() {
		return self::$_data ['pay_info'];
	}
	public function getWhere() {
		return self::$_where;
	}
	public function setRid($value) {
		self::$_data ['rid'] = $value;
		return $this;
	}
	public function setType($value) {
		self::$_data ['type'] = $value;
		return $this;
	}
	public function setBank($value) {
		self::$_data ['bank'] = $value;
		return $this;
	}
	public function setOrder_id($value) {
		self::$_data ['order_id'] = $value;
		return $this;
	}
	public function setUid($value) {
		self::$_data ['uid'] = $value;
		return $this;
	}
	public function setUsername($value) {
		self::$_data ['username'] = $value;
		return $this;
	}
	public function setPay_time($value) {
		self::$_data ['pay_time'] = $value;
		return $this;
	}
	public function setCash($value) {
		self::$_data ['cash'] = $value;
		return $this;
	}
	public function setStatus($value) {
		self::$_data ['status'] = $value;
		return $this;
	}
	public function setPay_info($value) {
		self::$_data ['pay_info'] = $value;
		return $this;
	}
	public function setWhere($value) {
		self::$_where = $value;
		return $this;
	}
	public function setData($array) {
		self::$_data = array_filter ( $array, array ('Model', 'remove_null' ) );
		return $this;
	}
	
	/**
	 * insert into keke_witkey_recharge ,or add new record
	 * 
	 * @return int last_insert_id
	 */
	function create($return_last_id = 1) {
		$res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace );
		$this->reset ();
		return $res;
	}
	
	/**
	 * update table keke_witkey_recharge
	 * 
	 * @return int affected_rows
	 */
	function update() {
		if ($this->getWhere ()) {
			$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere () );
		} elseif (isset ( self::$_data ['rid'] )) {
			self::$_where = array ('rid' => self::$_data ['rid'] );
			unset ( self::$_data ['rid'] );
			$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere () );
		}
		$this->reset ();
		return $res;
	}
	/**
	 * query table: keke_witkey_recharge,if isset where return where record,else
	 * return all record
	 * 
	 * @return array
	 */
	function query($fields = '*', $cache_time = 0) {
		empty ( $fields ) and $fields = '*';
		if ($this->getWhere ()) {
			$sql = "select $fields from $this->_tablename where " . $this->getWhere ();
		} else {
			$sql = "select $fields from $this->_tablename";
		}
		empty ( $fields ) and $fields = '*';
		$this->reset ();
		return $this->_db->cached ( $cache_time )->cache_data ( $sql );
	}
	
	/**
	 * query count keke_witkey_recharge records,if iset where query by where
	 * 
	 * @return int count records
	 */
	function count() {
		if ($this->getWhere ()) {
			$sql = "select count(*) as count from $this->_tablename where " . $this->getWhere ();
		} else {
			$sql = "select count(*) as count from $this->_tablename";
		}
		$this->reset ();
		return $this->_db->get_count ( $sql );
	}
	
	/**
	 * delete table keke_witkey_recharge, if isset where delete by where
	 * 
	 * @return int deleted affected_rows
	 */
	function del() {
		if ($this->getWhere ()) {
			$sql = "delete from $this->_tablename where " . $this->getWhere ();
		} else {
			$sql = "delete from $this->_tablename where rid = $this->_rid ";
		}
		$this->reset ();
		return $this->_db->query ( $sql, Database::DELETE );
	}
} //end 