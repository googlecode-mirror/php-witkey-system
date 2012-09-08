<?php
/**
 * @author hr
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$nav_active_index = 'task';
$basic_url = "index.php?do=task&task_id=$task_id"; //基本链接
keke_lang_class::loadlang ( 'public', 'task_taobao' );
$cover_cash = kekezu::get_cash_cove('',true);
$task_obj = taobao_task_class::get_instance ( $task_info );
$task_info = $task_obj->_task_info;
$task_config = $task_obj->_task_config; //config
$model_id = $task_info ['model_id']; //对应的模型
$task_status = $task_obj->_task_status; //任务状态
$indus_arr = $kekezu->_indus_c_arr; //子行业集
$indus_p_arr = $kekezu->_indus_p_arr; //父行业集
$status_arr = $task_obj->_task_status_arr; //任务状态数组
//任务阶段描述
$time_desc = $task_obj->get_task_timedesc ();

$stage_desc = $task_obj->get_task_stage_desc (); //任务阶段样式
$related_task = $task_obj->get_task_related (); //获取相关任务
$process_can = $task_obj->process_can (); //用户操作权限
$process_desc = $task_obj->process_desc (); //用户操作权限中文描述
$assign = $task_obj->_assign; //计费模式
$task_obj->plus_view_num (); //查看加一

$g_info = $task_obj->_g_userinfo;

$web_arr = keke_glob_class::get_oauth_type (); //微博名称数组


$browing_history = $task_obj->browing_history ( $task_id, $task_info ['task_cash'] . "元", $task_info ['task_title'] ); //历史记录


$time_obj = new taobao_time_class ();
$time_obj->task_hand_end (); //触发任务是否投稿到期
$plat = explode ( ',', $task_info ['wb_platform'] ); //平台
$plat_count = sizeof ( $plat );
$check_can_work = true; //检测是否已经交过稿件
switch ($op) {
	case "reqedit" : //需求补充
        if($task_info['ext_desc']){
		$title = $_lang['edit_supply_demand'];
		}else{
		$title =$_lang['supply_demand'];
		}
		if ($sbt_edit) {
			$task_obj->set_task_reqedit ( $tar_content, '', 'json' );
			die ();
		}
		$ext_desc = $task_info ['ext_desc'];
		require keke_tpl_class::template ( 'task/task_reqedit' );
		die ();
	// 		break;
	case "work_hand" : //交稿
		if ($sbt) {
			$task_obj->work_hand ( $platform, $at );
		} else {
			$title = $_lang ['choose_platform'];
			$page_title = $_lang ['taobao_workhand'];
			$plat = explode ( ',', $task_info ['wb_platform'] ); //平台
			

			! isset ( $step ) && $step = 1;
			$step = intval ( $step );
			if ($step == 2) {
				! $platform && $platform = $plat ['0']; //选择平台
				! in_array ( $platform, $plat ) && $platform = $plat ['0']; //验证平台
				if ($uid && ! $task_obj->check_work_times ( $platform )) { //是否已经交过稿
					kekezu::show_msg ( $_lang ['operate_tips'], "?do=task&task_id={$task_info['task_id']}&view=work", 2, $_lang ['you_has_been'] . '<b>' . $web_arr [$platform] ['name'] . '</b>' . $_lang ['no_need_rehand'] . '<br/>', 'warning' );
				}
				$oauth_url = $kekezu->_sys_config ['website_url'] . "/index.php?do=task&task_id=$task_id&platform=$platform&op=work_hand&step=2";
				$weibo_arr = $task_obj->get_weibo_info ( $platform, $call_back, $oauth_url ); //交稿前的一些判断
				if ($relog) { //重新登录//鸡肋,退出是调用的end_session接口,但是官方说明此接口仅对js web应用有效
					$weibo_login_class = new keke_oauth_login_class ( $platform );
					$weibo_login_class->logout ();
					$weibo_login_class->login ( $call_back, $oauth_url );
				}
			}
			require keke_tpl_class::template ( "task/taobao/tpl/" . $kekezu->_template . "/tao_work" );
		}
		die ();
		break;
	case "report" : //举报
		$transname = keke_report_class::get_transrights_name ( $type );
		$title = $transname . $_lang ['submit'];
		if ($sbt_edit) {
			$task_obj->set_report ( $obj, $obj_id, $to_uid, $to_username, $type, $file_url, $tar_content );
		} else {
			require keke_tpl_class::template ( "report" );
		}
		die ();
		break;
	case "work_del" : //稿件删除
		$task_obj->del_work ( $work_id, '', 'json' );
		break;
	case "comment" : //相关留言
		if ($obj_type == 'work' && $tar_content) {
			$task_obj->set_work_comment ( $obj_type, $obj_id, $tar_content, $p_id, '', 'json' );
		}
		break;
	case "wb_cl" : //点击计费
		$task_obj->wb_click ( $w_id );
		break;
}
switch ($view) {
	case "work" : //稿件浏览
		$search_condit = $task_obj->get_search_condit ();
		$date_prv = date ( "Y-m-d", time () ); //用在雇主回复时的时间前缀部分
		$work_status = $task_obj->get_work_status (); //获取稿件状态数组
		intval ( $page ) and $p ['page'] = intval ( $page ) or $p ['page'] = '1';
		intval ( $page_size ) and $p ['page_size'] = intval ( $page_size ) or $p ['page_size'] = '10';
		$p ['url'] = $basic_url . "&view=work&ut=$ut&page_size=" . $p ['page_size'] . "&page=" . $p ['page'];
		$p ['anchor'] = '';
		$w ['work_status'] = $st; //稿件状态
		$w ['user_type'] = $ut; //用户类型  my自己
		$work_arr = $task_obj->get_work_info ( $w, " work_id asc ", $p ); //稿件信息
		$pages = $work_arr ['pages'];
		$work_info = $work_arr ['work_info'];
		$display_str = $task_info ['is_repost'] == 2 ? 'style="display:none;"' : '';
		/*检测是否有新留言**/
		$has_new = $task_obj->has_new_comment ( $p ['page'], $p ['page_size'] );
		break;
	default : //任务描述页面
		$weibo_plat = explode ( ',', $task_info ['wb_platform'] );
		$task_info ['assign'] == 1 && $fans_part_str = fans_part ( $task_info ['unit_price'] ); //粉丝段
		if($task_info['task_status']==8){
			$list_work = db_factory::query(' select uid,username from '.TABLEPRE.'witkey_task_work where task_id='.intval($task_id).' and work_status = 6');
		}
		if($task_info['task_status']==2&&$task_info['uid']==$uid){
			$item_list= keke_payitem_class::get_payitem_config ( 'employer', null, null, 'item_id' );
		}
		break;
}
//粉丝段
function fans_part($fans_arr) {
	global $task_config, $_lang;
	$fans_rule = $task_config ['affect_rule'];
	$fans_arr = unserialize ( $fans_arr );
	$str = '';
	while ( list ( $key, $value ) = each ( $fans_arr ) ) {
		$str .= '<li><strong>' . $value . $_lang ['yuan'] . '</strong>' . keke_glob_class::num2ch ( $key ) . $_lang ['level_yxl'] . '</li>';
	}
	return $str;
}
//换算星级
function convert_star($i) {
	$star = intval ( $i );
	$star == '0' or $star = strlen ( intval ( $i ) );
	echo keke_user_mark_class::gen_star2 ( $star );
}
function click_corve($work_id) {
	global $task_info, $task_id, $_lang;
	$day_arr = $cl_info = array ();
	$cl_tmp = db_factory::query ( sprintf ( " select count(view_id) count,click_time time,dayofyear(from_unixtime(click_time)) cl_day from %switkey_task_taobao_views where task_id='%d' and work_id='%d' group by cl_day order by cl_day desc limit 0,10", TABLEPRE, $task_id, $work_id ) );
	if ($cl_tmp) {
		$s = sizeof ( $cl_tmp );
		$end_time = $cl_tmp [0] ['time'];
		$start_time = $cl_tmp [$s - 1] ['time'];
		$day_count = min ( ceil ( ($end_time - $start_time) / (24 * 3600) ), 10 );
		$day_arr = array_reverse ( range ( 0, $day_count - 1 ) );
		$zone = date ( 'Y-m-d', $end_time - ($day_count - 1) * 24 * 3600 ) . $_lang ['zhi'] . date ( 'Y-m-d', $end_time );
	} else {
		$zone = $_lang ['was_null'];
	}
	$t_caption = '<table class="chart_line"><caption>' . $_lang ['click_chart'] . '(' . $zone . ')</caption>';
	$t_head = '<thead><tr><td></td>';
	$t_body = '</thead><tbody><tr><th scope="row">' . $_lang ['click_num'] . '</th>';
	if ($cl_tmp [0] ['time']) {
		foreach ( $cl_tmp as $v ) {
			$time = date ( 'd', $v ['time'] );
			$cl_info [$time] = $v ['count'];
		}
	}
	foreach ( $day_arr as $v ) {
		$time = date ( 'd', $end_time - $v * 24 * 3600 );
		$t_head .= '<th scope="col">' . $time . '</th>';
		$click = intval ( $cl_info [$time] );
		$t_body .= '<td>' . $click . '</td>';
	}
	$t_head .= '</tr></thead>';
	$t_body .= '</tr></tbody>';
	$t_info = $t_caption . $t_head . $t_body . '</table>';
	return $t_info;
}

require keke_tpl_class::template ( "task/" . $model_info ['model_code'] . "/tpl/" . $_K ['template'] . "/task_info" );