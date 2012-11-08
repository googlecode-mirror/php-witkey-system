<?php
/**
 * @todo ������������
 * @author H.R.
 */
require_once S_ROOT . '/client/keke/keke_tool_class.php';
require_once S_ROOT . '/client/keke/keke_service_class.php';
require_once S_ROOT . '/client/keke/config.php';

class keke_union_class {
	private $_task_id; //����id
	private $_model_id; //ģ��id
	private $_r_task_id; //����id
	private $_model_code; //ģ��code
	private $_config; //����
	private  $_data; //�ص���Ӧ����
	

	function __construct($task_id, $data = array()) {
		global $config;
		if (! empty ( $task_id )) {
			$this->_config = $config;
			$this->_task_id = intval ( $task_id );
			$this->init_task ( $task_id ); //��ʼ�����������������
		}
		$this->_data = $data;
	}
	private function init_task($task_id = '') {
		if (! $this->_task_id && $task_id) {
			$this->_task_id = $task_id;
		}
		$sql = "select `task_id`,`model_id`,`task_union`,`r_task_id` from `%switkey_task` where task_id=%d";
		$result = dbfactory::get_one ( sprintf ( $sql, TABLEPRE, $this->_task_id ) );
		if (! $result || ! $result ['task_union']) { //���������������,����.
			return false;
		}
		$this->_model_id = $result ['model_id'];
		$this->_r_task_id = $result ['r_task_id'];
		$this->_model_code = $this->get_model_code ();
	
	}
	/**
	 * ����socket����
	 * @param  $service �ӿ�����
	 * @param  $comm_data ���ݲ���
	 * @param  $return_type �������� url/form
	 * @param  $method �ύ��ʽpost
	 * @param  $sign_type ǩ������
	 * @param  $_input_charset �ַ�����
	 */
	public static function union_request($service,$comm_data= array(),$return_type = 'url', $method = 'post',$sign_type = 'MD5',$_input_charset = 'GBK'){
		global $config;
		$request = keke_tool_class::union_build($config, $service,$comm_data,$return_type,$method,$sign_type,$_input_charset);
		Keke::socket_request ( $request );
	}
	/**
	 * ������������
	 * @param int $task_id ������
	 * @param boolean $is_return �Ƿ�ص�
	 * 
	 */
	 function create_task($task_id, $is_return = false) {
		global $_K;
		switch ($is_return) {
			case false :
				global $config, $kekezu, $_K;
				$sql = "select `task_id`,`model_id`,`task_cash_coverage`,`task_cash`,`task_title`,`task_status`,`username`,`start_time`,`end_time` from %switkey_task where task_id=%d and task_union=0";
				$task_info = dbfactory::get_one ( sprintf ( $sql, TABLEPRE, intval ( $task_id ) ) );
				if (! $task_info) {
					return false;
				}
				$model_code = Keke::$_model_list [$task_info ['model_id']] ['model_code'];
				$task_info ['task_cash_coverage'] and $task_info ['cash_coveage'] = self::get_cash_cove ( $task_info ['task_cash_coverage'] );
				$class_name = $model_code. '_task_class'; //��Ӧ��class name
				$task_status_arr = call_user_func ( array ($class_name, 'get_task_union_status' ) ); //��Ӧ��״̬����
				$task_info ['task_status'] = $task_status_arr [$task_info ['task_status']];
				$task_info ['task_owner']  = $task_info['username'];
				$task_info ['outer_task_id']= "{$model_code}-{$task_id}";
				$task_info ['task_amount']  = $task_info['task_cash'];
				$inter = 'create_task'; //��Ӧ�ӿ�
				$request = keke_tool_class::union_build ( $config, $inter, $task_info );
				return $request;
				break;
			case true:
				$data  = $this->_data;
				$response = array ();
				$url = $_K ['siteurl'] . "/index.php?do=task&task_id=" . $data['task_id'];
				$response ['url'] = $url;
				switch ($data['is_success']) {
					case "T" : //�ɹ���Ӧ
						$sql = sprintf ( " update %switkey_task set r_task_id ='%d',task_union='1' where task_id='%d'", TABLEPRE, $data['r_task_id'], $data['task_id']);
						$res = dbfactory::execute ( $sql );
						$response ['type'] = "success";
						$response ['notice'] = "�������񷢲��ɹ�";
						break;
					case "F" :
						$response ['type'] = "error";
						$response ['notice'] = "�������񷢲�ʧ��";
						break;
				}
				return $response;
				break;
		}
	}
	/**
	 * ����ӿ�
	 * @param int $wrok_id
	 * @param $is_return �Ƿ�ص�
	 */
	public function work_hand($work_id, $is_return = false) {
		global $uid;
		switch ($is_return) {
			case false :
				if (! $work_id || ! $uid) {
					return false;
				}
				//���Ҷ�Ӧ��relation�Ƿ����
				$sql = "select * from %switkey_task_relation where task_id=%d and uid=%d";
				$relation_arr = dbfactory::get_one ( sprintf ( $sql, TABLEPRE, $this->_task_id, $uid ) );
				if (! $relation_arr) {
					return false;
				}
				$inter = 'hand_work'; //��Ӧ�ӿ�
				$comm_data = array ('model_code' => $this->_model_code, 'task_id' => $this->_task_id, 'r_task_id' => $this->_r_task_id, 'source_app_id' => $relation_arr ['app_id'], 'work_id' => intval ( $work_id ) );
				$url = keke_tool_class::union_build ( $this->_config, $inter, $comm_data );
				Keke::socket_request ( $url, $this->_config ['_input_charset'] );
				break;
			case true :
				$response = array ();
				$url = '';
				$response ['url'] = $url;
				switch ($this->_data ['is_success']) {
					case "T" : //�ɹ���Ӧ
						$response ['type'] = "success";
						$response ['notice'] = "�ɹ�";
						break;
					case "F" :
						$response ['type'] = "error";
						$response ['notice'] = "ʧ��";
						break;
				}
				return $response;
				break;
		}
	}
	
