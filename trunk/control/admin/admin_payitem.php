<?php	defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��ֵ����ĺ�̨·��
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-11-5-����02:03:21
 * @version V2.0
 */


$views = array ('index','buy','config');

(! empty ( $view ) && in_array ( $view, $views ))  or  $view = 'index';


require ADMIN_ROOT . 'admin_payitem_' . $view . '.php';
 
