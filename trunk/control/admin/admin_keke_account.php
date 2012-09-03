<?php
/**
 * @copyright keke-tech
 * @author zhang
 * @version v 2.0
 * 2011-11-11下午02:29:58
 */

defined ( "IN_KEKE" ) or exit ( "Access denied!" );
$path = S_ROOT.'/keke_client/keke/config.php';
include $path;
$op or $op = 'apply';
$op=='account' and $op='account';
$op!='account'&&trim ( $config ['keke_app_id'] ) and $op = 'config';
kekezu::admin_check_role ( 133 );
$reg_ip = $_SERVER ['REMOTE_ADDR'];

if ( $op=='config' ) {//配置
	$data = file_get_contents($path);
	if ($submit){
		$res = preg_replace(array(
		"/keke_app_id\'] = '(\w)*(\s)*'/",
		"/keke_app_secret\'] = '(\w)*(\s)*'/",
		), array(
		"keke_app_id'] = '$keke_id'",
		"keke_app_secret'] = '$keke_secret'",
		), $data);
		if(file_put_contents($path, $res)){
			kekezu::admin_show_msg('配置成功', '', 3);
		}
	}
}
//正式地址
$url = 'http://www.kekezu.com/union/apply.php';
//测试地址
$url='http://192.168.1.118/server/apply.php'; 
if($op=='apply'){
	require Keke_tpl::template ( "control/admin/tpl/IN_KEKE_account_apply" );
}else{
	require Keke_tpl::template ( "control/admin/tpl/IN_KEKE_account_config" );
}