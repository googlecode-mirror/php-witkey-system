<?php
	class keke_witkey_article  extends model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_article' );		 }	    
	    		public function getArt_id(){			 return self::$_data ['art_id']; 		}		public function getArt_cat_id(){			 return self::$_data ['art_cat_id']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getArt_title(){			 return self::$_data ['art_title']; 		}		public function getArt_source(){			 return self::$_data ['art_source']; 		}		public function getArt_pic(){			 return self::$_data ['art_pic']; 		}		public function getContent(){			 return self::$_data ['content']; 		}		public function getIs_recommend(){			 return self::$_data ['is_recommend']; 		}		public function getSeo_title(){			 return self::$_data ['seo_title']; 		}		public function getSeo_keyword(){			 return self::$_data ['seo_keyword']; 		}		public function getSeo_desc(){			 return self::$_data ['seo_desc']; 		}		public function getListorder(){			 return self::$_data ['listorder']; 		}		public function getIs_show(){			 return self::$_data ['is_show']; 		}		public function getIs_delineas(){			 return self::$_data ['is_delineas']; 		}		public function getPub_time(){			 return self::$_data ['pub_time']; 		}		public function getViews(){			 return self::$_data ['views']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setArt_id($value){ 			 self::$_data ['art_id'] = $value;			 return $this ; 		}		public function setArt_cat_id($value){ 			 self::$_data ['art_cat_id'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setArt_title($value){ 			 self::$_data ['art_title'] = $value;			 return $this ; 		}		public function setArt_source($value){ 			 self::$_data ['art_source'] = $value;			 return $this ; 		}		public function setArt_pic($value){ 			 self::$_data ['art_pic'] = $value;			 return $this ; 		}		public function setContent($value){ 			 self::$_data ['content'] = $value;			 return $this ; 		}		public function setIs_recommend($value){ 			 self::$_data ['is_recommend'] = $value;			 return $this ; 		}		public function setSeo_title($value){ 			 self::$_data ['seo_title'] = $value;			 return $this ; 		}		public function setSeo_keyword($value){ 			 self::$_data ['seo_keyword'] = $value;			 return $this ; 		}		public function setSeo_desc($value){ 			 self::$_data ['seo_desc'] = $value;			 return $this ; 		}		public function setListorder($value){ 			 self::$_data ['listorder'] = $value;			 return $this ; 		}		public function setIs_show($value){ 			 self::$_data ['is_show'] = $value;			 return $this ; 		}		public function setIs_delineas($value){ 			 self::$_data ['is_delineas'] = $value;			 return $this ; 		}		public function setPub_time($value){ 			 self::$_data ['pub_time'] = $value;			 return $this ; 		}		public function setViews($value){ 			 self::$_data ['views'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}
	    /**		 * insert into  keke_witkey_article  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_article		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['art_id'] )) { 						self::$_data ['where'] = array ('art_id' => self::$_data ['art_id'] );						unset(self::$_data['art_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_article,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_article records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_article, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where art_id = $this->_art_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, database::DELETE ); 		 } 
   } //end 