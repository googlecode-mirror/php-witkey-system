<?php defined ( 'IN_KEKE' ) or die ( 'Access Denied' );
class Keke_witkey_link extends Model {
	protected static $_data = array ();
	function __construct() {
		parent::__construct ( 'witkey_link' );
	}
	public function getLink_id() {
		return self::$_data ['link_id'];
	}
	public function getLink_type() {
		return self::$_data ['link_type'];
	}
	public function getLink_name() {
		return self::$_data ['link_name'];
	}
	public function getLink_url() {
		return self::$_data ['link_url'];
	}
	public function getLink_pic() {
		return self::$_data ['link_pic'];
	}
	public function getListorder() {
		return self::$_data ['listorder'];
	}
	public function getLink_status() {
		return self::$_data ['link_status'];
	}
	public function getOn_time() {
		return self::$_data ['on_time'];
	}
	public function getObj_type() {
		return self::$_data ['obj_type'];
	}
	public function getObj_id() {
		return self::$_data ['obj_id'];
	}
	public function getWhere() {
		return self::$_where;
	}
	public function setLink_id($value) {
		self::$_data ['link_id'] = $value;
		return $this;
	}
	public function setLink_type($value) {
		self::$_data ['link_type'] = $value;
		return $this;
	}
	public function setLink_name($value) {
		self::$_data ['link_name'] = $value;
		return $this;
	}
	public function setLink_url($value) {
		self::$_data ['link_url'] = $value;
		return $this;
	}
	public function setLink_pic($value) {
		self::$_data ['link_pic'] = $value;
		return $this;
	}
	public function setListorder($value) {
		self::$_data ['listorder'] = $value;
		return $this;
	}
	public function setLink_status($value) {
		self::$_data ['link_status'] = $value;
		return $this;
	}
	public function setOn_time($value) {
		self::$_data ['on_time'] = $value;
		return $this;
	}
	public function setObj_type($value) {
		self::$_data ['obj_type'] = $value;
		return $this;
	}
	public function setObj_id($value) {
		self::$_data ['obj_id'] = $value;
		return $this;
	}
	public function setWhere($value) {
		self::$_where = $value;
		return $this;
	}
	public function setData($array) {
		self::$_data = $array;
		return $this;
	}
	
	/**
	 * insert into keke_witkey_link ,or add new record
	 * 
	 * @return int last_insert_id
	 */
	function create($return_last_id = 1) {
		$res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace );
		$this->reset ();
		return $res;
	}
	
	/**
	 * update table keke_witkey_link
	 * 
	 * @return int affected_rows
	 */
	function update() {
		if ($this->getWhere ()) {
			$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere () );
		} elseif (isset ( self::$_data ['link_id'] )) {
			self::$_where = array (
					'link_id' => self::$_data ['link_id'] 
			);
			unset ( self::$_data ['link_id'] );
			$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere () );
		}
		$this->reset ();
		return $res;
	}
	/**
	 * query table: keke_witkey_link,if isset where return where record,else
	 * return all record
	 * 
	 * @return array
	 */
	function query($fields = '*', $cache_time = 0) {
		if ($this->getWhere ()) {
			$sql = "select %s from $this->_tablename where " . $this->getWhere ();
		} else {
			$sql = "select %s from $this->_tablename";
		}
		empty ( $fields ) and $fields = '*';
		$sql = sprintf ( $sql, $fields );
		$this->reset ();
		return $this->_db->cached ( $cache_time )->cache_data ( $sql );
	}
	
	/**
	 * query count keke_witkey_link records,if iset where query by where
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
	 * delete table keke_witkey_link, if isset where delete by where
	 * 
	 * @return int deleted affected_rows
	 */
	function del() {
		if ($this->getWhere ()) {
			$sql = "delete from $this->_tablename where " . $this->getWhere ();
		} else {
			$sql = "delete from $this->_tablename where link_id = $this->_link_id ";
		}
		$this->reset ();
		return $this->_db->query ( $sql, Database::DELETE );
	}
} //end 