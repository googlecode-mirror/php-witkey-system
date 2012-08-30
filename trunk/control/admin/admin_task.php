<?php	defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台task入口路由
 * @copyright keke-tech
 * @author Michael
 * @version v 2.0
 * 2010-5-17下午02:25:13
 */

$views = array ('industry', 'industry_edit', 'skill', 'skill_edit','comment','report','tpl' ,'mail','custom','union_industry','check_comment');
$view = (! empty ( $view ) && in_array ( $view, $views )) ? $view : 'industry';
if (file_exists ( ADMIN_ROOT . 'admin_task_' . $view . '.php' )) {
	require ADMIN_ROOT . 'admin_task_' . $view . '.php';
} else {
	kekezu::admin_show_msg ($_lang['404_page'],'',3,'','warning');
}
