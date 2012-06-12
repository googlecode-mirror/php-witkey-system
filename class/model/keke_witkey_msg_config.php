<?php
	class keke_witkey_msg_config  extends model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_msg_config' );		 }	    
	    		public function getConfig_id(){			 return self::$_data ['config_id']; 		}		public function getK(){			 return self::$_data ['k']; 		}		public function getObj(){			 return self::$_data ['obj']; 		}		public function getDesc(){			 return self::$_data ['desc']; 		}		public function getV(){			 return self::$_data ['v']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setConfig_id($value){ 			 self::$_data ['config_id'] = $value;			 return $this ; 		}		public function setK($value){ 			 self::$_data ['k'] = $value;			 return $this ; 		}		public function setObj($value){ 			 self::$_data ['obj'] = $value;			 return $this ; 		}		public function setDesc($value){ 			 self::$_data ['desc'] = $value;			 return $this ; 		}		public function setV($value){ 			 self::$_data ['v'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}
	    /**		 * insert into  keke_witkey_msg_config  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_msg_config		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['config_id'] )) { 						self::$_data ['where'] = array ('config_id' => self::$_data ['config_id'] );						unset(self::$_data['config_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_msg_config,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_msg_config records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_msg_config, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where config_id = $this->_config_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, database::DELETE ); 		 } 
   } //end 