<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_task_taobao_views  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_task_taobao_views' );		 }	    
	    		public function getView_id(){			 return self::$_data ['view_id']; 		}		public function getTask_id(){			 return self::$_data ['task_id']; 		}		public function getWork_id(){			 return self::$_data ['work_id']; 		}		public function getTbwk_id(){			 return self::$_data ['tbwk_id']; 		}		public function getRefer_url(){			 return self::$_data ['refer_url']; 		}		public function getUser_ip(){			 return self::$_data ['user_ip']; 		}		public function getUser_agent(){			 return self::$_data ['user_agent']; 		}		public function getClick_time(){			 return self::$_data ['click_time']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setView_id($value){ 			 self::$_data ['view_id'] = $value;			 return $this ; 		}		public function setTask_id($value){ 			 self::$_data ['task_id'] = $value;			 return $this ; 		}		public function setWork_id($value){ 			 self::$_data ['work_id'] = $value;			 return $this ; 		}		public function setTbwk_id($value){ 			 self::$_data ['tbwk_id'] = $value;			 return $this ; 		}		public function setRefer_url($value){ 			 self::$_data ['refer_url'] = $value;			 return $this ; 		}		public function setUser_ip($value){ 			 self::$_data ['user_ip'] = $value;			 return $this ; 		}		public function setUser_agent($value){ 			 self::$_data ['user_agent'] = $value;			 return $this ; 		}		public function setClick_time($value){ 			 self::$_data ['click_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_task_taobao_views  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_task_taobao_views		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['view_id'] )) { 						self::$_where = array ('view_id' => self::$_data ['view_id'] );						unset(self::$_data['view_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_task_taobao_views,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_task_taobao_views records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_task_taobao_views, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where view_id = $this->_view_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 