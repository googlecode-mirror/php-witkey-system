<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-08����02:57:33
 */


/**
 * ������άȨ��
 */
if ($op == 'report') {
	$title =$_lang['order_rights_submit'];
	$to_uid=intval($to_uid);
	$obj_id = intval($obj_id);
	if ($sbt_edit) {
		keke_order_class::set_report ( $obj_id, $to_uid, $to_username, $type, $file_url, $tar_content );
	} else {
		$order_info = keke_order_class::get_order_info ( $obj_id ); //������Ϣ
		if ($type == '1') { //���ﴫ�ݵ���role
			$to_uid = $order_info ['order_uid'];
			$to_username = $order_info ['order_username'];
		} else {
			$to_uid = $order_info ['seller_uid'];
			$to_username = $order_info ['seller_username'];
		}
		$type = "1"; //άȨ
		require keke_tpl_class::template ( "report" );
	}
	die ();
}
$ops = array ('detail', 'recharge', 'withdraw', 'order' ,'prom');

in_array ( $op, $ops ) or $op = "detail";
/**
 * �Ӽ��˵�
 */
$sub_nav = array(
	array ("detail" => array ($_lang['accounts_detail'], "chart-line" ),
 		//"order" => array ($_lang['order_trading'], "case-1" ),
		"prom" => array ($_lang['prom_make_money'], "emotion-smile" ) ),
	array (
 		"recharge" => array ($_lang['account_recharge'], "cur-yen" ),
 		"withdraw" => array ($_lang['account_withdraw'], "clipboard-copy" ))
	);
$pay_arr = Keke::get_table_data ( "k,v", "witkey_pay_config", '', '', '', '', 'k' ); //���֡���ֵ����
$payment_list = Keke::get_payment_config (); //����֧���ӿ�����
//$offline_pay_list = Keke::get_table_data ( "*", "witkey_pay_api", " type='offline'", '', '', '', 'payment' ); //����֧����ʽ


require 'user_' . $view . '_' . $op . '.php';

