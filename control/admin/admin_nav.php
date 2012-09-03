<?php	defined ( 'IN_KEKE' )  or 	exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-19下午09:25:13
 */


/**后台全局菜单信息**/
$menu_conf = $admin_obj->get_admin_menu();

/**子菜单列表**/
$sub_menu_arr = $menu_conf['menu'];

//var_dump($menu_conf);
require  Keke_tpl::template ( 'control/admin/tpl/admin_' . $do );