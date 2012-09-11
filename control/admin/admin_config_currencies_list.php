<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 支付配置
 * @author S
 * @version v 2.0
 * 2011-12-13
 */

Keke::admin_check_role ( 2 );
$url = "index.php?do=$do&view=$view"; 
$default_currency =$Keke->_sys_config['currency'];
$currencies_obj = new keke_table_class('witkey_currencies');
$page and $page=intval ( $page ) or $page = 1;
$slt_page_size and $slt_page_size=intval ( $slt_page_size ) or $slt_page_size = 20;
$cur = new keke_curren_class();
if ($ac == 'del') {
	if ($cid&&($cid!=keke_curren_class::$_default['currencies_id'])) { //不允许删除默认货币
		$res = $currencies_obj->del ( "currencies_id", $cid, $url );
		Keke::admin_system_log ( $_lang['links_delete'].$del_id );
		Keke::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' );die;
	} else {
		Keke::admin_show_msg ( $_lang['delete_fail'], $url ,3,$_lang['del_default'],'warning');die;
	}

}else {
	$where = ' 1 = 1  ';
	$d = $currencies_obj->get_grid ( $where, $url, $page, $slt_page_size,null,1,'ajax_dom');
	$currencies_config = $d [data];
	$pages = $d [pages];
}
//更新汇率
if($ac=='update'){
	if(isset($code)){
		$res = $cur->update(false,$code);
	}else{
		//批量更新
		$res = $cur->update(true);
	}
	$res and Keke::admin_show_msg ( $_lang['update_mi_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['update_mi_fail'], $url,3,'','warning' );
}


require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view );