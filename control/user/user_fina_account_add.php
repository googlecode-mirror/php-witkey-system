<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8下午06:42:39
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

$user_info = kekezu::get_user_info($uid);
$step_list=array(
				
				"step1"=>array($_lang['step_one'], $_lang['select_an_account_type']), 
				"step2"=>array($_lang['step_two'], $_lang['complete_bank_account_info']), 
				"step3"=>array($_lang['step_three'], $_lang['account_setting_successful'])); 
$step or ($_SESSION['bank_zone'] and $step='step2' or $step='step1');
$ac_url=$origin_url."&op=$op&opp=$opp";
switch ($step){
	case "step1":
		break;
	case "step2":
		/**企业认证检测**/
		$enterprise_auth=keke_auth_fac_class::auth_check('enterprise', $uid);
		
		break;
	case "step3":
		/**实名认证检测**/
		$real_pass=keke_auth_fac_class::auth_check("realname", $uid);
		!$real_pass&&$bank_type=='1' and   kekezu::show_msg( $_lang['realname_auth_not_pass'] ,"index.php?do=user&view=payitem&op=auth&auth_code=realname#userCenter","3",'','warning');
		
		/**企业认证检测**/
		$enterprise_auth=keke_auth_fac_class::auth_check('enterprise', $uid);
		!$enterprise_auth&&$bank_type=='2' and  kekezu::show_msg( $_lang['enterprise_auth_not_pass'],$ac_url."#userCenter","3",'','warning');
		
		$bank_arr=keke_global_class::get_bank();//银行列表
		$bank_zone=$_SESSION['bank_zone'];//银行地区
		$bank_detail=$_SESSION['bank_zone_detail'];//银行详细地区所在
		
		//卡号验证
		if($check_card){
			$card_exists=db_factory::get_count(sprintf(" select card_num from %switkey_member_bank where bind_status='1' and card_num='%d'",TABLEPRE,$check_card));
			if($card_exists){
				$notice= $_lang['this_account_has_been_bound_to_others'];
				CHARSET=='gbk' and $notice=kekezu::gbktoutf($notice);
				echo $notice;
			} 
			else echo true;
			die();
		}
		
		if(kekezu::submitcheck($formhash)){
			$conf or kekezu::show_msg( $_lang['submit_fail_retry_later'],$ac_url."&step=step3&bank_type=$bank_type#userCenter","3",'','warning');;
			$bank_obj=keke_table_class::get_instance("witkey_member_bank");
			$conf['uid']		 =$uid;	
			$conf['bank_address']=$province.",".$city.','.$area;
			$conf['bank_type']   =$bank_type;
			$conf['on_time']     = time();
			$conf = kekezu::escape($conf);
			$bank_id=$bank_obj->save($conf);
			
			if($bank_id){
				db_factory::execute(sprintf(" update %switkey_member_bank set bind_status='1' where bank_id='%d'",TABLEPRE,$bank_id));
				unset($_SESSION['bank_zone']);
				unset($_SESSION['bank_zone_detail']);
				kekezu::show_msg($_lang['account_binding_successful'],$ac_url."&step=step4&bank_type=$bank_type&bank_id=$bank_id#userCenter","3",'','success');
			}else{
				kekezu::show_msg( $_lang['account_binding_fail'],$ac_url."&step=step3&bank_type=$bank_type#userCenter","3",'','warning');
			}
		
		}
		break;
	case "step4":
		$bank_arr=keke_global_class::get_bank();//银行列表
		$bank_info=db_factory::get_one(sprintf(" select * from %switkey_member_bank where bank_id='%d' and uid='%d' and bind_status='1' ",TABLEPRE,$bank_id,$uid));
		$bank_info or kekezu::show_msg( $_lang['no_binding_account_please_bind'],"$ac_url&step=step1","3","","warning");
		break;
}

 
require keke_tpl_class::template ( "user/" . $do . "_" . $op."_".$opp );


