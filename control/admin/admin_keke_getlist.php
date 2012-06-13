<?php
/**
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * 2012-2-17下午
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role(136);
include S_ROOT.'/keke_client/keke/config.php';
// var_dump($_SERVER);

if (isset($sbt_action) || ($ac=='del' && $del_id)){
	if (!$ckb && !$del_id){
		Keke::admin_show_msg('提示','?do=keke&view=gettask',2,'任务删除失败,参数缺失','warning');
	}
	$ckb && array_filter( $ckb, 'str2int');
	$ids = isset($del_id) ? intval($del_id) : implode(',', $ckb);
	$sql = sprintf("delete from %switkey_task where task_union=2 and task_id in (%s)",TABLEPRE,$ids);
	$result = dbfactory::execute($sql);
	Keke::admin_system_log('批量删除联盟task'.$ids);
	if ($result){
		Keke::admin_show_msg('提示','?do=keke&view=getlist',2,'任务删除成功!!','success');
	} else {
		Keke::admin_show_msg('提示','?do=keke&view=getlist',2,'任务删除失败!','warning');
	}
	die();
}

$cove_arr = Keke::get_cash_cove();
$pagesize = isset($page_size) ? intval($page_size) : '10' ;
$page = max(intval($page),1);
$url = 'index.php?do=keke&view=getlist&page='.$page;
$where = '1=1 and task_union=2 order by `task_id` desc';
$table_obj = new keke_table_class( 'witkey_task');

$ad_arr = $table_obj -> get_grid($where, $url, $page, $pagesize, null, 1, 'ajax_dom'); //var_dump($ad_arr);
$pages = $ad_arr['pages'];
$task_arr = $ad_arr['data'];
require $template_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );
function str2int($value){
	return intval($value);
}
