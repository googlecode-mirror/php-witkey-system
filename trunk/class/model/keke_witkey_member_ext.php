<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_member_ext  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_member_ext' );		 }	    
	    		public function getExt_id(){			 return self::$_data ['ext_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getK(){			 return self::$_data ['k']; 		}		public function getV1(){			 return self::$_data ['v1']; 		}		public function getV2(){			 return self::$_data ['v2']; 		}		public function getV3(){			 return self::$_data ['v3']; 		}		public function getV4(){			 return self::$_data ['v4']; 		}		public function getV5(){			 return self::$_data ['v5']; 		}		public function getType(){			 return self::$_data ['type']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setExt_id($value){ 			 self::$_data ['ext_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setK($value){ 			 self::$_data ['k'] = $value;			 return $this ; 		}		public function setV1($value){ 			 self::$_data ['v1'] = $value;			 return $this ; 		}		public function setV2($value){ 			 self::$_data ['v2'] = $value;			 return $this ; 		}		public function setV3($value){ 			 self::$_data ['v3'] = $value;			 return $this ; 		}		public function setV4($value){ 			 self::$_data ['v4'] = $value;			 return $this ; 		}		public function setV5($value){ 			 self::$_data ['v5'] = $value;			 return $this ; 		}		public function setType($value){ 			 self::$_data ['type'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array,array('Model','remove_null')); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_member_ext  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_member_ext		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['ext_id'] )) { 						self::$_where = array ('ext_id' => self::$_data ['ext_id'] );						unset(self::$_data['ext_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_member_ext,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_member_ext records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_member_ext, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where ext_id = $this->_ext_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 