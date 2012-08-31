<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_task_delay  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_task_delay' );		 }	    
	    		public function getDelay_id(){			 return self::$_data ['delay_id']; 		}		public function getTask_id(){			 return self::$_data ['task_id']; 		}		public function getDelay_cash(){			 return self::$_data ['delay_cash']; 		}		public function getDelay_day(){			 return self::$_data ['delay_day']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getDelay_status(){			 return self::$_data ['delay_status']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setDelay_id($value){ 			 self::$_data ['delay_id'] = $value;			 return $this ; 		}		public function setTask_id($value){ 			 self::$_data ['task_id'] = $value;			 return $this ; 		}		public function setDelay_cash($value){ 			 self::$_data ['delay_cash'] = $value;			 return $this ; 		}		public function setDelay_day($value){ 			 self::$_data ['delay_day'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setDelay_status($value){ 			 self::$_data ['delay_status'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_task_delay  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_task_delay		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['delay_id'] )) { 						self::$_where = array ('delay_id' => self::$_data ['delay_id'] );						unset(self::$_data['delay_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_task_delay,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select %s from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select %s from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $sql = sprintf ( $sql, $fields ); 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_task_delay records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_task_delay, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where delay_id = $this->_delay_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 