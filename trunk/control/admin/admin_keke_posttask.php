<?php
/**
 * @copyright keke-tech
 * @author Michael
 * @version v 2.0
 * 2012-02-22 12:22:22
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role(137);
include S_ROOT.'/keke_client/keke/config.php';
$model_list = $Keke->_model_list;
 //任务模型数组
$task_type_arr = keke_glob_class::get_task_type();
$indus_p_arr   = $Keke->_indus_p_arr;
$indus_c_arr   = $Keke->_indus_c_arr;
//查询
$sql =sprintf("select * from %switkey_task where task_union=0 and sub_time>UNIX_TIMESTAMP() ",TABLEPRE);
$where .=" and task_status=2";
$model_id  and $where .=" and model_id = $model_id";
$indus_pid  and  $where .=" and indus_pid = $indus_pid"; 
$indus_id  and  $where .=" and indus_id = $indus_id"; 
$task_id  and  $where .=" and task_id = $task_id"; 
$where .= ' order by `task_id` desc'; 
$page = max(intval($page), 1);
$page_size = max(intval($page_size), 10);
$url = "index.php?do=keke&view=posttask&task_status=$task_status&model_id=$model_id&task_id=$task_id&page=$page";
$count = intval(Dbfactory::execute($sql.$where));
$pages = $Keke->_page_obj->getPages($count, $page_size, $page, $url);
$task_list = Dbfactory::query($sql.$where.$pages['where']);

require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );