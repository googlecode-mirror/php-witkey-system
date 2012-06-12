<?php
	class keke_witkey_system_log  extends model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_system_log' );		 }	    
	    		public function getLog_id(){			 return self::$_data ['log_id']; 		}		public function getLog_type(){			 return self::$_data ['log_type']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getUser_type(){			 return self::$_data ['user_type']; 		}		public function getLog_content(){			 return self::$_data ['log_content']; 		}		public function getLog_ip(){			 return self::$_data ['log_ip']; 		}		public function getLog_time(){			 return self::$_data ['log_time']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setLog_id($value){ 			 self::$_data ['log_id'] = $value;			 return $this ; 		}		public function setLog_type($value){ 			 self::$_data ['log_type'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setUser_type($value){ 			 self::$_data ['user_type'] = $value;			 return $this ; 		}		public function setLog_content($value){ 			 self::$_data ['log_content'] = $value;			 return $this ; 		}		public function setLog_ip($value){ 			 self::$_data ['log_ip'] = $value;			 return $this ; 		}		public function setLog_time($value){ 			 self::$_data ['log_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}
	    /**		 * insert into  keke_witkey_system_log  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_system_log		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['log_id'] )) { 						self::$_data ['where'] = array ('log_id' => self::$_data ['log_id'] );						unset(self::$_data['log_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_system_log,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_system_log records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_system_log, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where log_id = $this->_log_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, database::DELETE ); 		 } 
   } //end 