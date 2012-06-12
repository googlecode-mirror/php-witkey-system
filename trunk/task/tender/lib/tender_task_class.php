<?php
/**
 * ��ͨ�б�ҵ����
 * @method init ������Ϣ��ʼ��
 * =>����״̬������Ϣ
 * =>�����������
 * check_if_bided        ����Ƿ��б�			 
 * 
 * get_task_stage_desc	        ��ȡ����׶�����
 * get_task_timedesc 	        ��ȡ����ʱ������
 * get_task_work		        ��ȡ����ָ��״̬�ĸ����Ϣ
 * get_work_info      	        ��ȡ��������Ϣ
 *
 * start_vote                   ����ͶƱ
 * set_task_vote      			 ����ͶƱ����
 * set_work_status   			 ���״̬���
 * set_task_sp_end_time			��������ʾʱ��
 *
 * dispose_witkey_prom   		 �����ƹ����
 * dispose_employer_prom  		 �����ƹ����
 * dispose_task		   		 ���������
 * dispose_task_return    		 �������
 *
 * auto_choose    	    	          �Զ�ѡ��
 *
 *ʱ����
 * time_task_gs   	       	     ����ʾ
 * time_task_vote     		     ����ͶƱ
 * time_task_end      		     �������
 *
 * process_can 	    	                ��ǰ�����ж�
 * work_hand  		      	      ���񽻸�
 * work_choose 	      	                ����ѡ��
 */
keke_lang_class::load_lang_class ( 'tender_task_class' );
class tender_task_class extends keke_task_class {
	
	public $_task_status_arr; // ����״̬����
	public $_work_status_arr; // ���״̬����
	

	public $_delay_rule; // ���ڹ���
	public $_time_rule; // ʱ�����
	

	public $_cove_obj;
	public $_cash_cove_obj;
	public $_task_bid_obj;
	public $_cash_arr;
	protected $_inited = false;
	
	public static function get_instance($task_info) {
		static $obj = null;
		if ($obj == null) {
			$obj = new tender_task_class ( $task_info );
		}
		return $obj;
	}
	public function __construct($task_info) {
		parent::__construct ( $task_info );
		$this->init ();
	}
	
	public function init() {
		if (! $this->_inited) {
			$this->status_init ();
			$this->time_rule_init ();
			$this->delay_rule_init ();
			$this->wiki_priv_init ();
		}
		$this->_inited = true;
		
		$this->_task_bid_obj = new Keke_witkey_task_bid_class ();
		$this->_cash_cove_obj = new Keke_witkey_task_cash_cove_class ();
		$this->_cash_arr = $this->_cash_cove_obj->query_keke_witkey_task_cash_cove ();
	
	}
	/**
	 * ����(���)״̬������Ϣ
	 */
	public function status_init() {
		$this->_task_status_arr = $this->get_task_status ();
		$this->_work_status_arr = $this->get_work_status ();
	}
	/**
	 * ����ʱ�����
	 */
	public function time_rule_init() {
		$this->_time_rule = keke_task_config::get_time_rule ( $this->_model_id, '3600' );
	}
	/**
	 * �������ڹ���
	 */
	public function delay_rule_init() {
		$this->_delay_rule = keke_task_config::get_delay_rule ( $this->_model_id, '3600' );
	}
	/**
	 * ����Ȩ�޶����ж�
	 */
	public function wiki_priv_init() {
		$arr = tender_priv_class::get_priv ( $this->_task_id, $this->_model_id, $this->_userinfo );
		$this->_priv = $this->user_priv_format ( $arr );
	}
	
	/**
	 * ��ȡ��������
	 */
	public function get_task_coverage() {
		$covers = kekezu::get_cash_cove ();
		/*
		 * $this->_cash_cove_obj->setWhere("cash_rule_id =
		 * ".$this->_task_info['task_cash_coverage']); $cover_info =
		 * $this->_cash_cove_obj->query_keke_witkey_task_cash_cove();
		 */
		$cover_info = $covers [$this->_task_info ['task_cash_coverage']];
		
		return $cover_info ['cove_desc'];
	
	}
	
	public function work_hand($work_desc, $hdn_att_file, $hidework = '2', $url = '', $output = 'normal') {
	}
	
