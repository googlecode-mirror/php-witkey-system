<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_msg_tpl  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_msg_tpl' );		 }	    
	    		public function getTpl_id(){			 return self::$_data ['tpl_id']; 		}		public function getTpl_code(){			 return self::$_data ['tpl_code']; 		}		public function getContent(){			 return self::$_data ['content']; 		}		public function getSend_type(){			 return self::$_data ['send_type']; 		}		public function getListorder(){			 return self::$_data ['listorder']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setTpl_id($value){ 			 self::$_data ['tpl_id'] = $value;			 return $this ; 		}		public function setTpl_code($value){ 			 self::$_data ['tpl_code'] = $value;			 return $this ; 		}		public function setContent($value){ 			 self::$_data ['content'] = $value;			 return $this ; 		}		public function setSend_type($value){ 			 self::$_data ['send_type'] = $value;			 return $this ; 		}		public function setListorder($value){ 			 self::$_data ['listorder'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array,array('Model','remove_null')); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_msg_tpl  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_msg_tpl		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['tpl_id'] )) { 						self::$_where = array ('tpl_id' => self::$_data ['tpl_id'] );						unset(self::$_data['tpl_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_msg_tpl,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_msg_tpl records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_msg_tpl, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where tpl_id = $this->_tpl_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 