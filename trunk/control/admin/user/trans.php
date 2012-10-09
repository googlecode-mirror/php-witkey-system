<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.1
 * 2012-10-9 下午17：30
 */
class Control_admin_user_trans extends Controller{
	function action_index(){
		global $_K,$_lang;
		$fields = '`report_id`,`obj`,`username`,`to_username`,`report_file`,`on_time`,`report_status`,`op_username`';
		$query_fields = array('report_id'=>$_lang['id'],'username'=>$_lang['name'],'on_time'=>$_lang['time']);
		$base_uri = BASE_URL.'/index.php/admin/user_trans';
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'on_time';
		extract($this->get_url($base_uri));
		$data_info = Model::factory('witkey_report')->get_grid($fields,$fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		$list_arr = $data_info['data'];
		$pages = $data_info['pages'];
// 		var_dump($data_info);die;
	}
}
/* Keke::admin_check_role(80);
$views = array ("rights", "report", "complaint", "process" );
in_array ( $view, $views ) or $view = "rights";

$action_arr    = keke_report_class::get_transrights_type(); *//**交易维权类型**/
/* $trans_status = keke_report_class::get_transrights_status(); //交易维权状态
$trans_object = keke_report_class::get_transrights_obj(); //交易维权对象
$page and $page=intval ( $page ) or $page = '1';
$page_size and $page_size=intval ( $page_size ) or $page_size = "10";
$url = "index.php?do=$do&view=$view&report_status=$report_status&obj=$obj&ord=$ord&page_size=$page_size&page=$page";
//die('1');
if ($ac) {
	switch ($ac) {
		//die('1');
		case "del" :
			if ($report_id) {
				$res = Dbfactory::execute ( sprintf ( " delete from %switkey_report where report_id='%d'", TABLEPRE, $report_id ) );
				$res and Keke::admin_show_msg ( $_lang['record_delete_success'], $url, "3",'','success' ) or Keke::admin_show_msg ($action_arr[$view]. $_lang['record_delete_fail'], $url, "3",'','warning');
			} else
				Keke::admin_show_msg ($_lang['choose_delete_operate'], $url, "3",'','warning' );
			break;
		case "download" :
			keke_file_class::file_down ( $filename, $filepath );
			break;
	}

} elseif ($sbt_action) {
	
	$ckb and $dels = implode ( ",", $ckb ) or $dels = array ();
	if (! empty ( $dels )) {
		$res = Dbfactory::execute ( sprintf ( " delete from %switkey_report where report_id in ('%s') ", TABLEPRE, $dels ) );
		$res and Keke::admin_show_msg ( $action_arr[$view].$_lang['record_mulit_delete_success'], $url, "3",'','success' ) or Keke::admin_show_msg ( $action_arr[$view].$_lang['record_delete_fail'], $url, "3",'','warning' );
	} else
		Keke::admin_show_msg ($_lang['choose_delete_operate'], $url, "3",'','warning' );

} else {
	
	$report_obj = new Keke_witkey_report_class ();
	$page_obj = $Keke->_page_obj;
	
	$where = " report_type = '" . $action_arr [$view] ['0'] . "'";
	$report_id and $where .= " and report_id='$report_id'";
	$report_status and $where .= " and report_status='$report_status' ";
	$obj and $where .= " and obj='$obj' ";

	is_array($w['ord']) and $where .=' order by '.$ord['0'].' '.$ord['1']  or $where .= " order by report_id desc ";
	$report_obj->setWhere ( $where );
	$count = intval ( $report_obj->count_keke_witkey_report () );
	$page_obj->setAjax(1);
	$page_obj->setAjaxDom("ajax_dom");
	$pages = $page_obj->getPages ( $count, $page_size, $page, $url );
	
	$report_obj->setWhere ( $where . $pages ['where'] );
	$report_list = $report_obj->query_keke_witkey_report ();
}

if ($view != 'process') {
	require keke_tpl_class::template ( 'control/admin/tpl/admin_trans_rights' );
} else {

	//var_dump(ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php');die();
	require ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php';
} */

