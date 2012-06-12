<?php
/**
 * @copyright keke-tech
 * @author Michael
 * @version v 2.0
 * 2012-02-22 12:22:22
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
kekezu::admin_check_role(137);
include S_ROOT.'/keke_client/keke/config.php';
 //任务模型数组
$task_type_arr = keke_glob_class::get_task_type();
$task_status_arr = array(2=>"交稿中",3=>"选稿中");

//查询
$sql =sprintf("select * from %switkey_task where task_union=0 and end_time>UNIX_TIMESTAMP() ",TABLEPRE);
$task_status= $task_status ? $task_status : 2;
$where .=" and task_status=$task_status";
$model_id  and $where .=" and model_id = $model_id";
$task_id  and  $where .=" and task_id = $task_id"; 
$where .= ' order by `task_id` desc'; 
$page = max(intval($page), 1);
$page_size = max(intval($page_size), 10);
$url = "index.php?do=keke&view=posttask&task_status=$task_status&model_id=$model_id&task_id=$task_id&page=$page";
$count = intval(db_factory::execute($sql.$where));
$pages = $kekezu->_page_obj->getPages($count, $page_size, $page, $url);
$task_list = db_factory::query($sql.$where.$pages['where']);
require $kekezu->_tpl_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );