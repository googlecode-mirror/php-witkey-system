<?php
/**
 * ��̨��������
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * @date 2011-12-26 ����09:43:25
 * @encoding GBK
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role ( 32 );

$tag_obj = new Keke_witkey_tag_class ();
$ad_obj = new Keke_witkey_ad_class ();
$table_obj = new keke_table_class ( 'witkey_tag' );

$url = "index.php?do=$do&view=$view&order_type=$order_type&w[tpl_type]=$w[tpl_type]&w[page_size]=$page_size&w[ord]=$w[ord]&page=$page";

//ɾ��
if ($sbt_action == $_lang['mulit_delete'] || $ac == 'del') {
	if (! empty ( $delid )) { //������¼
		$ad_name = dbfactory::get_count ( "select tagname from " . TABLEPRE . "witkey_tag where tag_id = '$delid'" );
		$ad_obj->setWhere ( "ad_name='$ad_name'" );
		$ad_result = $ad_obj->del_keke_witkey_ad (); /*ɾ������¹��  */
		$tag_obj->setWhere ( 'tag_id=' . $delid ); //ɾ������ǩ
		$result = $tag_obj->del_keke_witkey_tag ();
		Keke::admin_system_log ( $_lang['delete_ads_tags_id'] . $delid . 'name:' . $ad_name . $_lang['corresponding_ads_data'] . $ad_result . $_lang['tiao'] );
		Keke::admin_show_msg ( $result ? $_lang['delete_ads_tags_success'] : $_lang['delete_fail'], $url,3,'',$result?'success':'warning' );
	
	} else if (! empty ( $ckb )) { //����ɾ��
		$ids = is_array ( $ckb ) && count ( $ckb ) > 0 ? implode ( ',', $ckb ) : Keke::admin_show_msg ( $_lang['delete_fail_select_operation'],$url,3,'','warning' ); // echo $ids;
		$tag_name_arr = dbfactory::query ( ' select tagname from ' . TABLEPRE . "witkey_tag where tag_id in($ids) " );
		while ( list ( $key, $value ) = each ( $tag_name_arr ) ) {
			$ad_obj->setWhere ( 'ad_name = "' . $value ['tagname'] . '"' );
			$ad_result .= $ad_obj->del_keke_witkey_ad ();
		}
		$tag_obj->setWhere ( 'tag_id in(' . $ids . ')' );
		$result = $tag_obj->del_keke_witkey_tag ();
		Keke::admin_system_log ( $_lang['mulit_delete_ads_tags'] . $ids . $_lang['is_corresponding_ads_data'] . $ad_result . $_lang['tiao'] );
		Keke::admin_show_msg ( $result ? $_lang['mulit_delete_ads_tags_success'] : $_lang['delete_fail'], $url,3,'',$res?'success':'warning' );
	}

}
//��ѯ
$template_arr = dbfactory::query ( ' select tpl_title from ' . TABLEPRE . 'witkey_template' ); //����ģ��
$where = '1=1 and tag_type=9';
$w ['tag_id'] && $where .= ' and tag_id="' . $w ['tag_id'] . '"';
$w ['tagname'] && $where .= " and INSTR(tagname,'$w[tagname]')>0 ";
is_array($w['ord']) && $w ['ord']=$w['ord'][0].' '.$w['ord'][1];//implode(' ',$ord);
$w ['ord'] && $where .= ' order by ' . $w ['ord'] or $sql .= ' order by tag_id desc ';
$w ['page_size'] and $page_size = intval ( $w ['page_size'] ) or $page_size = 10;
$page and $page = intval ( $page ) or $page = '1';
$tag_arr = $table_obj->get_grid ( $where, $url, $page, $page_size );
$pages = $tag_arr ['pages'];
$tag_arr = $tag_arr ['data'];
//var_dump($tag_arr);
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );
  