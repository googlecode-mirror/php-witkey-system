<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-9 12:10
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$cove_arr = Keke::get_table_data("*","witkey_task_cash_cove","","","","","cash_rule_id");
/**
 * 三级横向菜单
 */
$third_nav=array();
foreach ($model_list as $v){
	  $third_nav[]=array(
	  				"1"=>$v['model_id'],
	  				"2"=>$_lang['go_in'].$v['model_name']);
}

if($model_id){
	$model_info=$model_list[$model_id];//模型信息
	switch ($model_info['model_code']){
		case "sreward":
		case "preward":
		case "mreward":
		case "wbzf":
		case "wbdj":
		case "taobao":
			$tab_name="witkey_task_work";
			$time_fds  ="work_time";
			$id_fds    ="work_id";
			$satus_fds="work_status";
			break;
		case "dtender":
		case "tender":
			$tab_name="witkey_task_bid";
			$time_fds  ="bid_time";
			$id_fds    ="bid_id";
			$satus_fds="bid_status";
			break;
	}
	
$user_join = keke_task_config::get_user_join_task();//用户参加任务统计

/* 排序方式*/
$ord_arr=array(" a.$id_fds desc "=>$_lang['manuscript_id_desc'],
		   " a.$id_fds asc "=>$_lang['manuscript_id_asc'],
		   " a.$time_fds desc "=>$_lang['submit_time_desc'],
		   " a.$time_fds asc "=>$_lang['submit_time_asc']);
	$cln     =$model_info['model_code']."_task_class";
	$page_obj=Keke::$_page_obj;
	/**获取对应任务的状态值**/
	$status_arr=call_user_func(array($cln,"get_task_status"));
	/*获取稿件状态**/
	$work_arr  =call_user_func(array($cln,"get_work_status"));
	$work_arr[0] = $_lang['yet_deal_with'];
	//**周参加任务统计**//
	$join_count=intval(dbfactory::get_count(sprintf("select count(task_id) from %s%s
 	where YEARWEEK(FROM_UNIXTIME(%d)) = YEARWEEK('%s') and uid='%d' ",TABLEPRE,$tab_name,$time_fds,date('Y-m-d H:i:s',time()),$uid)));
		
	$sql=" select a.$satus_fds,a.$time_fds,a.$id_fds,b.task_id,b.task_cash,b.task_title,b.model_id,b.task_cash_coverage,b.task_status from ".TABLEPRE
		 .$tab_name." a left join ".TABLEPRE."witkey_task b on a .task_id=b.task_id where b.model_id = '$model_id' and a.uid='$uid'";
	$count_sql="select a.$id_fds from ".TABLEPRE.$tab_name." a left join ".TABLEPRE."witkey_task b on a .task_id=b.task_id where b.model_id = '$model_id' and a.uid='$uid'";
	
	($task_status==='0') and $where.=" and b.task_status='".intval($task_status)."'" or ($task_status and $where.=" and b.task_status = '".intval($task_status)."' ");
	($$satus_fds==='0') and $where.=" and a.$satus_fds='".intval($$satus_fds)."'" or ($$satus_fds and $where.=" and a.$satus_fds = '".intval($$satus_fds)."' ");
	
	$$id_fds&&$$id_fds!=$_lang['enter_manuscript_id']       and $where.=" and a.$id_fds = '".intval($$id_fds)."' ";
	$ord and $where.=" order by $ord " or $where.=" order by a.$id_fds desc ";
	
	$page_size and $page_size=intval($page_size) or $page_size='10';
	$page and $page=intval($page) or $page='1';
	$url="index.php?do=$do&view=$view&op=$op&model_id=$model_id&page_size=$page_size&task_status=$task_status&$satus_fds=".$$satus_fds."&page=$page";
	$count=intval(dbfactory::execute($count_sql.$where));
	$pages=$page_obj->getPages($count, $page_size, $page, $url);

	$task_info = dbfactory::query($sql.$where.$pages['where']);
}
require keke_tpl_class::template ( "user/" . $do . "_".$view."_" . $op );


