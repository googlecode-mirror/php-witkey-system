<?php
/**
 * 行业管理
 * @copyright keke-tech
 * @author Tao
 * @version v 2.0
 * 2011-8-24 16:28
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
//Keke::admin_check_role ( 7 );

$cat_obj = new Keke_witkey_article_category_class ();
$file_obj = new keke_file_class();
$table_obj = new keke_table_class ( "witkey_article_category" );
//所有资讯分类的数组
$cat_all_arr = Keke::get_table_data('*',"witkey_article_category",'','','','','art_cat_id');

$url = "index.php?do=$do&view=$view&type=$type&w[art_cat_pid]={$w[art_cat_pid]}&w[cat_name]={$w[cat_name]}
		&$ord[0]={$ord[1]}";

if ($ac == 'del') { //删除
	//var_dump($art_cat_id);die();
	$table_obj->del ( 'art_cat_id', $art_cat_id, $url );
	Keke::admin_show_msg($_lang['delete_success'],'index.php?do=article&view=cat_list&type='.$type,3,'','success');
} elseif (isset ( $sbt_action )) {
	if ($edit_cat_name_arr) { //编辑
		foreach ( $edit_cat_name_arr as $k => $v ) {
			$cat_obj->setWhere ( "art_cat_id = $k" );
			$cat_obj->setCat_name ( $v );
			$cat_obj->edit_keke_witkey_article_category ();
		
		}
	
		Keke::admin_system_log ( $_lang['edit_article_category'] );
	} elseif ($add_cat_name_arr) { //删除
		 
		foreach ( $add_cat_name_arr as $k => $aindarr ) {
			foreach ( $aindarr as $kk => $v ) {
				if (! $v)
					continue;
				$cat_obj->_art_cat_id = null;
				$cat_obj->setCat_name ( $v );
				$cat_obj->setArt_cat_pid ( $k );
				$cat_obj->setListorder ( $add_cat_name_listarr [$k] [$kk] ? $add_cat_name_listarr [$k] [$kk] : 0 );
				$cat_obj->setOn_time ( time () );
				if($type=='art'){
				   $cat_type='article';
				}else{
				   $cat_type='help';
				}				
				$cat_obj->setCat_type($cat_type);
				$res = $cat_obj->create_keke_witkey_article_category ();
				$res and Dbfactory::execute(sprintf("update %switkey_article_category set art_index = '%s' where art_cat_id = $res ",TABLEPRE,$cat_all_arr[$k]['art_index'].'{'.$res.'}'));
			}
		}
		Keke::admin_system_log ( $_lang['delete_article_cat'] );
	}
		$file_obj->delete_files(S_ROOT."./data/data_cache/");
		$file_obj->delete_files(S_ROOT.'./data/tpl_c/');
	Keke::admin_show_msg ( $_lang['operate_success'], 'index.php?do=' . $do . '&view=' . $view.'&type='.$type,3,'','success' );
} elseif ($ac === 'editlistorder') { //改排序
	if ($iid) {
		$cat_obj->setWhere ( 'art_cat_id=' . $iid );
		$cat_obj->setListorder ( $val );
		$cat_obj->edit_keke_witkey_article_category ();
	}
} else {
	$where = ' 1 = 1 ';	
    $types =  array ('help', 'art');
    $type = (! empty ( $type ) && in_array ( $type, $types )) ? $type : 'art';
    switch ( $type ){
	case 'art':
		$art_cat_arr = Keke::get_table_data('*',"witkey_article_category","art_cat_pid =1 or art_cat_id = 1"," art_cat_id desc",'','','art_cat_id',null);
		$where.=" and cat_type='article' ";
		Keke::admin_check_role(14);
		break;
		;
	case 'help':
		$art_cat_arr = Keke::get_table_data('*',"witkey_article_category","cat_type='help'"," art_cat_id desc",'','','art_cat_id',null);
		$where.=" and cat_type='help' ";
		Keke::admin_check_role(44);
   }
	//查询条件
	if (isset ( $sbt_search )) {
		intval ( $w [art_cat_pid] ) and $where .= " and art_cat_pid = $w[art_cat_pid]";
		strval ( $w [cat_name] ) and $where .= " and cat_name like '%$w[cat_name]%'";
		$ord [1] and $where .= " order by $ord[0] $ord[1]";
	}
	//var_dump($where);
	$cat_arr = Keke::get_table_data ( "*", "witkey_article_category", $where, "", "", "", "", 0 );
	sort ( $cat_arr );
	
	if (! $w) {
		$t_arr = array ();
		Keke::get_tree ( $cat_arr, $t_arr, 'cat', NULL, 'art_cat_id', 'art_cat_pid', 'cat_name' );
		$cat_show_arr = $t_arr;
		//var_dump($t_arr);die(); 
		unset ( $t_arr );
	} else {
		//sort($indus_arr);
		$cat_show_arr = $cat_arr;
	}
	//var_dump($cat_show_arr);
	//搜索行业下拉菜单
//	$temp_arr = array ();
//	$indus_option_arr = Keke::get_industry ();
//	Keke::get_tree ( $indus_option_arr, $temp_arr, "option", $w [indus_pid] );
//	$indus_option_arr = $temp_arr;
//	unset ( $temp_arr );
//	$indus_index_arr = Keke::get_indus_by_index ();
	
	$temp_arr = array();
	//var_dump($art_cat_arr);
	Keke::get_tree($art_cat_arr,$temp_arr,'option',$w [art_cat_pid],'art_cat_id','art_cat_pid','cat_name');	
	$cat_option_arr = $temp_arr;
	unset($temp_arr);
	$cat_index_arr = get_cat_by_index ();
}

function sortTree($nodeid, $arTree) {
	$res = array ();
	for($i = 0; $i < sizeof ( $arTree ); $i ++)
		if ($arTree [$i] ["indus_pid"] == $nodeid) {
			array_push ( $res, $arTree [$i] );
			$subres = sortTree ( $arTree [$i] ["indus_id"], $arTree );
			for($j = 0; $j < sizeof ( $subres ); $j ++)
				array_push ( $res, $subres [$j] );
		}
	return $res;
}
function get_cat_by_index($cat_type='1', $pid = NULL){
   	global $Keke;
		$cat_index_arr = $Keke->_cache_obj->get ( 'cat_index_arr' . $cat_type . '_' . $pid );
		if (! $cat_index_arr) {
			$cat_arr = get_cat ( $pid );
			$cat_index_arr = array ();
			foreach ( $cat_arr as $cat ) {
				$cat_index_arr [$cat ['art_cat_pid']] [$cat ['art_cat_id']] = $cat;
			}
			$Keke->_cache_obj->set ( 'cat_index_arr' . $cat_type . '_' . $pid, $cat_index_arr, 3600 );
		}
		return $cat_index_arr;
}
function get_cat($pid = NULL, $cache = NULL) {
		
		! is_null ( $pid ) and $where = " art_cat_pid = '" . intval ( $pid ) . "'";
		
		$cat_arr = Keke::get_table_data ( '*', "witkey_article_category", $where, "listorder", '', '', 'art_cat_id', $cache );
		
		return $cat_arr;
	
	}
require  $template_obj->template('control/admin/tpl/admin_'. $do .'_'. $view);
