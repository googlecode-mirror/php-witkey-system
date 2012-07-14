<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-12-25 ����   2:32:39
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**�������Ӽ�¼**/
$link_obj = new Keke_witkey_link_class ();
$page_obj = Keke::$_page_obj;
$where = " obj_type='shop' and obj_id='{$shop_info['shop_id']}' order by link_id desc";
intval ( $page ) or $page = '1';
intval ( $page_size ) or $page_size = '5';
$url = "index.php?do=$do&view=$view&op=$op&opp=link&page_size=$page_size&page=$page";

$link_obj->setWhere ( $where );
$count = intval ( $link_obj->count_keke_witkey_link () );
$pages = $page_obj->getPages ( $count, $page_size, $page, $url, "#userCenter" );

$link_obj->setWhere ( $where . $pages ['where'] );
$shop_link = $link_obj->query_keke_witkey_link ();
switch ($ac) {
	case "del" :
		$res = dbfactory::execute ( sprintf ( " delete from %switkey_link where link_id='%d'", TABLEPRE, $link_id ) );
		$res and Keke::echojson ( $_lang['delete_success'], "1" ) or Keke::echojson ( $_lang['delete_fail'], "0" );
		die ();
		break;
	case "edit" :
		$link_obj->setWhere ( " link_id='$link_id'" );
		CHARSET == 'gbk' and $link_name = Keke::utftogbk ( $link_name );
		$link_obj->setLink_name ( $link_name );
		$link_obj->setLink_url ( $link_url );
		$res = $link_obj->edit_keke_witkey_link ();
		$res and Keke::echojson ( $_lang['edit_success'], '1' ) or Keke::echojson ( $_lang['edit_fail'], "0" );
		die ();
		break;
	case "add" :
		$link_obj->_link_id = null;
		CHARSET == 'gbk' and $link_name = Keke::utftogbk ( $link_name );
		$link_obj->setLink_name ( $link_name );
		$link_obj->setLink_status ( 1 );
		$link_obj->setLink_url ( $link_url );
		$link_obj->setObj_id ( $shop_info ['shop_id'] );
		$link_obj->setObj_type ( "shop" );
		$link_obj->setOn_time ( time () );
		$link_id = $link_obj->create_keke_witkey_link ();
		$link_id and Keke::echojson ( $_lang['add_success'], $link_id ) or Keke::echojson ( $_lang['add_fail'], "0" );
		die ();
		break;
}

require Keke_tpl::template ( "user/" . $do . "_" . $op . "_" . $opp );