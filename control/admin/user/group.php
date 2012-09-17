<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
class Control_admin_user_group extends Controller{
	function action_index(){
		//加载全局变量和语言包
		global $_K,$_lang;
		//需要显示的字段
		$list_arr = DB::select()->from('witkey_member_group')->where('1=1')->execute();
		//页面uri
		$base_uri = BASE_URL.'/index.php/admin/user_group';
		//编辑uri
		$edit_uri = $base_uri.'/edit';
		//删除uri
		$del_uri = $base_uri.'/del';
		require keke_tpl::template("control/admin/tpl/user/group");
	}
	function action_add(){
		global $_K,$_lang;
		//一级标题
		$menus_arr = keke_admin_class::get_admin_menu();
		//权限中加黑部分语言包
		$menu_arr = array (
				'config' => $_lang['global_config'],
				'article' => $_lang['article_manage'],
				'task' => $_lang['task_manage'],
				'shop' => $_lang['shop_manage'],
				'finance' => $_lang['finance_manage'],
				'user' => $_lang['user_manage'],
				'tool' => $_lang['system_tool'],
				'demo'=>'MVC演示',
		);
		$group_id=$_GET['group_id'];
		if ($group_id){
			$groupinfo_arr = DB::select()->from('witkey_member_group')->where('group_id ='. $group_id)->execute();
			$groupinfo_arr = $groupinfo_arr[0];
		}
// 		var_dump($groupinfo_arr);die;
		$grouprole_arr = explode ( ',', $groupinfo_arr ['group_roles'] );
		require keke_tpl::template("control/admin/tpl/user/group_add");
	}
	function action_save(){
		//防止跨域提交
		keke::formcheck($_POST['formhash']);
		$group_roles = $_POST['chb_resource'];
		$group_roles = implode(",", $group_roles);
		$array = array('group_id'=>$_POST['group_id'],
				'groupname'=>$_POST['txt_groupname'],
				'group_roles'=>$group_roles,
				'on_time'=>time()
				);
		if ($_POST['is_submit']){
			Model::factory('witkey_member_group')->setData($array)->setWhere('group_id = '.$_POST['is_submit'])->update();
			keke::show_msg('系统提交','/index.php/admin/user_group/add?group_id='.$_POST['is_submit'],'编辑成功','success');
		}else{
			Model::factory('witkey_member_group')->setData($array)->create();
			keke::show_msg('系统提交','index.php/admin/user_group/add','提交成功','success');
		}
	}
	function action_del(){
		if($_GET['group_id']){
			$where = 'group_id ='.$_GET['group_id'];
		}
		echo Model::factory('witkey_member_group')->setWhere($where)->del();
	}
}


/* Keke::admin_check_role ( 13 );

$menuset_arr = keke_admin_class::get_admin_menu ();
$membergroup_obj = new Keke_witkey_member_group_class ();

//列表模式
$grouplist_arr = $membergroup_obj->query_keke_witkey_member_group ();
//添加-编辑模式

if ($op == 'del') {
	$editgid = $editgid ? $editgid : Keke::admin_show_msg ( $_lang['param_error'], "index.php?do=user&view=back&type=group",3,'','warning');
	$membergroup_obj->setWhere ( "group_id='{$editgid}'" );
	$membergroup_obj->del_keke_witkey_member_group ();
	Keke::admin_system_log ( $_lang['delete_user_group']."$groupinfo_arr[groupname]" );
	Keke::admin_show_msg ( $_lang['operate_success'], "index.php?do=user&view=group_list", 3 ,'','success');
}


require $template_obj->template ( 'control/admin/tpl/admin_user_group_list' );
 */
 
