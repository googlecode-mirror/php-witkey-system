<?php define ( "IN_KEKE", TRUE );
 
include 'app_boot.php';

//keke_lang_class::load_lang_class('keke_base_class');

$dos = array ('en','captcha','shop_payitem_tools','payitem_tools','wb','oauth_register','register_wizard','case','oauth_login','login','ajax','show_menu', 'index', 'register', 'seccode', 'login', 'logout', 'get_password', 'news_list', 'help', 'help_list', 'help_info', 'talent', 'talent_list','case_list', 'search', 'search_list', 'task_preview', 'task_op', 'message', 'secode_demo', 'release', 'xs_release', 'zb_release', 'user', 'space', 'mark', 'task', 'task_list', 'level_rule', 'shop', 'shop_list', 'footer', 'task_indus_list', 'indus', 'agreement', 'report', 'seccode','bid','work','prom','reset_email','avatar','pay','browser','shop_release','service','shop_order','article','task_map','verify_secode','index_map','shop_map','protocol','excite_email','link','mobile','link','single');
(!empty($do)&& in_array($do, $dos)) and $do or $do='index';

isset($m)&&$m=="user" and  $do ="avatar";



$_K = $_K+Keke::$_sys_config;
$_K['uid'] = Keke::$_uid;
$_K['username'] = Keke::$_username;
$_K['messagecount'] = Keke::$_messagecount;
$_K['user_info'] = Keke::$_userinfo;
$_K['model_list'] = Keke::$_model_list;
$_K['nav_arr'] = Keke::$_nav_list;
$_K['lang_list'] = Keke::$_lang_list;
$_K['language']      = Keke::$_lang;
$_K['api_open']   = Keke::$_api_open;
$_K['weibo_list'] = Keke::$_weibo_list;
$_K['attent_api_open'] = Keke::$_attent_api_open;
$_K['attent_list'] = Keke::$_weibo_attent;
$_K['style_path'] = Keke::$_style_path;
$_K['style_path']=SKIN_PATH;

 
//if(strpos($_SERVER['REQUEST_URI'],'?')){
	//include S_ROOT . 'control/' . $do . '.php';
	//die;
//}else{
// 	$_K['control'] = Request::initial()->controller();
	//var_dump($request);die;
    $request = Request::factory();
    $_K['control'] = $request->initial()->controller();
    $_K['action'] = $request->initial()->action();
    $_K['directory'] = $request->initial()->directory();
    keke_lang_class::package_init("index");
    keke_lang_class::loadlang($_K['control']);
    
	$request->execute();
	 
	die;
//}
