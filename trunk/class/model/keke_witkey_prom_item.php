<?php
	class Keke_witkey_prom_item  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_prom_item' );		 }	    
	    		public function getItem_id(){			 return self::$_data ['item_id']; 		}		public function getItem_type(){			 return self::$_data ['item_type']; 		}		public function getProm_type(){			 return self::$_data ['prom_type']; 		}		public function getObj_id(){			 return self::$_data ['obj_id']; 		}		public function getItem_name(){			 return self::$_data ['item_name']; 		}		public function getItem_pic(){			 return self::$_data ['item_pic']; 		}		public function getItem_content(){			 return self::$_data ['item_content']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setItem_id($value){ 			 self::$_data ['item_id'] = $value;			 return $this ; 		}		public function setItem_type($value){ 			 self::$_data ['item_type'] = $value;			 return $this ; 		}		public function setProm_type($value){ 			 self::$_data ['prom_type'] = $value;			 return $this ; 		}		public function setObj_id($value){ 			 self::$_data ['obj_id'] = $value;			 return $this ; 		}		public function setItem_name($value){ 			 self::$_data ['item_name'] = $value;			 return $this ; 		}		public function setItem_pic($value){ 			 self::$_data ['item_pic'] = $value;			 return $this ; 		}		public function setItem_content($value){ 			 self::$_data ['item_content'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_prom_item  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_prom_item		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['item_id'] )) { 						self::$_data ['where'] = array ('item_id' => self::$_data ['item_id'] );						unset(self::$_data['item_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_prom_item,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_prom_item records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_prom_item, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where item_id = $this->_item_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 