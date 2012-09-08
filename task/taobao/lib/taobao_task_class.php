<?php
/**
 * @author hr
 * @version V2.0
 * 微博转发业务类
 */
keke_lang_class::load_lang_class ( 'taobao_task_class' );
class taobao_task_class extends keke_task_class {
	
	public $_task_status_arr; // 任务状态数组
	public $_work_status_arr; // 稿件状态数组
	public $_assign; //计费模式
	public $_tao_id;
	public $_task_url; //任务地址
	protected $_inited = false;
	public static function get_instance($task_info) {
		static $obj = null;
		if ($obj == null) {
			$obj = new taobao_task_class ( $task_info );
		}
		return $obj;
	}
	public function __construct($task_info) {
		global $kekezu, $_K;
		parent::__construct ( $task_info );
		$siteurl = preg_replace ( "/localhost/i", "127.0.0.1", $_K ['siteurl'], 1 );
		$this->_task_url = $siteurl . '/index.php?do=task&task_id=' . $this->_task_id;
		$this->init ();
	}
	
	public function init() {
		if (! $this->_inited) {
			$this->status_init ();
			$this->wiki_priv_init ();
			$this->get_tao_init ();
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
	private function get_tao_init() {
		$task_id = $this->_task_id;
		$sql = 'select * from ' . TABLEPRE . 'witkey_task_taobao where task_id=' . $task_id;
		$tao_info = db_factory::get_one ( $sql );
		$this->_tao_id = $tao_info ['taobao_id'];
		$this->_assign = $tao_info ['assign'];
		$this->_task_info = array_merge ( $tao_info, $this->_task_info );
	}
	/**
	 * 威客权限动作判断
	 */
	public function wiki_priv_init() {
		$arr = taobao_priv_class::get_priv ( $this->_task_id, $this->_model_id, $this->_userinfo );
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
				$time_desc ['ext_desc'] =  $_lang['task_nopay_can_not_look']; //追加描述
				break;
			case "1":  //待审核
				$time_desc ['ext_desc'] = $_lang['wait_patient_to_audit']; //追加描述
				break;
			case "2" : // 投稿中
				$time_desc ['time_desc'] = $_lang ['from_hand_work_deadline']; // 时间状态描述
				$time_desc ['time'] = $task_info ['sub_time']; // 当前状态结束时间
				//$time_desc ['ext_desc'] = $_lang ['task_working_can_hand_work']; // 追加描述
				$time_desc ['ext_desc'] = $_lang['hand_work_and_reward_trust']; //追加描述
				if ($this->_task_config ['open_select'] == 'open') { // 开启进行选稿
					$time_desc ['g_action'] = $_lang ['now_employer_can_choose_work']; // 雇主追加描述
				}
				break;
			case "3":
				$time_desc ['ext_desc'] = $_lang['work_choosing_and_wait_employer_choose']; //追加描述
				break;
			case "7" : // 冻结中
				//$time_desc ['ext_desc'] = $_lang ['task_diffrent_opnion_and_web_in']; // 追加描述
				$time_desc ['ext_desc'] =$_lang['task_frozen_can_not_operate'];//追加描述
				break;
			case "8" : // 结束
				//$time_desc ['ext_desc'] = $_lang ['task_haved_complete']; // 追加描述
				$time_desc ['ext_desc'] = $_lang['task_over_congra_witkey']; //追加描述
				break;
			case "9" : // 失败
				//$time_desc ['ext_desc'] = $_lang ['task_timeout_and_no_works_fail']; // 追加描述
				$time_desc ['ext_desc'] = $_lang['pity_task_fail']; //追加描述
				break;
			case "10"://未通过审核
				$time_desc ['ext_desc'] =$_lang['fail_audit_please_repub']; //追加描述
				break;
			case "11" : //仲裁
				//$time_desc ['ext_desc'] = $_lang ['task_arbitrating']; //追加描述
				$time_desc ['ext_desc'] = $_lang['wait_for_task_arbitrate'];
				break;
		}
		return $time_desc;
	}
	
	/**
	 * 获取任务稿件信息 支持分页，用户前端稿件列表
	 * 
	 * @param $w array
	 * 前端查询条件数组
	 * ['work_status'=>稿件状态
	 * 'user_type'=>用户类型 --有值表示自己
	 * ......]
	 * @param $p array
	 * 前端传递的分页初始信息数组
	 * ['page'=>当前页面
	 * 'page_size'=>页面条数
	 * 'url'=>分页链接
	 * 'anchor'=>分页锚点]
	 * @return array work_list
	 */
	public function get_work_info($w = array(), $order = null, $p = array()) {
		global $kekezu, $_K, $uid;
		$work_arr = array ();
		$sql = " select a.*,c.*,b.seller_credit,b.seller_good_num,b.residency,b.seller_total_num,b.seller_level
				 from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b
				  on a.uid=b.uid left join " . TABLEPRE . "witkey_task_taobao_work c on a.work_id=c.work_id";
		$count_sql = " select count(a.work_id) from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$where = " where a.task_id = '$this->_task_id' ";
		
		if (! empty ( $w )) {
			$w ['user_type'] == 'my' and $where .= " and a.uid = '$this->_uid'";
			isset ( $w ['work_status'] ) and $where .= " and a.work_status = '" . intval ( $w ['work_status'] ) . "'";
		}
		$where .= "   order by (CASE WHEN  a.work_status!=0 THEN 100 ELSE 0 END) desc,a.work_id asc ";
		if (!empty($p)){
			$page_obj = $kekezu->_page_obj;
			$page_obj->setAjax ( 1 );
			$page_obj->setAjaxDom ( "gj_summery" );
			$count = intval ( db_factory::get_count ( $count_sql . $where ) );
			$pages = $page_obj->getPages ( $count, $p ['page_size'], $p ['page'], $p ['url'], $p ['anchor'] );
			$where .= $pages ['where'];
			$pages ['count'] = $count;
		}
		$work_info = db_factory::query ( $sql . $where );
		$work_info = kekezu::get_arr_by_key($work_info,'work_id');
		$work_arr ['work_info'] = $work_info;
		$work_arr ['pages'] = $pages;
		
		$work_ids = implode ( ',', array_keys ( $work_info ) );
		/*更新查看状态*/
		$work_ids && $uid == $this->_task_info ['uid'] and db_factory::execute ( 'update ' . TABLEPRE . 'witkey_task_work set is_view=1 where work_id in (' . $work_ids . ') and is_view=0' );
		return $work_arr;
	}
	
	/**
	 * 任务交稿页面
	 * 
	 * @param $platform string
	 * 平台
	 * @param
	 * $call_back
	 * @param
	 * $oauth_url
	 * @return array
	 */
	public function get_weibo_info($platform, $call_back, $oauth_url) {
		global $_lang;
		$weibo_info_arr = array ();
		$weibo_info_arr ['reqiure'] = '';
		$weibo_login_class = new keke_oauth_login_class ( $platform );
		if ($platform && ! $_SESSION ['auth_' . $platform] ['last_key']) {
			$weibo_login_class->login ( $call_back, $oauth_url );
		} else {
			$weibo_info_arr ['user_info'] = $weibo_login_class->get_login_user_info (); // 用户的微博信息
		}
		$weibo_class = new keke_weibo_class ( $platform );
		
		$weibo_info_arr ['tips'] = $_lang ['public'];
		$weibo_info_arr ['content'] = $this->_task_info ['wb_content'];
		$weibo_info_arr ['content_img'] = $this->_task_info ['wb_img'];
		$weibo_info_arr ['reqiure'] = $_lang ['pub_zd_wb_'];
		// 是否@好友
		if ($this->_task_info ['is_at'] == 1) {
			$weibo_info_arr ['user_fans'] = $weibo_class->get_followers_by_uid ( $weibo_info_arr ['user_info'] ['account'] ); // 获取用户的粉丝列表
			$weibo_info_arr ['reqiure'] .= '@' . $this->_task_info ['at_num'] . $_lang ['ge_friend_'];
		}
		$weibo_info_arr ['reqiure'] = rtrim ( $weibo_info_arr ['reqiure'], ',' );
		return $weibo_info_arr;
	}
	/**
	 * 获得影响力,ref array(覆盖度,活跃度,传播度)
	 * @access static
	 * @param array $user_info 用户的信息(此信息为微博api用户信息,而非彼信息也)
	 * @param array $idata &
	 * @param int $create_days 创建的天数,可选(为空的话,会再计算一次)
	 * @return int $affect 影响力
	 */
	static function get_affect($user_info, $platform, &$idata = array(), $create_days = '') {
		if ($platform == 'ten') {
			return self::get_ten_affect ( $user_info, $idata );
		}
		$follows = $user_info ['fans_count'];
		$bi_follows = $user_info ['hf_count'];
		$focus = $user_info ['gz_count'];
		$weibo = $user_info ['wb_count'];
		$favour = $user_info ['faver_count'];
		if ($create_days == '') {
			$days = floor ( time () - $user_info ['create_at'] ) / (60 * 60 * 24); //账号创建的天数计算
		}
		$days = max ( ( int ) $days, ( int ) $create_days );
		
		$coef_init = array (array (1.01, 10.02, 10.04, 10.95 ), //0-30
array (1.01, 8.12, 8.04, 8.95 ), //30-100
array (2.01, 4.04, 3.04, 1.95 ) ); //>100
		//系数 
		if ($days <= 30) {
			$coef = $coef_init ['0'];
		} else if ($days > 30 && $days <= 100) {
			$coef = $coef_init ['1'];
		} else if ($days > 100) {
			$coef = $coef_init ['2'];
		} else {
			return false;
		}
		$fugai = round ( ((( int ) $follows / 8289) + ( int ) $bi_follows * 1.8 + ( int ) $weibo * 1.46) / $coef ['1'] ); //覆盖度
		$huoyue = round ( ((( int ) $bi_follows / 1.48) + ( int ) $focus / ( int ) $days * 0.26 + ( int ) $favour * 1.26) / $coef ['2'] ); //活跃度
		$chuanbo = round ( ((( int ) $follows / 7748) + ( int ) $bi_follows * 1.46 + ( int ) $weibo / ( int ) $days * 1.85) / $coef ['3'] ); //传播度
		

		$idata = array ($fugai, $huoyue, $chuanbo );
		$affect = round ( (($fugai / 8.4) + $huoyue * 1.13 + $chuanbo * 0.894) / $coef ['0'] ); //影响力
		return $affect;
	
	}
	static function get_ten_affect($user_info, &$idata) {
		$follows = $user_info ['fans_count'];
		$focus = $user_info ['gz_count'];
		$weibo = $user_info ['wb_count'];
		$isvip = ( int ) $user_info ['isvip'];
		
		$fans_interval = array (100, 50, 10, 1, 0 ); //从大到小,粉丝区间/1000
		$fans_coef_init = array (6223, 1428, 628, 58, 38 ); //粉丝系数
		while ( list ( $k, $v ) = each ( $fans_interval ) ) {
			if ($follows > ($v * 1000)) {
				$fans_coef = $fans_coef_init [$k];
				break;
			}
		}
		$weibo_interval = array (3000, 1000, 300, 100, 50, 0 ); //从大到小,微博数量(顺序)
		$weibo_coef_init = array (1.21, 1.14, 1.02, 0.93, 0.62, 0.33 ); //微博区间,要跟$weibo_interval保持一致
		while ( list ( $key, $value ) = each ( $weibo_interval ) ) {
			if ($weibo > $value) {
				$weibo_coef = $weibo_coef_init [$key];
				break;
			}
		}
		$affect = round ( ($weibo * 1.03 + $focus * 0.86 + $follows / $fans_coef + $isvip * 100) / 3.8 * $weibo_coef );
		$fugai = round ( $affect * 0.188 ); //覆盖度
		$huoyue = round ( $affect * 0.524 ); //活跃度
		$chuanbo = round ( $affect * 0.288 ); //传播度
		$idata = array ($fugai, $huoyue, $chuanbo );
		return $affect;
	}
	/**
	 * 根据影响力计算影响力等级
	 * @param int $affect 影响力
	 * @param array $config $mode['affect_rule'], 数组下标从1开始,key表示等级
	 */
	static function get_affect_level($affect, $config) {
		if (! isset ( $affect ) || ! $config) { //如果$affect是0的话........,所以应该用isset,而不能用!$affect
			return false;
		}
		if ($affect <= $config ['1'] ['max']) {
			return ( int ) 1;
		}
		while ( list ( $k, $v ) = each ( $config ) ) {
			if ($v ['min'] <= $affect && $affect <= $v ['max']) {
				return ( int ) $k;
			}
		}
		if ($affect > $config [sizeof ( $config )] ['max']) {
			return sizeof ( $config );
		}
	}
	/**
	 * 发布微博
	 * @param $platform 交稿平台
	 * @param $at .$at需要@的数组。
	 * @param $at .$at需要@的数组。
	 * @param $jump_url 系统保存的本地跳转链接
	 */
	public function post_wb($platform, $at = array(), $jump_url = '') {
		global $_lang, $_K;
		$t_info = $this->_task_info;
		$content = $t_info ['wb_content'] . '。';
		$url = $this->_task_url . "&op=work_hand&platform=" . $platform . "&step=2";
		// 判断@人数是否符合要求
		if ($t_info ['is_at'] == 1) { // @sb.(somebody)
			sizeof ( $at ) >= $t_info ['at_num'] or kekezu::show_msg ( '操作提示', $url, 3, '您必须@' . $t_info ['at_num'] . '人', 'warning' );
			foreach ( $at as $v ) {
				$content .= '@' . $v . ' /';
			}
			$content = rtrim ( $content, '/' );
		}
		$content .= ' ' . $jump_url;
		$wl_obj = new keke_oauth_login_class ( $platform );
		$info = $wl_obj->get_login_user_info (); //获取用户信息
		$wb_obj = new keke_weibo_class ( $platform );
		$wb_sid = $wb_obj->post_wb ( $content, $t_info ['wb_img'] );
		$wb_sid or kekezu::show_msg ( $_lang ['operate_notice'], $jump_url, 2, $_lang ['wb_pub_error_repeat_pub_again'], 'warning' );
		return array ('wb_sid' => $wb_sid, 'info' => $info, 'con' => $content );
	}
	/**
	 * 任务交稿
	 * @param $work_desc .继承自基类。此处指代.$platform
	 * @param $hdn_att_file .继承自基类。此处指代 $at需要@的数组。
	 * @see keke_task_class::work_hand()
	 */
	public function work_hand($work_desc, $hdn_att_file, $hidework = '2', $url = '', $output = 'normal') {
		global $_K, $_lang;
		$platform = $work_desc; //交稿平台
		$at = $hdn_att_file; //@数组
		kekezu::check_login ();
		$this->check_if_can_hand (); // 是否可以交稿
		$res = $this->check_work_times ( $platform ); //是否多次交稿
		switch ($this->_assign) { //计费模式
			case "1" : //影响力模式
				$work_id = $this->affect_work_hand ( $platform, $at );
				break;
			case "2" : //点击模式
				$work_id = $this->click_work_hand ( $platform, $at );
				break;
		}
		if ($work_id) {
			$this->plus_work_num (); // 稿件数量增加
			kekezu::show_msg ( $_lang ['operate_notice'], $this->_task_url . '&view=work&work_id=' . $work_id, 2, $_lang ['congratulate_you_hand_work_success'], 'success' ); // $url,
		} else {
			kekezu::show_msg ( $_lang ['operate_notice'], $this->_task_url, 2, $_lang ['pity_hand_work_fail'], 'warning' );
		}
	}
	/**
	 * 构造要存入表的用户微博数据数组
	 * @param $platform 平台
	 * @param $wb_info 微博信息
	 */
	public function format_wb_data($platform, $wb_info) {
		$wb_sid = $wb_info ['wb_sid']; //新发微博编号
		$wb_content = $wb_info ['con']; //新发微博内容
		$u_info = $wb_info ['info']; //用户微博基本信息
		$exist_days = floor ( time () - $u_info ['create_at'] ) / (60 * 60 * 24); //账号创建的天数计算
		$params = array ();
		$affect = self::get_affect ( $u_info, $platform, $params, $exist_days );
		$wb_leve = self::get_affect_level ( $affect, $this->_task_config [$platform . '_affect_rule'] );
		$wb_url = keke_weibo_class::build_wb_url ( $platform, $u_info ['account'], $wb_sid );
		$wb_data = serialize ( $u_info );
		return array ('fans' => $u_info ['fans_count'], 'hf_num' => $u_info ['fans_count'], 'focus_num' => $u_info ['gz_count'], 'wb_num' => $u_info ['wb_count'], 'faver_num' => $u_info ['faver_count'], 'create_day' => $exist_days, 'fgd_num' => $params ['0'], 'hyd_num' => $params ['1'], 'cbd_num' => $params ['2'], 'yxl_num' => $affect, 'wb_leve' => $wb_leve, 'wb_url' => $wb_url, 'wb_account' => $u_info ['account'], 'wb_sid' => $wb_sid, 'wb_data' => $wb_data );
	}
	
	/**
	 * 任务交稿_step2
	 * 
	 * @param $platform 用户当前所选择的平台
	 * @param $at 用户at的人
	 * @return null
	 */
	public function affect_work_hand($platform, $at = array()) {
		global $_K, $_lang;
		$wb_info = $this->post_wb ( $platform, $at );
		$work_obj = keke_table_class::get_instance ( "witkey_task_work" );
		$data_1 = array ('task_id' => $this->_task_id, 'uid' => $this->_uid, 'username' => $this->_username, 'work_title' => $this->_task_title . $_lang ['de_work'], 'work_desc' => $wb_info ['con'], 'work_time' => time () );
		$work_id = $work_obj->save ( $data_1 );
		$tao_obj = keke_table_class::get_instance ( "witkey_task_taobao_work" );
		$data_2 = array ('task_id' => $this->_task_id, 'work_id' => $work_id, 'wb_type' => $platform );
		$wb_data = $this->format_wb_data ( $platform, $wb_info );
		$wb_data and $data_2 = array_merge ( $data_2, $wb_data );
		$tao_id = $tao_obj->save ( $data_2 );
		if (! $work_id || ! $tao_id /* || !$this->work_choose($work_id, '6') */){
			return false;
		}
		if ($this->work_choose ( $work_id, '6' )) { //结算
			return $work_id;
		}
		return false;
	
	}
	
	/**
	 * 检查交稿次数
	 * @param $platform 平台
	 */
	public function check_work_times($platform) {
		global $_lang;
		$sql = " select count(a.tbwk_id) count from %switkey_task_taobao_work a left join 
				%switkey_task_work b on a.work_id=b.work_id where b.uid='%d' and a.task_id='%d' and a.wb_type='%s'";
		$count = db_factory::get_count ( sprintf ( $sql, TABLEPRE, TABLEPRE, $this->_uid, $this->_task_id, $platform ) );
		intval ( $count ) and kekezu::show_msg ( $_lang ['operate_notice'], $this->_task_url, 2, '您已在此平台提交过稿件，无法再次交稿', 'warning' );
		return true;
	}
	
	/**
	 * 根据影响力等级得出要付的money<br/>
	 * <b>这里的$this->_task_info ['unit_price']的key(level)一定要跟后台的配置的key保持一致,不然有问题</b>
	 * @param int $affect_level
	 * @return money
	 */
	private function according_affect_get_cash($affect_level) {
		$unit_price = unserialize ( $this->_task_info ['unit_price'] );
		return ( float ) $unit_price [( int ) $affect_level];
	}
	/**
	 * 任务选稿, 交稿完成后检测稿件是否达到要求数量,并且对人物状态做出相应的调整
	 * @param $work_id int       	
	 * @param $to_status int       	
	 * @see keke_task_class::work_choose()
	 */
	public function work_choose($work_id, $to_status, $url = '', $output = 'normal', $trust_response = false) {
		global $kekezu, $_K, $_lang;
		if (! $this->check_if_operated ( $work_id, $to_status, $url, $output ) || ! $this->check_if_can_choose ()) { // 检测是否能够选稿
			return false;
		
		// 			kekezu::show_msg ( $_lang ['error_notice'], $url, 2, $_lang ['present_status_and_not_operate_work'], "warning" );
		}
		$status_arr = $this->get_work_status ();
		$sql = "select a.*,b.wb_leve,b.get_cash from %switkey_task_work a left join 
				%switkey_task_taobao_work b on a.work_id=b.work_id where a.work_id='%d'";
		$work_info = db_factory::get_one ( sprintf ( $sql, TABLEPRE, TABLEPRE, $work_id ) );
		if ($work_info ['work_status'] != 0) { //已经操作过、无法继续结算
			return false;
		}
		if (! $this->set_work_status ( $work_id, $to_status )) { //状态变更失败
			return false;
		}
		$url = '<a href ="' . $this->_task_url . '">' . $this->_task_title . '</a>';
		if ($to_status == 6) { // 合格结算并且提示用户
			$single_cash = $this->according_affect_get_cash ( $work_info ['wb_leve'] ); // 手续费之前
			$syje = floatval ( $this->_task_info ['task_cash'] - $this->_task_info ['pay_amount'] );
			$syje <= $single_cash and $single_cash = $syje; // 如果最后所剩下的金额不够付款,那么有多少给多少
			$profit_cash = $single_cash * floatval ( $this->_task_info ['profit_rate'] / 100 ); // 手续费
			$real_cash = $single_cash - $profit_cash; // 手续费之后
			$data = array (':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
			keke_finance_class::init_mem ( 'task_bid', $data );
			keke_finance_class::cash_in ( $work_info ['uid'], $real_cash, 0, 'task_bid', '', 'task', $this->_task_id, $profit_cash );
			// 更改已支付赏金
			db_factory::execute ( sprintf ( "update %switkey_task_taobao set pay_amount=pay_amount+'%.3f',sy_amount=sy_amount-'%.3f' where task_id='%d'", TABLEPRE, $single_cash, $single_cash, $this->_task_id ) );
			// 更改中标金额
			db_factory::execute ( sprintf ( "update %switkey_task_taobao_work set get_cash='%.3f' where work_id='%d'", TABLEPRE, $real_cash, $work_id ) );
			// 更改稿件支付金额
			db_factory::execute ( sprintf ( "update %switkey_task_work set work_price='%.3f' where work_id='%d'", TABLEPRE, $real_cash, $work_id ) );
			// 检测是否已经达到稿件数量
			$pay_amount = floatval ( $this->_task_info ['pay_amount'] ) + $single_cash;
			if ($pay_amount >= $this->_task_info ['task_cash']) {
				if ($this->set_task_status ( 8 )) {
					/**
					 * 通知联盟
					 */
					if ($this->_task_info ['task_union'] == 2) {
						$bid_uid = array ();
						$ids = db_factory::query ( 'select uid from ' . TABLEPRE . 'witkey_task_work where work_status=6 and task_id=' . $this->_task_id );
						foreach ( $ids as $v ) {
							$bid_uid [] = $v ['uid'];
						}
						$u = new keke_union_class ( $this->_task_id );
						$u->task_close ( array ('r_task_id' => $u->_r_task_id, 'indetify' => 1, 'bid_uid' => implode ( ',', $bid_uid ) ) );
					}
					$v_arr = array ($_lang ['username'] => $this->_gusername, $_lang ['model_name'] => $this->_model_name, $_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $this->_task_title );
					keke_msg_class::notify_user ( $this->_guid, $this->_gusername, 'task_over', $_lang ['task_over_notice'], $v_arr );
				}
			}
			// 写入feed
			$feed_arr = array ("feed_username" => array ("content" => $work_info ['username'], "url" => "index.php?do=space&member_id={$work_info['uid']}" ), "action" => array ("content" => $_lang ['success_bid_haved'], "url" => "" ), "event" => array ("content" => "$this->_task_title", "url" => "index.php?do=task&task_id=$this->_task_id", 'cash' => $real_cash ) );
			kekezu::save_feed ( $feed_arr, $work_info ['uid'], $work_info ['username'], 'work_accept', $this->_task_id );
			$this->plus_accepted_num ( $work_info ['uid'] );
			//消息提示
			$v = array ($_lang['work_status']=>$status_arr[$to_status],$_lang ['username'] => $work_info ['username'], $_lang ['website_name'] => $kekezu->_sys_config ['website_name'], $_lang ['task_id'] => "#" . $this->_task_id, $_lang ['task_title'] => $url, $_lang ['bid_cash'] => $single_cash );
			$this->notify_user ( "task_bid", $_lang ['work_bid'], $v, '1', $work_info ['uid'] );
			return true;
		}
	}
	/**
	 * 点击模式交稿
	 * @param $platform 平台
	 * @param $at @at
	 */
	public function click_work_hand($platform, $at) {
		global $_K, $_lang;
		$pass = false;
		$work_obj = keke_table_class::get_instance ( "witkey_task_work" );
		$data_1 = array ('task_id' => $this->_task_id, 'uid' => $this->_uid, 'username' => $this->_username, 'work_title' => $this->_task_title . $_lang ['de_work'], 'work_status' => 6, 'work_time' => time () );
		$work_id = $work_obj->save ( $data_1 );
		$tao_obj = keke_table_class::get_instance ( "witkey_task_taobao_work" );
		$data_2 = array ('task_id' => $this->_task_id, 'work_id' => $work_id, 'wb_type' => $platform, 'ip' => kekezu::get_ip () );
		$tao_id = $tao_obj->save ( $data_2 );
		if ($work_id && $tao_id) {
			/*发布微博*/
			$j_url = $this->_task_url . "&op=wb_cl&w_id={$work_id}";
			$wb_info = $this->post_wb ( $platform, $at, $j_url );
			if ($wb_info) { //成功
				$work_obj->save ( array ('work_desc' => $wb_info ['con'] ), array ('work_id' => $work_id ) ); //更新稿件内容
				$wb_data = $this->format_wb_data ( $platform, $wb_info );
				$wb_data ['jump_url'] = $j_url;
				$tao_obj->save ( $wb_data, array ('tbwk_id' => $tao_id ) );
				$this->plus_accepted_num ( $this->_uid ); //中标次数+1
				$pass = $work_id;
			} else { //失败
				$work_obj->del ( 'work_id', $work_id );
				$tao_obj->del ( 'tbwk_id', $tao_id );
			}
		}
		return $pass;
	}
	/**
	 * 稿件点击计费
	 * @param $work_id 稿件编号
	 */
	public function wb_click($work_id) {
		global $uid;
		$task_info = $this->_task_info; //任务信息
		$ip = kekezu::get_ip (); //获取当前用户IP
		$sql = " select a.work_id,a.uid,a.username,b.tbwk_id,b.wb_type,b.ip,c.prom_url,c.taobao_id
				,c.pay_amount,c.sy_amount,c.unit_price from %switkey_task_work a left join %switkey_task_taobao_work b on 
				a.work_id=b.work_id left join %switkey_task_taobao c on a.task_id=c.task_id
				 where a.work_id='%d'";
		$tao_info = db_factory::get_one ( sprintf ( $sql, TABLEPRE, TABLEPRE, TABLEPRE, $work_id ) );
		if ($tao_info) {
			//非当前用户、非交稿者IP，剩余赏金
			if ($uid != $tao_info ['uid'] && $ip != $tao_info ['ip'] && $tao_info ['pay_amount'] < $task_info ['task_cash']) {
				//在点击记录表中查找此IP的最新点击记录
				$refer_url = $_SERVER ['HTTP_REFERER'];
				$user_agent = $_SERVER ['HTTP_USER_AGENT'];
				switch ($tao_info ['wb_type']) {
					case "sina" :
						$refer_true = strpos ( $refer_url, "weibo.com" );
						break;
					case "ten" :
						$refer_true = strpos ( $refer_url, "t.qq.com" );
						break;
				}
				if ($refer_true !== FALSE && $user_agent) { //来自指定微博地址
					//** 以ip为主体依据*/
					$dj_info = db_factory::get_one ( sprintf ( " select refer_url,user_ip,user_agent,click_time from %switkey_task_taobao_views where work_id='%d' and user_ip='%s' order by click_time desc limit 0,1", TABLEPRE, $work_id, $ip ) );
					if ($dj_info) { //点击信息存在、相差一天。创建
						$this->check_ip_and_agent ( $dj_info ['click_time'], $dj_info ['user_agent'], $user_agent, false ) and $this->create_tao_views ( $tao_info, $refer_url, $ip, $user_agent );
					} else {
						//** 以代理为主体依据*/
						$dj_info = db_factory::get_one ( sprintf ( " select refre_url,user_ip,user_agent,click_time from %switkey_task_wbdj_views where work_id='%d' and user_agent='%s' order by click_time desc limit 0,1", TABLEPRE, $work_id, $user_agent ) );
						if ($dj_info) { //点击信息存在、相差一天。创建
							$this->check_ip_and_agent ( $dj_info ['click_time'], $dj_info ['user_ip'], $ip ) and $this->create_tao_views ( $tao_info, $refer_url, $ip, $user_agent );
						} else { //2种判断都没有记录、直接创建
							$this->create_tao_views ( $tao_info, $refer_url, $ip, $user_agent );
						}
					}
				} else { //直接创建点击记录
					header ( "Location:" . $tao_info ['prom_url'] );
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
		$ver2 = $ver1 - $task_info ['unit_price']; //余额是否充足
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
	 * @param $tao_info 点击稿件相关信息
	 * @param $refer_url 之前链接
	 * @param $ip  当前IP
	 */
	public function create_tao_views($tao_info, $refer_url, $ip, $user_agent) {
		if ($this->verify_click ()) {
			$price = $tao_info ['unit_price']; //单价
			$real_price = $price * (1 - $this->_task_info ['profit_rate'] / 100); //扣利单价
			/*更新微博任务表*/
			db_factory::execute ( sprintf ( " update %switkey_task_taobao set pay_amount=pay_amount+'%s',sy_amount=sy_amount-'%s',click_count=click_count+1 where taobao_id='%d'", TABLEPRE, $price, $price, $tao_info ['taobao_id'] ) );
			/*更新微博稿件表*/
			db_factory::execute ( sprintf ( " update %switkey_task_taobao_work set click_num=click_num+1,get_cash=get_cash+'%s' where tbwk_id='%d'", TABLEPRE, $real_price, $tao_info ['tbwk_id'] ) );
			/*插入微博点击记录表*/
			$views_obj = keke_table_class::get_instance ( "witkey_task_taobao_views" );
			$data = array ('task_id' => $this->_task_id, 'work_id' => $tao_info ['work_id'], 'tbwk_id' => $tao_info ['tbwk_id'], 'refer_url' => $refer_url, 'user_ip' => $ip, 'user_agent' => $user_agent, 'click_time' => time () );
			$res = $views_obj->save ( $data );
			$dispose = false;
		} else {
			$res = $dispose = true;
		}
		if ($res) { //跳转
			$dispose and $this->dispose_task (); //金额不够或已用完，任务结束
			header ( "Location:" . $tao_info ['prom_url'] );
		}
	}
	/**
	 * 结算任务
	 * [分发赏金，余额退款]
	 */
	public function dispose_task() {
		global $kekezu, $_K, $_lang;
		$t_info = $this->_task_info;
		$prom_obj = $kekezu->_prom_obj;
		$profit_rate = $this->_profit_rate; //利润比
		$url = '<a href ="' . $this->_task_url . '" target="_blank" >' . $this->_task_title . '</a>';
		if ($t_info ['task_status'] == '2') {
			$work_list = $this->get_task_work ( 6 ); //稿件列表
			if (! empty ( $work_list )) {
				$price = $t_info ['unit_price'];
				$s = sizeof ( $work_list );
				$bid_uid = array ();
				for($i = 0; $i < $s; $i ++) {
					$v = $work_list [$i];
					if ($v ['click_num']) { //有点击
						$profit = $profit_rate * $price / 100; //利润
						$get_cash = ($price - $profit) * $v ['click_num']; //获取金额
						$data = array (':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
						keke_finance_class::init_mem ( 'task_bid', $data );
						keke_finance_class::cash_in ( $v ['uid'], $get_cash, 0, 'task_bid', '', 'task', $this->_task_id, $profit );
						$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['task_over'], $_lang ['task_link'] => $url );
						$this->notify_user ( "dispose_task", $_lang ['work_cash_js_notice'], $v, '1', $v ['uid'] ); //通知威客
						$bid_uid [] = $v ['uid'];
					}
				}
			}
			$t_info ['sy_amount'] and $this->dispose_task_return (); //尾款结算
			$this->set_task_status ( 8 ); //任务完成
			/**
			 * 通知联盟
			 */
			if ($this->_task_info ['task_union'] == 2 && ! empty ( $bid_uid )) {
				$u = new keke_union_class ( $this->_task_id );
				$u->task_close ( array ('r_task_id' => $u->_r_task_id, 'indetify' => 1, 'bid_uid' => implode ( ',', $bid_uid ) ) );
			}
			$kekezu->init_prom ();
			$kekezu->_prom_obj->dispose_prom_event ( "pub_task", $this->_guid, $this->_task_id );
			$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['over'], $_lang ['task_link'] => $url );
			$this->notify_user ( "dispose_task", $_lang ['task_over_notice'], $v, 2, $this->_guid ); //通知雇主
		}
	}
	/**
	 * 获取点击稿件
	 */
	public function get_task_work($work_status = '', $work_id = '') {
		$sql = " select a.*,b.tbwk_id,b.click_num from %switkey_task_work a left join %switkey_task_taobao_work b 
				on a.work_id=b.work_id where a.task_id = '%d' and a.work_status='%d'";
		return db_factory::query ( sprintf ( $sql, TABLEPRE, TABLEPRE, $this->_task_id, $work_status ) );
	}
	/**
	 * 操作判断、能做什么
	 * //注意用户权限的判断
	 * 雇主不受威客权限的限制、、拥有威客的所有权限
	 * 威客严格受到条件约束
	 * 威客限制：查看任务
	 * 留言
	 * 举报
	 * 
	 * @see keke_task_class::process_can()
	 */
	public function process_can() {
		$wiki_priv = $this->_priv; // 威客权限数组
		$process_arr = array ();
		$status = intval ( $this->_task_status );
		$task_info = $this->_task_info;
		$config = $this->_task_config;
		$g_uid = $this->_guid;
		$uid = $this->_uid;
		$user_info = $this->_userinfo;
		
		switch ($status) {
			case "2" : // 投稿中
				switch ($g_uid == $uid) { // 雇主
					case "1" :
						$process_arr ['tools'] = true; //工具
						$process_arr ['reqedit'] = true; // 补充需求
						$process_arr ['work_comment'] = true; // 稿件回复
						break;
					case "0" : // 威客
						$process_arr ['work_choose'] = true; // 选稿
						$process_arr ['work_hand'] = true; // 提交稿件
						$process_arr ['task_report'] = true; // 任务举报
						break;
				}
				$process_arr ['work_report'] = true; // 稿件举报
				break;
			case "8" : // 已结束
				break;
		}
		$uid != $g_uid and $process_arr ['task_complaint'] = true; // 任务投诉
		$process_arr ['work_complaint'] = true; // 稿件投诉
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
	 * 
	 * @param $work_id int
	 * 稿件编号
	 * @param $to_status int
	 * 更新到状态
	 * @return boolean
	 */
	public function set_work_status($work_id, $to_status) {
		return db_factory::execute ( sprintf ( " update %switkey_task_work set work_status='%d' where work_id='%d'", TABLEPRE, $to_status, $work_id ) );
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
			$action = 'task_remain_return';
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
		$data = array (':model_name' => $this->_model_name, ':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
		keke_finance_class::init_mem ( $action, $data );
		$res = keke_finance_class::cash_in ( $this->_guid, $return_cash, floatval ( $return_credit ) + 0, $action, '', 'task', $this->_task_id, $site_profit );
		if ($res) {
			$url = '<a href ="' . $this->_task_url . '" target="_blank" >' . $this->_task_title . '</a>';
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
				$this->set_task_status ( 8 ); //任务完成
				$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['task_over'], $_lang ['task_link'] => $url );
				$this->notify_user ( "dispose_task", $_lang ['task_remain_cash_return'], $v, 2, $this->_guid ); //通知雇主
			}
		}
	}
	/**
	 * 时间触发投稿到期处理(状态2:投稿中)
	 * 有稿件：结算稿件
	 * 无稿件：任务失败
	 */
	public function time_hand_end() {
		if ($this->_task_status == 2 && $this->_task_info ['end_time'] < time ()) // 任务投稿时间到
			if ($this->_task_info ['work_num']) {
				$this->dispose_task (); //结算
			} else {
				$this->dispose_task_return (); // 否则任务失败,退还赏金
			}
	}
	
	/**
	 * 检测是否可以选标
	 * 先判断当前任务是否能选稿，再判断稿件是否已进行过操作
	 * 
	 * @param $work_id int       	
	 * @param $to_status int       	
	 */
	public function check_if_operated($work_id, $to_status, $url = '', $output = 'normal') {
		$can_select = false; // 是否可选标
		if (! $this->check_if_can_choose ( $url, $output )) { // 不是选稿期
			return false;
		}
		$work_status = db_factory::get_count ( sprintf ( " select work_status from %switkey_task_work where work_id='%d'
					 and uid='%d'", TABLEPRE, $work_id, $this->_uid ) );
		if ($work_status != 0) { // 只有状态是0的情况下才能操作稿件
			return false;
		}
		if (! $to_status) {
			return false;
		}
		if (! in_array ( $to_status, array (6, 7, 8 ) )) {
			return false;
		} // 如果to_status不在6,7,8那么肯定出错
		return true;
	}
	
	/**
	 *
	 * @return 返回单人悬赏任务状态
	 */
	public static function get_task_status() {
		global $_lang;
		return array ("0" => $_lang ['task_no_pay'], "1" => $_lang ['task_wait_audit'], "2" => $_lang ['task_vote_choose'], "3" => $_lang ['task_choose_work'], "7" => $_lang ['freeze'], "8" => $_lang ['task_over'], "9" => $_lang ['fail'], "10" => $_lang ['task_audit_fail']);
	}
	
	/**
	 *
	 * @return 返回单人悬赏稿件状态
	 */
	public static function get_work_status() {
		global $_lang;
		return array ("0" => $_lang ['default'], '6' => $_lang ['hg'], '7' => $_lang ['not_recept'], '8' => $_lang ['task_can_not_choose_bid'] );
	}
	public function dispose_order($order_id) {
		global $kekezu, $_K, $_lang;
		// 后台配置
		$task_config = $this->_task_config;
		$task_info = $this->_task_info; // 任务信息
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
			$res = keke_finance_class::cash_out ( $task_info ['uid'], $order_amount, 'pub_task' ); // 支付费用
			switch ($res == true) {
				case "1" : // 支付成功
					$feed_arr = array ("feed_username" => array ("content" => $task_info ['username'], "url" => "index.php?do=space&member_id={$task_info['uid']}" ), "action" => array ("content" => $_lang ['pub_task'], "url" => "" ), "event" => array ("content" => "{$task_info['task_title']}", "url" => "index.php?do=task&task_id={$task_info['task_id']}" ) );
					kekezu::save_feed ( $feed_arr, $task_info ['uid'], $task_info ['username'], 'pub_task', $task_info ['task_id'] );
					
					/**更新任务的现金金币消耗*/
					$consume = kekezu::get_cash_consume ( $task_info ['task_cash'] );
					db_factory::execute ( sprintf ( " update %switkey_task set cash_cost='%s',credit_cost='%s' where task_id='%d'", TABLEPRE, $consume ['cash'], $consume ['credit'], $this->_task_id ) );
					
					db_factory::updatetable ( TABLEPRE . "witkey_order", array ("order_status" => "ok" ), array ("order_id" => "$order_id" ) );
					if ($order_amount < $task_config ['audit_cash']) { // 如果订单的金额比发布任务时配置的审核金额要小
						$this->set_task_status ( 1 ); // 状态更改为审核状态
						return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_success_and_wait_admin_audit'], $url, 'success' );
					} else {
						$this->set_task_status ( 2 ); // 状态更改为进行状态
						return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_success_and_task_pub_success'], $url, 'success' );
					}
					break;
				case "0" : // 支付失败
					$pay_url = $_K ['siteurl'] . "/index.php?do=pay&order_id=$order_id"; // 支付跳转链接
					return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_error_and_please_repay'], $pay_url, 'warning' );
					break;
			}
		}
	}
}