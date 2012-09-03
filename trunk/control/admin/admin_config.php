<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * 后台配置入口
 * @copyright keke-tech
 * @author Michael
 * @version v 2.0
 * 2011-8-11
 */



$views = array ('basic','currencies', 'pay','editpay','edittrust', 'tpl' ,'mail','msg','msgtpl','cove','integration','score','mark','model','nav','field');

(! empty ( $view ) && in_array ( $view, $views )) and $view or  $view = 'basic';
if (file_exists ( ADMIN_ROOT . 'admin_config_' . $view . '.php' )) {
	require ADMIN_ROOT . 'admin_config_' . $view . '.php';
} else {
	kekezu::admin_show_msg ( $_lang['404_page'],'',3,'','warning' );
}
