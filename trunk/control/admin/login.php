<?php
class Control_admin_login extends Controller {
	/**
	 * 判断有没有登录，如果登录了，跳到index.php
	 * 如果没有登录跳到初始化登录页面
	 */
	function action_index() {
		global $_K,$_lang;
		// group_id > 0 表示是
		if ($_SESSION ['admin_uid'] and $_K ['userinfo'] ['group_id'] > 0) {
			// 已经登录了，跳到首页
			header ( 'localhost', 'index.php/admin/index' );
		} else {
			// 初始化登录页面
			$login_limit = $_SESSION ['login_limit']; // 用户登录限制时间
			$remain_times = $login_limit - time (); // 允许再次登录时差
			$admin_obj = new keke_admin_class();
			$allow_times = $admin_obj->times_limit ( $allow_num ); // 允许登录尝试次数
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
 