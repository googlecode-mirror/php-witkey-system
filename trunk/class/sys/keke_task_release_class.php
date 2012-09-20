<?php

/**
 * @author       Administrator
 * std_obj Ϊ��ĳ�Ա������������release_info������Ϣ,att_info��������Ϣ
 */
keke_lang_class::load_lang_class ( 'keke_task_release_class' );
abstract class keke_task_release_class {
	public $_uid;
	public $_username;
	public $_user_info; //��ǰ�û���Ϣ
	public $_kf_info; //�ͷ���Ϣ
	

	public $_priv; //����Ȩ�޻�ȡ
	public $_default_max_day; //Ĭ��Ԥ���µ��������
	

	public $_pub_mode; //����ģʽ
	

	public $_model_id; //ģ�ͱ��
	public $_model_info; //ģ����Ϣ
	public $_task_config; //��������
	public $_pay_item; //����������
	public $_inited = false;
	public $_task_obj; //�������
	

	public $_std_obj; //��Ա���󣬴�����񷢲���Ϣ,��ֵ������Ϣ��������session��ʵ�ֿ�ҳ�洫����������
	function __construct($model_id, $pub_mode = 'professional') {
		global $kekezu;
		$this->_task_obj = new Keke_witkey_task_class ();
		$this->_model_id = $model_id;
		$this->_pub_mode = $pub_mode;
		
		$this->_model_info = Keke::$_model_list [$model_id];
		
		$this->_std_obj = new stdClass ();
		$this->_std_obj->_release_info = array (); //���񷢲���Ϣ
		

		$this->_std_obj->_att_info = array (); //������ֵ������Ϣ
		

		$this->init ();
	
	}
	function init() {
		if (! $this->_inited) {
			$this->user_info_init ();
			$this->get_rand_kf ();
			
			$this->pay_item_init ();
		
		}
		$this->_inited = true;
	}
	/**
	 * ��ʼ����ǰ�û���Ϣ
	 */
	function user_info_init() {
		global $user_info, $uid, $username;
		$this->_user_info = $user_info;
		$this->_uid = $uid;
		$this->_username = $username;
	}
	/**
	 * ��ȡ���ӷ�������
	 * @return   void
	 */
	public function pay_item_init() {
		$this->_pay_item = keke_payitem_class::get_payitem_config ( 'employer', $this->_model_info ['model_code'] );
	}
	/**
	 * �����ȡ�ͷ�
	 * @return   void
	 */
	public function get_rand_kf() {
		$this->_kf_info = Keke::get_rand_kf ();
	}
	
	/**
	 * ��ȡ����󶨸�����ҵ��û�����ȡȫ��ҵ��
	 * @return   void
	 */
	public function get_bind_indus() {
		global $kekezu;
		if ($this->_model_info ['indus_bid']) {
			$bind_indus = implode ( ',', array_filter ( explode ( ',', $this->_model_info ['indus_bid'] ) ) );
			return Keke::get_table_data ( '*', "witkey_industry", "indus_id in (select indus_pid from " . TABLEPRE . "witkey_industry where indus_id in({$bind_indus}))", 'listorder desc', '', '', 'indus_id', null );
		} else {
			return $this->_indus_arr = Keke::$_indus_p_arr;
		
		}
	}
	/**
	 * ��ȡ��������ҵ��Ϣ(Ҳ�������첽��ȡ)
	 * @param int $indus_pid ������ҵ
	 * @param string $ajax�첽������
	 */
	public function get_task_indus($indus_pid = '', $ajax = '') {
		global $kekezu;
		global $_lang;
		if ($indus_pid > 0) {
			if ($this->_model_info ['indus_bid']) {
				$indus_ids = Keke::get_table_data ( '*', "witkey_industry", "indus_id in ({$this->_model_info['indus_bid']}) and indus_pid = $indus_pid", 'listorder desc', '', '', 'indus_id', null );
			} else {
				$indus_ids = Keke::get_table_data ( '*', "witkey_industry", " indus_pid = $indus_pid", 'listorder desc', '', '', 'indus_id', null );
			}
			switch ($ajax == 'show_indus') {
				case "0" :
					return $indus_ids;
					break;
				case "1" :
					$option .= '<option value=""> ' . $_lang ['please_son_industry'] . ' </option>';
					foreach ( $indus_ids as $v ) {
						$option .= '<option value=' . $v [indus_id] . '>' . $v [indus_name] . '</option>';
					}
					CHARSET == 'gbk' and $option = Keke::gbktoutf ( $option );
					echo $option;
					die ();
					break;
			}
		} else
			return false;
	}
	
