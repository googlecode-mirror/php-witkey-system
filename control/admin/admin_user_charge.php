<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 用户管理
 */

kekezu::admin_check_role ( 11 );

if($check_uid){
	CHARSET=='gbk' and $check_uid = kekezu::utftogbk($check_uid);
	$info = get_info($check_uid);
	$info and kekezu::echojson('',1,$info) or kekezu::echojson($_lang['none_exists_uid_or_username'],0);
	die();
}
if($is_submit){
	$url = "index.php?do=$do&view=$view";
	$user or kekezu::admin_show_msg($_lang['username_uid_can_not_null'],$url,3,'','warning');
	$info = get_info($user);
	$cash = floatval($cash);$credit = floatval($credit);
	if($cash<-$info['balance']){
		kekezu::admin_show_msg($_lang['user_deduct_limit'].$info['balance'].$_lang['yuan'],$url,3,'','warning');
	}elseif($credit<-$info['credit']){
		kekezu::admin_show_msg($_lang['user_deduct_limit'].$info['balance'].CREDIT_NAME,$url,3,'','warning');
	}
	($cash==0&&$credit==0) and kekezu::admin_show_msg($_lang['cash_can_not_null'],$url,3,'','warning');
	$cash_type or $cash = -$cash;
	$credit_type or $credit=-$credit;
	$res = keke_finance_class::cash_in($info['uid'], floatval($cash),floatval($credit),'admin_charge','','admin_charge');
	//fina_mem 充值事由
	$charge_reason = kekezu::filter_input($charge_reason);
	$sql2 = "update " . TABLEPRE . "witkey_finance set  fina_mem='{$charge_reason}' where fina_id = last_insert_id()";
	db_factory::execute ( $sql2 );
	$res and kekezu::admin_show_msg($_lang['charge_success'],$url,3,'','success') or kekezu::admin_show_msg($_lang['charge_fail'],"index.php?do=$do&view=$view",3,'','warning');
}
function get_info($uid){
	$sql = " select balance,credit,uid from %switkey_space where ";
	is_numeric($uid) and $sql.=" uid='%d'" or $sql.=" username='%s'";
	return  db_factory::get_one(sprintf($sql,TABLEPRE,$uid));
}
require keke_tpl_class::template ( 'control/admin/tpl/admin_user_charge' );