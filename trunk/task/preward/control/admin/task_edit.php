<?php
/**
 * 计件悬赏任务编辑 页面 
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );

intval ( $task_id ) or kekezu::admin_show_msg ( $_lang['param_error'], 'index.php?do=model&model_id=' . $model_id . '&view=list',3,'','warning' );
$task_info = db_factory::get_one ( sprintf ( " select * from %switkey_task where task_id='%d'", TABLEPRE, $task_id ) );

if ($sbt_edit) {//编辑
	$task_obj = new Keke_witkey_task_class ();
	$task_obj->setWhere(" task_id =".$task_id);
	if($recommend){
		$task_obj->setIs_top(1);
	}else{
		$task_obj->setIs_top(0);
	}
	$task_obj->setTask_title (kekezu::escape($fields['task_title']) );
	$task_obj->setIndus_id ( $slt_indus_id );
	$task_obj->setTask_cash($fields['task_cash']);
	$task_obj->setTask_desc ($fields['task_desc'] );
	if($_FILES['fle_task_pic']['name']){
		$task_pic = keke_file_class::upload_file("fle_task_pic");
	}else{
		$task_pic = $task_pic_path;
	}
	$task_obj->setTask_pic($task_pic);
	kekezu::admin_system_log ( $_lang['edit_task'].":{$fields['task_title']}" );	//生成日志
	$res=$task_obj->edit_keke_witkey_task ();
	if($res){
		kekezu::notify_user ( $_lang['system_message'], $_lang['admin'] . $myinfo_arr ['username'] . $_lang['edit_your_task'].'<b><a href="index.php?do=task&task_id=' . $task_info ['task_id'] . '">' . $task_info ['task_title'] . '</a></b>(id' . $task_id . ') 。', $task_info ['uid'], $task_info ['username'] );
	}
} elseif($sbt_act){
	switch ($sbt_act){
		case "freeze"://冻结
			$res=keke_task_config::task_freeze ( $task_id );
			break;
		case "unfreeze"://解冻
			$res=keke_task_config::task_unfreeze ( $task_id );
			break;
		case "pass"://通过
			$res=keke_task_config::task_audit_pass ( array($task_id));
			break;
		case "nopass"://不通过
			$res=keke_task_config::task_audit_nopass ( $task_id );
			break;
	}
	
}else {
	$process_arr = keke_task_config::can_operate ( $task_info ['task_status'] );
	$file_list = db_factory::query ( sprintf ( " select * from %switkey_file where task_id='%d'", TABLEPRE, $task_id ) );
	$status_arr = sreward_task_class::get_task_status ();
	
	$payitem_list=keke_payitem_class::get_payitem_config('employer');
	/*行业*/
	$indus_arr = $kekezu->_indus_arr;
	$temp_arr = array ();
	$indus_option_arr = $indus_arr;
	kekezu::get_tree ( $indus_option_arr, $temp_arr, "option", $task_info ['indus_id'] );
	$indus_option_arr = $temp_arr;
}
if($res){
	kekezu::admin_show_msg ( $_lang['task_operate_success'], "index.php?do=model&model_id=$model_id&view=list",3,'','success' );
}
require $kekezu->_tpl_obj->template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_edit' );