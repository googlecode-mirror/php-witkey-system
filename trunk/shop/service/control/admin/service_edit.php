<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-09-29 15:31:34
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$service_obj = new service_shop_class();
$service_info = dbfactory::get_one(sprintf("select * from %switkey_service where service_id='%d'",TABLEPRE,$service_id));

$ac_url="index.php?do=model&model_id=7&view=edit&service_id=".$service_id;

$status_arr = $service_obj->get_service_status();

//����༭
if($sbt_edit){
	Keke::admin_system_log($_lang['to_witkey_service_name_is'].$service_info[title].$_lang['in_edit_operate']);

	$service_obj = keke_table_class::get_instance('witkey_service');	
    $service=Keke::escape($service); 
	$res = $service_obj->save($service,array("service_id"=>$service_id));
	$res and Keke::admin_show_msg($_lang['service_edit_success'],'index.php?do=model&model_id=7&view=list',2,$_lang['service_edit_success'],'success') or Keke::admin_show_msg($_lang['service_edit_fail'],'index.php?do=model&model_id=7&view=edit&service_id='.$service_id,2,$_lang['service_edit_fail'],'warning');
}


require Keke_tpl::template ( 'shop/'.$model_info['model_dir'].'/control/admin/tpl/service_' . $view );