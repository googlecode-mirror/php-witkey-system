<?php
keke_lang_class::load_lang_class('service_shop_class');
class service_shop_class {
	/**
	 * 
	 * �ú�����������ɾ��
	 * @param  $service_ids ������ids
	 */
	function service_del($service_ids) {
		is_array ( $service_ids ) and $ids = implode ( ",", $service_ids ) or $ids = $service_ids;
		return dbfactory::execute ( sprintf ( "delete from %switkey_service where service_id in(%s)", TABLEPRE, $ids ) );
	}
	
	/**
	 * 
	 * �ú������ڷ����¼ܲ���..
	 * @param  $service_ids ������ids
	 */
	function service_down($service_ids) {
		is_array ( $service_ids ) and $ids = implode ( ",", $service_ids ) or $ids = $service_ids;
		return dbfactory::execute ( sprintf ( "update %switkey_service set service_status='%d'  where service_id in(%s)", TABLEPRE, 3, $ids ) );
	}
	/**
	 * 
	 * �ú������ڷ�����ò���..
	 * @param  $service_ids ������ids
	 */
	function service_disable($service_ids) {
		is_array ( $service_ids ) and $ids = implode ( ",", $service_ids ) or $ids = $service_ids;
		return dbfactory::execute ( sprintf ( "update %switkey_service set service_status='%d'  where service_id in(%s)", TABLEPRE, 4, $ids ) );
	}
	/**
	 * 
	 * �ú������ڷ�����˲���..
	 * @param  $service_ids ������ids
	 */
	function service_pass($service_ids) {
		is_array ( $service_ids ) and $ids = implode ( ",", $service_ids ) or $ids = $service_ids;
		return dbfactory::execute ( sprintf ( "update %switkey_service set service_status='%d'  where service_id in(%s)", TABLEPRE, 2, $ids ) );
	}
	
	/**
	 * 
	 * �ú������ڷ������ò���..
	 * @param  $service_ids ������ids
	 */
	function service_use($service_ids) {
		is_array ( $service_ids ) and $ids = implode ( ",", $service_ids ) or $ids = $service_ids;
		return dbfactory::execute ( sprintf ( "update %switkey_service set service_status='%d'  where service_id in(%s)", TABLEPRE, 5, $ids ) );
	}
	
	/**
	 * 
	 * �ú����������ͷ��񶩵�����ɾ��
	 * @param  $order_ids ������ids
	 */
	function order_del($order_ids) {
		is_array ( $order_ids ) and $ids = implode ( ",", $order_ids ) or $ids = $order_ids;
		return dbfactory::execute ( sprintf ( "delete from %switkey_order where order_id in(%s)", TABLEPRE, $ids ) );
	}
	
