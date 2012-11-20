<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-账号管理首页-用户充值记录
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_finance_recharges extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'finance';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'recharges';
	
	function action_index(){
		//编号		金额 	账户 	状态 	时间
		$fields = '`rid`,`cash`,`bank`,`status`,`pay_time`';
		$query_fields = array('rid'=>'编号','status'=>'状态','pay_time'=>'时间');
		
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'pay_time';
		$base_uri = BASE_URL.'/index.php/user/finance_recharges	';
		extract($this->get_url($base_uri));
		//收件	条件
		$where .= ' and uid = '.$_SESSION['uid'];
		$data_info = Model::factory('witkey_recharge')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
		//显示分页的页数
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('user/finance/recharges');
	}
}