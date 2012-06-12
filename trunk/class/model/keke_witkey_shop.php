<?php
	class keke_witkey_shop  extends model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_shop' );		 }	    
	    		public function getShop_id(){			 return self::$_data ['shop_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getShop_type(){			 return self::$_data ['shop_type']; 		}		public function getIndus_pid(){			 return self::$_data ['indus_pid']; 		}		public function getShop_name(){			 return self::$_data ['shop_name']; 		}		public function getService_range(){			 return self::$_data ['service_range']; 		}		public function getShop_desc(){			 return self::$_data ['shop_desc']; 		}		public function getWork(){			 return self::$_data ['work']; 		}		public function getWork_year(){			 return self::$_data ['work_year']; 		}		public function getKeyword(){			 return self::$_data ['keyword']; 		}		public function getShop_background(){			 return self::$_data ['shop_background']; 		}		public function getLogo(){			 return self::$_data ['logo']; 		}		public function getBanner(){			 return self::$_data ['banner']; 		}		public function getShop_slogans(){			 return self::$_data ['shop_slogans']; 		}		public function getShop_skin(){			 return self::$_data ['shop_skin']; 		}		public function getShop_backstyle(){			 return self::$_data ['shop_backstyle']; 		}		public function getShop_font(){			 return self::$_data ['shop_font']; 		}		public function getShop_active(){			 return self::$_data ['shop_active']; 		}		public function getIs_close(){			 return self::$_data ['is_close']; 		}		public function getViews(){			 return self::$_data ['views']; 		}		public function getFocus_num(){			 return self::$_data ['focus_num']; 		}		public function getOn_time(){			 return self::$_data ['on_time']; 		}		public function getHomebanner(){			 return self::$_data ['homebanner']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setShop_id($value){ 			 self::$_data ['shop_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setShop_type($value){ 			 self::$_data ['shop_type'] = $value;			 return $this ; 		}		public function setIndus_pid($value){ 			 self::$_data ['indus_pid'] = $value;			 return $this ; 		}		public function setShop_name($value){ 			 self::$_data ['shop_name'] = $value;			 return $this ; 		}		public function setService_range($value){ 			 self::$_data ['service_range'] = $value;			 return $this ; 		}		public function setShop_desc($value){ 			 self::$_data ['shop_desc'] = $value;			 return $this ; 		}		public function setWork($value){ 			 self::$_data ['work'] = $value;			 return $this ; 		}		public function setWork_year($value){ 			 self::$_data ['work_year'] = $value;			 return $this ; 		}		public function setKeyword($value){ 			 self::$_data ['keyword'] = $value;			 return $this ; 		}		public function setShop_background($value){ 			 self::$_data ['shop_background'] = $value;			 return $this ; 		}		public function setLogo($value){ 			 self::$_data ['logo'] = $value;			 return $this ; 		}		public function setBanner($value){ 			 self::$_data ['banner'] = $value;			 return $this ; 		}		public function setShop_slogans($value){ 			 self::$_data ['shop_slogans'] = $value;			 return $this ; 		}		public function setShop_skin($value){ 			 self::$_data ['shop_skin'] = $value;			 return $this ; 		}		public function setShop_backstyle($value){ 			 self::$_data ['shop_backstyle'] = $value;			 return $this ; 		}		public function setShop_font($value){ 			 self::$_data ['shop_font'] = $value;			 return $this ; 		}		public function setShop_active($value){ 			 self::$_data ['shop_active'] = $value;			 return $this ; 		}		public function setIs_close($value){ 			 self::$_data ['is_close'] = $value;			 return $this ; 		}		public function setViews($value){ 			 self::$_data ['views'] = $value;			 return $this ; 		}		public function setFocus_num($value){ 			 self::$_data ['focus_num'] = $value;			 return $this ; 		}		public function setOn_time($value){ 			 self::$_data ['on_time'] = $value;			 return $this ; 		}		public function setHomebanner($value){ 			 self::$_data ['homebanner'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}
	    /**		 * insert into  keke_witkey_shop  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_shop		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['shop_id'] )) { 						self::$_data ['where'] = array ('shop_id' => self::$_data ['shop_id'] );						unset(self::$_data['shop_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_shop,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_shop records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_shop, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where shop_id = $this->_shop_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, database::DELETE ); 		 } 
   } //end 