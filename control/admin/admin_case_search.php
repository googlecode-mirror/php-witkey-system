<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author S
 * @version kppw 2.0
 * 2011-12-14
 */

Keke::admin_check_role(52); 
$model_type_arr  = keke_glob_class::get_task_type();
$Keke->_page_obj->setAjax(1);
$Keke->_page_obj->setAjaxDom('ajax_dom');
//��������
if($search_type=='task'){
	$sql =sprintf("select * from %switkey_task where ",TABLEPRE);  
	$where = sprintf(" 1=1 and  task_status=%d",8);
	$search_id and 	$where .= sprintf(" and task_id=%d",$search_id);
	$url = "index.php?do=case&view=search&page_size=$page_size&search_type=$search_type&search_id=$search_id";
	$page_size = 5;
	$count = Dbfactory::get_count(sprintf("select count(task_id) as c from `%switkey_task` where %s ",TABLEPRE,$where));
	$page = $page ? $page : 1;
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url );
	$where .=$pages['where']; 
	$task_case_arr = Dbfactory::query( $sql.$where);

}elseif($search_type=='service'){
	$sql =sprintf("select * from %switkey_service where ",TABLEPRE);  
	$where = sprintf(" service_status!=%d",1);
	$search_id and 	$where .= sprintf(" and service_id=%d",$search_id);
	$url = "index.php?do=case&view=search&page_size=$page_size&search_type=$search_type&search_id=$search_id";
	$page_size = 5;
	$count = Dbfactory::get_count(sprintf("select count(service_id) as c from `%switkey_service` where %s",TABLEPRE,$where));
	$page = $page ? $page : 1;
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url );
	$where .=$pages['where']; 
	$task_case_arr = Dbfactory::query( $sql.$where);
	
}

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );