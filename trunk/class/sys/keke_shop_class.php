<?php
/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-28����16:59:00
 */
Keke_lang::load_lang_class('keke_shop_class');
class keke_shop_class {
	
	/**
	 * 
	 * ��ȡ��Ʒ��Ϣ
	 * @param int $sid ������
	 * @return array $service_arr
	 */
	public static function get_service_info($sid) {
		return dbfactory::get_one ( sprintf ( " select * from %switkey_service where service_id=%d", TABLEPRE, $sid ) );
	}
	
	/**
	 * ��Ϣ֪ͨ
	 */
	public static function notify_user($uid, $username, $action, $title, $v_arr = array()) {
		$msg_obj = new keke_msg_class ();
		$contact = self::get_contact ( $uid );
		$msg_obj->send_message ( $uid, $username, $action, $title, $v_arr, $contact ['email'], $contact ['mobile'] );
	}
	/**
	 * ��ȡ�û���ϵ��ʽ email,mobile
	 */
	public static function get_contact($uid) {
		return dbfactory::get_one ( sprintf ( " select mobile,email from %switkey_space where uid = '%d'", TABLEPRE, $uid ) );
	}
	/**
	 * ���ص�ǰ�û����ڴ˷�����Ʒ�������¶���״̬
	 * ��δ��ɶ�������߶���״̬ȷ����ת����
	 * ��δ��ɶ�������붩��ȷ��ҳ��
	 * @param int $sid ������
	 * @param int $s_uid ����UID
	 * @param int $model_id ģ��ID
	 */
	public static function access_check($sid, $s_uid, $model_id) {
		global $uid, $kekezu;
		global $_lang;
		$uid == $s_uid and Keke::keke_show_msg ( "index.php?do=shop", $_lang['seller_not_to_order_page'], 'error' );
		$order_info = self::check_has_buy ( $sid, $uid );
		$order_status = $order_info ['order_status'];
		$order_id = intval ( $order_info ['order_id'] );
		$model_code = Keke::$_model_list [$model_id] ['model_code'];
		if (! $order_status) {
			return true;
		} else {
			if ($order_status == 'close') {
				return true;
			} elseif ($order_status == 'confirm') {
				if ($model_code == 'goods') {
					Keke::keke_show_msg ( "index.php?do=user&view=finance&op=order&obj_type=service&role=2&order_id=" . $order_id, $_lang['you_has_buy_work'], 'error' );
				} else {
					return true;
				}
			} else {
				Keke::keke_show_msg ( "index.php?do=user&view=finance&op=order&obj_type=service&role=2&order_id=" . $order_id, $_lang['your_order_not_process_complete'], 'error' );
			}
		}
	}
	
	/**
	 * ��Ʒ�����������¼��������  
	 * @param array $service_info ��Ʒ��Ϣ
	 * @param string $order_status ����״̬
	 * @param string $type Ĭ�Ϸ���
	 */
	public static function create_service_order($service_info) {
		global $uid, $username, $_K;
		global $_lang;
		$uid == $service_info ['uid'] and Keke::keke_show_msg ( "index.php?do=shop", $_lang['seller_can_not_order_self'], 'error' );
		
		$oder_obj = new Keke_witkey_order_class (); //������¼�����
		$order_detail = new Keke_witkey_order_detail_class (); //������ϸ���¸�
		$service_cash = $service_info ['price']; //�����ܽ�
		switch ($service_info ['model_id']) {
			case "6" :
				$type = $_lang['work'];
				break;
			case "7" :
				$type = $_lang['service'];
				break;
		}
		/**��������**/
		$order_name = $service_info ['title']; //��������
		$order_body = $_lang['buy_goods']."<a href=\"index.php?do=service&sid=$service_info[service_id]\">" . $order_name . "</a>"; //������ע
		/** �������*/
		$fina_id = keke_finance_class::cash_out ( $uid, $service_cash, 'buy_service', '', 'service', $service_info ['service_id'] );
		$fina_id and $order_status = 'ok' or $order_status = 'wait'; //�Ƿ�֧�����
		/** ��������**/
		$order_id = keke_order_class::create_order ( $service_info ['model_id'], $service_info ['uid'], $service_info ['username'], $order_name, $service_cash, $order_body, $order_status );
		if ($order_id) {
			/** ���²����¼�Ķ�����*/
			$fina_id and keke_order_class::update_fina_order ( $fina_id, $order_id );
			keke_order_class::create_order_detail ( $order_id, $order_name, 'service', intval ( $service_info [service_id] ), $service_cash );
			
			/**֪ͨ*/
			$msg_obj = new keke_msg_class (); //��Ϣ��
			$service_url = "<a href=\"" . $_K [siteurl] . "/index.php?do=service&sid=" . $service_info [service_id] . "\">" . $order_name . "</a>";
			$order_url = "<a href=\"" . $_K [siteurl] . "/index.php?do=user&view=finance&op=order&obj_type=service&role=1&order_id=" . $order_id . "#userCenter\">#" . $order_id . "</a>";
			$s_notice = array ($_lang['user_action'] => $username . $_lang['order_buy'], $_lang['service_name'] => $service_url, $_lang['service_type'] => $type, $_lang['order_link'] => $order_url );
			$contact = dbfactory::get_one ( sprintf ( " select mobile,email from %switkey_space where uid='%d'", TABLEPRE, $service_info [uid] ) );
			
			$msg_obj->send_message ( $service_info ['uid'], $service_info ['username'], "service_order", $_lang['you_has_new'] . $type . $_lang['order'], $s_notice, $contact ['email'], $contact ['mobile'] ); ////֪ͨ����
			$feed_arr = array ("feed_username" => array ("content" => $username, "url" =>"index.php?do=space&member_id=".$uid), "action" => array ("content" => $_lang['buy'], "url" => '' ), "event" => array ("content" => $order_name, "url" =>"index.php?do=service&sid=$service_info[service_id]" ) );
			Keke::save_feed ( $feed_arr, $uid, $username, 'service', $service_info ['service_id'], $service_url );
			if ($fina_id) {
				Keke::keke_show_msg ( 'index.php?do=user&view=finance&op=order&obj_type=service&role=2&order_id=' . $order_id, $_lang['order_produce_success'] );
			} else {
				header ( "location:index.php?do=pay&order_id=$order_id" );
				die ();
			}
		} else {
			Keke::keke_show_msg ( 'index.php?do=shop_order&sid=' . $service_info [service_id], $_lang['order_produce_fail'], "error" );
		}
	}
	/**
	 * ��ȡָ����Ʒ�Ĺ����¼
	 */
	public static function get_sale_info($sid, $w = array(), $p = array(), $order = null,$ext_condit) {
		global $kekezu;
		$where = " select a.order_status,a.order_uid,a.order_username,a.order_amount,a.order_time from " . TABLEPRE . "witkey_order a left join " . TABLEPRE . "witkey_order_detail b on a.order_id=b.order_id where
		b.obj_id='$sid' and b.obj_type = 'service' ";
		$ext_condit and $where.=" and ".$ext_condit;
		$arr = keke_table_class::format_condit_data ( $where, $order, $w, $p );
		$sale_info = dbfactory::query ( $arr ['where'] );
		$sale_arr ['sale_info'] = $sale_info;
		$sale_arr ['pages'] = $arr ['pages'];
		return $sale_arr;
	
	}
	/**
	 * ��ȡ��Ʒ������
	 * @param int $sid
	 * @param array $w
	 * @param array $p
	 * @param string $order
	 * @return Ambigous <array(page,where), string>
	 */
	function get_service_comment($sid, $w = array(), $p = array(), $order = null) {
		global $kekezu;
		$comm_obj = new Keke_witkey_comment_class ();
		$where = " select * from " . TABLEPRE . "witkey_comment where obj_id = '$sid' and obj_type = 'service' ";
		$arr = keke_table_class::format_condit_data ( $where, $order, $w, $p );
		$comm_info = dbfactory::query ( $arr ['where'] );
		$comm_arr ['comm_info'] = $comm_info;
		$comm_arr ['pages'] = $arr ['pages'];
		return $comm_arr;
	}
	/**
	 * ��Ʒ(�ٱ���Ͷ��)
	 * @param $obj_id ������
	 * @param $report_type �ٱ�����
	 * @param $to_uid ���ٱ���
	 * @param $to_username ���ٱ�������
	 * @param $file_name �ϴ��ļ�·��
	 * @return json 
	 */
	public static function set_report($obj_id, $to_uid, $to_username, $report_type, $file_name, $desc) {
		global $uid;
		global $_lang;
		$service_info = self::get_service_info ( $obj_id );
		$transname = keke_report_class::get_transrights_name ( $report_type ); //�ٱ�Ͷ������
		$service_info ['uid'] == $uid and Keke::keke_show_msg ( '', $_lang['can_not_to_self'] . $transname, 'error', 'json' );
		$user_type = '2'; //ֻ�ܹ�����������
		$res = keke_report_class::add_report ( 'product', $obj_id, $to_uid, $to_username, $desc, $report_type, $service_info ['service_status'], $obj_id, $user_type, $file_name );
	}
	/**
	 * ͳ�ƻ�����״̬����
	 * @param string $model_code ģ��code
	 * @param int $sid ��Ʒ���
	 * @return array();
	 */
	public static function get_mark_count($model_code, $sid) {
		return  Keke::get_table_data ( " count(mark_id) count,mark_status", "witkey_mark", "model_code='" . $model_code . "' and origin_id='$sid'", "", "mark_status", "", "mark_status", 3600 );
	}
	
	/**
	 * ��ȡ���Թ����������͵�������Ϣ
	 * *
	 */
   public static function get_mark_count_ext($model_code, $sid){
		return Keke::get_table_data ( " count(mark_id) count,mark_type", "witkey_mark", "model_code='" . $model_code . "' and origin_id='$sid'", "", "mark_type", "", "mark_type", 3600 );
	}
	/**
	 * ͬ������3��
	 */
	public static function get_hot_service($model_id, $sid, $indus_pid) {
		return Keke::get_table_data ( " sale_num,service_id,price,title,pic ", "witkey_service", " model_id = '$model_id' and service_id !='$sid' and indus_pid = '$indus_pid' and service_status='2' and sale_num>0", "sale_num desc", "", "3", "", 3600 );
	}
	/**
	 * ͬ����Ʒ6��
	 */
	public static function get_related_service($model_id, $sid, $indus_id) {
		return Keke::get_table_data ( "pic,service_id,title", "witkey_service", " model_id = '$model_id' and service_id !='$sid' and indus_id = '$indus_id' and service_status='2'", "", "", "6", "", 3600 );
	}
	/**
	 * ����������Ʒ5��
	 */
	public static function get_more_service($uid, $sid) {
		return Keke::get_table_data ( "service_id,title,pic", "witkey_service", " uid='$uid' and service_status='2' and service_id!='$sid'", "sale_num desc ", "", "5", "", 3600 );
	}
	/**
	 * ��������б�14��
	 */
	public static function get_task_info($indus_id) {
		return Keke::get_table_data ( "task_id,task_title,task_cash", "witkey_task", " indus_id = '$indus_id' and task_status='2'", "", "", "14", "", 3600 );
	}
	/**
	 * �����������
	 */
	public static function plus_view_num($sid, $s_uid) {
		global $uid;
		if (! $_SESSION ['service_view_' . $sid . '_' . $uid] && $uid != $s_uid) {
			dbfactory::execute ( sprintf ( " update %switkey_service set views=views+1 where service_id='%d'", TABLEPRE, $sid ) );
			$_SESSION ['service_view_' . $sid . '_' . $uid] = '1';
		}
	}
	/**
	 * ���³��۴����ͳ����ܽ��
	 */
	public static function plus_sale_num($sid, $sale_cash) {
		return dbfactory::execute ( sprintf ( " update %switkey_service set sale_num=sale_num+1,total_sale=total_sale+'%f.2'", TABLEPRE, $sale_cash ) );
	}
	/**
	 * �������������� ÿ��+2
	 */
	public static function plus_mark_num($service_id) {
		return dbfactory::execute ( sprintf ( "update %switkey_service set mark_num=mark_num+2 where service_id ='%d'", TABLEPRE, $service_id ) );
	}
	/**
	 * ����Ƿ������Ʒ
	 * ��Ʒ�ǲ����ظ������
	 */
	public static function check_has_buy($sid, $uid) {
		return dbfactory::get_one ( sprintf ( " select a.order_status,a.order_id from %switkey_order a left join %switkey_order_detail b
					on a.order_id = b.order_id where a.order_uid ='%d' and b.obj_id='%d' and obj_type='service'", TABLEPRE, TABLEPRE, $uid, $sid ) );
	}
}