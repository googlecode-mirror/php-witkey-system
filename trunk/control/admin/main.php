<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
class Control_admin_main extends Controller {
	function action_index(){
		global $_lang,$_K;
		$file_obj = new keke_file_class ();
		$file_size = $file_obj->getdirsize ( S_ROOT . '/data/uploads' );
		$file_size = intval ( $file_size / 1024 / 1024 ); // 获取当前附件大小
		$tables = Dbfactory::query ( "SHOW TABLE STATUS " );
		foreach ( $tables as $table ) { // 数据库大小
			$dbsize += $table ['Data_length'] + $table ['Index_length'];
		}
		
		$dbsize = round ( $dbsize / 1024 / 1024, 2 ); // 转换单位
		$mysql_ver = mysql_get_server_info (); // 获得 MySQL 版本
		
		/* 系统信息 */
		$sys_info ['os'] = PHP_OS;
		$sys_info ['ip'] = $_SERVER ['SERVER_ADDR'];
		$sys_info ['web_server'] = $_SERVER ['SERVER_SOFTWARE'];
		$sys_info ['php_ver'] = PHP_VERSION;
		$sys_info ['mysql_ver'] = $mysql_ver;
		$sys_info ['safe_mode'] = ( boolean ) ini_get ( 'safe_mode' ) ? $_lang ['yes'] : $_lang ['no'];
		$sys_info ['safe_mode_gid'] = ( boolean ) ini_get ( 'safe_mode_gid' ) ? $_lang ['yes'] : $_lang ['no'];
		$sys_info ['timezone'] = function_exists ( 'date_default_timezone_set' ) ? date_default_timezone_set ( 'Asia/Shanghai' ) : date_default_timezone_set ( 'Asia/Shanghai' );
		
		/* 允许上传的最大文件大小 */
		$sys_info ['max_filesize'] = ini_get ( 'upload_max_filesize' );
		$sys_info ['file_uploads'] = ini_get ( 'file_uploads' );
		
		/* 新增用户留言 */
		$news_count = intval ( Dbfactory::get_count ( sprintf ( " select count(msg_id) from %switkey_msg where to_uid='%d' and  uid>0 ", TABLEPRE, $admin_info [uid] ) ) );
		/* 新增发布任务 */
		$task_count = intval ( Dbfactory::get_count ( sprintf ( " select count(task_id) from %switkey_task where DATE(from_unixtime(start_time))=DATE('%s') ", TABLEPRE, date ( 'Y-m-d', time () ) ) ) );
		/* 新增注册会员 */
		$user_count = intval ( Dbfactory::get_count ( sprintf ( " select count(uid) from %switkey_space where DATE(from_unixtime(reg_time))=DATE('%s')", TABLEPRE, date ( 'Y-m-d', time () ) ) ) );
		/* 新增提现申请 */
		$withdraw_count = intval ( Dbfactory::get_count ( sprintf ( " select count(withdraw_id) from %switkey_withdraw where DATE(from_unixtime(applic_time))=DATE('%s')", TABLEPRE, date ( 'Y-m-d', time () ) ) ) );
		/* 新增用户充值 */
		$charge_count = intval ( Dbfactory::get_count ( sprintf ( " select count(order_id) from %switkey_order_charge where DATE(from_unixtime(pay_time))=DATE('%s') ", TABLEPRE, date ( 'Y-m-d', time () ) ) ) );
		
		/* 新增交易维权 */
		$report_count = intval ( Dbfactory::get_count ( sprintf ( " select count(report_id) from %switkey_report where DATE(from_unixtime(on_time))=DATE('%s')", TABLEPRE, date ( 'Y-m-d', time () ) ) ) );
		
		$pars = array (
				'ac' => 'run',
				'sitename' => urlencode ( $basic_config ['website_name'] ),
				'siteurl' => htmlentities ( $basic_config ['website_url'] ),
				'charset' => $_K ['charset'],
				'version' => KEKE_VERSION,
				'release' => KEKE_RELEASE,
				'os' => PHP_OS,
				'php' => $_SERVER ['SERVER_SOFTWARE'],
				'mysql' => $mysql_ver,
				'browser' => urlencode ( $_SERVER ['HTTP_USER_AGENT'] ),
				'username' => urlencode ( $_SESSION ['username'] ),
				'email' => $basic_config ['email'] ? $basic_config ['email'] : 'noemail',
				'p_name' => P_NAME
		);
		
		$data = http_build_query ( $pars );
		
		$lic = $_K ['ci'];
		$str_lic = Keke::set_star ( $lic, 5, 5, '3', '*' );
		$verify = md5 ( $data . $lic );
		$notice = "http://www.Kekezu.com/update.php?" . $data . "&lic=" . urlencode ( $lic ) . "&verify=" . $verify;
		$sys = array (
				"ac" => "sysinfo",
				'charset' => $_K ['charset'],
				'p_name' => P_NAME
		);
		$sysinfo = "http://www.Kekezu.com/news.php?" . http_build_query ( $sys );
		
		require Keke_tpl::template ( 'control/admin/tpl/main' );
	}
}

?>