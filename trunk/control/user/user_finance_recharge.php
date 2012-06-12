<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8下午06:42:39
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$step_list = array ("step1" => array ($_lang['step_one'],$_lang['input_recharge_money'] ), "step2" => array ($_lang['step_two'], $_lang['choose_pay_account'] ),"step3" => array ($_lang['step_three'], $_lang['waiting_for_background_review'] ));
$step or $step = 'step1';

$verify = kekezu::reset_secode_session($ver?0:1);//安全码输入
$ac_url = $origin_url . "&op=$op&ver=".intval($ver);

switch ($step) {
	case "step1" : 
		if ($reset) {
			$_SESSION ['charge_cash'] = '';
		}elseif ($choose_cash) {
			if ($cash) {
				$cash>=$pay_arr['recharge_min']['v'] or kekezu::show_msg ($_lang['alert_money_too_title'], $ac_url, "3", "", "warning" );
				$_SESSION ['charge_cash'] = $cash;
				header ( "Location:" . $ac_url . "&step=step2&cash=$cash" );
			} else {
				kekezu::show_msg ($_lang['not_choose_recharge_money'], $ac_url, "3", "", "warning" );
			}
		}
		
		break;
	case "step2" :
		$cash != $_SESSION ['charge_cash'] and kekezu::show_msg ( $_lang['alert_return_rewrite'], $ac_url . "&step=step1&reset=1", "3", "", "warning" );
		switch ($pay_type) { //充值方式
			case "online_charge" : //在线充值
				$total_money = trim ( sprintf ( "%10.2f", abs ( floatval ( ($cash) ) ) ) );
				$now = time ();
				$randno = rand ( 1000, 9999 );
				$user_info = kekezu::get_user_info ( $uid );
				$paytitle = $username . "(" . $_K ['html_title'] . $_lang['balance_recharge'] . trim ( sprintf ( "%10.2f", $total_money ) ) . $_lang['yuan'].")";
				
				if (isset ( $ajax ) && $ajax == 'confirm') { //确认充值
					$payment_config = kekezu::get_payment_config($pay_mode);
					require S_ROOT . "/payment/" . $pay_mode . "/order.php";
					$order_id=keke_order_class::create_user_charge_order('online_charge', $payment_config['payment'],$total_money);
					$from = get_pay_url('user_charge',$total_money, $payment_config, $paytitle, $order_id,'0','0');
				   $title=$_lang['confirm_pay'];
				   
					require keke_tpl_class::template ( "pay_cash");die();
					//kekezu::show_msg($_lang['operate_notice'],'',3,$from,"confirm_info",'success');
				}
				break;
				
			case "offline_charge" : //线下充值
	            $pay_info=kekezu::escape($pay_info);
				$order_id=keke_order_class::create_user_charge_order('offline_charge', $pay_account,$cash,'',$pay_info);
				 
				if($order_id){
					unset($_SESSION ['charge_cash']);
					kekezu::show_msg ( $_lang['order_submit_success_notice'],$ac_url."&step=step3&cash=$cash#userCenter","3",'','success');
				}else{
					kekezu::show_msg ( $_lang['order_submit_fail'],$_SERVER['HTTP_REFERER'],3,'','warning');
				}
				
				break;
		}
		
		break;
	case "step3" :
		break;
}

 
require keke_tpl_class::template ( "user/" . $do . "_" . $view . "_" . $op );


