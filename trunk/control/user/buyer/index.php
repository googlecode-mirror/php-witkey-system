<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�������ҳ
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_user_buyer_index extends Control_user{
    
	function action_index(){
		global $_K,$_lang;
		 
		 
		
		require Keke_tpl::template('user/index');
	}
	
}