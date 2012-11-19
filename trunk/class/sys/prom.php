<?php
/**
 * �ƹ���
 * Enter description here ...
 * @author Administrator
 *
 */
Keke_lang::load_lang_class('sys_prom');
class Sys_prom {
	public $_prom_open;
	public $_prom_period;
	public $_auth_step;
	
	public static function get_instance() {
		static $obj = null;
		if ($obj == null) {
			$obj = new Sys_prom ();
		}
		return $obj;
	}
	public function __construct() {
		global $kekezu;
		$this->_prom_open = intval ( Keke::$_sys_config ['prom_open'] );//�жϺ�̨�Ƿ����ƹ�
		$this->_prom_period = intval ( Keke::$_sys_config ['prom_period'] );//�ƹ����Ч����
		$this->auth_step_init();
	}
	/**
	 * ��֤��Ч�����ʼ��
	 */
	public function auth_step_init(){
		$reg_config = $this->get_prom_rule("reg");
		$this->_auth_step = $reg_config['auth_step'];//��ȡ�ƹ�ע��ɹ�����Ҫ������֤
	
	}
	/**
	 *@��ʼ�ƹ�������� 
	 */
	public static function get_prom_rule($prom_code) {
		$p_config = dbfactory::get_one(sprintf(" select * from %switkey_prom_rule where prom_code='%s'",TABLEPRE,$prom_code));//��ȡ��Ӧ���ƹ�����
		$p_config ['config'] and $config = unserialize ( $p_config ['config'] ) or $config = array ();
		return array_merge ( $p_config, $config );
	}
	/**
	 * ���Ӳ�����ȡ
	 */
	public function url_data_format($query_string) {
		$format_data = array ();
		parse_str ( $query_string, $format_data );
		$format_data ['p'] and $format_data ['p'] = $format_data ['p'] or $format_data ['p'] = 'reg';
		$format_data ['l'] and $format_data ['l'] = $format_data ['l'] or $format_data ['l'] = 'register';
		return $format_data;
	}
	/**
	 * ��ȡ���ߵ��ƹ��ϵ
	 * (��ϵ��Ч����Ż᷵��)
	 *@param int $uid ����uid
	 *@param string $prom_type �ƹ�����
	 */
	public function get_prom_relation($uid, $prom_type) {
		$sql = " select * from %switkey_prom_relation where uid='%d' and prom_type='%s'";
		$p_relation = dbfactory::get_one ( sprintf ( $sql, TABLEPRE, $uid, $prom_type ) );
		if(!$p_relation){//����ע���ϵ
			$p_relation or $p_relation = dbfactory::get_one ( sprintf ( $sql, TABLEPRE, $uid, 'reg' ) );
			$reg_event = $this->get_prom_event($uid, $uid,$this->_auth_step);//����δ�����ע����֤�¼�
			$reg_event and $p_relation['relation_status']=4;//ע��ע���¼�δ���㡢��ϵ״̬��ΪʧЧ״̬����ֹ�����¼�����
		}
		//�жϹ�ϵ��Чʱ��
		if ($this->_prom_period&&$p_relation) { //��ʱ������
			$valid_time = time () - $p_relation ['on_time'] - $this->_prom_period * 24 * 3600;
			$valid_time >0 and $this->set_relation_status ( $p_relation ['relation_id'], 3 ); //���ù�ϵΪ����
		}
		return $p_relation;
	}
	/**
	 * ��ȡ�����ƹ��¼�
	 * @param $uid ����uid
	 * @param $action �ƹ㶯��=>reg,pub_task,realname_auth,....
	 * @param $obj_id �ƹ����ID
	 * @param $event_status �ƹ��¼�״̬ 1=>δ����  2=>�ѽ���,3=>ʧ��
	 */
	function get_prom_event($obj_id, $uid, $action, $event_status = '1') {
		$sql = " select a.*,b.relation_id from %switkey_prom_event a 
				left join %switkey_prom_relation b on a.uid=b.uid where a.obj_id='%d'
				and a.action='%s'  and a.uid='%d' and a.event_status='%d'";
		return dbfactory::get_one ( sprintf ( $sql, TABLEPRE,TABLEPRE, $obj_id, $action, $uid, $event_status ) );
	}
	/**
	 * �����ƹ���ؽ����
	 * �������ƹ��¼�����ʱ���������û��ɵ��ֽ𡢿ɵý���
	 * @param array $prom_type �ƹ��ϵ����
	 * @param int $obj_id ����ID
	 * @param float $cash �����ֽ�
	 * @param float $credit ������
	 * @return array 
	 */
	public function get_income_rule($prom_type, $obj_id, $cash = 0, $credit = 0) {
		$income_rule = array ();
		$p_config = $this->get_prom_rule ( $prom_type); //��Ӧ���͵��ƹ�����
		switch ($prom_type) {
			case "reg" : //ע��
				$auth_type = $p_config ['auth_step'];
				$auth_p_config = $this->get_prom_rule ( $auth_type ); //��Ӧ��֤���ƹ�����
				$rake_cash = $auth_p_config ['cash'];
				$rake_credit = $auth_p_config ['credit'];
				$event_desc = $p_config['prom_item']."+".$auth_p_config['prom_item'];
				$action     = $auth_p_config['prom_code'];
				break;
			case "pub_task" : //���񷢲�
			case "bid_task" : //�н�����
			case "service" : //�������
				$obj_info = $this->get_prom_obj_info ( $prom_type, $obj_id ); //������Ϣ
				$cash or $cash = $obj_info['cash'];
				//�ƹ���ɱ���
				$rate = $p_config ['rate'] * $obj_info ['profit_rate'] / 10000;
				/** �ɻ��ֽ𡢽��*/
				$rake_cash   = $cash * $rate;
				$rake_credit = $credit * $rate;
				if ($prom_type == 'pub_task') {
					if ($p_config ['pub_task_rake_type'] == 1) { //�̶����
						$rake_cash = $p_config ['cash']; //���߶���
						$rake_credit = $p_config ['credit']; //���߶���
					}else{
						$rake_cash = $cash*$p_config['rate']/100; //���߶���
						$rake_credit = 0;
					}
				}
				$event_desc = $p_config['prom_item'];
				$action     = $p_config['prom_code'];
				break;
		}
		$income_rule ['rake_cash'] = floatval ( $rake_cash );
		$income_rule ['rake_credit'] = floatval ( $rake_credit );
		$income_rule ['event_desc'] = $event_desc;
		$income_rule ['action'] = $action;
		return $income_rule;
	}
	/**
	 * �ƹ��ϵ���� 
	 *@param int $uid �ƹ�����id
	 *@param string ��������
	 *@param $url_data ���ƹ����ӻ�ȡ�Ĳ���
	 *@param $relation_status ��ϵ״̬ Ĭ��Ϊδ��Ч
	 */
	function create_prom_relation($uid, $username, $url_data, $relation_status = 1) {
		global $_lang;
		$relate_obj =  new Keke_witkey_prom_relation ();
		if ($this->_prom_open) {
			if ($url_data ['uid'] == $uid) { //�޷��ƹ��Լ�
				Keke::notify_user ( $_lang['prom_fail'], $_lang['you_can_not_prom_self'], $url_data ['u'] );
				return false;
			} else {
				$prom_relation = $this->get_prom_relation ( $uid, $url_data ['p'] ); //��ȡ�������ƹ��ϵ
				$r_status      = intval($prom_relation['relation_status']);
				$r_status==3||$r_status==0 and $p_status =1 or $p_status=2;//û��ʧЧ����ֹ
				if ($p_status==2) { //�����ƹ��ϵ
					Keke::notify_user ( $_lang['prom_fail'], $_lang['your_prom_user_has_promer'], $url_data ['u'] );
				} else {
					$p_info = Keke::get_user_info ( $url_data ['u'] ); //�����û���Ϣ
					$relate_obj->setUid ( $uid );
					$relate_obj->setUsername ( $username );
					$relate_obj->setProm_uid ( $p_info ['uid'] );
					$relate_obj->setProm_username ( $p_info ['username'] );
					$relate_obj->setProm_type ( $url_data ['p'] );
					$relate_obj->setRelation_status ( intval ( $relation_status ) );
					$relate_obj->setOn_time ( time () );
					return $relate_obj->create ();
				}
			}
		} else {
			Keke::notify_user ( $_lang['prom_fail'], $_lang['prom_system_closed'], $url_data ['u'] );
			return false;
		}
	}
	/**
	 * �ƹ��¼�����
	 * @param $action �ƹ㶯�� reg,pub_task....
	 * @param $uid ����id
	 * @param $obj_id �ƹ������
	 * @param float $cash �����ֽ�
	 * @param float $credit ������
	 * @param $event_status �¼�״̬  1=>δ���,2=>�ѽ���,3=>�˴��¼�ʧ�ܡ�
	 * @return boolen 
	 */
	function create_prom_event($action, $uid, $obj_id, $cash = 0, $credit = 0, $event_status = '1') {
		$result = FALSE;
		if ($this->_prom_open) {
			$prom_relation = $this->get_prom_relation ( $uid, $action ); //��ȡ�����ƹ��ϵ
			$r_status      = intval($prom_relation['relation_status']);
			if ($prom_relation&&$r_status!=3&&$r_status!=4 && $prom_relation ['prom_uid'] != $uid) { //��ϵδʧЧ�����߲����Լ�
				/**�¼��������ơ�����uid���ڴ�prom_type,$obj_id�����´���δ����¼�ʱ����ֹ�¼�����**/
				if (! $this->get_prom_event ( $obj_id, $uid, $action, $event_status )) {
					//���ݹ����ȡ�������
					$income_rule = $this->get_income_rule ( $action, $obj_id, $cash, $credit );
					//�����ƹ��¼�
					$event_obj = new Keke_witkey_prom_event ();
					$event_obj->setEvent_desc ( $income_rule ['event_desc'] );
					$event_obj->setUid ( $uid );
					$event_obj->setUsername ( $prom_relation ['username'] );
					$event_obj->setParent_uid ( $prom_relation ['prom_uid'] );
					$event_obj->setParent_username ( $prom_relation ['prom_username'] );
					$event_obj->setObj_id ( $obj_id );
					$event_obj->setAction($income_rule ['action']);
					$event_obj->setRake_cash ( $income_rule ['rake_cash'] );
					$event_obj->setRake_credit ( $income_rule ['rake_credit'] );
					$event_obj->setEvent_time ( time () );
					$event_obj->setEvent_status ( intval ( $event_status ) );
					$result = $event_obj->create();
					//���cookie
					$this->clear_prom_cookie ();
				}
			}
		}
		return $result;
	}
	/**
	 * �¼�����
	 * @param $action �ƹ㶯��.
	 * @param $uid ����id
	 * @param $obj_id �ƹ��¼�id
	 * @return boolen
	 */
	function dispose_prom_event($action, $uid, $obj_id) {
		$p_relation = $this->get_prom_relation ( $uid, $action );
		if ($p_relation&&$p_relation ['realtion_status'] !=3) { //��ϵδʧЧ
			$prom_event = $this->get_prom_event ( $obj_id, $uid, $action ); //��ȡ�ƹ��¼�
		}
		if ($prom_event) {
			Sys_finance::cash_in ( $prom_event ['parent_uid'], $prom_event ['rake_cash'], $prom_event ['rake_credit'], "prom_" .$action);
			$p_relation ['relation_status'] == '1' and $this->set_relation_status ( $p_relation ['relation_id'], '2' ); //δ��Ч������Ϊ��Ч
			return $this->set_prom_event_status ( $prom_event ['parent_uid'], $prom_event ['username'], $prom_event ['event_id'], 2 );
		}
	}
	/**
	 * �����ƹ��ϵ״̬
	 * @param $relation_id ��ϵid
	 * @param $status ���״̬
	 */
	function set_relation_status($relation_id, $status) {
		return dbfactory::execute ( " update " . TABLEPRE . "witkey_prom_relation set relation_status ='$status' where relation_id ='$relation_id'" );
	}
	/**
	 * �����¼�״̬
	 * @param $p_uid����UID
	 * @param $username �����û���
	 * @param $event_id �¼�id
	 * @param $status �¼����״̬
	 */
	function set_prom_event_status($p_uid, $username, $event_id, $status) {
		global $_lang;
		$res = dbfactory::execute ( " update " . TABLEPRE . "witkey_prom_event set event_status = '$status' where event_id= '$event_id'" );
		if ($res) {
			if ($status == 2) {
				$title = $_lang['prom_msg_notice'];
				$content = $_lang['you_prom_offline'] . $username . $_lang['complete_event_get_money_notice'];
			} elseif ($status == 3) {
				$title = $_lang['prom_msg_notice'];
				$content = $_lang['you_prom_offline'] . $username . $_lang['event_fail_notice'];
			}
			$title && $content and Keke::notify_user ( $title, $content, $p_uid );
		}
	}
	/**
	 * �ƹ������ѯ
	 */
	function prom_income_rank() {
		return Keke::get_table_data ( " uid,username,sum(fina_cash) cash,sum(fina_credit) credit", "witkey_finance", " INSTR(fina_action,'prom_')", "", "uid", "", "uid", 3600 );
	}
	/**
	 * �����ƹ�cookie
	 * ���������㡣����¼ʱ�Ĺ�ϵ����
	 * @param $query_string ���� ����
	 */
	function create_prom_cookie($query_string) {
		global $uid, $username;
		global $_lang;
		$url_data = $this->url_data_format ( $query_string ); 
		if ($uid) { //��¼����²����ƹ��ϵ
			if ($url_data ['u'] != $uid && $url_data ['p']) {
				if ($this->get_prom_relation ( $uid, $url_data ['p'] )) { //��������
					/** ֪ͨ�û�*/
					Keke::notify_user ( $_lang['prom_fail'], $_lang['from_you_prom_website_user'] . "��".$username."��" . $_lang['already_exist_prom_promotion_fail'], $url_data ['u'] );
				} else {
					$this->create_prom_relation ( $uid, $username, $url_data,2 );
				}
			}
		} else { //��¼�ƹ��COOKIE
			setcookie ( "user_prom_event", serialize ( $url_data ), time () + 24 * 3600, COOKIE_PATH, COOKIE_DOMAIN );
		}
		$this->prom_jump ( $url_data ); //�ض�����ָ��ҳ��
	}
	/**
	 * �������Ƿ�����ƹ�����
	 */
	public function is_meet_requirement($prom_code, $obj_id) {
		$result = TRUE;
		$obj_info = self::get_prom_obj_info ($prom_code,$obj_id ); //������Ϣ
		if ($obj_info) {
			$prom_config = dbfactory::get_one ( sprintf ( " select * from %switkey_prom_rule where prom_code='%s'", TABLEPRE, $prom_code ) );
			$prom_config = unserialize ( $prom_config ['config'] );
			if ($prom_config ['indus_string']&&FALSE === strpos ( $prom_config ['indus_string'], $obj_info ['indus_id'] )) {
				$result = FALSE;
			}
			if ($prom_config ['model'] && FALSE === strpos ( $prom_config ['model'], $obj_info ['model_id'] )) {
				$result = FALSE;
			}
		}
		return $result;
	}
	/**
	 * ��ȡ�ƹ������Ϣ
	 * @param string $prom_type �ƹ�����
	 * @param int $obj_id ������	
	 */
	public static function get_prom_obj_info($prom_type, $obj_id) {
		if ($prom_type == 'pub_task' || $prom_type == 'bid_task') {
			$obj_info = dbfactory::get_one ( sprintf ( " select model_id,indus_id,profit_rate,task_cash cash from %switkey_task where task_id='%d'", TABLEPRE, $obj_id ) );
		} elseif ($prom_type == 'service') {
			$obj_info = dbfactory::get_one ( sprintf ( " select model_id,indus_id,profit_rate,price cash from %switkey_service where service_id='%d'", TABLEPRE, $obj_id ) );
		}
		return $obj_info;
	}
	/**
	 * �������ƹ�cookie
	 */
	function extract_prom_cookie() {
		isset ( $_COOKIE ['user_prom_event'] ) and $url_data = unserialize ( stripslashes ( $_COOKIE ['user_prom_event'] ) );
		return $url_data;
	}
	/**
	 * �����ƹ�cookie
	 */
	static function clear_prom_cookie() {
		if (isset ( $_COOKIE ['user_prom_event'] )) {
			setcookie ( 'user_prom_event', '', - 9999 );
			unset ( $_COOKIE ['user_prom_event'] );
		}
	}
	/**
	 * �ƹ���ת
	 * @param $url_data  ���Ӳ���
	 */
	function prom_jump($url_data) {
		global $_K;
		if (isset ( $url_data ['u'] ) && $url_data ['l']) {
			if ($url_data ['o']) {
				$url_data ['l']=='service' and $j = "&sid=" or $j = "&task_id=";
				header ( "Location:" . $_K ['siteurl'] . "/index.php?do=" . $url_data ['l'] .$j. $url_data ['o'] );
			} else {
				header ( "Location:" . $_K ['siteurl'] . "/index.php?do=" . $url_data ['l'] );
			}
		}
	}
	/**
	 * ��ȡ�ƹ��ϵ״̬
	 */
	public static function get_prelation_status(){
		global $_lang;
		return array("1"=>$_lang['not_take_effect'],"2"=>$_lang['has_task_effect'],"3"=>$_lang['has_over_time']);
	}
	/**
	 * ��ȡ�ƹ��¼�״̬
	 */
	public static function get_pevent_status(){
		global $_lang;
		return array("1"=>$_lang['not_settlement'],"2"=>$_lang['has_settlement'],"3"=>$_lang['has_fail']);
	}
	/**
	 * ��ȡ�ƹ�����
	 */
	public static function get_prom_type(){
		return Keke::get_table_data("prom_code,prom_item,type","witkey_prom_rule","","","","","prom_code",3600);
	}
}