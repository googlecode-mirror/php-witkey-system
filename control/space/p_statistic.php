<?php
/**
 * 个人空间用户统计
 * @author lj
 * @charset:GBK  last-modify 2011-12-12-上午11:04:44
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

$p_url =$_K['siteurl']."/index.php?do=space&member_id=$member_id";
$credit_level = unserialize ( $member_info ['seller_level'] );
/**卖家辅助评价**/
$seller_aid = keke_user_mark_class::get_user_aid ( $member_id, '2', null, '1' );

//出售的商品数 
$sale_num = intval(db_factory::get_count(sprintf(" select count(order_id) count from %switkey_order where seller_uid='%d' and model_id in (6,7)",TABLEPRE,$member_id)));
//购买的商品数 
$buy_num = intval(db_factory::get_count(sprintf(" select count(order_id) count from %switkey_order where order_uid='%d' and model_id in (6,7)",TABLEPRE,$member_id)));
$model_list = $kekezu->_model_list;
//销售统计 
$sql =" select a.order_uid,a.order_name,a.order_username,b.obj_id,a.model_id from "
	  .TABLEPRE."witkey_order a left join ".TABLEPRE
	  ."witkey_order_detail b on a.order_id = b.order_id where 
	  	a.seller_uid='$member_id' and a.order_status='confirm'";
intval($page) or $page=1;
intval($page_size) or $page_size=10;
$url = "index.php?do=space&member_id=$member_id&view=statistic&page=$page";
$count = intval(db_factory::execute($sql));
$pages = $kekezu->_page_obj->getPages($count, $page_size, $page, $url);
$sale_list = db_factory::query($sql.$pages['where']);
/**互评列表*/
$mark_list = kekezu::get_table_data("mark_content,mark_value,by_uid","witkey_mark"," uid='$member_id' and model_code in ('goods','service') and mark_status>0","","","","by_uid",3600);

require keke_tpl_class::template ( SKIN_PATH . "/space/{$type}_{$view}" );

