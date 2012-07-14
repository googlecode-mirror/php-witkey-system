<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2010-10-31 ����13��30
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$report_info = keke_report_class::get_report_info ( $report_id );
$report_info or Keke::admin_show_msg ( $_lang['parameters_error_not_exist'] . $action_arr [$type] [1] . $_lang['record'], "index.php?do=trans&view=$type",3,'','warning' );
$user_info = Keke::get_user_info ( $report_info ['uid'] ); //�ٱ�����Ϣ
$to_userinfo = Keke::get_user_info ( $report_info ['to_uid'] ); //�Է���Ϣ

$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);
 
$ac == 'download' and keke_file_class::file_down ( $filename, $filepath ); //�ļ�����

if ($type == 'complaint') { //Ͷ��
	$obj_info or Keke::admin_show_msg($_lang['friendly_notice'],'index.php?do=trans&view=complaint',3,$_lang['deal_object_del'],'warning');
	if ($sbt_op) {
		//����Ͷ��
		$op_result[action]=='pass' and $report_status = 4 or $report_status=3;		
		$url = "index.php?do=$do&view=$type&report_status=$report_status";		
		$res = keke_report_class::sub_process_ts ($report_info,$user_info,$to_userinfo,$op_result );
		$res and Keke::admin_show_msg ($_lang['operate_notice'], $url, "2", $_lang['process_success'],'success') or Keke::admin_show_msg ( $_lang['operate_notice'], $url, "2",$_lang['operate_over'], 'warning' );
	} else {
		$report_info = keke_report_class::get_report_info ( $report_id );
	}
	require Keke_tpl::template ( 'control/admin/tpl/admin_trans_process' );
} else {
	/**
	 * ��ת����Ӧģ�͵�admin_route����ģ������Ŀ��Ʋ㴦�����ҵ��
	 */
	 $obj_info or Keke::admin_show_msg($_lang['friendly_notice'],'index.php?do=trans&view=report',3,$_lang['deal_object_del'],'warning');
	$report_info = keke_report_class::get_report_info ( $report_id );

	$model_info = Keke::$_model_list [$obj_info ['model_id']];
	
	$path =  S_ROOT .$model_info ['model_type'] . "/" . $model_info ['model_dir'] . "/control/admin/admin_route.php";
	
	require $path;
}