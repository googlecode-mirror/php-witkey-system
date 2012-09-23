<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 用户添加
 */
class Control_admin_user_add extends Controller{
	function action_index(){
		global $_K,$_lang;
		$uid = $_GET['uid'];
		if ($uid){
			$where .= ' uid='.$uid;
			//获取用户信息
			$edit_arr = keke_user_class::get_user_info($uid);
			//查询shop表的shop方便推荐
			$shop_open = DB::select('shop_id')->from('witkey_shop')->where($where)->execute();
			$shop_open = $shop_open['0'];
		}
		//查询group表用来选择用户组
		$member_arr = DB::select()->from('witkey_member_group')->where('1=1')->execute();
		require keke_tpl::template('control/admin/tpl/user/add');
	}
	function action_save(){
		$_POST = keke_tpl::chars($_POST);
		//防止跨域提交
		keke::formcheck($_POST['formhash']);
		//密码md5加密
		$password = md5($_POST['password']);
		//需要插入数据库的字段
		$array = array('username'=>'$_POST[username]',
				'password'=>'$password',
				'email'=>'$_POST[email]',
				'group_id'=>'$_POST[group_id]',
				);
		//存在uid更新表数据，没有则插入数据
		if($_GET['hid_uid']){
			Model::factory('witkey_member')->setData($array)->update();
			Model::factory('witkey_space')->setData($array)->update();
			keke::show_msg("系统提交","{BASE_URL}/index.php/admin/user_add?uid=".$_GET['hid_uid'],"提交成功","success");
		}else {
			Model::factory('witkey_member')->setData($array)->create();
			Model::factory('witkey_space')->setData($array)->create();
			keke::show_msg("系统提交","{BASE_URL}/index.php/admin/user_add".$_GET['hid_uid'],"提交成功","success");
		}
		
	}
	function action_charge(){
		global $_K,$_lang;
		
		require keke_tpl::template('control/admin/tpl/user/add_charge');
	}
}
/* Keke::admin_check_role ( 11 );
$basic_config = $Keke->_sys_config;

$reg_obj = new keke_register_class ();
$member_class = new keke_table_class ( 'witkey_member' );
$space_class = new keke_table_class ( 'witkey_space' );
//$member_group_class=new keke_table_class ('witkey_member_group');
if($edituid){
	$member_arr = Keke::get_user_info ( $edituid );
	$shop_open = Dbfactory::get_count('select shop_id from '.TABLEPRE.'witkey_shop where uid='.$edituid);
}
// $edituid and $memberinfo_arr = $member_arr;
$member_group_arr = Dbfactory::query ( sprintf ( "select group_id,groupname from %switkey_member_group", TABLEPRE ) );

if ($is_submit == 1) {
	//添加用户
	if (! $edituid) {
		$reg_uid = $reg_obj->user_register ( $fds [username], md5 ( $fds [password] ), $fds ['email'], null, false,$fds [password] );
		unset ( $fds [repassword] );
		is_null ( $fds ['group_id'] ) or Dbfactory::execute ( sprintf ( "update %switkey_space set group_id={$fds['group_id']} where uid=$reg_uid", TABLEPRE ) );
		Keke::admin_system_log ( $_lang['add_member'] . $fds ['username'] );
		Keke::admin_show_msg ( $_lang['operate_notice'], "index.php?do=user&view=add", 3, $_lang['user_creat_success'] ,'success');
	} else { //编辑用户
		//unset($fds[repassword]);
		$uinfo = Keke::get_user_info($edituid);
		if ($fds ['password']) {
			//sec_code
			$slt = Dbfactory::get_count ( sprintf ( "select rand_code from %switkey_member where uid = '%d'", TABLEPRE, $edituid ) );
			$sec_code = keke_user_class::get_password ( $fds ['password'], $slt );
			$fds ['sec_code'] = $sec_code;
			$newpwd  = $fds ['password'];
			$pwd = md5 ( $fds ['password'] );
			$fds [password] = $pwd;
			Dbfactory::execute ( sprintf ( "update %switkey_member set password ='%s' where uid=%d", TABLEPRE, $pwd, $edituid ) );
			
		}else{
	 		unset($fds['password']);
	 	}
	 	keke_user_class::user_edit ( $uinfo['username'], '', $newpwd,$email,1,0);
		$space_class->save ( $fds, array ("uid" => "$edituid" ) ); //存储信息 
		Keke::admin_system_log ( $_lang['edit_member'] . $member_arr [username] );
		//Keke::notify_user ( $_lang['system_message'], $message_str, $edituid, $m_info ['username'] );
		Keke::admin_show_msg ( $_lang['edit_success'], $_SERVER ['HTTP_REFERER'],3,'','success' );
	}
}

require $template_obj->template ( 'control/admin/tpl/admin_user_add' ); */
/* charge  充值    if($check_uid){
	CHARSET=='gbk' and $check_uid = Keke::utftogbk($check_uid);
	$info = get_info($check_uid);
	$info and Keke::echojson('',1,$info) or Keke::echojson($_lang['none_exists_uid_or_username'],0);
	die();
}
if($is_submit){
	$url = "index.php?do=$do&view=$view";
	$user or Keke::admin_show_msg($_lang['username_uid_can_not_null'],$url,3,'','warning');
	$info = get_info($user);
	$cash = floatval($cash);$credit = floatval($credit);
	if($cash<-$info['balance']){
		Keke::admin_show_msg($_lang['user_deduct_limit'].$info['balance'].$_lang['yuan'],$url,3,'','warning');
	}elseif($credit<-$info['credit']){
		Keke::admin_show_msg($_lang['user_deduct_limit'].$info['balance'].CREDIT_NAME,$url,3,'','warning');
	}
	($cash==0&&$credit==0) and Keke::admin_show_msg($_lang['cash_can_not_null'],$url,3,'','warning');
	$cash_type or $cash = -$cash;
	$credit_type or $credit=-$credit;
	$res = keke_finance_class::cash_in($info['uid'], floatval($cash),floatval($credit),'admin_charge','','admin_charge');
	//fina_mem 充值事由
	$charge_reason = Keke::filter_input($charge_reason);
	$sql2 = "update " . TABLEPRE . "witkey_finance set  fina_mem='{$charge_reason}' where fina_id = last_insert_id()";
	Dbfactory::execute ( $sql2 );
	$res and Keke::admin_show_msg($_lang['charge_success'],$url,3,'','success') or Keke::admin_show_msg($_lang['charge_fail'],"index.php?do=$do&view=$view",3,'','warning');
}
function get_info($uid){
	$sql = " select balance,credit,uid from %switkey_space where ";
	is_numeric($uid) and $sql.=" uid='%d'" or $sql.=" username='%s'";
	return  Dbfactory::get_one(sprintf($sql,TABLEPRE,$uid));
} */