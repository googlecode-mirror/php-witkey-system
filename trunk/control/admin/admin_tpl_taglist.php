<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-5-24����06:08:41
 */



Keke::admin_check_role (29);
$tag_list = Keke::get_tag ();
$tag_obj = new Keke_witkey_tag_class ();
//$tag_type_arr = keke_glob_class::get_tag_type ();
$t    = max($t,0);

$slt_page_size and $slt_page_size=intval ( $slt_page_size ) or $slt_page_size = 10;
$page and $page=intval ( $page ) or $page = 1; 
$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&ord=$ord&tag_type=$tag_type&tpl_type=$tpl_type&type=$type&txt_title=$txt_title";
if ($op == 'del') {
	$delid = $delid ? $delid : Keke::admin_show_msg ($_lang['wrong_parameters'], $url,3,'','warning' );	
	$tag_obj->setWhere ( "tag_id='{$delid}'" );
	$tag_obj->del_keke_witkey_tag ();
	$Keke->_cache_obj->del ( 'tag_list_cache' );
	Keke::admin_system_log ( $_lang['delete_tag']."$delid" );
	Keke::admin_show_msg ($_lang['operate_success'], $url,3,'','success' );
} elseif (isset ( $sbt_action )) { //��������	
	if (is_array ( $ckb )) {
		$ids = implode ( ',', array_filter ( $ckb ) );
	}
	if (count ( $ids )) {
		$tag_obj->setWhere ( ' tag_id in (' . $ids . ') ' );
		$tag_obj->del_keke_witkey_tag ();
		$Keke->_cache_obj->del ( 'tag_list_cache' );
		Keke::admin_system_log ($_lang['delete_tag']. "$ids" );
		Keke::admin_show_msg ($_lang['mulit_operate_success'], $url,3,'','success' );
	} else {
		Keke::admin_show_msg ( $_lang['choose_operate_item'], $url,3,'','warning' );
	}
} else {
	
	//Ĭ�ϲ�ѯ����
	$where = " tag_type=5  ";
	$type or $type = 1;
	//var_dump($type);
	
	if($type==1){
	   $where .=" and tagname like '%�%' ";
	}elseif($type==2){
	   $where .=" and tagname like '%Э��%' ";
	}else{
	    $where .=" and tagname like '%����%' ";
	}
	//$where .= " and tag_type=$tag_type ";
	strval ( $txt_title ) and $where .= " and tagname like '%$txt_title%' ";
	
	$ord ['1'] and $where .= " order by". $ord['0']. $ord['1'];	
	$t_obj = keke_table_class::get_instance ( "witkey_tag" );
	$tag_type=5;
	$d = $t_obj->get_grid ( $where, $url, $page, $slt_page_size );
	$tag_arr = $d ['data'];	
	$pages = $d ['pages'];
}

require $template_obj->template ( 'control/admin/tpl/admin_tpl_' . $view );