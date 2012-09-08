<?php
/**
 * @author hr
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$nav_active_index = 'task';
$basic_url = "index.php?do=task&task_id=$task_id"; //基本链接
//语言包
//keke_lang_class::package_init("task");
// keke_lang_class::loadlang('public');
keke_lang_class::loadlang('public','task_wbzf');
// $time_obj->task_choose_end();//检测"进行中"状态下是否任务到期
$cove_arr = kekezu::get_table_data("*","witkey_task_cash_cove","","","","","cash_rule_id");
$task_obj = wbzf_task_class::get_instance ( $task_info );
$task_info = $task_obj ->_task_info;
$task_config =$task_obj->_task_config;//config
$model_id = $task_info ['model_id'];//对应的模型
$task_status = $task_obj->_task_status;//任务状态
$indus_arr = $kekezu->_indus_c_arr; //子行业集
$indus_p_arr = $kekezu->_indus_p_arr; //父行业集
$status_arr = $task_obj->_task_status_arr; //任务状态数组
$time_desc = $task_obj->get_task_timedesc (); //任务阶段描述
$stage_desc = $task_obj->get_task_stage_desc (); //任务阶段样式
$related_task = $task_obj->get_task_related ();//获取相关任务
$process_can = $task_obj->process_can (); //用户操作权限
$process_desc = $task_obj->process_desc (); //用户操作权限中文描述
$sub_task_user_level =$g_info = $task_obj->_g_userinfo;
$task_obj->plus_view_num();//查看加一

$time_obj =new wbzf_time_class();
$time_obj->task_hand_end();//触发任务是否投稿到期
$web_arr = keke_glob_class::get_oauth_type();//微博名称数组

$browing_history = $task_obj->browing_history($task_id,$task_info['task_cash'].$_lang['yuan'],$task_info['task_title']);//历史记录

$g_info = $task_obj->_g_userinfo;
$plat = explode(',', $task_info['wb_platform']);//平台
$plat_count = sizeof($plat);
$check_can_work = true;//检测是否已经交过稿件
switch ($op) {
	case "reqedit" : //需求补充
        if($task_info['ext_desc']){
		$title = $_lang['edit_supply_demand'];
		}else{
		$title =$_lang['supply_demand'];
		}
		if ($sbt_edit){
			$task_obj->set_task_reqedit ( $tar_content, '', 'json' );
			die();
		}
		$ext_desc = $task_info ['ext_desc'];
		require keke_tpl_class::template ( 'task/task_reqedit' );
		die ();
	case "work_hand" : //交稿
		if ($sbt) {
			$url = "?do=task&task_id={$task_info['task_id']}&view=work";
			$repost = kekezu::escape($repost);//转发时候说的话
			$comment = kekezu::escape($comment);
			$repost_img = kekezu::escape($repost_img);
			$repost_data = kekezu::escape($nickname).':'.kekezu::escape($repost_data);
			$repost_img && $repost_data .= '<br/><a href="'.$repost_img.'">'.$repost_img.'</a>';//转发的原文
			$task_obj->weibo_work_hand ( $repost, $comment,$platform, $url, $domain, $repost_data, $plat_count);
			die();
		}
		$title =$_lang['hand_work'];
		$step = max((int)$step, 1);
		if ($check_work=='check_work' && $uid){
			$check_can_work = $task_obj->check_work_times($plat_count);
			if ($check_can_work==false){
				echo 'false';die();
			}
		}
		if ($step==2){
			$page_title = $_lang['wbzf_work_hand'];
			(!$platform || !in_array($platform, $plat)) && $platform=$plat['0']; //验证平台
			if ($uid && !$task_obj->check_work_times($plat_count,$platform)){//是否已经交过稿
				kekezu::show_msg ( $_lang['operate_tips'], "?do=task&task_id={$task_info['task_id']}&view=work", 2, $_lang['you_has_been'].'<b>'.$web_arr[$platform]['name'].'</b>'.$_lang['no_need_rehand'].'<br/>', 'warning' );
			}
			$oauth_url = $kekezu->_sys_config['website_url']."/index.php?do=task&task_id=$task_id&platform=$platform&op=work_hand&step=2";
			if ($relog){//重新登录
				$weibo_login_class = new keke_oauth_login_class ( $platform );
				$weibo_login_class->logout();
				$weibo_login_class->login($call_back, $oauth_url);
			}
			 
			$weibo_arr = $task_obj -> get_weibo_info($platform,$call_back, $oauth_url);//交稿前的一些判断
 
			//判断对应的微博账号是否已经交过稿件
			$count = db_factory::query('select count(*) as total from '.TABLEPRE.'witkey_task_wbzf_work where wb_account="'.$weibo_arr['user_info']['account'].'" and task_id="'.$task_info['task_id'].'"');
			if ($count['0']['total']>0){
				$weibo_login_class = new keke_oauth_login_class ( $platform );
				$weibo_login_class->logout();
				kekezu::show_msg ( $_lang['operate_tips'], "?do=task&task_id={$task_info['task_id']}&view=work", 10, $_lang['you_no_need_rehand'].'<br/>', 'warning' );
			}
		}
		require keke_tpl_class::template ( "task/" . $model_info ['model_code'] . "/tpl/" . $_K ['template'] . '/wbzf_work' );
		die();
// 	case "work_choose" : //选稿
// 		$task_obj->work_choose ( $work_id, $to_status,'','json');
// 		break;
	case "report" : //举报
		$transname = keke_report_class::get_transrights_name($type);
		$title=$transname.$_lang['submit'];
		if($sbt_edit){
			$task_obj->set_report ( $obj, $obj_id, $to_uid,$to_username, $type, $file_url, $tar_content);
		}else{
			require keke_tpl_class::template("report");
		}
		die();
	case "work_del"://稿件删除
		$task_obj->del_work($work_id,'','json');
		break;
	case "comment" : //相关留言
		if ($obj_type=='work' && $tar_content) {
			$task_obj->set_work_comment ( $obj_type, $obj_id, $tar_content, $p_id, '', 'json' );
		}
		break;
}
switch ($view) {
	case "work" ://稿件浏览
		$search_condit = $task_obj->get_search_condit();
		$date_prv = date("Y-m-d",time());//用在雇主回复时的时间前缀部分
		$work_status = $task_obj->get_work_status ();//获取稿件状态数组
		$p['page'] = max((int)$page,1);
		intval ( $page_size ) and $p ['page_size'] = intval ( $page_size ) or $p['page_size']='10';
		$p['url'] = $basic_url."&view=work&page_size=".$p ['page_size']."&page=".$p ['page'];
		$p ['anchor'] = '';
		$w['work_status'] = $st;//稿件状态
		$w['user_type']   = $ut;//用户类型  my自己
		$work_arr = $task_obj->get_work_info ($w, " work_id asc ", $p ); //稿件信息
		$pages = $work_arr ['pages'];
		$work_info = $work_arr ['work_info'];
		$display_str = $task_info['is_repost']==2 ? 'style="display:none;"' : '';
		/*检测是否有新留言**/
		$has_new  = $task_obj->has_new_comment($p ['page'],$p ['page_size']);
		break;
	default ://任务描述页面
		$weibo_arr = $task_obj -> _weibo_arr;
		$weibo_arr = $weibo_arr['0'];
		$weibo_plat = explode(',', $weibo_arr['wb_platform']);
		$require_str = weibo_require($weibo_arr);//微博任务需求描述
		$fans_part_str = fans_part($weibo_arr['unit_price']);//粉丝段
        if($task_info['task_status']==8){
			$list_work = db_factory::query(' select uid,username from '.TABLEPRE.'witkey_task_work where task_id='.intval($task_id).' and work_status =6 ');
		}
		if($task_info['task_status']==2&&$task_info['uid']==$uid){
			$item_list= keke_payitem_class::get_payitem_config ( 'employer', null, null, 'item_id' );
		}
		break;
}
$font_reqiure = array('is_focus'=>$_lang['focus'].'|', 'is_comment'=>$_lang['comment'].'|', 'is_at'=>'@', 'is_repost'=>$_lang['zf_this_weibo'].',','post'=>$_lang['post_weibo'].',');

