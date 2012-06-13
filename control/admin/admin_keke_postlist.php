<?php
/**
 * @author hr
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role(136);

include S_ROOT.'/keke_client/keke/config.php';
$pagesize = isset($page_size) ? intval($page_size) : '10' ;
$page = max(intval($page),1);
$url = 'index.php?do=keke&view=postlist';
$where = '`task_union`=1 and `r_task_id` is not null order by `task_id` desc';
$table_obj = new keke_table_class( 'witkey_task');

$ad_arr = $table_obj -> get_grid($where, $url, $page, $pagesize, null, 1, 'ajax_dom'); 
$pages = $ad_arr['pages'];
$task_arr = $ad_arr['data'];

$task_status = keke_global_class::get_taskstatus_desc();
$indus_arr = Keke::$_indus_arr;




//任务时间描述
function task_time_desc($model_id, $status, $end_time) {
	global $end_time_arr;
	//var_dump($end_time_arr[$model_id][$status]['time']);
	$now_time = time ();
	$desc_time = $end_time - $now_time;
	$sy_time = Keke::time2Units ( $desc_time );
	if (! $end_time) {
		return $end_time_arr [$model_id] [$status] ['desc'];
	}
	if ($sy_time) {
		return $sy_time . '后' . $end_time_arr [$model_id] [$status] ['desc'];
	}
}
require $template_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );