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
	/**
	 * ������¼��ɾ��,֧�ֵ����ɾ��
	 */
	function action_del() {
		// ɾ������,�����file_id ����ģ���ϵ������������е�
		if ($_GET ['withdraw_id']) {
			$where = 'withdraw_id = ' . $_GET ['withdraw_id'];
			echo Model::factory ( 'witkey_withdraw' )->setWhere ( $where )->del ();
		}
		if ($_GET ['ids']) {
			$ids = $_GET ['ids'];
			if (count ( $_GET ['ids'] )) {
				// ����˵������¼
				// $nodraw_arr = Model::factory("witkey_withdraw")->setWhere("
				// withdraw_id in ('$ids') and withdraw_status =1 ")->query();
				$nodraw_arr = db::select ( '*' )->from ( "witkey_withdraw" )->where ( "withdraw_id in ('$ids') and withdraw_status =1" )->execute ();
				$nodraw_arr = $nodraw_arr [0];
				// var_dump($nodraw_arr);die;
				switch ($sbt_action) {
					case $_lang ['mulit_nopass'] : // �����˿�
					                               // ����˵��˿���
						
						foreach ( $nodraw_arr as $v ) {
							$withdraw_id = $v ['withdraw_id'];
							$where = "withdraw_id = '$withdraw_id' ";
							$withdraw_info = db::select ( '*' )->from ( "witkey_withdraw" )->where ( $where )->execute ();
							$withdraw_info = $withdraw_info [0];
							$withdraw_cash = $withdraw_info ['withdraw_cash'];
							$uid = $withdraw_info ['uid'];
							$username = $withdraw_info ['username'];
							$pay_way = array_merge ( keke_global_class::get_bank (), keke_global_class::get_online_pay () );
							
							$data = array (':pay_way' => $pay_way [$withdraw_info ['pay_type']], ':pay_account' => $withdraw_info ['pay_account'], ':pay_name' => $withdraw_info ['pay_name'] );
							keke_finance_class::init_mem ( 'withdraw_fail', $data );
							keke_finance_class::cash_in ( $uid, $withdraw_cash, 0, 'withdraw_fail' );
						}
						// ���ͨ���������˿�
						Model::factory ( "witkey_withdraw" )->setData ( array ('withdraw_status' => '3' ) )->setWhere ( 'withdraw_id in (' . $_GET ['ids'] . ')' )->update ();
						Keke::admin_system_log ( $_lang ['delete_audit_withdraw'] . $ids );
						break;
					case $_lang ['mulit_review'] : // �������
					                               // �ֶ�����
						$array = array ('withdraw_status' => '2', 'process_uid' => $admin_info ['uid'], 'process_username' => $admin_info ['username'], 'process_uid' => $admin_info ['uid'], 'process_time' => time () );
						// ���²���״̬
						Model::factory ( "witkey_withdraw" )->setData ( $array )->setWhere ( ' withdraw_id in (' . $ids . ') ' )->update ();
						$withdraw_arr = db::select ( '*' )->from ( "witkey_withdraw" )->where ( "withdraw_id in ('$ids') and withdraw_status =1" )->execute ();
						$withdraw_arr = $withdraw_arr [0];
						foreach ( $withdraw_arr as $withdraw_info ) {
							$withdraw_id = $withdraw_info ['withdraw_id'];
							/* ���������� */
							if (in_array ( $withdraw_id, $ids )) {
								$fee = $withdraw_info ['withdraw_cash'] - keke_finance_class::get_to_cash ( $withdraw_info ['withdraw_cash'] );
								Dbfactory::execute ( sprintf ( ' update %switkey_withdraw set fee=%.2f where withdraw_id=%d', TABLEPRE, $fee, $withdraw_id ) );
							}
							if ($withdraw_info ['withdraw_status'] != 1) {
								continue;
							}
						}
						
						Keke::admin_system_log ( $_lang ['audit_withdraw_apply'] . $ids );
						break;
				}
				
				if ($res) {
					Keke::admin_show_msg ( $_lang ['mulit_operate_success'], BASE_URL . "/index.php/admin/finance_withdraw/index", 3, '', 'success' );
				} else {
					Keke::admin_show_msg ( $_lang ['mulit_operate_fail'], BASE_URL . "/index.php/admin/finance_withdraw/index", 3, '', 'warning' );
				}
			} else {
				Keke::admin_show_msg ( $_lang ['choose_operate_item'], BASE_URL . "/index.php/admin/finance_withdraw/index", 3, '', 'warning' );
			}
		}
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