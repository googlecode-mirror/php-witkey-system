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
		$bank_abb = Keke_global::get_bank_code();
		$charge_uri = URL::site('user/finance_recharge/charge');
		require Keke_tpl::template('user/finance/recharge');
	}
	
	function action_charge(){
		//订单编号
		$order_id = $_POST['hdn_order_id'];
		//付款金额
		$pay_amount = abs((float)$_POST['amount']);
		//支付接口名称
		$pay_name = $_POST['pay'];
		//通过pay_name 得到pay_id
		$pay_id = DB::select('pay_id')->from('witkey_pay_api')->where("payment='$pay_name'")->get_count()->execute();
		//银行代码
		$bank_code = $_POST['bank_code'];
		
		$title = $order_id?'订单充值,订单编号:'.$order_id:'余额充值';
		//充值 标题
		$subject = Keke::$_sys_config['website_name'].$title;
		
		$rid = $this->save_charge_recode($pay_id, $order_id, $pay_amount, $title);
		
		echo Sys_payment::factory($pay_name)->get_pay_html('post', $pay_amount, $subject, $order_id, $rid);
	}
	/**
	 * 生成充值记录
	 */
	function save_charge_recode($pay_id,$order_id,$amount,$mem){
		$columns = array('pay_id','order_id','uid','username','pay_time','cash','status','mem');
		$values = array($pay_id,$order_id,$_SESSION['uid'],$_SESSION['username'],SYS_START_TIME,$amount,'wait',$mem);
		return (int)DB::insert('witkey_recharge')->set($columns)->value($values)->execute();
	}
}