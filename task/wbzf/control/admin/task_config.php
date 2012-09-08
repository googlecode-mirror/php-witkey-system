<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-11-07 11:31:34
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$ops = array ("config", "control", "priv" );
in_array ( $op, $ops ) or $op = 'config';
$ac_url = "index.php?do=model&model_id=$model_id&view=config&op=$op";
kekezu::empty_cache();
switch ($op) {
	case "config" : // 基本配置
		if ($sbt_edit) {
			$model_obj = keke_table_class::get_instance ( "witkey_model" );
			! empty ( $fds [indus_bid] ) and $fds ['indus_bid'] = implode ( ",", $fds [indus_bid] ) or $fds ['indus_bid'] = '';
			$fds ['on_time'] = time ();
			$fds = kekezu::escape ( $fds );
			$res = $model_obj->save ( $fds, $pk );
			$res and kekezu::admin_show_msg ( $_lang['edit_successfully'], $ac_url, 3, '', 'success' ) or kekezu::admin_show_msg ( "修改失败", $ac_url, 3, '', 'warning' );
		} else {
			$indus_arr = $kekezu->_indus_arr; // 任务行业
			$indus_index = kekezu::get_indus_by_index (); // 索引行业
		}
		break;
	case "control" : // 流程配置
		if ($sbt_edit) {
			$conf = unserialize($model_info['config']);
			$conf = array_merge($conf, $_POST['conf']);
			if($affect_rule){
				while (list($k,$v)=each($conf['sina_affect_rule'])){
					$conf['sina_affect_rule'][$k]['cash']=$affect_rule[$k];
					$conf['ten_affect_rule'][$k]['cash']=$affect_rule[$k];
				}
			}
			$res .= keke_task_config::set_time_rule ( $model_id, $timeOld, $timeNew ); // 时间规则配置
			is_array ( $conf ) and $res .= keke_task_config::set_task_ext_config ( $model_id, $conf );
			$res and kekezu::admin_show_msg ( $_lang['edit_successfully'], $ac_url, 3, '', 'success' ) or kekezu::admin_show_msg ( $_lang['edit_fail'], $ac_url, 3, '', 'warning' );
		} else {
			$confs = unserialize($model_info['config']);
			is_array($confs)&&extract($confs); // 配置解压
			$total_affect_level= sizeof(keke_glob_class::num2ch());//总共得影响力等级
			$time_rule = keke_task_config::get_time_rule ( $model_id ); // 时间规则
		}
		break;
	case "priv" : // 权限配置
		if ($sbt_edit && $fds ['allow_times']) {
			$perm_item_obj = new Keke_witkey_priv_item_class ();
			foreach ( $fds ['allow_times'] as $k => $v ) {
				$perm_item_obj->setWhere ( " op_id = '$k'" );
				$perm_item_obj->setAllow_times ( intval ( $v ) );
				$perm_item_obj->edit_keke_witkey_priv_item ();
			}
			kekezu::admin_show_msg ( $model_info [model_name] . $_lang['edit_rights_config_successfully'], "$ac_url", '3', '', 'success' );
		}
		$perm_item = keke_privission_class::get_model_priv_item ( $model_id ); // 权限配置项
		break;
}
if ($sbt_edit) {
		//清除配置缓存
	$file_obj = new keke_file_class();
	$file_obj->delete_files(S_ROOT."./data/data_cache/");
	$log_op_arr = array ("config" => $_lang['basic_config'], "control" => $_lang['control_config'], "priv" => $_lang['private_config'] );
	$log_msg = $_lang['edit_single_reward_task'] . $log_op_arr [$op];
	kekezu::admin_system_log ( $log_msg );
}

require keke_tpl_class::template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_' . $op );