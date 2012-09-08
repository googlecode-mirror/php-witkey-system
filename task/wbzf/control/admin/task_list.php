<?php
/**
 * 后台转发微博列表
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
//任务配置
$task_config = unserialize ( $model_info ['config'] );
$model_list = $kekezu->_model_list;

$table_obj = keke_table_class::get_instance('witkey_task');

//任务状态
$task_status = wbzf_task_class::get_task_status ();

$page and $page=intval ( $page ) or $page = 1;
$page_size and $page_size = intval($page_size) or $page_size=10;


$wh = " a.model_id=8";

if ($w['task_id']) {
	$wh .= " and a.task_id = " . intval($w ['task_id']);

}


if ($w ['wb_platform']) {
	switch ($w ['wb_platform']) {
		case $_lang['sina']:
			$wb_platform = 'sina';
		break;
		case $_lang['ten']:
			$wb_platform = 'ten';
		break;

	}
	$wh .= " and b.wb_platform like '%$wb_platform%'";
	
}


if ($w ['task_status']) {

	$wh .= " and a.task_status = " .$w ['task_status'];
	
}

$w ['task_status']==='0' and $wh .= " and task_status = 0" ;
if ($sbt_search) {
	$wh .= " order by a.$ord[0] $ord[1]";
}else{
	$wh .= " order by a.task_id desc ";
}

$url_str = "index.php?do=model&model_id=8&view=list&w[task_id]={$w['task_id']}&w[wb_platform]={$w['wb_platform']}&ord[0]=$ord[0]&ord[1]=$ord[1]&page=$page&page_size=$page_size";

$sql_count = "select count(a.task_id) from ".TABLEPRE."witkey_task_wbzf b left join ".TABLEPRE."witkey_task a on a.task_id = b.task_id where ".$wh;

$count = db_factory::get_count($sql_count);


$pages = $page_obj->getPages($count, $page_size, $page, $url_str);
$wh .= $pages['where'];

$sql = "select a.*,b.* from ".TABLEPRE."witkey_task_wbzf b left join ".TABLEPRE."witkey_task a on a.task_id = b.task_id where".$wh;

$task_arr = db_factory::query($sql);



if($task_id){ 
	$task_audit_arr = get_task_info($task_id);
	$start_time = date("Y-m-d H:i:s",$task_audit_arr['start_time']);
	$end_time = date("Y-m-d H:i:s",$task_audit_arr['end_time']);
	$url = "<a href =\"{$_K['siteurl']}/index.php?do=task&task_id={$task_audit_arr['task_id']}\" target=\"_blank\" >" . $task_audit_arr['task_title']. "</a>";

}

switch ($ac) {
	case "del" : //删除
		$res = keke_task_config::task_del($task_id);
		$res and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['delete_success'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['delete_fail'],"warning");
		break;
	case "pass" : //通过审核
		$res =keke_task_config::task_audit_pass ( $task_id );
		$res and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['examine_successfully'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['nopass'],"warning");
		break;
	case "nopass" : //不通过审核
		$res =keke_task_config::task_audit_nopass ( $task_id );	
		$res and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['operate_success'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['operate_fail'],"warning");
		break;
	case "freeze" : //冻结任务
		$res =keke_task_config::task_freeze ( $task_id );
		$res and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_frooze_successfully'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_frooze_fail'],"warning");
		break;
	case "unfreeze" : //任务解冻
		$res =keke_task_config::task_unfreeze ( $task_id );
		$res and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_unfrooze_successfully'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_unfrooze_fail'],"warning");
		break;
	case "recommend"://任务推荐
		$res = keke_task_config::task_recommend($task_id);
		$res and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_recommend_successfully'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_recommend_fail'],"warning");
		break;
	case "unrecommend"://取消任务推荐
		$res = keke_task_config::task_unrecommend($task_id);
		$res and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_unrecommend_successfully'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_unrecommend_fail'],"warning");
		break;
}


//批量删除
if ($sbt_action==$_lang['mulit_delete']&&!empty($ckb)) {
	keke_task_config::task_del($ckb) and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_delete_success'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_delete_fail'],"warning");
}
//批量审核
if ($sbt_action==$_lang['mulit_pass']&&!empty($ckb)) {
	keke_task_config::task_audit_pass($ckb) and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_pass_success'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_pass_fail'],"warning");
}
//批量冻结
if ($sbt_action==$_lang['mulit_freeze']&&!empty($ckb)) {
	keke_task_config::task_freeze($ckb) and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_freeze_success'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_freeze_fail'],"warning");
}
//批量解冻
if ($sbt_action==$_lang['mulit_unfreeze']&&!empty($ckb)) {
	keke_task_config::task_unfreeze($ckb) and kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_unfreeze_success'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_unfreeze_fail'],"warning");
}
function get_task_info($task_id){
	$task_obj = new Keke_witkey_task_class();
	$task_obj->setWhere("task_id = $task_id");
	$task_info = $task_obj->query_keke_witkey_task();
	$task_info = $task_info ['0'];
	return $task_info;

}
require $kekezu->_tpl_obj->template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_' . $view );