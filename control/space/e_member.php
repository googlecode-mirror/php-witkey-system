<?php
/**
 * 个人空间商品展示
 * @author lj
 * @charset:GBK  last-modify 2011-12-12-上午11:04:44
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );


$sql = sprintf("select * from %switkey_shop_member where shop_id=%d",TABLEPRE,$e_shop_info['shop_id']);
$e_member_arr = db_factory::query($sql);

require keke_tpl_class::template ( SKIN_PATH . "/space/{$type}_{$view}" );

