<?php
class Control_admin_login extends Controller {
	/**
	 * �ж���û�е�¼�������¼�ˣ�����index.php
	 * ���û�е�¼������ʼ����¼ҳ��
	 */
	function action_index() {
		global $_K,$_lang;
		// group_id > 0 ��ʾ��
		if ($_SESSION ['admin_uid'] and $_K ['userinfo'] ['group_id'] > 0) {
			// �Ѿ���¼�ˣ�������ҳ
			header ( 'localhost', 'index.php/admin/index' );
		} else {
			// ��ʼ����¼ҳ��
			$login_limit = $_SESSION ['login_limit']; // �û���¼����ʱ��
			$remain_times = $login_limit - time (); // �����ٴε�¼ʱ��
			$admin_obj = new keke_admin_class();
			$allow_times = $admin_obj->times_limit ( $allow_num ); // �����¼���Դ���
			if ($is_submit) {
				$user_name = Keke::utftogbk ( $_POST['user_name'] );
				$admin_obj->admin_login ( $_POST['user_name'], $_POST['pass_word'], $allow_num, $token );
				die ();
			}
			require Keke_tpl::template ( 'control/admin/tpl/login' );
		}
	}
}
//end
 