<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
��̨�û�·��
*/


$views = array("add","list","charge","custom_list","group_add","group_list","custom_add");

$view = (! empty ( $view ) && in_array ( $view, $views )) ? $view : 'add';

require "admin_user_$view.php";


