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
		
		$sql = sprintf("select a.*,
				b.pay_id pid,b.payment bpayment,b.type btype,b.pay_user bpay_user,b.pay_account bpay_account,b.pay_name bpay_name,b.status bstatus 
				from %switkey_recharge a 
				left join %switkey_pay_api b 
				on a.pay_id= b.pay_id ",TABLEPRE,TABLEPRE);
		$query_fields = array('a.status'=>'状态','a.pay_name'=>'时间');
		if ($_GET['txt_condition']){
			//查询状态转换
			$this->turn_search($_GET['txt_condition']);
		}
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'pay_time';
		$base_uri = BASE_URL.'/index.php/user/finance_recharges	';
		extract($this->get_url($base_uri));
		
		//收件	条件
		$where .= ' and a.uid = '.$_SESSION['uid'];
		
		$data_info = Model::sql_grid($sql,$where,$uri,$order,$group_by,$page,$count,$_GET['page_size'],null);
		
		$data_list = $data_info['data'];
		//显示分页的页数
		$pages = $data_info['pages'];
		//充值状态
		$status_arr = Sys_order::get_recharge_status ();
		
		require Keke_tpl::template('user/finance/recharges');
	}
	
	//充值状态转换
	function turn_search($cond){
		switch ($cond){
			case '待确认':
				$_GET['txt_condition']='wait';
				break;
			case '已付款':
				$_GET['txt_condition']='ok';
				break;
			case '付款失败':
				$_GET['txt_condition']='fail';
				break;
			case '交易关闭':
				$_GET['txt_condition']='close';
				break;
			default:
				$_GET['txt_condition']=$cond;
		}
	}
}