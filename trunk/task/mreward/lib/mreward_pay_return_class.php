<?php
/**
 * 支付回调处理任务订单的结算
 * 
 * @see mreward_release_class
 * 
 */


final class mreward_pay_return_class extends pay_return_base_class {
	function __construct($charge_type, $model_id = '', $uid = '', $obj_id = '', $order_id = '', $total_fee = '') {
		parent::__construct ( $charge_type, $model_id, $uid, $obj_id, $order_id, $total_fee );
	}
	/**
	 * 订单付款
	 * @see pay_return_base_class::order_charge()
	 */
	function order_charge() {
		$task_info = dbfactory::get_one ( sprintf ( "select * from %switkey_task where task_id='%d'", TABLEPRE, $this->_obj_id ) );
		$task_obj = mreward_task_class::get_instance($task_info);
		return $task_obj->dispose_order ( $this->_order_id );	
	}
}