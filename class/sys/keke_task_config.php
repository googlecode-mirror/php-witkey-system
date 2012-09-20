<?php
/**
 * �������ÿ�����
 * @author Chen
 * 
 */
keke_lang_class::load_lang_class('keke_task_config');
class keke_task_config {
	/**
	 * ��ȡ����ʱ�����
	 * @param int $model_id ģ��ID
	 * @param int $cache_time ����ʱ��
	 */
	public static function get_time_rule($model_id, $cache_time = null) {
		return Keke::get_table_data ( "*", "witkey_task_time_rule", "model_id='$model_id'", "rule_cash", "", "", "", "", $cache_time );
	}
	/**
	 * ��ȡ�������ڹ���
	 * @param int $model_id ģ��ID
	 * @param int $cache_time ����ʱ��
	 */
	public static function get_delay_rule($model_id, $cache_time = null) {
		return Keke::get_table_data ( "*", "witkey_task_delay_rule", "model_id='$model_id'", "defer_rate", "", "", "", "", $cache_time );
	}
	/**
	 * ��������ʱ�����
	 * @param int $model_id ģ��ID
	 * @param array $timeOld ������
	 * @param array $timeNew ������
	 * @return boolean
	 */
	public static function set_time_rule($model_id, $timeOld = array(), $timeNew = array()) {
		if (is_array ( $timeOld )) {
			foreach ( $timeOld as $k => $v ) {
				$res = dbfactory::execute ( sprintf ( " update %switkey_task_time_rule set rule_day='%d',rule_cash='%s' where day_rule_id='%d' and model_id='%d'", TABLEPRE, $v ['rule_day'], $v ['rule_cash'], $k, $model_id ) );
			}
		}
		if (is_array ( $timeNew )) {
			foreach ( $timeNew as $v2 ) {
				! empty ( $v2['rule_day'] )&&!empty($v2['rule_cash']) and $res = dbfactory::execute ( sprintf ( " insert into %switkey_task_time_rule values('','%f','%d','%d')", TABLEPRE,  floatval ( $v2 ['rule_cash'] ), intval ( $v2 ['rule_day'] ),$model_id ) );
			}
		}
		return $res;
	
	}

	/**
	 * �����������ڹ���
	 * @param int $model_id ģ��ID
	 * @param array $delayOld ������
	 * @param array $delayNew ������
	 * @return boolean
	 */
	public static function set_delay_rule($model_id, $delayOld = array(), $delayNew = array()) {
		if (is_array ( $delayOld )) {
			foreach ( $delayOld as $k => $v ) {
				$res = dbfactory::execute ( sprintf ( " update %switkey_task_delay_rule set defer_rate='%d' where defer_rule_id='%d' and model_id='%d'", TABLEPRE, $v ['defer_rate'], $k, $model_id ) );
			}
		}
		if (is_array ( $delayNew )) {
			foreach ( $delayNew as $v2 ) {
				! empty ( $v2['defer_times'] )&&!empty($v2['defer_rate']) and $res = dbfactory::execute ( sprintf ( " insert into %switkey_task_delay_rule values('','%d','%s','%d')", TABLEPRE, intval ( $v2 ['defer_times'] ), intval ( $v2 ['defer_rate'] ), $model_id ) );
			}
		}
		return $res;
	}
	/**
	 * ������չ���ñ���
	 * @param int $model_id ģ��ID
	 * @param array $conf ��չ����
	 * @return boolean
	 */
	public static function set_task_ext_config($model_id, $conf = array()) {
		return dbfactory::execute ( sprintf ( " update %switkey_model set config='%s' where model_id='%d'", TABLEPRE, Keke::k_input(serialize ( $conf )), $model_id ) );
		
	}
	/**
	 * ɾ��ʱ�����
	 * @param int $rule_id ������
	 * @return boolean
	 */
	public static function del_time_rule($rule_id) {
		return dbfactory::execute ( sprintf ( " delete from %switkey_task_time_rule where day_rule_id='%d'", TABLEPRE, $rule_id ) );
	}

	/**
	 * ɾ�����ڹ���
	 * @param int $rule_id ������
	 * @return boolean
	 */
	public static function del_delay_rule($rule_id) {
		return dbfactory::execute ( sprintf ( " delete from %switkey_task_delay_rule where defer_rule_id='%d'", TABLEPRE, $rule_id ) );
	}
	/**
	 * ����task,����״̬Ϊ!('6','7','8','10','11')
	 * (2,3,4,5) ���Զ���
	 * @param int/array $task_ids
	 */
	public static function task_freeze($task_ids) {
		global $admin_info;
		global $_lang;
		if ($task_ids && is_array ( $task_ids )) {
			$ids = implode ( ',', $task_ids );
			//����Ҫ����ļ�¼,�����Ͷ���֪ͨ,����һϵͳ��־
			$sql2 = sprintf ( "select task_id,task_status,task_title,uid from %switkey_task where task_id in(%s) and task_status in (2,3,4,5)", TABLEPRE, $ids );
			$task_arr = dbfactory::query ( $sql2 );
			foreach ( $task_arr as $v ) {
				$sql3 = sprintf ( "insert into %switkey_task_frost (frost_status,task_id,frost_time,admin_uid,admin_username) 
        					values('%d','%d','%d','%d','%s')", TABLEPRE, $v ['task_status'], $v ['task_id'], time (), $admin_info ['uid'], $admin_info ['username'] );
				dbfactory::execute ( $sql3 );
				Keke::admin_system_log ( $_lang['freeze_task'].":{$v['task_title']}" );
				Keke::notify_user ( $_lang['freeze_notcie'], $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $v [task_id] . '>' . $v [task_title] . '</a>'.$_lang['has_freeze'], $v [uid] );
			}
		} elseif ($task_ids) { //��������
			$ids = $task_ids;
			$sql2 = sprintf ( "select task_id,task_status,task_title,uid from %switkey_task where task_id = %d and task_status  in (2,3,4,5)", TABLEPRE, $task_ids );
			$task_info = dbfactory::get_one($sql2);
			$sql3 = sprintf ( "insert into %switkey_task_frost (frost_status,task_id,frost_time,admin_uid,admin_username) 
        					values(%d,%d,%d,%d,'%s')", TABLEPRE, $task_info ['task_status'], $task_info ['task_id'], time (), $admin_info ['uid'], $admin_info ['username'] );
			dbfactory::execute ( $sql3 );
			Keke::admin_system_log ( $_lang['freeze_task'].":{$task_info['task_title']}" );
			Keke::notify_user ( $_lang['freeze_notcie'], $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $task_info [task_id] . '>' . $task_info [task_title] . '</a>'.$_lang['has_freeze'], $task_info [uid] );
		}
		$sql = sprintf ( "update %switkey_task set task_status = '7' where task_id in(%s) and task_status   in (2,3,4,5)", TABLEPRE, $ids );
		return dbfactory::execute ( $sql ); //ִ�ж���
	}
	/**
	 * ȡ������,��ԭ����������֮ǰ��״̬��ɾ�������¼
	 * @param int/array $task_ids
	 */
	public static function task_unfreeze($task_ids) {
		global $admin_info;
		global $_lang;
		if ($task_ids && is_array ( $task_ids )) { //�����ָ�
			$ids = implode ( ',', $task_ids );
			//Ҫ�ָ�������ɾ�� �Ķ����¼
			$sql = sprintf ( "select task_id,task_title,task_status,end_time,sub_time,uid from %switkey_task where task_status=7 and task_id in(%s)", TABLEPRE, $ids );
			$task_arr = dbfactory::query ( $sql );
			foreach ( $task_arr as $v ) {
				$sqlf = sprintf ( "select task_id,frost_status,frost_time from %switkey_task_frost", TABLEPRE, $v ['task_id'] );
				$frost_info = dbfactory::get_one ( $sqlf );
				$end_time = (time () - $frost_info ['frost_time']) + $v[end_time];
				$sub_time = (time () - $frost_info ['frost_time']) +$v[sub_time];
				$sql2 = sprintf ( "update %switkey_task set task_status = %d,end_time='%s',sub_time='%s'  where task_id = '%d'", TABLEPRE, $frost_info ['frost_status'], $end_time,$sub_time, $v ['task_id'] );
				dbfactory::execute ( $sql2 );
				dbfactory::execute ( sprintf ( "delete from %switkey_task_frost where task_id = '%d'", TABLEPRE, $frost_info ['task_id'] ) );
				Keke::admin_system_log ( $_lang['unfreeze_task'].":{$v['task_title']}" );
				Keke::notify_user ( $_lang['task_unfreeze_notice'], $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $v [task_id] . '>' . $v [task_title] . '</a>'.$_lang['has_unfreeze'], $v [uid] );
			}
		} elseif (task_ids) { //�����ָ�
			$sql = sprintf ( "select task_id,task_title,task_status,end_time,sub_time,uid from %switkey_task where task_status=7 and task_id ='%d'", TABLEPRE, $task_ids );
			$task_info = dbfactory::get_one ( $sql );
			$sqlf = sprintf ( "select task_id,frost_status,frost_time from %switkey_task_frost", TABLEPRE, $task_info ['task_id'] );
			$frost_info = dbfactory::get_one ( $sqlf );
			$end_time = (time () - $frost_info ['frost_time']) + $task_info[end_time];
			$sub_time = (time () - $frost_info ['frost_time']) +$task_info[sub_time];
			$sql2 = sprintf ( "update %switkey_task set task_status = %d,end_time='%s',sub_time='%s' where task_id = '%d'", TABLEPRE, $frost_info ['frost_status'], $end_time, $sub_time, $task_info ['task_id'] );
			
			dbfactory::execute ( $sql2 );
			dbfactory::execute ( sprintf ( "delete from %switkey_task_frost where task_id = '%d'", TABLEPRE, $frost_info ['task_id'] ) );
			Keke::admin_system_log ( $_lang['unfreeze_task'].":{$task_info['task_title']}" );
			Keke::notify_user ( $_lang['task_unfreeze_notice'], $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $task_info [task_id] . '>' . $task_info [task_title] . '</a>'.$_lang['has_unfreeze'], $task_info [uid] );
		}
		return true;
	}
	/**
	 * ����ͨ�����  task_staus = 1 �Ǵ���˵����� ������״̬��2
	 * @param int/array $task_ids
	 */
	public static function task_audit_pass($task_ids) {
		global $_lang;
		if ($task_ids && is_array ( $task_ids )) {
			$ids = implode ( ',', $task_ids );
			$task_arr = dbfactory::query ( sprintf ( "select task_id,task_title,task_status,uid,username,start_time,sub_time,end_time,payitem_time from %switkey_task where task_id in(%s) and task_status=1", TABLEPRE, $ids ) );
			foreach ( $task_arr as $v ) {
				Keke::admin_system_log ( $_lang['audit_task'].":{$v['task_title']}".$_lang['pass'] );
				Keke::notify_user ( $_lang['task_audit_notice'], $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $v [task_id] . '>' . $v [task_title] . '</a>'.$_lang['audit_pass'], $v [uid] );
				//��ֵ������ʱ�����
				$payitem_add_time =time()- $v['start_time'];//�������ӵ�ʱ��
				$payitem_arr = unserialize($v['payitem_time']);
				intval($payitem_arr[top])>0 or $top_add_time = false;
				intval($payitem_arr[urgent])>0 or $urgent_add_time = false;
				$payitem_time = keke_task_class::get_payitem($v['payitem_time'],$top_add_time,$urgent_add_time);
				
				//��������״̬
				$sub_time = time()+($v['sub_time']-$v['start_time']);
				$end_time = time()+($v['end_time']-$v['start_time']);
				$res = dbfactory::execute ( sprintf ( "update %switkey_task set task_status=2 ,start_time='%d',sub_time='%d',end_time='%d',payitem_time='%s'  where task_id in(%s)", TABLEPRE,time(),$sub_time,$end_time,$payitem_time,$v['task_id']) );
				
				$feed_arr = array ("feed_username" => array ("content" =>$v['username'], "url" => "index.php?do=space&member_id={$v['uid']}" ), "action" => array ("content" => $_lang['pub_task'], "url" => "" ), "event" => array ("content" => "{$v['task_title']}", "url" => "index.php?do=task&task_id={$v['task_id']}" ) );
				Keke::save_feed ( $feed_arr,$v['uid'],$v['username'], 'pub_task',$v['task_id']);
			}
		} elseif ($task_ids) {
				
			$ids = $task_ids;
			$task_info = dbfactory::get_one ( sprintf ( "select task_id,task_title,task_status,uid,username,start_time,sub_time,end_time,payitem_time from %switkey_task where task_id = '%d' and task_status=1", TABLEPRE, $ids ) );
			if ($task_info) {
				//��ֵ������ʱ�����
				$payitem_add_time =time()- $task_info['start_time'];//�������ӵ�ʱ�� 
				$payitem_arr = unserialize($task_info['payitem_time']);
			
				$payitem_arr['top']>1000000000 and $top_add_time = $payitem_add_time or $top_add_time=false;
				$payitem_arr['urgent']>1000000000 and $urgent_add_time = $payitem_add_time or $urgent_add_time=false;
			
				$payitem_time = keke_task_class::get_payitem($task_info['payitem_time'],$top_add_time,$urgent_add_time);
				
              
				$sub_time = time()+(intval($task_info['sub_time'])-intval($task_info['start_time']));
				$end_time = time()+($task_info['end_time']-$task_info['start_time']);
				$sql =  sprintf ( "update %switkey_task set task_status=2 ,start_time='%d',sub_time='%d',end_time='%d',payitem_time='%s'  where task_id  ='%d' ", TABLEPRE,time(),$sub_time,$end_time,$payitem_time,$task_info['task_id'] ) ;
		
				$res = dbfactory::execute ($sql);
			
				Keke::admin_system_log ( $_lang['audit_task'].":{$task_info['task_title']}".$_lang['pass'] );
				Keke::notify_user ( $_lang['task_audit_notice'], $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $task_info [task_id] . '>' . $task_info [task_title] . '</a>'.$_lang['audit_pass'], $task_info [uid] );
				
				$feed_arr = array ("feed_username" => array ("content" =>$task_info['username'], "url" => "index.php?do=space&member_id={$task_info['uid']}" ), "action" => array ("content" => $_lang['pub_task'], "url" => "" ), "event" => array ("content" => "{$task_info['task_title']}", "url" => "index.php?do=task&task_id={$task_info['task_id']}" ) );
				Keke::save_feed ( $feed_arr,$task_info['uid'],$task_info['username'], 'pub_task',$task_info['task_id']);
			}
		}
		return $res;
	}
	/**
	 * 
	 * �÷�������������Ƽ�
	 * @param int $task_id
	 */
	public static function task_recommend($task_id){
		return dbfactory::execute(sprintf("update %switkey_task set is_top=1 where task_id='%d' ",TABLEPRE,$task_id));
	}
	/**
	 * 
	 * �÷�������ȡ��������Ƽ�
	 * @param int $task_id
	 */
	public static function task_unrecommend($task_id){
		return dbfactory::execute(sprintf("update %switkey_task set is_top=0 where task_id='%d' ",TABLEPRE,$task_id));
	}
	/**
	 * ������˲�������task_staus =1 ��Ϊ  10,���ʧ��
	 * ���ʧ�ܺ������˿������
	 * @param unknown_type $task_ids
	 * @param $trust_response �����ص���Ӧ
	 */
	public static function task_audit_nopass($task_ids,$trust_response=false) {
		global $kekezu;
		global $_lang;
		if ($task_ids && is_array ( $task_ids )) {
			$ids = implode ( ',', $task_ids );
			$task_arr = dbfactory::get_one ( sprintf ( "select task_id,task_title,task_status,task_cash,uid from %switkey_task where task_id in(%s)", TABLEPRE, $ids ) );
			foreach ( $task_arr as $v ) {
				
				$res = dbfactory::execute ( sprintf ( "update %switkey_task set task_status=10 where task_id ='%d' ", TABLEPRE, $v ['task_id'] ) );
				
				keke_finance_class::cash_in ( $v ['uid'], $v ['task_cash'], 0, 'task_fail', 'admin', 'task', $v ['task_id'] );
				Keke::admin_system_log ( $_lang['audit_task'].":{$v['task_title']}".$_lang['not_pass'] );
				Keke::notify_user ( $_lang['task_audit_notice'], $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $v [task_id] . '>' . $v [task_title] . '</a>'.$_lang['audit_not_pass'], $v [uid] );
			}
		
		} elseif ($task_ids) {
			$ids = $task_ids;
			$task_info = dbfactory::get_one ( sprintf ( "select task_id,task_title,task_status,task_cash,uid,is_trust,trust_type,model_id from %switkey_task where task_id = '%d'", TABLEPRE, $ids ) );
			if ($task_info) {
				if($task_info['is_trust']&&$trust_response==false){
					$trust_data['refund'] = array($ids,$task_info['task_cash']);
					$jump_url = keke_trust_fac_class::trust_task_request("pt_refund",Keke::$_model_list[$task_info['model_id']]['model_dir'],$ids,$task_info['trust_type'],$trust_data);
					header("Location:".$jump_url);die();	
				}else{
					$res = dbfactory::execute ( sprintf ( "update %switkey_task set task_status=10 where task_id  ='%d' ", TABLEPRE, $ids ) );
					switch($task_info['is_trust']){
						case "1":
							$fina_cash = $task_info['task_cash'];
							$data = array ("uid" => $task_info ['uid'], "username" => $task_info ['username'], "obj_id" => $ids, "fina_cash" => $fina_cash, "fina_time" => time (), "fina_action" => "task_fail");
							keke_finance_class::finance_trust ( $data,$task_info['trust_type'], 'in' );
							break;
						case "0":
							keke_finance_class::cash_in ( $task_info ['uid'], $task_info ['task_cash'], 0, 'task_fail', 'admin', 'task', $task_info ['task_id'] );
							break;
					}
					Keke::admin_system_log ( $_lang['audit_task'].":{$task_info['task_title']}".$_lang['not_pass'] );
					Keke::notify_user ( $_lang['task_audit_notice'],  $_lang['you_pub_task'].':<a href=index.php?do=task&task_id=' . $task_info [task_id] . '>' . $task_info [task_title] . '</a>'.$_lang['audit_not_pass'], $task_info [uid] );
				}
			}
		}
		return $res;
	}
	/**
	 * ��������ɾ��������ɾ�������� 0,8,9,10
	 * @param $model 1=>����ģʽ��2=>�б�ģʽ�� ����ģʽ�������һ��
	 * @param int/array $task_ids
	 */
	public static function task_del($task_ids,$model=1) {
		global $_lang;
		if(is_array($task_ids)){
			//��������ɾ��
			$ids = implode ( ",", $task_ids ) or $ids = $task_ids;
			foreach ($task_ids as $v) {
				self::del_sign_task($v, $model);
			}
		}else{
			$ids = $task_ids;
			self::del_sign_task($task_ids, $model);
		}
		return dbfactory::execute ( sprintf ( "delete from %switkey_task where task_status in(0,8,9,10) and task_id in(%s)", TABLEPRE, $ids ) );
	}
	/**
	 * ɾ��������
	 * @param int $task_id
	 * @param int $model
	 */
	public static function del_sign_task($task_id,$model){
		global $_lang;
		//������ɾ��
		if($model===1){
			//���͸��
			$sql = sprintf("delete from %switkey_task_work where task_id='%d'",TABLEPRE,$task_id);
		}else{
			//�б���
			$sql = sprintf("delete from %switkey_task_bid where task_id ='%d'",TABLEPRE,$task_id);
		}
		//ɾ�����
		dbfactory::execute($sql);
		//ɾ������
		//�Ȳ�ѯ����
			
		$file_sql = sprintf("select save_name from %switkey_file where task_id = '%d' ",TABLEPRE,$task_id);
		$files = dbfactory::query($file_sql);
		//ɾ���ļ�
		foreach ($files as $v){
			keke_file_class::del_file($v['save_name']);
		}
		//ɾ���ļ���¼
		dbfactory::execute(sprintf("delete from %switkey_file where task_id ='%d' ",TABLEPRE,$task_id));
		//����ϵͳ��־
		$del_title = dbfactory::get_count(sprintf("select task_title from %switkey_task where task_id='%d'",TABLEPRE,$task_id));
		Keke::admin_system_log($_lang['delete_task'].":{$del_title}");
	}
	/***�����̨�༭����***/
	public static function can_operate($status) {
		global $_lang;
		$operate = array ();
		switch ($status) {
			case "1" : //�����
				$operate ['pass'] = $_lang['pass_audit'];
				$operate ['nopass'] = $_lang['pass_audit'];
				break;
			case "2" : //Ͷ��
			case "3" : //ѡ��
			case "4" : //ͶƱ
			case "5" : //��ʾ
			case "6" : //����
				$operate ['freeze'] = $_lang['freeze_task'];
				break;
			case "7" : //����
				$operate ['unfreeze'] = $_lang['unfreeze_task'];
		}
		return $operate;
	}
	/**
	 * ��ȡ�û��μӵ�����ͳ�ơ���ģ�� 
	 * @param int $uid
	 */
	public static function get_user_join_task($uid = '') {
		global $user_info;
		$count_arr = array ();
		$uid or $uid = $user_info ['uid']; //������Ĭ��Ϊ��ǰ�û���Ϣ
		/*����������ͳ��**/
		$reward_sql = " select count(c.task_id) count,c.model_id from (select DISTINCT a.task_id,b.model_id from %switkey_task_work a left join %switkey_task b on a.task_id=b.task_id where a.uid='%d') c  group by c.model_id";
		$reward_arr = dbfactory::query ( sprintf ( $reward_sql, TABLEPRE, TABLEPRE, $uid ), 3600 );
		/**�б�������ͳ��**/
		$tender_sql = " select count(c.task_id) count,c.model_id from (select DISTINCT a.task_id,b.model_id from %switkey_task_bid a left join %switkey_task b on a.task_id=b.task_id where a.uid='%d') c  group by c.model_id";
		$tender_arr = dbfactory::query ( sprintf ( $tender_sql, TABLEPRE, TABLEPRE, $uid ), 3600 );
		/**�ϲ�**/
		$total_arr = array_merge ( $reward_arr, $tender_arr );
		foreach ( $total_arr as $v ) {
			$count_arr [$v ['model_id']] =intval($v ['count']);
		}
		return $count_arr;
	}
	/**
	 * �����������������ԡ��ղء��ٱ�ɾ��
	 * @param $model_id ģ��ID
	 * @param $task_id ����ID ����Ϊ����
	 * @param $is_array �жϴ���ID�Ƿ�Ϊ����
	 */
	public static function delete_task_releate_item($model_id,$task_id,$is_array=false){
		global $kekezu;
			$model_code = Keke::$_model_list[$model_id]['model_code'];
			$model_code=='tender'||$model_code=='dtender' and $tab_work = "task_bid" or $tab_work='task_work';
			
	}
}