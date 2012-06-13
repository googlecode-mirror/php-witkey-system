<?php

/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-9-01����2:37:13
 */
defined ( 'IN_KEKE' ) or exit('Access Denied');
$page_title= $_lang['mobi_auth'];
$step_arr=array("step1"=>array( $_lang['step_one'], $_lang['enter_cellphone_num']),
				"step2"=>array( $_lang['step_two'], $_lang['check_in_cellphone_num']),
				"step3"=>array( $_lang['step_three'], $_lang['auth_pass']));

$auth_step= keke_auth_email_class::get_auth_step($auth_step,$auth_info);

$verify = Keke::reset_secode_session($ver?0:1);//��ȫ������
$ac_url = $origin_url . "&op=$op&auth_code=$auth_code&ver=".intval($ver);

switch ($auth_step){
	case "step1": 
		$sbt_add==1 and $auth_obj->add_auth($fds);//��֤�����ύ
		break;
	case "step2":
		//��ʼ��֤
		$sbt_add and $auth_obj->valid_auth($fds);
		break;
	case "step3":
		break;
}
require keke_tpl_class::template ( 'auth/' . $auth_dir . '/tpl/' . $_K ['template'] . '/auth_add' );