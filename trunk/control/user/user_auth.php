<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8����06:42:39
 */

Keke_lang::package_init ( 'auth' );
Keke_lang::loadlang ( 'auth_add' );  
$keys = array_keys ( $auth_item_list );
$auth_code or $auth_code = $keys ['0']; //Ĭ����֤�� 
$auth_code or Keke::show_msg ( $_lang['param_error'], "index.php?do=auth",3,'','warning' );

if($auth_item_list[$auth_code]){
	$auth_class = "keke_auth_".$auth_code."_class";
	$auth_obj = new $auth_class ( $auth_code ); //��ʼ����֤����
	$auth_item = $auth_item_list [$auth_code]; //��֤��ϸ����
	$auth_dir = $auth_item ['auth_dir']; //��֤�ļ�·��
	$auth_info = $auth_obj->get_user_auth_info ( $uid,0,$show_id); //�û���֤��Ϣ;//������֤��¼
	require S_ROOT."./auth/$auth_code/control/auth_add.php";
}else{
	
	Keke::show_msg($_lang['param_unlaw_or_no_open'],"index.php",3,'','warning');
}