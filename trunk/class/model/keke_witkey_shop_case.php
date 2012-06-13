<?php
	class Keke_witkey_shop_case  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_shop_case' );		 }	    
	    		public function getCase_id(){			 return self::$_data ['case_id']; 		}		public function getCate_id(){			 return self::$_data ['cate_id']; 		}		public function getShop_id(){			 return self::$_data ['shop_id']; 		}		public function getIndus_id(){			 return self::$_data ['indus_id']; 		}		public function getCase_type(){			 return self::$_data ['case_type']; 		}		public function getService_id(){			 return self::$_data ['service_id']; 		}		public function getCase_name(){			 return self::$_data ['case_name']; 		}		public function getCase_desc(){			 return self::$_data ['case_desc']; 		}		public function getCase_pic(){			 return self::$_data ['case_pic']; 		}		public function getCase_url(){			 return self::$_data ['case_url']; 		}		public function getStart_time(){			 return self::$_data ['start_time']; 		}		public function getEnd_time(){			 return self::$_data ['end_time']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setCase_id($value){ 			 self::$_data ['case_id'] = $value;			 return $this ; 		}		public function setCate_id($value){ 			 self::$_data ['cate_id'] = $value;			 return $this ; 		}		public function setShop_id($value){ 			 self::$_data ['shop_id'] = $value;			 return $this ; 		}		public function setIndus_id($value){ 			 self::$_data ['indus_id'] = $value;			 return $this ; 		}		public function setCase_type($value){ 			 self::$_data ['case_type'] = $value;			 return $this ; 		}		public function setService_id($value){ 			 self::$_data ['service_id'] = $value;			 return $this ; 		}		public function setCase_name($value){ 			 self::$_data ['case_name'] = $value;			 return $this ; 		}		public function setCase_desc($value){ 			 self::$_data ['case_desc'] = $value;			 return $this ; 		}		public function setCase_pic($value){ 			 self::$_data ['case_pic'] = $value;			 return $this ; 		}		public function setCase_url($value){ 			 self::$_data ['case_url'] = $value;			 return $this ; 		}		public function setStart_time($value){ 			 self::$_data ['start_time'] = $value;			 return $this ; 		}		public function setEnd_time($value){ 			 self::$_data ['end_time'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_shop_case  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_shop_case		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['case_id'] )) { 						self::$_data ['where'] = array ('case_id' => self::$_data ['case_id'] );						unset(self::$_data['case_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_shop_case,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_shop_case records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_shop_case, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where case_id = $this->_case_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 