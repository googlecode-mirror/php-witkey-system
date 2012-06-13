<?php
 /**
 * @copyright keke-tech
 * @author Liyingqing
 * @version v 2.0
 * 2010-7-15 10:00:34
 */
defined ( 'ADMIN_KEKE' )or exit ( 'Access Denied' );
Keke::admin_check_role(22);
$art_cat_obj = new Keke_witkey_article_category_class();//实例化文章分类表对象

$types =  array ('help', 'art');
$type = (! empty ( $type ) && in_array ( $type, $types )) ? $type : 'art';

 
//分类结果数组

if($type=='art'){
	Keke::admin_check_role(39);
	$art_cat_arr = Keke::get_table_data('*',"witkey_article_category","art_index like '%{1}%'","  art_cat_id desc",'','','art_cat_id',null);
}elseif($type=='help'){
	Keke::admin_check_role(45);
	$art_cat_arr = Keke::get_table_data('*',"witkey_article_category","art_index like '%{100}%'"," art_cat_id desc",'','','art_cat_id',null);
}


//单条分类信息
if($art_cat_id){
	$art_cat_obj->setWhere('art_cat_id='.intval($art_cat_id));
	$art_cat_info = $art_cat_obj->query_keke_witkey_article_category();
	$art_cat_info = $art_cat_info[0];
	$art_cat_pid = $art_cat_info[art_cat_pid];
}


//编辑分类
if($sbt_edit){
	$flag = null;
	if($hdn_art_cat_id){
		$art_cat_obj->setWhere('art_cat_id='.intval($hdn_art_cat_id));
		$art_cat_info = $art_cat_obj->query_keke_witkey_article_category();
		$art_cat_info = $art_cat_info[0];
		if($art_cat_info['art_cat_pid']>0){
			$art_cat_obj->setArt_cat_pid($slt_cat_id);
		}
	}else{

		$art_cat_obj->setArt_cat_pid($slt_cat_id);
	}
	
	$art_cat_obj->setCat_name(Keke::escape($txt_cat_name));
	$art_cat_obj->setListorder($txt_listorder?$txt_listorder:0);
	$art_cat_obj->setIs_show(intval($chk_is_show));
	$art_cat_obj->setOn_time(time());
	if($type=="art"){
		$art_cat_obj->setCat_type("article");
	}else if($type=="help"){
		$art_cat_obj->setCat_type("help");
	}else if ($type=="single"){
		$art_cat_obj->setCat_type("single");
	}
	$art_index = "";
	$art_index = "{{$slt_cat_id}}".$art_index;
	$flag = $art_cat_arr[$slt_cat_id];
	
	while ($flag['art_cat_pid']){
		$art_index = "{{$flag['art_cat_pid']}}".$art_index;
		$flag = $art_cat_arr[$flag['art_cat_pid']];
	}
	
	if($hdn_art_cat_id){
		$art_cat_obj->setArt_cat_id($hdn_art_cat_id);
		$art_index = $art_index."{{$hdn_art_cat_id}}";
		$art_cat_obj->setArt_index($art_index);
		$res = $art_cat_obj->edit_keke_witkey_article_category();//编辑文章分类
		if($res){
			Keke::admin_system_log($_lang['edit_article_cat'].$txt_cat_name);
			Keke::admin_show_msg($_lang['edit_article_cat_success'],'index.php?do='.$do.'&view='.$view.'&type='.$type.'&art_cat_id='.$hdn_art_cat_id,3,'','success');
		}
	}else{
		$res = $art_cat_obj->create_keke_witkey_article_category();//添加文章分类
		$art_index = $art_index."{{$res}}";
		if($res){
			$art_cat_obj->setWhere("art_cat_id='$res'");
			$art_cat_obj->setArt_index($art_index);
			$art_cat_obj->edit_keke_witkey_article_category();
			Keke::admin_system_log($_lang['add_article_cat'] . $txt_cat_name);
			Keke::admin_show_msg($_lang['add_article_cat_success'],'index.php?do='.$do.'&view=cat_list&type='.$type,3,'','success');
		}
	}
}
 
//递归分类列表
$temp_arr = array();
Keke::get_tree($art_cat_arr,$temp_arr,'option',$art_cat_pid,'art_cat_id','art_cat_pid','cat_name');
$cat_arr = $temp_arr;
unset($temp_arr);
 

require  $template_obj->template('control/admin/tpl/admin_'. $do .'_'. $view);