<?php
/**
 * ������ӣ��ж�������͹�������û��ֱ�����
 * @copyright keke-tech
 * @author S
 * @version kppw 2.0
 * 2011-12-14
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$case_obj = new Keke_witkey_case_class ();
$task_obj = new Keke_witkey_task_class ();
 
$case_id and $case_info = dbfactory::get_one ( " select * from " . TABLEPRE . "witkey_case where case_id ='$case_id'" );

$txt_task_id and $case_info = dbfactory::get_one ( " select * from " . TABLEPRE . "witkey_task where task_id = '$txt_task_id'" );

$url ="index.php?do=case&view=list" ;

//��ѯ�������
if ($ac == 'ajax' && $id&&$obj) {
	case_obj_exists ( $id, $obj ) and kekezu::echojson ( $_lang['echojson_msg'],1 ) or kekezu::echojson ( $_lang['echojosn_erreor_msg'],0 );
}

//�༭����Ӱ���
if (isset ( $sbt_edit )) { //�༭
	if ($hdn_case_id) {
		$case_obj->setCase_id ( $hdn_case_id );
	}else{
			if (case_obj_exists($fds['obj_id'],$case_type)) {
			$case_obj->setObj_id ( $fds ['obj_id'] );
			}
	}
	$case_obj->setObj_type ( $case_type );
	$case_obj->setCase_auther ( $fds ['case_auther'] );
	$case_obj->setCase_price ( $fds ['case_price'] );
	$case_obj->setCase_desc ( kekezu::escape($fds ['case_desc']) );
	$case_obj->setCase_title ( kekezu::escape($fds ['case_title']) );
	$case_obj->setOn_time ( time () );
	//������ϴ��ļ�����ѡ���ϴ��ļ�
	($case_img = keke_file_class::upload_file ( "fle_case_img" )) or $case_img = $hdn_case_img;
	$case_obj->setCase_img ($case_img );//�ϴ�ͼƬ
	if ($hdn_case_id) {//�༭
		$res = $case_obj->edit_keke_witkey_case ();
		kekezu::admin_system_log ( $_lang['edit_case'].':' . $hdn_case_id ); //��־��¼
		$res and kekezu::admin_show_msg ( $_lang['modify_case_success'], 'index.php?do=case&view=lise',3,'','success' ) or kekezu::admin_show_msg ( $_lang['modify_case_fail'], 'index.php?do=case&view=lise',3,'','warning' );
	}else{//���
		$res = $case_obj->create_keke_witkey_case ();
		kekezu::admin_system_log ( $_lang['add_case'] ); //��־��¼
		$res and kekezu::admin_show_msg ( $_lang['add_case_success'],'index.php?do=case&view=lise',3,'','success' ) or kekezu::admin_show_msg ( $_lang['add_case_fail'],'index.php?do=case&view=add',3,'','warning' );
	}
	
}

/**
 * �ж�id�Ƿ����
 * @param int $id	����id
 * @param string $obj �������ͣ�������Ʒ��
 */
function case_obj_exists($id, $obj = 'task') {
	if ($obj == 'task') {
		$search_obj = dbfactory::get_count ( sprintf ( "select count(task_id) from %switkey_task where task_id='%d' ", TABLEPRE, $id ) );
	} elseif ($obj =='service') {
		$search_obj = dbfactory::get_count ( sprintf ( "select count(service_id) from %switkey_service where service_id='%d' ", TABLEPRE, $id ) );
	}
	if ($search_obj) {
		return true;
	} else {
		return false;
	}
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );