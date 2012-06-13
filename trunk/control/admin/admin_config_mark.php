<?php
/**
 * ������������
 * @copyright keke-tech
 * @author Aqing
 * @version v 2.0
 * 2010-08-29 14:37:34
 */
defined ( "ADMIN_KEKE" ) or exit ( "Access Denied" );
Keke::admin_check_role ( 33 );
$url = "index.php?do=$do&view=$view&mark_rule_id=$mark_rule_id";

$mark_rule_obj = new Keke_witkey_mark_rule_class ();
if (isset ( $op )) {
	switch ($op) {
		case "edit" : //�༭
			if (intval ( $mark_rule_id )) {
				$mark_rule_obj->setWhere ( " mark_rule_id  =  " . $mark_rule_id . "" );
				$mark_info = $mark_rule_obj->query_keke_witkey_mark_rule ();
				$mark_info = $mark_info ['0'];
			}
			require Keke::$_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_edit" );
			break;
		case "del" :
			intval ( $mark_rule_id ) or Keke::admin_show_msg ($_lang['parameter_error_fail_to_delete'], $url,3,'','warning' );
			$mark_rule_obj->setWhere ( " mark_rule_id  =  " . $mark_rule_id . "" );
			$res = $mark_rule_obj->del_keke_witkey_mark_rule ();
			Keke::admin_system_log ($_lang['delete_credit_rules']);
			$res < 1 and Keke::admin_show_msg ($_lang['delete_fail'], $url,3,'','warning' ) or Keke::admin_show_msg ( $_lang['success_delete_a_credit_rules'], $url,3,'','success' );
			break;
		case "config":
			Keke::admin_check_role(78);
			require ADMIN_ROOT . 'admin_config_' . $view . '_'.$op.'.php';
			break;
		case "config_add":
			Keke::admin_check_role(78);
			require ADMIN_ROOT . 'admin_config_' . $view . '_'.$op.'.php';
			break;
		case "log":
			Keke::admin_check_role(79);
		   require ADMIN_ROOT . 'admin_config_' . $view . '_'.$op.'.php';
			break;
	}
} elseif ($is_submit=='1'){    //�༭
	intval ( $hdn_mark_rule_id ) and $mark_rule_obj->setWhere ( " mark_rule_id = " . intval ( $hdn_mark_rule_id ) . "" );
	$mark_rule_obj->setM_value(intval( $txt_m_value ));
	$mark_rule_obj->setG_value(intval($txt_g_value));
	$mark_rule_obj->setG_title ( $txt_g_title );
	$mark_rule_obj->setM_title ( $txt_m_title );
	$mark_rule_obj->setG_ico($hdn_g_ico);
	$mark_rule_obj->setM_ico($hdn_m_ico);
	if(intval ( $hdn_mark_rule_id )){
		Keke::admin_system_log($_lang['edit_mark_rule']);
	 	$res = $mark_rule_obj->edit_keke_witkey_mark_rule () ;
	}else{
		Keke::admin_system_log($_lang['create_mark_rule']);
		 $res = $mark_rule_obj->create_keke_witkey_mark_rule ();
	}
	 
	$res  and Keke::admin_show_msg ($_lang['operate_notice'], $url,2,$_lang['submit_success'],'success') or Keke::admin_show_msg ($_lang['operate_notice'], $url,2,$_lang['submit_fail'],'warning' );
} else {//�б�
	 
	$mark_rule = $mark_rule_obj->query_keke_witkey_mark_rule ();
	require Keke::$_tpl_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );
}