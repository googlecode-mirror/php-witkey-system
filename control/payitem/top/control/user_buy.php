<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-11-5下午03:42:39
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

if($sbt_buy){
	$res=keke_payitem_class::payitem_cost($item_code,$buy_num);
	$res and kekezu::show_msg($item_info['item_name'].$_lang['buy_success'],"index.php?do=$do&view=$view&op=$op&show=my#userCenter","3",'','success') or kekezu::show_msg($item_info['item_name'].$_lang['buy_fail'],$_SERVER['HTTP_REFERER'],"3","","warning");
}
//隐藏交稿剩余数量
$remain= keke_payitem_class::payitem_exists($uid,$item_code);
require keke_tpl_class::template("control/payitem/$item_code/tpl/user_buy");