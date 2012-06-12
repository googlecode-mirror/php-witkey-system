<?php
/**
 * 单人悬赏业务类
 */
keke_lang_class::load_lang_class ( 'sreward_task_class' );
class sreward_task_class extends keke_task_class {
	
	public $_task_status_arr; //任务状态数组
	public $_work_status_arr; //稿件状态数组
	

	public $_delay_rule; //延期规则
	public $_time_rule; //时间规则
	public $_agree_id; //协议编号
	

	// 	public $_union_obj;
	

	protected $_inited = false;
	public static function get_instance($task_info) {
		static $obj = null;
		if ($obj == null) {
			$obj = new sreward_task_class ( $task_info );
		}
		return $obj;
	}
	public function __construct($task_info) {
		parent::__construct ( $task_info );
		$this->_task_status == '6' and $this->_agree_id = dbfactory::get_count ( sprintf ( " select agree_id from %switkey_agreement where task_id='%d'", TABLEPRE, $this->_task_id ) );
		$this->init ();
	}
	
	public function init() {
		if (! $this->_inited) {
			$this->status_init ();
			$this->time_rule_init ();
			$this->delay_rule_init ();
			$this->wiki_priv_init ();
		}
		$this->_inited = true;
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
		$arr = sreward_priv_class::get_priv ( $this->_task_id, $this->_model_id, $this->_userinfo );
		$this->_priv = $this->user_priv_format ( $arr );
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
				$time_desc ['ext_desc'] = $_lang['task_diffrent_opnion_and_web_in']; //追加描述
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
		$sql = " select a.*,b.seller_credit,b.seller_good_num,b.residency,b.seller_total_num,b.seller_level from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$count_sql = " select count(a.work_id) from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$where = " where a.task_id = '$this->_task_id' ";
		
		if (! empty ( $w )) {
			$w['work_id'] and $where.=" and a.work_id='".$w['work_id']."'";
			$w ['user_type'] == 'my' and $where .= " and a.uid = '$this->_uid'";
			isset ( $w ['work_status'] ) and $where .= " and a.work_status = '" . intval ( $w ['work_status'] ) . "'";
		/**待添加**/
		}
		$where .= "   order by (CASE WHEN  a.work_status!=0 THEN work_id ELSE 0 END) desc,work_id asc ";
		if (! empty ( $p )) {
			$page_obj = kekezu::$_page_obj;
			$page_obj->setAjax ( 1 );
			$page_obj->setAjaxDom ( "gj_summery" );
			$count = intval ( dbfactory::get_count ( $count_sql . $where ) );
			$pages = $page_obj->getPages ( $count, $p ['page_size'], $p ['page'], $p ['url'], $p ['anchor'] );
			$where .= $pages ['where'];
			$pages ['count'] = $count;
		}
		$work_info = dbfactory::query ( $sql . $where );
		$work_info = kekezu::get_arr_by_key($work_info,'work_id');
		$work_arr ['work_info'] = $work_info;
		$work_arr ['pages'] = $pages;
		$work_arr ['mark']  = $this->has_mark(implode(',',array_keys($work_info)));
		return $work_arr;
	}
	/**
	 * 任务交稿
	 * @param string $work_desc 交稿描述
	 * @param int    $hidework 稿件隐藏  1=>隐藏,2=>不隐藏  默认为不隐藏
	 * @param string $file_ids 稿件附件编号串  eg:1,2,3,4,5
	 * @see keke_task_class::work_hand()
	 */
	public function work_hand($work_desc, $file_ids, $hidework = '2', $url = '', $output = 'normal') {
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
			$work_obj->setWork_title ( $this->_task_title . $_lang['de_work'] );
			$work_obj->setHide_work ( intval ( $hidework ) );
			CHARSET == 'gbk' and $work_desc = kekezu::utftogbk ( $work_desc );
			$work_obj->setWork_desc ( $work_desc );
			$work_obj->setWork_time ( time () );
			
			if ($file_ids) { //提交附件
				$file_arr = array_unique ( array_filter ( explode ( ',', $file_ids ) ) );
				$f_ids = implode ( ',', $file_arr ); //附件编号串
				$work_obj->setWork_file ( implode ( ',', $file_arr ) );
			}
			$work_id = $work_obj->create_keke_witkey_task_work ();
			$hidework == '1' and keke_payitem_class::payitem_cost ( "workhide", '1', 'work', 'spend', $work_id, $this->_task_id );
			if ($work_id) {
				//是否是联盟任务
				if ($this->_task_info ['task_union'] == '1') {
					$union_obj = new keke_union_class ( $this->_task_id );
					$union_obj->work_hand ( $work_id );
				}
				//更新附件表里相应附件的稿件ID
				$file_ids and dbfactory::execute ( sprintf ( " update %switkey_file set work_id='%d',task_title='%s',obj_id='%d' where file_id in ('%s')", TABLEPRE, $work_id, $this->_task_title, $work_id, $f_ids ) );
				$this->plus_work_num (); //更新任务稿件数量
				$this->plus_take_num (); //更新用户交稿数量
				$notice_url = "<a href=\"" . $_K['siteurl'] . "/index.php?do=task&task_id=" . $this->_task_id . "\">" . $this->_task_title . "</a>";
				$g_notice = array ($_lang['user'] => $this->_username, $_lang['call'] => $_lang['you'], $_lang['task_title'] => $notice_url );
				$w_notice = array ($_lang['user'] => $_lang['you'], $_lang['call'] => $this->_gusername, $_lang['task_title'] => $notice_url );
				$this->notify_user ( "task_hand", $_lang['task_hand'], $g_notice ); //通知雇主
				$this->notify_user ( "task_hand", $_lang['task_hand'], $w_notice, 1 ); //通知威客
				kekezu::keke_show_msg ( $url, $_lang['congratulate_you_hand_work_success'], "", $output );
			} else {
				kekezu::keke_show_msg ( $url, $_lang['pity_hand_work_fail'], "error", $output );
			}
		}
	}
	/**
	 * 任务选稿
	 * @param int $work_id
	 * @param int $to_status
	 * @param $trust_response 担保回调响应
	 * @see keke_task_class::work_choose()
	 */
	public function work_choose($work_id, $to_status, $url = '', $output = 'normal', $trust_response = false) {
		global $kekezu, $_K;
		global $_lang;
		kekezu::check_login ( $url, $output ); //检测登录
		$this->check_if_operated ( $work_id, $to_status, $url, $output ); //检测是否可选/是否中标
		$status_arr = $this->get_work_status ();
		
		$task_info = $this->_task_info;
		if ($to_status == 4 && $this->_trust_mode && $trust_response == false) { //担保模式
			$jump_url = keke_trust_fac_class::trust_task_request ( "confirm", 'sreward', $this->_task_id, $task_info ['trust_type'], array ("work_id" => $work_id ) );
			kekezu::keke_show_msg ( $url, $jump_url, "", $output );
		} else {
			$work_info = $this->get_task_work ( $work_id ); //稿件信息
			if ($to_status == 4) { //中标提示用户
				//是否是联盟任务
				if ($this->_task_info ['task_union'] == '1') {
					$union_obj = new keke_union_class ( $this->_task_id );
					$union_obj->work_choose ( $work_id );
				}
				$this->set_task_status ( '5' ); //*更改任务状态为公示**/
				$this->set_task_sp_end_time ();
				$feed_arr = array ("feed_username" => array ("content" => $work_info['username'], "url" => "index.php?do=space&member_id={$work_info['uid']} " ), "action" => array ("content" => "成功中标了", "url" => "" ), "event" => array ("content" => "$this->_task_title", "url" => "index.php?do=task&task_id=$this->_task_id" ) );
				kekezu::save_feed ( $feed_arr, $work_info ['uid'], $work_info ['username'], 'work_accept', $this->_task_id );
				$this->plus_accepted_num ( $work_info ['uid'] );
				/** 威客推广产生*/
				$kekezu->init_prom ();
				if (kekezu::$_prom_obj->is_meet_requirement ( "bid_task", $this->_task_id )) {
					kekezu::$_prom_obj->create_prom_event ( "bid_task", $work_info ['uid'], $this->_task_id, $this->_task_info ['task_cash'] );
				}
			}
			$res = $this->set_work_status ( $work_id, $to_status );
			$notify_url = '<a href ="' . $_K['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '" target="_blank" >' . $this->_task_title . '</a>';
			$v = array ($_lang['action'] => $status_arr [$to_status], $_lang['task_id'] => $this->_task_id, $_lang['task_title'] => $notify_url );
			$this->notify_user ( "task_bid", $_lang['work'] . $status_arr [$to_status], $v, 1, $work_info ['uid'] ); //通知威客
			if ($res) {
				kekezu::keke_show_msg ( $url, $_lang['work'] . $status_arr [$to_status] . $_lang['set_success'], "", $output );
			} else {
				kekezu::keke_show_msg ( $url, $_lang['work'] . $status_arr [$to_status] . $_lang['set_fail'], "error", $output );
			}
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
						break;
					case "0" : //威客
						$process_arr ['task_comment'] = true; //任务回复
						$process_arr ['task_report'] = true; //任务举报
						break;
				}
				$process_arr ['work_report'] = true; //稿件举报
				break;
			case "4" : //投票中
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
				$uid and $process_arr ['work_vote'] = true; //进行投票
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
			case "6" : //交付中
				$process_arr ['task_rights'] = true; //任务维权
				if ($uid == $g_uid) {
					$process_arr ['work_rights'] = true; //雇主发起稿件维权
				}
				$process_arr ['task_agree'] = true; //进入交付
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
		$sp_end_time = time () + $this->_task_config [$time_type] * 24 * 3600;
		return dbfactory::execute ( sprintf ( " update %switkey_task set sp_end_time = '%d' where task_id='%d'", TABLEPRE, $sp_end_time, $this->_task_id ) );
	}
	/**
	 * 稿件进行投票
	 * @param int $work_id  稿件编号
	 */
	public function set_task_vote($work_id, $url = '', $output = 'normal') {
		global $_lang;
		if ($this->check_if_voted ( $work_id, $url, $output )) {
			$vote_obj = new Keke_witkey_vote_class ();
			$vote_obj->setTask_id ( $this->_task_id );
			$vote_obj->setWork_id ( $work_id );
			$vote_obj->setUid ( $this->_uid );
			$vote_obj->setUsername ( $this->_username );
			$vote_obj->setVote_ip ( kekezu::get_ip () );
			$vote_obj->setVote_time ( time () );
			$vote_id = $vote_obj->create_keke_witkey_vote ();
			if ($vote_id) {
				dbfactory::execute ( sprintf ( " update %switkey_task_work set vote_num=vote_num+1 where work_id ='%d'", TABLEPRE, $work_id ) );
				kekezu::keke_show_msg ( $url, $_lang['vote_success'], "", $output );
			} else {
				kekezu::keke_show_msg ( $url, $_lang['vote_fail'], "error", $output );
			}
		}
	}
	/**
	 * 任务失败返还结算
	 * @param $trust_response 担保回调响应
	 */
	public function dispose_task_return($trust_response = false) {
		global $kekezu;
		global $_lang;
		$config = $this->_task_config;
		$task_info = $this->_task_info;
		$task_cash = $task_info ['task_cash']; //任务总金额
		$fail_rate = $this->_fail_rate; //失败返金抽成比
		$site_profit = $task_cash * $fail_rate / 100; //网站利润
		switch ($config ['defeated']) {
			case "2" : //返款方式   金币
				$return_cash = '0';
				$return_credit = $task_cash - $site_profit; //返还佣金
				break;
			case "1" : //现金(有花费现金优先将花费的现金返还,剩余部分返还金币)
				$cash_cost = $task_info ['cash_cost']; //现金花费
				$credit_cost = $task_info ['credit_cost']; //金币花费
				if ($cash_cost == $task_cash) { //全用现金
					$return_cash = $task_cash - $site_profit;
					$return_credit = '0';
				} elseif ($credit_cost == $task_cash) { //全用金币
					$return_cash = '0';
					$return_credit = $task_cash - $site_profit;
				} else {
					$return_cash = $cash_cost * (1 - $fail_rate / 100); //减去金币消耗
					$return_credit = $credit_cost * (1 - $fail_rate / 100);
				}
				break;
		}
		if ($this->_task_info ['is_trust'] && $trust_response == false) {
			$refund_cash = $task_cash - $site_profit;
			$data ['refund'] [] = array ('employer_refund', $refund_cash ); //退款明细
			$data ['platform'] [] = array ('pt_profit', $site_profit ); //平台划拨明细
			$jump_url = keke_trust_fac_class::trust_task_request ( "pt_refund", 'sreward', $this->_task_id, $task_info ['trust_type'], $data );
			header ( "Location:" . $jump_url );
		} else {
			switch ($this->_trust_mode) {
				case "1" : //担保模式
					$data = array ("uid" => $this->_guid, "username" => $this->_gusername, "obj_id" => $this->_task_id, "fina_cash" => $task_cash - $site_profit, "fina_time" => time (), "fina_action" => "task_fail", 'site_profit' => $site_profit );
					$res = keke_finance_class::finance_trust ( $data, 'alipay_trust', 'in' );
					break;
				case "0" : //非担保模式
					$res = keke_finance_class::cash_in ( $this->_guid, $return_cash, floatval ( $return_credit ) + 0, 'task_fail', '', 'task', $this->_task_id, $site_profit );
					break;
			}
			if ($res) {
				/** 终止雇主的此次推广事件*/
				$kekezu->init_prom ();
				$p_event = kekezu::$_prom_obj->get_prom_event ( $this->_task_id, $this->_guid, "pub_task" );
				kekezu::$_prom_obj->set_prom_event_status ( $p_event ['parent_uid'], $this->_gusername, $p_event ['event_id'], '3' );
				switch ($this->_trust_mode) {
					case "0" :
						$this->set_task_status ( 9 ); //任务失败
						if ($this->_task_info ['task_union'] == '1') { //如果是联盟任务
							$union_obj = new keke_union_class ( $this->_task_id );
							$union_obj->change_status ( 'failure' );
						}
						break;
					case "1" :
						$this->set_task_status ( 12 ); //担保退款成功
						kekezu::admin_show_msg ( $_lang['operate_notice'], "index.php?do=model&view=list&model_id=" . $this->_mode_id, 3, "担保退款处理成功" ); //担保处理完成
						break;
				}
			}
			return $res;
		}
	}
	/**
	 * 时间触发投稿到期处理
	 * 有稿件：进入选稿
	 * 无稿件：任务失败
	 */
	public function time_hand_end() {
		global $_lang;
		if ($this->_task_status == 2 && $this->_task_info ['sub_time'] < time ()){ //任务投稿时间到
			if ($this->_task_info ['work_num']) {
				$this->set_task_status ( '3' );
			} else {
				switch ($this->_trust_mode) { //担保模式判断
					case "0" : //非担保
						$this->dispose_task_return ();
						break;
					case "1" : //担保。直接失败
						$this->set_task_status ( 9 );
						kekezu::notify_user ( $_lang['task_fail_notice'], $_lang['your_jh'] . $this->_task_id . $_lang['choose_no_body_hand_work'], $this->_guid );
						break;
				}
			}
		}
	}
	/**
	 * 时间触发投票到期处理
	 * 最高票票数：
	 * 有:进入公示。稿件中标
	 * 无:任务失败
	 */
	public function time_vote_end() {
		global $_K, $kekezu;
		global $_lang;
		if ($this->_task_status == 4 && $this->_task_info ['sp_end_time'] < time ()) { //任务投票时间到
			//获取票数最多的/时间早的稿件
			$bid_work = dbfactory::get_one ( sprintf ( " select * from %switkey_task_work where work_status=5 and task_id ='%d' order by vote_num desc,work_time desc limit 0,1", TABLEPRE, $this->_task_id ) );
			if ($bid_work ['vote_num'] > 0) { //有票
				$this->set_task_status ( 5 ); //任务进入公示
				$this->set_work_status ( $bid_work['work_id'], 4 ); //稿件选为中标
				/*将任务标识为自动选标任务*/
				dbfactory::execute ( sprintf ( " update %switkey_task set is_auto_bid='1' where task_id='%d'", TABLEPRE, $this->_task_id ) );
				/*改变其他入围任务*/
				dbfactory::execute ( sprintf ( "update %switkey_task_work set work_status = 0 where work_status=5 and task_id='%d'", TABLEPRE, $this->_task_id ) );
				$this->set_task_sp_end_time ( "notice_period" ); //重置sp_end_time
				$this->plus_accepted_num ( $bid_work ['uid'] ); //威客中标次数加1 accepted_num
				/** 威客上线推广产生*/
				$kekezu->init_prom ();
				
				if (kekezu::$_prom_obj->is_meet_requirement ( "bid_task", $this->_task_id )) {
					kekezu::$_prom_obj->create_prom_event ( "bid_task", $bid_work ['uid'], $this->_task_id, $this->_task_info ['task_cash'] );
				}
				$url = '<a href =\"' . $_K['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '\" target=\"_blank\" >' . $this->_task_title . '</a>';
				$v = array ($_lang['task_id'] => $this->_task_id, $_lang['task_title'] => $url );
				$this->notify_user ( "task_bid", $_lang['work_vote_bid'], $v, 1, $bid_work ['uid'] ); //通知威客
				//写入feed表
				$feed_arr = array ("feed_username" => array ("content" => $bid_work['username'], "url" => "index.php?do=space&member_id={$bid_work['uid']}" ), "action" => array ("content" => "成功中标了", "url" => "" ), "event" => array ("content" => "$this->_task_title ", "url" => "index.php?do=task&task_id=$this->_task_id" ) );
				kekezu::save_feed ( $feed_arr, $bid_work['uid'], $bid_work['username'], 'work_accept', $this->_task_id );
			} else {
				/** 没人投，将入围稿件更改为不合格 .任务退款**/
				dbfactory::execute ( sprintf ( " update %switkey_task_work set work_status='7' where work_status = '5' and task_id = '%d'", TABLEPRE, $this->_task_id ) );
				switch ($this->_trust_mode) { //担保模式判断
					case "0" : //非担保
						$this->dispose_task_return ();
						break;
					case "1" : //担保。直接失败
						$this->set_task_status ( 9 );
						kekezu::notify_user ( $_lang['task_fail_notice'], $_lang['your_r'] . $this->_task_id . "投票期无人进行投票、任务已失败、由于是担保任务，请等待管理员解冻任务款项。", $this->_guid );
						break;
				}
			}
		}
	}
	/**
	 * 时间触发任务选稿到期处理
	 * 选稿到期被触发绝对是没有选择中标的稿件.
	 * 有稿件：单入围则直接中标、进入公示。
	 * 多入围进入投票。
	 * 无入围自动选稿（前提：只要有稿件被操作过就不能选）。
	 * 无稿件：任务失败返款。
	 */
	public function time_choose_end() {
		global $kekezu;
		global $_lang;
		if ($this->_task_status == 3 && $this->_task_info ['end_time'] < time ()) { //选稿结束
			if ($this->_task_info ['work_num'] > 0) { //当有稿件的时候
				$rw_work = $this->get_task_work ( '', '5' ); //获取入围稿件信息
				$rw_count = intval ( count ( $rw_work ) ); //入围数量
				if ($rw_count == '1') { //一个入围
					$this->set_work_status ( $rw_work['0']['work_id'], 4 ); //把稿件的状态设为中标
					$this->plus_accepted_num ( $rw_work['0'] ['uid'] ); //威客中标次数加1 accepted_num
					/** 威客上线推广产生*/
					$kekezu->init_prom ();
					
					if (kekezu::$_prom_obj->is_meet_requirement ( "bid_task", $this->_task_id )) {
						kekezu::$_prom_obj->create_prom_event ( "bid_task", $rw_work ['uid'], $this->_task_id, $this->_task_info ['task_cash'] );
					}
					$this->set_task_status ( 5 ); //把任务状态设为公示
					$this->set_task_sp_end_time ( "notice_period" ); //设置公示时间
					/*将任务标识为自动选标任务*/
					dbfactory::execute ( sprintf ( " update %switkey_task set is_auto_bid='1' where task_id='%d'", TABLEPRE, $this->_task_id ) );
					//发送站内短信给雇主
					kekezu::notify_user ( $_lang['task_timeout'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['timeout_sys_default_in_and_bid'], $this->_guid, $this->_gusername );
				} elseif ($rw_count > 1) { //多个入围
					$this->set_task_status ( 4 ); //将任务状态设为投票中
					$this->set_task_sp_end_time ( "vote_period" ); //设置投票结束时间
					kekezu::notify_user ( $_lang['task_timeout'], $_lang['your_task'] . '<a href="index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>' . $_lang['timeout_sys_default_vote_status'], $this->_guid, $this->_gusername );
				} else { //无入围，进入自动选稿
					$this->auto_choose (); //自动选稿
				}
			} else { //无稿件
				switch ($this->_trust_mode) { //担保模式判断
					case "0" : //非担保
						$this->dispose_task_return ();
						break;
					case "1" : //担保。直接失败
						$this->set_task_status ( 9 );
						kekezu::notify_user ( $_lang['task_fail_notice'], $_lang['your_r'] . $this->_task_id . $_lang['choose_no_body_hand_work'], $this->_guid );
						break;
				}
			}
		}
	}
	/**
	 * 时间触发公示结束处理
	 * 进入公示的任务必有中标。
	 * =======>任务进入交付阶段。
	 */
	public function time_notice_end() {
		global $_K;
		global $_lang;
		$work_info = $this->get_task_work ( '', '4' ); //获取中标稿件信息
		$work_info = $work_info['0'];
		// 			var_dump($this->_task_status,time());die();
		if ($this->_task_status == 5 && time () > $this->_task_info['sp_end_time']) { //判读是否处于公式期
			if ($this->_task_info ['task_union'] == '1') { //如果是联盟任务,更改任务状态
				$union_obj = new keke_union_class ( $this->_task_id );
				$union_obj->change_status ( 'end' );
			}
			
			//进入交付流程
			$this->set_task_status ( 6 );
			/*产生交付协议*/
			$agree_title = $_lang['task_jh'] . $this->_task_id . $_lang['de_jh'] . $work_info ['work_id'] . $_lang['num_work_jf'];
			$agree_id = keke_task_agreement::create_agreement ( $agree_title, $this->_model_id, $this->_task_id, $work_info ['work_id'], $this->_guid, $work_info ['uid'] );
			
			$a_url = '<a href="' . $_K ['siteurl'] . '/index.php?do=agreement&agree_id=' . $agree_id . '">' . $agree_title . '</a>';
			$notice = $_lang['task_in_jf_stage'];
			
			$s_arr = array ($_lang['agreement_link'] => $a_url, $_lang['agreement_status'] => $notice );
			$b_arr = array ($_lang['agreement_link'] => $a_url, $_lang['agreement_status'] => $notice );
			$this->notify_user ( "agreement", $_lang['task_in_jf_stage'], $s_arr, 1, $work_info ['uid'] ); //通知威客
			$this->notify_user ( "agreement", $_lang['task_in_jf_stage'], $b_arr, 2, $this->_guid ); //通知雇主
		}
	}
	/**
	 * 任务自动选稿
	 * 未进行操作的稿件才能自动选稿
	 * 雇主有操作过稿件说明雇主对某些稿件有主观判断、自动选不合雇主意愿
	 */
	public function auto_choose() {
		global $_K, $kekezu;
		global $_lang;
		if (!$this->_trust_mode) { //担保模式不进行自动选稿、直接进行退款
			$has_operated = dbfactory::get_count ( sprintf ( " select count(work_id) from %switkey_task_work where work_status>0 and task_id ='%d'", TABLEPRE, $this->_task_id ) );
			if ($has_operated) {
				/** 有操作过稿件**/
				$this->dispose_task_return (); //直接退款。无视自动选稿策略
			} else {
				switch ($this->_task_config ['end_action']) { //任务自动选稿动作
					case "refund" : //退款
						$this->dispose_task_return ();
						break;
					case "split" : //平分
						$task_info = $this->_task_info;
						$split_num = intval ( $this->_task_config ['witkey_num'] ); //后台设置的平分人数
						$single_cash = number_format ( $task_info ['task_cash'] / $split_num, 2 ); //单人分配金额
						if ($split_num) {
							$kekezu->init_prom ();
							$prom_obj = kekezu::$_prom_obj;
							
							$site_profit = $single_cash * $this->_profit_rate / 100; //网站利润
							$cash = $single_cash - $site_profit; //每人可分实际金额
							$sql = "select a.*,b.oauth_id from %switkey_task_work a left join %switkey_member_oauth b on a.uid=b.uid
								where a.task_id='%d' and a.work_status='0' order by a.work_time desc limit 0,%d";
							$work_list = dbfactory::query ( sprintf ( $sql, TABLEPRE, TABLEPRE, $this->_task_id, $split_num ) );
							$key = array_keys ( $work_list );
							$count = sizeof ( $key );
							for($i = 0; $i < $count; $i ++) {
								keke_finance_class::cash_in ( $work_list [$i] ['uid'], $cash, 0, 'task_bid', '', 'task', $work_list [$i] ['work_id'], $site_profit );
								$this->set_work_status ( $work_list [$i] ['work_id'], 4 );
								/** 威客的上线推广结算---创建。同时结算*/
								if ($prom_obj->is_meet_requirement ( "bid_task", $this->_task_id )) {
									$prom_obj->create_prom_event ( "bid_task", $work_list [$i] ['uid'], $this->_task_id, $single_cash );
									$prom_obj->dispose_prom_event ( "bid_task", $work_list [$i] ['uid'], $work_list [$i] ['work_id'] );
								}
								$url = '<a href ="' . $_K['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>';
								$v = array ($_lang['task_id'] => $this->_task_id, $_lang['task_title'] => $url );
								$this->notify_user ( "auto_choose", $_lang['task_auto_choose_bid'], $v, 1, $work_list [$i] ['uid'] ); //通知威客
								/**威客记录**/
								keke_user_mark_class::create_mark_log ( $this->_model_code, '1', $work_list [$i] ['uid'], $this->_guid, $work_list [$i] ['work_id'], $single_cash, $this->_task_id, $work_list [$i] ['username'], $this->_gusername );
								/**雇主记录**/
								keke_user_mark_class::create_mark_log ( $this->_model_code, '2', $this->_guid, $work_list [$i] ['uid'], $work_list [$i] ['work_id'], $cash, $this->_task_id, $this->_gusername, $work_list [$i] ['username'] );
								/** 评价数+2***/
								$this->plus_mark_num ();
							}
							if ($split_num > $count) { //人数不够。将多余的返还。现金{
								$remain_cash = $task_info ['task_cash'] - $count * $single_cash; //剩余金额
								$res = $this->dispose_auto_return ( $remain_cash );
								if ($res) {
									$v = array ($_lang['task_id'] => $this->_task_id, $_lang['task_title'] => $url );
									$this->notify_user ( "auto_choose", $_lang['task_auto_choose_work_and_return'], $v, 2, $this->_guid ); //通知雇主
								}
							}
							/** 雇主的上线推广结算.*/
							$prom_obj->dispose_prom_event ( "pub_task", $this->_guid, $this->_task_id );
						} else {
							$this->dispose_task_return (); //没选人.直接退款。无视自动选稿策略
						}
						break;
				}
			}
			$this->set_task_status ( 8 );
			if ($this->_task_info ['task_union'] == '1') { //如果是联盟任务,更改任务状态
				$union_obj = new keke_union_class ( $this->_task_id );
				$union_obj->change_status ( 'end' );
			}
			kekezu::notify_user ( $_lang['aito_choose_work_notice'], $_lang['your_r'] . $this->_task_id . $_lang['task_timeout_and_sys_auto_choose_work'], $this->_guid );
		} else { //任务失败
			$this->set_task_status ( 9 );
			if ($this->_task_info ['task_union'] == '1') { //如果是联盟任务,更改任务状态
				$union_obj = new keke_union_class ( $this->_task_id );
				$union_obj->change_status ( 'failure' );
			}
			kekezu::notify_user ( $_lang['task_fail_notice'], $_lang['your_r'] . $this->_task_id . $_lang['task_fail_and_wait_amin_unfreeze_task'], $this->_guid );
		}
	}
	/**
	 * 任务自动选稿剩余金额返还
	 * @param float $remain_cash 返还金额
	 */
	public function dispose_auto_return($remain_cash) {
		global $kekezu;
		$config = $this->_task_config;
		$task_info = $this->_task_info;
		$fail_rate = $this->_fail_rate; //失败返金抽成比
		$site_profit = $remain_cash * $fail_rate / 100; //网站利润
		switch ($config ['defeated']) {
			case "2" : //返款方式   金币
				$return_cash = '0';
				$return_credit = $remain_cash - $site_profit; //返还佣金
				break;
			case "1" : //现金
				$return_credit = '0';
				$return_cash = $remain_cash - $site_profit; //返还佣金
				break;
		}
		return keke_finance_class::cash_in ( $this->_guid, $return_cash, floatval ( $return_credit ) + 0, 'task_auto_return', '', 'task', $this->_task_id, $site_profit );
	
	}
	/**
	 * 检测是否可以选标
	 * 先判断当前任务是否能选稿，再判断稿件是否已进行过操作
	 * @param int $work_id
	 * @param int $to_status
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
				switch ($to_status) {
					case "4" : //中标时检查是否有中标
						$has_bidwork = dbfactory::get_count ( sprintf ( " select count(work_id) from %switkey_task_work where work_status='4' and task_id = '%d' ", TABLEPRE, $this->_task_id ) );
						if ($has_bidwork) {
							kekezu::keke_show_msg ( $url, $_lang['task_have_bid_work_and_not_choose_the_work'], "error", $output );
						} else {
							if ($work_status == '7') { //淘汰(不可选标)不能改为中标
								kekezu::keke_show_msg ( $url, $_lang['the_work_is_out_and_not_choose_the_work'], "error", $output );
							} else {
								return true;
							}
						}
						break;
					case "5" : //中标、淘汰、入围稿件不能更改为入围
						switch ($work_status) {
							case "4" :
								kekezu::keke_show_msg ( $url, $_lang['the_work_haved_bid_and_not_change_stutus_to_in'], "error", $output );
								break;
							case "5" :
								kekezu::keke_show_msg ( $url, $_lang['the_work_haved_in_and_not_repeat'], "error", $output );
								break;
							case "7" :
								kekezu::keke_show_msg ( $url, $_lang['the_work_is_bid_and_not_change_status_to_in'], "error", $output );
								break;
						}
						return true;
						break;
					case "7" : //中标、淘汰稿件无法变更为淘汰。入围稿件可以变更为淘汰
						switch ($work_status) {
							case "4" :
								kekezu::keke_show_msg ( $url, $_lang['the_work_is_bid_and_not_change_status'], "error", $output );
								break;
							case "7" :
								kekezu::keke_show_msg ( $url, $_lang['the_work_is_out_and_not_repeat'], "error", $output );
								break;
						}
						return true;
						break;
				}
			}
		} else { //不是选稿期
			kekezu::keke_show_msg ( $url, $_lang['now_status_can_not_choose'], "error", $output );
		}
	}
	/**
	 * 检测是否可以发起投票
	 * @return boolean or show_msg
	 */
	public function check_start_vote($url = '', $output = 'normal') {
		global $_lang;
		if ($this->_uid != $this->_guid) { //非雇主无法发起
			kekezu::keke_show_msg ( $url, $_lang['start_vote_fail_and_employer_can_vote'], "error", $output );
		} else {
			if (! $this->_process_can ['task_vote']) {
				kekezu::keke_show_msg ( $url, $_lang['work_num_limit_notice'], "error", $output );
			} else {
				return true;
			}
		}
	}
	/**
	 * 检测是否可以投票
	 * @param int $work_id  稿件编号
	 * @return boolean or show_msg
	 */
	public function check_if_voted($work_id, $url = '', $output = 'normal') {
		global $_lang;
		$vote_count = dbfactory::get_count ( sprintf ( " select count(vote_id) from %switkey_vote where
		 work_id='%d' and uid='%d' and vote_ip='%s'", TABLEPRE, $work_id, $this->_uid, kekezu::get_ip () ) );
		if ($vote_count > 0) {
			kekezu::keke_show_msg ( $url, $_lang['you_have_vote'], "error", $output );
		} else {
			return true;
		}
	}
	/**
	 * @return 返回单人悬赏任务状态
	 */
	public static function get_task_status() {
		global $_lang;
		return array ("0" => $_lang['task_no_pay'], "1" => $_lang['task_wait_audit'], "2" => $_lang['task_vote_choose'], "3" => $_lang['task_choose_work'], "4" => $_lang['task_vote'], "5" => $_lang['task_gs'], "6" => "交付", "7" => $_lang['freeze'], "8" => $_lang['task_over'], "9" => $_lang['fail'], "10" => $_lang['task_audit_fail'], "11" => $_lang['arbitrate'], '12' => $_lang['assure_return_cash'] );
	}
	
	/**
	 * @return 返回单人悬赏稿件状态
	 */
	public static function get_work_status() {
		global $_lang;
		return array ('4' => $_lang['task_bid'], '5' => $_lang['task_in'], '7' => $_lang['task_out'], '8' => $_lang['task_can_not_choose_bid'] );
	}
	/**
	 * @return 返回任务英文状态
	 */
	public static function get_task_union_status() {
		return array ('0' => "wait", '1' => "audit", '2' => "sub", '3' => "choose", '4' => "vote", '5' => "notice", '6' => 'deliver', '7' => "freeze", '8' => "end", '9' => "failure", '10' => "audit_fail", '11' => "arbitrate" );
	}
	
	/**
	 * @return 返回 稿件英文状态
	 */
	
	public static function get_work_union_status() {
		return array ('0' => 'wait', '4' => 'bid', '5' => 'finalist', '7' => 'not_adopted', '8' => 'no_optional' );
	}
	
	public function dispose_order($order_id, $trust_response = false) {
		global $kekezu, $_K;
		global $_lang;
		$response = array ();
		//后台配置
		$task_config = $this->_task_config;
		$task_info = $this->_task_info; //任务信息
		$url = $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id;
		$task_status  = $this->_task_status;
		$order_info = dbfactory::get_one ( sprintf ( "select order_amount,order_status from %switkey_order where order_id='%d'", TABLEPRE, intval ( $order_id ) ) );
		$order_amount = $order_info ['order_amount'];
		if ($order_info ['order_status'] == 'ok') {
			$task_status==1&&$notice=$_lang['task_pay_success_and_wait_admin_audit'];
			$task_status==2&&$notice=$_lang['task_pay_success_and_task_pub_success'];
			return pay_return_fac_class::struct_response ( $_lang['operate_notice'],$notice, $url, 'success' );
		} else {
			if ($this->_trust_mode && $trust_response == false) {
				$res = false;
			} else {
				switch ($this->_trust_mode) {
					case "1" : //担保模式
						$pub_data = array ("uid" => $task_info ['uid'], "username" => $task_info ['username'], "obj_id" => $this->_task_id, "fina_cash" => $task_info ['task_cash'], "fina_time" => time (), "fina_action" => "pub_task", "order_id" => $task_info ['order_id'] );
						$res = keke_finance_class::finance_trust ( $pub_data, 'alipay_trust', 'out' );
						if ($task_info ['att_cash'] > 0) {
							$item_data = array ("uid" => $task_info ['uid'], "username" => $task_info ['username'], "obj_id" => $this->_task_id, "fina_cash" => $task_info ['att_cash'], "fina_time" => time (), "fina_action" => "payitem", "order_id" => $task_info ['order_id'] );
							$res = keke_finance_class::finance_trust ( $item_data, 'alipay_trust', 'out' );
						}
						break;
					case "0" :
						$res = keke_finance_class::cash_out ( $task_info['uid'], $order_amount, 'pub_task' ); //支付费用
						break;
				}
			}
			switch ($res == true) {
				case "1" : //支付成功
					/** 雇主推广事件产生*/
					$kekezu->init_prom ();
					if (kekezu::$_prom_obj->is_meet_requirement ( "pub_task", $this->_task_id )) {
						kekezu::$_prom_obj->create_prom_event ( "pub_task", $this->_guid, $task_info ['task_id'], $task_info ['task_cash'] );
					} //更改订单状态到已付款状态
					dbfactory::updatetable ( TABLEPRE . "witkey_order", array ("order_status" => "ok" ), array ("order_id" => "$order_id" ) );
					if ($order_amount < $task_config ['audit_cash'] && ! $this->_trust_mode) { //如果订单的金额比发布任务时配置的审核金额要小
						$this->set_task_status ( 1 ); //状态更改为审核状态
						return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_success_and_wait_admin_audit'], $url, 'success' );
					} else {
						$this->set_task_status ( 2 ); //状态更改为进行状态	
						return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_success_and_task_pub_success'], $url, 'success' );
					}
					break;
				case "0" : //支付失败
					switch ($this->_trust_mode) {
						case "1" :
							$pay_url = keke_trust_fac_class::trust_task_request ( "create", $this->_model_code, $this->_task_id, $task_info ['trust_type'] );
							break;
						case "0" :
							$pay_url = $_K ['siteurl'] . "/index.php?do=pay&order_id=$order_id"; //支付跳转链接
							break;
					}
					return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_error_and_please_repay'], $pay_url, 'warning' );
					break;
			}
		}
	}
}