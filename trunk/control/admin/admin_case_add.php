<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

$case_obj = new Keke_witkey_case_class ();
$task_obj = new Keke_witkey_task_class ();
$case_id and $case_info = Dbfactory::get_one ( " select * from " . TABLEPRE . "witkey_case where case_id ='$case_id'" );
$txt_task_id and $case_info = Dbfactory::get_one ( " select * from " . TABLEPRE . "witkey_task where task_id = '$txt_task_id'" );

$url ="index.php?do=case&view=list" ;
if ($ac == 'ajax' && $id&&$obj) {
	case_obj_exists ( $id, $obj ) and Keke::echojson ( $_lang['echojson_msg'],1 ) or Keke::echojson ( $_lang['echojosn_erreor_msg'],0 );
}

if (isset ( $sbt_edit )) { 

	if ($hdn_case_id) {
		$case_obj->setCase_id ( $hdn_case_id );
	}else{
			if (case_obj_exists($fds['obj_id'],$case_type)) {
			$case_obj->setObj_id ( $fds ['obj_id'] );
			}
	}
	
	//var_dump($_POST);die();
	//var_dump($_FILE);die();
	$case_obj->setObj_type ( $case_type );
	$case_obj->setCase_auther ( $fds ['case_auther'] );
	$case_obj->setCase_price ( $fds ['case_price'] );
	$case_obj->setCase_desc ( Keke::escape($fds ['case_desc']) );
	$case_obj->setCase_title ( Keke::escape($fds ['case_title']) );
	$case_obj->setOn_time ( time () );
	$case_img = $hdn_case_img or ($case_img = keke_file_class::upload_file ( "fle_case_img" ));
	$case_obj->setCase_img ($case_img );
	
	if ($hdn_case_id) {
		$res = $case_obj->edit_keke_witkey_case ();
		Keke::admin_system_log ( $_lang['edit_case'].':' . $hdn_case_id ); 
		$res and Keke::admin_show_msg ( $_lang['modify_case_success'], 'index.php?do=case&view=lise',3,'','success' ) or Keke::admin_show_msg ( $_lang['modify_case_fail'], 'index.php?do=case&view=lise',3,'','warning' );
	}else{
		$res = $case_obj->create_keke_witkey_case ();
		Keke::admin_system_log ( $_lang['add_case'] ); 
		$res and Keke::admin_show_msg ( $_lang['add_case_success'],'index.php?do=case&view=lise',3,'','success' ) or Keke::admin_show_msg ( $_lang['add_case_fail'],'index.php?do=case&view=add',3,'','warning' );
	}
}
function case_obj_exists($id, $obj = 'task') {
	if ($obj == 'task') {
		$search_obj = Dbfactory::get_count ( sprintf ( "select count(task_id) from %switkey_task where task_id='%d' ", TABLEPRE, $id ) );
	} elseif ($obj =='service') {
		$search_obj = Dbfactory::get_count ( sprintf ( "select count(service_id) from %switkey_service where service_id='%d' ", TABLEPRE, $id ) );
	}
	if ($search_obj) {
		return true;
	} else {
		return false;
	}
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );