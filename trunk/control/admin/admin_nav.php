<?php
/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-19����09:25:13
 */
defined ( 'ADMIN_KEKE' )  or 	exit ( 'Access Denied' );

/**��̨ȫ�ֲ˵���Ϣ**/
$menu_conf = $admin_obj->get_admin_menu();

/**�Ӳ˵��б�**/
$sub_menu_arr = $menu_conf['menu'];
require  $template_obj->template ( 'control/admin/tpl/admin_' . $do );