<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 1.4
 * 2011-9-19上午10:15:13
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
kekezu::admin_check_role(67);
require '../../keke_client/sms/postmsg.php';

$account_info=kekezu::$_sys_config;//手机账号信息
$mobile_u=$account_info['mobile_username'];
$mobile_p=$account_info['mobile_password'];
switch ($ac){
	case "ser":
		$type=='uid' and $where=" uid='$u' " or $where=" INSTR(username,'$u')>0 ";
		$user_info=db_factory::get_one(" select uid,username,phone,mobile from ".TABLEPRE."witkey_space where $where ");
		if(!$user_info){
			kekezu::echojson($_lang['he_came_from_mars'],'3');die();
		}else{
			if(!$user_info['mobile']){
				kekezu::echojson($_lang['no_record_of_his_cellphone'],'2');die();
			}else{
				kekezu::echojson($user_info['mobile'],'1');die();
			}
		}
		break;
	case "send":
		$tar_content=strip_tags($tar_content);
		switch ($slt_type=='specify'){
			case "1":
				strpos($txt_tel, ",")>0 and $tel=explode(",",$txt_tel) or $tel=$txt_tel;
				
				is_array($tel) and $res=Msg_PostBlockNumber($mobile_u, $mobile_p, $tel, $tar_content,'') or $res=Msg_PostSingle($mobile_u, $mobile_p, $tel, $tar_content,'');
				
				kekezu::admin_show_msg(Desc_ReturnInfo($res),"index.php?do=$do&view=$view",3,'','success');
				break;
			case "0":
				$slt_type=='vip' and $where="isvip='1'" or $where="isvip!='1'";
				$tel_arr=db_factory::query(" select mobile from ".TABLEPRE."witkey_space where $where and mobile is not null ");
				$tel_group=array();
				foreach ($tel_arr as $v){
					$tel_group[]=$v['mobile'];
				}
				kekezu::admin_system_log($_lang['parameters']);
				$res=Msg_PostBlockNumber($mobile_u, $mobile_p, $tel_group, $tar_content,'');
				kekezu::admin_show_msg(Desc_ReturnInfo($res),"index.php?do=$do&view=$view",3,'','success');
				break;
		}
		break;
}
require $template_obj->template('control/admin/tpl/admin_'.$do.'_'.$view);