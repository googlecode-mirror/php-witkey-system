<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ºóÌ¨ËøÆÁ
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-08-30 09:51:34
 */
class Control_admin_lock extends Controller{
	function action_index(){
		global $_K,$_lang;
		require keke_tpl::template('control/admin/tpl/lock');
	}
	function action_unlock(){
		$admin_obj = new keke_admin_class(); 
		if($_GET['unlock_times']){
		}
		$admin_obj->screen_unlock($_GET['unlock_times'],$_GET['unlock_pwd']);
		require keke_tpl::template('control/admin/tpl/index');
	}
}
/* defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
switch ($ac){
	case "lock":
		$admin_obj->screen_lock();
		break;
	case "unlock":
		$admin_obj->screen_unlock($unlock_times,$unlock_pwd);
		break;
		
}
require $Keke->_tpl_obj->template("control/admin/tpl/admin_" .$do); */