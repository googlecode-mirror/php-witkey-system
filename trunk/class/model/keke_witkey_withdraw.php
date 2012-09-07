<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_withdraw  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_withdraw' );		 }	    
	    		public function getWithdraw_id(){			 return self::$_data ['withdraw_id']; 		}		public function getWithdraw_cash(){			 return self::$_data ['withdraw_cash']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getPay_username(){			 return self::$_data ['pay_username']; 		}		public function getWithdraw_status(){			 return self::$_data ['withdraw_status']; 		}		public function getApplic_time(){			 return self::$_data ['applic_time']; 		}		public function getProcess_uid(){			 return self::$_data ['process_uid']; 		}		public function getProcess_username(){			 return self::$_data ['process_username']; 		}		public function getProcess_time(){			 return self::$_data ['process_time']; 		}		public function getPay_account(){			 return self::$_data ['pay_account']; 		}		public function getPay_type(){			 return self::$_data ['pay_type']; 		}		public function getFee(){			 return self::$_data ['fee']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setWithdraw_id($value){ 			 self::$_data ['withdraw_id'] = $value;			 return $this ; 		}		public function setWithdraw_cash($value){ 			 self::$_data ['withdraw_cash'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setPay_username($value){ 			 self::$_data ['pay_username'] = $value;			 return $this ; 		}		public function setWithdraw_status($value){ 			 self::$_data ['withdraw_status'] = $value;			 return $this ; 		}		public function setApplic_time($value){ 			 self::$_data ['applic_time'] = $value;			 return $this ; 		}		public function setProcess_uid($value){ 			 self::$_data ['process_uid'] = $value;			 return $this ; 		}		public function setProcess_username($value){ 			 self::$_data ['process_username'] = $value;			 return $this ; 		}		public function setProcess_time($value){ 			 self::$_data ['process_time'] = $value;			 return $this ; 		}		public function setPay_account($value){ 			 self::$_data ['pay_account'] = $value;			 return $this ; 		}		public function setPay_type($value){ 			 self::$_data ['pay_type'] = $value;			 return $this ; 		}		public function setFee($value){ 			 self::$_data ['fee'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_withdraw  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_withdraw		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['withdraw_id'] )) { 						self::$_where = array ('withdraw_id' => self::$_data ['withdraw_id'] );						unset(self::$_data['withdraw_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_withdraw,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_withdraw records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_withdraw, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where withdraw_id = $this->_withdraw_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 