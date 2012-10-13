<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
class Control_admin_user_group extends Controller{
	function action_index(){
		//����ȫ�ֱ��������԰�
		global $_K,$_lang;
		//��Ҫ��ʾ���ֶ�
		$list_arr = DB::select()->from('witkey_member_group')->where('1=1')->execute();
		//ҳ��uri
		$base_uri = BASE_URL.'/index.php/admin/user_group';
		//�༭uri
		$edit_uri = $base_uri.'/edit';
		//ɾ��uri
		$del_uri = $base_uri.'/del';
		require keke_tpl::template("control/admin/tpl/user/group");
	}
	function action_add(){
		global $_K,$_lang;
		//һ������
		$menus_arr = keke_admin_class::get_admin_menu();
 
		//Ȩ���мӺڲ������԰�
		$menu_arr = array (
				'config' => $_lang['global_config'],
				'article' => $_lang['article_manage'],
				'task' => $_lang['task_manage'],
				'shop' => $_lang['shop_manage'],
				'finance' => $_lang['finance_manage'],
				'user' => $_lang['user_manage'],
				'tool' => $_lang['system_tool'],
				'demo'=>'MVC��ʾ',
		);
		//��ȡgroup_id�������ж��Ǳ༭
		$group_id=$_GET['group_id'];
		if ($group_id){
			$groupinfo_arr = DB::select()->from('witkey_member_group')->where('group_id ='. $group_id)->execute();
			$groupinfo_arr = $groupinfo_arr[0];
		}
// 		var_dump($groupinfo_arr);die;
        //��ѡ��ѡ���ַ����ֽ�Ϊ����
		$grouprole_arr = explode ( ',', $groupinfo_arr ['group_roles'] );
		require keke_tpl::template("control/admin/tpl/user/group_add");
	}
	function action_save(){
		//��ֹsqlע�룬
		$_POST = Keke_tpl::chars($_POST);
		//��ֹ�����ύ
		keke::formcheck($_POST['formhash']);
		//��ȡѡ�еĶ�ѡ������group_id��Ϊ����
		$group_roles = $_POST['chb_resource'];
		//������ת��Ϊ�ַ���
		if($group_roles!=''){
		$group_roles = implode(",", $group_roles);
		}
		if($_POST['txt_groupname']){
			$txt_groupname = $_POST['txt_groupname'];
		}else{
			Keke::show_msg('��������Ϊ�գ���','admin/user_group/add','warning');
		}
		//��Ҫ���д洢���ֶ�
		$array = array('group_id'=>$_POST['group_id'],
				'groupname'=>$txt_groupname,
				'group_roles'=>$group_roles,
				'on_time'=>time()
				);
		if ($_POST['is_submit']){
			//�༭������ύ������
			Model::factory('witkey_member_group')->setData($array)->setWhere('group_id = '.$_POST['is_submit'])->update();
			Keke::show_msg('�༭�ɹ�','admin/user_group/add?group_id='.$_POST['is_submit'],'success');
		}else{
			//���������ύ��ֱ�Ӳ���
			Model::factory('witkey_member_group')->setData($array)->create();
			Keke::show_msg('�ύ�ɹ�','admin/user_group/add','success');
		}
	}
	/*
	 * ͨ��group_id��ɾ����Ҫɾ����һ�����ݣ��޶���ɾ��
	 *  @group_id int
	 **/
	function action_del(){
		if($_GET['group_id']){
			$where = 'group_id ='.$_GET['group_id'];
		}
		echo Model::factory('witkey_member_group')->setWhere($where)->del();
	}
}

