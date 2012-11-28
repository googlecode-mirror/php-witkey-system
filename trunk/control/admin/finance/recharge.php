<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��ֵ���
 * 
 * @copyright keke-tech
 * @author fu
 * @version v 22
 *          2012-10-9 15:18:30
 */
class Control_admin_finance_recharge extends Control_admin {
	function action_index() {
		// ����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K, $_lang;
		// Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		
		
		$sql = sprintf("select a.*,
				b.pay_id pid,b.payment bpayment,b.type btype,b.pay_user bpay_user,b.pay_account bpay_account,b.pay_name bpay_name,b.status bstatus
				from %switkey_recharge a
				left join %switkey_pay_api b
				on a.pay_id= b.pay_id ",TABLEPRE,TABLEPRE);
		
		//$fields = ' `rid`,`pay_id`,`recharge_pic`,`username`,`cash`,`pay_time`,`status`,`mem` ';
		// Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		
		$query_fields = array ('rid' => $_lang ['id'], 'username' => $_lang ['username'], 'bank' => '����' );
		// �ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval ( $_GET ['count'] );
		// finance������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���finance_recharge
		// So���ﲻ��дΪfinance/recharge
		$base_uri = BASE_URL . "/index.php/admin/finance_recharge";
		
		$del_uri = $base_uri . '/del';
		// Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'pay_time';
		// ����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract ( $this->get_url ( $base_uri ) );
		// ��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::sql_grid($sql,$where,$uri,$order,$group_by,$page,$count,$_GET['page_size'],null);
		//$data_info = Model::factory ( 'witkey_recharge' )->get_grid ( $fields, $where, $uri, $order, $page, $count, $_GET ['page_size'] );
		// �б�����
		$list_arr = $data_info ['data'];
		//var_dump($list_arr);
		// ��ҳ����
		$pages = $data_info ['pages'];
		// �û���
		$group_arr = Keke_admin::get_user_group ();
		
		// ��ֵ��������
		$charge_type_arr = Keke_global::get_charge_type ();
		
		// ��ֵ����
		$bank_arr = Keke_global::get_bank ();
// 		var_dump($list_arr);
		// ��ֵ����״̬
		
		$status_arr = Sys_payment::recharge_status();
		
		// ����֧����ʽ
		$offline_pay = DB::select ()->from ( 'witkey_pay_api' )->where ( "type='offline'" )->execute ();
		$offline_pay = Keke::get_arr_by_key ( $offline_pay, 'payment' );
		
		require Keke_tpl::template ( 'control/admin/tpl/finance/recharge' );
	}
	/**
	 * ������¼��ɾ��,֧�ֵ����ɾ��
	 */
	function action_del() {
		// ɾ������,�����file_id ����ģ���ϵ������������е�
		if ($_GET ['rid']) {
			$where = 'rid = ' . $_GET ['rid'];
		}
		echo Model::factory ( 'witkey_recharge' )->setWhere ( $where )->del ();
	}
	
	/**
	 * ��˳�ֵ����
	 */
	function action_update() {
		global $_lang;
		if (($rid = $_GET ['rid']) == NULL) {
			return FALSE;
		}
		//��������
		$bank_arr = Keke_global::get_bank ();
		
		 $charge_info = DB::select ()->from ( 'witkey_recharge' )->where ( "rid=:rid" )
		->param ( ":rid", $rid )->get_one ()->execute ();
		
		Sys_payment::set_recharge_status($charge_info['uid'], $rid, $_GET['bank'], $charge_info['cash'],$_GET['bank']);
		
		// ��ֵ��־
		Keke::admin_system_log ( $_lang ['confirm_payment_recharge'] . $rid );
	}
	function action_nopass(){
		if (($rid = $_GET ['rid']) == NULL) {
			return false;
		}
		if (CHARSET == 'gbk') {
			$_POST ['data'] = Keke::utftogbk ( $_POST ['data'] );
		}
		
		$data = $_POST ['data'];
		$columns = array ('status', 'mem' );
		$values = array ('fail', $data );
		$where = "rid = '$rid'";
		DB::update ( 'witkey_recharge' )->set ( $columns )->value ( $values )->where ( $where )->execute ();
		 
	}
}
