<?php
	class Keke_witkey_shop_member  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_shop_member' );		 }	    
	    		public function getMember_id(){			 return self::$_data ['member_id']; 		}		public function getShop_id(){			 return self::$_data ['shop_id']; 		}		public function getTruename(){			 return self::$_data ['truename']; 		}		public function getMember_pic(){			 return self::$_data ['member_pic']; 		}		public function getMember_job(){			 return self::$_data ['member_job']; 		}		public function getEntry_age(){			 return self::$_data ['entry_age']; 		}		public function getTop_eduction(){			 return self::$_data ['top_eduction']; 		}		public function getSchool(){			 return self::$_data ['school']; 		}		public function getMember_desc(){			 return self::$_data ['member_desc']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setMember_id($value){ 			 self::$_data ['member_id'] = $value;			 return $this ; 		}		public function setShop_id($value){ 			 self::$_data ['shop_id'] = $value;			 return $this ; 		}		public function setTruename($value){ 			 self::$_data ['truename'] = $value;			 return $this ; 		}		public function setMember_pic($value){ 			 self::$_data ['member_pic'] = $value;			 return $this ; 		}		public function setMember_job($value){ 			 self::$_data ['member_job'] = $value;			 return $this ; 		}		public function setEntry_age($value){ 			 self::$_data ['entry_age'] = $value;			 return $this ; 		}		public function setTop_eduction($value){ 			 self::$_data ['top_eduction'] = $value;			 return $this ; 		}		public function setSchool($value){ 			 self::$_data ['school'] = $value;			 return $this ; 		}		public function setMember_desc($value){ 			 self::$_data ['member_desc'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = $array; 			return $this; 		} 
	    /**		 * insert into  keke_witkey_shop_member  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_shop_member		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['member_id'] )) { 						self::$_data ['where'] = array ('member_id' => self::$_data ['member_id'] );						unset(self::$_data['member_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_shop_member,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_shop_member records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_shop_member, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where member_id = $this->_member_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 