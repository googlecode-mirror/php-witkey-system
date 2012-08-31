<?php  defined ( 'IN_KEKE' ) or exit('Access Denied');
/**
 * this not free,powered by keke-tech
 * @version kppw 2.0
 * @auther 九江
 * 
 */
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set ( 'PRC' );
define ( "S_ROOT", dirname ( __FILE__ ).DIRECTORY_SEPARATOR);
ini_set('unserialize_callback_func', 'spl_autoload_call');
require (S_ROOT . 'class/keke/core.php');

/*
 * 如果网站在子目录下，就写目录名称 ，后面不要加斜杠，
 * 如果网站在目录就设为空
*/
define('BASE_URL', '/kppw_google');

$exec_time_traver = Keke::exec_js('get');
(!isset($exec_time_traver)||$exec_time_traver<time()) and $exec_time_traver = true or $exec_time_traver = false;

isset($_GET['inajax']) and $_K['inajax']= $_GET['inajax'];
isset($_GET['ajaxmenu']) and $_K['ajaxmenu'] = $_GET['ajaxmenu'];
 
unset ( $uid, $username);

/**
 * 支持子目录的路由
 */

//支持子目录的路由
Route::set('sections', '<directory>(/<controller>(/<action>(/<id>)))',
array(
'directory' => '(admin|test|ui)'
		))
		->defaults(array(
		'controller' => 'login',
		'action'     => 'index',
		));
		
 Route::set('default', '(<controller>(/<action>(/<id>(/<ids>))))',array
('ids'=>'.*'))
->defaults(array(
'controller' => 'index',
'action'     => 'index',
));    

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


//uir路由测试
//$uri = "ui/test/index";
/* foreach (Route::all() as $r){
	 var_dump($r->matches($uri));
} */
// die(); 