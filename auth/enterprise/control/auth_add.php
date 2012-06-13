<?php

/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-9-01下午2:37:13
 */
defined ( 'IN_KEKE' ) or exit('Access Denied');
$page_title= $_lang['enterprise_auth'];
$step_arr=array("step1"=>array( $_lang['step_one'], $_lang['auth_intro']),
				"step2"=>array( $_lang['step_two'], $_lang['fill_in_enterprise_info']),
				"step3"=>array( $_lang['step_three'], $_lang['background_exam_and_verify']),
				"step4"=>array( $_lang['step_four'], $_lang['auth_pass']));

$auth_step= keke_auth_enterprise_class::get_auth_step($auth_step,$auth_info);
//$verify = Keke::reset_secode_session($ver?0:1);//安全码输入
$verify   = 0;
$ac_url = $origin_url . "&op=$op&auth_code=$auth_code&ver=".intval($ver);
switch ($auth_step){
	case "step1":
		break;
	case "step2":		
		$sbt_add and $auth_obj->add_auth($fds,'licen_pic');//认证申请提交
		break;
	case "step3":
		break;
}

require keke_tpl_class::template ( 'auth/' . $auth_dir . '/tpl/' . $_K ['template'] . '/auth_add' );