<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-09-10 13:51:34
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$model_list = kekezu::$_model_list;

$task_status = dtender_task_class::get_task_status();
$task_id = $task_id ? $task_id : kekezu::admin_show_msg ($_lang['param_error'], "index.php?do=model&model_id=$model_id&view=list",3,'','warning' );
$task_obj=keke_table_class::get_instance("witkey_task");
$task_info = db_factory::get_one ( " select * from " . TABLEPRE . "witkey_task where task_id = '$task_id'" );
//load  attachment
$file_list = db_factory::get_one ( " select * from " . TABLEPRE . "witkey_file where task_id = '$task_id'" );
$cash_rule_arr = kekezu::get_table_data ( "*", "witkey_task_cash_cove", "", "", '', '', "cash_rule_id" );
/**可执行动作**/
$operate = keke_task_config::can_operate ( $task_info ['task_status'] );
if ($sbt_edit) {
	if (! $fds[indus_id]) {
		kekezu::admin_show_msg ( $_lang['must_select_a_industry'], $_SERVER['HTTP_REFERER'],3,'','warning');
	}
	/**文件上传**/
	/* $upload_obj = new keke_upload_class ( UPLOAD_ROOT, array ("gif", 'jpeg', 'jpg', 'png' ), UPLOAD_MAXSIZE );
	$files = $upload_obj->run ( 'fle_task_pic', 1 );
	if ($files != 'The uploaded file is Unallowable!') {
		$task_pic = $files [0] ['saveName'];
	} */
	if($_FILES['fle_task_pic']['name']){
		$task_pic = keke_file_class::upload_file("fle_task_pic");
	}else{
		$task_pic = $task_pic_path;
	}
	$task_pic and $fds['task_pic']=$task_pic;
	if($recommend){
		$fds['is_top'] = 1;
	}else{
		$fds['is_top'] = 0;
	}
	$fds=kekezu::escape($fds);
	$pk and $success=$task_obj->save($fds,$pk);
	kekezu::admin_system_log ( $_lang['edit_task'],'{$fds[task_title]}');
	if($success){
		kekezu::notify_user ($_lang['system_message'], $_lang['admin'] . $myinfo_arr ['username'] . $_lang['edit_your_tasks'].'<b><a href="index.php?do=task&task_id=' . $task_info ['task_id'] . '">' . $task_info ['task_title'] . '</a></b>(id' . $task_id . ') 。', $task_info ['uid'], $task_info ['username'] );
	kekezu::admin_show_msg ( $_lang['task_edit_success'], "index.php?do=model&model_id=$model_id&view=list",3,'','success' );
	}else{
		kekezu::admin_show_msg ( $_lang['task_edit_fail'], "index.php?do=model&model_id=$model_id&view=edit&task_id=$task_id",3,'','warning' );
	}
}
$indus_arr = kekezu::$_indus_arr;
$temp_arr = array ();
$indus_option_arr = $indus_arr;
$indus_arr = kekezu::get_industry ( 1 );
$temp_arr = array ();
//$indus_option_arr = kekezu::get_industry ( 1 );
//var_dump($indus_option_arr);die();
kekezu::get_tree ( $indus_option_arr, $temp_arr, "option", $task_info ['indus_id'] );
$indus_option_arr = $temp_arr;


require keke_tpl_class::template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_' . $view );