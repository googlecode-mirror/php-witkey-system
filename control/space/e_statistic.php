<?php
/**
 * 个人空间商品展示
 * @author lj
 * @charset:GBK  last-modify 2011-12-12-上午11:04:44
 * @version V2.0/
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$credit_level = unserialize ( $member_info ['seller_level'] );
$saler_aid = keke_user_mark_class::get_user_aid ( $member_id, '2', null, '1' );
$sql = sprintf ( "SELECT sum(sale_num)as num FROM %switkey_service where uid=%d", TABLEPRE, $member_id );

$pub_service = db_factory::query( $sql );
$pub_service_num = $pub_service['0']['num'];
 $sql = sprintf ( "select * from %switkey_order where model_id in(6,7) and seller_uid=%d", TABLEPRE, $member_id ); 
$buy_service_num = db_factory::execute ( $sql ); 

$sql = "select a.*,b.* from " . TABLEPRE . "witkey_service as a left join " . TABLEPRE . "witkey_mark as b on a.service_id=b.origin_id 
where b.model_code='goods' or b.model_code='service' and b.mark_type=1 and a.uid = $member_id ";
$url = "index.php?do=space&member_id=" . intval ( $member_id ) . "&view=statistic&page_size=$page_size";
$page_size = 10;
$count = db_factory::execute ( $sql );
$page = $page ? $page : 1;
$pages = kekezu::$_page_obj->getPages ( $count, $page_size, $page, $url );
$where = $pages ['where'];
$shop_arr = db_factory::query ( $sql . $where );
require keke_tpl_class::template ( SKIN_PATH . "/space/{$type}_{$view}" );

