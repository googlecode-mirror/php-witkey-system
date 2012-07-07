<?php
define ( "IN_KEKE", TRUE );
 
include 'app_boot.php';

//keke_lang_class::load_lang_class('keke_base_class');

$dos = array ('captcha','shop_payitem_tools','payitem_tools','wb','oauth_register','register_wizard','case','oauth_login','login','ajax','show_menu', 'index', 'register', 'seccode', 'login', 'logout', 'get_password', 'news_list', 'help', 'help_list', 'help_info', 'talent', 'talent_list','case_list', 'search', 'search_list', 'task_preview', 'task_op', 'message', 'secode_demo', 'release', 'xs_release', 'zb_release', 'user', 'space', 'mark', 'task', 'task_list', 'level_rule', 'shop', 'shop_list', 'footer', 'task_indus_list', 'indus', 'agreement', 'report', 'seccode','bid','work','prom','reset_email','avatar','pay','browser','shop_release','service','shop_order','article','task_map','verify_secode','index_map','shop_map','protocol','excite_email','link','mobile','link','single');
(!empty($do)&& in_array($do, $dos)) and $do or $do='index';
isset($m)&&$m=="user" and  $do ="avatar";

keke_lang_class::package_init("index");
keke_lang_class::loadlang($do);

$page_keyword = Keke::$_sys_config['seo_keyword'];
$page_description = Keke::$_sys_config ['seo_desc'];
// $shop_config = Keke::$_shop_config;
$uid = Keke::$_uid;
$username = Keke::$_username;
$messagecount = Keke::$_messagecount;
$user_info = Keke::$_userinfo;
// $shop_info = Keke::$_shop_info; 
$indus_p_arr = Keke::$_indus_p_arr;
$indus_c_arr = Keke::$_indus_c_arr;
$indus_arr = Keke::$_indus_arr;
$model_list = Keke::$_model_list;
$nav_arr = Keke::$_nav_list;
$lang_list = Keke::$_lang_list;
$language      = Keke::$_lang;
$api_open   = Keke::$_api_open;
$weibo_list = Keke::$_weibo_list;
$attent_api_open = Keke::$_attent_api_open;
$attent_list = Keke::$_weibo_attent;
$style_path = Keke::$_style_path;
$style_path=SKIN_PATH;//自定义样式引用路径。对tpl下使用的js、css文件有效
/** 首页底部链接*/
/* $link_task = Keke::$_model_list;
$link_help = Keke::get_table_data("art_cat_id,cat_name","witkey_article_category","art_cat_pid='100'"," listorder asc","","","",3600);
$link_news =Keke::get_table_data ( "art_cat_id,cat_name", "witkey_article_category", "cat_type='article' and art_cat_pid='1'"," listorder asc", "",  "6", "", 3600 );

$log_account=null;
if(isset($_COOKIE['log_account'])){
	$log_account = $_COOKIE['log_account'];
} */
//$_K[siteurl] = "http://192.168.1.69/kppw20"; 
include S_ROOT . './control/' . $do . '.php';
