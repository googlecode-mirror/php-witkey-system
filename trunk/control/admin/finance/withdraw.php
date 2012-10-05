<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ����--�������
 * @copyright keke-tech
 * @author Chen
 * @version v 20
 * 2011-09-03 15:18:30
 */
class Control_admin_finance_withdraw extends Controller{

	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;

		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `withdraw_id`,`pay_username`,`username`,`pay_account`,`pay_type`,`withdraw_cash`,`withdraw_status` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('withdraw_id'=>$_lang['financial_id'],'username'=>$_lang['username']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//tool������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���too_file So���ﲻ��дΪtool/file
		$base_uri = BASE_URL."/index.php/admin/finance_withdraw";

		//��ӱ༭��uri,add���action �ǹ̶���
		//$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'withdraw_id';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_withdraw')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//�û���
		$group_arr = keke_admin_class::get_user_group ();

		//��ֵ��������
		$charge_type_arr = keke_global_class::get_charge_type();

		//��������
		$bank_arr = keke_global_class::get_bank();
		
		//����״̬
		$status_arr = keke_global_class::withdraw_status();
		
		$paytype_list = Keke::get_table_data ( "payment,config", "witkey_pay_api", " type!='trust'", "", "", "", "payment" );
		//var_dump($bank_arr);die;
		require Keke_tpl::template('control/admin/tpl/finance/withdraw');

	}
	/**
	 * ������¼��ɾ��,֧�ֵ����ɾ��
	 */
	function action_del(){
		//ɾ������,�����file_id ����ģ���ϵ������������е�
		if($_GET['withdraw_id']){
			$where = 'withdraw_id = '.$_GET['withdraw_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'withdraw_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_withdraw')->setWhere($where)->del();

	}

	/**
	 * ��˳�ֵ����
	 */
	function action_update(){
		if($_GET['withdraw_id']){
			$where = 'withdraw_id = '.$_GET['withdraw_id'];
			//���µ���,�����file_id ����ģ���ϵ������������е�
		}
	}

}



// Keke::admin_check_role ( 5 );
// $withdraw_obj = new Keke_witkey_withdraw_class (); //ʵ�������ֱ����
// $user_space_obj = new Keke_witkey_space_class (); //ʵ�����û���Ϣ�����
// $page_obj = $Keke->_page_obj; //ʵ������ҳ����
// $paytype_list = Keke::get_table_data ( "payment,config", "witkey_pay_api", " type!='trust'", "", "", "", "payment" );
// $status_arr  = keke_glob_class::withdraw_status();
// $bank_arr = keke_glob_class::get_bank();
// //��ҳ
// $w ['page_size'] and $page_size = intval ( $w ['page_size'] ) or $page_size = 10;
// $page and $page = intval ( $page ) or $page = '1';
// $url = "index.php?do=$do&view=$view&w[pay_type]=".$w['pay_type']."&w[page_size]=$page_size&w[ord]=".$w['ord']."&page=$page";

// $withdraw_id and $withdraw_info = Dbfactory::get_one ( "select * from " . TABLEPRE . "witkey_withdraw where withdraw_id = '$withdraw_id'" );
// if (isset ( $ac )) { //��������嵥����
// 	switch ($ac) {
// 		case 'pass' : //�����������
// 			if ($withdraw_info['withdraw_status']) {
// 				if ($is_submit) {
					
// 					$user_space_info = Keke::get_user_info ( $withdraw_info ['uid'] );
					
// 					if ($withdraw_info ['withdraw_status'] != 1) {
// 						Keke::admin_show_msg ( $_lang['no_need_to_repeat'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 					}
					
// 					$withdraw_obj->setWhere ( 'withdraw_id=' . $withdraw_id );
// 					//�������ͨ��
// 					$withdraw_obj->setWithdraw_status ( 2 );
// 					$withdraw_obj->setProcess_uid ( $admin_info ['uid'] );
// 					$withdraw_obj->setProcess_username ( $admin_info ['username'] );
// 					$withdraw_obj->setProcess_time ( time () );
// 					$fee = $withdraw_info['withdraw_cash']-keke_finance_class::get_to_cash($withdraw_info['withdraw_cash']);
// 					$withdraw_obj->setFee($fee);
// 					$res = $withdraw_obj->edit_keke_witkey_withdraw ();
					
// 					$feed_arr = array ("feed_username" => array ("content" => $withdraw_info ['username'],
// 					 "url" => "index.php?do=space&member_id=".$user_space_info['uid']),
// 					 "action" => array ("content" => $_lang['withdraw'], "url" => "" )
// 					, "event" => array ("content" => $_lang['withdraw_le'] . $withdraw_info ['withdraw_cash'] . $_lang['yuan'], "url" => "" )
// 					 );
// 					Keke::save_feed ( $feed_arr, $user_space_info ['uid'], $user_space_info ['username'], 'withdraw' );
					
// 					//Keke::feed_add ( '<a href="index.php?do=space&member_id=' . $user_space_info ['uid'] . '" target="_blank">' . $withdraw_info ['username'] . '</a>�ɹ�������' . $withdraw_info ['withdraw_cash'] . $_lang['yuan'], $user_space_info ['uid'], $user_space_info ['username'], 'withdraw' );
// 					//�ʼ�
// 					/* $message_obj = new keke_msg_class ();
// 					$t_userinfo = Dbfactory::get_one ( " select mobile,email from " . TABLEPRE . "witkey_space where uid ='".$withdraw_info['uid']."'" );
// 					$v = array ($_lang['withdraw_cash'] => $withdraw_info ['withdraw_cash'],$_lang['account_msg']=>$withdraw_info['pay_account'] );
// 					$message_obj->send_message ( $withdraw_info ['uid'], $withdraw_info ['username'], 'draw_success', $_lang['withdraw_success'], $v, $t_userinfo ['email'], $t_userinfo ['mobile'] );
// 					 */
// 					$v_arr = array('��վ����'=>$_K['sitename'],'���ַ�ʽ'=>$pay_way[$withdraw_info['pay_type']],'�ʻ�'=>$withdraw_info['pay_account'],'���ֽ��'=>$withdraw_info['withdraw_cash']);
// 					keke_msg_class::notify_user( $withdraw_info ['uid'] , $withdraw_info ['username'] ,'draw_success',$_lang['withdraw_success'],$v_arr);
					 
					
// 					//$space_info = Keke::get_user_info ( intval ( $withdraw_info ['uid'] ) );
					
// 					//	Keke::update_score_value ( $withdraw_info ['uid'], 'withdraw', 2 );
// 					Keke::admin_system_log ( $_lang['audit_withdraw_apply'] . $withdraw_id );
// 					Keke::admin_show_msg ( $_lang['audit_withdraw_pass'], 'index.php?do=' . $do . '&view=' . $view,3,'','success');
// 				}else{
// 					$bank_arr=keke_glob_class::get_bank();
// 					$k_arr   = array_keys($bank_arr);
// 				}
// 				require $template_obj->template ( 'control/admin/tpl/admin_finance_withdraw_info' );
// 				die ();
// 			} else {
// 				Keke::admin_show_msg ( $_lang['audit_withdraw_not_exist'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 			}
// 			;
// 			break;
// 		//�ܾ�ͨ����,ɾ����������
// 		case 'nopass' :
// 			if ($withdraw_info) {
// 				$withdraw_obj->setWhere ( 'withdraw_id=' . $withdraw_id );
// 				$withdraw_obj->setWithdraw_status (3);
// 				$withdraw_obj->setProcess_uid ( $admin_info ['uid'] );
// 				$withdraw_obj->setProcess_username ( $admin_info ['username'] );
// 				$withdraw_obj->setProcess_time ( time () );
// 				$res = $withdraw_obj->edit_keke_witkey_withdraw();
// 				//������
// 				$withdraw_cash = $withdraw_info ['withdraw_cash'];
// 				$uid = $withdraw_info  ['uid'];
// 				$username = $withdraw_info  ['username'];
				
// // 				$user_info = keke_user_class::get_user_info($uid);

// 				//�������ڵ��������ƻ��߹������ƣ��罨�����С�֧������
// 				$pay_way = array_merge(keke_glob_class::get_bank(),keke_glob_class::get_online_pay());
// 				$data = array(':pay_way'=>$pay_way[$withdraw_info['pay_type']],':pay_account'=>$withdraw_info['pay_account'],':pay_name'=>$withdraw_info['pay_name']);
// 				keke_finance_class::init_mem('withdraw_fail', $data);
// 				keke_finance_class::cash_in ( $uid, $withdraw_cash, 0, 'withdraw_fail' );

				
// 				$v_arr = array('��վ����'=>$_K['sitename'],'���ַ�ʽ'=>$pay_way[$withdraw_info['pay_type']],'�ʻ�'=>$withdraw_info['pay_account'],'���ֽ��'=>$withdraw_info['withdraw_cash']);
// 	         	keke_msg_class::notify_user($uid,$username,'withdraw_fail',$_lang['fail_and_check_you_account'],$v_arr);
	           
// 				Keke::admin_system_log ( $_lang['delete_audit_withdraw'] . $withdraw_id );
// 				Keke::admin_show_msg ( $_lang['delete_audit_withdraw_success'], 'index.php?do=' . $do . '&view=' . $view,3,'','success' );
// 			} else {
// 				Keke::admin_show_msg ( $_lang['fail_item_not_exist'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 			}
// 			;
// 			break;
	
// 	}
// } elseif (isset ( $ckb )) { //����ɾ��
// 	$ids = implode ( ',', $ckb );
// 	if (count ( $ids )) {
// 		//����˵������¼
// 		$withdraw_obj->setWhere ( " withdraw_id in ('$ids') and withdraw_status =1 " );
// 		$nodraw_arr = $withdraw_obj->query_keke_witkey_withdraw ();		
// 		$withdraw_obj->setWhere ( ' withdraw_id in (' . $ids . ') ' );				
// 		switch ($sbt_action) {
// 			case $_lang['mulit_nopass']: //�����˿�
// 				//����˵��˿���
// 				foreach ( $nodraw_arr as $v ) {
// 					$withdraw_id = $v ['withdraw_id'];
// 					$where = "withdraw_id = '$withdraw_id' ";
// 					$withdraw_info = Dbfactory::get_one ( "select * from " . TABLEPRE . "witkey_withdraw where $where" );
// 					$withdraw_cash = $withdraw_info ['withdraw_cash'];
// 					$uid = $withdraw_info ['uid'];
// 					$username = $withdraw_info ['username'];
// 					$pay_way = array_merge(keke_glob_class::get_bank(),keke_glob_class::get_online_pay());
					
// 					$data = array(':pay_way'=>$pay_way[$withdraw_info['pay_type']],':pay_account'=>$withdraw_info['pay_account'],':pay_name'=>$withdraw_info['pay_name']);
// 					keke_finance_class::init_mem('withdraw_fail', $data);
// 					keke_finance_class::cash_in ( $uid, $withdraw_cash, 0, 'withdraw_fail' );

					
// 					$v_arr = array('��վ����'=>$_K['sitename'],'���ַ�ʽ'=>$pay_way[$withdraw_info['pay_type']],'�ʻ�'=>$withdraw_info['pay_account'],'���ֽ��'=>$withdraw_cash);
// 					keke_msg_class::notify_user($uid,$username,'withdraw_fail',$_lang['fail_and_check_you_account'],$v_arr);
	                
// 				}
// 				//���ͨ���������˿�
// 				$withdraw_obj->setWithdraw_status (3);
// 				$res = $withdraw_obj->edit_keke_witkey_withdraw();
// 				Keke::admin_system_log ( $_lang['delete_audit_withdraw'] . $ids );
// 				break;
// 			case $_lang['mulit_review']: //�������
// 				$withdraw_arr = $withdraw_obj->query_keke_witkey_withdraw ();
// 				$withdraw_obj->setWhere ( ' withdraw_id in (' . $ids . ') ' );
// 				$withdraw_obj->setWithdraw_status ( 2);
// 				$withdraw_obj->setProcess_uid ( $admin_info ['uid'] );
// 				$withdraw_obj->setProcess_username ( $admin_info ['username'] );
// 				$withdraw_obj->setProcess_time ( time () );
// 				$res = $withdraw_obj->edit_keke_witkey_withdraw ();
				
// 				foreach ( $withdraw_arr as $withdraw_info ) {
// 					$withdraw_id = $withdraw_info ['withdraw_id'];
// 					/*����������*/
// 					if(in_array($withdraw_id,$ids)){
// 						$fee = $withdraw_info['withdraw_cash'] - keke_finance_class::get_to_cash($withdraw_info['withdraw_cash']);
// 						Dbfactory::execute(sprintf(' update %switkey_withdraw set fee=%.2f where withdraw_id=%d',TABLEPRE,$fee,$withdraw_id));
// 					}
// 					if ($withdraw_info ['withdraw_status'] != 1) {
// 						continue;
// 					}
					
// 					$v_arr = array('��վ����'=>$_K['sitename'],'���ַ�ʽ'=>$pay_way[$withdraw_info['pay_type']],'�ʻ�'=>$withdraw_info['pay_account'],'���ֽ��'=>$withdraw_cash);
// 					keke_msg_class::notify_user($withdraw_info ['uid'],$withdraw_info ['username'],'draw_success',$_lang['withdraw_success'],$v_arr);
					
// 					$feed_arr = array ("feed_username" => array ("content" => $withdraw_info ['username'], "url" => "index.php?do=space&member_id=".$space_info['uid']), "action" => array ("content" => $_lang['withdraw'], "url" => "" ), "event" => array ("content" =>$_lang['withdraw_le'].$withdraw_info['withdraw_cash']. $_lang['yuan'],"url" => "" ) );
// 					Keke::save_feed ( $feed_arr, $user_space_info ['uid'], $user_space_info ['username'], 'withdraw' );
				
// 				}
				
// 				Keke::admin_system_log ( $_lang['audit_withdraw_apply'] . $ids );
// 				break;
		
// 		}
		
// 		if ($res) {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_success'], 'index.php?do=' . $do . '&view=' . $view,3,'','success');
// 		} else {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_fail'], 'index.php?do=' . $do . '&view=' . $view ,3,'','warning');
// 		}
	
// 	} else {
// 		Keke::admin_show_msg ( $_lang['choose_operate_item'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 	}

// } elseif ($type == 'batch' && $pay_type == 'alipayjs') {
// 	$payment_config = Keke::get_payment_config('alipayjs');
// 	require S_ROOT . "/payment/alipayjs/order.php";
// 	$detail_data = Dbfactory::query ( sprintf ( " select withdraw_id,pay_account,pay_username,withdraw_cash fee,uid from %switkey_withdraw where withdraw_id in (%s) and withdraw_status='1'", TABLEPRE, $ids ) );
// 	echo get_batch_url ($payment_config, $detail_data,'url');
// 	die();
// } else {
// 	$where = ' 1 = 1 '; //Ĭ�ϲ�ѯ����
// 	$w ['withdraw_id'] and $where .= " and withdraw_id = '".$w['withdraw_id']."' ";
// 	$w ['username'] and $where .= " and username like '%".$w['username']."%' ";
// 	$w ['pay_type'] and $where .= " and pay_type = '".$w['pay_type']."' ";

// 	is_array($w['ord']) and $where .= ' order by '.$w['ord']['0'].' '.$w['ord']['1'] or $where .= "order by withdraw_id desc";
	
// 	//$w ['ord'] and $where .= " order by $w['ord']" or $where .= "order by withdraw_id desc ";
// 	//��ѯͳ��
// 	$withdraw_obj->setWhere ( $where );
// 	$count = $withdraw_obj->count_keke_witkey_withdraw ();
// 	$page_obj->setAjax(1);
// 	$page_obj->setAjaxDom("ajax_dom");
// 	$pages = $page_obj->getPages ( $count, $page_size, $page, $url );
// 	//��ѯ�������
// 	$withdraw_obj->setWhere ( $where . $pages ['where'] );
// 	$withdraw_arr = $withdraw_obj->query_keke_witkey_withdraw ();
// }

// require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );