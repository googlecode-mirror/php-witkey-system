<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * @since control/admin/admin_trans_process
 * 2011-11-01 11:31:34
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
//ʵ�����������
$process_obj=preward_report_class::get_instance($report_id);
$report_info = $process_obj->_report_info;
$user_info = $process_obj->_user_info;
$to_userinfo  = $process_obj->_to_user_info;
$process_can = $process_obj->_process_can;
$credit_info = $process_obj->_credit_info;
$cash = $process_obj->_obj_info['cash']; 

$url = "index.php?do=trans&view=process&type=$type&report_id=$report_id";
if(!empty($op_result)){
	switch ($type) {
		case "rights"://άȨ
				
			$res=$process_obj->process_rights($op_result,$type);
			break;
		case "report"://�ٱ�
			$res=$process_obj->process_report($op_result,$type);
			 if($op_result['action']=='pass'){
				$res  and kekezu::admin_show_msg($_lang['operate_notice'],$url,3,$action_arr[$type]['1'].$_lang['operation_completed'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url,3,$action_arr[$type]['1'].$_lang['operate_fail'],'warning');
			}else{
				//var_dump($action_arr[$type]['1'].$_lang['operation_completed']);die;
				$url = "index.php?do=trans&view=report&type=$type&report_status=3";
				$res  and kekezu::admin_show_msg($_lang['operate_notice'],$url,3,$action_arr[$type]['1'].$_lang['operation_completed'],'success') or kekezu::admin_show_msg($_lang['operate_notice'],$url,3,$action_arr[$type]['1'].$_lang['operate_fail'],'warning');
			} 
			break;
		case "complaint"://Ͷ��
			break;
	}
} 

/* $process_obj=sreward_report_class::get_instance($report_id,$report_info,$obj_info,$user_info,$to_userinfo);//ʵ�����������
 if($op_result){
	//echo 1;die;
	switch ($type){
		case "rights"://άȨ
			
			$res=$process_obj->process_rights($op_result,$type);
			break;
		case "report"://�ٱ�
			
			$res=$process_obj->process_report($op_result,$type);
			break;
		case "complaint"://Ͷ��
			break;
	}
}else{
	$gz_info  =$process_obj->user_role('gz');//������Ϣ
	$wk_info  =$process_obj->user_role('wk');//������Ϣ
	$credit_info=$process_obj->_credit_info;//�۳�������������Ϣ
	$process_can=$process_obj->_process_can;//���Խ��еĴ�����
} */


require keke_tpl_class::template ( 'task/' . $model_info ['model_dir'] . "/control/admin/tpl/task_$view");