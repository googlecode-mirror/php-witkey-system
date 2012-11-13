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
		$fields = ' `rid`,`type`,`bank`,`username`,`cash`,`pay_time`,`status`,`mem` ';
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
		$data_info = Model::factory ( 'witkey_recharge' )->get_grid ( $fields, $where, $uri, $order, $page, $count, $_GET ['page_size'] );
		// �б�����
		$list_arr = $data_info ['data'];
		// ��ҳ����
		$pages = $data_info ['pages'];
		// �û���
		$group_arr = Keke_admin::get_user_group ();
		
		// ��ֵ��������
		$charge_type_arr = keke_global_class::get_charge_type ();
		
		// ��ֵ����
		$bank_arr = keke_global_class::get_bank ();
		
		// ��ֵ����״̬
		$status_arr = Sys_order::get_recharge_status ();
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
		$bank_arr = keke_global_class::get_bank ();
		
		$order_info = DB::select ()->from ( 'witkey_recharge' )->where ( "rid=:rid" )
		->param ( ":rid", $rid )->get_one ()->execute ();
		//��ֵ����
		Sys_finance::init_mem ( 'recharge', array (':bank' => $bank_arr [$order_info ['bank']], ':cash' => $order_info ['cash'] ) );
		//��ֵ���
		Sys_finance::cash_in ( $order_info ['uid'], $order_info ['cash'],0, 'offline_recharge' );
		// �ı��ֵ��¼��״̬
		DB::update ( 'witkey_recharge' )->set ( array ('status' ) )->value ( array ('ok' ) )
		->where ( "rid=:rid" )->param ( ":rid", $rid )->execute ();
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
