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
		$menu_arr = keke_admin_class::get_admin_menu();
// 		var_dump($menu_arr);
		//��������
		$list_arr = keke_admin_class::get_user_group();
		$membergroup_obj = new Keke_witkey_member_group();
		if($_POST['is_submit']){
			$groupinfo_arr = $membergroup_obj->query ();
			$groupinfo_arr = $groupinfo_arr ['0'];
		}
		$grouprole_arr = array();
		$grouprole_arr = explode ( ',', $groupinfo_arr ['group_roles'] );
// 		var_dump($grouprole_arr);die;
		require keke_tpl::template("control/admin/tpl/user/group_add");
	}
}


/* Keke::admin_check_role ( 13 );

$menuset_arr = keke_admin_class::get_admin_menu ();
$membergroup_obj = new Keke_witkey_member_group_class ();

//�б�ģʽ
$grouplist_arr = $membergroup_obj->query_keke_witkey_member_group ();
//���-�༭ģʽ

if ($op == 'del') {
	$editgid = $editgid ? $editgid : Keke::admin_show_msg ( $_lang['param_error'], "index.php?do=user&view=back&type=group",3,'','warning');
	$membergroup_obj->setWhere ( "group_id='{$editgid}'" );
	$membergroup_obj->del_keke_witkey_member_group ();
	Keke::admin_system_log ( $_lang['delete_user_group']."$groupinfo_arr[groupname]" );
	Keke::admin_show_msg ( $_lang['operate_success'], "index.php?do=user&view=group_list", 3 ,'','success');
}


require $template_obj->template ( 'control/admin/tpl/admin_user_group_list' );
 */
 
