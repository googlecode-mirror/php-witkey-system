<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_task_time_rule  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_task_time_rule' );		 }	    
	    		public function getDay_rule_id(){			 return self::$_data ['day_rule_id']; 		}		public function getRule_cash(){			 return self::$_data ['rule_cash']; 		}		public function getRule_day(){			 return self::$_data ['rule_day']; 		}		public function getModel_id(){			 return self::$_data ['model_id']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setDay_rule_id($value){ 			 self::$_data ['day_rule_id'] = $value;			 return $this ; 		}		public function setRule_cash($value){ 			 self::$_data ['rule_cash'] = $value;			 return $this ; 		}		public function setRule_day($value){ 			 self::$_data ['rule_day'] = $value;			 return $this ; 		}		public function setModel_id($value){ 			 self::$_data ['model_id'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_task_time_rule  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_task_time_rule		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['day_rule_id'] )) { 						self::$_where = array ('day_rule_id' => self::$_data ['day_rule_id'] );						unset(self::$_data['day_rule_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_task_time_rule,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_task_time_rule records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_task_time_rule, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where day_rule_id = $this->_day_rule_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 