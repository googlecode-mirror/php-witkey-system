<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ֧������
 * @author Michael	
 * @version v 2.2
 * 2012-10-01
 */
class Control_admin_config_pay extends Control_admin{
	/**
	 * ֧������
	 */
	function action_index(){
		global $_lang;
		//���ύʱ
		if(!$_POST){
			//��ȡҪ�༭������
			$res = DB::select('k,v')->from('witkey_pay_config')->execute();
			//��ֵ����
			$pay_config = Arr::get_arr_by_key($res,'k');
			//����֧������ģ��
			require Keke_tpl::template('control/admin/tpl/config/pay');
			die;
		}
		//����ȫ���
		Keke::formcheck($_POST['formhash']);
		//ȥ��formhash
		unset($_POST['formhash']);
		//��������
		foreach ($_POST as $k=>$v){
			DB::update('witkey_pay_config')->set(array('v'))->value(array($v))
			->where("k = '$k'")->execute();
		}
		//��ת
		Keke::show_msg($_lang['submit_success'],'admin/config_pay','success');
	}
	 
	/**
	 * ����֧��
	 */
	function action_online(){
		global $_K,$_lang;
		$payment_list = DB::select()->from('witkey_pay_api')->where("type='online'")->execute();
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_online');
	}
	/**
	 * ����֧���б� 
	 */
	function action_offline(){
		global $_K,$_lang;
		//����
		$where = "type='offline'";
		//���������б�
		$payment_list = DB::select()->from('witkey_pay_api')->where($where)->execute();
		//��������
		$bank_arr = Keke_global::get_bank();
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_offline');
	}
	/**
	 * ����֧����ӱ༭
	 */
	function action_offline_add(){
		global $_K,$_lang;
		if($_GET['pay_id']){
			$payment_config = self::get_pay_config($_GET['pay_id']);
			
		}
		$bank_arr   = Keke_global::get_bank();
	 
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_offline_add');
	}
	
	/**
	 * ����֧�����ݱ���
	 */
	function action_offline_save(){
		global $_K,$_lang;
		//����ȫ���
		Keke::formcheck($_POST['formhash']);
		$array = array('pay_name'=>$_POST['pay_name'],
						'payment'=>$_POST['payment'],
						'status'=>$_POST['status'],
						'pay_user'=>$_POST['pay_user'],
						'pay_tel'=>$_POST['pay_phone'],
						'pay_account'=>$_POST['pay_account'],
						'type'=>'offline');
		if($_POST['hdn_pay_id']){
			$where = "pay_id = '{$_POST['hdn_pay_id']}'";
			Model::factory('witkey_pay_api')->setData($array)->setWhere($where)->update();
			$url = "?pay_id={$_POST['hdn_pay_id']}";
		}else{
			Model::factory('witkey_pay_api')->setData($array)->create();
		}
		Keke::show_msg($_lang['submit_success'],'admin/config_pay/offline_add'.$url,'success');
	}
	
	/**
	 * �ı�֧���ӿڵ�״̬ 
	 * @example 0 ���� 1 ����
	 */
	function action_change_status(){
		global $_lang;
		//״̬
		$status = $_GET['status'];
		//����
		$pay_id = $_GET['pay_id'];
		//Ĭ��Ϊ���߽ӿ�
		$type = $_GET['type']?$_GET['type']:'online';
		//�ı�״̬
		DB::update('witkey_pay_api')->set(array('status'))->value(array($status))
		->where("pay_id='$pay_id'")->execute();
		Keke::show_msg($_lang['submit_success'],'admin/config_pay/'.$type,'success');
	}
	/**
	 * ���Ͻӿڱ༭
	 */
	function action_add(){
		global $_K,$_lang;
		if($_GET['pay_id']){
			$payment_config = self::get_pay_config($_GET['pay_id']);
			//֧��������Ҳ����Ŀ¼
			$dir = $payment_config['payment'];
			$pay_basic = array();
			//��ʼ����������
			include S_ROOT.'payment/'.$dir.'/config.php';
			//��ʼ���������� $pay_basic ��config.php �е�����
			$init_param = $pay_basic ['initparam'];
			$items = array();
			foreach (explode(';', $init_param) as $v){
				$it = explode ( ":", $v );
				//k Ϊ��,VΪֵ��ֵ�����л������������ݿ���config�ֶ�,����ΪʲôҪ������������Ϊÿ������֧���ӿڵĲ�������һ��
				$items [] = array ('k' => $it ['0'], 'name' => $it ['1'], 'v' => $payment_config [$it ['0']] );
			}
		}
		 
		//����֧������ģ��
		require Keke_tpl::template('control/admin/tpl/config/pay_add');
	}
	
	
	static function get_pay_config($pay_id){
		//��ѯ����
		$where = "pay_id = '".intval($pay_id)."'";
		//ִ�в�ѯ
		return  DB::select()->from('witkey_pay_api')->where($where)->get_one()->execute();
		
	}
	/**
	 * ���߽ӿڵ����ñ���
	 */
	function action_online_save(){
		global  $_lang;
		//form ��ȫ���
		Keke::formcheck($_POST['formhash']);
		//����ִֻֻ��update
		if($_POST['hdn_pay_id']){
			//Ҫ�����ֶ�
			$columns= array_keys($_POST['fds']);
			//���µ�ֵ
			$values =array_values($_POST['fds']);
			//ִ������
			$where = "pay_id='{$_POST['hdn_pay_id']}'";
			//��ʼִ��
			DB::update('witkey_pay_api')->set($columns)->value($values)->where($where)->execute();
			
			Keke::show_msg($_lang['submit_success'],"admin/config_pay/add?pay_id={$_POST['hdn_pay_id']}",'success');
		} 
		
	}
	/**
	 * ���½ӿ�ɾ��
	 */
	function action_del(){
		$pay_id = $_GET['pay_id'];
		//ɾ�������ᶨ�Ľӿ�
		echo DB::delete('witkey_pay_api')->where("pay_id = '$pay_id' and type='offline'")->execute();
	}
	
}