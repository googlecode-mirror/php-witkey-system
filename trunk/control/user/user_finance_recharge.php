<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8下午06:42:39
 */


$step_list = array ("step1" => array ($_lang['step_one'],$_lang['input_recharge_money'] ), "step2" => array ($_lang['step_two'], $_lang['choose_pay_account'] ),"step3" => array ($_lang['step_three'], $_lang['waiting_for_background_review'] ));
$step or $step = 'step1';

//$verify = kekezu::reset_secode_session($ver?0:1);//安全码输入
$ac_url = $origin_url . "&op=$op&ver=".intval($ver);


// var_dump($offline_pay_list);

$offline_pay_list = get_pay_config('','offline');

$payment_list = get_pay_config();

switch ($step) {
	case "step2" :
		 
		switch ($pay_type) { //充值方式
			case "online_charge" : //在线充值
				$total_money = trim ( sprintf ( "%10.2f", abs ( floatval ( ($cash) ) ) ) );
				$now = time ();
				$randno = mt_rand ( 1000, 9999 );
			   $paytitle = $username . "(" . $_K ['html_title'] . $_lang['balance_recharge'] . trim ( sprintf ( "%10.2f", $total_money ) ) . $_lang['yuan'].")";
				
				if (isset ( $ajax ) && $ajax == 'confirm') { //确认充值
					$payment_config = kekezu::get_payment_config($pay_mode);
					
					require S_ROOT . "/payment/" . $pay_mode . "/order.php";
					
					$order_id=keke_order_class::create_user_charge_order('online_charge', $payment_config['payment'],$total_money);
					if($pay_mode==='tenpay'){
						$service = $bank_type;
					}else{
						$service = null;
					}
				   $from = get_pay_url('user_charge',$total_money, $payment_config, $paytitle, $order_id,'0','0',$service);
				   $title=$_lang['confirm_pay'];
				   
				   require keke_tpl_class::template ( "pay_cash");
					die();
					//kekezu::show_msg($_lang['operate_notice'],'',3,$from,"confirm_info",'success');
				}
				break;
				
			case "offline_charge" : //线下充值
				if (isset($formhash)&&kekezu::submitcheck($formhash)) {
		            $pay_info=kekezu::escape($pay_info);
		            $cash = keke_curren_class::convert(abs($recharge),0,true)+0;
					$order_id=keke_order_class::create_user_charge_order('offline_charge', $pay_account,$cash,'',$pay_info);
					 if($order_id){
						kekezu::show_msg ( $_lang['system prompt'],$ac_url."&op=detail&action=charge#userCenter",'3',"{$_lang['order_submit_success_notice']}",'alert_right');
						
					}else{
						kekezu::show_msg ( $_lang['system prompt'],$ac_url."&step=step2&show=offline#userCenter",'3',"{$_lang['order_submit_fail']}",'alert_error');
					}
				}
				break;
		}
		
		break;

}
//获取支付接口配置
function get_pay_config($paymentname = "", $pay_type = 'online'){
  $where = " 1=1 ";
  $paymentname and $where  .= " and payment='$paymentname' ";
  $pay_type and  $where .= " and type = '$pay_type' ";	
  $list=  kekezu::get_table_data ( '*', "witkey_pay_api", $where, "pay_id asc", '', '', '', null );
   
  $tmp = array();
  foreach ($list as $k=>$v){
  	  $pay_config ['payment'] = $v ['payment'];
	  $pay_config ['pay_id'] = $v ['pay_id'];
	  $pay_config ['type'] = $v ['type'];
	  $config = unserialize ( $v['config'] );
	  $r = array_merge($config,$pay_config);
	  $tmp[$v ['payment']] = $r;
  }
  return $tmp;
	
}
//腾讯网银的bank_type，对应的图片名称
function get_ten_bank_type(){
	static $bank = array(
			"1001"=>"17",   
			"1002"=>"10",
			"1003"=>"2",
			"1004"=>"9",
			"1005"=>"1",
			"1006"=>"4",
			"1008"=>"8",
			"1009"=>"27",
			"1010"=>"18",
			"1020"=>"5",
			"1021"=>"7",
			"1022"=>"3",
			"1024"=>"20",
			"1025"=>"22",
			"1027"=>"6",
			"1032"=>"11",
			"1033"=>"14",
			"1052"=>"19",
			"8001"=>"logo",
			);
	return $bank;
}
$ten_bank_type_arr = get_ten_bank_type(); 


require keke_tpl_class::template ( "user/" . $do . "_" . $view . "_" . $op );