//需求
function weibo_require($weibo){
	global $_lang;
	$font_reqiure = array('is_focus'=>$_lang['focus'].'|', 'is_comment'=>$_lang['comment'].'|', 'is_at'=>'@', 'is_repost'=>$_lang['zf_this_weibo'].',','post'=>$_lang['post_weibo'].',');
	$str = '';
	$str .= $weibo['is_repost']==1 ? $font_reqiure['is_repost'] : $font_reqiure['post'];
	$weibo['is_focus']==1 && $str .= $font_reqiure['is_focus'];
	$weibo['is_comment']==1 && $str .= $font_reqiure['is_comment'];
	$weibo['is_at']==1 && $str .= $font_reqiure['is_at'].$weibo['at_num'].$_lang['ge_friends'].'|';
	return rtrim($str,',|');
}
//影响力区间
function fans_part($fans_arr){
	global $task_config,$_lang;
	$fans_rule = $task_config['affect_rule'];//价格规则
	$fans_arr = unserialize($fans_arr);//影响力规则
	$str = '';
	while (list($key,$value)=each($fans_arr)) {
		$str .= '<li><strong >' . $value .$_lang['yuan'].'</strong>' . keke_glob_class::num2ch($key) . $_lang['level_yxl'].'</li>';
	}
	return $str;
}
//换算星级
function convert_star($i){
	$star  = intval($i);
	$star=='0' or $star =strlen(intval($i));
	echo keke_user_mark_class::gen_star2($star);
}

require keke_tpl_class::template ( "task/" . $model_info ['model_code'] . "/tpl/" . $_K ['template'] . "/task_info" );