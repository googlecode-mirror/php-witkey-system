<?php	defined ( 'IN_KEKE' )  or 	exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-19下午09:25:13
 */
class Control_admin_nav extends Controller{
	function action_index(){
		//加载全局变量和语言包
		global $_K,$_lang;
		//获取后台的父目录和子目录
		$menus_arr = keke_admin_class::get_admin_menu();
		$menus_arr = $menus_arr['menu'];
 
		require keke_tpl::template('control/admin/tpl/nav');
	}
}

