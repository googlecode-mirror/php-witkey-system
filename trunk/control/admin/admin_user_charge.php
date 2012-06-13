<?php
/**
 * 用户管理
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role ( 11 );

if($check_uid){
	$sql = " select count(uid) from %switkey_member where ";
	intval($check_uid) and $sql.=" uid='%d'" or $sql.=" username='%s'";
	$count = dbfactory::get_count(sprintf($sql,TABLEPRE,$check_uid));
	if($count){
		echo true;
	}else{
		echo $_lang['none_exists_uid_or_username'];
	}
	die();
}
if($is_submit){
	$url = "index.php?do=$do&view=$view";
	$user or Keke::admin_show_msg($_lang['username_uid_can_not_null'],$url,3,'','warning');
	$cash or Keke::admin_show_msg($_lang['cash_can_not_null'],$url,3,'','warning');
	if(intval($user)){
		$uid  = intval($user);
	}else{
		$uid = dbfactory::get_count(sprintf(" select uid from %switkey_member where username='%s'",TABLEPRE,$user));
	}
	$res = keke_finance_class::cash_in($uid, $cash,0,'admin_charge');
	$res and Keke::admin_show_msg($_lang['charge_success'],$url,3,'','success') or Keke::admin_show_msg($_lang['charge_fail'],"index.php?do=$do&view=$view",3,'','warning');
}

require keke_tpl_class::template ( 'control/admin/tpl/admin_user_charge' );