<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * »¥ÆÀ¹æÔòÅäÖÃ
 * @copyright keke-tech
 * @author Aqing
 * @version v 2.0
 * 2010-08-29 14:37:34
 */
class Control_admin_user_marklog extends Controller{
	function action_index(){
		global $_K,$_lang;
		$fields = '`mark_id`,`model_code`,`mark_type`,`by_username`,`username`,`mark_status`,`mark_value`,`mark_time`';
		$query_fields = array('mark_id'=>$_lang['id'],'username'=>$_lang['name'],'mark_time'=>$_lang['time']);
		$base_uri=BASE_URL.'/index.php/admin/user_mark/log';
		$this->_default_ord_field = 'mark_time';
		$count = intval($_GET['count']);
		extract($this->get_url($base_uri));
		$data_info = Model::factory('witkey_mark')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		$list_arr = $data_info['data'];
		$pages = $data_info['pages'];
		$model_type_arr = keke_global_class::get_model_type ();
		require keke_tpl::template('control/admin/tpl/user/mark_log');
	}
}
/* Keke::admin_check_role ( 79 );
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

require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_" . $op ); */