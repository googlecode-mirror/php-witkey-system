<?php   defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ϵͳ����
 * 
 * @author Michael
 *
 */
class Sys_misc {
	
	/**
	 * �б�����,Ĭ��ֻȡ��ͨ�б꣬
	 * $all == true ȡȫ�����б����� 
	 * @param string  $model_code
	 * @param bool $all
	 */
	public static function get_cash_cove($model_code = 'tender', $all = false) {
		$w = '';
		if ($all === false) {
			$w = " model_code ='$model_code'";
		}
		return Keke::get_table_data ( '*', "witkey_task_cash_cove", $w, "start_cove", '', '', 'cash_rule_id', null );
	}
}
