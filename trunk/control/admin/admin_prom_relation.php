<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * �ƹ��ϵ
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-09-02 11:40:30
 */

Keke::admin_check_role ( 58 );
$prom_relation_obj = new Keke_witkey_prom_relation_class ();
//��ҳ
$page_size = isset ( $w ['page_size'] ) ? intval ( $w ['page_size'] ) : 10;
$page = $page ? intval ( $page ) : '1';

$url = "index.php?do=$do&view=$view&w[relation_id]={$w['relation_id']}&w[prom_username]={$w['prom_username']}&w[username]={$w['username']}&w[prom_type]={$w['prom_type']}&w[relation_status]={$w['relation_status']}&w[page_size]=$page_size&w[ord]={$w['ord']}&page=$page";
$ac_url = "index.php?do=$do&view=$view&w[prom_type]={$w['prom_type']}&w[relation_status]={$w['relation_status']}&w[page_size]=$page_size&w[ord]={$w['ord']}&page=$page";
if (isset ( $ac )) { //��������
	if ($relation_id && $ac = 'del') {
		$prom_relation_obj->setWhere ( 'relation_id=' . intval($relation_id) );
		$res = $prom_relation_obj->del_keke_witkey_prom_relation ();
		Keke::admin_system_log ( $_lang['delete_prom_relation'] . $relation_id );
		Keke::admin_show_msg ( $res ? $_lang['delete_success'] : $_lang['delete_fail'], $url,3,'',$res?'success':'warning' );
	} else
		Keke::admin_show_msg ( $_lang['delete_fail_please_choose_operate'], $url,3,'','warning' );
} elseif (isset ( $sbt_action )) { //����ɾ��
	$ckb_string = $ckb;
	is_array ( $ckb_string ) and $ckb_string = implode ( ',', $ckb_string );
	if (count ( $ckb_string )) {
		$prom_relation_obj->setWhere ( ' relation_id in (' . $ckb_string . ') ' );
		$res = $prom_relation_obj->del_keke_witkey_prom_relation ();
		Keke::admin_system_log ( $_lang['mulit_delete_prom_relation'] . $ckb_string );
		Keke::admin_show_msg ( $res ? $_lang['mulit_operate_success'] : $_lang['mulit_operate_fail'], $url,3,'',$res?'success':'warning' );
	} else
		Keke::admin_show_msg ( $_lang['mulit_delete_fail_please_choose'], $url,3,'','warning' );
} else {
	$status_arr = keke_prom_class::get_prelation_status ();
	$type_arr = keke_prom_class::get_prom_type();
	//��ѯ����
	$where = '1=1';
	$w ['relation_id'] and $where .= " and relation_id = '{$w['relation_id']}'";
	$w ['username'] and $where .= " and username like '%{$w['username']}%'";
	$w ['prom_username'] and $where .= " and prom_username like '%{$w['prom_username']}%'";
	$w ['prom_type'] and $where .= " and prom_type = '{$w['prom_type']}'";
	$w ['relation_status'] >= '0' and $where .= " and relation_status = '{$w['relation_status']}'";
	//var_dump($w['ord']);

	is_array($w['ord']) and $where .= ' order by '.$w['ord']['0'].' '.$w['ord']['1'];
	
	//��ѯͳ��
	//echo $where;
	$prom_relation_obj->setWhere ( $where );
	$count = $prom_relation_obj->count_keke_witkey_prom_relation ();
	$Keke->_page_obj->setAjax(1);
	$Keke->_page_obj->setAjaxDom('ajax_dom');
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url ); //��ҳ
	//��ѯ�������
	$prom_relation_obj->setWhere ( $where . $pages ['where'] );
	$prom_relation_arr = $prom_relation_obj->query_keke_witkey_prom_relation ();
}
//echo 'control/admin/tpl/admin_' . $do . '_' .$view ;
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );