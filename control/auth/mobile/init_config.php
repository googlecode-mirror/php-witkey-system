<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
//初始化配置 
$auth_config=array(  
  'auth_code'=>'mobile',
  'auth_title' =>$_lang['mobile_auth'],
  'auth_cash' =>'1-3',
  'auth_day' => '0',
  'auth_expir' =>'0',
  'auth_dir'=>'mobile',
  'auth_small_ico' =>'',
  'auth_small_n_ico' =>'',	
  'auth_big_ico' =>'',
  'auth_desc' => $_lang['mobile_auth'],
  'auth_show' =>'0',
  'auth_open' => '1',
  'update_time' =>'1306225128');
//后台菜单，主要为了控制操作权限，如果这个资源有存在就更新，否则创建
$menu_arr = array(
		'resource_id'=>77,
		'resource_url'=>'index.php/auth/mobile_admin_list',
		'resource_name'=>$_lang['mobile_auth'],
		'submenu_id'=>29);
