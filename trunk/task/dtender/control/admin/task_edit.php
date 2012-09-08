<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-09-10 13:51:34
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
intval ( $task_id ) or kekezu::admin_show_msg ( $_lang ['param_error'], 'index.php?do=model&model_id=' . $model_id . '&view=list', 3, '', 'warning' );
$ops = array ('basic', 'work', 'comm', 'mark');
in_array ( $op, $ops ) or $op = 'basic';

keke_lang_class::loadlang('task_edit','task');
if ($op == 'basic') { //基本信息
	$model_list = $kekezu->_model_list;
	
	$task_status = dtender_task_class::get_task_status();
	$task_id = $task_id ? $task_id : kekezu::admin_show_msg ($_lang['param_error'], "index.php?do=model&model_id=$model_id&view=list",3,'','warning' );
	$task_obj=keke_table_class::get_instance("witkey_task");
	$task_info = db_factory::get_one ( " select * from " . TABLEPRE . "witkey_task where task_id = '$task_id'" );
	//load  attachment
	$file_list = db_factory::get_one ( " select * from " . TABLEPRE . "witkey_file where task_id = '$task_id'" );
	$cash_rule_arr = kekezu::get_table_data ( "*", "witkey_task_cash_cove", "model_code='{$model_info['model_code']}'", "", '', '', "cash_rule_id" );
	/**可执行动作**/
	$operate = keke_task_config::can_operate ( $task_info ['task_status'] );
	if ($sbt_edit) {
		if (! $fds[indus_id]) {
			kekezu::admin_show_msg ( $_lang['must_select_a_industry'], $_SERVER['HTTP_REFERER'],3,'','warning');
		}
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
			$v_arr = array($_lang['admin_name']=>$myinfo_arr ['username'],$_lang['time']=>date('Y-m-d H:i:s',time()),$_lang['model_name']=>$model_info['model_name'],$_lang['task_id']=>$task_info ['task_id'],$_lang['task_title']=>$task_info ['task_title']);
	        keke_msg_class::notify_user($task_info ['uid'],$task_info ['username'],'task_edit',$_lang['edit_task'],$v_arr);
		kekezu::admin_show_msg ( $_lang['task_edit_success'], "index.php?do=model&model_id=$model_id&view=list",3,'','success' );
		}else{
			kekezu::admin_show_msg ( $_lang['task_edit_fail'], "index.php?do=model&model_id=$model_id&view=edit&task_id=$task_id",3,'','warning' );
		}
	}
	$indus_arr = $kekezu->_indus_arr;
	$temp_arr = array ();
	$indus_option_arr = $indus_arr;
	$indus_arr = kekezu::get_industry ( 1 );
	$temp_arr = array ();
	//$indus_option_arr = kekezu::get_industry ( 1 );
	//var_dump($indus_option_arr);die();
	kekezu::get_tree ( $indus_option_arr, $temp_arr, "option", $task_info ['indus_id'] );
	$indus_option_arr = $temp_arr;
}else{//任务杂项
	require S_ROOT.'/task/'.$model_info ['model_dir'].'/control/admin/task_misc.php';
}

require keke_tpl_class::template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_edit_' .$op  );