<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_payitem  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_payitem' );		 }	    
	    		public function getItem_id(){			 return self::$_data ['item_id']; 		}		public function getModel_code(){			 return self::$_data ['model_code']; 		}		public function getItem_code(){			 return self::$_data ['item_code']; 		}		public function getSmall_pic(){			 return self::$_data ['small_pic']; 		}		public function getBig_pic(){			 return self::$_data ['big_pic']; 		}		public function getItem_name(){			 return self::$_data ['item_name']; 		}		public function getUser_type(){			 return self::$_data ['user_type']; 		}		public function getItem_cash(){			 return self::$_data ['item_cash']; 		}		public function getItem_standard(){			 return self::$_data ['item_standard']; 		}		public function getItem_limit(){			 return self::$_data ['item_limit']; 		}		public function getItem_desc(){			 return self::$_data ['item_desc']; 		}		public function getExt(){			 return self::$_data ['ext']; 		}		public function getIs_open(){			 return self::$_data ['is_open']; 		}		public function getItem_type(){			 return self::$_data ['item_type']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setItem_id($value){ 			 self::$_data ['item_id'] = $value;			 return $this ; 		}		public function setModel_code($value){ 			 self::$_data ['model_code'] = $value;			 return $this ; 		}		public function setItem_code($value){ 			 self::$_data ['item_code'] = $value;			 return $this ; 		}		public function setSmall_pic($value){ 			 self::$_data ['small_pic'] = $value;			 return $this ; 		}		public function setBig_pic($value){ 			 self::$_data ['big_pic'] = $value;			 return $this ; 		}		public function setItem_name($value){ 			 self::$_data ['item_name'] = $value;			 return $this ; 		}		public function setUser_type($value){ 			 self::$_data ['user_type'] = $value;			 return $this ; 		}		public function setItem_cash($value){ 			 self::$_data ['item_cash'] = $value;			 return $this ; 		}		public function setItem_standard($value){ 			 self::$_data ['item_standard'] = $value;			 return $this ; 		}		public function setItem_limit($value){ 			 self::$_data ['item_limit'] = $value;			 return $this ; 		}		public function setItem_desc($value){ 			 self::$_data ['item_desc'] = $value;			 return $this ; 		}		public function setExt($value){ 			 self::$_data ['ext'] = $value;			 return $this ; 		}		public function setIs_open($value){ 			 self::$_data ['is_open'] = $value;			 return $this ; 		}		public function setItem_type($value){ 			 self::$_data ['item_type'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_payitem  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_payitem		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['item_id'] )) { 						self::$_where = array ('item_id' => self::$_data ['item_id'] );						unset(self::$_data['item_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_payitem,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select %s from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select %s from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $sql = sprintf ( $sql, $fields ); 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_payitem records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_payitem, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where item_id = $this->_item_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 