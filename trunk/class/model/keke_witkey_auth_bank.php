<?php
	class keke_witkey_auth_bank  extends model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_auth_bank' );		 }	    
	    		public function getBank_a_id(){			 return self::$_data ['bank_a_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getBank_account(){			 return self::$_data ['bank_account']; 		}		public function getBank_name(){			 return self::$_data ['bank_name']; 		}		public function getBank_id(){			 return self::$_data ['bank_id']; 		}		public function getDeposit_area(){			 return self::$_data ['deposit_area']; 		}		public function getDeposit_name(){			 return self::$_data ['deposit_name']; 		}		public function getPay_to_user_cash(){			 return self::$_data ['pay_to_user_cash']; 		}		public function getUser_get_cash(){			 return self::$_data ['user_get_cash']; 		}		public function getPay_time(){			 return self::$_data ['pay_time']; 		}		public function getCash(){			 return self::$_data ['cash']; 		}		public function getStart_time(){			 return self::$_data ['start_time']; 		}		public function getEnd_time(){			 return self::$_data ['end_time']; 		}		public function getAuth_status(){			 return self::$_data ['auth_status']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setBank_a_id($value){ 			 self::$_data ['bank_a_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setBank_account($value){ 			 self::$_data ['bank_account'] = $value;			 return $this ; 		}		public function setBank_name($value){ 			 self::$_data ['bank_name'] = $value;			 return $this ; 		}		public function setBank_id($value){ 			 self::$_data ['bank_id'] = $value;			 return $this ; 		}		public function setDeposit_area($value){ 			 self::$_data ['deposit_area'] = $value;			 return $this ; 		}		public function setDeposit_name($value){ 			 self::$_data ['deposit_name'] = $value;			 return $this ; 		}		public function setPay_to_user_cash($value){ 			 self::$_data ['pay_to_user_cash'] = $value;			 return $this ; 		}		public function setUser_get_cash($value){ 			 self::$_data ['user_get_cash'] = $value;			 return $this ; 		}		public function setPay_time($value){ 			 self::$_data ['pay_time'] = $value;			 return $this ; 		}		public function setCash($value){ 			 self::$_data ['cash'] = $value;			 return $this ; 		}		public function setStart_time($value){ 			 self::$_data ['start_time'] = $value;			 return $this ; 		}		public function setEnd_time($value){ 			 self::$_data ['end_time'] = $value;			 return $this ; 		}		public function setAuth_status($value){ 			 self::$_data ['auth_status'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}
	    /**		 * insert into  keke_witkey_auth_bank  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_auth_bank		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['bank_a_id'] )) { 						self::$_data ['where'] = array ('bank_a_id' => self::$_data ['bank_a_id'] );						unset(self::$_data['bank_a_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_auth_bank,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_auth_bank records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_auth_bank, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where bank_a_id = $this->_bank_a_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 