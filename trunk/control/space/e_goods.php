<?php
/**
 * 个人空间商品展示
 * @author lj
 * @charset:GBK  last-modify 2011-12-12-上午11:04:44
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

// 商品展示
$sql = "select * from " . TABLEPRE . "witkey_service where ";
$csql = sprintf ( "select count(service_id) as c  from `%switkey_service` where", TABLEPRE );
$where = " uid=" . intval ( $member_id ) . " and  service_status!=1 order by on_time desc";
$url = "index.php?do=space&member_id=$member_id&view=goods&page_size=$page_size";
$page_size = 12;
$count = intval ( dbfactory::get_count ( $csql . $where, 0, null, 3600 ) );
$page = $page ? $page : 1;
$pages = kekezu::$_page_obj->getPages ( $count, $page_size, $page, $url );
$where .= $pages ['where'];
$shop_arr = dbfactory::query ( $sql . $where );

require keke_tpl_class::template ( SKIN_PATH . "/space/{$type}_{$view}" );

