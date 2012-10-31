<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ����--�������
 *
 * @copyright keke-tech
 * @author fu
 * @version v 22
 *          2012-10-9 15:18:30
 */
class Control_admin_finance_withdraw extends Control_admin {
	function action_index() {
		// ����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K, $_lang;
		
		// Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `wid`,`bank_username`,`bank_name`,`username`,`bank_account`,`type`,`cash`,`status` ';
		// Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array ('wid' => $_lang ['financial_id'], 'username' => $_lang ['username'], 'bank_username' => '�տ���' );
		// �ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval ( $_GET ['count'] );
		// tool������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���too_file So���ﲻ��дΪtool/file
		$base_uri = BASE_URL . "/index.php/admin/finance_withdraw";
		
		$del_uri = $base_uri . '/del';
		// Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'wid';
		// ����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract ( $this->get_url ( $base_uri ) );
		// ��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory ( 'witkey_withdraw' )->get_grid ( $fields, $where, $uri, $order, $page, $count, $_GET ['page_size'] );
		// �б�����
		$list_arr = $data_info ['data'];
		// ��ҳ����
		$pages = $data_info ['pages'];
		
		// ����
		$bank_arr = keke_global_class::get_bank ();
		
		// ����״̬ 0�����,1���ֳɹ�,2����ʧ��
		$status_arr = keke_global_class::withdraw_status ();
		
		require Keke_tpl::template ( 'control/admin/tpl/finance/withdraw' );
	}
	// �������ͨ��
	function action_pass() {
		if (($wid = $_GET ['wid']) == NULL) {
			return false;
		}
		$where = "wid='$wid'";
		// ����״̬
		DB::update ( 'witkey_withdraw' )->set ( array ('status' ) )->value ( array ('1' ) )->where ( $where )->execute ();
		// ������Ϣ
		$winfo = DB::select ()->from ( 'witey_withdraw' )->where ( $where )->get_one ()->execute ();
		// ����out �����¼,������cash_out;
		$columns = array ('fina_type', 'fina_action', 'uid', 'username', 'fina_cash', 'fina_time', 'obj_type', 'obj_id' );
		$values = array ('out', 'withdraw', $winfo ['uid'], $winfo ['username'], $winfo ['cash'], time (), 'withdraw', $wid );
		DB::insert ( 'witkey_finance' )->set ( $columns )->value ( $values )->execute ();
	}
	
	// ��ͨ�����
	function action_nopass() {
		// ��ͨ��ˣ��ı�״̬���Ӳ���ͨ��������
		if (($wid = $_GET ['wid']) == NULL) {
			return false;
		}
		if (CHARSET == 'gbk') {
			$_POST ['data'] = Keke::utftogbk ( $_POST ['data'] );
		}
		$data = $_POST ['data'];
		$columns = array ('status', 'mem' );
		$values = array ('2', $data );
		$where = "wid = '$wid'";
		DB::update ( 'witkey_withdraw' )->set ( $columns )->value ( $values )->where ( $where )->execute ();
		// ����ʧ�ܣ��˿�����ֵ���
		$winfo = DB::select ()->from ( 'witkey_withdraw' )->where ( $where )->get_one ()->execute ();
		Sys_finance::cash_out ( $winfo ['uid'], $winfo ['cash'], 'withdraw_fail' );
	}
}