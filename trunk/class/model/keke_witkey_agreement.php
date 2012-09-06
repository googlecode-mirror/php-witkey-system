<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_agreement  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_agreement' );		 }	    
	    		public function getAgree_id(){			 return self::$_data ['agree_id']; 		}		public function getAgree_status(){			 return self::$_data ['agree_status']; 		}		public function getModel_id(){			 return self::$_data ['model_id']; 		}		public function getTask_id(){			 return self::$_data ['task_id']; 		}		public function getWork_id(){			 return self::$_data ['work_id']; 		}		public function getBuyer_uid(){			 return self::$_data ['buyer_uid']; 		}		public function getBuyer_status(){			 return self::$_data ['buyer_status']; 		}		public function getBuyer_accepttime(){			 return self::$_data ['buyer_accepttime']; 		}		public function getBuyer_confirmtime(){			 return self::$_data ['buyer_confirmtime']; 		}		public function getSeller_uid(){			 return self::$_data ['seller_uid']; 		}		public function getSeller_status(){			 return self::$_data ['seller_status']; 		}		public function getSeller_accepttime(){			 return self::$_data ['seller_accepttime']; 		}		public function getSeller_confirmtime(){			 return self::$_data ['seller_confirmtime']; 		}		public function getAgree_title(){			 return self::$_data ['agree_title']; 		}		public function getFile_ids(){			 return self::$_data ['file_ids']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setAgree_id($value){ 			 self::$_data ['agree_id'] = $value;			 return $this ; 		}		public function setAgree_status($value){ 			 self::$_data ['agree_status'] = $value;			 return $this ; 		}		public function setModel_id($value){ 			 self::$_data ['model_id'] = $value;			 return $this ; 		}		public function setTask_id($value){ 			 self::$_data ['task_id'] = $value;			 return $this ; 		}		public function setWork_id($value){ 			 self::$_data ['work_id'] = $value;			 return $this ; 		}		public function setBuyer_uid($value){ 			 self::$_data ['buyer_uid'] = $value;			 return $this ; 		}		public function setBuyer_status($value){ 			 self::$_data ['buyer_status'] = $value;			 return $this ; 		}		public function setBuyer_accepttime($value){ 			 self::$_data ['buyer_accepttime'] = $value;			 return $this ; 		}		public function setBuyer_confirmtime($value){ 			 self::$_data ['buyer_confirmtime'] = $value;			 return $this ; 		}		public function setSeller_uid($value){ 			 self::$_data ['seller_uid'] = $value;			 return $this ; 		}		public function setSeller_status($value){ 			 self::$_data ['seller_status'] = $value;			 return $this ; 		}		public function setSeller_accepttime($value){ 			 self::$_data ['seller_accepttime'] = $value;			 return $this ; 		}		public function setSeller_confirmtime($value){ 			 self::$_data ['seller_confirmtime'] = $value;			 return $this ; 		}		public function setAgree_title($value){ 			 self::$_data ['agree_title'] = $value;			 return $this ; 		}		public function setFile_ids($value){ 			 self::$_data ['file_ids'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_agreement  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_agreement		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['agree_id'] )) { 						self::$_where = array ('agree_id' => self::$_data ['agree_id'] );						unset(self::$_data['agree_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_agreement,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_agreement records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_agreement, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where agree_id = $this->_agree_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 