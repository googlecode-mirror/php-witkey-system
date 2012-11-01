<?php	defined ( 'IN_KEKE' ) or 	exit ( 'Access Denied' );
/**
*客服管理
*/
class Control_admin_user_custom extends Controller{
	function action_index(){
		global $_K,$_lang;
		
		$base_uri = BASE_URL.'/index.php/admin/user_custom';
		 
		//列表用户的用户组信息
		$sql = "SELECT a.*,count(*) as num FROM `:Pwitkey_member_group` as a \n".
				"left join :Pwitkey_space as b\n".
				"on a.group_id = b.group_id \n".
				"group by a.group_id";
		$list_arr = DB::query($sql)->tablepre(':P')->execute();
		  
	 
		require keke_tpl::template('control/admin/tpl/user/custom');
	}
	//添加用户组
	function action_add(){
		global $_K,$_lang;
		//如果获取出传递的uid，如果存在就是编辑，没有则是添加
		$gid = $_GET['group_id']; 
		$group_info = DB::select()->from('witkey_member_group')->where("group_id = '$gid'")->get_one()->execute();
		require keke_tpl::template('control/admin/tpl/user/custom_add');
	}
	/**
	 * 保存用户信息
	 */
	function action_update(){
		//防止sql注入
		$_POST = Keke_tpl::chars($_POST);
		//防止跨域提交
		Keke::formcheck($_POST['formhash']);
		$uid = $_POST['euid'];
		$username = $_POST['username'];
		$phone = $_POST['phone'];
		$qq = $_POST['qq'];
		$group_id = $_POST['group_id'];
		//需要更新的数据
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
	 * 获取用户信息
	 */
	function action_get_user(){
		if ($_GET['guid']){
			Keke::echojson(1,1,keke_user_class::get_user_info($_GET['guid']));
			die;
		}
	}
	/**
	 * 删除用户组
	 */
	function action_del(){
		if($_GET['uid']){
			$where .= ' and uid ='.$_GET['uid'];
		}
		echo Model::factory('witkey_space')->setWhere($where)->del();
	}
}