	/**
	 * ���񸽼����
	 * @param $task_id ������
	 * @param $title �������
	 */
	function save_task_file($task_id, $title) {
		$release_info = $this->_std_obj->_release_info;
		if ($release_info ['file_ids']) {
			$file_obj = new Keke_witkey_file_class ();
			$file_arr = array_filter ( explode ( ',', $release_info ['file_ids'] ) );
			foreach ( $file_arr as $v ) {
				$file_obj->setFile_id ( $v );
				$file_obj->setUid ( $this->_uid );
				$file_obj->setUsername ( $this->_username );
				$file_obj->setTask_id ( $task_id );
				$file_obj->setTask_title ( $title );
				$file_obj->edit_keke_witkey_file ();
			}
		}
	}
	/**
	 * ������Ϣ    ���񷢲��ɹ�����ʾ�û�
	 * @param int $task_id ������
	 * @param int $task_status ����״̬ Ĭ�Ϸ����ɹ�
	 */
	public function notify_user($task_id, $task_status = '2') {
		global $_K;
		global $_lang;
		$task_obj = $this->_task_obj;
		$model_code = $this->_model_info ['model_code'];
		$status_arr = call_user_func ( array ($model_code . "_task_class", 'get_task_status' ) ); //����״̬����
		$message_obj = new keke_msg_class ();
		$url = '<a href="' . $_K ['siteurl'] . '/index.php?do=task&task_id=' . $task_id . '"  target="_blank">' . $task_obj->getTask_title () . '</a>';
		//$url = urlencode($url);
		$v = array ($_lang ['task_id'] => $task_id, $_lang ['task_title'] => $std_obj->_task_title, $_lang ['task_link'] => $url, $_lang ['task_status'] => $status_arr [$task_status], $_lang ['start_time'] => date ( 'Y-m-d H:i:s', $task_obj->getStart_time () ), $_lang ['hand_work_timeout'] => date ( 'Y-m-d H:i:s', $task_obj->getSub_time () ), $_lang ['choose_timeout'] => date ( 'Y-m-d H:i:s', $task_obj->getEnd_time () ) );
		$message_obj->send_message ( $this->_uid, $this->_username, "task_pub", $_lang ['pub_task_notice'], $v, $this->_user_info ['email'], $this->_user_info ['mobile'] );
	}
	
	/**
	 * ���������ܽ��
	 * ��������+��ֵ�������
	 * @param float $task_cash ������޸��ӵȣ�
	 * @return float $total_cash;
	 */
	public function get_total_cash($task_cash) {
		
		$total_cash = $task_cash + $this->_std_obj->_att_cash; //������+��ֵ����
		return $total_cash;
	}
	/**
	 * ���񷢲�״̬�����Լ����񻨷ѽ�����ҵļ���
	 * ���ظ��ݺ�̨���õ���С���ó��ĵ�ǰ����ɷ���״̬
	 * @param $total_cash �����ܽ��(������ֵ����)
	 * @param $task_cash  ������(������ֵ����)
	 * @param $is_trust �Ƿ񵣱�
	 */
	public function set_task_status($total_cash, $task_cash) {
		global $kekezu;
		$basic_config = Keke::$_sys_config;
		$balance = $this->_user_info ['balance'];
		$credit = $this->_user_info ['credit'];
		if ($balance + $credit >= $total_cash) { //�û���������ܻ��ѵ������
			$model_code = $this->_model_info ['model_code'];
			switch ($model_code) {
				case "tender" :
					$this->_task_config ['zb_audit'] == 2 and $task_status = "2" or $task_status = "1";
					break;
				case "match" :
					$task_status = "2";
					break;
				default :
					if ($task_cash >= $this->_task_config ['audit_cash']) { //�������������С������ƣ��û�������� �������
						$task_status = '2'; //����״̬������Ϊ�ɹ�����
					} elseif ($task_cash < $this->_task_config ['audit_cash']) { //�������С����С���,��Ҫ���
						$task_status = "1"; //����״̬����Ϊ��Ҫ���
					}
					if ($basic_config ['credit_is_allow'] == '2') { //��ҹ�
						$cash_cost = $task_cash;
						$credit_cost = '0';
					} else { //��
						if ($credit >= $task_cash) { //�����ܽ�
							$credit_cost = $task_cash;
							$cash_cost = '0';
						} else {
							$credit_cost = $credit;
							$cash_cost = $task_cash - $credit;
						}
					}
			}
		} else {
			$task_status = "0"; //�ӳ�֧��
		}
		$this->_task_obj->setTask_status ( $task_status ); //��������״̬
		$this->_task_obj->setCash_cost ( $cash_cost ); //�ֽ𻨷�
		$this->_task_obj->setCredit_cost ( $credit_cost ); //��һ���
	}
	/**
	 * �ύ��֤
	 */
	private function submit_check(){
		global $kekezu,$_lang;
		$std_obj = $this->_std_obj;//��Ա����
		$r_info = $std_obj->_release_info;//���񷢲���Ϣ
		$pass = true;
		if(empty($r_info)||!is_array($r_info)){
			$pass = false;
		}else{
			$tmp = array();
			$tmp['t']  = trim($r_info['txt_title']);
			$tmp['i']  = trim($r_info['indus_id']);
			$tmp['pi'] = trim($r_info['indus_pid']);
			//$tmp['d']  = strtotime($r_info['txt_task_day']);
			$kekezu->get_cash_cove($this->_model_info['model_code']) and $c=intval($r_info['task_cash_cove']) or $c=floatval($r_info['txt_task_cash']);
			$tmp['c']  = $c;
			if(sizeof($tmp)!=sizeof(array_filter($tmp))){
				$pass = false;
			}
		}
		$pass===false&&Keke::show_msg($_lang['operate_notice'],$_SERVER['HTTP_REFERER'],3,$_lang['key_information_missed'],'warning');
	}
	/**
	 * ���񷢲�ͨ�ÿ�����
	 * ��������������ͨ�õ�����
	 */
	public function public_pubtask() {
		$this->submit_check();
		$std_obj = $this->_std_obj; //��Ա����
		$release_info = $std_obj->_release_info; //���񷢲���Ϣ
		$task_obj = $this->_task_obj; //�������
		$user_info = $this->_user_info;
		
		$txt_task_title = Keke::str_filter ( $release_info ['txt_title'] ); //�������
		$task_obj->setTask_title ( $txt_task_title );
		$task_obj->setModel_id ( $this->_model_id ); //�趨��������
		$task_obj->setProfit_rate ( $this->_task_config ['task_rate'] ); //��ǰ����
		$task_obj->setTask_fail_rate ( $this->_task_config ['task_fail_rate'] ); //ʧ�ܷ����ɱ�
		$task_obj->setTask_cash ( $release_info ['txt_task_cash'] ); //������
		$task_obj->setReal_cash ( $release_info ['txt_task_cash'] * (100 - $this->_task_config ['task_rate']) / 100 ); //ʵ��Ӷ��
		$task_obj->setStart_time ( time () ); //����ʼʱ��
		$time_arr = getdate ();
		$rel_time = $time_arr ['hours'] * 3600 + $time_arr ['minutes'] * 60 + $time_arr ['seconds'];
		$task_obj->setSub_time ( strtotime ( $release_info ['txt_task_day'] ) + $rel_time ); //����Ͷ����
		

		$task_obj->setEnd_time ( strtotime ( $release_info ['txt_task_day'] ) + $this->_task_config ['choose_time'] * 24 * 3600+ $rel_time); //����ѡ����
		$task_obj->setIndus_id ( $release_info ['indus_id'] ); //������ҵ
		$task_obj->setIndus_pid ( $release_info ['indus_pid'] );
		
		$tar_content = Keke::str_filter ( $release_info ['tar_content'] );
		$task_obj->setTask_desc ( $tar_content ); //��������
		$task_obj->setUid ( $this->_uid );
		$task_obj->setUsername ( $this->_username );
		/**��ֵ������¼��**/
		$att_info = array_filter ( $std_obj->_att_info ); //��ֵ����Ϣ
		

		$keys_arr = array_keys ( $att_info );
		//��ֵ�������ʱ������
		$payitem_arr [top] = 1000000000;
		$payitem_arr [urgent] = 1000000000;
		foreach ( $att_info as $k => $v ) {
			$v [item_code] == 'top' and $payitem_arr [top] = time () + 3600 * 24 * $v [item_num];
			$v [item_code] == 'urgent' and $payitem_arr [urgent] = time () + 3600 * 24 * $v [item_num];
		}
		
		$payitem_time = serialize ( $payitem_arr );
		
		$att_ids = implode ( ",", array_keys ( $att_info ) ); //��ֵ���Ŵ�
		$task_obj->setPay_item ( $att_ids );
		$task_obj->setPayitem_time ( $payitem_time );
		$task_obj->setAtt_cash ( floatval ( $std_obj->_att_cash ) ); //��ֵ����
		

		$contact = serialize ( $release_info ['cont'] ); //��ϵ��ʽ
		$task_obj->setContact ( $contact );
		$task_obj->setKf_uid ( $this->_kf_uid ); //ָ���ͷ�
		//���񸽼�
		$file_arr = array_filter ( explode ( ',', $release_info ['file_ids'] ) );
		$file_s = implode ( ',', $file_arr );
		$task_obj->setTask_file ( $file_s );
	}
	
	/**
	 * ������Ϣ�ɹ�������׷�Ӳ���
	 */
	public function update_task_info($task_id, $obj_name) {
		
		global $_K,$_lang;
		$std_obj = $this->_std_obj;
		$release_info = $std_obj->_release_info; //������Ϣ
		$att_info = $std_obj->_att_info; //��ֵ��Ϣ
		

		$user_info = $this->_user_info; //�û���Ϣ
		$task_obj = $this->_task_obj; //�������
		if ($task_id) {
			dbfactory::execute ( "update " . TABLEPRE . "witkey_space set pub_num = pub_num+1 where uid=$this->_uid " );
			//���񸽼�����
			$release_info ['file_ids'] and $this->save_task_file ( $task_id, $release_info ['txt_title'] );
			$task_status = $task_obj->getTask_status (); //����״̬
			/**��������**/
			$task_title = $task_obj->getTask_title ();
			
			switch ($task_status) {
				case "2" :
					//��������+����
					

					$this->create_task_order ( $task_id, $this->_model_id, $release_info, $att_info );
					
					$this->create_prom_event ( $task_id ); /*���񷢲��ɹ��������ƹ��¼�*/
					
					$feed_arr = array ("feed_username" => array ("content" => $this->_username, "url" => "index.php?do=space&member_id={$this->_uid}" ), "action" => array ("content" => $_lang['pub_task'], "url" => "" ), "event" => array ("content" => " $task_title", "url" => "index.php?do=task&task_id=$task_id" ) );
					Keke::save_feed ( $feed_arr, $this->_uid, $this->_username, 'pub_task', $task_id );
					//������Ϣ
					$this->notify_user ( $task_id, '2' );
					$j_step = 'step4';
					$status = '2';
					break;
				case "1" : //�������
					//��������+����
					$this->create_task_order ( $task_id, $this->_model_id, $release_info, $att_info );
					$this->create_prom_event ( $task_id ); /*���񷢲��ɹ��������ƹ��¼�*/
					/*$feed_arr = array ("feed_username" => array ("content" => $this->_username, "url" => "index.php?do=space&member_id= $this->_uid  " ), "action" => array ("content" => "���������� ", "url" => "" ), "event" => array ("content" => " $task_title", "url" => "index.php?do=task&task_id=$task_id" ) );
					Keke::save_feed ( $feed_arr, $this->_uid, $this->_username, 'pub_task', $task_id );*/
					//������Ϣ
					$this->notify_user ( $task_id, '1' );
					$j_step = 'step4';
					$status = '1';
					
					break;
				case "0" : //����
					

					$total_cash = $this->get_total_cash ( $release_info ['txt_task_cash'] );
					$pay_cash = $total_cash - ($user_info ['balance'] + $user_info ['credit']);
					$pay_cash = ceil ( $pay_cash );
					$order_id = $this->create_task_order ( $task_id, $this->_model_id, $release_info, $att_info, 'wait' );
					//������Ϣ
					$this->notify_user ( $task_id, '0' );
					//��������
					$jump_url = "index.php?do=pay&order_id=$order_id";
					$this->del_task_obj ( $obj_name ); //�������
					header ( "location:" . $jump_url );
					die ();
					break;
			}
		}
		
		$this->del_task_obj ( $obj_name ); //�������
		header ( "Location:" . $_K ['siteurl'] . "/index.php?do=release&model_id=$this->_model_id&r_step=" . $j_step . "&task_id=$task_id&status=" . $status );
	}
	
	/**
	 * �����ƹ��¼�
	 * @param $task_id ������
	 */
	public function create_prom_event($task_id) {
		global $kekezu;
		$task_obj = $this->_task_obj;
		if ($this->_model_info ['model_code'] != 'tender') {
			$this->_model_info ['model_code'] == 'dtender' and $task_cash = $task_obj->getReal_cash () or $task_cash = $task_obj->getTask_cash ();
			$kekezu->init_prom ();
			$prom_obj = Keke::$_prom_obj;
			if ($prom_obj->is_meet_requirement ( "pub_task", $task_id )) {
				$prom_obj->create_prom_event ( "pub_task", $this->_uid, $task_id, $task_cash );
			}
		}
	}
	/**
	 * ��ȡ�����������
	 * @return   void
	 */
	public abstract function get_task_config();
	/**
	 * ���񷢲�����
	 */
	public abstract function pub_task();
	
	/**
	 * ����ģʽ������Ϣ
	 * @param $std_cache_name session��
	 * @param $data �ⲿ�������
	 */
	public abstract function pub_mode_init($std_cache_name, $data = array());
	/**
	 * ���񶩵��������¼��������  ��ֵ���obj_id���ܻ�Ϊ�ա���Ϊ�������ûֱ��ͨ��
	 * @param int $task_id	������
	 * @param int $model_id ģ��ID
	 * @param array $release_info ������Ϣ
	 * @param array $att_info ��ֵ����Ϣ
	 * @param string $order_status ����״̬
	 */
	public function create_task_order($task_id, $model_id, $release_info, $att_info = array(), $order_status = 'ok') {
		global $uid, $username;
		global $_lang;
		$oder_obj = new Keke_witkey_order_class (); //������¼������
		$order_detail = new Keke_witkey_order_detail_class (); //������ϸ���¸�
		$task_cash = $release_info ['txt_task_cash']; //������
		/**��������**/
		$order_name = $release_info ['txt_title']; //��������
		$order_amount = $this->get_total_cash ( $task_cash ); //�����ܽ�
		$order_body = "��������<a href=\"index.php?do=task&task_id=$task_id\">" . $order_name . "</a>"; //������ע
		

		/**���충�����⣬��ϸ**/
		if (! empty ( $att_info )) {
			foreach ( $att_info as $k => $v ) {
				$order_name .= "#" . $v ['item_name'];
				$order_body .= $_lang ['use_payitem_service'] . $v ['item_name'] . "<br>";
			}
		}
		
		/**��������**/
		$order_id = keke_order_class::create_order ( $model_id, $uid, $username, $order_name, $order_amount, $order_body, $order_status );
		
		if ($order_id) {
			keke_order_class::create_order_detail ( $order_id, $release_info ['txt_title'], 'task', intval ( $task_id ), $task_cash );
			/** �������**/
			if ($this->_task_obj->getTask_status () != 0) {
				if (! $release_info ['trust']) { //�ǵ���ģʽ
					$this->_model_info ['model_code'] == 'tender' and $site_profit = $task_cash;
					$fina_id = keke_finance_class::cash_out ( $this->_uid, $task_cash, 'pub_task', $site_profit, 'task', $task_id ); //�������
					/** ���²����¼�Ķ�����*/
					$fina_id and keke_order_class::update_fina_order ( $fina_id, $order_id );
				
				}
				/**��ֵ�����¼����**/
				if (! empty ( $att_info )) {
					$payitem_list = keke_payitem_class::get_payitem_config (); //�ɹ������
					

					foreach ( $att_info as $k => $v ) {
						$remain = keke_payitem_class::payitem_exists ( $this->_uid, $v ['item_code'] );
						$remain > 0 or keke_payitem_class::payitem_cost ( $v ['item_code'], $v ['item_num'], $payitem_list [$v ['item_code']] ['item_type'], 'buy', $task_id, $task_id ); //��
						

						//��
						$v ['record_id'] = $pay_id = keke_payitem_class::payitem_cost ( $v ['item_code'], $v ['item_num'], $payitem_list [$v ['item_code']] ['item_type'], 'spend', $task_id, $task_id );
						
						$pay_id and dbfactory::execute ( sprintf ( " update %switkey_task set point='%s',city='%s' where task_id = '%d'", TABLEPRE, $release_info ['point'], $release_info ['province'], $task_id ) );
					
					}
				}
				if (! empty ( $att_info )) { //ѭ��������ֵ�����ϸ
					foreach ( $att_info as $v ) {
						keke_order_class::create_order_detail ( $order_id, $v ['item_name'], 'payitem', intval ( $v ['record_id'] ), $v ['item_cash'] );
					}
				}
			}
			return $order_id;
		}
	}
	/**
	 * ���ӷ��ñ���
	 * @param int $item_id      ��ֵ��������
	 * @param string $item_code ��ֵ�����
	 * @param string $item_name ��ֵ��������
	 * @param float $item_cash  ��ֵ������
	 * @param string $obj_name  ������Ϣsession ������
	 * @return void
	 */
	public function save_pay_item($item_id, $item_code, $item_name, $item_cash, $obj_name, $item_num) {
		
		$att_info = $this->_std_obj->_att_info; //��ֵ��������
		

		if ($att_info [$item_id] ['item_cash'] != $item_cash) { //������ĳ�����ֵ�ı�ʱ����
			CHARSET == 'gbk' and $item_name = Keke::utftogbk ( $item_name );
			$att_info [$item_id] ['item_code'] = $item_code;
			$att_info [$item_id] ['item_name'] = $item_name;
			$att_info [$item_id] ['item_cash'] = $item_cash;
			$att_info [$item_id] ['item_num'] = $item_num;
		}
		$this->_std_obj->_att_info = array_filter ( $att_info ); //���±�����ֵ����Ϣ
		$this->save_task_obj ( array (), $obj_name ); //���±���session
		

		$total_cash = $this->get_total_cash ( $this->_std_obj->_release_info ['txt_task_cash'] );
		Keke::echojson ( number_format ( $total_cash, 2 ), 1 );
		
		die ();
	}
	/**
	 * ��ȡ������ֵ������Ϣ 
	 *@return $att_info;
	 */
	public function get_pay_item() {
		return $this->_std_obj->_att_info; //��ֵ������Ϣ
	}
	/**
	 * �Ƴ�������	 
	 * @param $item_id  ���Ƴ���id
	 * @param $obj_name ������Ϣsession ������
	 */
	public function remove_pay_item($item_id, $obj_name) {
		$att_info = $this->_std_obj->_att_info; //��ֵ��������
		if ($att_info [$item_id]) {
			unset ( $att_info [$item_id] );
		}
		$this->_std_obj->_att_info = array_filter ( $att_info ); //���±�����ֵ������Ϣ
		$this->save_task_obj ( array (), $obj_name ); //���±�������session
		$total_cash = $this->get_total_cash ( $this->_std_obj->_release_info ['txt_task_cash'] );
		Keke::echojson ( number_format ( $total_cash, 2 ), 1 );
		die ();
	}
	/**
	 * ������ֵ������ܶ�
	 * @param array $att_info ��ֵ����������Ϣ
	 * @return float ��ֵ�����ܽ��
	 */
	public function solve_pay_item($att_info = array()) {
		$att_cash = '0';
		if (is_array ( $att_info )) {
			foreach ( $att_info as $v ) {
				$att_cash += floatval ( $v ['item_cash'] );
			}
		}
		
		return $att_cash;
	}
	/**
	 * ���浱ǰ������Ϣ
	 * @param array $release_info ���񷢲���Ϣ
	 * @param string $obj_name    �ⲿ������������session��
	 * @return   void
	 */
	public function save_task_obj($release_info = array(), $obj_name) {
		global $kekezu;
		empty ( $release_info ) or $this->_std_obj->_release_info = $release_info; //��������Ϣ�������Ա������
		$this->_std_obj->_att_cash = $this->solve_pay_item ( $this->_std_obj->_att_info ); //��ֵ�����ܶ�
		$this->_model_info ['model_code'] == 'tender' and $this->_std_obj->_release_info ['txt_task_cash'] = $this->_task_config [zb_fees];
		$_SESSION [$obj_name] = serialize ( $this->_std_obj ); //�����󱣴���session
	}
	/**
	 * ��ȡ������Ϣ
	 * @param string $obj_name    �ⲿ������������session��
	 * @return   void
	 */
	public function get_task_obj($obj_name) {
		$_SESSION [$obj_name] and $this->_std_obj = unserialize ( $_SESSION [$obj_name] );
	}
	
