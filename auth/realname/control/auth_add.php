<?php

/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-9-01下午2:37:13
 */
defined ( 'IN_KEKE' ) or exit('Access Denied');
$page_title= $_lang['realname_auth'];
$step_arr=array("step1"=>array( $_lang['step_one'], $_lang['auth_intro']),
				"step2"=>array( $_lang['step_two'], $_lang['fill_in_realname_auth_info']),
				"step3"=>array( $_lang['step_three'], $_lang['waiting_for_background_check']),
				"step4"=>array( $_lang['step_four'], $_lang['background_check_pass']));
 

$auth_step= keke_auth_realname_class::get_auth_step($auth_step,$auth_info);
//$verify = kekezu::reset_secode_session($ver?0:1);//安全码输入
$verify   = 0;
$ac_url = $origin_url . "&op=$op&auth_code=$auth_code&ver=".intval($ver);

switch ($auth_step){
	case "step1":
		break;
	case "step2":
		$sbt_add and $auth_obj->add_auth($fds,'id_pic');//认证申请提交
		break;
	case "step3":
		
		break;
	case "step4":
		break;
}

require keke_tpl_class::template ( 'auth/' . $auth_dir . '/tpl/' . $_K ['template'] . '/auth_add' );