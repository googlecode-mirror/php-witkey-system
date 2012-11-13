<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * �û����
 */
class Control_admin_user_add extends Control_admin{
	function action_index(){
		global $_K,$_lang;
		$uid = $_GET['uid'];
		if ($uid){
			$where .= ' uid='.$uid;
			//��ȡ�û���Ϣ
			$edit_arr = Keke_user::instance()->get_user_info($uid);
			//��ѯshop���shop�����Ƽ�
			$shop_open = DB::select('shop_id')->from('witkey_shop')->where($where)->get_count()->execute();
		}
		//��ѯgroup������ѡ���û���
		$member_arr = DB::select()->from('witkey_member_group')->execute();
		require Keke_tpl::template('control/admin/tpl/user/add');
	}
	/**
	 * ��֤�û���
	 */
	function action_checkusername(){
		$check_username = $_GET['check_username'];
		if (isset ( $check_username ) && ! empty ( $check_username )) {
			$res =  Keke_user_register::instance()->check_username ( $check_username );
			CHARSET == 'gbk' and $res = Keke::gbktoutf(Keke_user_register::$_status[$res]);
			echo  $res;
// 			die ();
		}
	}
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		//��ֹ�����ύ
		Keke::formcheck($_POST['formhash']);
		
		//����md5����
		$password = md5($_POST['password']);
		//��Ҫ�������ݿ���ֶ�
		$member = array('username'=>$_POST[username],
				'password'=>$password,
				);
		$space = array('username'=>$_POST['username'],
				'email'=>$_POST['email'],
				'group_id'=>$_POST['group_id'],
		);
		//����uid���±����ݣ�û�����������
		if($_GET['hid_uid']){
			Model::factory('witkey_member')->setData($member)->update();
			Model::factory('witkey_space')->setData($space)->update();
			Keke::show_msg("�ύ�ɹ�","admin/user_add?uid=".$_GET['hid_uid'],"success");
		}else {
			$uid = Model::factory('witkey_member')->setData($member)->create();
			$space['uid'] = $uid;
			Model::factory('witkey_space')->setData($space)->create();
			Keke::show_msg("�ύ�ɹ�","admin/user_add".$_GET['hid_uid'],"success");
		}
	}
	function action_charge(){
		global $_K,$_lang;
		Keke::formcheck($_POST['formhash']);
		if($_POST['user']){
			//��ֵ�Ľ���Ԫ����Ϊ0���߿յ�ʱ������
			if(!$_POST['cash']&&!$_POST['credit']){
				Keke::show_msg("��ֵ���߿۳�����Ϊ0���߿�!!!","admin/user_add/charge","warning");
			}
			$fina_obj = new Keke_witkey_finance;
			
			CHARSET=='gbk' and $user = Keke::utftogbk($_POST['user']);
			$info = $this->get_info($user);
			//�ֽ�ĳ�ֵ�Ϳ۳�
			if ($_POST['cash_type']==0&&$_POST['cash']>$info['balance']){
				Keke::show_msg("�۳����ֽ𳬳��˿����ֽ�!!!","admin/user_add/charge","warning");
			}elseif($_POST['cash_type']==1){
				$info['balance']+=$_POST['cash'];
				$array = array('balance'=>$info['balance']);
				Model::factory('witkey_space')->setData($array)->setWhere('uid= '.$info['uid'])->update();
			}else{
				$info['balance']-=$_POST['cash'];
				$array = array('balance'=>$info['balance']);
				Model::factory('witkey_space')->setData($array)->setWhere('uid= '.$info['uid'])->update();
			}
			$fina_obj->setFina_type('in');
			$fina_obj->setFina_action('admin_charge');
			$fina_obj->setFina_cash($_POST['cash']);
			$fina_obj->setUser_balance($info['balance']);
			$fina_obj->setUid($info['uid']);
			$fina_obj->setUsername($info['username']);
			
			//Ԫ���ĳ�ֵ�Ϳ۳�
			if ($_POST['credit_type']==0&&$_POST['credit']>$info['credit']){
				Keke::show_msg("�۳���Ԫ�������˿���Ԫ��!!!","admin/user_add/charge","warning");
			}elseif ($_POST['credit_type']==1){
				$info['credit']+=$_POST['credit'];
				$array = array('credit'=>$info['credit']);
				Model::factory('witkey_space')->setData($array)->setWhere('uid= '.$info['uid'])->update();
			}else{
				$info['credit']-=$_POST['credit'];
				$array = array('credit'=>$info['credit']);
				Model::factory('witkey_space')->setData($array)->setWhere('uid= '.$info['uid'])->update();
			}
			$fina_obj->setFina_credit($_POST['credit']);
			$fina_obj->setUser_credit($info['credit']);
			$fina_obj->setFina_mem("ϵͳ����Ա����".$_POST['charge_reason']);
			
			//���ɲ����¼
			$fina_obj->create();
		}
		require Keke_tpl::template('control/admin/tpl/user/add_charge');
	}
	function action_check(){
		global $_K,$_lang;
		if($_GET['check_uid']){
			//�Դ�������check_uid����ת��
			CHARSET=='gbk' and $check_uid = Keke::utftogbk($_GET['check_uid']);
			$info = $this->get_info($check_uid);
			$info and Keke::echojson('',1,$info) or Keke::echojson($_lang['none_exists_uid_or_username'],0);
			die();
		}
	}
	/**
	 * ��ȡ�û���
	 * @param int $uid
	 * @return array
	 */
	function get_info($uid){
		$sql = " select balance,credit,uid,username from %switkey_space where ";
		if(is_numeric($uid)){
			$sql.=" uid='%d'";
		} else 
			{
				$sql.=" username='%s'";
			}
		return  Dbfactory::get_one(sprintf($sql,TABLEPRE,$uid));
	}
}
