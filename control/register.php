<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 用户注册
 * @copyright keke-tech
 * @author Michael
 * @version v 2.2 2012-11-06
 */
class Control_register extends Control_front{
	
	function action_index(){
		global $_K, $_lang;
	 
		var_dump($_SESSION);
		
		$img = Keke_user::instance()->get_avatar($_SESSION['uid']);
		
 		require Keke_tpl::template('register');
	}
	
	function action_check_username(){
		$username = $_GET['username'];
		$res = Keke_user_register::instance()->check_username($username);
		if($res>0){
			echo TRUE;
		}else{
			echo Keke_user_register::$_status[$res];
		}
	}
	
	function action_check_email(){
		$email = $_GET['email'];
		$res = Keke_user_register::instance()->check_email($email);
		if($res>0){
			echo TRUE;
		}else{
			echo Keke_user_register::$_status[$res];
		}
	}
	
	function action_reg(){
		$_POST = Keke_tpl::chars($_POST);
		Keke::formcheck($_POST['formhash']);
		$username = $_POST['txt_account'];
		$pwd  = $_POST['pwd_password'];
		$email = $_POST['txt_email'];
		$res = Keke_user_register::instance()->set_username($username)->set_pwd($pwd)->set_email($email)->reg();
		if($res<0){
			$res = '注册失败:'.Keke_user_register::$_status[$res];
		}elseif (Keke_valid::numeric($res) and $res>0){
			$res = '注册成功';
		}else{
			//整合后输出html
			$res .='注册成功';
		}
		Keke::show_msg($res,'register','success');
	}
	
}