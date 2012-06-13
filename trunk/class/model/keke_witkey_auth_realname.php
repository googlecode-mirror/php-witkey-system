<?php
	class keke_witkey_auth_realname  extends model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_auth_realname' );		 }	    
	    		public function getRealname_a_id(){			 return self::$_data ['realname_a_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getRealname(){			 return self::$_data ['realname']; 		}		public function getId_card(){			 return self::$_data ['id_card']; 		}		public function getId_pic(){			 return self::$_data ['id_pic']; 		}		public function getCash(){			 return self::$_data ['cash']; 		}		public function getStart_time(){			 return self::$_data ['start_time']; 		}		public function getEnd_time(){			 return self::$_data ['end_time']; 		}		public function getAuth_status(){			 return self::$_data ['auth_status']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setRealname_a_id($value){ 			 self::$_data ['realname_a_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setRealname($value){ 			 self::$_data ['realname'] = $value;			 return $this ; 		}		public function setId_card($value){ 			 self::$_data ['id_card'] = $value;			 return $this ; 		}		public function setId_pic($value){ 			 self::$_data ['id_pic'] = $value;			 return $this ; 		}		public function setCash($value){ 			 self::$_data ['cash'] = $value;			 return $this ; 		}		public function setStart_time($value){ 			 self::$_data ['start_time'] = $value;			 return $this ; 		}		public function setEnd_time($value){ 			 self::$_data ['end_time'] = $value;			 return $this ; 		}		public function setAuth_status($value){ 			 self::$_data ['auth_status'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}
	    /**		 * insert into  keke_witkey_auth_realname  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_auth_realname		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['realname_a_id'] )) { 						self::$_data ['where'] = array ('realname_a_id' => self::$_data ['realname_a_id'] );						unset(self::$_data['realname_a_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_auth_realname,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_auth_realname records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_auth_realname, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where realname_a_id = $this->_realname_a_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 