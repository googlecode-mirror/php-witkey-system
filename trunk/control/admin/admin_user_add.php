<?php
/**
 * �û�����
 */
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
kekezu::admin_check_role ( 11 );
$basic_config = kekezu::$_sys_config;

$reg_obj = new keke_register_class ();
$member_class = new keke_table_class ( 'witkey_member' );
$space_class = new keke_table_class ( 'witkey_space' );
//$member_group_class=new keke_table_class ('witkey_member_group');
$edituid and $member_arr = kekezu::get_user_info ( $edituid );
// $edituid and $memberinfo_arr = $member_arr;
$member_group_arr = db_factory::query ( sprintf ( "select group_id,groupname from %switkey_member_group", TABLEPRE ) );

if ($is_submit == 1) {
	//����û�
	if (! $edituid) {
		$reg_uid = $reg_obj->user_register ( $fds [username], md5 ( $fds [password] ), $fds ['email'], null, false,$fds [password] );
		unset ( $fds [repassword] );
		is_null ( $fds ['group_id'] ) or db_factory::execute ( sprintf ( "update %switkey_space set group_id={$fds['group_id']} where uid=$reg_uid", TABLEPRE ) );
		kekezu::admin_system_log ( $_lang['add_member'] . $fds ['username'] );
		kekezu::admin_show_msg ( $_lang['operate_notice'], "index.php?do=user&view=add", 3, $_lang['user_creat_success'] ,'success');
	} else { //�༭�û�
		//unset($fds[repassword]);
		if ($fds ['password']) {
			//sec_code
			$slt = db_factory::get_count ( sprintf ( "select rand_code from %switkey_member where uid = '%d'", TABLEPRE, $edituid ) );
			$sec_code = keke_user_class::get_password ( $fds ['password'], $slt );
			$fds ['sec_code'] = $sec_code;
			$pwd = md5 ( $fds ['password'] );
			$fds [password] = $pwd;
			db_factory::execute ( sprintf ( "update %switkey_member set password ='%s' where uid=%d", TABLEPRE, $pwd, $edituid ) );
	 	}else{
	 		unset($fds['password']);
	 	}
 
		$space_class->save ( $fds, array ("uid" => "$edituid" ) ); //�洢��Ϣ 
		//is_null ( $fds ['group_id'] ) or db_factory::execute ( sprintf ( "update %switkey_space set group_id={$fds['group_id']} where uid='$edituid'", TABLEPRE ) );
		/* $add_balance = $fds ['balance'] - $member_arr ['balance']; //��Ǯ
		$add_credit = $fds ['credit'] - $member_arr ['credit'];
		$message_str = "";
		$add_balance > 0 and $message_str .= $_lang['system_admin'] . "{$myinfo_arr['username']}" . $_lang['give_your_cash_account_added'] . sprintf ( "%10.2f", $add_balance ) . $_lang['yuan'];
		$add_balance < 0 and $message_str .= $_lang['system_admin'] . "{$myinfo_arr['username']}" . $_lang['give_your_cash_account_deduct'] . sprintf ( "%10.2f", abs ( $add_balance ) ) . $_lang['yuan'];
		$add_credit > 0 and $message_str .= $_lang['system_admin'] . "{$myinfo_arr['username']}" . $_lang['give_your_cash_account_added'] . sprintf ( "%10.2f", $add_credit ) . $_lang['yuan'];
		$add_credit < 0 and $message_str .= $_lang['system_admin'] . "{$myinfo_arr['username']}" . $_lang['give_your_cash_account_deduct'] . sprintf ( "%10.2f", abs ( $add_credit ) ) . $_lang['yuan']; */
		kekezu::admin_system_log ( $_lang['edit_member'] . $member_arr [username] );
		//kekezu::notify_user ( $_lang['system_message'], $message_str, $edituid, $m_info ['username'] );
		kekezu::admin_show_msg ( $_lang['edit_success'], $_SERVER ['HTTP_REFERER'],3,'','success' );
	}
}

require $template_obj->template ( 'control/admin/tpl/admin_user_add' );