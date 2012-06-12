<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2010-11-23 下午16:59:00
 */
defined ( 'IN_KEKE' ) or exit('Access Denied');
$agree_obj		 = sreward_task_agreement::get_instance($agree_id);
$agree_info		 = $agree_obj->_agree_info;//协议内容
$buyer_uid       = $agree_obj->_buyer_uid;//买家编号
$seller_uid       = $agree_obj->_seller_uid;//卖家编号
$buyer_username  = $agree_obj->_buyer_username;//买家(雇主)姓名
$seller_username = $agree_obj->_seller_username;//买家(雇主)姓名
$agree_status 	 = $agree_obj->_agree_status;//协议状态
$buyer_status	 = $agree_obj->_buyer_status;//买家状态
$seller_status	 = $agree_obj->_seller_status;//卖家状态
$process_can     = $agree_obj->process_can();//可操作动作
$user_type 		 = $agree_obj->_user_role;/** 用户角色判断**/
$step			 = $agree_obj->stage_access_check($user_type);//协议当前步骤
$stage_nav		 = $agree_obj->agreement_stage_nav();//交付阶段导航
$basic_url		 = 'index.php?do='.$do.'&agree_id='.$agree_id.'&step='.$step;
$task_status     = $agree_obj->_trust_info['task_status'];
switch ($step){
	case "step1"://第一步、协议签署
		$op == 'sign' and $agree_obj->agreement_stage_one($user_type,'','json');
		break;
	case "step2"://具体交付
		$buyer_contact	   = $agree_obj->_buyer_contact;//买家联系方式
		$buyer_status_arr  = $agree_obj->get_buyer_status();//买家协议状态
		$seller_contact	   = $agree_obj->_seller_contact;//卖家联系方式
		$seller_status_arr = $agree_obj->get_seller_status();//卖家协议状态
		$stage_list        = $agree_obj->agreement_stage_list($user_type);//当前用户的协议阶段描述
		$file_list         = $agree_obj->get_file_list();//交付附件
		$trust_mode        = $agree_obj->_trust_info['is_trust'];//担保模式
		switch ($op){
			case "report":
				$title=$_lang['zc_submit'];
				if($sbt_edit){
					$agree_obj->set_report ( $obj, $obj_id, $to_uid,$to_username, $type, $file_url, $tar_content);
				}else{
					require keke_tpl_class::template("report");
				}die();
				break;
			case "confirm":
				$agree_obj->upfile_confirm($file_str,$basic_url);
				break;
			case "accept":
				$agree_obj->accept_confirm('','json');
				break;
		}
		break;
	case "step3"://双方互评
		switch ($op){
			case "mark":
				$title = $_lang['each_mark'];
				$model_code = $agree_obj->_model_code;
				$obj_id     = $agree_info['work_id'];
				$role_type = $user_type;
				require S_ROOT.'control/mark.php';
				die();
				break;
		}
		break;
}
$page_title=$agree_info['agree_title'].'--'.$_K['html_title'];
 

require keke_tpl_class::template("task/".$model_info['model_dir']."/tpl/".$_K['template']."/agreement/agreement_".$step);
