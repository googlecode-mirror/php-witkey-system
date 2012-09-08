<?php
/**
 * 微博转发业务类
 */
keke_lang_class::load_lang_class ( 'wbdj_task_class' );
class wbdj_task_class extends keke_task_class {
	
	public $_task_status_arr; //任务状态数组
	public $_work_status_arr; //稿件状态数组
	

	public $_delay_rule; //延期规则
	public $_single_cash; //单稿实际金额
	

	protected $_inited = false;
	public static function get_instance($task_info) {
		static $obj = null;
		if ($obj == null) {
			$obj = new wbdj_task_class ( $task_info );
		}
		return $obj;
	}
	public function __construct($task_info) {
		parent::__construct ( $task_info );
		$this->init ();
	}
	
	public function init() {
		if (! $this->_inited) {
			$this->status_init ();
			$this->delay_rule_init ();
			$this->wiki_priv_init ();
			$this->get_weibo_init ();
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
	 * 从数据库 获取单条微博记录
	 */
	private function get_weibo_init() {
		$task_id = $this->_task_id;
		$weibo_info = db_factory::get_one ( sprintf ( " select * from %switkey_task_wbdj where task_id='%d'", TABLEPRE, $task_id ) );
		$this->_task_info = array_merge ( $weibo_info, $this->_task_info );
		$this->_single_cash = $this->_task_info ['click_price'] * (1 - $this->_task_info ['profit_rate'] / 100);
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
		$arr = wbdj_priv_class::get_priv ( $this->_task_id, $this->_model_id, $this->_userinfo );
		$this->_priv = $this->user_priv_format ( $arr );
	}
	/**
	 * 任务阶段时间描述_仅仅做描述
	 */
	public function get_task_timedesc() {
		global $_lang;
		$status_arr = $this->_task_status_arr;
		$task_status = $this->_task_status;
		$task_info = $this->_task_info;
		$time_desc = array ();
		switch ($task_status) {
			case "0"://未付款
				$time_desc ['ext_desc'] = $_lang['task_nopay_can_not_look']; //追加描述
				break;
			case "1":  //待审核
				$time_desc ['ext_desc'] = $_lang['wait_patient_to_audit']; //追加描述
				break;
			case "2" : //投稿中
				$time_desc ['time_desc'] = $_lang ['from_task_over']; //时间状态描述
				$time_desc ['time'] = $task_info ['sub_time']; //当前状态结束时间
				//$time_desc ['ext_desc'] = $_lang ['task_hand_working_and_can_hand']; //追加描述
				$time_desc ['ext_desc'] = $_lang['hand_work_and_reward_trust']; //追加描述
				break;
			case "7" : //冻结中
				//$time_desc ['ext_desc'] = $_lang ['task_diffrent_opnion_and_web_in']; //追加描述
				$time_desc ['ext_desc'] =$_lang['task_frozen_can_not_operate'];//追加描述
				break;
			case "8" : //结束
				//$time_desc ['ext_desc'] = $_lang ['task_haved_complete']; //追加描述
				$time_desc ['ext_desc'] = $_lang['task_over_congra_witkey']; //追加描述
				break;
			case "9" : //失败
				//$time_desc ['ext_desc'] = $_lang ['task_timeout_and_no_works_fail']; //追加描述
				$time_desc ['ext_desc'] = $_lang['pity_task_fail']; //追加描述
				break;
			case "10"://未通过审核
				$time_desc ['ext_desc'] = $_lang['fail_audit_please_repub']; //追加描述
				break;
			case "11" : //仲裁
				//$time_desc ['ext_desc'] = $_lang ['task_arbitrating']; //追加描述
				$time_desc ['ext_desc'] = $_lang['wait_for_task_arbitrate'];
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
		global $kekezu, $_K, $uid;
		$work_arr = array ();
		$sql = " select a.*,c.wb_type,c.wb_url,c.wb_account,c.wb_sid,c.get_cash,c.click_num,c.wb_data,b.seller_credit,b.seller_good_num,b.residency,b.seller_total_num,b.seller_level from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid left join " . TABLEPRE . "witkey_task_wbdj_work c on a.work_id=c.work_id";
		$count_sql = " select count(a.work_id) from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$where = " where a.task_id = '$this->_task_id' ";
		
		if (! empty ( $w )) {
			$w ['user_type'] == 'my' and $where .= " and a.uid = '$this->_uid'";
			isset ( $w ['work_status'] ) and $where .= " and a.work_status = '" . intval ( $w ['work_status'] ) . "'";
		}
		$where .= "   order by (CASE WHEN  a.work_status!=0 THEN 100 ELSE 0 END) desc,work_id asc ";
		if (! empty ( $p )) {
			$page_obj = $kekezu->_page_obj;
			$page_obj->setAjax ( 1 );
			$page_obj->setAjaxDom ( "gj_summery" );
			$count = intval ( db_factory::get_count ( $count_sql . $where ) );
			$pages = $page_obj->getPages ( $count, $p ['page_size'], $p ['page'], $p ['url'], $p ['anchor'] );
			$where .= $pages ['where'];
			$pages ['count'] = $count;
		}
		$work_info = db_factory::query ( $sql . $where );
		$work_arr ['work_info'] = $work_info;
		$work_arr ['pages'] = $pages;
		$work_ids = implode ( ',', array_keys ( $work_info ) );
		/*更新查看状态*/
		$work_ids && $uid == $this->_task_info ['uid'] and db_factory::execute ( 'update ' . TABLEPRE . 'witkey_task_work set is_view=1 where work_id in (' . $work_ids . ') and is_view=0' );
		
		return $work_arr;
	}
	
	/**
	 * 任务交稿页面
	 * @param string $platform 平台
	 * @param $call_back 
	 * @param $oauth_url 
	 * @return array
	 */
	public function get_weibo_info($platform, $call_back, $oauth_url) {
		$weibo_info_arr = array ();
		$weibo_info_arr ['reqiure'] = '';
		$weibo_login_class = new keke_oauth_login_class ( $platform );
		if ($platform && ! $_SESSION ['auth_' . $platform] ['last_key']) {
			
			$weibo_login_class->login ( $call_back, $oauth_url );
		} else {
			$weibo_info_arr ['user_info'] = $weibo_login_class->get_login_user_info (); //用户的微博信息
		}
		return $weibo_info_arr;
	}
	public function work_hand($work_desc, $hdn_att_file, $hidework = '2', $url = '', $output = 'normal') {
	
	}
	/**
	 * 任务交稿_step2
	 * @param $content 转发/发布内容+@somebody
	 * @param $comment 评论(转发的时候才有)
	 */
	public function weibo_work_hand($platform) {
		global $_K;
		global $_lang;
		$this->check_if_can_hand (); //是否可以交稿
		$url = $_K ['siteurl'] . "/index.php?do=task&task_id={$this->_task_id}&view=work";
		$has_handed = db_factory::get_count ( sprintf ( " select work_id from %switkey_task_work where task_id='%d' and uid='%d'", TABLEPRE, $this->_task_id, $this->_uid ) );
		if ($has_handed) { //已交过稿时
			kekezu::show_msg ( $_lang ['operate_notice'], $url, 2, $_lang ['pity_click_task_can_hand_work_once'], 'waring' );
		} else {
			$task_info = $this->_task_info;
			$wb_original = $this->_task_info ['wb_content']; //微博原始内容
			//添加数据库记录
			$work_obj = keke_table_class::get_instance ( "witkey_task_work" );
			$data_1 = array ('task_id' => $this->_task_id, 'uid' => $this->_uid, 'username' => $this->_username, 'work_status' => '6', 'work_title' => $this->_task_title . $_lang ['de_work'], 'work_desc' => $wb_original, 'work_time' => time () );
			$work_id = $work_obj->save ( $data_1 );
			$wbdj_obj = keke_table_class::get_instance ( "witkey_task_wbdj_work" );
			$ip = kekezu::get_ip (); //获取交稿者IP 
			$data_2 = array ('task_id' => $this->_task_id, 'work_id' => $work_id, 'wb_type' => $platform, 'ip' => $ip );
			$wbdj_id = $wbdj_obj->save ( $data_2 );
			if ($work_id && $wbdj_id) {
				/*发布微博*/
				$weibo_obj = new keke_weibo_class ( $platform );
				$siteurl = preg_replace ( "/localhost/i", "127.0.0.1", $_K ['siteurl'], 1 );
				$jump_url = $siteurl . "/index.php?do=task&task_id={$this->_task_id}&op=wb_cl&w_id={$work_id}";
				$wb_new = $wb_original . $jump_url; //微博原始内容（+推广链接）
				if ($this->_task_info ['wb_img']) {
					$img_address = $_K ['siteurl'] . '/' . $this->_task_info ['wb_img'];
				}
				$weibo_sid = $weibo_obj->post_wb ( $wb_new, $img_address );
				if ($weibo_sid) { //微博发送成功。更新点击稿件表
					$wb_login_obj = new keke_oauth_login_class ( $platform );
					$user_info_arr = $wb_login_obj->get_login_user_info ();
					$weibo_url = keke_weibo_class::build_wb_url ( $platform, $user_info_arr ['account'], $weibo_sid );
					$data_3 = array ('wb_url' => $weibo_url, 'wb_account' => $user_info_arr ['account'], 'wb_sid' => trim ( $weibo_sid ), 'wb_data' => $wb_new, 'jump_url' => $jump_url );
					$wbdj_obj->save ( $data_3, array ('djwk_id' => $wbdj_id ) );
					$this->plus_work_num (); //稿件数量增加
					$this->plus_accepted_num ( $this->_uid ); //中标次数+1
				} else { //微博发送失败.删除2条记录
					$work_obj->del ( 'work_id', $work_id );
					$wbdj_obj->del ( 'djwk_id', $wbdj_id );
					kekezu::show_msg ( $_lang ['operate_notice'], $url, 2, $_lang ['pity_hand_work_fail'], 'warning' );
				}
				kekezu::show_msg ( $_lang ['operate_notice'], $url, 2, $_lang ['congratulate_you_hand_work_success'], 'success' );
			} else {
				kekezu::show_msg ( $_lang ['operate_notice'], $url, 2, $_lang ['pity_hand_work_fail'], 'warning' );
			}
		}
	}
	
	/**
	 * 任务选稿, 交稿完成后检测稿件是否达到要求数量,并且对人物状态做出相应的调整
	 */
	public function work_choose($work_id, $to_status, $url = '', $output = 'normal', $trust_response = false) {
	
	}
	/**
	 * 稿件点击计费
	 * @param $work_id 稿件编号
	 */
	public function wb_click($work_id) {
		global $uid;
		$task_info = $this->_task_info; //任务信息
		$ip = kekezu::get_ip (); //获取当前用户IP
		$sql = " select a.work_id,a.uid,a.username,b.djwk_id,b.wb_type,b.ip,c.prom_url,c.wbdj_id
				,c.pay_amount,c.click_price from %switkey_task_work a left join %switkey_task_wbdj_work b on 
				a.work_id=b.work_id left join %switkey_task_wbdj c on a.task_id=c.task_id
				 where a.work_id='%d'";
		$wbdj_info = db_factory::get_one ( sprintf ( $sql, TABLEPRE, TABLEPRE, TABLEPRE, $work_id ) );
		if ($wbdj_info) {
			//非当前用户、非交稿者发布IP，剩余赏金
			if ($uid != $wbdj_info ['uid'] && $ip != $wbdj_info ['ip'] && $wbdj_info ['pay_amount'] <= $task_info ['task_cash']) {
				//在点击记录表中查找此IP的最新点击记录
			//if ($uid != $wbdj_info ['uid']&&$wbdj_info ['pay_amount'] <=$task_info ['task_cash']) {
				$refer_url = $_SERVER ['HTTP_REFERER'];
				$user_agent = $_SERVER ['HTTP_USER_AGENT'];
				switch ($wbdj_info ['wb_type']) {
					case "sina" :
						$refer_true = strpos ( $refer_url, "weibo.com" );
						break;
					case "ten" :
						$refer_true = strpos ( $refer_url, "t.qq.com" );
						break;
				}
				if ($refer_true !== FALSE && $user_agent) { //来自指定微博地址
					//** 以ip为主体依据*/
					$dj_info = db_factory::get_one ( sprintf ( " select refre_url,user_ip,user_agent,click_time from %switkey_task_wbdj_views where work_id='%d' and user_ip='%s' order by click_time desc limit 0,1", TABLEPRE, $work_id, $ip ) );
					if ($dj_info) { //点击信息存在、相差一天。创建
						$this->check_ip_and_agent ( $dj_info ['click_time'], $dj_info ['user_agent'], $user_agent, false ) and $this->create_dj_views ( $wbdj_info, $refer_url, $ip, $user_agent );
					} else {
						//** 以代理为主体依据*/
						$dj_info = db_factory::get_one ( sprintf ( " select refre_url,user_ip,user_agent,click_time from %switkey_task_wbdj_views where work_id='%d' and user_agent='%s' order by click_time desc limit 0,1", TABLEPRE, $work_id, $user_agent ) );
						if ($dj_info) { //点击信息存在、相差一天。创建
							$this->check_ip_and_agent ( $dj_info ['click_time'], $dj_info ['user_ip'], $ip ) and $this->create_dj_views ( $wbdj_info, $refer_url, $ip, $user_agent );
						} else { //2种判断都没有记录、直接创建
							$this->create_dj_views ( $wbdj_info, $refer_url, $ip, $user_agent );
						}
					}
				} else { //直接创建点击记录
					header ( "Location:" . $wbdj_info ['prom_url'] );
				}
			} else {
				header ( "Location:" . $task_info ['prom_url'] );
			}
        } else {
			header ( "Location:" . $task_info ['prom_url'] );
		}
	}
	/**
	 * 验证当前点击。是否 足够结算
	 */
	public function verify_click() {
		$enough = true;
		$task_info = $this->_task_info;
		$ver1 = $task_info ['task_cash'] - $task_info ['pay_amount']; //余额
		$ver2 = $ver1 - $task_info ['click_price']; //余额是否充足
		$ver1 > 0 && $ver2 >= 0 or $enough = false; //不够结算
		return $enough;
	}
	/**
	 * 判断IP和代理
	 * @param  boolean $click_time 上次点击时间
	 * @param  $ip ip/代理
	 * @param $compare_ip 比较 IP/代理
	 * @param unknown_type $is_ip 是否IP
	 * @return boolean
	 */
	public function check_ip_and_agent($click_time, $ip, $compare_ip, $is_ip = true) {
		//return true;
		$time_diff = time () - $click_time - 24 * 3600;
		if ($time_diff > 0) {
			switch ($is_ip) {
				case true :
					if ($ip != $compare_ip) { //不同的代理。允许创建
						return true;
					} else {
						header ( "Location:" . $this->_task_info ['prom_url'] );
					}
					break;
				case false :
					if ($ip != $compare_ip) { //不同的IP。允许创建
						return true;
					} else {
						header ( "Location:" . $this->_task_info ['prom_url'] );
					}
					break;
			}
		} else {
			header ( "Location:" . $this->_task_info ['prom_url'] );
		}
	}
	/**
	 * 创建点击记录
	 * @param $wbdj_info 点击稿件相关信息
	 * @param $refer_url 之前链接
	 * @param $ip  当前IP
	 */
	public function create_dj_views($wbdj_info, $refer_url, $ip, $user_agent) {
		if ($this->verify_click ()) {
			$price = $wbdj_info ['click_price']; //单价
			$real_price = $this->_single_cash; //扣利单价
			/*更新微博任务表*/
			db_factory::execute ( sprintf ( " update %switkey_task_wbdj set pay_amount=pay_amount+'%s',click_count=click_count+1 where wbdj_id='%d'", TABLEPRE, $price, $wbdj_info ['wbdj_id'] ) );
			/*更新微博稿件表*/
			db_factory::execute ( sprintf ( " update %switkey_task_wbdj_work set click_num=click_num+1,get_cash=get_cash+'%s' where djwk_id='%d'", TABLEPRE, $real_price, $wbdj_info ['djwk_id'] ) );
			/*插入微博点击记录表*/
			$views_obj = keke_table_class::get_instance ( "witkey_task_wbdj_views" );
			$data = array ('task_id' => $this->_task_id, 'work_id' => $wbdj_info ['work_id'], 'djwk_id' => $wbdj_info ['djwk_id'], 'refre_url' => $refer_url, 'user_ip' => $ip, 'user_agent' => $user_agent, 'click_time' => time () );
			$res = $views_obj->save ( $data );
			$dispose = false;
		} else {
			$res = $dispose = true;
		}
		if ($res) { //跳转
			$dispose and $this->dispose_task (); //金额不够或已用完，任务结束
			header ( "Location:" . $wbdj_info ['prom_url'] );
		}
	}
	/**
	 * 结算任务
	 * [分发赏金，余额退款]
	 */
	public function dispose_task() {
		global $kekezu, $_K;
		global $_lang;
		$t_info = $this->_task_info;
		$profit_rate = $this->_profit_rate; //利润比
		$url = '<a href ="' . $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '" target="_blank" >' . $this->_task_title . '</a>';
		if ($t_info ['task_status'] == '2') {
			$work_list = $this->get_wbdj_work ( 6 ); //稿件列表
			if (! empty ( $work_list )) {
				$price = $t_info ['click_price'];
				$s = sizeof ( $work_list );
				$bid_uid = array();
				for($i = 0; $i < $s; $i ++) {
					$v = $work_list [$i];
					if ($v ['click_num']) { //有点击
						$profit = $profit_rate * $price / 100; //利润
						$get_cash = ($price - $profit) * $v ['click_num']; //获取金额
						$data = array (':task_id' => $t_info ['task_id'], ':task_title' => $t_info ['task_title'], ':work_title' => $v ['work_title'] );
						keke_finance_class::init_mem ( 'task_bid', $data );
						keke_finance_class::cash_in ( $v ['uid'], $get_cash, 0, 'task_bid', '', 'task', $this->_task_id, $profit );
						// 写入feed
						$feed_arr = array ("feed_username" => array ("content" => $v ['username'], "url" => "index.php?do=space&member_id={$v['uid']}" ), "action" => array ("content" => $_lang ['success_bid_haved'], "url" => "" ), "event" => array ("content" => "$this->_task_title", "url" => "index.php?do=task&task_id=$this->_task_id", 'cash' => $get_cash ) );
						kekezu::save_feed ( $feed_arr, $v ['uid'], $v ['username'], 'work_accept', $this->_task_id );
						
						$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['task_over'], $_lang ['task_link'] => $url );
						$this->notify_user ( "dispose_task", $_lang ['work_cash_js_notice'], $v, '1', $v ['uid'] ); //通知威客
						$bid_uid[] = $v['uid'];
					}
				}
			}
			($t_info ['task_cash'] - $t_info ['pay_amount']) and $this->dispose_task_return (); //尾款结算
			$this->set_task_status ( 8 ); //任务完成
			$kekezu->init_prom ();
			$kekezu->_prom_obj->dispose_prom_event ( "pub_task", $this->_guid, $this->_task_id );
			/**
			 * 通知联盟
			 */
			if ($this->_task_info ['task_union'] == 2 && ! empty ( $bid_uid )) {
				$u = new keke_union_class ( $this->_task_id );
				$u->task_close ( array ('r_task_id' => $u->_r_task_id, 'indetify' => 1, 'bid_uid' => implode ( ',', $bid_uid ) ) );
			}
			$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['over'], $_lang ['task_link'] => $url );
			$this->notify_user ( "dispose_task", $_lang ['task_over_notice'], $v, 2, $this->_guid ); //通知雇主
		}
	}
	/**
	 * 获取点击稿件
	 */
	public function get_wbdj_work($status) {
		$sql = " select a.*,b.djwk_id,b.click_num from %switkey_task_work a left join %switkey_task_wbdj_work b 
				on a.work_id=b.work_id where a.task_id = '%d' and a.work_status='%d'";
		return db_factory::query ( sprintf ( $sql, TABLEPRE, TABLEPRE, $this->_task_id, $status ) );
	}
	/**
	 * 时间触发投稿到期处理(状态2:投稿中)
	 * 有稿件：结算任务
	 * 无稿件：任务失败
	 */
	public function time_hand_end() {
		if ($this->_task_status == 2 && $this->_task_info ['end_time'] < time ()) //任务投稿时间到
			if ($this->_task_info ['work_num']) {
				$this->dispose_task (); //结算
			} else {
				$this->dispose_task_return ();
			}
	}
	/**
	 * 任务尾款[失败]返还结算
	 */
	public function dispose_task_return() {
		global $kekezu, $_K;
		global $_lang;
		$config = $this->_task_config;
		$task_info = $this->_task_info;
		$task_cash = $task_info ['task_cash']; //任务总金额
		$fail_rate = $this->_fail_rate; //失败返金抽成比
		$pay_amount = $task_info ['pay_amount']; //已经支付的金额
		$remain_cash = $task_cash - $pay_amount; //剩余金额
		$site_profit = $remain_cash * $fail_rate / 100; //网站利润
		if ($pay_amount) { //返还模式
			$action = 'task_remain';
			switch ($config ['defeated']) {
				case "2" : //返款方式   金币
					$return_cash = '0';
					$return_credit = $remain_cash - $site_profit; //返还佣金
					break;
				case "1" : //现金(有花费现金优先将花费的现金返还,剩余部分返还金币)
					$cash_cost = $task_info ['cash_cost']; //现金花费
					$credit_cost = $task_info ['credit_cost']; //金币花费
					if ($cash_cost >= $remain_cash) { //花费现金多余余额
						$return_cash = $remain_cash - $site_profit;
						$return_credit = '0';
					} else {
						if ($cash_cost) {
							$return_cash = $cash_cost * (1 - $fail_rate / 100); //减去金币消耗
							$return_credit = ($remain_cash - $cash_cost) * (1 - $fail_rate / 100);
						} else {
							$return_cash = 0;
							$return_credit = $remain_cash * (1 - $fail_rate / 100);
						}
					}
					break;
			}
		} else { //失败模式
			$action = 'task_fail';
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
		}
		//财务理由
		$data = array (':model_name' => '微博点击', ':task_id' => $task_info ['task_id'], ':task_title' => $task_info ['task_title'] );
		keke_finance_class::init_mem ( 'task_fail', $data );
		$res = keke_finance_class::cash_in ( $this->_guid, $return_cash, floatval ( $return_credit ) + 0, 'task_fail', '', 'task', $this->_task_id, $site_profit );
		if ($res) {
			$url = '<a href ="' . $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '" target="_blank" >' . $this->_task_title . '</a>';
			if (! $pay_amount) {
				$this->set_task_status ( 9 ); //任务失败
				$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['fail'], $_lang ['task_link'] => $url );
				$this->notify_user ( "dispose_task", $_lang ['task_fail_notice'], $v, 2, $this->_guid ); //通知雇主
				/**
				 * 通知联盟
				 */
				if ($this->_task_info ['task_union'] == 2) {
					$u = new keke_union_class ( $this->_task_id );
					$u->task_close ( array ('r_task_id' => $u->_r_task_id, 'indetify' => - 1 ) );
				}
			} else {
				$this->set_task_status ( 8 ); //任务失败
				$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['task_over'], $_lang ['task_link'] => $url );
				$this->notify_user ( "dispose_task", $_lang ['task_remain_cash_return'], $v, 2, $this->_guid ); //通知雇主
			}
		}
	}
	/**
	 * 操作判断、能做什么
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
						$process_arr ['tools'] = true; //工具
						$process_arr ['reqedit'] = true; //补充需求
						break;
					case "0" : //威客
						$process_arr ['work_hand'] = true; //提交稿件
						// 						$process_arr ['task_comment'] = true; //任务回复
						$process_arr ['task_report'] = true; //任务举报
						break;
				}
				$process_arr ['work_report'] = true; //稿件举报
				break;
			case "8" : //已结束
				break;
		}
		$uid != $g_uid and $process_arr ['task_complaint'] = true; //任务投诉
		$process_arr ['work_complaint'] = true; //稿件投诉
		if ($user_info ['group_id']) { //管理员
			switch ($status) {
				case 1 : //审核
					$process_arr ['task_audit'] = true;
					break;
				case 2 : //推荐
					$task_info['is_top'] or $process_arr ['task_recommend'] = true;
					$process_arr ['task_freeze'] = true;
					break;
				default :
					if ($status > 1 && $status < 8) {
						$process_arr ['task_freeze'] = true;
					}
			}
		
		}
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
		return db_factory::execute ( sprintf ( " update %switkey_task_work set work_status='%d' where work_id='%d'", TABLEPRE, $to_status, $work_id ) );
	}
	
	/**
	 * 检测是否可以选标
	 * 先判断当前任务是否能选稿，再判断稿件是否已进行过操作
	 * @param int $work_id 
	 * @param int $to_status 
	 */
	public function check_if_operated($work_id, $to_status, $url = '', $output = 'normal') {
		$can_select = false; //是否可选标
		if (! $this->check_if_can_choose ( $url, $output )) { //不是选稿期
			return false;
		}
		$work_status = db_factory::get_count ( sprintf ( " select work_status from %switkey_task_work where work_id='%d'
					 and uid='%d'", TABLEPRE, $work_id, $this->_uid ) );
		if ($work_status != 0) { //只有状态是0的情况下才能操作稿件
			return false;
		}
		if (! $to_status) {
			return false;
		}
		if (! in_array ( $to_status, array (6, 7, 8 ) )) {
			return false;
		} //如果to_status不在6,7,8那么肯定出错
		return true;
	}
	
	/**
	 * @return 返回单人悬赏任务状态
	 */
	public static function get_task_status() {
		global $_lang;
		return array ("0" => $_lang ['task_no_pay'], "1" => $_lang ['task_wait_audit'], "2" => $_lang ['task_vote_choose'], "7" => $_lang ['freeze'], "8" => $_lang ['task_over'], "9" => $_lang ['fail'], "10" => $_lang ['task_audit_fail']);
	}
	
	/**
	 * @return 返回单人悬赏稿件状态
	 */
	public static function get_work_status() {
		global $_lang;
		return array ('6' => $_lang ['hg'] );
	}
	
	/**
	 * @return 返回任务英文状态
	 */
	public static function get_task_union_status() {
		return array ('0' => "wait", '1' => "audit", '2' => "sub", '3' => "choose", '4' => "vote", '5' => "notice", '6' => 'deliver', '7' => "freeze", '8' => "end", '9' => "failure", '10' => "audit_fail", '11' => "arbitrate" );
	}
	public function dispose_order($order_id) {
		global $kekezu, $_K;
		global $_lang;
		//后台配置
		$task_config = $this->_task_config;
		$task_info = $this->_task_info; //任务信息
		$url = $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id;
		$task_status = $this->_task_status;
		$order_info = db_factory::get_one ( sprintf ( "select order_amount,order_status from %switkey_order where order_id='%d'", TABLEPRE, intval ( $order_id ) ) );
		$order_amount = $order_info ['order_amount'];
		if ($order_info ['order_status'] == 'ok') {
			$task_status == 1 && $notice = $_lang ['task_pay_success_and_wait_admin_audit'];
			$task_status == 2 && $notice = $_lang ['task_pay_success_and_task_pub_success'];
			return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $notice, $url, 'success' );
		} else {
			$data = array (':model_name' => $this->_model_name, ':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
			keke_finance_class::init_mem ( 'pub_task', $data );
			$res = keke_finance_class::cash_out ( $task_info ['uid'], $order_amount, 'pub_task' ); //支付费用
			switch ($res == true) {
				case "1" : //支付成功
					/** 雇主推广事件产生*/
					$kekezu->init_prom ();
					if ($kekezu->_prom_obj->is_meet_requirement ( "pub_task", $this->_task_id )) {
						$kekezu->_prom_obj->create_prom_event ( "pub_task", $this->_guid, $task_info ['task_id'], $task_info ['task_cash'] );
					}
					$feed_arr = array ("feed_username" => array ("content" => $task_info ['username'], "url" => "index.php?do=space&member_id={$task_info['uid']}" ), "action" => array ("content" => $_lang ['pub_task'], "url" => "" ), "event" => array ("content" => "{$task_info['task_title']}", "url" => "index.php?do=task&task_id={$task_info['task_id']}" ) );
					kekezu::save_feed ( $feed_arr, $task_info ['uid'], $task_info ['username'], 'pub_task', $task_info ['task_id'] );
					
					/**更新任务的现金金币消耗*/
					$consume = kekezu::get_cash_consume ( $task_info ['task_cash'] );
					db_factory::execute ( sprintf ( " update %switkey_task set cash_cost='%s',credit_cost='%s' where task_id='%d'", TABLEPRE, $consume ['cash'], $consume ['credit'], $this->_task_id ) );
					
					//更改订单状态到已付款状态
					db_factory::updatetable ( TABLEPRE . "witkey_order", array ("order_status" => "ok" ), array ("order_id" => "$order_id" ) );
					if ($order_amount < $task_config ['audit_cash']) { //如果订单的金额比发布任务时配置的审核金额要小
						$this->set_task_status ( 1 ); //状态更改为审核状态
						return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_success_and_wait_admin_audit'], $url, 'success' );
					} else {
						$this->set_task_status ( 2 ); //状态更改为进行状态
						return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_success_and_task_pub_success'], $url, 'success' );
					}
					break;
				case "0" : //支付失败
					$pay_url = $_K ['siteurl'] . "/index.php?do=pay&order_id=$order_id"; //支付跳转链接
					return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_error_and_please_repay'], $pay_url, 'warning' );
					break;
			}
		}
	}
}