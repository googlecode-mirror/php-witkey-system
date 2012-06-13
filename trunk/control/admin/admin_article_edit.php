<?php
/**
 * @copyright keke-tech
 * @author Liyingqing
 * @version v 2.0
 * 2010-7-15 10:00:34
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role(15);
$art_obj = keke_table_class::get_instance ( "witkey_article" );

$types = array ('help', 'art', 'single' );
(! empty ( $type ) && in_array ( $type, $types )) or $type = 'art';

switch ($type) {
	case 'art' :
		Keke::admin_check_role ( 31 );
		$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "art_index like '%{1}%'", " art_cat_id desc", '', '', 'art_cat_id', null );

		break;
		;
	case 'help' :
		Keke::admin_check_role (43);
		$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "art_index like '%{100}%'", "art_cat_id desc", '', '', 'art_cat_id', null );
		break;
		;
	case 'single' :
		Keke::admin_check_role (54);
		$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "art_index like '%{200}%'", " art_cat_id desc", '', '', 'art_cat_id', null );
		break;
		;
}
(isset ( $art_id ) and intval ( $art_id ) > 0) and $art_info = $art_obj->get_table_info ( 'art_id', $art_id );
empty ( $art_info ) or extract ( $art_info );
/**
 * ����ҳ������ύ
 */
if ($sbt_edit) { 

	 
	//���·���ʱ��
	$fields ['pub_time'] = time ();
	//�����Ƽ�
	isset($fields['is_recommend']) or $fields['is_recommend']=0;
	//��ת��ַ
	$url = "index.php?do=$do&view=list&type=$type";
	$fields=Keke::escape($fields);
	$res = $art_obj->save ( $fields, $pk );
	
	$log_ac = array('edit'=>$_lang['edit_art'],'add'=>$_lang['add_art']);
	if($pk['art_id']){
		Keke::admin_system_log($log_ac['edit'].":".$fields['art_title']) ;
	}else{
		Keke::admin_system_log($log_ac['add'].":".$fields['art_title']) ;
	} 
	if($res){
		Keke::admin_show_msg($_lang['operate_success'],$url,3,'','success');
	}else{
	
		Keke::admin_show_msg($_lang['operate_fail'],$url,3,'','warning');
	}
}
 

//�ݹ�����б�
$cat_arr = array ();
Keke::get_tree ( $art_cat_arr, $cat_arr, 'option', $art_id, 'art_cat_id', 'art_cat_pid', 'cat_name' );


require $template_obj->template ( 'control/admin/tpl/admin_' . $do . "_" . $view );

function get_fid($path){//ɾ��ͼƬʱ��ȡͼƬ��Ӧ��fid,ͼƬ�Ĵ����ʽ��e.g ...img.jpg?fid=1000
	if(!path){
		return false;
	}
	$querystring = substr(strstr($path, '?'), 1);
	parse_str($querystring, $query);
	return $query['fid'];
}