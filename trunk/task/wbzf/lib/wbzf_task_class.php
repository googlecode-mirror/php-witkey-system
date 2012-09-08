<?php
/**
 * @author hr
 * @version V2.0
 * 微博转发业务类
 */
keke_lang_class::load_lang_class ( 'wbzf_task_class' );
class wbzf_task_class extends keke_task_class {
	
	public $_prom_can; // 是否可推广
	public $_requirement; // 任务基本需求格式 包含(所需稿件、中标稿件、仍需稿件、状态描述)
	

	public $_task_status_arr; // 任务状态数组
	public $_work_status_arr; // 稿件状态数组
	

	public $_weibo_id;
	public $_weibo_arr; // 对应的单条微博信息(任务)
	// 	public $_weibo_obj;
	

	public $_prom_obj;
	// public $_wbzf_obj;//微博转发obj
	// public $_wbinfo;
	protected $_inited = false;
	public static function get_instance($task_info) {
		static $obj = null;
		if ($obj == null) {
			$obj = new wbzf_task_class ( $task_info );
		}
		return $obj;
	}
	public function __construct($task_info) {
		global $kekezu;
		parent::__construct ( $task_info );
		$this->init ();
	}
	
	public function init() {
		if (! $this->_inited) {
			$this->status_init ();
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
		$sql = 'select * from ' . TABLEPRE . 'witkey_task_wbzf where task_id=' . $task_id;
		$this->_weibo_arr = db_factory::query ( $sql );
		$this->_weibo_id = $this->_weibo_arr ['0'] ['wbzf_id'];
		if ($this->_weibo_id) {
			$this->_task_info = array_merge ( $this->_weibo_arr ['0'], $this->_task_info );
		} else {
			die ( 'weibo_info_not_exists' );
		}
	}
	/**
	 * 威客权限动作判断
	 */
	public function wiki_priv_init() {
		$arr = wbzf_priv_class::get_priv ( $this->_task_id, $this->_model_id, $this->_userinfo );
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
			case "2" : // 投稿中
				$time_desc ['time_desc'] = $_lang ['from_hand_work_deadline']; // 时间状态描述
				$time_desc ['time'] = $task_info ['sub_time']; // 当前状态结束时间
				$time_desc ['ext_desc'] = $_lang ['task_working_can_hand_work']; // 追加描述
				if ($this->_task_config ['open_select'] == 'open') { // 开启进行选稿
					$time_desc ['g_action'] = $_lang ['now_employer_can_choose_work']; // 雇主追加描述
				}
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
	 * 获取任务稿件信息 支持分页，用户前端稿件列表
	 * @param $w array
	 * 前端查询条件数组
	 * ['work_status'=>稿件状态
	 * 'user_type'=>用户类型 --有值表示自己
	 * @param $p array
	 * 前端传递的分页初始信息数组
	 * ['page'=>当前页面
	 * 'page_size'=>页面条数
	 * 'url'=>分页链接
	 * 'anchor'=>分页锚点]
	 * @return array work_list
	 */
	public function get_work_info($w = array(), $order = null, $p = array()) {
		global $kekezu, $_K, $_lang, $uid;
		$work_arr = array ();
		$sql = " select a.*,c.wb_type,c.fans,c.wb_url,c.wb_account,c.fgd_num,c.hyd_num,c.cbd_num,c.yxl_num,c.wb_leve,c.wb_sid,c.get_cash,c.wb_data,b.seller_credit,b.seller_good_num,b.residency,b.seller_total_num,b.seller_level from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid left join " . TABLEPRE . "witkey_task_wbzf_work c on a.work_id=c.work_id";
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
	 * @param string $call_back
	 * @param string $oauth_url
	 * @return array
	 */
	public function get_weibo_info($platform, $call_back, $oauth_url) {
		global $code, $_lang, $_K;
		$weibo_info_arr = array ();
		$weibo_info_arr ['reqiure'] = '';
		$weibo_login_class = new keke_oauth_login_class ( $platform );
		if ($platform && ! $_SESSION ['auth_' . $platform] ['last_key']) {
			$weibo_login_class->login ( $call_back, $oauth_url );
		} else {
			$weibo_info_arr ['user_info'] = $weibo_login_class->get_login_user_info (); // 用户的微博信息
		}
		$weibo_class = new keke_weibo_class ( $platform );
		if ($this->_task_info ['is_repost'] == 1) { // 转发微博,判断微博是否存在
			$weibo_info_arr ['tips'] = $_lang ['at'];
			$repost = unserialize ( $this->_task_info ['repost_url'] );
			$repost_url = $repost [$platform]; // 要转发的url
			$repost_id = $this->get_sid_by_url ( $repost [$platform], $platform, $weibo_class ); //$repost [$platform];
			if (( int ) $repost_id < 1 || ! $repost_info = $weibo_class->get_weibo_by_sid ( $repost_id )) { // 判断对应的微博是否存在
				/* 	$notice_url = "<a href=\"" . $_K ['siteurl'] . "?do=task&task_id=" . $this->_task_id . "\">" . $this->_task_title . "</a>";
				$g_notice = array ($_lang['user'] => $this->_username, $_lang['call'] => $_lang['you'], $_lang['task_title'] => $notice_url );
				$this->notify_user ( 'task_hand', $_lang['zd_wb_not_exists'], $g_notice ); */
				kekezu::show_msg ( $_lang ['id_is_kh'] . $repost_id . ']' . $_lang ['de_wb_not_exists'], $_K ['siteurl'] . "?do=task&task_id=" . $this->_task_id );
			}
			if ($repost_info ['user'] ['id'] == $weibo_info_arr ['user_info'] ['account']) { //判断是否是同一个微博账号
				unset ( $_SESSION ['auth_' . $platform] );
				kekezu::show_msg ( '不能操作自己的微博', $_K ['siteurl'] . "?do=task&task_id=" . $this->_task_id );
			}
			$weibo_info_arr ['repost_user'] = $repost_info ['user']; // 这个信息跟关注有关(腾讯),放在页面的隐藏域中
			$weibo_info_arr ['content'] = $repost_info ['text']; // 要转发的微博信息
			$weibo_info_arr ['content_img'] = $repost_info ['original_pic']; // 图片的显示有问题//有些gif图片用$repost_info['retweeted_status']['thumbnail_pic']
			$weibo_info_arr ['reqiure'] = $_lang ['zf_zd_wb_dh'];
			// 只有转发情况下,才有关注和评论
			if ($this->_task_info ['is_focus'] == 1) {
				$weibo_info_arr ['reqiure'] .= $_lang ['focus_dh'];
			}
			// 是否评论
			if ($this->_task_info ['is_comment'] == 1) {
				$weibo_info_arr ['reqiure'] .= $_lang ['comment_dh'];
			}
		
		} else if ($this->_task_info ['is_repost'] == 2) { // 新发微博
			$weibo_info_arr ['tips'] = $_lang ['public'];
			$weibo_info_arr ['content'] = $this->_task_info ['wb_content'];
			$weibo_info_arr ['content_img'] = $this->_task_info ['wb_img'];
			$weibo_info_arr ['reqiure'] = $_lang ['pub_zd_wb_'];
		}
		// 是否@好友
		if ($this->_task_info ['is_at'] == 1) {
			$weibo_info_arr ['user_fans'] = $weibo_class->get_followers_by_uid ( $weibo_info_arr ['user_info'] ['account'] ); // 获取用户的粉丝列表
			$weibo_info_arr ['reqiure'] .= '@' . $this->_task_info ['at_num'] . $_lang ['ge_friend_'];
		}
		$weibo_info_arr ['reqiure'] = rtrim ( $weibo_info_arr ['reqiure'], ',' );
		return $weibo_info_arr;
	}
	public function work_hand($work_desc, $hdn_att_file, $hidework = '2', $url = '', $output = 'normal') {
	
	}
	/**
	 * 任务交稿_step2
	 * @param $content 转发/发布内容+@somebody       	
	 * @param $comment 评论(转发的时候才有)  
	 * @param $platform 用户当前所选择的平台
	 * @param $url 要跳转的url
	 * @param $username 转发微博的时候要转发的微博博主的username(放在页面的隐藏域中)
	 * @param $repost_content 转发的时候原文
	 * @param $plat_count 对应的task的要投放的平台数
	 */
	public function weibo_work_hand($content, $comment = '', $platform = '', $url = '', $username = '', $repost_content = '', $plat_count = '1') {
		global $_K, $_lang;
		kekezu::check_login ();
		$this->check_if_can_hand (); // 是否可以交稿
		$jump_url = "?do=task&task_id={$this->_task_info['task_id']}&op=work_hand&platform={$platform}&step=2";
		if (! $this->check_work_times ( $plat_count, $platform )) {
			kekezu::show_msg ( $_lang ['operate_notice'], $url, 2, $_lang ['you_haved_hand_not_repeat'], 'warning' );
		}
		// 判断@人数是否符合要求
		if ($this->_task_info ['is_at'] == 1) { // @sb.(somebody)
			if ($content == '') {
				kekezu::show_msg ( $_lang ['operate_notice'], $jump_url, 2, $_lang ['pity_hand_work_fail_and_you_at_your_friends'], 'warning' );
			}
			preg_match_all ( "/(\/?\@)/", $content, $matches );
			$i = 0;
			foreach ( $matches ['1'] as $k => $v ) {
				$v == '@' and $i += 1;
			}
			if ($i < $this->_task_info ['at_num']) { // @的人数不符合要求
				kekezu::show_msg ( $_lang ['operate_notice'], $jump_url, 2, $_lang ['pity_hand_work_fail_and_you_at_your_friends'] . $this->_task_info ['at_num'] . $_lang ['ge'], 'warning' );
			}
		}
		$weibo_obj = new keke_weibo_class ( $platform );
		$repost_address = unserialize ( $this->_task_info ['repost_url'] );
		$sid = $this->get_sid_by_url ( $repost_address [$platform], $platform, $weibo_obj ); //$repost_address [$platform];
		

		if ($this->_task_info ['is_repost'] == 1) { // 转发微博,判断微博是否存在
			if ($this->_task_info ['is_comment'] == 1) {
				$comment == '' && kekezu::show_msg ( $_lang ['operate_notice'], $jump_url, 2, $_lang ['pity_and_hand_wok_fail_do_comment'], 'warning' );
				$weibo_obj->comment_wb_by_wid ( $sid, $comment ); //评论原文
			}
			$result = $weibo_obj->repost_wb ( $sid, $content );
			$weibo_sid = $result ['id'];
			$platform == 'sina' && $weibo_sid = $result ['mid'];
			$weibo_username = $username; // $result['retweeted_status']['user']['id'];
			if ($this->_task_info ['is_focus'] == 1) {
				$weibo_obj->focus_by_uid ( $weibo_username ); // 关注
			}
		} else if ($this->_task_info ['is_repost'] == 2) { // 发布新的微博
			$all_content = $this->_task_info ['wb_content'] . $content;
			if ($this->_task_info ['wb_img']) {
				$img_address = $_K ['siteurl'] . DIRECTORY_SEPARATOR . $this->_task_info ['wb_img'];
			}
			$weibo_sid = $weibo_obj->post_wb ( $all_content, $img_address );
			if (! $weibo_sid) {
				kekezu::show_msg ( $_lang ['operate_notice'], $jump_url, 2, $_lang ['wb_pub_error_repeat_pub_again'], 'warning' );
			}
		}
		$content_fild = $platform == 'sina' ? 'wb_content' : 'ten_content';
		//由于 'wb_content'/'ten_content'字段是存在task_wbzf表(父表),不可能每交一次稿就修改一次,所以,先要判断一下子
		if (! $this->_task_info [$content_fild]) { //就是说只在第一次交稿的时候判断是否为空(转发的时候有效)
			db_factory::execute ( sprintf ( "update %switkey_task_wbzf set %s='%s' where task_id=%s", TABLEPRE, $content_fild, $repost_content, $this->_task_info ['task_id'] ) ); //将要转发的信息存在这个字段里面
		}
		
		// 添加数据库记录
		$work_obj = new Keke_witkey_task_work_class ();
		$work_wbzf_obj = new Keke_witkey_task_wbzf_work_class ();
		$work_obj->_work_id = null;
		$work_obj->setTask_id ( $this->_task_id );
		$work_obj->setUid ( $this->_uid );
		$work_obj->setUsername ( $this->_username );
		$work_obj->setVote_num ( 0 );
		$work_obj->setWork_status ( 0 );
		$work_obj->setWork_title ( $this->_task_title . $_lang ['de_work'] );
		$work_obj->setWork_desc ( $content );
		$work_obj->setWork_time ( time () );
		$work_id = $work_obj->create_keke_witkey_task_work ();
		
		$weibo_login_obj = new keke_oauth_login_class ( $platform );
		$user_info_arr = $weibo_login_obj->get_login_user_info (); //获取用户信息
		$work_wbzf_obj->setTask_id ( $this->_task_id );
		$work_wbzf_obj->setWork_id ( $work_id );
		$work_wbzf_obj->setWb_type ( $platform );
		$work_wbzf_obj->setFans ( $user_info_arr ['fans_count'] );
		$work_wbzf_obj->setHf_num ( $user_info_arr ['hf_count'] );
		$work_wbzf_obj->setFocus_num ( $user_info_arr ['gz_count'] );
		$work_wbzf_obj->setWb_num ( $user_info_arr ['wb_count'] );
		$work_wbzf_obj->setFaver_num ( $user_info_arr ['faver_count'] );
		$exist_days = floor ( time () - $user_info_arr ['create_at'] ) / (60 * 60 * 24); //账号创建的天数计算
		$work_wbzf_obj->setCreate_day ( $exist_days ); //离交稿时,账号创建的天数
		$params = array ();
		$affect = self::get_affect ( $user_info_arr, $platform, $params, $exist_days );
		$work_wbzf_obj->setFgd_num ( ( int ) $params ['0'] );
		$work_wbzf_obj->setHyd_num ( ( int ) $params ['1'] );
		$work_wbzf_obj->setCbd_num ( ( int ) $params ['2'] );
		$work_wbzf_obj->setYxl_num ( ( int ) $affect );
		$work_wbzf_obj->setWb_leve ( self::get_affect_level ( $affect, $this->_task_config [$platform . '_affect_rule'] ) );
		$weibo_url = keke_weibo_class::build_wb_url ( $platform, $user_info_arr ['account'], $result ['mid'] );
		$work_wbzf_obj->setWb_url ( $weibo_url );
		$work_wbzf_obj->setWb_account ( $user_info_arr ['account'] );
		$work_wbzf_obj->setWb_sid ( trim ( $weibo_sid ) );
		$weibo_data ['name'] = $user_info_arr ['name'];
		$weibo_data ['img'] = $user_info_arr ['img'];
		$weibo_data ['gz_count'] = $user_info_arr ['gz_count']; //关注数
		$weibo_data ['wb_count'] = $user_info_arr ['wb_count']; //微博数量
		$weibo_data ['url'] = $user_info_arr ['url']; //微博地址连接,当前无用
		$weibo_data ['repost_note'] = $content; //转发的时候的信息 --只有转发的时候有吧
		$weibo_data = serialize ( $weibo_data );
		$work_wbzf_obj->setWb_data ( $weibo_data );
		$work_wbzf_id = $work_wbzf_obj->create_keke_witkey_task_wbzf_work ();
		if ($work_id && $work_wbzf_id) {
			$this->plus_work_num (); // 稿件数量增加
			$this->work_choose ( $work_id, '6' );
			kekezu::show_msg ( $_lang ['operate_notice'], $url, 2, $_lang ['congratulate_you_hand_work_success'], 'success' ); // $url,
		} else {
			kekezu::show_msg ( $_lang ['operate_notice'], $url, 2, $_lang ['pity_hand_work_fail'], 'warning' );
		}
	}
	
	/**
	 * 获得影响力,ref array(覆盖度,活跃度,传播度)
	 * @access static
	 * @param array $user_info 用户的信息(此信息为微博api用户信息,而非彼信息也)
	 * @param string $platform 平台
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
		if (! isset ( $affect ) || ! $config) {
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
	 * 检查交稿次数
	 * @param $platform 平台
	 * @param $platform_count 平台数
	 */
	public function check_work_times($platform_count, $platform = '') {
		$sql = "select count(*) as total from %switkey_task_work where task_id=%s and uid=%s";
		$total = db_factory::get_count ( sprintf ( $sql, TABLEPRE, $this->_task_id, $this->_uid ) );
		if ($total >= $platform_count) {
			return false;
		}
		if ($platform) { //检测当前要交稿的平台是否已经交过一次稿件
			$plat_sql = "select count(*) from %switkey_task_work where uid=%s and work_id in(select work_id from %switkey_task_wbzf_work where task_id=%s and wb_type='%s')";
			$count = db_factory::get_count ( sprintf ( $plat_sql, TABLEPRE, $this->_uid, TABLEPRE, $this->_task_id, $platform ) );
			if (intval ( $count ) > intval ( 0 )) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 根据$url,提取出url中的mid,从而获得sid
	 * @param unknown_type $url
	 * @param unknown_type $platform
	 */
	private function get_sid_by_url($url, $platform, $obj) {
		$last_pos = strripos ( $url, '/' );
		$sid = substr ( $url, $last_pos + 1 );
		if ($platform == 'sina') {
			$sid = $obj->query_sid ( $sid );
		}
		return $sid;
	
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
		$status_arr = $this->get_work_status ();
		$wbzf_work_sql = 'select a.*,b.* from ' . TABLEPRE . 'witkey_task_work a left join ' . TABLEPRE . 'witkey_task_wbzf_work b on a.work_id=b.work_id where a.work_id=' . $work_id;
		$work_info = db_factory::get_one ( $wbzf_work_sql );
		if (! $this->set_work_status ( $work_id, $to_status )) {
			return false;
		}
		$url = '<a href ="' . $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>';
		if ($to_status == 6) { // 合格结算并且提示用户
			$single_cash = $this->according_affect_get_cash ( $work_info ['wb_leve'] ); // 手续费之前
			$syje = floatval ( $this->_task_info ['task_cash'] - $this->_task_info ['pay_amount'] ); // 如果最后所剩下的金额不够付款,那么有多少给多少
			if ($syje <= $single_cash) {
				$single_cash = $syje;
			}
			$profit_cash = $single_cash * floatval ( $this->_task_info ['profit_rate'] / 100 ); // 手续费
			$real_cash = $single_cash - $profit_cash; // 手续费之后
			$data = array (':task_id' => $this->_task_id, ':task_title' => $this->_task_title, ':work_title' => $work_info ['work_title'] );
			keke_finance_class::init_mem ( 'task_bid', $data );
			keke_finance_class::cash_in ( $work_info ['uid'], $real_cash, 0, 'task_bid', '', 'task', $this->_task_id, $profit_cash );
			
			keke_user_mark_class::create_mark_log ( $this->_model_code, '1', $work_info ['uid'], $this->_guid, $work_info ['work_id'], $this->_task_info ['single_cash'], $this->_task_id, $work_info ['username'], $this->_gusername );
			keke_user_mark_class::create_mark_log ( $this->_model_code, '2', $this->_guid, $work_info ['uid'], $work_info ['work_id'], $real_cash, $this->_task_id, $this->_gusername, $work_info ['username'] );
			
			db_factory::execute ( 'update ' . TABLEPRE . 'witkey_task_wbzf set pay_amount=pay_amount+' . floatval ( $single_cash ) . ' where task_id=' . $this->_task_id ); // 更改已支付赏金
			db_factory::execute ( 'update ' . TABLEPRE . 'witkey_task_wbzf_work set get_cash="' . $real_cash . '" where work_id=' . $work_id ); // 更改中标金额
			db_factory::execute ( 'update ' . TABLEPRE . 'witkey_task_work set work_price="' . $real_cash . '" where work_id=' . $work_id );
			
			// 检测是否已经达到稿件数量
			$pay_amount = floatval ( $this->_task_info ['pay_amount'] ) + $single_cash;
			if ($pay_amount >= $this->_task_info ['task_cash']) {
				if ($this->set_task_status ( 8 )) {
					$v_arr = array ($_lang ['username'] => $this->_gusername, $_lang ['model_name'] => $this->_model_name, $_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $this->_task_title );
					keke_msg_class::notify_user ( $this->_guid, $this->_gusername, 'task_over', $_lang ['task_over_notice'], $v_arr );
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
				}
			}
			// 存数时间动态
			$feed_arr = array ("feed_username" => array ("content" => $work_info [username], "url" => "index.php?do=space&member_id=$work_info[uid]" ), "action" => array ("content" => $_lang ['success_bid_haved'], "url" => "" ), "event" => array ("cash" => $real_cash, "content" => "$this->_task_title", "url" => "index.php?do=task&task_id=$this->_task_id" ) );
			kekezu::save_feed ( $feed_arr, $work_info ['uid'], $work_info ['username'], 'work_accept', $this->_task_id );
			$this->plus_accepted_num ( $work_info ['uid'] );
			
			$v = array ($_lang ['username'] => $work_info ['username'], $_lang ['model_name'] => $this->_model_name, $_lang ['website_name'] => $kekezu->_sys_config ['website_name'], $_lang ['task_id'] => "#" . $this->_task_id, $_lang ['task_title'] => $url, $_lang ['bid_cash'] => $single_cash );
			$this->notify_user ( "task_bid", $_lang ['work_bid'], $v, '1', $work_info ['uid'] );
			
			return true;
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
						if ($config ['open_select'] == 'open') {
							$process_arr ['work_choose'] = true; // 开启投稿中选稿
						}
						$process_arr ['work_comment'] = true; // 稿件回复
						break;
					case "0" : // 威客
						$process_arr ['work_hand'] = true; // 提交稿件
						// $process_arr
						// ['task_comment'] = true; //任务回复
						$process_arr ['task_report'] = true; // 任务举报
						break;
				}
				$process_arr ['work_report'] = true; // 稿件举报
				break;
			case "8" : // 已结束
				switch ($g_uid == $uid) { // 雇主
					case "1" :
						$process_arr ['work_mark'] = true; // 稿件评价
						break;
					case "0" :
						$process_arr ['task_comment'] = true; // 任务回复
						$process_arr ['task_mark'] = true; // 任务评价
						break;
				}
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
	 * 任务失败返还结算
	 */
	public function dispose_task_return() {
		global $kekezu;
		$config = $this->_task_config;
		$task_info = $this->_task_info;
		$task_cash = $task_info ['task_cash']; // 任务总金额
		$fail_rate = $this->_fail_rate; // 失败返金抽成比
		$pay_amount = $this->_weibo_arr ['0'] ['pay_amount'] ? $this->_weibo_arr ['0'] ['pay_amount'] : '0'; // 已经支付的金额
		$remain_cash = $task_cash - $pay_amount; // 剩余金额
		$site_profit = $remain_cash * $fail_rate / 100; // 网站利润
		$back_cash = $remain_cash - $site_profit; // 除去利润后的真正应该返回给雇主的金额
		switch ($this->_task_config ['defeated']) { // 任务失败的处理方式
			case '2' : // 返款方式 金币
				$return_cash = '0';
				$return_credit = $back_cash; // 返还佣金
				break;
			case '1' : // 现金(有花费现金优先将花费的现金返还,剩余部分返还金币)
				$cash_cost = $task_info ['cash_cost']; // (发布任务时的)现金花费
				$credit_cost = $task_info ['credit_cost']; // (发布任务时的)金币花费
				if ($task_cash == $cash_cost) { // 用现金支付的
					$return_cash = $back_cash; // 那么就退现金
					$return_credit = '0';
				} else if ($task_cash == $credit_cost) {
					$return_cash = '0';
					$return_credit = $back_cash; // 退元宝
				} else {
					$return_cash = $cash_cost * (1 - $fail_rate / 100);
					$return_credit = $credit_cost * (1 - $fail_rate / 100);
				}
				break;
		}
		if ($this->set_task_status ( 9 )) {
			$data = array (':model_name' => '微博转发', ':task_id' => $task_info ['task_id'], ':task_title' => $task_info ['task_title'] );
			keke_finance_class::init_mem ( 'task_fail', $data );
			keke_finance_class::cash_in ( $this->_guid, $return_cash, floatval ( $return_credit ) + 0, 'task_fail', '', 'task', $this->_task_id, $site_profit );
			/**
			 * 通知联盟
			 */
			if ($this->_task_info ['task_union'] == 2) {
				$u = new keke_union_class ( $this->_task_id );
				$u->task_close ( array ('r_task_id' => $u->_r_task_id, 'indetify' => - 1 ) );
			}
		}
	}
	/**
	 * 时间触发投稿到期处理(状态2:投稿中)
	 * 任务失败
	 */
	public function time_hand_end() {
		if ($this->_task_status == 2 && $this->_task_info ['sub_time'] < time ()) // 任务投稿时间到
			$this->dispose_task_return (); // 否则任务失败,退还赏金
	}
	
	/**
	 * 任务自动选稿剩余金额返还
	 * 
	 * @param $remain_cash float
	 * 返还金额
	 */
	public function dispose_auto_return($remain_cash) {
		global $kekezu;
		$config = $this->_task_config;
		$task_info = $this->_task_info;
		$fail_rate = $this->_fail_rate; // 失败返金抽成比
		$site_profit = $remain_cash * $fail_rate / 100; // 网站利润
		$real_cash = $remain_cash - $site_profit;
		switch ($config ['defeated']) {
			case "2" : // 返款方式 金币
				$return_cash = '0';
				$return_credit = $real_cash; // 返还佣金
				break;
			case "1" : // 现金
				$return_credit = '0';
				$return_cash = $real_cash; // 返还佣金
				break;
		}
		$data = array (':model_name' => '微博转发', ':task_id' => $task_info ['task_id'], ':task_title' => $task_info ['task_title'] );
		keke_finance_class::init_mem ( 'task_fail', $data );
		$result = keke_finance_class::cash_in ( $this->_guid, $return_cash, floatval ( $return_credit ) + 0, 'task_auto_return', '', 'task', $this->_task_id, $site_profit );
		if ($result) {
			return $real_cash;
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
		return array ("0" => $_lang ['task_no_pay'], "1" => $_lang ['task_wait_audit'], "2" => $_lang ['task_vote_choose'], /* "3" => $_lang['task_choose_work'], */ "7" => $_lang ['freeze'], "8" => $_lang ['task_over'], "9" => $_lang ['fail'], "10" => $_lang ['task_audit_fail']);
	}
	
	/**
	 *
	 * @return 返回单人悬赏稿件状态
	 */
	public static function get_work_status() {
		global $_lang;
		return array ("0" => $_lang ['default'], '6' => $_lang ['hg'], '7' => $_lang ['not_recept'], '8' => $_lang ['task_can_not_choose_bid'] );
	}
	
	/**
	 * @return 返回任务英文状态
	 */
	public static function get_task_union_status() {
		return array ('0' => "wait", '1' => "audit", '2' => "sub", /* '3' => "choose", */ '4' => "vote", '5' => "notice", '6' => 'deliver', '7' => "freeze", '8' => "end", '9' => "failure", '10' => "audit_fail", '11' => "arbitrate" );
	}
	
	public function dispose_order($order_id) {
		global $kekezu, $_K, $_lang;
		;
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