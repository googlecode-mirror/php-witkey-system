<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
//��ʼ������ 
$auth_config=array(
  'auth_code'=>'bank',
  'auth_title' =>$_lang['bank_auth'],
  'auth_cash' =>'1-3',
  'auth_day' =>'1-3',
  'auth_expir' => '',
  'auth_small_ico' =>'',
  'auth_big_ico' =>'',
  'auth_desc' =>$_lang['bank_auth'],
  'auth_show' =>'0',
  'auth_open' =>'1',
  'update_time' =>'1306142441',
);
//��̨�˵�����ҪΪ�˿��Ʋ���Ȩ�ޣ���������Դ�д��ھ͸��£����򴴽�
$menu_arr = array(
		'resource_id'=>68,
		'resource_url'=>'index.php/auth/bank_admin_list',
		'resource_name'=>$_lang['bank_auth'],
		'submenu_id'=>29);
