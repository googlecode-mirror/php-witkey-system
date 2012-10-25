<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 *
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_account_index extends Control_user{
    
	/**
	 * @var 默认选中项
	 */
	protected static $_default = 'account';
	 
	function action_index(){
		
		require Keke_tpl::template('user/account/index');
	}
}