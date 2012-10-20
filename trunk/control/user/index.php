<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心首页
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_user_index extends Controller{
    
	function action_index(){
		global $_K,$_lang;
		Keke::init_nav();
		$nav = $_K['nav_arr'];
		$user_info  = Keke::$_userinfo;
		 
		/* 中心最顶级url*/
		//$origin_url="index.php?do=$do&view=$view";
		
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
		 
		
		require Keke_tpl::template('user/index');
	}
	
}