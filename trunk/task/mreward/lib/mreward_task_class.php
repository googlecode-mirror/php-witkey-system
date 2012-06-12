<?php
/**
 * 多人悬赏业务类
 * @method init 任务信息初始化
 * =>任务状态数组信息
 * =>任务基本需求
 * check_if_bided        检测是否中标			 
 * 
 * get_task_stage_desc	        获取任务阶段描述
 * get_task_timedesc 	        获取任务时间描述
 * get_task_work		        获取任务指定状态的稿件信息
 * get_work_info      	        获取任务稿件信息
 *
 * start_vote                   发起投票
 * set_task_vote      			 任务投票进行
 * set_work_status   			 稿件状态变更
 * set_task_sp_end_time			更改任务公示时间
 *
 * dispose_witkey_prom   		 威客推广结算
 * dispose_employer_prom  		 雇主推广结算
 * dispose_task		   		 任务金额结算
 * dispose_task_return    		 任务金额返还
 *
 * auto_choose    	    	          自动选稿
 *
 *时间类
 * time_task_gs   	       	     任务公示
 * time_task_vote     		     任务投票
 * time_task_end      		     任务结束
 *
 * process_can 	    	                当前操作判断
 * work_hand  		      	      任务交稿
 * work_choose 	      	                任务选稿
 */
keke_lang_class::load_lang_class ( 'mreward_task_class' );
class mreward_task_class extends keke_task_class {
	public $_task_status_arr; //任务状态数组
	public $_work_status_arr; //稿件状态数组
	

	public $_delay_rule; //延期规则
	public $_time_rule; //时间规则
	

	public $_union_obj;
	
