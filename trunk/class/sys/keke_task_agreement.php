<?php
/**
 *
 * @author Chen
 * ���񽻸����ƻ���
 *
 * ��ȡ����Э��
 * ��ȡ������Ϣ
 * ��ȡ������Ϣ
 *
 */
Keke_lang::load_lang_class('keke_task_agreement');
abstract class keke_task_agreement {
	public $_agree_id; //Э����
	public $_agree_status; //Э��״̬
	public $_agree_info; //Э����Ϣ
	public $_agree_url; //��������
	

	public $_task_id; //������
	public $_model_code; //ģ��code
	public $_trust_info; //������Ϣ
	

	public $_buyer_uid; //���(����)���
	public $_buyer_username; //���(����)����
	public $_buyer_status; //���(����)״̬
	public $_buyer_contact; //���(����)��ϵ��Ϣ
	

	public $_seller_uid; //����(����)���
	public $_seller_username; //����(����)����
	public $_seller_status; //����(����)״̬
	public $_seller_contact; //����(����)��ϵ��Ϣ
	

	public $_user_role; //�û���ɫ
	

	protected $_inited = false;
	
	public function __construct($agree_id) {
		$this->_agree_id = $agree_id;
		$this->get_agreement_info ();
		$this->init ();
	}
	
	public function init() {
		if (! $this->_inited) {
			$this->buyer_contact_init ();
			$this->seller_contact_init ();
		}
		$this->_inited = true;
	}
	/**
	 * ����Э�����
	 * @param string $agree_title  Э�����
	 * @param int $mode_id ģ�ͱ��
	 * @param int $task_id ����id
	 * @param int $work_id �б������
	 * @param int $buyer_uid ���uid
	 * @param int $seller_uid ����uid��
	 * @return Ambigous <number, boolean>
	 */
	public static function create_agreement($agree_title, $mode_id, $task_id, $work_id, $buyer_uid, $seller_uid) {
		$agree_obj = new Keke_witkey_agreement_class ();
		
		$agree_obj->_agree_id = null;
		$agree_obj->setAgree_title ( $agree_title );
		$agree_obj->setTask_id ( $task_id );
		$agree_obj->setModel_id ( $mode_id );
		$agree_obj->setWork_id ( $work_id );
		$agree_obj->setBuyer_uid ( $buyer_uid );
		$agree_obj->setBuyer_status ( 1 );
		$agree_obj->setSeller_uid ( $seller_uid );
		$agree_obj->setSeller_status ( '1' );
		$agree_obj->setAgree_status ( '1' );
		$agree_obj->setOn_time ( time () );
		return $agree_obj->create_keke_witkey_agreement ();
	}
	/**
	 * ����Э����Ϣ��ȡ
	 */
	public function get_agreement_info() {
		global $_K, $uid, $kekezu;
		$agree_info = dbfactory::get_one ( sprintf ( " select * from %switkey_agreement where agree_id = '%d'", TABLEPRE, $this->_agree_id ) );
		$this->_agree_info = $agree_info;
		$uid == $agree_info ['buyer_uid'] and $this->_user_role = '2' or $this->_user_role = '1';
		$this->_agree_status = $agree_info ['agree_status'];
		$this->_task_id = $agree_info ['task_id'];
		$this->_trust_info = dbfactory::get_one ( sprintf ( " select is_trust,trust_type,is_auto_bid,task_status from %switkey_task where task_id='%d'", TABLEPRE, $this->_task_id ) ); //�Ƿ񵣱�
		
		$this->_model_code = Keke::$_model_list [$agree_info ['model_id']] ['model_code']; //ģ��code
		$this->_buyer_uid = $agree_info ['buyer_uid'];
		$this->_buyer_status = $agree_info ['buyer_status'];
		$this->_seller_uid = $agree_info ['seller_uid'];
		$this->_seller_status = $agree_info ['seller_status'];
		$this->_agree_url = "<a href=\"" . $_K ['siteurl'] . "/index.php?do=agreement" . "&agree_id=" . $this->_agree_id . "\">" . $this->_agree_info ['agree_title'] . "</a>";
	
	}
	/**
	 * ��ȡ��������ϵ��Ϣ
	 */
	public function buyer_contact_init() {
		$info = dbfactory::get_one ( sprintf ( " select a.contact,a.username,b.truename,b.phone from %switkey_task a left join %switkey_space b on a.uid=b.uid where a.task_id='%d'", TABLEPRE, TABLEPRE, $this->_task_id ) );
		$this->_buyer_username = $info ['username'];
		$contact = unserialize ( $info ['contact'] );
		$this->_buyer_contact = array_merge ( $info, $contact );
	}
	/**
	 * ��ȡ���������ϵ��Ϣ
	 */
	public function seller_contact_init() {
		$info = dbfactory::get_one ( sprintf ( " select truename,username,qq,mobile,email,msn,phone from %switkey_space where uid='%d'", TABLEPRE, $this->_seller_uid ) );
		$this->_seller_username = $info ['username'];
		$this->_seller_contact = $info;
	}
	/**
	 * ����Э���һ�׶�
	 * ͬ��Э�顣����
	 * @param string $user_type �û���ɫ
	 * @param string $url    ������ʾ����  ����μ� Keke::keke_show_msg
	 * @param string $output ��Ϣ�����ʽ ����μ� Keke::keke_show_msg
	 */
	public function agreement_stage_one($user_type, $url = '', $output = 'normal') {
		global $_lang;
		$buyer_status = intval ( $this->_buyer_status ); //���ǩ��״̬
		$seller_sattus = intval ( $this->_seller_status ); //����ǩ��״̬
		$agree_status = intval ( $this->_agree_status ); //Э�鵱ǰ״̬
		

		if ($agree_status == '1') { //δȷ�ϲſ�ǩ��
			switch ($user_type) {
				case "1" : //����(����)
					if ($seller_sattus == '2') { //ǩ���Э��
						$notice = $_lang['you_has_agree_agreement_notice'];
					} else { //δǩ���
						$res = $this->set_agreement_status ( 'seller_status', '2' ); //����״̬
						if ($res) {
							/** ����ǩ��ʱ�� */
							dbfactory::execute ( sprintf ( " update %switkey_agreement set seller_accepttime='%s' where seller_uid='%d' and agree_id ='%d'", TABLEPRE, time (), $this->_seller_uid, $this->_agree_id ) );
							/** ���(����)ǩ��״̬�ж�**/
							switch ($buyer_status) {
								case "1" :
									$notice = $_lang['agreement_signed_complete_wait_you'];
									break;
								case "2" :
									$notice = $_lang['agreement_signed_complete_to_deliver'];
									$this->set_agreement_status ( 'agree_status', "2" ); //Э����뽻���׶�
									break;
							}
						} else {
							$notice = $_lang['agreement_signed_fail'];
							$type = 'error';
						}
					}
					break;
				case "2" : //���(����)
					if ($buyer_status == '2') { //ǩ���Э��
						$notice = $_lang['you_has_agree_not_sign'];
					} else { //δǩ���
						$res = $this->set_agreement_status ( 'buyer_status', '2' ); //����״̬
						if ($res) {
							/** ����ǩ��ʱ�� */
							dbfactory::execute ( sprintf ( " update %switkey_agreement set buyer_accepttime='%s' where buyer_uid ='%d' and agree_id='%d'", TABLEPRE, time (), $this->_buyer_uid, $this->_agree_id) );
							/** ����(����)ǩ��״̬�ж�**/
							switch ($seller_sattus) {
								case "1" :
									$notice = $_lang['agreement_signed_complete_wait_witkey'];
									break;
								case "2" :
									$notice = $_lang['agreement_signed_complete_to_deliver'];
									$this->set_agreement_status ( 'agree_status', "2" ); //Э����뽻���׶�
									break;
							}
						} else {
							$notice = $_lang['agreement_signed_fail'];
							$type = 'error';
						}
					}
					break;
			}
			$msg_obj = new keke_msg_class (); //��Ϣ��
			$s_arr = array ($_lang['agreement_link'] => $this->_agree_url, $_lang['agreement_status'] => $notice );
			$b_arr = array ($_lang['agreement_link']  => $this->_agree_url, $_lang['agreement_status'] => $notice );
			$msg_obj->send_message ( $this->_seller_uid, $this->_seller_username, "agreement", $_lang['deliver_agreement_sign'], $s_arr, $this->_seller_contact ['email'], $this->_seller_contact ['mobile'] ); //֪ͨ����
			$msg_obj->send_message ( $this->_buyer_uid, $this->_buyer_username, "agreement",  $_lang['deliver_agreement_sign'], $s_arr, $this->_buyer_contact ['email'], $this->_buyer_contact ['mobile'] ); ////֪ͨ����
		} else {
			$notice = $_lang['agreement_complete_no_confirm_again'];
			$type = 'error';
		}
		Keke::keke_show_msg ( $url, $notice, $type, $output ); //��Ϣ����
	}
	/**
	 * Դ�ļ��ϴ��ύ
	 * @param string $file_ids
	 */
	public function upfile_confirm($file_ids, $url = '', $output = 'normal') {
		global $uid;
		global $_lang;
		$uid != $this->_seller_uid and Keke::keke_show_msg ( $url, $_lang['warning_you_no_rights_submit'], "error", $output );
		$file_ids = implode ( ",", array_filter ( explode ( ",", $file_ids ) ) );
		$res = dbfactory::execute ( sprintf ( " update %switkey_agreement set seller_confirmtime = UNIX_TIMESTAMP(),file_ids = '%s' where agree_id='%d'", TABLEPRE, $file_ids, $this->_agree_id ) );
		$res *= $this->set_agreement_status ( 'seller_status', '3' ); //�������Ϊ�ȴ�����״̬
		$res *= $this->set_agreement_status ( 'buyer_status', '3' ); //������Ϊȷ�Ͻ���״̬
		

		$notice = $_lang['seller_has_submit_wait_buyrer'];
		$msg_obj = new keke_msg_class (); //��Ϣ��
		$v_arr = array ($_lang['the_initiator'] => $this->_seller_username, $_lang['agreement_link'] => $this->_agree_url, $_lang['action'] => $_lang['has_submit_source_files'], $_lang['agreement_status'] => $notice );
		$msg_obj->send_message ( $this->_buyer_uid, $this->_buyer_username, "agreement_file", $_lang['agreement_files_submit'], $v_arr, $this->_buyer_contact ['email'], $this->_buyer_contact ['mobile'] ); //֪ͨ����
		$res and Keke::keke_show_msg ( $url, $_lang['source_file_success'], "success", $output ) or Keke::keke_show_msg ( $url, $_lang['source_file_fail'], 'error', $output );
	}
	/**
	 * Դ�ļ�ȷ���ύ
	 * @param $Url ��תurl
	 * @parama $output ��תģʽ
	 * @param $trust_response �����ص���Ӧ
	 */
	public function accept_confirm($url = '', $output = 'normal', $trust_response = false) {
		global $uid;
		global $_lang;
		$agree_info = $this->_agree_info; //Э����Ϣ
		$uid != $this->_buyer_uid and Keke::keke_show_msg ( $url, $_lang['warning_you_no_rights_confirm'], "error", $output );
		$trust_info = $this->_trust_info;
		if ($this->_agree_status == 2 && $this->_seller_status == 3 && $this->_buyer_status == 3) {
				$res = dbfactory::execute ( sprintf ( " update %switkey_agreement set buyer_confirmtime = UNIX_TIMESTAMP() where agree_id ='%d'", TABLEPRE, $this->_agree_id ) );
				dbfactory::execute ( sprintf ( " update %switkey_task set task_status = '8' where task_id ='%d'", TABLEPRE, $this->_task_id ) );
				$res *= $this->set_agreement_status ( 'seller_status', '4' ); //���뻥���׶�
				$res *= $this->set_agreement_status ( 'buyer_status', '4' ); //���뻥���׶�
				$res *= $this->set_agreement_status ( 'agree_status', '3' ); //�������
				$this->dispose_task (); //�������
				$notice = $_lang['buyer_has_confirm_deliver_complete'];
				$msg_obj = new keke_msg_class (); //��Ϣ��
				$v_arr = array ($_lang['the_initiator'] => $this->_buyer_username, $_lang['agreement_link'] => $this->_agree_url, $_lang['action'] => $_lang['confirm_has_received_file'], $_lang['agreement_status'] => $notice );
				$msg_obj->send_message ( $this->_seller_uid, $this->_seller_username, "agreement_file", $_lang['agreement_file_recevie'], $v_arr, $this->_seller_contact ['email'], $this->_seller_contact ['mobile'] ); //֪ͨ����
				$res and Keke::keke_show_msg ( $url,$_lang['source_file_confirm_deliver_success'], "success", $output ) or Keke::keke_show_msg ( '', $_lang['file_confirm_fail_deliver_fail'], 'error', $output );
		} else {
			Keke::keke_show_msg ( $url, $_lang['current_status_can_not_confirm'], "error", $output );
		}
	}
	/**
	 * ��������������
	 */
	abstract function dispose_task();
	
	/**
	 * �������������� ÿ��+2
	 */
	public function plus_mark_num() {
		return dbfactory::execute ( sprintf ( "update %switkey_task set mark_num=ifnull(mark_num,0)+2 where task_id ='%d'", TABLEPRE, $this->_task_id ) );
	}
	/**
	 * ����(���)άȨ(�ٲ�)
	 * @param string $obj �ٲö���
	 * @param $obj_id ������
	 * @param $report_type �ٱ�����
	 * @param $to_uid ���ٲ���
	 * @param $to_username ���ٲ�������
	 * @param $file_name �ϴ��ļ�·��
	 * @return json
	 */
	public function set_report($obj, $obj_id, $to_uid, $to_username, $report_type, $file_name, $desc) {
		$res = keke_report_class::add_report ( $obj, $obj_id, $to_uid, $to_username, $desc, $report_type, '6', $this->_task_id, $this->_user_role, $file_name );
		$res&&$this->set_agreement_status($type = 'agree_status',4);
	}
	/**
	 * �׶β���Ȩ�޳�ʼ��
	 */
	abstract function process_can();
	/**
	 * Э��׶ν���Ȩ���ж�
	 */
	abstract function stage_access_check();
	/**
	 * �����׶�2ʱ��״̬�б�
	 * ��ǰ�û��ܽ���step2 ˵��������ǩ����Э�顣����ֻ���ж϶Է�״̬
	 * @param int $user_type �û�����  1=>����(����),2=>����(���)
	 */
	abstract function agreement_stage_list($user_type = '1');
	/**
	 * ��ȡ��ҽ���״̬
	 */
	abstract function get_buyer_status();
	/**
	 * ��ȡ���ҽ���״̬
	 */
	abstract function get_seller_status();
	/**
	 * ��ȡ��������
	 */
	public function get_file_list() {
		if ($this->_agree_info ['file_ids']) {
			return dbfactory::query ( sprintf ( " select file_id,file_name,save_name from %switkey_file where obj_type='agreement' and obj_id='%d'", TABLEPRE, $this->_agree_id ) );
		}
	}
	/**
	 * ɾ���ϴ�����
	 * @param int $file_id
	 */
	public function del_file($file_id) {
		$res = keke_file_class::del_att_file ( $file_id );
		$res and Keke::echojson ( '', '1' ) or Keke::echojson ( '', '0' );
		die ();
	}
	/**
	 * ����Э�����״̬
	 * @param string $type Ҫ����״̬���ֶ��� Ĭ��Э��״̬  ����buyer_status seller_status
	 */
	public function set_agreement_status($type = 'agree_status', $to_status) {
		return dbfactory::execute ( sprintf ( " update %switkey_agreement set %s = '%d' where agree_id = '%d'", TABLEPRE, $type, $to_status, $this->_agree_id ) );
	}
	/**
	 * ��ȡ����״̬��Ϣ
	 * @return array
	 */
	public static function get_agreement_status() {
		global $_lang;
		return array ("1" => $_lang['wait_sign'], "2" => $_lang['agreement_sign_complete'], "3" => $_lang['task_order_complete'] );
	}

}