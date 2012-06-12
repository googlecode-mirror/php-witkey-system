<?php
/**
 * @copyright keke-tech
 * @author zhang
 * @version v 2.0
 * 2011-11-11ÏÂÎç02:29:58
 */

defined ( "ADMIN_KEKE" ) or exit ( "Access denied!" );
$path = S_ROOT.'/keke_client/keke/config.php';
include $path;
$ops = array (
		'apply',
		'config' 
);
in_array ( $op, $ops ) or $op = 'apply';
trim ( $config ['keke_app_id'] )&& $op = 'config';
kekezu::admin_check_role ( 133 );
$reg_ip = $_SERVER ['REMOTE_ADDR'];

if ( $op=='config' ) {//ÅäÖÃ
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
			kekezu::admin_show_msg('ÅäÖÃ³É¹¦', '', 3);
		}
	}
}
require $template_obj->template ( "control/admin/tpl/admin_{$do}_{$view}_{$op}" );