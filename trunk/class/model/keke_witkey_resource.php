<?php
	class Keke_witkey_resource  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_resource' );		 }	    
	    		public function getResource_id(){			 return self::$_data ['resource_id']; 		}		public function getResource_name(){			 return self::$_data ['resource_name']; 		}		public function getResource_url(){			 return self::$_data ['resource_url']; 		}		public function getSubmenu_id(){			 return self::$_data ['submenu_id']; 		}		public function getListorder(){			 return self::$_data ['listorder']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setResource_id($value){ 			 self::$_data ['resource_id'] = $value;			 return $this ; 		}		public function setResource_name($value){ 			 self::$_data ['resource_name'] = $value;			 return $this ; 		}		public function setResource_url($value){ 			 self::$_data ['resource_url'] = $value;			 return $this ; 		}		public function setSubmenu_id($value){ 			 self::$_data ['submenu_id'] = $value;			 return $this ; 		}		public function setListorder($value){ 			 self::$_data ['listorder'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_resource  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_resource		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['resource_id'] )) { 						self::$_data ['where'] = array ('resource_id' => self::$_data ['resource_id'] );						unset(self::$_data['resource_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_resource,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_resource records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_resource, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where resource_id = $this->_resource_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 