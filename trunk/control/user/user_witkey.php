<?php
 /**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-08下午02:57:33
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');

$ops = array ('pub', 'task', 'shop','g_pub');

in_array($op,$ops) or $op ='task';
/**
 * 子集菜单
 */
$sub_nav = array(
	array ("pub" => array ($_lang['pub_goods'], "doc-new" )),
	array ("task" => array ($_lang['work_manage'], "doc-lines-stright" ),
		  "g_pub"=>array ($_lang['g_pub'],"notepad"),
 		   "shop" => array ($_lang['goods_trans'], "box" ))
);
$op=='task' and $model_type='task' or $model_type='shop';
$model_list=kekezu::get_table_data ( '*', 'witkey_model', " model_type = '$model_type'", 'model_id asc ', '', '', 'model_id', 3600 );
$model_fds = array_keys($model_list);
$model_id or $model_id = intval($model_fds['0']);
		
switch ($op){
	case "pub":
		 header("Location:index.php?do=shop_release");
		break;
	case "task":
		require 'user_'.$view.'_'.$op.'.php';	
		break;
	case "g_pub":
	case "shop":
		require 'user_'.$op.'.php';	die();
		break;
}