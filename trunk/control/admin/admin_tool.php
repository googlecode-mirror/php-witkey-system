<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ϵͳ�������·��
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-19����09:25:13
 */


//echo  trim($view);
$views = array ('dbbackup', 'dbrestore','dboptim', 'cache', 'file', 'log','payitem');
 
$view = (! empty ( $view ) && in_array ( $view, $views )) ? $view : 'log';
if (file_exists ( ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php' )) {
	require_once ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php';
} else {
	kekezu::admin_show_msg ( $_lang['404_page'],'',3,'','warning');
}