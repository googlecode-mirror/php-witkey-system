<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.1
 * 2012-10-9 ����17��30
 */
class Control_admin_user_trans extends Controller{
	private $_action_arr ;
	private $_trans_status;
	private $_trans_object;
	
	function __construct(){
		//��ѯ�ٱ���άȨ��Ͷ������
		$this->_action_arr  = keke_report_class::get_transrights_type();
		//��������
		$this->_trans_status =  keke_report_class::get_transrights_status();
		$this->_trans_object = keke_report_class::get_transrights_obj();
	}
	
	function action_index($type=NULL,$report_status=NULL){
		global $_K,$_lang;
		//��Ҫ���б�����ʾ���ֶ�
		$fields = '`report_id`,`obj`,`username`,`to_username`,`report_file`,`on_time`,`report_status`,`op_uid`,`op_username`,`report_desc`';
		//�����в�ѯ���ֶ�
		$query_fields = array('report_id'=>$_lang['id'],'report_status'=>'��ǰ״̬','on_time'=>$_lang['time']);
		//����uri
		$base_uri = BASE_URL.'/index.php/admin/user_trans';
		$del_uri = $base_uri.'/del';
		//ͳ�Ƶ�����������д�������ٲ�ѯһ��
		$count = intval($_GET['count']);
		//Ĭ�������ֶ�
		$this->_default_ord_field = 'on_time';
		//��ȡ��ҳ����
		extract($this->get_url($base_uri));
		//��ѯ�ٱ���άȨ��Ͷ������
		$action_arr =  $this->_action_arr; //keke_report_class::get_transrights_type();
		//��������
		$trans_status = $this->_trans_status; //keke_report_class::get_transrights_status();
		//��ȡ����Ĳ���type������report��rights��complaint
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'report';
		}
		if($report_status){
			$where .= " and report_status = '$report_status'";
			$uri .="&report_status ='$report_status'"; 
		}
		//������ת��Ϊ���ݿ�洢�ֶ�1,2,3
		$rp_type = $action_arr[$type][0];
		//��Ӳ�ѯ����
		$where .= " and  report_type = '$rp_type' ";
		//���url��������������άȨ��ȥ����Ͷ��
		$uri .= "&type=$type";
		//��ѯ���������µ�����
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//��ѯ����������
		$report_list = $data_info['data'];
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		//�����������Ʒ�����񣬸��������
		$trans_object = $this->_trans_object;
// 		var_dump($trans_object);die;
		require keke_tpl::template('control/admin/tpl/user/trans');
	}
	/**
	 * һ�������ֱ��Ǿٱ�������
	 */
	function action_report(){
		$report_status = $_GET['report_status'];
	 	$this->action_index('report',$report_status);
	}
	function action_rights(){
		$report_status = $_GET['report_status'];
		$this->action_index('rights',$report_status);
	}
	function action_complaint(){
		$report_status = $_GET['report_status'];
		$this->action_index('complaint',$report_status);
	} 
	/**
	 * ����ٱ��Ͳ鿴������
	 */
	function action_process(){
		global $_K,$_lang;
		//��ȡ��������type,�û����ض�Ӧ�������б���ٱ��б�
		$type = $_GET['type'];
		//��ѯ�ٱ���άȨ��Ͷ������
		$action_arr = $this->_action_arr;//keke_report_class::get_transrights_type();
		//���͵ĺ��֣��ٱ���άȨ��Ͷ��
		$rep_type_chinese = $action_arr[$_GET['type']][1];
		//��ȡ���ݹ����Ķ�report_id��������ѯ��Ӧ��report�����Ϣ
		$report_id = $_GET['report_id'];
		//��Ӧ����Ϣ
		$report_info = keke_report_class::get_report_info ( $report_id );
		//�ٱ�����Ϣ
		$user_info = keke_user_class::get_user_info ( $report_info ['uid'] ); 
		//�Է���Ϣ
		$to_userinfo = keke_user_class::get_user_info ( $report_info ['to_uid'] );
		//��ȡprocessҳ�����Ϣ
		$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);
		//�����������Ʒ�����񣬸��������
		$trans_object = $this->_trans_status;//keke_report_class::get_transrights_obj();
		//��������
		$trans_status = $this->_trans_status;
