<?php
/**
 * ��̨��¼����
 */

defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
$login_limit = $_SESSION ['login_limit']; // �û���¼����ʱ��
$remain_times = $login_limit - time (); // �����ٴε�¼ʱ��
$allow_times = $admin_obj->times_limit ( $allow_num ); // �����¼���Դ���
if ($is_submit) {
	$user_name = Keke::utftogbk ( $user_name );
	$admin_obj->admin_login ( $user_name, $pass_word, $allow_num, $token );
	die();
}
require keke_tpl_class::template ( 'control/admin/tpl/admin_' . $do );