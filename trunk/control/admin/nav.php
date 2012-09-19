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
/* 		foreach ( $menus_arr as $list){
			foreach ($list as $list_one){
				foreach ($list_one['items'] as $list_two){
					$url = $list_two['resource_url'];
					$uri = explode('/', $url);
					if($uri[3]){
						$uri = $uri[2].$uri[3];
					}else{
						$uri = $uri[2];
						var_dump($uri);die;
					}
				}
			}
		}
		var_dump($uri);die; */
		require keke_tpl::template('control/admin/tpl/nav');
	}
}

/**后台全局菜单信息**/
// $menu_conf = $admin_obj->get_admin_menu();

/**子菜单列表**/
// $sub_menu_arr = $menu_conf['menu'];

//var_dump($menu_conf);
// require  $template_obj->template ( 'control/admin/tpl/admin_' . $do );