// 		var_dump($report_info['obj']);die;
		require keke_tpl::template('control/admin/tpl/user/trans_process');
	}
	/**
	 * ɾ�������ٱ���Ϣ
	 */
	function action_del(){
		$report_id = $_GET['report_id'];
		if ($report_id){
			$where .=' report_id ='.$report_id;
		}
		echo Model::factory('witkey_report')->setWhere($where)->del();
	}
}
/* Keke::admin_check_role(80);
$views = array ("rights", "report", "complaint", "process" );
in_array ( $view, $views ) or $view = "rights";

$action_arr    = keke_report_class::get_transrights_type(); *//**����άȨ����**/
/* $trans_status = keke_report_class::get_transrights_status(); //����άȨ״̬
$trans_object = keke_report_class::get_transrights_obj(); //����άȨ����
$page and $page=intval ( $page ) or $page = '1';
$page_size and $page_size=intval ( $page_size ) or $page_size = "10";
$url = "index.php?do=$do&view=$view&report_status=$report_status&obj=$obj&ord=$ord&page_size=$page_size&page=$page";
//die('1');
if ($ac) {
	switch ($ac) {
		//die('1');
		case "del" :
			if ($report_id) {
				$res = Dbfactory::execute ( sprintf ( " delete from %switkey_report where report_id='%d'", TABLEPRE, $report_id ) );
				$res and Keke::admin_show_msg ( $_lang['record_delete_success'], $url, "3",'','success' ) or Keke::admin_show_msg ($action_arr[$view]. $_lang['record_delete_fail'], $url, "3",'','warning');
			} else
				Keke::admin_show_msg ($_lang['choose_delete_operate'], $url, "3",'','warning' );
			break;
		case "download" :
			keke_file_class::file_down ( $filename, $filepath );
			break;
	}

} elseif ($sbt_action) {
	
	$ckb and $dels = implode ( ",", $ckb ) or $dels = array ();
	if (! empty ( $dels )) {
		$res = Dbfactory::execute ( sprintf ( " delete from %switkey_report where report_id in ('%s') ", TABLEPRE, $dels ) );
		$res and Keke::admin_show_msg ( $action_arr[$view].$_lang['record_mulit_delete_success'], $url, "3",'','success' ) or Keke::admin_show_msg ( $action_arr[$view].$_lang['record_delete_fail'], $url, "3",'','warning' );
	} else
		Keke::admin_show_msg ($_lang['choose_delete_operate'], $url, "3",'','warning' );

} else {
	
	$report_obj = new Keke_witkey_report_class ();
	$page_obj = $Keke->_page_obj;
	
	$where = " report_type = '" . $action_arr [$view] ['0'] . "'";
	$report_id and $where .= " and report_id='$report_id'";
	$report_status and $where .= " and report_status='$report_status' ";
	$obj and $where .= " and obj='$obj' ";

	is_array($w['ord']) and $where .=' order by '.$ord['0'].' '.$ord['1']  or $where .= " order by report_id desc ";
	$report_obj->setWhere ( $where );
	$count = intval ( $report_obj->count_keke_witkey_report () );
	$page_obj->setAjax(1);
	$page_obj->setAjaxDom("ajax_dom");
	$pages = $page_obj->getPages ( $count, $page_size, $page, $url );
	
	$report_obj->setWhere ( $where . $pages ['where'] );
	$report_list = $report_obj->query_keke_witkey_report ();
}

if ($view != 'process') {
	require keke_tpl_class::template ( 'control/admin/tpl/admin_trans_rights' );
} else {

	//var_dump(ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php');die();
	require ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php';
} 
$report_info = keke_report_class::get_report_info ( $report_id );

$report_info or Keke::admin_show_msg ( $_lang['parameters_error_not_exist'] . $action_arr [$type] [1] . $_lang['record'], "index.php?do=trans&view=$type",3,'','warning' );

$user_info = Keke::get_user_info ( $report_info ['uid'] ); //�ٱ�����Ϣ
$to_userinfo = Keke::get_user_info ( $report_info ['to_uid'] ); //�Է���Ϣ

$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);

$ac == 'download' and keke_file_class::file_down ( $filename, $filepath ); //�ļ�����

if ($type == 'complaint') { //Ͷ��
	//$obj_info or Keke::admin_show_msg($_lang['friendly_notice'],'index.php?do=trans&view=complaint',3,$_lang['deal_object_del'],'warning');
	if ($sbt_op) {
		//����Ͷ��
		$op_result[action]=='pass' and $report_status = 4 or $report_status=3;
		$url = "index.php?do=$do&view=$type&report_status=$report_status";
		$res = keke_report_class::sub_process_ts ($report_info,$user_info,$to_userinfo,$op_result );
		$res and Keke::admin_show_msg ($_lang['operate_notice'], $url, "2", $_lang['process_success'],'success') or Keke::admin_show_msg ( $_lang['operate_notice'], $url, "2",$_lang['operate_over'], 'warning' );
	} else {
		$report_info = keke_report_class::get_report_info ( $report_id );
	}
	require keke_tpl_class::template ( 'control/admin/tpl/admin_trans_process' );
} else {
	/**
	 * ��ת����Ӧģ�͵�admin_route����ģ������Ŀ��Ʋ㴦�����ҵ��
	 */
	//���һ��ʵ�ת��
	//var_dump($op_result);die();
	/*if(empty($obj_info)||empty($obj_info ['model_id']))
	{
		Keke::admin_show_msg($_lang['friendly_notice'],'index.php?do=trans&view=report',3,$_lang['deal_object_del'],'warning');
	}
	$report_info = keke_report_class::get_report_info ( $report_id );

	//if(empty($obj_info ['model_id']) or Keke::admin_show_msg($_lang['friendly_notice'],'index.php?do=trans&view=report',3,$_lang['deal_object_del'],'warning');
	$model_info = $Keke->_model_list [$obj_info ['model_id']];
	//var_dump($model_info);die();
	$path =  S_ROOT .$model_info ['model_type'] . "/" . $model_info ['model_dir'] . "/control/admin/admin_route.php";
	require $path;*/