	/**
	 * ���񽻸�
	 * 
	 * @param $work_desc string
	 * ��������
	 * @param $hidework int
	 * ������� 1=>����,2=>������ Ĭ��Ϊ������
	 * @param $work_file string
	 * ���������Ŵ� eg:1,2,3,4,5
	 * @param $mobile string
	 * �û��ֻ� ͨ���ֻ���֤�Ĳ�֧���޸�
	 * @param $qq string
	 * �û�QQ
	 * @param $url string
	 * ��Ϣ��ʾ���� ����μ� kekezu::keke_show_msg
	 * @param $output string
	 * ��Ϣ�����ʽ ����μ� kekezu::keke_show_msg
	 * @see keke_task_class::work_hand()
	 */
	public function tender_work_hand($work_info, $url = '', $output = 'normal') {
		global $kekezu, $_K;
		global $_lang;
		if ($this->check_if_can_hand ( $url, $output )) {
			// �ж��Ƿ��ѽ���
			$this->_task_bid_obj->setWhere ( "task_id = $this->_task_id and uid = $this->_uid and bid_status=0" );
			$is_hand = $this->_task_bid_obj->count_keke_witkey_task_bid ();
			$is_hand and kekezu::keke_show_msg ( '', $_lang['you_haved_tender'], 'error', $output );
			$this->_task_bid_obj->setUid ( $this->_uid );
			$this->_task_bid_obj->setUsername ( $this->_username );
			$this->_task_bid_obj->setArea ( $work_info ['area'] );
			$this->_task_bid_obj->setCycle ( $work_info ['task_over_time'] );
			$this->_task_bid_obj->setQuote ( $work_info ['txt_cash'] );
			$this->_task_bid_obj->setTask_id ( $this->_task_id );
			$this->_task_bid_obj->setBid_time ( time () );
			$this->_task_bid_obj->setHidden_status ( $work_info ['workhide'] );
			$this->_task_bid_obj->setMessage ( $work_info ['tar_content'] );
			$res = $this->_task_bid_obj->create_keke_witkey_task_bid ();
			if ($res) {
				// �Ƿ�����������
				if ($this->_task_info ['task_union'] == '1') {
					$union_obj = new keke_union_class ( $this->_task_id );
					$union_obj->work_hand ( $res );
				}
			}
			$work_info ['workhide'] == 1 and keke_payitem_class::payitem_cost ( "workhide", '1', 'work', 'spend', $res, $this->_task_id );
			// ֪ͨ�������˽���
			$this->plus_work_num (); // ��������������
			$this->plus_take_num (); // �����û���������
			$url = '<a href ="' . $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '">' . $this->_task_title . '</a>';
			$v_arr = array ($_lang['username'] => "$this->_gusername", $_lang['user'] => $this->_username, $_lang['call'] => $_lang['you'], $_lang['task_title'] => $url, $_lang['website_name'] => kekezu::$_sys_config ['website_name'] );
			keke_shop_class::notify_user ( $this->_guid, $this->_gusername, 'task_hand', $_lang['hand_work_notice'], $v_arr );
			
			kekezu::notify_user ( $_lang['hand_work_notice'], $_lang['you_pub_tender_task_'] . '<a href=index.php?do=task&task_id=' . $this->_task_id . '&view=work>' . $this->_task_info ['task_title'] . '</a>' . $_lang['have_new_work'], $this->_guid );
			kekezu::keke_show_msg ( $url, $_lang['tender_success'], 'right', $output );
		
		}
	}
	
	/**
	 * �б�
	 * 
	 * @param $url string
	 * ��Ϣ��ʾ���� ����μ� kekezu::keke_show_msg
	 * @param $output string
	 * ��Ϣ�����ʽ ����μ� kekezu::keke_show_msg
	 * @see keke_task_class::work_choose()
	 */
	public function work_choose($work_id, $to_status, $url = '', $output = 'json', $trust_response = false) {
		global $_K;
		global $_lang;
		$bid_info = $this->select_bid_check ( $work_id, $url );
		$status_arr = $this->get_work_status ();
		// �ı���״ֵ̬
		if ($this->set_work_status ( $work_id, $to_status )) {
			if ($to_status == 4) { // �б���ʾ�û�
				$this->set_task_status ( 4 ); // *��������״̬Ϊ������**/
				$this->plus_accepted_num ( $bid_info ['uid'] );
				
				// �Ƿ�����������
				if ($this->_task_info ['task_union'] == '1') {
					$union_obj = new keke_union_class ( $this->_task_id );
					$union_obj->work_choose ( $work_id );
				}
				// д��feed
				$feed_arr = array ("feed_username" => array ("content" => $bid_info ['username'], "url" => "index.php?do=space&member_id=$bid_info[uid] " ), "action" => array ("content" => $_lang['success_bid_haved'], "url" => "" ), "event" => array ("content" => "$this->_task_title", "url" => "index.php?do=task&task_id={$this->_task_id}" ) );
				kekezu::save_feed ( $feed_arr, $bid_info ['uid'], $bid_info ['username'], 'work_accept', $this->_task_info ['task_id'] );
			}
			// ֪ͨ����
			$url = '<a href ="' . $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id . '" target="_blank" >' . $this->_task_title . '</a>';
			$v = array ($_lang['action'] => $status_arr [$to_status], $_lang['task_id'] => $this->_task_id, $_lang['task_title'] => $url );
			$this->notify_user ( "task_bid", $_lang['work'] . $status_arr [$to_status], $v, '1', $bid_info ['uid'] ); // ֪ͨ����
			kekezu::keke_show_msg ( $url, $_lang['choose_tender_success'], '', $output );
		} else {
			kekezu::keke_show_msg ( $url, $_lang['choose_tender_fail'], 'error', $output );
		}
	}
	
	/**
	 * ���ø��Ϊ��̭
	 */
	public function set_work_eliminate($work_id, $to_status, $url = '', $output = 'normal') {
		global $_lang;
		$bid_info = $this->select_bid_check ( $work_id, $url );
		// �ж��Ƿ������б���
		$this->_task_bid_obj->setWhere ( " task_id = " . intval ( $this->_task_id ) . " and bid_status = 4" );
		$count = $this->_task_bid_obj->count_keke_witkey_task_bid ();
		$count > 0 and kekezu::keke_show_msg ( $url, $_lang['haved_bid_work'], '', $output );
		// �ı���״ֵ̬
		$res = $this->set_work_status ( $work_id, $to_status );
		// ֪ͨ����
		kekezu::notify_user ( $_lang['work_notice'], $_lang['you_join_normal_tender_task'] . '<a href=index.php?do=task&task_id=' . $this->_task_id . ">" . $this->_task_info ['task_title'] . "</a>" . $_lang['work_out'], $bid_info ['uid'] );
		// д��feed
		$feed_arr = array ("feed_username" => array ("content" => $bid_info ['username'], "url" => "index.php?do=space&member_id={$bid_info['uid']} " ), "action" => array ("content" => $_lang['success_bid_haved'], "url" => "" ), "event" => array ("content" => "$this->_task_title", "url" => "index.php?do=task&task_id=$this->_task_info ['task_id']" ) );
		kekezu::save_feed ( $feed_arr, $bid_info ['uid'], $bid_info ['username'], 'work_accept', $this->_task_info ['task_id'] );
		// kekezu::feed_add ( '<a target="_blank"
		// href="index.php?do=space&member_id=' . $bid_info ['uid'] . '">' .
		// $bid_info ['username'] . '</a>�ɹ��б�������<a
		// href="index.php?do=task&task_id=' . $this->_task_info ['task_id'] .
		// '">' . $this->_task_title . '</a>', $bid_info ['uid'], $bid_info
		// ['username'], 'work_accept' );
		// �����б����
		$this->plus_accepted_num ( $bid_info ['uid'] );
		if ($res) {
			kekezu::keke_show_msg ( $url, $_lang['operate_success'], '', $output );
		} else {
			kekezu::keke_show_msg ( $url, $_lang['operate_fail'], 'error', $output );
		}
	}
	
	/**
	 * ѡ��ǰ���
	 */
	public function select_bid_check($work_id, $url) {
		global $_lang;
		// �ж��Ƿ�Ϊ����
		$this->_uid != $this->_guid and kekezu::keke_show_msg ( $url, $_lang['sorry_you_not_rightS_operate'] );
		// �ж��Ƿ����б����̭
		$this->_task_bid_obj->setWhere ( " bid_id = " . $work_id );
		$bid_info = $this->_task_bid_obj->query_keke_witkey_task_bid ();
		$bid_info = $bid_info ['0'];
		$bid_info ['bid_status'] and kekezu::keke_show_msg ( $url, $_lang['please_not_repeat_bid'] );
		// �ж��Ƿ�Ϊѡ��ʱ��
		$this->_task_info ['task_status'] != 3 && $this->_task_config ['open_select'] != 'open' and kekezu::keke_show_msg ( $url, $_lang['present_status_not_choose_work'] );
		
		return $bid_info;
	
	}
	
	/**
	 * ��ȡ��������Ϣ ֧�ַ�ҳ���û�ǰ�˸���б�
	 * 
	 * @param $w array
	 * ǰ�˲�ѯ��������
	 * ['work_status'=>���״̬
	 * 'user_type'=>�û����� --��ֵ��ʾ�Լ�
	 * ......]
	 * @param $p array
	 * ǰ�˴��ݵķ�ҳ��ʼ��Ϣ����
	 * ['page'=>��ǰҳ��
	 * 'page_size'=>ҳ������
	 * 'url'=>��ҳ����
	 * 'anchor'=>��ҳê��]
	 * @return array work_list
	 */
	public function get_work_info($w = array(), $order = null, $p = array()) {
		global $kekezu, $_K;
		$work_arr = array ();
		
		$sql = " select a.* from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		
		$count_sql = " select count(a.work_id) from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$where = " where a.task_id = '$this->_task_id' ";
		
		if (! empty ( $w )) {
			$w ['user_type'] == 'my' and $where .= " and a.uid = '$this->_uid'";
			isset ( $w ['work_status'] ) and $where .= " and a.work_status = '" . intval ( $w ['work_status'] ) . "'";
		/**
		 * �����*
		 */
		}
		$where .= " order by work_time desc ";
		if (! empty ( $p )) {
			$page_obj = kekezu::$_page_obj;
			$count = intval ( dbfactory::get_count ( $count_sql . $where ) );
			$pages = $page_obj->getPages ( $count, $p ['page_size'], $p ['page'], $p ['url'], $p ['anchor'] );
			$where .= $pages ['where'];
		}
		$work_info = dbfactory::query ( $sql . $where );
		$work_arr ['work_info'] = $work_info;
		$work_arr ['pages'] = $pages;
		return $work_arr;
	}
	
	/**
	 * ���ø��״̬
	 */
	
	function set_work_status($work_id, $status) {
		
		$this->_task_bid_obj->setWhere ( "bid_id = $work_id and task_id = $this->_task_id" );
		$this->_task_bid_obj->setBid_status ( $status );
		$res = $this->_task_bid_obj->edit_keke_witkey_task_bid ();
		
		return $res;
	
	}
	
	/**
	 * �����ж�
	 * //ע���û�Ȩ�޵��ж�
	 * ������������Ȩ�޵����ơ���ӵ�����͵�����Ȩ��
	 * �����ϸ��ܵ�����Լ��
	 * �������ƣ��鿴����
	 * ����
	 * �ٱ�
	 * 
	 * @see keke_task_class::process_can()
	 */
	public function process_can() {
		$wiki_priv = $this->_priv; // ����Ȩ������
		$process_arr = array ();
		$status = intval ( $this->_task_status );
		$task_info = $this->_task_info;
		$config = $this->_task_config;
		$g_uid = $this->_guid;
		$uid = $this->_uid;
		$user_info = $this->_userinfo;
		
		switch ($status) {
			case "2" : // Ͷ����
				switch ($g_uid == $uid) { // ����
					case "1" :
						$process_arr ['reqedit'] = true; // ��������
						if ($config ['open_select'] == 'open') {
							$process_arr ['work_choose'] = true; // ����Ͷ����ѡ��
						}
						$process_arr ['work_comment'] = true; // ����ظ�
						break;
					case "0" : // ����
						$process_arr ['work_hand'] = true; // �ύ���
						$process_arr ['task_comment'] = true; // ����ظ�
						$process_arr ['task_report'] = true; // ����ٱ�
						break;
				}
				
				break;
			case "3" : // ѡ����
				switch ($g_uid == $uid) { // ����
					case "1" :
						$process_arr ['work_choose'] = true; // ѡ��
						$process_arr ['work_comment'] = true; // ����ظ�
						break;
					case "0" : // ����
						$process_arr ['task_comment'] = true; // ����ظ�
						$process_arr ['task_report'] = true; // ����ٱ�
						break;
				}
				
				break;
			case "4" : // ������
				$bid_info = $this->get_bid_info ();
				switch ($g_uid == $uid) { // ����
					case "1" :
						$process_arr ['work_comment'] = true; // ���Իظ�
						$bid_info ['ext_status'] == 1 and $process_arr ['work_over'] = true;
						break;
					case "0" :
						$process_arr ['task_comment'] = true; // ����ظ�
						$bid_info ['ext_status'] != 1 and $process_arr ['pub_agreement'] = true;
						$process_arr ['task_report'] = true; // ����ٱ�
						break;
				}
				$process_arr ['work_report'] = true; // ����ٱ�
				break;
			
			case "5" : // ���ͽ�����
				$bid_info = $this->get_bid_info ();
				
				switch ($g_uid == $uid) { // ����
					case "1" :
						
						$bid_info ['ext_status'] == 1 and $process_arr ['work_over'] = true;
						break;
					case "0" :
						$this->_uid == $bid_info ['uid'] && $bid_info ['ext_status'] != 1 and $process_arr ['pub_agreement'] = true;
						break;
				}
				break;
			
			case "8" : // �ѽ���
				switch ($g_uid == $uid) { // ����
					case "1" :
						$process_arr ['work_comment'] = true; // ���Իظ�
						$process_arr ['work_mark'] = true; // �������
						

						break;
					case "0" :
						$process_arr ['task_comment'] = true; // ����ظ�
						$process_arr ['task_mark'] = true; // ��������
						

						break;
				}
				break;
		}
		$uid != $g_uid and $process_arr ['task_complaint'] = true; // ����Ͷ��
		$process_arr ['work_complaint'] = true; // ���Ͷ��
		$this->_process_can = $process_arr;
		return $process_arr;
	}
	/**
	 *
	 * @return ������ͨ�б�����״̬
	 */
	public static function get_task_status() {
		global $_lang;
		return array ("0" => $_lang['task_no_pay'], "1" => $_lang['task_wait_audit'], "2" => $_lang['tendering'], "3" => $_lang['choose_tendering'], "4" => $_lang['working'], "5" => $_lang['jfing'], "7" => $_lang['freeze'], "8" => $_lang['task_over'], "9" => $_lang['fail'], "10" => $_lang['task_audit_fail'], "11" => $_lang['arbitrate'] );
	
	}
	
	/**
	 *
	 * @return ������ͨ�б���״̬
	 *
	 */
	public static function get_work_status() {
		global $_lang;
		return array ('4' => $_lang['task_bid'], '7' => $_lang['task_out'], '8' => $_lang['task_can_not_choose_bid'] );
	
	}
	
	/**
	 *
	 * @return ��������Ӣ��״̬
	 */
	public static function get_task_union_status() {
		return array ('0' => "wait", '1' => "audit", '2' => "sub", '3' => "choose", '4' => "vote", '5' => "notice", '6' => 'deliver', '7' => "freeze", '8' => "end", '9' => "failure", '10' => "audit_fail", '11' => "arbitrate" );
	}
	/**
	 *
	 * @return ���ظ��Ӣ��״̬
	 */
	public static function get_work_union_status() {
		return array ('0' => 'wait', '4' => 'bid', '8' => 'no_optional' );
	}
	
	/**
	 * ����׶�ʱ������
	 */
	public function get_task_timedesc() {
		global $_lang;
		$status_arr = $this->_task_status_arr;
		$task_status = $this->_task_status;
		$task_info = $this->_task_info;
		$time_desc = array ();
		switch ($task_status) {
			case "0" :
				$time_desc ['ext_desc'] = $_lang['task_not_payment'];
				break;
			case "1" :
				$time_desc ['ext_desc'] = $_lang['task_auditing'];
				break;
			case "2" : // Ͷ����
				$time_desc ['time_desc'] = $_lang['from_hand_work_deadline']; // ʱ��״̬����
				$time_desc ['time'] = $task_info ['sub_time']; // ��ǰ״̬����ʱ��
				$time_desc ['ext_desc'] = $_lang['task_doing_can_tender']; // ׷������
				if ($this->_task_config ['open_select'] == 'open') { // ��������ѡ��
					$time_desc ['g_action'] = $_lang['present_state_employer_can_choose']; // ����׷������
				}
				break;
			case "3" : // ѡ����
				$time_desc ['time_desc'] = $_lang['from_choose_deadline']; // ʱ��״̬����
				$time_desc ['time'] = $task_info ['end_time']; // ��ǰ״̬����ʱ��
				$time_desc ['ext_desc'] = $_lang['task_choosing_tender']; // ׷������
				break;
			case "4" : // ������
				$time_desc ['ext_desc'] = $_lang['employer_haved_choose_and_witkey_working']; // ׷������
				break;
			case "5" : // ������
				$time_desc ['ext_desc'] = $_lang['task_in_jf_rate']; // ׷������
				break;
			case "7" : // ������
				$time_desc ['ext_desc'] = $_lang['task_diffrent_opnion_and_web_in']; // ׷������
				break;
			case "8" : // ����
				$time_desc ['ext_desc'] = $_lang['task_haved_complete']; // ׷������
				break;
			case "9" : // ʧ��
				$time_desc ['ext_desc'] = $_lang['task_timeout_and_no_works_fail']; // ׷������
				break;
			case "11" : // �ٲ�
				$time_desc ['ext_desc'] = $_lang['task_arbitrating']; // ׷������
				break;
		}
		
		return $time_desc;
	}
	
