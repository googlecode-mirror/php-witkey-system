<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��ֵ���
 * @copyright keke-tech
 * @author fu
 * @version v 22
 * 2012-10-9 15:18:30
 */
class Control_admin_finance_recharge extends Controller{

	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;

		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `order_id`,`username`,`order_type`,`pay_type`,`pay_money`,`pay_time`,`order_status` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('order_id'=>$_lang['id'],'username'=>$_lang['username'],'order_type'=>$_lang['order_type']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//finance������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���finance_recharge So���ﲻ��дΪfinance/recharge
		$base_uri = BASE_URL."/index.php/admin/finance_recharge";

		//��ӱ༭��uri,add���action �ǹ̶���
		//$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'order_id';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_order_charge')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//�û���
		$group_arr = keke_admin_class::get_user_group ();
		
		//��ֵ��������
		$charge_type_arr = keke_global_class::get_charge_type();
		
		//��ֵ����
		$bank_arr = keke_global_class::get_bank();
		//��ֵ����״̬
		$status_arr = keke_order_class::get_order_status();
		//����֧����ʽ
		//$offline_pay=Keke::get_table_data ( "*", "witkey_pay_api", " type='offline'", '', '', '', 'payment' );
		$offline_pay=DB::select()->from('witkey_pay_api')->where("type='offline'")->execute(); 
		$offline_pay= Keke::get_arr_by_key($offline_pay,'payment');
		//var_dump($list_arr);die;
		require Keke_tpl::template('control/admin/tpl/finance/recharge');

	}
	/**
	 * ������¼��ɾ��,֧�ֵ����ɾ��
	 */
	function action_del(){
		//ɾ������,�����file_id ����ģ���ϵ������������е�
		if($_GET['order_id']){
			$where = 'order_id = '.$_GET['order_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'order_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_order_charge')->setWhere($where)->del();

	}
	
	/**
	 * ��˳�ֵ����
	 */
	function action_update(){
		global $_lang;
		$array = array(
					'order_status'=>'ok'
				);
		$page = $_GET['page'];
		if($_GET['order_id']){
			$where = 'order_id = '.$_GET['order_id'];
			//��ȡ��ֵ��Ϣ
// 			$order_info = Model::factory("witkey_order_charge")->setData($array)->setWhere($where)->query();
			
			//DB::update('witkey_order_charge')->set($columns)->value($values)->where($where)->execute();
			$order_info = DB::select()->from('witkey_order_charge')->where($where)->execute();
			$order_info = $order_info[0];
			
			if ($order_info [order_status] == 'ok'){
				Keke::admin_show_msg($_lang['payment_has_been_success_no_need_repeat'], BASE_URL.'index.php/admin/finance_recharge',3,'','warning');
			}
			//�û���Ϣ
			//$user_info = keke_user_class::get_user_info($order_info ['uid']);
			//��ֵ״̬
			//Model::factory("witkey_order_charge")->setData($array)->setWhere($where)->update();
			//��ֵ
			keke_finance_class::cash_in($order_info['uid'], $order_info['pay_money'],0,'offline_charge','','offline_charge');
			//����վ���Ÿ��û�
			//keke_msg_class::send_private_message('��ֵ�ɹ�', '����ֵ��'.$order_info['pay_money'], $order_info['uid'], $order_info['username']);
			//��ֵ��־
			Keke::admin_system_log ( $_lang['confirm_payment_recharge'].$_GET['order_id']);
			//�ɹ���ת��ʾ
			Keke::show_msg('����ɹ�',BASE_URL.'/index.php/admin/finance_recharge','success');
		}
	}

}

// Keke::admin_check_role (76 );

// $recharge_obj = new Keke_witkey_order_charge_class(); //ʵ������ֵ�����
// $page_obj = $Keke->_page_obj; //ʵ������ҳ����
// $charge_type_arr=keke_glob_class::get_charge_type();/*��ֵ��������*/
// $status_arr = keke_order_class::get_order_status();
// $offline_pay=Keke::get_table_data ( "*", "witkey_pay_api", " type='offline'", '', '', '', 'payment' ); //����֧����ʽ
// //var_dump($offline_pay);
// //��ҳ
// $w [page_size] and $page_size = intval ( $w [page_size] ) or $page_size =10;
// intval ( $page ) or $page = '1';
// $url = "index.php?do=$do&view=$view&w[order_status]=$w[order_status]&w[order_id]=$w[order_id]&w[order_type]=$w[order_type]&w[username]=$w[username]&w[page_size]=$page_size&w[ord]=$w[ord]&page=$page";

// $bank_arr     = keke_glob_class::get_bank();
// if (isset ( $ac )) { //��������嵥����
// $order_info=Dbfactory::get_one(" select * from ".TABLEPRE."witkey_order_charge where order_id = ".intval($order_id));
// //�ʼ�
// $message_obj = new keke_msg_class ();
// $order_info or Keke::admin_show_msg ( $_lang['charge_num_not_exist'], $url,3,'','warning');
// 	switch ($ac) {
// 		case 'pass' : //��˳�ֵ����
// 				if ($order_info [order_status] == 'ok') {
 
// 					Keke::admin_show_msg ( $_lang['payment_has_been_success_no_need_repeat'], $url,3,'','warning');
 
// 				}
// 				$recharge_obj->setWhere ( 'order_id =' . $order_id );
// 				$recharge_obj->setOrder_status('ok' );//��ֵ���ͨ��
// 				$res = $recharge_obj->edit_keke_witkey_order_charge();
// 				$user_info = Keke::get_user_info ( $order_info [uid] );
// 				/** ֪ͨ�û�*/ 
// 				$v_arr = array ($_lang['charge_amount'] => $order_info['pay_money']);
// 				keke_shop_class::notify_user ( $user_info[uid], $user_info[username], "pay_success", $_lang['line_recharge_success'], $v_arr );
// 					/*���������¼*/
// 				keke_finance_class::cash_in($user_info['uid'], $order_info['pay_money'],0,'offline_charge','','offline_charge');
				
// 				Keke::admin_system_log ( $_lang['confirm_payment_recharge'].$order_id);
// 				Keke::admin_show_msg ( $_lang['message_about_recharge_success'], $url,3,'','success' );
// 		break;
// 	//ɾ����ֵ����
// 	case 'del' :
// 			$recharge_obj->setWhere ( ' order_id=' . $order_id );
// 			$res = $recharge_obj->del_keke_witkey_order_charge();
			 
// 			$user_info = Keke::get_user_info ( $order_info [uid] );
// 			$v = array ($_lang['recharge_single_num'] => $order_id,$_lang['recharge_cash'] => $order_info [pay_money] );
// 			$message_obj->send_message ( $user_info ['uid'], $user_info ['username'], 'recharge_fail', $_lang['recharge_fail'], $v, $user_info [email], $user_info ['mobile'] );
				
// 			Keke::admin_system_log ( $_lang['delete_apply_forwithdraw'] . $order_id );
// 			Keke::admin_show_msg ( $_lang['message_about_delete'], $url,3,'','success' );
// 		;
// 		break;	
// 	}
 
// }elseif (isset ( $ckb )) { //����ɾ��
// 	$ids = implode ( ',', $ckb );
// 	if (count ( $ids )) {
	
// 		$recharge_obj->setWhere ( " order_id in ($ids) and order_status = 'wait' " );
// 		$nodraw_arr = $recharge_obj->query_keke_witkey_order_charge();	//����˵ĳ�ֵ��¼
// 		$del_ids=array();
// 		switch ($sbt_action) {
// 			case $_lang['mulit_delete'] : //����ɾ��
// 				//����˵��˿���
// 				foreach ( $nodraw_arr as $k=>$v ) {
// 					$del_ids[$k]=$v[order_id];
					
// 					$message_obj = new keke_msg_class ();//�ʼ�
// 					$user_info=keke_user_class::get_user_info($v[uid]);//�û���Ϣ
// 					$v = array ($_lang['recharge_single_num'] =>$v['order_id'],$_lang['recharge_cash'] => $v [pay_money] );
// 					$message_obj->send_message ( $user_info ['uid'], $user_info ['username'], 'recharge_fail', $_lang['recharge_fail'], $v, $user_info [email], $user_info ['mobile'] );
// 				}
// 				//���ͨ����ֱ��ɾ��
// 				$del_ids=implode(",", $del_ids);
// 				if($del_ids){
// 					$recharge_obj->setWhere ( " order_id in ($del_ids)" );
// 					$res = $recharge_obj->del_keke_witkey_order_charge();
// 					Keke::admin_system_log ( $_lang['delete_recharge_order'].$del_ids );
// 				}
// 				break;
// 		}
		
// 		if ($res) {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url,3,'','success');
// 		} else {
// 			Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url,3,'','warning');
// 		}
	
// 	} else {
// 		Keke::admin_show_msg ( $_lang['please_select_an_item_to_operate'], 'index.php?do=' . $do . '&view=' . $view,3,'','warning' );
// 	}

// } else {
// 	$where = ' 1 = 1 '; //Ĭ�ϲ�ѯ����
// 	$w ['order_id'] and $where .= " and order_id = '$w[order_id]' ";
// 	$w ['order_type'] and $where .= " and order_type = '$w[order_type]'";
// 	$w ['order_status'] and $where .= " and order_status = '$w[order_status]' ";
// 	$w ['username'] and $where .= " and username like '%$w[username]%' ";

// 	is_array($w['ord']) and $where .= ' order by '.$w['ord'][0].' '.$w['ord'][1] or $where.=' order by order_id desc' ;
	
// 	//$w ['ord'] and $where .= " order by $w[ord]" or $where .= "order by pay_time desc ";
// 	//��ѯͳ��
// 	$recharge_obj->setWhere ( $where );
// 	$count = $recharge_obj->count_keke_witkey_order_charge();
// 	$page_obj->setAjax(1);
// 	$page_obj->setAjaxDom("ajax_dom");
// 	$pages = $page_obj->getPages ( $count, $page_size, $page, $url );

// 	//��ѯ�������
// 	$recharge_obj->setWhere ( $where . $pages [where] );
// 	$recharge_arr = $recharge_obj->query_keke_witkey_order_charge();
// }

// require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );