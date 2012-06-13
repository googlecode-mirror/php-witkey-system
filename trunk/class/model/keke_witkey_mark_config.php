<?php
	class Keke_witkey_mark_config  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_mark_config' );		 }	    
	    		public function getMark_config_id(){			 return self::$_data ['mark_config_id']; 		}		public function getObj(){			 return self::$_data ['obj']; 		}		public function getGood(){			 return self::$_data ['good']; 		}		public function getNormal(){			 return self::$_data ['normal']; 		}		public function getBad(){			 return self::$_data ['bad']; 		}		public function getType(){			 return self::$_data ['type']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setMark_config_id($value){ 			 self::$_data ['mark_config_id'] = $value;			 return $this ; 		}		public function setObj($value){ 			 self::$_data ['obj'] = $value;			 return $this ; 		}		public function setGood($value){ 			 self::$_data ['good'] = $value;			 return $this ; 		}		public function setNormal($value){ 			 self::$_data ['normal'] = $value;			 return $this ; 		}		public function setBad($value){ 			 self::$_data ['bad'] = $value;			 return $this ; 		}		public function setType($value){ 			 self::$_data ['type'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_mark_config  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_mark_config		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['mark_config_id'] )) { 						self::$_data ['where'] = array ('mark_config_id' => self::$_data ['mark_config_id'] );						unset(self::$_data['mark_config_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_mark_config,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_mark_config records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_mark_config, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where mark_config_id = $this->_mark_config_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 