<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8下午06:42:39
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

//新手指南
$res = kekezu::get_table_data ( "art_cat_id", 'witkey_article_category', "art_index like '%{294}%'", '', '', '', 'art_cat_id', 3600 );

if ($res) {
	$cat_ids = array_keys ( $res );
	$cat_ids = implode ( ',', $cat_ids );
	$new_help = db_factory::query ( sprintf ( "select art_id,art_title,art_cat_id from %switkey_article where art_cat_id in (%s) order by views desc limit 0,3", TABLEPRE, $cat_ids ), 1, 3600 );
}
//认证信息
$auth_item = keke_auth_base_class::get_auth_item ();
$auth_temp = array_keys ( $auth_item );
$user_info ['user_type'] == 2 and $un_code = 'realname' or $un_code = "enterprise";
$t = implode ( ",", $auth_temp );
$auth_info = db_factory::query ( " select a.auth_code,a.auth_status,b.auth_title,b.auth_small_ico,b.auth_small_n_ico from " . TABLEPRE . "witkey_auth_record a left join " . TABLEPRE . "witkey_auth_item b on a.auth_code=b.auth_code where a.uid ='$uid' and FIND_IN_SET(a.auth_code,'$t')", 1, 3600 );
$auth_info = kekezu::get_arr_by_key ( $auth_info, "auth_code" );
//待付款任务数，商品数
$wait_task = intval ( db_factory::get_count ( sprintf ( "select count(*) from %switkey_task where task_status=0 and uid='%d'", TABLEPRE, $uid ) ) );
$wait_shop = intval ( db_factory::get_count ( sprintf ( "select count(*) from %switkey_order where order_status='wait' and model_id in(6,7) and order_uid='%d'", TABLEPRE, $uid ) ) );
//待选稿任务数
$xg_task = intval ( db_factory::get_count ( sprintf ( "select count(*) from %switkey_task where task_status=3 and uid='%d'", TABLEPRE, $uid ) ) );
//待确认商品数
$confirm_service = intval ( db_factory::get_count ( sprintf ( "select count(*) from %switkey_order where order_status='wait' and model_id in(6,7) and order_uid='%d'", TABLEPRE, $uid ) ) );
//工具箱
$payitem_list = keke_payitem_class::get_payitem_config ( null, null, null, 'item_id' ); //可购买服务


//任务状态数组
$task_status = keke_global_class::get_taskstatus_desc ();
//任务区间数组
$task_cove = kekezu::get_table_data ( '*', "witkey_task_cash_cove", '', "start_cove", '', '', 'cash_rule_id', 3600 );

//发布任务
$pub_task = db_factory::query ( sprintf ( "select * from %switkey_task where uid = '%d' and task_status in (2,3,4,5,6) order by start_time desc limit 0,6", TABLEPRE, $uid ) );
$pub_task = get_pub_list ( $pub_task );
//发布的商品
$pub_service = db_factory::query ( sprintf ( "select * from %switkey_service where uid='%d'", TABLEPRE, $uid ) );
$pub_service = get_pub_list ( $pub_service );

//参与的任务
$sql = "select a.* from %switkey_task a left join %switkey_task_bid b on a.task_id = b.task_id left join %switkey_task_work c on a.task_id = c.task_id where task_status in (2,3,4,5,6) and (b.uid=%d or c.uid=%d) group by a.task_id order by start_time desc";

$join_task = db_factory::query ( sprintf ( $sql, TABLEPRE, TABLEPRE, TABLEPRE, $uid, $uid ) );
$join_task = get_pub_list ( $join_task );
//购买的商品
$buy_service = db_factory::query ( sprintf ( "
		SELECT a.order_uid,a.order_username,a.seller_uid,a.seller_username,a.order_time,c.* from %switkey_order a
		left join %switkey_order_detail b on a.order_id =b.order_id and a.order_status ='confirm'
		left join %switkey_service c on b.obj_id = c.service_id where a.order_uid='%d' and a.model_id in(6,7) and b.obj_type='service' group by c.service_id", TABLEPRE, TABLEPRE, TABLEPRE, $uid ) );
$buy_service = get_pub_list ( $buy_service );
/**
 * 获取任务，工具属性
 * @param array $list  任务信息
 * @return array
 */
function get_pub_list($list) {
	foreach ( $list as $k => $v ) {
		$pay_time = unserialize ( $v ['payitem_time'] );
		$list [$k] = $v;
		$list [$k] ['top'] = $pay_time ['top'];
		$list [$k] ['urgent'] = $pay_time ['urgent'];
	}
	return $list;
}
if (in_array ( $ajax, array ('rules', 'withdraw', 'safe' ) )) {
	$cat_arr = array ('rules' => 100, 'withdraw' => 297, 'safe' => 203 );
	$art_arr = get_art ( $cat_arr [$ajax] );
	require keke_tpl_class::template ( "ajax/index" );
	die ();
}
$art_notice_arr = get_art ( 17 );
function get_art($cat_id) {
	$sql = "select a.art_id,a.art_title from " . TABLEPRE . "witkey_article a left join " . TABLEPRE . "witkey_article_category b  on a.art_cat_id = b.art_cat_id
 					where a.art_cat_id = $cat_id  or b.art_cat_pid like '%{ $cat_id }%' order by a.pub_time desc limit 4";
	return db_factory::query ( $sql, 0, 600 );
}
require keke_tpl_class::template ( "user/user_" . $view );