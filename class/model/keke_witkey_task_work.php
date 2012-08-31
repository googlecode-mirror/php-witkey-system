<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_task_work  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_task_work' );		 }	    
	    		public function getWork_id(){			 return self::$_data ['work_id']; 		}		public function getTask_id(){			 return self::$_data ['task_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getWork_title(){			 return self::$_data ['work_title']; 		}		public function getWork_price(){			 return self::$_data ['work_price']; 		}		public function getWork_desc(){			 return self::$_data ['work_desc']; 		}		public function getWork_file(){			 return self::$_data ['work_file']; 		}		public function getWork_pic(){			 return self::$_data ['work_pic']; 		}		public function getWork_time(){			 return self::$_data ['work_time']; 		}		public function getHide_work(){			 return self::$_data ['hide_work']; 		}		public function getVote_num(){			 return self::$_data ['vote_num']; 		}		public function getComment_num(){			 return self::$_data ['comment_num']; 		}		public function getWork_status(){			 return self::$_data ['work_status']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setWork_id($value){ 			 self::$_data ['work_id'] = $value;			 return $this ; 		}		public function setTask_id($value){ 			 self::$_data ['task_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setWork_title($value){ 			 self::$_data ['work_title'] = $value;			 return $this ; 		}		public function setWork_price($value){ 			 self::$_data ['work_price'] = $value;			 return $this ; 		}		public function setWork_desc($value){ 			 self::$_data ['work_desc'] = $value;			 return $this ; 		}		public function setWork_file($value){ 			 self::$_data ['work_file'] = $value;			 return $this ; 		}		public function setWork_pic($value){ 			 self::$_data ['work_pic'] = $value;			 return $this ; 		}		public function setWork_time($value){ 			 self::$_data ['work_time'] = $value;			 return $this ; 		}		public function setHide_work($value){ 			 self::$_data ['hide_work'] = $value;			 return $this ; 		}		public function setVote_num($value){ 			 self::$_data ['vote_num'] = $value;			 return $this ; 		}		public function setComment_num($value){ 			 self::$_data ['comment_num'] = $value;			 return $this ; 		}		public function setWork_status($value){ 			 self::$_data ['work_status'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_task_work  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_task_work		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['work_id'] )) { 						self::$_where = array ('work_id' => self::$_data ['work_id'] );						unset(self::$_data['work_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_task_work,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select %s from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select %s from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $sql = sprintf ( $sql, $fields ); 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_task_work records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_task_work, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where work_id = $this->_work_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 