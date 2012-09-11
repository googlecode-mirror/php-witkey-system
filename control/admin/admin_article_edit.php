<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Liyingqing
 * @version v 2.0
 * 2010-7-15 10:00:34
 */

//Keke::admin_check_role(15);
$art_obj = keke_table_class::get_instance ( "witkey_article" );

$types = array ('help', 'art','bulletin','about' );
(! empty ( $type ) && in_array ( $type, $types )) or $type = 'art';

switch ($type) {
	case 'art' :
		Keke::admin_check_role ( 15);
		$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "cat_type = 'article'", " art_cat_id desc", '', '', 'art_cat_id', null );
		break;
		;
	case 'help' :
		Keke::admin_check_role (43);
		$art_cat_arr = Keke::get_table_data ( '*', "witkey_article_category", "cat_type = 'help'", " art_cat_id desc", '', '', 'art_cat_id', null );
		break;
		;
	case 'bulletin' :
		Keke::admin_check_role (43);
		break;
		;
	case 'about' :
		Keke::admin_check_role (54);
		break;
		;
}
(isset ( $art_id ) and intval ( $art_id ) > 0) and $art_info = $art_obj->get_table_info ( 'art_id', $art_id );
empty ( $art_info ) or extract ( $art_info );
/**
 * 处理页面表单的提交
 */

if ($sbt_edit) { 

	 
	//文章发布时间
	$fields ['pub_time'] = time ();
	
	if($type=='art'){
		$fields ['cat_type'] = 'article';
	}else{
		$fields ['cat_type'] = $type;
	}
	
	
	//文章推荐
	isset($fields['is_recommend']) or $fields['is_recommend']=0;
	//跳转地址
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
 
if(isset($ac)&&$ac=='del'){
	if($filepath){
		$pk and Dbfactory::execute(" update ".TABLEPRE."witkey_article set art_pic ='' where art_id = ".intval($pk));
		$file_info = Dbfactory::get_one(" select * from ".TABLEPRE."witkey_file where save_name = '.$filepath.'");
	
		keke_file_class::del_att_file($file_info['file_id'], $file_info['save_name']);
		Keke::echojson ( '', '1' );
	}
}

//递归分类列表
$cat_arr = array ();

Keke::get_tree ( $art_cat_arr, $cat_arr, 'option', $art_id, 'art_cat_id', 'art_cat_pid', 'cat_name' );

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . "_" . $view );

function get_fid($path){//删除图片时获取图片对应的fid,图片的存放形式是e.g ...img.jpg?fid=1000
	if(!path){
		return false;
	}
	$querystring = substr(strstr($path, '?'), 1);
	parse_str($querystring, $query);
	return $query['fid'];
}