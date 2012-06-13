<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 1.4
 * 2011-9-19ÉÏÎç10:15:13
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
Keke::admin_check_role ( 141 );
if ($sbt_edit) { //Ìí¼Ó¡¢±à¼­\
	/**map_api**/
	$api = array();
	foreach ( $conf as $k => $v ) {
		$res .= dbfactory::execute ( " update " . TABLEPRE . "witkey_basic_config set v='$v' where k='$k'" );
		$open==$k and $api[$k] = 1 or $api[$k] = 0;
	}
	$api = serialize($api);
	dbfactory::execute(sprintf("update %switkey_basic_config set v='%s' where k='map_api_open'",TABLEPRE,$api));
	Keke::admin_system_log ($_lang['edit_map_api']);
	
	if ($res){
		Keke::admin_show_msg ($_lang['map_api_edit_success'], "index.php?do=$do&view=$view",2,'','success' );
	}else{
		Keke::admin_show_msg ($_lang['map_api_edit_fail'], "index.php?do=$do&view=$view",2,'','warning' );
	}
}else {
	$map_apis = Keke::get_table_data ( "k,v,type,desc", "witkey_basic_config", "type='map'", "", "", "", "k" );
 	$api_open =  dbfactory::get_one("select v from ".TABLEPRE."witkey_basic_config where k='map_api_open'"); 
 	$api_open =unserialize($api_open['v']);
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );