<?php	defined ( 'IN_KEKE' ) or 	exit ( 'Access Denied' );
/**
*客服管理
*/
class Control_admin_user_custom extends Controller{
	function action_index(){
		global $_K,$_lang;
		//选择要查询的字段，将在列表中显示
		$fields = '`uid`,`username`,`group_id`,`phone`,`qq`';
		//搜索中用到的字段
		$query_fields = array('uid'=>$_lang['id'],'username'=>$_lang['name']);
		//基本uri
		$base_uri = BASE_URL.'/index.php/admin/user_custom';
		//统计查询出来的记录的总条数
		$count = intval($_GET['count']);
		//默认排序字段
		$this->_default_ord_field = 'uid';
		//处理查询的条件
		extract($this->get_url($base_uri));
		//获取分页的相关参数
		$data_info = Model::factory('witkey_space')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		$pages = $data_info['pages'];
		//列表用户的用户组信息
		$grouplist_arr = keke_admin_class::get_user_group ();
		require keke_tpl::template('control/admin/tpl/user/custom');
	}
	//添加用户组
	function action_add(){
		global $_K,$_lang;
		//如果获取出传递的uid，如果存在就是编辑，没有则是添加
		if ($_GET['uid']){
			$where .= 'uid ='.$_GET['uid'];
			$spaceinfo = DB::select()->from('witkey_space')->where($where)->execute();
			$spaceinfo = $spaceinfo[0];
		}
		$member_group_arr = DB::select()->from('witkey_member_group')->where('1=1')->execute();
		require keke_tpl::template('control/admin/tpl/user/custom_add');
	}
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
			$data_info = DB::select('uid')->from('witkey_space')->execute();
			Model::factory('witkey_space')->setData($array)->setWhere('uid = '.$_POST['euid'])->update();
			keke::show_msg("提交成功","admin/user_custom/add?uid=$_POST[euid]","success");
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