<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * ������������
 * this not free,powered by keke-tech
 * @author Keke
 * @charset:GBK  
 * @version V2.0
 */

Keke::admin_check_role ( 133 );

$juese = array ("1" => $_lang['witkey'], "2" => $_lang['employer'] );

$url = "index.php?do=config&view=mark&op=config";

$mark_config_obj = keke_table_class::get_instance ( 'witkey_mark_config' );

$mark_config_id and $mark_config_arr = $mark_config_obj->get_table_info ( 'mark_config_id', intval($mark_config_id) );

foreach ( $Keke->_model_list as $k => $v ) {
	$model_list2 [$v ['model_code']] = $v ['model_name'];
}
if ($sbt_add && $fds && $hdn_mark_config_id) {
	$hdn_mark_config_id and Keke::admin_system_log ( $_lang['edit'] . $obj_name . $_lang['mark_config'] );
	$res = $mark_config_obj->save ( $fds, array ('mark_config_id' => $hdn_mark_config_id ) );
	$res and Keke::admin_show_msg ( $_lang['edit_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['edit_fail'], $url,3,'','warning' );
}

require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_" . $op );