	/**
	 * ����б�
	 * @param string $work_status Ĭ��,����д
	 */
	public function work_choose($work_id, $to_status = '4') {
		if (! $work_id) {
			return false;
		}
		$status_arr = call_user_func ( array ($this->_model_code . '_task_class', 'get_work_union_status'));
		$inter = 'change_status'; //��Ӧ�ӿ�
		$comm_data = array ('model_code' => $this->_model_code, 'task_id' => $this->_task_id, 'r_task_id' => $this->_r_task_id, 'work_id' => intval ( $work_id ), 'work_status' => $status_arr [$to_status] );
		$url = keke_tool_class::union_build ( $this->_config, $inter, $comm_data );
		Keke::socket_request ( $url, $this->_config ['_input_charset'] ); //����״̬��ֱ�Ӵ�server�˻�ȡ��
	}
	
	/**
	 * �ı�����״̬_֪ͨ�����
	 * @param enum $status array('end','failure')
	 * @param boolean $is_return �Ƿ�ص�
	 */
	public function change_status($status = 'end', $is_return = false) {
		switch ($is_return) {
			case false :
				if (! in_array ( $status, array ('end', 'failure' ) )) {
					return false;
				}
				$inter = 'change_status'; //��Ӧ�ӿ�
				$comm_data = array ('model_code' => $this->_model_code, 'task_id' => $this->_task_id, 'r_task_id' => $this->_r_task_id, 'task_status' => $status );
				$url = keke_tool_class::union_build ( $this->_config, $inter, $comm_data );
				Keke::socket_request ( $url, $this->_config ['_input_charset'] ); //����״̬��ֱ�Ӵ�server�˻�ȡ��
				break;
			case true :
				$data = $this->_data;
				$response = array ();
				$url = '';
				$response ['url'] = $url;
				switch ($data['is_success']) {
					case "T" : //�ɹ���Ӧ
						if ($data['task_status']) {
							$status_arr = call_user_func ( array ($data['model_code'] . '_task_class', 'get_task_union_status'));
							$status_arr = array_flip ( $status_arr );
							$task_status = $status_arr [$data['task_status']];
							$res = dbfactory::execute ( sprintf ( " update %switkey_task set task_status='%d' where r_task_id ='%d'", TABLEPRE, $task_status, $data['r_task_id']) );
						}
						$response ['type'] = "success";
						$response ['notice'] = "״̬�޸ĳɹ�";
						break;
					case "F" :
						$response ['type'] = "error";
						$response ['notice'] = "ʧ��";
						break;
				}
				return $response;
				break;
		}
	}
	/**
	 * union�鿴����->��ת����Ӧ��Ŀ��ҳ��
	 * @param $r_task_id ��Ӧ����������id
	 */
	public function view_task() {
		$r_task_id = $this->_r_task_id;
		if (! $r_task_id) {
			return false;
		}
		$inter = 'save_relation';
		$comm_data = array ('r_task_id' => intval ( $r_task_id ) );
		$jump_url = keke_tool_class::union_build ( $this->_config, $inter, $comm_data );
		self::jump ( $jump_url );
	}
	
	/**
	 * ��ȡ�������
	 */
	static function get_cash_cove($rule_id) {
		//$cove = dbfactory::get_one ( sprintf ( " select start_cove,end_cove from %switkey_task_cash_cove where cash_rule_id='%d'", TABLEPRE, $rule_id ) );
		global $kekezu;
		$cove_arr = $kekezu->get_cash_cove();
		$cove = $cove_arr[$rule_id];
		return $cove ['start_cove'] . '-' . $cove ['end_cove'];
	}
	/**
	 * ��ȡ�������ϵ� �����б�(˧ѡǰ)
	 */
	static function get_task_list() {
		global $config;
		$inter = 'get_task'; //��Ӧ�ӿ�
		$config ['return_url'] = str_replace ( '&', '|', 'http://' . $_SERVER [SERVER_NAME] . $_SERVER [REQUEST_URI] );
		$request = keke_tool_class::union_build ( $config, $inter );
		self::jump ( $request );
	}

	/**
	 * model_code
	 */
	private function get_model_code() {
		global $kekezu;
		$model_arr = Keke::$_model_list;
		return $model_arr [$this->_model_id] ['model_code'];
	}
	static function jump($url) {
		header ( 'Location:' . $url );
	}
}