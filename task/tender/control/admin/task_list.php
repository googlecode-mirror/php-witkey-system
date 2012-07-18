<?php
/**
 * 后台单人悬赏任务管理列表
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
//任务配置
$task_config = unserialize ( $model_info ['config'] );

$model_list = Keke::$_model_list;
//任务状态
$task_status = tender_task_class::get_task_status ();

$table_obj = keke_table_class::get_instance ( 'witkey_task' );

$page and $page = intval ( $page ) or $page=1;
$page_size and $page_size=intval($page_size) or $page_size = 10;

$wh = "model_id={$model_info['model_id']}";

if ($w ['task_id']) {
	$wh .= " and task_id  like '%$w[task_id]%'";
}
if ($w ['task_title']) {
	$wh .= " and task_title like '%$w[task_title]%' ";
}
if ($w ['task_status']) {
	$wh .= " and task_status = " . $w[task_status];
}
$w ['task_status']==='0' and $wh .= " and task_status = 0" ;
is_array($w['ord']) and $wh .= ' order by '.$w['ord']['0'].' '.$w['ord']['1'] or $wh.=" order by task_id desc ";


$url_str = "index.php?do=model&model_id=4&view=list&w['task_id']={$w['task_id']}&w['task_title']={$w['task_title']}&w['task_status']={$w['task_status']}&w['ord']={$w['ord']}&page=$page&page_size=$page_size";


//查询
$table_arr = $table_obj->get_grid ( $wh, $url_str, $page, $page_size, null, 1, 'ajax_dom');
$task_arr = $table_arr ['data'];
$pages = $table_arr ['pages'];
if($task_id){ 
	$task_audit_arr = get_task_info($task_id);
	$start_time = date("Y-m-d H:i:s",$task_audit_arr['start_time']);
	$end_time = date("Y-m-d H:i:s",$task_audit_arr['end_time']);
	$url = "<a href =\"$_K[siteurl]/index.php?do=task&task_id=$task_audit_arr[task_id]\" target=\"_blank\" >" . $task_audit_arr['task_title']. "</a>";

}
switch ($ac) {
	case "del" : //删除
		$task_title = dbfactory::get_count(sprintf("select task_title from %switkey_task where task_id='%d' ",TABLEPRE,$task_id));
		Keke::admin_system_log(Keke::lang(delete_task)."：{$task_title}(".$_lang['normal_tender'].")");
		$res = $table_obj->del ( 'task_id', $task_id, $url_str );
		$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['delete_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['delete_fail'],"warning");
		break;
	case "pass" : //通过审核
		$res =keke_task_config::task_audit_pass ( $task_id );
		$v_arr = array($_lang['username']=>$task_audit_arr[username],$_lang['task_link']=>$url,$_lang[start_time]=>$start_time,$_lang['end_time']=>$end_time,$_lang['task_id']=>"#".$task_id); 
		keke_shop_class::notify_user($task_audit_arr['uid'], $task_audit_arr['username'], 'task_auth_success', $task_audit_arr['task_title'],$v_arr);
		
		$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['audit_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['nopass'],"warning");
		break;
	case "nopass" : //审核失败
		$res =keke_task_config::task_audit_nopass ( $task_id );
		$v_arr = array($_lang['username']=>$task_audit_arr['username'],$_lang['task_title']=>$url,$_lang['website_name']=>Keke::$_sys_config['website_name']); 
		keke_shop_class::notify_user($task_audit_arr['uid'], $task_audit_arr['username'], 'task_auth_fail', $task_audit_arr['task_title'],$v_arr);
			
		$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['operate_notice'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['operate_fail'],"warning");
		break;
	case "freeze" : //冻结任务
		$res =keke_task_config::task_freeze ( $task_id );
		$v_arr = array($_lang['username']=>"$task_audit_arr[username]",$_lang['task_title']=>$url); 
		keke_shop_class::notify_user($task_audit_arr['uid'], $task_audit_arr['username'], 'task_freeze', $task_audit_arr['task_title'],$v_arr); 
		$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['freeze_task_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['freeze_task_fail'],"warning");
		break;
		
	case "unfreeze" : //任务解冻
		$res =keke_task_config::task_unfreeze ( $task_id );
		$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['unfreeze_task_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['unfreeze_task_fail'],"warning");
		break;
	case "recommend"://任务推荐
		$res =keke_task_config::task_recommend($task_id);
		$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_recommend_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_recommend_fail'],"warning");
		break;
	case "unrecommend"://取消任务推荐
		$res = keke_task_config::task_unrecommend($task_id);
		$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['cancel_recommend_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['cancel_recommend_fail'],"warning");
		break;
}


//批量操作
if($sbt_action){
	$keyids = $ckb;
	if(is_array($keyids)){
		switch ($sbt_action) {
			case $_lang['mulit_delete']:
				keke_task_config::task_del($keyids) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_delete_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_delete_fail'],"warning");
			break;
			case $_lang['mulit_pass']:
				keke_task_config::task_audit_pass($keyids) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_pass_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_pass_fail'],"warning");
			break;
			case $_lang['mulit_freeze']:
				keke_task_config::task_freeze($keyids) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_freeze_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_freeze_fail'],"warning");
			break;
			case $_lang['mulit_unfreeze']:
				keke_task_config::task_unfreeze($keyids) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_unfreeze_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_unfreeze_fail'],"warning");
			break;
		}
	}
}
function get_task_info($task_id){
	$task_obj = new Keke_witkey_task_class();
	$task_obj->setWhere("task_id = $task_id");
	$task_info = $task_obj->query_keke_witkey_task();
	$task_info = $task_info ['0'];
	return $task_info;

}

require Keke::$_tpl_obj->template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_' . $view );