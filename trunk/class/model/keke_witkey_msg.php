<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_msg  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_msg' );		 }	    
	    		public function getMsg_id(){			 return self::$_data ['msg_id']; 		}		public function getMsg_pid(){			 return self::$_data ['msg_pid']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getTo_uid(){			 return self::$_data ['to_uid']; 		}		public function getTo_username(){			 return self::$_data ['to_username']; 		}		public function getMsg_status(){			 return self::$_data ['msg_status']; 		}		public function getView_status(){			 return self::$_data ['view_status']; 		}		public function getTitle(){			 return self::$_data ['title']; 		}		public function getContent(){			 return self::$_data ['content']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setMsg_id($value){ 			 self::$_data ['msg_id'] = $value;			 return $this ; 		}		public function setMsg_pid($value){ 			 self::$_data ['msg_pid'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setTo_uid($value){ 			 self::$_data ['to_uid'] = $value;			 return $this ; 		}		public function setTo_username($value){ 			 self::$_data ['to_username'] = $value;			 return $this ; 		}		public function setMsg_status($value){ 			 self::$_data ['msg_status'] = $value;			 return $this ; 		}		public function setView_status($value){ 			 self::$_data ['view_status'] = $value;			 return $this ; 		}		public function setTitle($value){ 			 self::$_data ['title'] = $value;			 return $this ; 		}		public function setContent($value){ 			 self::$_data ['content'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array,array('Model','remove_null')); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_msg  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_msg		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['msg_id'] )) { 						self::$_where = array ('msg_id' => self::$_data ['msg_id'] );						unset(self::$_data['msg_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_msg,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_msg records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_msg, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where msg_id = $this->_msg_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 