<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_comment  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_comment' );		 }	    
	    		public function getComment_id(){			 return self::$_data ['comment_id']; 		}		public function getObj_id(){			 return self::$_data ['obj_id']; 		}		public function getOrigin_id(){			 return self::$_data ['origin_id']; 		}		public function getObj_type(){			 return self::$_data ['obj_type']; 		}		public function getP_id(){			 return self::$_data ['p_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getContent(){			 return self::$_data ['content']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getStatus(){			 return self::$_data ['status']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setComment_id($value){ 			 self::$_data ['comment_id'] = $value;			 return $this ; 		}		public function setObj_id($value){ 			 self::$_data ['obj_id'] = $value;			 return $this ; 		}		public function setOrigin_id($value){ 			 self::$_data ['origin_id'] = $value;			 return $this ; 		}		public function setObj_type($value){ 			 self::$_data ['obj_type'] = $value;			 return $this ; 		}		public function setP_id($value){ 			 self::$_data ['p_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setContent($value){ 			 self::$_data ['content'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setStatus($value){ 			 self::$_data ['status'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_comment  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_comment		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['comment_id'] )) { 						self::$_where = array ('comment_id' => self::$_data ['comment_id'] );						unset(self::$_data['comment_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_comment,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select %s from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select %s from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $sql = sprintf ( $sql, $fields ); 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_comment records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_comment, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where comment_id = $this->_comment_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 