<?php
/**
 * 短信配置
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role(66);
require '../../keke_client/sms/postmsg.php';
$account_info = Keke::$_sys_config; //手机账号信息
$mobile_u = $account_info ['mobile_username'];
$mobile_p = $account_info ['mobile_password'];
$op and $op = $op or $op = 'config';

$url = "index.php?do=$do&view=$view&op=$op";
switch ($op) {
	case "config" :
		if (! isset ( $sbt_edit )) {
			$bind_info = check_bind ( 'mobile_username' );
		} else { //添加、编辑\
			/**mobile**/
			foreach ( $conf as $k => $v ) {
				if (check_bind ( $k )) {
					
					$res .= dbfactory::execute ( " update " . TABLEPRE . "witkey_basic_config set v='$v' where k='$k'" );
				} else {
				//	Keke::admin_system_log('创建了手机平台');
					$res .= dbfactory::execute ( " insert into " . TABLEPRE . "witkey_basic_config values('','$k','$v','mobile','','')" );
				}
			}
			Keke::admin_system_log($_lang['edit_mobile_log']);
			if ($res)
				Keke::admin_show_msg ( $_lang['binding_cellphone_account_successfully'], "index.php?do=$do&view=$view&op=config",3,'','success' );
			else
				Keke::admin_show_msg ( $_lang['binding_cellphone_account_fail'], "index.php?do=$do&view=$view&op=config",3,'','warning' );
		
		}
		break;
	case "manage" :
		if ($remain_fee) {
			if ($mobile_p && $mobile_u) {
				$config_info = Msg_GetConfigInfo ( $mobile_u, $mobile_p );
				if (! $config_info) {
					Keke::echojson ( $_lang['get_user_info_fail'], "2" );
					die ();
				} else {
					$remain_fee = Msg_GetRemainFee ( $mobile_u, $mobile_p ); //账号余额
					Keke::echojson ( $remain_fee / 100, "1" );
					die ();
				}
			} else {
				Keke::admin_show_msg ( $_lang['not_bind_cellphone_account'], "index.php?do=$do&view=$view&op=config",3,'','warning' );
			}
		
		}
		break;
}
/**
 *检测绑定账号是否存在 
 */
function check_bind($k) {
	return dbfactory::get_count ( " select k from " . TABLEPRE . "witkey_basic_config where k='$k'" );
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );