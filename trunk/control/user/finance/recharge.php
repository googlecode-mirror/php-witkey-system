	<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-账号管理首页-用户充值
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_finance_recharge extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'finance';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'recharge';
	
	function action_index(){
		
		
		
		//线上银行信息
		$bank_abb = Keke_global::get_bank_abb();
		require Keke_tpl::template('user/finance/recharge');
	}
}