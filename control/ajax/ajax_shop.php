<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.1
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
switch ($action) {
	case "load_sale" :
		$shop_id and $list = Dbfactory::query(' select price,title,service_id,pic from '.TABLEPRE.'witkey_service where shop_id = '.$shop_id.' and service_status=2 order by on_time desc limit 0,4');
		break;
}
require keke_tpl_class::template ('ajax/ajax_shop');