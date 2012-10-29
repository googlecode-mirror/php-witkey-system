<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-客服管理首页-用户建议
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_custom_steer extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'custom';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'steer';
	
	function action_index(){
		
		
		
		require Keke_tpl::template('user/custom/steer');
	}
}