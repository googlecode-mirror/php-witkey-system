<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-11-01 11:31:34
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

$process_obj=goods_report_class::get_instance($report_id,$report_info,$obj_info,$user_info,$to_userinfo);//ʵ�����������

if(!empty($op_result) ){
//	$log_type_arr = array("rights"=>"άȨ","report"=>"�ٱ�","complaint"=>"Ͷ��");
//	$log_msg = "��������Ʒ������".$log_type_arr[$op_result]."����";
//	kekezu::admin_system_log($log_msg);
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
}
require keke_tpl_class::template ( 'shop/' . $model_info ['model_dir'] . '/control/admin/tpl/goods_' . $view );