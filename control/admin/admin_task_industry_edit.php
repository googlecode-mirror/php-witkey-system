<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��ҵ�༭
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-18����22:20:00
 */


Keke::admin_check_role ( 6 );
$indus_table_obj = new Keke_witkey_industry_class();
$indus_obj = keke_table_class::get_instance ( "witkey_industry" ); //ʵ������ҵ������
$file_obj = new keke_file_class();

//��ҵ�������
$indus_arr = Keke::get_industry (0);
(isset ( $indus_id ) and intval ( $indus_id ) > 0) and $indus_info = $indus_obj->get_table_info ( 'indus_id', $indus_id );
empty ( $art_info ) or extract ( $art_info );
//������ҵ��Ϣ
if (isset ( $indus_id ) && intval ( $indus_id ) > o) {
	$indus_info = $indus_obj->get_table_info ( 'indus_id', $indus_id );
	$indus_pid = $indus_info ['indus_pid'];
}
//������ҵ
if($sbt_edit){
	$indus_table_obj->setWhere("indus_name = '".$fs['indus_name']."'");
	$res  = $indus_table_obj->count_keke_witkey_industry();
	!$pk&&$res and Keke::admin_show_msg($_lang['operate_fail'],$url,3,$_lang['indus_has']);
	$fs['on_time'] = time();
	isset($fs['is_recommend']) or $fs['is_recommend']=0;
	$fs=Keke::escape($fs);	
	$res = $indus_obj->save($fs,$pk);
	$indus_info = $indus_obj->get_table_info ( 'indus_id', $pk['indus_id'] ); 
	$url = "index.php?do=task&view=industry";
	!$pk and Keke::admin_system_log($_lang['add_industry']) or Keke::admin_system_log($_lang['edit_industry'].':'.$indus_info['indus_name']);
	$file_obj->delete_files(S_ROOT."./data/data_cache/");
	$file_obj->delete_files(S_ROOT.'./data/tpl_c/'); 
	$res and Keke::admin_show_msg($_lang['operate_success'],$url,3,'','success') or Keke::admin_show_msg($_lang['operate_fail'],$url,3,'','warning');	
}

 

//�ݹ�����б�

$temp_arr = array();

Keke::get_tree($indus_arr,$temp_arr,'option',$indus_pid,'indus_id'); 
$indus_arr = $temp_arr;
//var_dump($temp_arr);
require $template_obj->template ( 'control/admin/tpl/admin_task_' . $view );