	/**
	 *���ƶ�������
	 *@param int $order_id �������
	 *@param string $action ��ǰ��������
	 */
	public function dispose_order($order_id, $action) {
		global $uid, $username, $_K, $kekezu,$_lang;
		
		$order_info = keke_order_class::get_order_info ( $order_id ); //��ȡ����
		if ($order_info) {
			$s_order_link = "<a href=\"" . $_K['siteurl'] . "/index.php?do=user&view=finance&op=order&obj_type=service&role=1&order_id=" . $order_id . "\">" . $order_info ['order_name'] . "</a>";
			$b_order_link = "<a href=\"" . $_K['siteurl'] . "/index.php?do=user&view=finance&op=order&obj_type=service&role=2&order_id=" . $order_id . "\">" . $order_info ['order_name'] . "</a>";
			if ($uid == $order_info ['order_uid'] || $uid == $order_info ['seller_uid']) {
				$service_info = keke_shop_class::get_service_info ( $order_info ['obj_id'] ); //������Ϣ
				if ($service_info ['service_status'] == '2') { //�����ϼܵ�
					if ($action == 'delete') { //ɾ������
						keke_order_class::del_order ( $order_id, '', 'json' );
					} else {
						switch ($action) {
							case "ok" : //���Ϊ����״̬
								$suc = keke_finance_class::cash_out ( $order_info ['order_uid'], $order_info ['order_amount'], 'buy_service', '', 'service', $order_info ['obj_id'] );
								if ($suc) {
									keke_order_class::set_order_status ( $order_id, $action ); //״̬���
									/** ֪ͨ����*/
									$v_arr = array ($_lang['user'] => $order_info ['order_username'], $_lang['action'] => $_lang['haved_confim_pay'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $s_order_link );
									keke_shop_class::notify_user ( $order_info ['seller_uid'], $order_info ['seller_username'], "order_change", $_lang['goods_order_confirm_pay'], $v_arr );
									kekezu::keke_show_msg ( '', $_lang['order_complete_and_comfirm_pay'], '', 'json' );
								} else {
									kekezu::keke_show_msg ( '', $_lang['order_pay_fail_for_cash_little'].'<br>'.$_lang['click'].'<a href="' . $_K['siteurl'] . '/index.php?do=pay&order_id=' . $order_id . '" target="_blank">'.$_lang['go_recharge'].'</a>', 'error', 'json' );
								}
								break;
							case "close" : //���Ϊ�ر�״̬close(��ҹرն���)
								$res = keke_order_class::order_cancel_return ( $order_id ); //����
								if ($res) {
									keke_order_class::set_order_status ( $order_id, $action ); //״̬���
									

									/** ֪ͨ����*/
									$v_arr = array ($_lang['user'] => $order_info ['order_username'], $_lang['action'] => $_lang['close_order_have'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $s_order_link );
									keke_shop_class::notify_user ( $order_info ['seller_uid'], $order_info ['seller_username'], "order_change", $_lang['goods_order_close'], $v_arr );
									
									kekezu::keke_show_msg ( '', $_lang['order_deal_complete_and_close'], '', 'json' );
								} else {
									kekezu::keke_show_msg ( '', $_lang['order_deal_fail_and_link_kf'], 'error', 'json' );
								}
								break;
							case "accept" : //���Ϊ����(����ȷ�Ͻ��ն���)
								$res = keke_order_class::set_order_status ( $order_id, $action ); //״̬���
								if ($res) {
									/** ��������ƹ����*/
									$kekezu->init_prom ();
									if(kekezu::$_prom_obj->is_meet_requirement ( "service", $order_info[obj_id] )){
										kekezu::$_prom_obj->create_prom_event ( "service", $order_info ['order_uid'], $order_info ['obj_id'], $order_info ['order_amount'] );
									}/** ֪ͨ���*/
									$v_arr = array ($_lang['user'] => $order_info ['seller_username'], $_lang['action'] => $_lang['recept_your_order'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $b_order_link );
									keke_shop_class::notify_user ( $order_info ['order_uid'], $order_info ['order_username'], "order_change", $_lang['goods_order_recept'], $v_arr );
									kekezu::keke_show_msg ( '', $_lang['order_deal_complete_and_order_recept'], '', 'json' );
								} else {
									kekezu::keke_show_msg ( '', $_lang['order_deal_fail_and_link_kf'], 'error', 'json' );
								}
								break;
							case "send" : //���Ϊ�ѷ���״̬(����ȷ�Ϸ������)
								$res = keke_order_class::set_order_status ( $order_id, $action ); //״̬���
								if ($res) {
									/** ֪ͨ���*/
									$v_arr = array ($_lang['user'] => $order_info ['seller_username'], $_lang['action'] => $_lang['confirm_service_complete'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $b_order_link );
									keke_shop_class::notify_user ( $order_info ['order_uid'], $order_info ['order_username'], "order_change", $_lang['service_order_confirm_complete'], $v_arr );
									kekezu::keke_show_msg ( '', $_lang['order_deal_complete_and_order_comfirm'], '', 'json' );
								} else {
									kekezu::keke_show_msg ( '', $_lang['order_deal_fail_and_link_kf'], 'error', 'json' );
								}
								break;
							case "confirm" : //���Ϊ���״̬(���ȷ�Ϸ������)
								$res = keke_order_class::set_order_status ( $order_id, $action ); //״̬���
								if ($res) {
									$model_info = kekezu::$_model_list [$order_info['model_id']]; //ģ����Ϣ
									$profit = $service_info ['profit_rate'] * $order_info ['order_amount'] / 100; //��վ����
									///���һ�ÿ���
									keke_finance_class::cash_in ( $order_info ['seller_uid'], $order_info ['order_amount'] - $profit, '0', 'sale_service', '', 'service', $order_info ['obj_id'], $profit );
									/** ������Ʒ�۳���¼**/
									keke_shop_class::plus_sale_num ( $order_info ['obj_id'], $order_info ['order_amount'] );
									/** ˫����������**/
									keke_user_mark_class::create_mark_log ( $model_info ['model_code'],2, $order_info ['order_uid'], $order_info ['seller_uid'], $order_info ['obj_id'], $order_info ['order_amount'] - $profit, $order_info ['obj_id'], $order_info ['order_username'], $order_info ['seller_username'] );
									keke_user_mark_class::create_mark_log ( $model_info ['model_code'],1, $order_info ['seller_uid'], $order_info ['order_uid'], $order_info ['obj_id'], $order_info ['order_amount'], $order_info ['obj_id'], $order_info ['seller_username'], $order_info ['order_username'] );
									/** mark_num+2**/
									keke_shop_class::plus_mark_num ( $order_info ['obj_id'] );
									/** ��������ƹ����*/
									$kekezu->init_prom ();
									kekezu::$_prom_obj->dispose_prom_event ( "service", $order_info ['order_uid'], $order_info ['obj_id'] );
									
									/** ֪ͨ����*/
									$v_arr = array ($_lang['user'] => $order_info ['order_username'], $_lang['action'] => $_lang['confirm_service_complete'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $s_order_link );
									keke_shop_class::notify_user ( $order_info ['seller_uid'], $order_info ['seller_username'], "order_change", $_lang['service_order_confirm_complete'], $v_arr );
									kekezu::keke_show_msg ( '', $_lang['order_deal_complete_the_order_complete'], '', 'json' );
								} else {
									kekezu::keke_show_msg ( '', $_lang['order_deal_fail_and_link_kf'], 'error', 'json' );
								}
								break;
							case "arbitral" : //�����ٲ�
								$res = keke_order_class::set_order_status ( $order_id, $action ); //״̬���
								if ($res) {
									if ($uid == $order_info ['order_uid']) {
										/** ֪ͨ�Է�*/
										$v_arr = array ($_lang['user'] => $order_info ['order_username'], $_lang['action'] => $_lang['buyer_start_arbitrate'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $s_order_link );
										keke_shop_class::notify_user ( $order_info ['seller_uid'], $order_info ['seller_username'], "order_change", $_lang['sevice_order_arbitrate_submit'], $v_arr );
									
									} else {
										/** ֪ͨ�Է�*/
										$v_arr = array ($_lang['user'] => $order_info ['seller_username'], $_lang['action'] => $_lang['seller_start_arbitrate'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $b_order_link );
										keke_shop_class::notify_user ( $order_info ['order_uid'], $order_info ['order_username'], "order_change", $_lang['sevice_order_arbitrate_submit'], $v_arr );
									
									}
									kekezu::keke_show_msg ( '', $_lang['order_deal_complete_and_order_in_arbitrate'], '', 'json' );
								} else {
									kekezu::keke_show_msg ( '', $_lang['order_deal_fail_and_link_kf'], 'error', 'json' );
								}
								break;
						}
					}
				} else { //ϵͳ�رն���
					$res = keke_order_class::set_order_status ( $order_id, 'close' ); //�����ر�
					keke_order_class::order_cancel_return ( $order_id ); //����
					/** ֪ͨ���*/
					$v_arr = array ($_lang['user'] => $_lang['system'], $_lang['action'] => $_lang['stop_your_order_and_your_cash_return'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $b_order_link );
					keke_shop_class::notify_user ( $order_info ['order_uid'], $order_info ['order_username'], "order_change", $_lang['goods_order_close'], $v_arr );
					/** ֪ͨ����*/
					$v_arr = array ($_lang['user'] => $_lang['system'], $_lang['action'] => $_lang['stop_your_order_and_your_cash_return'], $_lang['order_id'] => $order_id, $_lang['order_link'] => $s_order_link );
					keke_shop_class::notify_user ( $order_info ['seller_uid'], $order_info ['seller_username'], "order_change", $_lang['goods_order_close'], $v_arr );
					kekezu::keke_show_msg ( '', $_lang['goods_down_shelf_and_trade_close'], 'error', 'json' );
				}
			} else {
				kekezu::keke_show_msg ( '', $_lang['error_order_num_notice'], 'error', 'json' );
			}
		} else {
			kekezu::keke_show_msg ( '', $_lang['no_exist_goods_order'], 'error', 'json' );
		}
	}
	/**
	 * ���ݶ���״̬������ȡ��ǰ������ť
	 * @param int $role ��ɫ  1=>������,2=>�µ���
	 * @return $process_arr[��ɫ]=array(
	 * trans=>array()���ײ���
	 * after=>�ۺ����
	 * other=>��������
	 * )
	 */
	public static function process_action($role = '1', $order_status) {
		global $_lang;
		$process_arr = array ();
		switch ($order_status) {
			case "wait" : //������
				/** �µ���**/
				$process_arr ['2'] ['trans'] ['ok'] = $_lang['confirm_pay']; //����
				$process_arr ['2'] ['trans'] ['close'] = $_lang['cancel_order']; //�ر�
				/** ������**/
				$process_arr ['1'] ['trans'] [''] = $_lang['wait_pay']; //�ȴ�����
				break;
			case "ok" : //����
				/** �µ���**/
				$process_arr ['2'] ['trans'] [''] = $_lang['wait_seller_confirm_order']; //�ȴ�ȷ��
				/** ������**/
				$process_arr ['1'] ['trans'] ['accept'] = $_lang['recept_order']; //����
				break;
			case "accept" : //����
				/** �µ���**/
				$process_arr ['2'] ['after'] ['arbitral'] = $_lang['trate_rights']; //�ٲ�
				$process_arr ['2'] ['trans'] [''] = $_lang['wait_seller_confirm_service']; //�ȴ�ȷ�Ϸ���
				/** ������**/
				$process_arr ['1'] ['trans'] ['send'] = $_lang['confirm_service']; //ȷ�Ϸ���
				$process_arr ['1'] ['after'] ['arbitral'] = $_lang['trate_rights']; //�ٲ�
				break;
			case "send" :
				/** �µ���**/
				$process_arr ['2'] ['trans'] ['confirm'] = $_lang['confirm_service']; //ȷ�Ϸ���
				$process_arr ['2'] ['after'] ['arbitral'] = $_lang['trate_rights']; //�ٲ�
				/** ������**/
				$process_arr ['1'] ['after'] ['arbitral'] = $_lang['trate_rights']; //�ٲ�
				$process_arr ['1'] ['trans'] [''] = $_lang['wait_buyer_confirm_service']; //�ȴ�ȷ��
				break;
			case "confirm" :
				/** �µ���**/
				$process_arr ['2'] ['trans'] ['mark'] = $_lang['each_mark']; //����
				/** ������**/
				$process_arr ['1'] ['trans'] ['mark'] = $_lang['each_mark']; //����
				break;
			case "close" :
				/** �µ���**/
				$process_arr ['2'] ['other'] ['delete'] = $_lang['delete_order']; //ɾ������
				/** ������**/
				$process_arr ['1'] ['other'] ['delete'] = $_lang['delete_order']; //ɾ������
				break;
			case "arbitral" :
				/** �µ���**/
				$process_arr ['2'] ['after'] [''] = $_lang['wait_kf_deal']; //�ٲ�
				/** ������**/
				$process_arr ['1'] ['after'] [''] = $_lang['wait_kf_deal']; //�ٲ�
				break;
		}
		return $process_arr [$role];
	}
	/**
	 * ���ͷ���״̬
	 * �ϼܺ��¼�״̬�ǶԷ����߶��ԣ����ú���������Թ���Ա����
	 */
	public static function get_service_status() {
		global $_lang;
		return array ("1" => $_lang['wait_audit'], "2" => $_lang['on_shelf'], "3" => $_lang['down_shelf'], "4" => $_lang['disable'], "5" => $_lang['disable'] );
	}
	/**
	 * ���ط��񶩵�״̬
	 */
	public static function get_order_status() {
		global $_lang;
		return array ('wait' => $_lang['wait_buyer_pay'], 'ok' => $_lang['buyer_haved_pay'], 'accept' => $_lang['seller_haved_recept'], 'send' => $_lang['seller_haved_service'], 'confirm' => $_lang['trade_complete'], 'close' => $_lang['trade_close'], 'arbitral' => $_lang['order_arbitrate'],'arb_confirm'=>$_lang['trade_complete']);
	}

}

?>