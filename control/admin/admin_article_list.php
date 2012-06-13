<?php
/**
 * @copyright keke-tech
 * @author Liyingqing
 * @version v 2.0
 * 2010-7-15 10:00:34
 * @example art => cate_id <100 ,help =>cate_id<200 and cate_id >=100,singel =>cate_id >=200
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );

Keke::admin_check_role(16);
$art_obj = new Keke_witkey_article_class ();

$table_obj = new keke_table_class ( "witkey_article" );

$types = array ('help', 'art', 'single' );
$type = (! empty ( $type ) && in_array ( $type, $types )) ? $type : 'art';
$url = "index.php?do=$do&view=$view&w[username]=$w[username]&w[art_title]=$w[art_title]&w[art_cat_id]=$w[art_cat_id]&page_size=$page_size&page=$page&type=$type";

if ($ac == 'del') {
	$res = $table_obj->del ( 'art_id', $art_id, $url );
	$res and Keke::admin_show_msg ( $_lang['operate_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['operate_fail'], $url,3,'','warning' );
} elseif (isset ( $sbt_action )) {
	sizeof ( $ckb ) or Keke::admin_show_msg ( $_lang['choose_operate_item'], $url,3,'','warning' );
	is_array ( $ckb ) and $ids = implode ( ',', array_filter ( $ckb ) );
	$art_obj->setWhere ( "art_id in ($ids)" );
	switch ($sbt_action) {
		case $_lang['recycle'] ://放入回收站
			$art_obj->setIs_show ( 2 );
			$res = $art_obj->edit_keke_witkey_article ();
			Keke::admin_system_log ( $_lang['mulit_delete_articles'] );//批量删除文章
			break;
		case $_lang['recovery_articles'] ://恢复文章
			$art_obj->setIs_show ( 1 );
			$res = $art_obj->edit_keke_witkey_article ();
			Keke::admin_system_log ( $_lang['mulit_recovery_articles']);
			break;
		case $_lang['mulit_delete'] ://批量删除
			$res = $art_obj->del_keke_witkey_article ();
			Keke::admin_system_log ( $_lang['mulit_recovery_articles'] );
			break;
		default :
			break;
	}
	$res and Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url,3,'','warning' );

} elseif ($op == 'listorder') {
	$art_obj = new Keke_witkey_article_class();
	$art_obj->setWhere ( "art_id='$art_id'" );
	$art_obj->setListorder (intval($value));
	$art_obj->edit_keke_witkey_article(); 
	Keke::admin_system_log($_lang['edit_art_order'].$art_id);
	die ();
} else {
	$where = ' 1 = 1 ';
	switch ($type) {
		case 'art' :
			Keke::admin_check_role ( 32 );
			$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "art_index like '%{1}%'", " art_cat_id desc", '', '', 'art_cat_id', null );
			$where .= " and art_cat_id in (select art_cat_id from " . TABLEPRE . "witkey_article_category where art_index like '%{1}%') ";
			break;
			;
		case 'help' :
			Keke::admin_check_role (42);
			$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "art_index like '%{100}%'", "art_cat_id desc", '', '', 'art_cat_id', null );
			$where .= " and art_cat_id in (select art_cat_id from " . TABLEPRE . "witkey_article_category where art_index like '%{100}%')";
			break;
			;
		case 'single' :
			Keke::admin_check_role ( 53);
			$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "art_index like '%{200}%'", " art_cat_id desc", '', '', 'art_cat_id', null );
			$where .= " and art_cat_id in (select art_cat_id from " . TABLEPRE . "witkey_article_category where art_index like '%{200}%') ";
			break;
			;
	}
	
	//文章分类
	$temp_arr = array ();
	Keke::get_tree ( $art_cat_arr, $temp_arr, 'option', NULL, 'art_cat_id', 'art_cat_pid', 'cat_name' );
	$cat_arr_list = $temp_arr;
	unset ( $temp_arr );
	
	$page_size  and $page_size = intval ( $page_size ) or $page_size = 10;
	$page and $page = intval ( $page ) or $page = 1;
	$w [art_id] and $where .= " and art_id = ".intval ( $w [art_id] );
	strval ( $w [art_title] ) and $where .= " and art_title like '%$w[art_title]%'";
	$w [art_cat_id] and $w [art_cat_id]=intval ( $w [art_cat_id] ) and $where .= " and art_cat_id in  (select art_cat_id from " . TABLEPRE . "witkey_article_category where art_index like '%{{$w [art_cat_id] }}%')";
	strval ( $w [username] ) and $where .= " and username like '%$w[username]%' ";
	
	$ord[0]&&$ord[1] and $where .=' order by '.$ord[0].' '.$ord[1] or $where.=" order by art_id desc ";
	
	//$ord and $where .= " order by $ord" or $where.="order by art_id desc";

	$r = $table_obj->get_grid ( $where, $url, $page, $page_size,null,1,'ajax_dom');
	$art_arr = $r [data];
	$pages = $r [pages];
}

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . "_" . $view );