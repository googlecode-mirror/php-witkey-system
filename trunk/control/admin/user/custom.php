<?php	defined ( 'IN_KEKE' ) or 	exit ( 'Access Denied' );
/**
*�ͷ�����
*/
class Control_admin_user_custom extends Controller{
	function action_index(){
		global $_K,$_lang;
		
		$base_uri = BASE_URL.'/index.php/admin/user_custom';
		 
		//�б��û����û�����Ϣ
		$sql = "SELECT a.*,count(*) as num FROM `:Pwitkey_member_group` as a \n".
				"left join :Pwitkey_space as b\n".
				"on a.group_id = b.group_id \n".
				"group by a.group_id";
		$list_arr = DB::query($sql)->tablepre(':P')->execute();
		  
	 
		require keke_tpl::template('control/admin/tpl/user/custom');
	}
	//����û���
	function action_add(){
		global $_K,$_lang;
		//�����ȡ�����ݵ�uid��������ھ��Ǳ༭��û���������
		$gid = $_GET['group_id']; 
		$group_info = DB::select()->from('witkey_member_group')->where("group_id = '$gid'")->get_one()->execute();
		require keke_tpl::template('control/admin/tpl/user/custom_add');
	}
	/**
	 * �����û���Ϣ
	 */
	function action_update(){
		//��ֹsqlע��
		$_POST = Keke_tpl::chars($_POST);
		//��ֹ�����ύ
		Keke::formcheck($_POST['formhash']);
		$uid = $_POST['euid'];
		$username = $_POST['username'];
		$phone = $_POST['phone'];
		$qq = $_POST['qq'];
		$group_id = $_POST['group_id'];
		//��Ҫ���µ�����
		$array = array( 'uid'=>$uid,
				'username'=>$username,
				'phone'=>$phone,
				'qq'=>$qq,
				'group_id'=>$group_id 
				);
			Model::factory('witkey_space')->setData($array)->setWhere('uid = '.$_POST['euid'])->update();
			$this->refer();
			 
	}
	/**
	 * ��ȡ�û���Ϣ
	 */
	function action_get_user(){
		if ($_GET['guid']){
			Keke::echojson(1,1,keke_user_class::get_user_info($_GET['guid']));
			die;
		}
	}
	/**
	 * ɾ���û���
	 */
	function action_del(){
		if($_GET['uid']){
			$where .= ' and uid ='.$_GET['uid'];
		}
		echo Model::factory('witkey_space')->setWhere($where)->del();
	}
}