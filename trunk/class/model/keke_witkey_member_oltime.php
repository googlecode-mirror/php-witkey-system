<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_member_oltime  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_member_oltime' );		 }	    
	    		public function getOltime_id(){			 return self::$_data ['oltime_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getUser_status(){			 return self::$_data ['user_status']; 		}		public function getLast_op_time(){			 return self::$_data ['last_op_time']; 		}		public function getOnline_total_time(){			 return self::$_data ['online_total_time']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setOltime_id($value){ 			 self::$_data ['oltime_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setUser_status($value){ 			 self::$_data ['user_status'] = $value;			 return $this ; 		}		public function setLast_op_time($value){ 			 self::$_data ['last_op_time'] = $value;			 return $this ; 		}		public function setOnline_total_time($value){ 			 self::$_data ['online_total_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_member_oltime  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_member_oltime		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['oltime_id'] )) { 						self::$_where = array ('oltime_id' => self::$_data ['oltime_id'] );						unset(self::$_data['oltime_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_member_oltime,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select %s from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select %s from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $sql = sprintf ( $sql, $fields ); 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_member_oltime records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_member_oltime, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where oltime_id = $this->_oltime_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 