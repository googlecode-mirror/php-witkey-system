<?php
	class keke_witkey_task  extends model {
	    protected static $_data = array ();
	     function  __construct(){ 			 parent::__construct ( 'witkey_task' );		 }	    
	    		public function getTask_id(){			 return self::$_data ['task_id']; 		}		public function getModel_id(){			 return self::$_data ['model_id']; 		}		public function getWork_count(){			 return self::$_data ['work_count']; 		}		public function getSingle_cash(){			 return self::$_data ['single_cash']; 		}		public function getProfit_rate(){			 return self::$_data ['profit_rate']; 		}		public function getTask_fail_rate(){			 return self::$_data ['task_fail_rate']; 		}		public function getTask_status(){			 return self::$_data ['task_status']; 		}		public function getTask_title(){			 return self::$_data ['task_title']; 		}		public function getTask_desc(){			 return self::$_data ['task_desc']; 		}		public function getTask_file(){			 return self::$_data ['task_file']; 		}		public function getTask_pic(){			 return self::$_data ['task_pic']; 		}		public function getIndus_id(){			 return self::$_data ['indus_id']; 		}		public function getIndus_pid(){			 return self::$_data ['indus_pid']; 		}		public function getUid(){			 return self::$_data ['uid']; 		}		public function getUsername(){			 return self::$_data ['username']; 		}		public function getStart_time(){			 return self::$_data ['start_time']; 		}		public function getSub_time(){			 return self::$_data ['sub_time']; 		}		public function getEnd_time(){			 return self::$_data ['end_time']; 		}		public function getSp_end_time(){			 return self::$_data ['sp_end_time']; 		}		public function getCity(){			 return self::$_data ['city']; 		}		public function getTask_cash(){			 return self::$_data ['task_cash']; 		}		public function getReal_cash(){			 return self::$_data ['real_cash']; 		}		public function getTask_cash_coverage(){			 return self::$_data ['task_cash_coverage']; 		}		public function getCash_cost(){			 return self::$_data ['cash_cost']; 		}		public function getCredit_cost(){			 return self::$_data ['credit_cost']; 		}		public function getView_num(){			 return self::$_data ['view_num']; 		}		public function getWork_num(){			 return self::$_data ['work_num']; 		}		public function getLeave_num(){			 return self::$_data ['leave_num']; 		}		public function getFocus_num(){			 return self::$_data ['focus_num']; 		}		public function getMark_num(){			 return self::$_data ['mark_num']; 		}		public function getIs_delineas(){			 return self::$_data ['is_delineas']; 		}		public function getKf_uid(){			 return self::$_data ['kf_uid']; 		}		public function getPay_item(){			 return self::$_data ['pay_item']; 		}		public function getAtt_cash(){			 return self::$_data ['att_cash']; 		}		public function getContact(){			 return self::$_data ['contact']; 		}		public function getUnique_num(){			 return self::$_data ['unique_num']; 		}		public function getExt_desc(){			 return self::$_data ['ext_desc']; 		}		public function getTask_union(){			 return self::$_data ['task_union']; 		}		public function getAlipay_trust(){			 return self::$_data ['alipay_trust']; 		}		public function getIs_delay(){			 return self::$_data ['is_delay']; 		}		public function getR_task_id(){			 return self::$_data ['r_task_id']; 		}		public function getIs_trust(){			 return self::$_data ['is_trust']; 		}		public function getTrust_type(){			 return self::$_data ['trust_type']; 		}		public function getIs_top(){			 return self::$_data ['is_top']; 		}		public function getIs_auto_bid(){			 return self::$_data ['is_auto_bid']; 		}		public function getPoint(){			 return self::$_data ['point']; 		}		public function getPayitem_time(){			 return self::$_data ['payitem_time']; 		}		public function getWhere(){			 return self::$_data ['where']; 		}
	    		public function setTask_id($value){ 			 self::$_data ['task_id'] = $value;			 return $this ; 		}		public function setModel_id($value){ 			 self::$_data ['model_id'] = $value;			 return $this ; 		}		public function setWork_count($value){ 			 self::$_data ['work_count'] = $value;			 return $this ; 		}		public function setSingle_cash($value){ 			 self::$_data ['single_cash'] = $value;			 return $this ; 		}		public function setProfit_rate($value){ 			 self::$_data ['profit_rate'] = $value;			 return $this ; 		}		public function setTask_fail_rate($value){ 			 self::$_data ['task_fail_rate'] = $value;			 return $this ; 		}		public function setTask_status($value){ 			 self::$_data ['task_status'] = $value;			 return $this ; 		}		public function setTask_title($value){ 			 self::$_data ['task_title'] = $value;			 return $this ; 		}		public function setTask_desc($value){ 			 self::$_data ['task_desc'] = $value;			 return $this ; 		}		public function setTask_file($value){ 			 self::$_data ['task_file'] = $value;			 return $this ; 		}		public function setTask_pic($value){ 			 self::$_data ['task_pic'] = $value;			 return $this ; 		}		public function setIndus_id($value){ 			 self::$_data ['indus_id'] = $value;			 return $this ; 		}		public function setIndus_pid($value){ 			 self::$_data ['indus_pid'] = $value;			 return $this ; 		}		public function setUid($value){ 			 self::$_data ['uid'] = $value;			 return $this ; 		}		public function setUsername($value){ 			 self::$_data ['username'] = $value;			 return $this ; 		}		public function setStart_time($value){ 			 self::$_data ['start_time'] = $value;			 return $this ; 		}		public function setSub_time($value){ 			 self::$_data ['sub_time'] = $value;			 return $this ; 		}		public function setEnd_time($value){ 			 self::$_data ['end_time'] = $value;			 return $this ; 		}		public function setSp_end_time($value){ 			 self::$_data ['sp_end_time'] = $value;			 return $this ; 		}		public function setCity($value){ 			 self::$_data ['city'] = $value;			 return $this ; 		}		public function setTask_cash($value){ 			 self::$_data ['task_cash'] = $value;			 return $this ; 		}		public function setReal_cash($value){ 			 self::$_data ['real_cash'] = $value;			 return $this ; 		}		public function setTask_cash_coverage($value){ 			 self::$_data ['task_cash_coverage'] = $value;			 return $this ; 		}		public function setCash_cost($value){ 			 self::$_data ['cash_cost'] = $value;			 return $this ; 		}		public function setCredit_cost($value){ 			 self::$_data ['credit_cost'] = $value;			 return $this ; 		}		public function setView_num($value){ 			 self::$_data ['view_num'] = $value;			 return $this ; 		}		public function setWork_num($value){ 			 self::$_data ['work_num'] = $value;			 return $this ; 		}		public function setLeave_num($value){ 			 self::$_data ['leave_num'] = $value;			 return $this ; 		}		public function setFocus_num($value){ 			 self::$_data ['focus_num'] = $value;			 return $this ; 		}		public function setMark_num($value){ 			 self::$_data ['mark_num'] = $value;			 return $this ; 		}		public function setIs_delineas($value){ 			 self::$_data ['is_delineas'] = $value;			 return $this ; 		}		public function setKf_uid($value){ 			 self::$_data ['kf_uid'] = $value;			 return $this ; 		}		public function setPay_item($value){ 			 self::$_data ['pay_item'] = $value;			 return $this ; 		}		public function setAtt_cash($value){ 			 self::$_data ['att_cash'] = $value;			 return $this ; 		}		public function setContact($value){ 			 self::$_data ['contact'] = $value;			 return $this ; 		}		public function setUnique_num($value){ 			 self::$_data ['unique_num'] = $value;			 return $this ; 		}		public function setExt_desc($value){ 			 self::$_data ['ext_desc'] = $value;			 return $this ; 		}		public function setTask_union($value){ 			 self::$_data ['task_union'] = $value;			 return $this ; 		}		public function setAlipay_trust($value){ 			 self::$_data ['alipay_trust'] = $value;			 return $this ; 		}		public function setIs_delay($value){ 			 self::$_data ['is_delay'] = $value;			 return $this ; 		}		public function setR_task_id($value){ 			 self::$_data ['r_task_id'] = $value;			 return $this ; 		}		public function setIs_trust($value){ 			 self::$_data ['is_trust'] = $value;			 return $this ; 		}		public function setTrust_type($value){ 			 self::$_data ['trust_type'] = $value;			 return $this ; 		}		public function setIs_top($value){ 			 self::$_data ['is_top'] = $value;			 return $this ; 		}		public function setIs_auto_bid($value){ 			 self::$_data ['is_auto_bid'] = $value;			 return $this ; 		}		public function setPoint($value){ 			 self::$_data ['point'] = $value;			 return $this ; 		}		public function setPayitem_time($value){ 			 self::$_data ['payitem_time'] = $value;			 return $this ; 		}		public function setWhere($value){ 			 self::$_data ['where'] = $value;			 return $this; 		}
	    /**		 * insert into  keke_witkey_task  ,or add new record		 * @return int last_insert_id		 */		function create($return_last_id=1){		 $res = $this->_db->insert ( $this->_tablename, self::$_data, $return_last_id, $this->_replace ); 		 $this->reset(); 			 return $res; 		 } 
	    /**		 * update table keke_witkey_task		 * @return int affected_rows		 */		function update() {				if ($this->getWhere()) { 					$res =  $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere());				} elseif (isset ( self::$_data ['task_id'] )) { 						self::$_data ['where'] = array ('task_id' => self::$_data ['task_id'] );						unset(self::$_data['task_id']);						$res = $this->_db->update ( $this->_tablename, self::$_data, $this->getWhere() );				}				$this->reset();				return $res;		}
	    /**		 * query table: keke_witkey_task,if isset where return where record,else return all record		 * @return array 		 */		function query($cache_time = 0){ 			 if($this->getWhere()){ 				 $sql = "select * from $this->_tablename where ".$this->getWhere(); 			 }else{ 				 $sql = "select * from $this->_tablename"; 			 } 			 $this->reset();			 return $this->_db->cached ( $cache_time )->cache_data ( $sql );		 } 
	    /**		 * query count keke_witkey_task records,if iset where query by where 		 * @return int count records		 */		function count(){ 			 if($this->getWhere()){ 				 $sql = "select count(*) as count from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "select count(*) as count from $this->_tablename"; 			 } 			 $this->reset(); 			 return $this->_db->get_count ( $sql ); 		 } 
	    /**		 * delete table keke_witkey_task, if isset where delete by where 		 * @return int deleted affected_rows 		 */		function del(){ 			 if($this->getWhere()){ 				 $sql = "delete from $this->_tablename where ".$this->getWhere(); 			 } 			 else{ 				 $sql = "delete from $this->_tablename where task_id = $this->_task_id "; 			 } 			 $this->reset(); 			 return $this->_db->query ( $sql, database::DELETE ); 		 } 
   } //end 