	/**
	 * �����������
	 * @return   void
	 */
	public function del_task_obj($obj_name) {
		if (isset ( $_SESSION [$obj_name] )) {
			unset ( $_SESSION [$obj_name] );
		}
	}
	/**
	 * ҳ�����Ȩ���ж�
	 * @param string $r_step ����ǰ����
	 * @param int $model_id ����ģ��
	 * @param array $relese_info ���񷢲���Ϣ
	 * @param int $task_id  ������ɵ�������  �����Ĳ�����
	 */
	public function check_access($r_step, $model_id, $release_info, $task_id = null, $output = 'normal') {
		global $_lang;
		switch ($r_step) {
			case "step1" :
				break;
			case "step2" : //û�н�����һ��
				$release_info ['step1'] or Keke::keke_show_msg ( "index.php?do=release&pub_mode=$this->_pub_mode&model_id=$model_id", $_lang ['you_not_choose_task_model'], "error", $output );
				break;
			case "step3" : //û�н����ڶ���
				if (! $release_info ['step2'] && ! $release_info ['step1']) { //û����ǰ2��
					Keke::keke_show_msg ( "index.php?do=release&pub_mode=$this->_pub_mode&model_id=$model_id", $_lang ['you_not_choose_task_model_and_not_in'], "error", $output );
				} elseif (! $release_info ['step2']) {
					Keke::keke_show_msg ( "index.php?do=release&pub_mode=$this->_pub_mode&model_id=$model_id&r_step=step2", $_lang ['you_not_fill_requirement_and_not_in'], "error", $output );
				}
				break;
			case "step4" : //�޷��鵽�ղŵ������¼����ҳ��10��������Ч
				$sql = sprintf ( " select task_id from %switkey_task where task_id = '%d' and start_time>%d", TABLEPRE, $task_id, time () - 600 );
				$task_info = dbfactory::get_one ( $sql );
				$task_info or Keke::keke_show_msg ( "index.php?do=release&pub_mode=$this->_pub_mode", $_lang ['the_page_timeout_notice'], "error", $output );
				return $task_info;
				break;
		}
	}
	/**
	 * �û���ϵ��ʽ
	 * @param int $contact_type ѡ����ϵ��ʽ
	 */
	public function user_contact($contact_type) {
		$user_info = $this->_user_info;
		$arr = array ();
		if ($contact_type == '2') { //ѡ�����з�ʽ�Ż���Ӧ
			$arr ['cont'] ['mobile'] = $user_info ['mobile'];
			$arr ['cont'] ['qq'] = $user_info ['qq'];
			$arr ['cont'] ['email'] = $user_info ['email'];
			$arr ['cont'] ['msn'] = $user_info ['msn'];
		}
		return $arr;
	}
	/**
	 * ת��ģʽ�������ݸ�ʽ��
	 */
	public function onekey_mode_format($data) {
		return array ("txt_title" => $data ['task_title'], "tar_content" => $data ['task_desc'], "indus_id" => $data ['indus_id'], "indus_pid" => $data ['indus_pid'], "step1" => '1' );
	}
	/**
	 * ����Ȩ���ж�
	 */
	public function check_pub_priv($url = '', $output = "normal") {
		global $_lang;
		$this->_priv ['pass'] and Keke::keke_show_msg ( $url, $_lang ['can_pub'], '', $output ) or Keke::keke_show_msg ( $url, $this->_priv ['notice'] . $_lang ['not_rights_pub_task'], "error", $output );
	}
	/**
	 * ���ݽ���ȡ�������
	 */
	public static function get_default_max_day($task_cash, $model_id, $min_day) {
		$max = Keke::get_show_day ( floatval ( $task_cash ), $model_id );
		$max >= $min_day or $max += $min_day;
		return date ( 'Y-m-d', time () + $max * 24 * 3600 );
	}
}