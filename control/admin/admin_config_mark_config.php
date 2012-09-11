<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * »¥ÆÀÅäÖÃ
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-10-22-ÏÂÎç04:10:03
 * @version V2.0
 */

Keke::admin_check_role ( 78 );

$juese = array ("1" => $_lang['witkey'], "2" => $_lang['employer'] );

$url = "index.php?do=$do&view=$view&op=$op&mark_config_id=$mark_config_id";

$mark_config_obj = keke_table_class::get_instance ( 'witkey_mark_config' );

if ($ac == 'del' && $mark_config_id) {
	Keke::admin_system_log ( $_lang['delete_mark_config'] );
	$mark_config_obj->del ( 'mark_config_id', $mark_config_id ) and Keke::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['delete_faile'], $url,3,'','warning' );

}
foreach ( $Keke->_model_list as $k => $v ) {
	$model_list2 [$v ['model_code']] = $v ['model_name'];
}

$mark_config_arr = $mark_config_obj->get_grid ( '1=1', $url, '', 14 );

$mark_config_arr = $mark_config_arr ['data'];

require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_" . $op );