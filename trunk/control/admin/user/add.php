<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * �û����
 */
class Control_admin_user_add extends Controller{
	function action_index(){
		global $_K,$_lang;
		$uid = $_GET['uid'];
		if ($uid){
			$where .= ' uid='.$uid;
			//��ȡ�û���Ϣ
			$edit_arr = Keke_user_class::get_user_info($uid);
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
			$res =  Keke_user_class::check_username ( $check_username );
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
		$array = array('username'=>$_POST[username],
				'password'=>$password,
				'email'=>$_POST['email'],
				);
		$array1 = array('username'=>$_POST['username'],
				'group_id'=>$_POST['group_id'],
		);
		//����uid���±����ݣ�û�����������
		if($_GET['hid_uid']){
			Model::factory('witkey_member')->setData($array)->update();
			Model::factory('witkey_space')->setData($array1)->update();
			Keke::show_msg("�ύ�ɹ�","admin/user_add?uid=".$_GET['hid_uid'],"success");
		}else {
			Model::factory('witkey_member')->setData($array)->create();
			Model::factory('witkey_space')->setData($array1)->create();
			Keke::show_msg("�ύ�ɹ�","admin/user_add".$_GET['hid_uid'],"success");
		}
	}
	function action_charge(){
		global $_K,$_lang;
		Keke::formcheck($_POST['formhash']);
		if($_POST['user']){
			CHARSET=='gbk' and $user = Keke::utftogbk($_POST['user']);
			function get_info($uid){
				$sql = " select balance,credit,uid from %switkey_space where ";
				if(is_numeric($uid)){
					$sql.=" uid='%d'";
				} else{
					$sql.=" username='%s'";
				}
				return  Dbfactory::get_one(sprintf($sql,TABLEPRE,$uid));
			}
			$info = get_info($user);
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
			//���ɲ����¼
			
			
			
			//��ֵ�Ľ���Ԫ����Ϊ0���߿յ�ʱ������
			if(!$_POST['cash']&&!$_POST['credit']){
				Keke::show_msg("��ֵ���߿۳�����Ϊ0���߿�!!!","admin/user_add/charge","warning");
			}
			 
		}
		require Keke_tpl::template('control/admin/tpl/user/add_charge');
	}
	function action_check(){
		global $_K,$_lang;
		if($_GET['check_uid']){
			//�Դ�������check_uid����ת��
			CHARSET=='gbk' and $check_uid = Keke::utftogbk($_GET['check_uid']);
			function get_info($uid){
				$sql = " select balance,credit,uid from %switkey_space where ";
				if(is_numeric($uid)){
					$sql.=" uid='%d'";
				} else 
					{
						$sql.=" username='%s'";
					}
				return  dbfactory::get_one(sprintf($sql,TABLEPRE,$uid));
			}
			$info = get_info($check_uid);
			$info and Keke::echojson('',1,$info) or Keke::echojson($_lang['none_exists_uid_or_username'],0);
			die();
		}
	}
}
