<?php defined ('IN_KEKE' ) or die ( 'Access Denied' );
	class Keke_witkey_space  extends Model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_space' );		 }	    
	    		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getGroup_id(){			 return self::$_data ['group_id']; 		}		public function getStatus(){			 return self::$_data ['status']; 		}		public function getUser_type(){			 return self::$_data ['user_type']; 		}		public function getSex(){			 return self::$_data ['sex']; 		}		public function getResidency(){			 return self::$_data ['residency']; 		}		public function getAddress(){			 return self::$_data ['address']; 		}		public function getBirthday(){			 return self::$_data ['birthday']; 		}		public function getTruename(){			 return self::$_data ['truename']; 		}		public function getEmail(){			 return self::$_data ['email']; 		}		public function getQq(){			 return self::$_data ['qq']; 		}		public function getMsn(){			 return self::$_data ['msn']; 		}		public function getFax(){			 return self::$_data ['fax']; 		}		public function getPhone(){			 return self::$_data ['phone']; 		}		public function getMobile(){			 return self::$_data ['mobile']; 		}		public function getIndus_id(){			 return self::$_data ['indus_id']; 		}		public function getIndus_pid(){			 return self::$_data ['indus_pid']; 		}		public function getSkill_ids(){			 return self::$_data ['skill_ids']; 		}		public function getSummary(){			 return self::$_data ['summary']; 		}		public function getExperience(){			 return self::$_data ['experience']; 		}		public function getReg_time(){			 return self::$_data ['reg_time']; 		}		public function getReg_ip(){			 return self::$_data ['reg_ip']; 		}		public function getDomain(){			 return self::$_data ['domain']; 		}		public function getCredit(){			 return self::$_data ['credit']; 		}		public function getBalance(){			 return self::$_data ['balance']; 		}		public function getPub_num(){			 return self::$_data ['pub_num']; 		}		public function getTake_num(){			 return self::$_data ['take_num']; 		}		public function getNominate_num(){			 return self::$_data ['nominate_num']; 		}		public function getAccepted_num(){			 return self::$_data ['accepted_num']; 		}		public function getMsg_num(){			 return self::$_data ['msg_num']; 		}		public function getScore(){			 return self::$_data ['score']; 		}		public function getBuyer_credit(){			 return self::$_data ['buyer_credit']; 		}		public function getBuyer_good_num(){			 return self::$_data ['buyer_good_num']; 		}		public function getBuyer_total_num(){			 return self::$_data ['buyer_total_num']; 		}		public function getSeller_credit(){			 return self::$_data ['seller_credit']; 		}		public function getSeller_good_num(){			 return self::$_data ['seller_good_num']; 		}		public function getSeller_total_num(){			 return self::$_data ['seller_total_num']; 		}		public function getLast_login_time(){			 return self::$_data ['last_login_time']; 		}		public function getClient_status(){			 return self::$_data ['client_status']; 		}		public function getRecommend(){			 return self::$_data ['recommend']; 		}		public function getBuyer_level(){			 return self::$_data ['buyer_level']; 		}		public function getSeller_level(){			 return self::$_data ['seller_level']; 		}		public function getWhere(){			 return self::$_where; 		}
	    		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setGroup_id($value){ 			 self::$_data ['group_id'] = $value;			 return $this ; 		}		public function setStatus($value){ 			 self::$_data ['status'] = $value;			 return $this ; 		}		public function setUser_type($value){ 			 self::$_data ['user_type'] = $value;			 return $this ; 		}		public function setSex($value){ 			 self::$_data ['sex'] = $value;			 return $this ; 		}		public function setResidency($value){ 			 self::$_data ['residency'] = $value;			 return $this ; 		}		public function setAddress($value){ 			 self::$_data ['address'] = $value;			 return $this ; 		}		public function setBirthday($value){ 			 self::$_data ['birthday'] = $value;			 return $this ; 		}		public function setTruename($value){ 			 self::$_data ['truename'] = $value;			 return $this ; 		}		public function setEmail($value){ 			 self::$_data ['email'] = $value;			 return $this ; 		}		public function setQq($value){ 			 self::$_data ['qq'] = $value;			 return $this ; 		}		public function setMsn($value){ 			 self::$_data ['msn'] = $value;			 return $this ; 		}		public function setFax($value){ 			 self::$_data ['fax'] = $value;			 return $this ; 		}		public function setPhone($value){ 			 self::$_data ['phone'] = $value;			 return $this ; 		}		public function setMobile($value){ 			 self::$_data ['mobile'] = $value;			 return $this ; 		}		public function setIndus_id($value){ 			 self::$_data ['indus_id'] = $value;			 return $this ; 		}		public function setIndus_pid($value){ 			 self::$_data ['indus_pid'] = $value;			 return $this ; 		}		public function setSkill_ids($value){ 			 self::$_data ['skill_ids'] = $value;			 return $this ; 		}		public function setSummary($value){ 			 self::$_data ['summary'] = $value;			 return $this ; 		}		public function setExperience($value){ 			 self::$_data ['experience'] = $value;			 return $this ; 		}		public function setReg_time($value){ 			 self::$_data ['reg_time'] = $value;			 return $this ; 		}		public function setReg_ip($value){ 			 self::$_data ['reg_ip'] = $value;			 return $this ; 		}		public function setDomain($value){ 			 self::$_data ['domain'] = $value;			 return $this ; 		}		public function setCredit($value){ 			 self::$_data ['credit'] = $value;			 return $this ; 		}		public function setBalance($value){ 			 self::$_data ['balance'] = $value;			 return $this ; 		}		public function setPub_num($value){ 			 self::$_data ['pub_num'] = $value;			 return $this ; 		}		public function setTake_num($value){ 			 self::$_data ['take_num'] = $value;			 return $this ; 		}		public function setNominate_num($value){ 			 self::$_data ['nominate_num'] = $value;			 return $this ; 		}		public function setAccepted_num($value){ 			 self::$_data ['accepted_num'] = $value;			 return $this ; 		}		public function setMsg_num($value){ 			 self::$_data ['msg_num'] = $value;			 return $this ; 		}		public function setScore($value){ 			 self::$_data ['score'] = $value;			 return $this ; 		}		public function setBuyer_credit($value){ 			 self::$_data ['buyer_credit'] = $value;			 return $this ; 		}		public function setBuyer_good_num($value){ 			 self::$_data ['buyer_good_num'] = $value;			 return $this ; 		}		public function setBuyer_total_num($value){ 			 self::$_data ['buyer_total_num'] = $value;			 return $this ; 		}		public function setSeller_credit($value){ 			 self::$_data ['seller_credit'] = $value;			 return $this ; 		}		public function setSeller_good_num($value){ 			 self::$_data ['seller_good_num'] = $value;			 return $this ; 		}		public function setSeller_total_num($value){ 			 self::$_data ['seller_total_num'] = $value;			 return $this ; 		}		public function setLast_login_time($value){ 			 self::$_data ['last_login_time'] = $value;			 return $this ; 		}		public function setClient_status($value){ 			 self::$_data ['client_status'] = $value;			 return $this ; 		}		public function setRecommend($value){ 			 self::$_data ['recommend'] = $value;			 return $this ; 		}		public function setBuyer_level($value){ 			 self::$_data ['buyer_level'] = $value;			 return $this ; 		}		public function setSeller_level($value){ 			 self::$_data ['seller_level'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_where = $value;			 return $this; 		}		public function setData($array){ 			self::$_data = array_filter($array,array('Model','remove_null')); 			return $this; 		} 
	    /**		 * insert into  keke_witkey_space  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_space		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['uid'] )) { 						self::$_where = array ('uid' => self::$_data ['uid'] );						unset(self::$_data['uid']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_space,if isset where return where record,else return all record		 * @return array 		 */		function query($fields = '*',$cache_time = 0){ 			 empty ( $fields ) and $fields = '*';			 if($this->getWhere()){ 				 $sql = "select $fields from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select $fields from $this->_tablename"; 			 } 			 empty($fields) and $fields = '*'; 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_space records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_space, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where uid = $this->_uid "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, Database::DELETE ); 		 } 
   } //end 