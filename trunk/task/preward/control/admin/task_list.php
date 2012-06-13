<?php
/**
 * ��̨����������������б�
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
//��������
$task_config = unserialize ( $model_info ['config'] );

$model_list = Keke::$_model_list;
//����״̬
$task_status = preward_task_class::get_task_status ();

$table_obj = keke_table_class::get_instance ( 'witkey_task' );


$page and $page=intval ( $page ) or $page = 1;
$page_size and $page_size=intval($page_size) or $page_size =10;

$wh = "model_id={$model_info['model_id']}";

if ($w ['task_id']) {
	$wh .= " and task_id = " . $w ['task_id'];
}
if ($w ['task_title']) {
	$wh .= ' and task_title like ' . '"%' . $w ['task_title'] . '%" ';
}
if ($w ['task_status']) {
	$wh .= " and task_status = " . $w ['task_status'];
}
$w ['task_status']==='0' and $wh .= " and task_status = 0" ;
if ($sbt_search) {
	$wh .= " order by $ord[0] $ord[1]";
}else{
	$wh .= " order by task_id desc ";
}

$url_str = "index.php?do=model&model_id=3&view=list&w['task_id']={$w['task_id']}&w['task_title']={$w['task_title']}&w['task_status']={$w['task_status']}&ord['0']={$ord['0']}&ord['1']={$ord['1']}&page=$page&page_size=$page_size";

//��ѯ
$table_arr = $table_obj->get_grid ( $wh, $url_str, $page, $page_size, null, 1, 'ajax_dom');
$task_arr = $table_arr ['data'];
$pages = $table_arr ['pages'];
if($task_id){ 
	$task_audit_arr = get_task_info($task_id);
	$start_time = date("Y-m-d H:i:s",$task_audit_arr['start_time']);
	$end_time = date("Y-m-d H:i:s",$task_audit_arr['end_time']);
	$url = "<a href =\"{$_K['siteurl']}/index.php?do=task&task_id={$task_audit_arr['task_id']}\" target=\"_blank\" >" . $task_audit_arr['task_title']. "</a>";

}
	switch ($ac) {
		case "del" : //ɾ��
			$task_title = dbfactory::get_count(sprintf("select task_title from %switkey_task where task_id='%d' ",TABLEPRE,$task_id));
			Keke::admin_system_log($_lang['delete_task'].":{$task_title}(".$_lang['piece_reward'].")");
			$res = $table_obj->del ( 'task_id', $task_id, $url_str );
			$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['delete_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['delete_fail'],"warning");
			break;
		case "pass" : //ͨ�����
			$res =keke_task_config::task_audit_pass ( $task_id );
			$v_arr = array($_lang['username']=>$task_audit_arr['username'],$_lang['task_link']=>$url,$_lang['start_time']=>$start_time,$_lang['end_time']=>$end_time,$_lang['task_id']=>"#".$task_id); 
			keke_shop_class::notify_user($task_audit_arr['uid'], $task_audit_arr['username'], 'task_auth_success', $task_audit_arr['task_title'],$v_arr);
			
			$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['audit_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['nopass'],"warning");
			break;
		case "nopass" : //���ʧ��
			$res =keke_task_config::task_audit_nopass ( $task_id );
			$v_arr = array($_lang['username']=>$task_audit_arr['username'],$_lang['task_title']=>$url,$_lang['website_name']=>Keke::$_sys_config['website_name']); 
			keke_shop_class::notify_user($task_audit_arr['uid'], $task_audit_arr['username'], 'task_auth_fail', $task_audit_arr['task_title'],$v_arr);
			
			$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['operate_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['operate_fail'],"warning");
			break;
		case "freeze" : //��������
			$res =keke_task_config::task_freeze ( $task_id );
			$v_arr = array($_lang['username']=>$task_audit_arr['username'],$_lang['task_title']=>$url); 
			keke_shop_class::notify_user($task_audit_arr['uid'], $task_audit_arr['username'], 'task_freeze', $task_audit_arr['task_title'],$v_arr);
		
			$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['freeze_task_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['freeze_task_fail'],"warning");
			break;
		case "unfreeze" : //����ⶳ
			$res =keke_task_config::task_unfreeze ( $task_id );
			$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['unfreeze_task_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['unfreeze_task_fail'],"warning");
			break;
		case "recommend"://�����Ƽ�
			$res =keke_task_config::task_recommend($task_id);
			$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_recommend_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_recommend_fail'],"warning");
			break;
		case "unrecommend"://ȡ�������Ƽ�
			$res = keke_task_config::task_unrecommend($task_id);
			$res and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['cancel_recommend_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['cancel_recommend_fail'],"warning");
			break;
	}


//����ɾ��
if ($sbt_action==$_lang['mulit_delete']&&!empty($ckb)) {	
	keke_task_config::task_del($ckb) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_delete_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['tips_about_delete_failure'],"warning");
}
//�������
if ($sbt_action==$_lang['mulit_pass']&&!empty($ckb)) {
	keke_task_config::task_audit_pass($ckb) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_pass_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_pass_fail'],"warning");
}
//��������
if ($sbt_action==$_lang['mulit_freeze']&&!empty($ckb)) {
	keke_task_config::task_freeze($ckb) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_freeze_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_freeze_fail'],"warning");
}
//�����ⶳ
if ($sbt_action==$_lang['mulit_unfreeze']&&!empty($ckb)) {
	keke_task_config::task_unfreeze($ckb) and Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_unfreeze_success'],'success') or Keke::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_unfreeze_fail'],"warning");
}
function get_task_info($task_id){
	$task_obj = new Keke_witkey_task_class();
	$task_obj->setWhere("task_id = $task_id");
	$task_info = $task_obj->query_keke_witkey_task();
	$task_info = $task_info ['0'];
	return $task_info;

}

require Keke::$_tpl_obj->template ( 'task/' . $model_info ['model_dir'] . '/control/admin/tpl/task_' . $view );