<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_task_match  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_task_match' );		 }	    
	    		public function getMt_id(){			 return self::$_data ['mt_id']; 		}		public function getTask_id(){			 return self::$_data ['task_id']; 		}		public function getHirer_deposit(){			 return self::$_data ['hirer_deposit']; 		}		public function getDeposit_cash(){			 return self::$_data ['deposit_cash']; 		}		public function getDeposit_credit(){			 return self::$_data ['deposit_credit']; 		}		public function getHost_amount(){			 return self::$_data ['host_amount']; 		}		public function getHost_cash(){			 return self::$_data ['host_cash']; 		}		public function getHost_credit(){			 return self::$_data ['host_credit']; 		}		public function getDeposit_rate(){			 return self::$_data ['deposit_rate']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setMt_id($value){ 			 self::$_data ['mt_id'] = $value;			 return $this ; 		}		public function setTask_id($value){ 			 self::$_data ['task_id'] = $value;			 return $this ; 		}		public function setHirer_deposit($value){ 			 self::$_data ['hirer_deposit'] = $value;			 return $this ; 		}		public function setDeposit_cash($value){ 			 self::$_data ['deposit_cash'] = $value;			 return $this ; 		}		public function setDeposit_credit($value){ 			 self::$_data ['deposit_credit'] = $value;			 return $this ; 		}		public function setHost_amount($value){ 			 self::$_data ['host_amount'] = $value;			 return $this ; 		}		public function setHost_cash($value){ 			 self::$_data ['host_cash'] = $value;			 return $this ; 		}		public function setHost_credit($value){ 			 self::$_data ['host_credit'] = $value;			 return $this ; 		}		public function setDeposit_rate($value){ 			 self::$_data ['deposit_rate'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array,array('Model','remove_null')); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_task_match  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_task_match		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['mt_id'] )) { 						self::$_where = array ('mt_id' => self::$_data ['mt_id'] );						unset(self::$_data['mt_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_task_match,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_task_match records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_task_match, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where mt_id = $this->_mt_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 