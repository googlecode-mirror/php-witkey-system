<?php
/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2011-9-2
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );

Keke::admin_check_role (57);

$feed_obj = new Keke_witkey_feed_class ();
 
$tag_obj = new Keke_witkey_tag_class ();
$feed_type = keke_global_class::get_feed_type ();

$template_arr = dbfactory::query ( " select tpl_title from " . TABLEPRE . "witkey_template", 1, null );

$type or $type = 'data';
intval ( $slt_page_size ) or $slt_page_size = 10;
intval ( $page ) or $page = 1;

if ($type == 'data' || ! isset ( $type )) {
	$where = " 1 = 1 ";
	$txt_feed_id and $where .= " and feed_id=$txt_feed_id ";
	$txt_title and $where .= " and title like '%" . $txt_title . "%' ";
} elseif ($type === 'manage') {
	//获取广告组
	$where = " 1=1 and tag_type=8 ";
	//查询条件
	$txt_tag_id and $where .= " and tag_id = $txt_tag_id ";
	$tpl_type or $tpl_type = $_K [template];
	$tpl_type == 1 or $where .= " and tpl_type like '%" . $tpl_type . "%' ";

}

$ord [1] and $where .= "order by $ord[0] $ord[1]";

if ($type == 'data' || ! isset ( $type )) {
	$feed_obj->setWhere ( $where );
	$count = $feed_obj->count_keke_witkey_feed ();
}
if ($type == 'manage') {
	$tag_obj->setWhere ( $where );
	$count = $tag_obj->count_keke_witkey_tag ();
}
//分页条件
if ($type === 'manage') {
	$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&feedtype=$feedtype&tpl_type=$tpl_type&txt_tag_id=$txt_tag_id&type=$type&ord[]=$ord[0]&ord[]=$ord[1]";
} else {
	$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&feedtype=$feedtype&tpl_type=$tpl_type&txt_feed_id=$txt_feed_id&type=$type&ord[]=$ord[0]&ord[]=$ord[1]";
}

$limit = $slt_page_size;
Keke::$_page_obj->setAjax(1);
Keke::$_page_obj->setAjaxDom("ajax_dom");
$pages = Keke::$_page_obj->getPages ( $count, $limit, $page, $url );

//查询结果数组
if ($type == 'data' || ! isset ( $type ) || $type == '') {
	$feed_obj->setWhere ( $where . $pages [where] );
	$feed_arr = $feed_obj->query_keke_witkey_feed ();
}
if ($type == 'manage') {
	$tag_obj->setWhere ( $where . $pages [where] );
	$feed_arr = $tag_obj->query_keke_witkey_tag ();
}
foreach ($feed_arr as $k=>$v) {
		$title_arr = unserialize($v[title]);
		$title_str =' <a href="'.$title_arr[feed_username][url].'">'.$title_arr[feed_username][content].'</a>'.$title_arr[action][content].'
		<a href="'.$title_arr[event][url].'">'.$title_arr[event][content].'</a>'; 
		$v[title] = $title_str;
		$new_feed_arr[] = $v;
}

$feed_arr = $new_feed_arr;


if ($ac == 'del') {
	$delid or Keke::admin_show_msg ( $_lang['err_parameter'], $url,3,'','warning' );
	if ($type == 'data' || ! isset ( $type ) || $type == '') {
		$feed_obj->setWhere ( "feed_id='{$delid}'" );
		$res = $feed_obj->del_keke_witkey_feed ();
	} else if ($type == 'manage') {
		$tag_obj->setWhere ( "tag_id='{$delid}'" );
		$res = $tag_obj->del_keke_witkey_tag ();
	}
	if ($res) {
		Keke::admin_show_msg ( $_lang['delete_success'], $url ,3,'','success' );
	} else {
		Keke::admin_show_msg ( $_lang['delete_fail'], $url ,3,'','warning' );
	}
}

//批量操作
if (isset ( $sbt_action ) && $sbt_action == $_lang['mulit_delete']) {
	if (is_array ( $ckb )) {
		$ids = implode ( ',', $ckb );
	}
	if ($ids) {
		
		if ($type == 'data' || ! isset ( $type ) || $type == '') {
			$feed_obj->setWhere ( ' feed_id in (' . $ids . ') ' );
			$res = $feed_obj->del_keke_witkey_feed ();
		} else if ($type == 'manage') {
			$tag_obj->setWhere ( ' tag_id in(' . $ids . ')' );
			$res = $tag_obj->del_keke_witkey_tag ();
		}
		if ($res) {
			Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url,3,'','success' );
		} else {
			Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url ,3,'','warning');
		}
	
	} else {
		Keke::admin_show_msg ( $_lang['choose_operate_item'], $url ,3,'','warning');
	}
}

require Keke::$_tpl_obj->template ( 'control/admin/tpl/admin_tpl_' . $view . '_' . $type );