<?php
 /**
  * 用户中心入口文件
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-08下午02:57:33
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');

//语言包初始化

keke_lang_class::package_init("user");
//keke_lang_class::package_init("payitem");
$do and keke_lang_class::loadlang($do);

// $view = $view?strval($view):'';
$views = array('index','setting','finance','employer','witkey','trans','message','collect','payitem');
!in_array($view,$views) && $view ='index';

($view || $op=='basic' )and keke_lang_class::loadlang("{$do}_{$view}");
$view == 'setting' and  keke_lang_class::loadlang("{$do}_{$op}");
$op  and keke_lang_class::loadlang("{$do}_{$view}_{$op}");

$_K['is_rewrite'] = 0 ;
 
kekezu::check_login ();

$user_info=$kekezu->_userinfo;
/* 中心最顶级url*/
$origin_url="index.php?do=$do&view=$view";

$page_title=$_lang['user_center'];
$nav=array(
"index"=>array($_lang['manage_tpl'],"meter"),
"setting"=>array($_lang['person_config'],"cog"),
"finance"=>array($_lang['finance_manage'],"chart-line2"),
"employer"=>array($_lang['employer_buyer'],"buyer"),
"witkey"=>array($_lang['witkey_seller'],"seller"),
"trans"=>array($_lang['process_right'],"hand-1"),
"message"=>array($_lang['info_center'],"sound-high"),
"collect"=>array($_lang['my_collect'],"star-fav"),
"payitem"=>array($_lang['add_service'],"bookmark-2"));
$user_type = intval($user_info['user_type']);
$user_type==1 and $w=" auth_code!='enterprise' " or ($user_type==2 and $w=" auth_code!='realname' "  or $w='');
 isset($w) and $auth_item_list = keke_auth_base_class::get_auth_item ( null, null, 1 ,$w);

 
require 'user/user_'.$view.'.php';