	public static function get_instance($task_info) {
		static $obj = null;
		if ($obj == null) {
			$obj = new mreward_task_class ( $task_info );
		}
		return $obj;
	}
	public function __construct($task_info) {
		parent::__construct ( $task_info );
		$this->init ();
	}
	public function init() {
		$this->status_init ();
		$this->task_requirement_init ();
		$this->time_rule_init ();
		$this->delay_rule_init ();
		$this->wiki_priv_init ();
		$this->union_obj ();
	}
	/**
	 * 联盟对象
	 * Enter description here ...
	 */
	public function union_obj() {
		$this->_union_obj = new keke_union_class ( $this->_task_id );
	}
	/**
	 * 任务(稿件)状态数组信息
	 */
	public function status_init() {
		$this->_task_status_arr = $this->get_task_status ();
		$this->_work_status_arr = $this->get_work_status ();
	}
	/**
	 * 任务时间规则
	 */
	public function time_rule_init() {
		$this->_time_rule = keke_task_config::get_time_rule ( $this->_model_id, '3600' );
	}
	/**
	 * 任务延期规则
	 */
	public function delay_rule_init() {
		$this->_delay_rule = keke_task_config::get_delay_rule ( $this->_model_id, '3600' );
	}
	/**
	 * 威客权限动作判断  
	 */
	public function wiki_priv_init() {
		$arr = mreward_priv_class::get_priv ( $this->_task_id, $this->_model_id, $this->_userinfo );
		$this->_priv = $this->user_priv_format ( $arr );
	}
	/**
	 * 任务基本需求
	 */
	public function task_requirement_init() {
		global $_lang;
		$require_arr = array (); //需求数组
		$require_arr [$_lang['haved_work']] = $this->_task_info ['work_num'];
		$require_arr [$_lang['haved_bid_work']] = $bid_num = intval ( dbfactory::get_count ( sprintf ( " select count(work_id) count from %switkey_task_work where
		 work_status = '4' and task_id = '%d'", TABLEPRE, $this->_task_id ) ) );
	}
	/**
	 * 任务阶段时间描述
	 */
	public function get_task_timedesc() {
		global $_lang;
		$status_arr = $this->_task_status_arr;
		$task_status = $this->_task_status;
		$task_info = $this->_task_info;
		$time_desc = array ();
		switch ($task_status) {
			case "2" : //投稿中
				$time_desc ['time_desc'] = $_lang['from_hand_work_deadline']; //时间状态描述
				$time_desc ['time'] = $task_info ['sub_time']; //当前状态结束时间
				$time_desc ['ext_desc'] = $_lang['task_working_can_hand_work']; //追加描述
				if ($this->_task_config ['open_select'] == 'open') { //开启进行选稿
					$time_desc ['g_action'] = $_lang['now_employer_can_choose_work']; //雇主追加描述
				}
				break;
			case "3" : //选稿中
				$time_desc ['time_desc'] = $_lang['from_choose_deadline']; //时间状态描述
				$time_desc ['time'] = $task_info ['end_time']; //当前状态结束时间
				$time_desc ['ext_desc'] = $_lang['task_choosing_wait_employer_choose']; //追加描述
				break;
			case "4" : //投票中
				$time_desc ['time_desc'] = $_lang['from_vote_deadline']; //时间状态描述
				$time_desc ['time'] = $task_info ['sp_end_time']; //当前状态结束时间
				$time_desc ['ext_desc'] = $_lang['task_voting_can_vote']; //追加描述
				break;
			case "5" : //公示中
				$time_desc ['time_desc'] = $_lang['from_gs_deadline']; //时间状态描述
				$time_desc ['time'] = $task_info ['sp_end_time']; //当前状态结束时间
				$time_desc ['ext_desc'] = $_lang['task_haved_choose_bid_and_user_look']; //追加描述
				break;
			case "6" : //交付中
				$time_desc ['ext_desc'] = $_lang['task_in_jf_rate']; //追加描述
				break;
			case "7" : //冻结中
				$time_desc ['ext_desc'] = $_lang['task_diffrent_opnion_and_web_in']; //追加描述
				break;
			case "8" : //结束
				$time_desc ['ext_desc'] = $_lang['task_haved_complete']; //追加描述
				break;
			case "9" : //失败
				$time_desc ['ext_desc'] = $_lang['task_timeout_and_no_works_fail']; //追加描述
				break;
			case "11" : //仲裁
				$time_desc ['ext_desc'] = $_lang['task_arbitrating']; //追加描述
				break;
		}
		return $time_desc;
	}
	/**
	 * 获取任务稿件信息  支持分页，用户前端稿件列表
	 * @param array $w 前端查询条件数组
	 * ['work_status'=>稿件状态	
	 * 'user_type'=>用户类型 --有值表示自己
	 * ......]
	 * @param array $p 前端传递的分页初始信息数组
	 * ['page'=>当前页面
	 * 'page_size'=>页面条数
	 * 'url'=>分页链接
	 * 'anchor'=>分页锚点]
	 * @return array work_list
	 */
	public function get_work_info($w = array(), $order = null, $p = array()) {
		global $kekezu, $_K;
		$work_arr = array ();
		$sql = " select a.*,b.seller_credit,b.seller_good_num,b.seller_total_num,b.seller_level from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$count_sql = " select count(a.work_id) from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$where = " where a.task_id = '$this->_task_id' ";
		
		if (! empty ( $w )) {
			$w['work_id'] and $where.=" and a.work_id='".$w['work_id']."'";
			$w ['user_type'] == 'my' and $where .= " and a.uid = '$this->_uid'";
			isset ( $w ['work_status'] ) and $where .= " and a.work_status = '" . intval ( $w ['work_status'] ) . "'";
		}
		$where .= "  order by (CASE WHEN  a.work_status!=0 THEN 100 ELSE 0 END) desc,work_time asc ";
		if (! empty ( $p )) {
			$page_obj = kekezu::$_page_obj;
			$page_obj->setAjax ( 1 );
			$page_obj->setAjaxDom ( "gj_summery" );
			$count = intval ( dbfactory::get_count ( $count_sql . $where ) );
			$pages = $page_obj->getPages ( $count, $p ['page_size'], $p ['page'], $p ['url'], $p ['anchor'] );
			$where .= $pages ['where'];
		}
		$work_info = dbfactory::query ( $sql . $where );
		$work_info = kekezu::get_arr_by_key($work_info,'work_id');
		$work_arr ['work_info'] = $work_info;
		$work_arr ['pages'] = $pages;
		$work_info_arr = array_keys($work_info);
		if(!empty($work_info_arr)){
			$work_arr ['mark']  = $this->has_mark(implode(',',$work_info_arr));
		}
		return $work_arr;
	}
	/**
	 * 任务交稿
	 * @param string $work_desc 交稿描述
	 * @param int    $hidework 稿件隐藏  1=>隐藏,2=>不隐藏  默认为不隐藏
	 * @param string $file_ids 稿件附件编号串  eg:1,2,3,4,5
	 * @param string $url    操作提示链接  具体参见 kekezu::keke_show_msg
	 * @param string $output 消息输出方式 具体参见 kekezu::keke_show_msg
	 * @see keke_task_class::work_hand()
	 */
	public function work_hand($work_desc, $file_ids, $hidework = '2', $qq = '', $mobile = '', $url = '', $output = 'normal') {
		global $_K;
		global $_lang;
		if ($this->check_if_can_hand ( $url, $output )) {
			$work_obj = new Keke_witkey_task_work_class ();
			//提交稿件
			$work_obj->_work_id = null;
			$work_obj->setTask_id ( $this->_task_id );
			$work_obj->setUid ( $this->_uid );
			$work_obj->setUsername ( $this->_username );
			$work_obj->setVote_num ( 0 );
			$work_obj->setWork_status ( 0 );
			$work_obj->setWork_title ( $this->_task_title );
			$work_obj->setHide_work ( intval ( $hidework ) );
			CHARSET == 'gbk' and $work_desc = kekezu::utftogbk ( $work_desc );
			$work_obj->setWork_desc ( kekezu::escape ( kekezu::str_filter ( $work_desc ) ) );
			$work_obj->setWork_time ( time () );
			
			if ($file_ids) { //提交附件
				$file_arr = array_unique ( array_filter ( explode ( ',', $file_ids ) ) );
				$f_ids = implode ( ',', $file_arr ); //附件编号串
				$work_obj->setWork_file ( implode ( ',', $file_arr ) );
			}
			$work_id = $work_obj->create_keke_witkey_task_work ();
			if ($work_id) {
				//更新附件表里相应附件的稿件ID
				$file_ids and dbfactory::execute ( sprintf ( " update %switkey_file set work_id='%d',task_title='%s',obj_id='%d' where file_id in ('%s')", TABLEPRE, $work_id, $this->_task_title, $work_id, $f_ids ) );
				$this->plus_work_num (); //更新任务稿件数量
				$this->plus_take_num (); //更新用户交稿数量
				//kekezu::update_score_value ( $this->_uid, 'task_pubwork', 2 );
				

				//http://localhost/kppw20/index.php?do=task&task_id=101133&r_task_id=93
				$notice_url = "<a href=\"" . $_K ['siteurl'] . "/index.php?do=task&task_id=" . $this->_task_id . "\">" . $this->_task_title . "</a>";
				$g_notice = array ($_lang['user'] => $this->_username, $_lang['call'] => $_lang['you'], $_lang['task_title'] => $notice_url );
				$w_notice = array ($_lang['user'] => $_lang['you'], $_lang['call'] => $this->_gusername, $_lang['task_title'] => $notice_url );
				$this->notify_user ( "task_hand", $_lang['task_hand'], $g_notice ); //通知雇主
				$this->notify_user ( "task_hand", $_lang['task_hand'], $w_notice, '1' ); //通知威客
				

				//union 请求
				if ($this->_task_info ['task_union'] == 1) {
					$this->_union_obj->work_hand ( $work_id );
				
				}
				
				kekezu::keke_show_msg ( $url, $_lang['congratulate_you_hand_work_success'], "", $output );
			
			} else
				kekezu::keke_show_msg ( $url, $_lang['pity_hand_work_fail'], "error", $output );
		}
	}
	/**
	 * 任务选稿
	 * @param string $url    操作提示链接  具体参见 kekezu::keke_show_msg
	 * @param string $output 消息输出方式 具体参见 kekezu::keke_show_msg
	 * @see keke_task_class::work_choose()
	 */
	public function work_choose($work_id, $to_status, $url = '', $output = 'normal', $trust_response = false) {
		global $_K, $kekezu;
		global $_lang;
		$kekezu->init_prom ();
		
		kekezu::check_login ( $url, $output ); //检测登录
		$this->check_if_operated ( $work_id, $to_status, $url, $output ); //检测是否可选/是否中标
		$status_arr = $this->get_work_status ();
		
		if ($this->set_work_status ( $work_id, $to_status )) {
			$status_desc_arr = array ("1" => $_lang['work_get_prize1'], "2" => $_lang['work_get_prize2'], "3" => $_lang['work_get_prize3'] );
			$work_info = $this->get_task_work ( $work_id ); //稿件信息
			

			$url = '<a href ="' . $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '" target="_blank" >' . $this->_task_title . '</a>';
			$v = array ($_lang['task_id'] => $this->_task_id, $_lang['task_title'] => $url );
			$this->notify_user ( "task_bid", $status_desc_arr [$to_status], $v, '1', $work_info ['uid'] ); //通知威客 
			$feed_arr = array ("feed_username" => array ("content" => $work_info ['username'], "url" => "index.php?do=space&member_id= {$work_info['uid']} " ), "action" => array ("content" => "成功中标了", "url" => "" ), "event" => array ("content" => "$this->_task_title ", "url" => "index.php?do=task&task_id=$this->_task_id " ) );
			kekezu::save_feed ( $feed_arr, $work_info ['uid'], $work_info ['username'], 'work_accept', $this->_task_id );
			
			$prize_date = $this->get_prize_date (); //获取各奖项对应的赏金
			$prize_cash = $prize_date ['cash'] [$to_status];
			/** 威客上线推广产生*/
			if (kekezu::$_prom_obj->is_meet_requirement ( "bid_task", $this->_task_id )) {
				kekezu::$_prom_obj->create_prom_event ( "bid_task", $work_info ['uid'], $this->_task_id, $prize_cash );
			}
			//union task
			if (in_array ( $to_status, array (1, 2, 3 ) )) {
				
				if ($this->_task_info ['task_union'] == 1) {
					$this->_union_obj->work_choose ( $work_id, $to_status );
				}
			}
			$this->plus_accepted_num ( $work_info ['uid'] );
			kekezu::echojson ( $_lang['choose_operate'], 1, $_lang['work_set_success'] );
			$this->check_if_gs (); //是否公示
			kekezu::keke_show_msg ( $url, $_lang['work'] . $status_arr [$to_status] . $_lang['set_success'], '', $output );
		} else {
			kekezu::keke_show_msg ( $url, $_lang['work'] . $status_arr [$to_status] . $_lang['set_fail'], "error", $output );
		}
	}
	/**
	 * 操作判断
	 * //注意用户权限的判断   
	 * 雇主不受威客权限的限制、、拥有威客的所有权限
	 * 威客严格受到条件约束
	 * 威客限制：查看任务       
	 * 留言        
	 * 举报	
	 * @see keke_task_class::process_can()
	 */
	public function process_can() {
		$wiki_priv = $this->_priv; //威客权限数组
		$process_arr = array ();
		$status = intval ( $this->_task_status );
		$task_info = $this->_task_info;
		$config = $this->_task_config;
		$g_uid = $this->_guid;
		$uid = $this->_uid;
		$user_info = $this->_userinfo;
		
		switch ($status) {
			case "2" : //投稿中
				switch ($g_uid == $uid) { //雇主
					case "1" :
						$process_arr ['reqedit'] = true; //补充需求
						sizeof ( $this->_delay_rule ) > 0 and $process_arr ['delay'] = true; //延期加价
						if ($config ['open_select'] == 'open') {
							$process_arr ['work_choose'] = true; //开启投稿中选稿
						}
						$process_arr ['work_comment'] = true; //稿件回复
						break;
					case "0" : //威客
						$process_arr ['work_hand'] = true; //提交稿件
						$process_arr ['task_comment'] = true; //任务回复
						$process_arr ['task_report'] = true; //任务举报
						break;
				}
				$process_arr ['work_report'] = true; //稿件举报
				break;
			case "3" : //选稿中
				switch ($g_uid == $uid) { //雇主
					case "1" :
						$process_arr ['work_choose'] = true; //选稿
						$process_arr ['work_comment'] = true; //稿件回复
						sizeof ( $this->get_task_work ( '', '5' ) ) > 1 and $process_arr ['task_vote'] = true; //发起投票
						break;
					case "0" : //威客
						$process_arr ['task_comment'] = true; //任务回复
						$process_arr ['task_report'] = true; //任务举报
						break;
				}
				$process_arr ['work_report'] = true; //稿件举报
				break;
			case "5" : //公示中
				switch ($g_uid == $uid) { //雇主
					case "1" :
						$process_arr ['work_comment'] = true; //留言回复
						break;
					case "0" :
						$process_arr ['task_comment'] = true; //任务回复
						$process_arr ['task_report'] = true; //任务举报
						break;
				}
				$process_arr ['work_report'] = true; //稿件举报
				break;
			case "8" : //已结束
				switch ($g_uid == $uid) { //雇主
					case "1" :
						$process_arr ['work_comment'] = true; //留言回复
						$process_arr ['work_mark'] = true; //稿件评价
						break;
					case "0" :
						$process_arr ['task_comment'] = true; //任务回复
						$process_arr ['task_mark'] = true; //任务评价
						break;
				}
				break;
		}
		$uid != $g_uid and $process_arr ['task_complaint'] = true; //任务投诉
		$process_arr ['work_complaint'] = true; //稿件投诉
		$this->_process_can = $process_arr;
		return $process_arr;
	}
	/**
	 * 更改稿件状态
	 * @param int $work_id 稿件编号
	 * @param int $to_status 更新到状态
	 * @return  boolean
	 */
	public function set_work_status($work_id, $to_status) {
		return dbfactory::execute ( sprintf ( " update %switkey_task_work set work_status='%d' where work_id='%d'", TABLEPRE, $to_status, $work_id ) );
	}
	/**
	 * 更改任务公示时间
	 * @param string $time_type 时间类型 notice_period=>公示时间 vote_period=>投票时间
	 */
	public function set_task_sp_end_time($time_type = 'notice_period') {
		global $_lang;
		$sp_end_time = time () + $this->_task_config [$time_type] * 24 * 3600;
		return dbfactory::execute ( sprintf ( " update %switkey_task set sp_end_time = '%d' where task_id='%d' ", TABLEPRE, $sp_end_time,$this->_task_id) );
	}
	public static function task_delay($task_id,$task_cash,$delay_cash) {
		$prize_data  = dbfactory::query(sprintf(" select * from %switkey_task_prize where task_id='%d'",TABLEPRE,$task_id));
		foreach($prize_data as $v){
			$rate = $v['prize_cash']/$task_cash;
			$new_cash = $v['prize_cash']+$delay_cash*$rate;
			dbfactory::execute(sprintf(" update %switkey_task_prize set prize_cash='%.2f' where prize_id='%d'",TABLEPRE,$new_cash,$v['prize_id']));
		}
	}
	/**
	 * 任务失败返还结算
	 */
	public function dispose_task_return() {
		global $kekezu;
		$config = $this->_task_config;
		$task_info = $this->_task_info;
		$task_cash = $task_info ['task_cash']; //任务总金额
		switch ($config ['defeated']) {
			case "1" : //返款方式   现金
				$return_cash = $task_cash * (1 - $config ['task_fail_rate'] / 100);
				$return_credit = '0';
				$res = keke_finance_class::cash_in ( $this->_guid, floatval ( $return_cash ), 0, 'task_fail', '', '', '', $task_cash - $return_cash ); //给雇主返钱
				break;
			case "2" : //金币
				$return_credit = $task_cash * (1 - $config ['task_fail_rate'] / 100);
				$return_cash = '0';
				$res = keke_finance_class::cash_in ( $this->_guid, 0, floatval ( $return_credit ), 'task_fail', '', '', '', $task_cash - $return_credit );
				break;
		}
		if ($res) {
			/** 终止雇主的此次推广事件*/
			$kekezu->init_prom ();
			$p_event = kekezu::$_prom_obj->get_prom_event ( $this->_task_id, $this->_guid, "pub_task" );
			kekezu::$_prom_obj->set_prom_event_status ( $p_event ['parent_uid'], $this->_gusername, $p_event ['event_id'], '3' );
			
			$this->set_task_status ( 9 ); //任务结束
			

			//联盟任务结束
			$this->_union_obj->change_status ( 'failure' );
		}
	}
	/**
	 * 时间触发任务投稿期结束
	 * 
	 */
	
	public function task_tg_timeout() {
		global $_lang;
		//当前时间大于投稿结束时间
		if (time () > $this->_task_info ['sub_time'] && $this->_task_info ['task_status'] == 2) {
			$work_num = $this->_task_info ['work_num'];
			//稿件数为0，任务失败
			if ($work_num == 0) {
				//	无稿件，任务失败返钱
				$this->dispose_task_return ();
				kekezu::notify_user ( $_lang['task_fail'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['haved_fail_for_task_not_work'], $this->_guid, $this->_gusername );
			}
			//稿件数不为0
			if ($work_num > 0) {
				$this->set_task_status ( 3 ); //把任务状态设为选稿状态
				kekezu::notify_user ( $_lang['choose_work_timeout'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['contribute_timeout_in_choose'], $this->_guid, $this->_gusername );
			}
		}
	}
	/**
	 * 时间触发任务选稿期结束
	 * 
	 */
	public function task_xg_timeout() {
		global $_lang;
		if (time () > $this->_task_info ['end_time'] && $this->_task_info ['task_status'] == 3) {
			$mxs_config = kekezu::get_task_config ( 2 );
			
			$prize_date = $this->get_prize_date (); //获取雇主需求的各奖项数
			$total_prize_count = $prize_date ['count'] ['prize_1'] + $prize_date ['count'] ['prize_2'] + $prize_date ['count'] ['prize_3']; //奖项总数
			//总投稿数
			$work_num = $this->_task_info ['work_num'];
			//稿件状态为0的稿件数
			$work_count = dbfactory::get_count ( sprintf ( "select count(work_id) as work_count from %switkey_task_work where task_id='%d' and work_status='%d' ", TABLEPRE, $this->_task_id, 0 ) );
			
			//雇主在选稿期没有操作过任何一个稿件时
			if ($work_num ['work_num'] == $work_count) {
				$this->auto_choose ( $prize_date ['count'] ['prize_1'], $prize_date ['count'] ['prize_2'], $total_prize_count ); //自动选稿
			} else { //雇主在选稿期有操作过稿件时
				$this->set_task_status ( 5 ); //任务状态更改到公示状态
				$this->set_task_sp_end_time ();
				kekezu::notify_user ( $_lang['task_gs'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['choose_timeout_in_gs'], $this->_guid, $this->_gusername );
			}
		}
	}
	
	/**
	 * 时间触发任务公示期结束
	 * 
	 */
	public function task_gs_timeout() {
		global $kekezu;
		global $_lang;
		$kekezu->init_prom ();
		$prom_obj = kekezu::$_prom_obj;
		
		if (time () > $this->_task_info ['sp_end_time'] && $this->_task_info ['task_status'] == 5) {
			//获取获奖稿件
			$prize_work_arr = dbfactory::query ( sprintf ( "select * from %switkey_task_work where task_id='%d' and work_status in(1,2,3) ", TABLEPRE, $this->_task_id ) );
			
			$prize_date = $this->get_prize_date (); //获取各奖项对应的赏金
			//遍历获奖稿件，结算赏金
			foreach ( $prize_work_arr as $k => $v ) {
				$prize = "prize_" . $v ['work_status'];
				$prize_cash = $prize_date ['cash'] [$prize];
				$prize_real_cash = $prize_cash * (1 - $this->_task_config ['task_rate'] / 100);
				keke_finance_class::cash_in ( $v ['uid'], $prize_real_cash, 0, 'task_bid', '', '', '', $prize_cash - $prize_real_cash ); //给威客打钱（一等奖用户）
				$prize_total_cash += $prize_cash; //一等奖话费的金额
				kekezu::notify_user ( $_lang['task_js'], $_lang['your_work'] . '<a href="index.php?do=user&view=witkey">' . $this->_task_title . '</a>' . $_lang['get_prize1_and_cash_in_your_account'], $v ['uid'], $v ['username'] );
				/** 威客上线推广结算*/
				$prom_obj->dispose_prom_event ( "bid_task", $v ['uid'], $v ['work_id'] );
				
				/**威客记录**/
				keke_user_mark_class::create_mark_log ( $this->_model_code, 1, $v ['uid'], $this->_guid, $v ['work_id'], $prize_cash, $this->_task_id, $v ['username'], $this->_gusername );
				/**雇主记录**/
				keke_user_mark_class::create_mark_log ( $this->_model_code, 2, $this->_guid, $v ['uid'], $v ['work_id'], $prize_cash * (1 - $this->_task_config ['task_rate'] / 100), $this->_task_id, $this->_gusername, $v ['username'] );
				/** 评价数+2***/
				$this->plus_mark_num ();
			}
			$this->set_task_status ( 8 ); //公示期结束，任务结束
			$this->_union_obj->change_status ( 'end' ); //联盟任务结束
			/** 雇主上线推广结算*/
			$prom_obj->dispose_prom_event ( "pub_task", $this->_guid, $this->_task_id );
			
			//判断任务是否有多余的金额：(这里的多余是指，把获奖的稿件的钱减掉，在看有没有多余的金额)
			//如果没有剩余，则任务圆满结束；如果有剩余，则奖项数比获奖的稿件数要大，此时，平台还要返剩余的前给雇主
			
			$if_sy = $this->_task_info ['task_cash'] - $prize_total_cash; //判断任务是否有剩余金额（$if_sy）
			if (intval ( $if_sy ) > 0) {
				$return_g_cash = $if_sy * (1 - $this->_task_config ['task_fail_rate'] / 100);
				if ($this->_task_config ['defeated'] == 1) { //配置是返现金
					keke_finance_class::cash_in ( $this->_guid, floatval ( $return_g_cash ), 0, 'task_fail', '', '', '', $if_sy - $return_g_cash ); //给雇主返现金
				} else { //配置是返金币
					keke_finance_class::cash_in ( $this->_guid, 0, floatval ( $return_g_cash ), 'task_fail', '', '', '', $if_sy - $return_g_cash ); //给雇主返现金
				}
				kekezu::notify_user ( $_lang['task_complete'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['gs_timeout_and_task_over_and_return_your_remain_cash'], $this->_guid, $this->_gusername );
			} else {
				//短信提示雇主，任务结束
				kekezu::notify_user ( $_lang['task_complete'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['gs_timeout_and_task_complete'], $this->_guid, $this->_gusername );
			}
		}
	}
	/**
	 * 任务自动选稿
	 * @param int $prize1_num  一等奖数量
	 * @param int $prize2_num   二等奖数量
	 * @param int  $prize_all        所有奖项数量
	 */
	public function auto_choose($prize1_num, $prize2_num, $prize_all) {
		global $kekezu;
		global $_lang;
		$kekezu->init_prom ();
		$prom_obj = kekezu::$_prom_obj;
		
		switch ($this->_task_config ['end_action']) {
			case "split" :
				$prize_date = $this->get_prize_date (); //获取各奖项对应的赏金
				//按时间获取获奖的work_id
				$work_bid_arr = dbfactory::query ( sprintf ( "select work_id ,uid,username from %switkey_task_work where task_id=%d order by work_time asc limit  %d", TABLEPRE, $this->_task_id, $prize_all ) );
				//遍历，设置奖项
				foreach ( $work_bid_arr as $k => $v ) {
					if ($k < $prize1_num) {
						$this->set_work_status ( $v ['work_id'], 1 ); //设置一等奖
						$prize_cash = $prize_date ['cash'] ['1'];
					} elseif ($k < $prize1_num + $prize2_num) {
						$this->set_work_status ( $v ['work_id'], 2 ); //设置二等奖
						$prize_cash = $prize_date ['cash'] ['2'];
					} elseif ($k < $prize_all) {
						$this->set_work_status ( $v ['work_id'], 3 ); //设置三等奖
						$prize_cash = $prize_date ['cash'] ['3'];
					}
					/** 威客上线推广产生*/
					if ($prom_obj->is_meet_requirement ( "bid_task", $this->_task_id )) {
						$prom_obj->create_prom_event ( "bid_task", $v ['uid'], $this->_task_id, $prize_cash );
					}
					kekezu::notify_user ( $_lang['work_get_prize'], $_lang['your_work'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['task_get'] . ($k + 1) . $_lang['prize_and_look'], $v ['uid'], $v ['username'] );
				}
				$this->set_task_status ( 5 ); //任务状态更改到公示状态
				$this->set_task_sp_end_time ();
				
				kekezu::notify_user ( $_lang['auto_choose_work'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['choose_timeout_and_not_work_and_auto_choose_work'], $this->_guid, $this->_gusername );
				
				break;
			case "refund" : //如果后台没有开启自动选稿，任务失败
				$this->dispose_task_return ();
				kekezu::notify_user ( $_lang['task_fail'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['for_no_operate_and_task_fail'], $this->_guid, $this->_gusername );
				break;
		}
	}
	
	/**
	 * 检测是否可以选标
	 * 先判断当前任务是否能选稿，再判断稿件是否已进行过操作
	 * @param int $work_id 
	 * @param int $to_status 
	 * @param string $url    操作提示链接  具体参见 kekezu::keke_show_msg
	 * @param string $output 消息输出方式 具体参见 kekezu::keke_show_msg
	 */
	public function check_if_operated($work_id, $to_status, $url = '', $output = 'normal') {
		global $_lang;
		$can_select = false; //是否可选标
		if ($this->check_if_can_choose ( $url, $output )) { //处于选稿期
			$work_status = dbfactory::get_count ( sprintf ( " select work_status from %switkey_task_work where work_id='%d'
					 and uid='%d'", TABLEPRE, $work_id, $this->_uid ) );
			if ($work_status == '8') { //不可选标不能更改状态
				kekezu::keke_show_msg ( $url, $_lang['the_work_is_not_choose_and_not_choose_the_work'], "error", $output );
			} else {
				$prize_date = $this->get_prize_date ();
				$prize_work_date = $this->get_prize_work_count ();
				//一等奖稿件的数目
				$work_count = $prize_work_date ["prize_" . $to_status];
				//雇主需求的一等奖数目
				$prize_count = $prize_date ['count'] ["prize_" . $to_status];
				if ($work_count == $prize_count) {
					kekezu::keke_show_msg ( $url, $_lang['now_task'] . "$to_status" . $_lang['prize_have_full'] . "$to_status" . $_lang['prize_th'], "error", $output );
				} else {
					return true;
				}
			}
		} else { //不是选稿期
			kekezu::keke_show_msg ( $url, $_lang['now_status_can_not_choose'], "error", $output );
		}
	}
	/**
	 * @return 返回多人悬赏任务状态
	 */
	public static function get_task_status() {
		global $_lang;
		return array ("0" => $_lang['task_no_pay'], "1" => $_lang['task_wait_audit'], "2" => $_lang['task_vote_choose'], "3" => $_lang['task_choose_work'], "5" => $_lang['task_gs'], "7" => $_lang['freeze'], "8" => $_lang['task_over'], "9" => $_lang['fail'], "10" => $_lang['task_audit_fail'], "11" => $_lang['arbitrate'] );
	}
	
	/**
	 * @return 返回多人悬赏稿件状态
	 * 
	 */
	public static function get_work_status() {
		global $_lang;
		return array ('1' => $_lang['prize_1'], '2' => $_lang['prize_2'], "3" => $_lang['prize_3'], '8' => $_lang['task_can_not_choose_bid'] );
	}
	
	/**
	 * @return 返回任务英文状态
	 */
	public static function get_task_union_status() {
		return array ('0' => "wait", '1' => "audit", '2' => "sub", '3' => "choose", '4' => "vote", '5' => "notice", '6' => 'deliver', '7' => "freeze", '8' => "end", '9' => "failure", '10' => "audit_fail", '11' => "arbitrate" );
	}
	/**
	 * @return 返回稿件英文状态
	 */
	public static function get_work_union_status() {
		return array ('0' => 'wait', '1' => 'first_prize', '2' => 'second_prize', '3' => 'third_prize', '8' => 'no_optional' );
	}
	/**
	 * @return 返回多人悬赏稿件奖项
	 * 
	 */
	public function get_work_prize() {
		global $_lang;
		$prize_arr = $this->get_task_prize ();
		switch (count ( $prize_arr )) {
			case 1 :
				return array ('1' => $_lang['prize_1'] );
				break;
			case 2 :
				return array ('1' => $_lang['prize_1'], '2' => $_lang['prize_2'] );
				break;
			case 3 :
				return array ('1' => $_lang['prize_1'], '2' => $_lang['prize_2'], "3" => $_lang['prize_3'] );
				break;
		}
	
	}
	/**
	 * @return 返回多人悬赏奖项对应的赏金
	 * 
	 */
	public function get_task_prize() {
		$task_prize_arr = kekezu::get_table_data ( "*", "witkey_task_prize", "task_id={$this->_task_id}", "", "", "", "prize", 0 ); //把prize字段作为主键
		return $task_prize_arr;
	}
	/**
	 * @雇主每设置一次奖项后，判断各等级奖的项数是否满足雇主的全部需求，任务状态是否到公式
	 * 
	 */
	public function check_if_gs() {
		//当奖项数满足雇主的需求时：更改任务状态为公示
		$prize_date = $this->get_prize_date (); //雇主设置的奖项
		$work_count = $this->get_prize_work_count (); //获奖稿件
		if ($prize_date ['count'] ['prize_1'] == $work_count ['prize_1'] && $prize_date ['count'] ['prize_2'] == $work_count ['prize_2'] && $prize_date ['count'] ['prize_3'] == $work_count ['prize_3']) {
			$this->set_task_status ( '5' );
			$this->set_task_sp_end_time ();
		}
	}
	/**
	 * @return 返回多人悬赏各奖项的奖金和对应的奖项数目组成的数组（二维）
	 */
	public function get_prize_date() {
		$all_prize_data = array ();
		$count = array (); //获取雇主需求的各奖项数
		$cash = array (); //获取雇主需求的奖项对应的奖金
		$prize_arr = dbfactory::query ( sprintf ( "select * from %switkey_task_prize where task_id='%d' ", TABLEPRE, $this->_task_id ) );
		$i = 1;
		foreach ( $prize_arr as $k => $v ) {
			$count ["prize_" . $i] = $v ['prize_count'];
			$cash ["prize_" . $i] = $v ['prize_cash'] / $v ['prize_count'];
			$i ++;
		}
		$all_prize_data ['count'] = $count;
		$all_prize_data ['cash'] = $cash;
		return $all_prize_data; //二维数组
	}
	/**
	 * @return 各个奖项对应的个数（数组）
	 * 
	 */
	public function get_prize_work_count() {
		$prize_work_date = array ();
		$work_count_arr = dbfactory::query ( sprintf ( "select work_status,count(work_id)  as work_count from %switkey_task_work where task_id='%d' and work_status in(1,2,3) GROUP BY work_status ", TABLEPRE, $this->_task_id ) );
		//获取稿件不同状态下得稿件数
		foreach ( $work_count_arr as $v ) {
			$prize = "prize_" . $v ['work_status'];
			$prize_work_count [$prize] = $v ['work_count'];
		}
		return $prize_work_count; //获奖稿件
	}
	/**
	 * 
	 * 订单处理
	 * @param int $order_id //订单id
	 */
	public function dispose_order($order_id) {
		global $kekezu, $_K;
		global $_lang;
		//后台配置
		$task_config = $this->_task_config;
		$task_info = $this->_task_info; //任务信息
		$url = $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id;
		$task_status = $this->_task_status;
		$order_info = dbfactory::get_one ( sprintf ( "select order_amount,order_status from %switkey_order where order_id='%d'", TABLEPRE, intval ( $order_id ) ) );
		$order_amount = $order_info ['order_amount'];
		if ($order_info ['order_status'] == 'ok') {
			$task_status == 1 && $notice = $_lang['task_pay_success_and_wait_admin_audit'];
			$task_status == 2 && $notice = $_lang['task_pay_success_and_task_pub_success'];
			return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $notice, $url, 'success' );
		} else {
			$res = keke_finance_class::cash_out ( $this->_task_info ['uid'], $order_amount, 'pub_task' ); //支付费用
			if ($res) { //支付成功
				/** 雇主推广事件产生*/
				$kekezu->init_prom ();
				if (kekezu::$_prom_obj->is_meet_requirement ( "pub_task", $this->_task_id )) {
					kekezu::$_prom_obj->create_prom_event ( "pub_task", $this->_guid, $this->_task_id, $this->_task_info ['task_cash'] );
				} //更改订单状态到已付款状态
				dbfactory::updatetable ( TABLEPRE . "witkey_order", array ("order_status" => "ok" ), array ("order_id" => "$order_id" ) );
				if ($order_amount < $task_config ['audit_cash']) { //如果订单的金额比发布任务时配置的最小金额要小
					$this->set_task_status ( 1 ); //状态更改为审核状态
					return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_success_and_wait_admin_audit'], $url, 'success' );
				} else {
					$this->set_task_status ( 2 ); //状态更改为进行状态
					return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_success_and_task_pub_success'], $url, 'success' );
				}
			} else { //支付失败
				$pay_url = $_K ['siteurl'] . "/index.php?do=pay&order_id=$order_id"; //支付跳转链接
				return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_error_and_please_repay'], $pay_url, 'warning' );
			}
		}
	}

}