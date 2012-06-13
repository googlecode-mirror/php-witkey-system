<?php
/**
 * @copyright keke-tech
 * @author shangk
 * @version v 2.0
 * 2010-5-17下午02:29:58
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$dir_name = dirname ( dirname ( dirname ( __FILE__ ) ) ).'/keke_client/keke/';
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'app_comm.php');
require_once $dir_name.'keke_tool_class.php';
require_once $dir_name.'keke_service_class.php';
require_once $dir_name.'config.php';
require_once dirname ( dirname( dirname(__FILE__))).'/lib/helper/keke_xml_class.php';

$page = $page ?  intval( $page ) : 1; 
$page_size = $page_size ? intval ( $page_size ) : 5;
$url = "index.php?do=keke&view=finance&page=$page&page_size=$page_size&fina_type=$fina_type";
//调用函数获得本地行业

//调用接口获得服务器行业列表
$comm_data = array();
$service = 'query_fina';//测试接口

$fina_type or $fina_type = 'in'; 

$fina_type_arr = array('in'=>$_lang['in'],'out'=>$_lang['out']);
$fina_action_arr = array('pub_task'=>$_lang['pub_task'],'task_bid'=>$_lang['task_bid']);
$fina_period_arr = array('week'=>$_lang['this_week'],'month'=>$_lang['this_month'],'year'=>$_lang['a_year'],'half'=>$_lang['half_year']);
/***
 * 请求的参数
 */
if ( $fina_type == 'in' ) {
	$comm_data['fina_action'] = 'task_bid';
}else {
	$comm_data['fina_action'] = 'pub_task';
}
$fina_type and $comm_data['fina_type'] = $fina_type;
$w['fina_period'] and $comm_data ['fina_period'] = $w['fina_period'];


if ($sbt_search){
    $request 	= keke_tool_class::union_build( $config, $service,$comm_data );
	$xml = Keke::curl_request($request,'get');
	$dxml = xml2array($xml);
}
require $template_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );
