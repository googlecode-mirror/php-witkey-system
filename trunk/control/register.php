<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * �û�ע��
 * @copyright keke-tech
 * @author Michael
 * @version v 2.2 2012-11-06
 */
class Control_register extends Control_front{
	
	function action_index(){
		global $_K, $_lang;
		//var_dump($_SESSION); 
		
		 
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
		var_dump($res);die;
		if($res<0){
			$res = 'ע��ʧ��:'.Keke_user_register::$_status[$res];
		}elseif (Keke_valid::numeric($res) and $res>0){
			$res = 'ע��ɹ�';
		}else{
			//���Ϻ����html
			$res .='ע��ɹ�';
		}
		Keke::show_msg($res,'register','success');
	}
	
}


/* //����ǹ���Ա����û��Ͳ����ж�
($uid && !isset($_SESSION['auid'])) and Keke::show_msg ( $_lang['friendly_notice'], 'index.php', 3, $_lang['you_has_login'],'warning');
$page_title=$_lang['register'].'-'.$_K['html_title'];
//��ʼ������
$reg_obj = new keke_register_class();
$api_name = keke_global_class::get_open_api();
 

if (isset($formhash)&&Keke::submitcheck($formhash)){ 
	
	//�û�ע��
	$reg_uid = $reg_obj->user_register($txt_account, md5($pwd_password), $txt_email,$txt_code,1,$pwd_password);
	$user_info = keke_user_class::get_user_info($reg_uid); 
	if(isset($unit)&&$unit){
		$unit_obj = new kk_client($app_key, $app_secret);
		$task_url = $unit_obj->clientlogin($user_info['uid']);
		keke_function_class::curl_request($task_url,"get",null);
	}
	 //�û���¼
	$reg_obj->register_login($user_info);
}

//�첽���
if (isset ( $check_email ) && ! empty ( $check_email )) {
	$res = keke_user_class::check_email ( $check_email );  
	echo  $res;
	die ();
}

if (isset ( $check_username ) && ! empty ( $check_username )) {
	 $res =  keke_user_class::check_username ( $check_username );
	 echo  $res;
	 die ();
}
require Keke_tpl::template ( $do ); */