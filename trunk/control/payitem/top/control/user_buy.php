<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-11-5����03:42:39
 */



if(isset($formhash)&&kekezu::submitcheck($formhash)){
	$res=keke_payitem_class::payitem_cost($item_code,$buy_num);
	$res and kekezu::show_msg($_lang['system prompt'],"index.php?do=$do&view=$view&op=$op&show=my#userCenter","1",$item_info['item_name'].$_lang['buy_success'],'alert_right') or kekezu::show_msg($_lang['system prompt'],$_SERVER['HTTP_REFERER'],"1",$item_info['item_name'].$_lang['buy_fail'],"alert_error");
}
//���ؽ���ʣ������
$remain= keke_payitem_class::payitem_exists($uid,$item_code);
require keke_tpl_class::template("control/payitem/$item_code/tpl/user_buy");