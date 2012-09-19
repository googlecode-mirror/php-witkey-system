<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author S
 * @version kppw 2.0
 * 2011-12-14
 */

Keke::admin_check_role(52);
$case_obj = new Keke_witkey_case_class ();
//��ҳ
 
$w ['page_size'] and $page_size = intval ( $w ['page_size'] ) or $page_size =10;
$page and $page = intval ( $page ) or $page = '1';
$url = "index.php?do=$do&view=$view&w[case_id]=".$w['case_id']."&w[art_title]=".$w['art_title']."&w[case_auther]=".
$w['case_auther']."&w[obj_type]=".$w['obj_type']."&w[page_size]=$page_size&w[ord]=".$w['ord']."&page=$page";

if (isset ( $ac )) { //����ɾ��
	if ($case_id) {
		switch ($ac) {
			case "del" :
				$case_obj->setWhere ( 'case_id=' . $case_id );
				$res = $case_obj->del_keke_witkey_case ();
				Keke::admin_system_log( $_lang['delete_case'].':' . $case_id );//��־��¼
				$res and Keke::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['delete_fail'], $url,3,'','warning' );
				break; 
		}
	} else {
		Keke::admin_show_msg ( $_lang['del_fail_select_operate'], $url );
	}
} elseif (isset ( $sbt_action )) { //����ɾ��
	$ckb_string = $ckb;
	is_array ( $ckb_string ) and $ckb_string = implode ( ',', $ckb_string );
	if (count ( $ckb_string )) {
		$case_obj->setWhere ( 'case_id in (' . $ckb_string . ')' );
		$res = $case_obj->del_keke_witkey_case ();//ɾ��
		Keke::admin_system_log($_lang['mulit_delete_case'].':' . $ckb_string );//��־��¼
		$res and Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url ,3,'','success') or Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url,3,'','warning' );
	} else
		Keke::admin_show_msg ( $_lang['mulit_del_fail_select_operate'], $url,3,'','warning' );
} else {
	
	$model_list = Keke::get_table_data ( '*', 'witkey_model', "model_status=1 and model_dir!='employtask'", 'listorder asc ', '', '', 'model_id', null );
	$count = $case_obj->count_keke_witkey_case();
	
//	$sql = "select *,ifnull(case_title,task_title) task_title from ".TABLEPRE."witkey_case as a left join ".TABLEPRE."witkey_task as b on a.obj_id = b.task_id ";
	$sql = "select * from ".TABLEPRE."witkey_case";
	$where = ' where 1 = 1'; //��ѯ
	//����
	$w ['case_id'] and $where .= " and case_id = '".$w['case_id']."' ";
	$w ['art_title'] and $where .= " and case_title like '%".$w['art_title']."%' ";
	$w ['case_auther'] and $where .= " and case_auther like '%".$w['case_auther']."%' ";
	$w ['obj_type'] and $where .= " and obj_type = '".$w['obj_type']."' ";
	
	$order_where = " order by on_time desc";
	is_array($w['ord']) and $order_where= ' order by '.$w['ord']['0'].' '.$w['ord']['1'];
	$where=$where.$order_where;
	
	//$w ['ord'] and $where .= " order by $w['ord']" or $where .= " order by case_id desc";//����
	$Keke->_page_obj->setAjax(1);
	$Keke->_page_obj->setAjaxDom("ajax_dom");
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url );
	$sql.=$where.$pages['where'];
	$case_arr =Dbfactory::query($sql);

}


require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );