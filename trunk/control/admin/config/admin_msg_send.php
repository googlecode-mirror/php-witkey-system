<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 1.4
 * 2011-9-19����10:15:13
 */

Keke::admin_check_role(67);
require '../../keke_client/sms/sms.php';

$account_info=$Keke->_sys_config;//�ֻ��˺���Ϣ
$mobile_u=$account_info['mobile_username'];
$mobile_p=$account_info['mobile_password'];
switch ($ac){
	case "ser":
		$type=='uid' and $where=" uid='$u' " or $where=" INSTR(username,'$u')>0 ";
		$user_info=Dbfactory::get_one(" select uid,username,phone,mobile from ".TABLEPRE."witkey_space where $where ");
		if(!$user_info){
			Keke::echojson($_lang['he_came_from_mars'],'3');die();
		}else{
			if(!$user_info['mobile']){
				Keke::echojson($_lang['no_record_of_his_cellphone'],'2');die();
			}else{
				Keke::echojson($user_info['mobile'],'1');die();
			}
		}
		break;
	case "send":
		$tar_content=strip_tags($tar_content);
		if($slt_type=='normal'){
				$tel_arr=Dbfactory::query(" select mobile from ".TABLEPRE."witkey_space where mobile is not null ");
				$tel_group=array();
				foreach ($tel_arr as $v){
					$tel_group[]=$v['mobile'];
				}
				$txt_tel = implode(",",$tel_group);
		}
		$sms = new sms($txt_tel,$tar_content);
		$m = $sms->send();
		if($m>0){
			Keke::admin_system_log($_lang['sms_send_success']);
			Keke::admin_show_msg($_lang['sms_send_success'],"index.php?do=$do&view=$view",3,'','success');
		}else{
			Keke::admin_show_msg($_lang['sms_send_fail'],"index.php?do=$do&view=$view",3,'','warning');
			
		}
		break;
}
require $template_obj->template('control/admin/tpl/admin_'.$do.'_'.$view);