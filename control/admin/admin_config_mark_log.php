<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * ������¼
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-10-22-����04:09:42
 * @version V2.0
 */

Keke::admin_check_role ( 79 );
$status = array (0 => $_lang['to_be_evaluated'], 1 => $_lang['good_value'], 2 => $_lang['middle_value'], 3 => $_lang['bad_value'] );
$form = array (1 => $_lang['witkey'], 2 => $_lang['employer'] );

$obj_arr = array ('task' => $_lang['task'], 'work' => $_lang['workl'], 'shop' => $_lang['goods'] );
$model_type_arr = keke_glob_class::get_model_type ();
$model_list = Keke::get_table_data ( '*', 'witkey_model', '', 'model_id asc ', '', '', 'model_code');
$mark_obj = keke_table_class::get_instance ( 'witkey_mark' );
$page and $page=intval ( $page ) or $page = 1;
$w ['page_size'] and $w ['page_size']=intval ( $w ['page_size'] ) or $w ['page_size'] = 10;

if(is_numeric($ord['0']) ||is_numeric($ord['1'])){
	Keke::admin_show_msg($_lang['operate_notice'],'index.php?do=$do&view=$view&op=$op',3,$_lang['operate_fail'],'warning');
}
$url = "index.php?do=$do&view=$view&op=$op&w[by_username]=".$w['by_username']."&w[mark_id]=".$w['mark_id']
."&w[page_size]=".$w['page_size']."&page=$page&ord[]=".$ord['0']."&ord[]=".$ord['1'];

if ($ac == 'del' && $mark_id) {
	$mark_obj->del ( 'mark_id', $mark_id ) and Keke::admin_show_msg ( $_lang['operate_notice'], $url, 2, $_lang['delete_success'],'success' ) or Keke::admin_show_msg ( $_lang['operate_notice'], $url, 2, $_lang['delete_faile'], 'warning' );
} elseif ($sbt_action == $_lang['mulit_delete']) {
	$mark_obj->del ( 'mark_id', array_filter ( $ckb ) ) and Keke::admin_show_msg ( $_lang['operate_notice'], $url, 2, $_lang['mulit_operate_success'],'success') or Keke::admin_show_msg ( $_lang['operate_notice'], $url, 2, $_lang['mulit_operate_fail'], 'warning' );
} else {
	$where = "1=1";
	intval ( $w ['mark_id'] ) and $where .= " and  mark_id = ".$w['mark_id'];
	strval ( $w ['by_username'] ) and $where .= " and by_uername like '%".$w['by_username']."%'";
	$ord['0']&&$ord['1'] and $where .= " order by ".$ord['0']." ".$ord['1'];
	$data = $mark_obj->get_grid ( $where, $url, $page, $w['page_size'],null ,1 ,'ajax_dom');
	$mark_data = $data ['data'];
	$pages = $data ['pages'];
}

require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_" . $op );