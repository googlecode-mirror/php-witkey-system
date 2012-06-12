<?php
 /**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-08下午02:57:33
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');
/**
 * 子集菜单
 */
$sub_nav=array(
			array("task"=>array( $_lang['collection_of_task'],"document"),
			   "work"=>array( $_lang['collection_of_work'],"doc-empty"),
			   "service"=>array( $_lang['collection_of_service'],"layers-1"),
				"shop"=>array( $_lang['collection_of_shop'],"shop_cart"))
			);

$model_name_arr = 	kekezu::get_table_data ( 'model_code,model_name', 'witkey_model', '', 'model_id asc ', '', '', 'model_code');

$op or $op='task';//收藏类型
$title = kekezu::lang("collection_of_".$op);

$favor_obj=new Keke_witkey_favorite_class();//收藏对象
$page_obj=$kekezu->_page_obj;//分页对象

$ord_arr=array("f_id desc "=> $_lang['collection_num_desc'],
		   "f_id asc "=> $_lang['collection_num_asc'],
		   "on_date desc"=> $_lang['collection_time_desc'],
		   "on_date asc "=> $_lang['collection_time_asc']);
$where=" uid = '$uid' ";

intval($page) or $page=1;
intval($page_size) or $page_size='10';

$url=$origin_url."&op=$op&obj_type=$obj_type&ord=$ord&page=$page&page_size=$page_size";

$op and $where.=" and keep_type= '$op'";
$f_id&&$f_id!=$_lang['please_enter_the_collection_num'] and $where.=" and f_id = '$f_id' ";
$obj_name&&$obj_name!=$_lang['please_enter_the_collection_name'] and $where.=" and INSTR(obj_name,'$obj_name')>0 ";
$obj_type and $where.=" and obj_type = '$obj_type' ";
$ord and $where.=" order by $ord " or $where.=" order by f_id desc ";

$favor_obj->setWhere($where);
$count=intval($favor_obj->count_keke_witkey_favorite());
$pages=$page_obj->getPages($count, $page_size, $page, $url,"#userCenter");

$favor_obj->setWhere($where.$pages['where']);
$favor_arr=$favor_obj->query_keke_witkey_favorite();



require keke_tpl_class::template('user/user_'.$view);

