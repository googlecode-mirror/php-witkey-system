<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 1.4
 * 2011-9-19ионГ10:15:13
 */


$views = array ('weibo','config', 'send', 'internal','intertpl','attention','map');

$view = (! empty ( $view ) && in_array ( $view, $views )) ? $view : 'weibo';

if (file_exists ( ADMIN_ROOT . 'admin_msg_' . $view . '.php' )) {
	require ADMIN_ROOT . 'admin_msg_' . $view . '.php';
} else {
	Keke::admin_show_msg ( $_lang['404_page'],'',3,'','warning' );
}