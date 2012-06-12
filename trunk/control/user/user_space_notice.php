<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8обнГ06:42:39
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$has_open_shop = dbfactory::get_count(sprintf("select count(*) from %switkey_shop",TABLEPRE));
$has_open_shop = intval($has_open_shop);
if($access==1){
	$space_desc = $_lang['have_not_complete_basics'];
}
require keke_tpl_class::template ( "user/user_space_notice");

