<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.1
 * 2012-10-9 下午17：30
 */
class Control_admin_user_trans extends Controller{
	private $_action_arr ;
	private $_trans_status;
	private $_trans_object;
	
	function __construct(){
		//查询举报，维权，投诉类型
		$this->_action_arr  = keke_report_class::get_transrights_type();
		//处理的情况
		$this->_trans_status =  keke_report_class::get_transrights_status();
		$this->_trans_object = keke_report_class::get_transrights_obj();
	}
	
	function action_index($type=NULL,$report_status=NULL){
		global $_K,$_lang;
		//需要在列表中显示的字段
		$fields = '`report_id`,`obj`,`username`,`to_username`,`report_file`,`on_time`,`report_status`,`op_uid`,`op_username`,`report_desc`';
		//搜索中查询的字段
		$query_fields = array('report_id'=>$_lang['id'],'report_status'=>'当前状态','on_time'=>$_lang['time']);
		//基本uri
		$base_uri = BASE_URL.'/index.php/admin/user_trans';
		$del_uri = $base_uri.'/del';
		//统计的总数，这里写出避免再查询一次
		$count = intval($_GET['count']);
		//默认排序字段
		$this->_default_ord_field = 'on_time';
		//获取分页条件
		extract($this->get_url($base_uri));
		//查询举报，维权，投诉类型
		$action_arr =  $this->_action_arr; //keke_report_class::get_transrights_type();
		//处理的情况
		$trans_status = $this->_trans_status; //keke_report_class::get_transrights_status();
		//获取后面的参数type，包括report，rights，complaint
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'report';
		}
		if($report_status){
			$where .= " and report_status = '$report_status'";
			$uri .="&report_status ='$report_status'"; 
		}
		//将类型转化为数据库存储字段1,2,3
		$rp_type = $action_arr[$type][0];
		//添加查询条件
		$where .= " and  report_type = '$rp_type' ";
		//添加url参数，用来区分维权，去报，投诉
		$uri .= "&type=$type";
		//查询各种类型下的数据
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//查询出来的数据
		$report_list = $data_info['data'];
		//显示分页的页数
		$pages = $data_info['pages'];
		//所属的类别，商品，任务，稿件，订单
		$trans_object = $this->_trans_object;
// 		var_dump($trans_object);die;
		require keke_tpl::template('control/admin/tpl/user/trans');
	}
	/**
	 * 一下三个分别是举报的三类
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
	 * 处理举报和查看处理方案
	 */
	function action_process(){
		global $_K,$_lang;
		//获取穿过来的type,用户返回对应的类型列表，如举报列表
		$type = $_GET['type'];
		//查询举报，维权，投诉类型
		$action_arr = $this->_action_arr;//keke_report_class::get_transrights_type();
		//类型的汉字，举报，维权，投诉
		$rep_type_chinese = $action_arr[$_GET['type']][1];
		//获取传递过来的额report_id，用来查询对应的report表的信息
		$report_id = $_GET['report_id'];
		//对应的信息
		$report_info = keke_report_class::get_report_info ( $report_id );
		//举报方信息
		$user_info = keke_user_class::get_user_info ( $report_info ['uid'] ); 
		//对方信息
		$to_userinfo = keke_user_class::get_user_info ( $report_info ['to_uid'] );
		//获取process页面的信息
		$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);
		//所属的类别，商品，任务，稿件，订单
		$trans_object = $this->_trans_status;//keke_report_class::get_transrights_obj();
		//处理的情况
		$trans_status = $this->_trans_status;
// 		var_dump($report_info['obj']);die;
		require keke_tpl::template('control/admin/tpl/user/trans_process');
	}
	/**
	 * 删除单条举报信息
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

$action_arr    = keke_report_class::get_transrights_type(); *//**交易维权类型**/
/* $trans_status = keke_report_class::get_transrights_status(); //交易维权状态
$trans_object = keke_report_class::get_transrights_obj(); //交易维权对象
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

$user_info = Keke::get_user_info ( $report_info ['uid'] ); //举报方信息
$to_userinfo = Keke::get_user_info ( $report_info ['to_uid'] ); //对方信息

$obj_info = keke_report_class::obj_info_init ( $report_info,$user_info);

$ac == 'download' and keke_file_class::file_down ( $filename, $filepath ); //文件下载

if ($type == 'complaint') { //投诉
	//$obj_info or Keke::admin_show_msg($_lang['friendly_notice'],'index.php?do=trans&view=complaint',3,$_lang['deal_object_del'],'warning');
	if ($sbt_op) {
		//处理投诉
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
	 * 跳转到对应模型的admin_route，由模型下面的控制层处理具体业务
	 */
	//货币汇率的转换
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