	// ��ȡ�б���
	function get_bid_info() {
		$this->_task_bid_obj->setWhere ( " task_id = $this->_task_id and bid_status = 4" );
		$bid_info = $this->_task_bid_obj->query_keke_witkey_task_bid ();
		$bid_info = $bid_info ['0'];
		if ($bid_info) {
			return $bid_info;
		} else {
			return false;
		}
	}
	
	// �ı���״̬
	function set_bid_status($bid_id, $bid_status) {
		$this->_task_bid_obj->setWhere ( " bid_id = $bid_id" );
		$this->_task_bid_obj->setBid_status ( $bid_status );
		$res = $this->_task_bid_obj->edit_keke_witkey_task_bid ();
		if ($res) {
			return $res;
		} else {
			return false;
		}
	
	}
	
	// �ı�Э��״̬
	function set_agreement_status($bid_id, $status) {
		$this->_task_bid_obj->setWhere ( " bid_id = $bid_id" );
		$this->_task_bid_obj->setExt_status ( $status );
		$res = $this->_task_bid_obj->edit_keke_witkey_task_bid ();
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}
	
	public function dispose_order($order_id) {
		global $kekezu, $_K;
		global $_lang;
		// ��̨����
		$task_config = $this->_task_config;
		$task_info = $this->_task_info; // ������Ϣ
		$url = $_K ['siteurl'] . '/index.php?do=task&task_id=' . $this->_task_id;
		$task_status = $this->_task_status;
		$order_info = dbfactory::get_one ( sprintf ( "select order_amount,order_status from %switkey_order where order_id='%d'", TABLEPRE, intval ( $order_id ) ) );
		$order_amount = $order_info ['order_amount'];
		if ($order_info ['order_status'] == 'ok') {
			$task_status == 1 && $notice = $_lang['task_pay_success_and_wait_admin_audit'];
			$task_status == 2 && $notice = $_lang['task_pay_success_and_task_pub_success'];
			return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $notice, $url, 'success' );
		} else {
			$res = keke_finance_class::cash_out ( $task_info ['uid'], $order_amount, 'pub_task',$task_info['task_cash']); // ֧������
			switch ($res == true) {
				case "1" : // ֧���ɹ�
					/**
					 * �����ƹ��¼�����
					 */
					$kekezu->init_prom ();
					if (kekezu::$_prom_obj->is_meet_requirement ( "pub_task", $this->_task_id )) {
						kekezu::$_prom_obj->create_prom_event ( "pub_task", $this->_guid, $task_info ['task_id'], $task_info ['task_cash'] );
					}
					// ���Ķ���״̬���Ѹ���״̬
					dbfactory::updatetable ( TABLEPRE . "witkey_order", array ("order_status" => "ok" ), array ("order_id" => "$order_id" ) );
					if ($order_amount < $task_config ['audit_cash']) { // ��������Ľ��ȷ�������ʱ���õ���˽��ҪС
						$this->set_task_status ( 1 ); // ״̬����Ϊ���״̬
						return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_success_and_wait_admin_audit'], $url, 'success' );
					} else {
						$this->set_task_status ( 2 ); // ״̬����Ϊ����״̬
						return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_success_and_task_pub_success'], $url, 'success' );
					}
					break;
				case "0" : // ֧��ʧ��
					$pay_url = $_K ['siteurl'] . "/index.php?do=pay&order_id=$order_id"; // ֧����ת����
					return pay_return_fac_class::struct_response ( $_lang['operate_notice'], $_lang['task_pay_error_and_please_repay'], $pay_url, 'warning' );
					break;
			}
		}
	}

}