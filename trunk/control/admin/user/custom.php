<?php	defined ( 'IN_KEKE' ) or 	exit ( 'Access Denied' );
/**
*�ͷ�����
*/
class Control_admin_user_custom extends Controller{
	function action_index(){
		global $_K,$_lang;
		//ѡ��Ҫ��ѯ���ֶΣ������б�����ʾ
		$fields = '`uid`,`username`,`group_id`,`phone`,`qq`';
		//�������õ����ֶ�
		$query_fields = array('uid'=>$_lang['id'],'username'=>$_lang['name']);
		//����uri
		$base_uri = BASE_URL.'/index.php/admin/user_custom';
		//ͳ�Ʋ�ѯ�����ļ�¼��������
		$count = intval($_GET['count']);
		//Ĭ�������ֶ�
		$this->_default_ord_field = 'uid';
		//�����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ��ҳ����ز���
		$data_info = Model::factory('witkey_space')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		$pages = $data_info['pages'];
		//�б��û����û�����Ϣ
		$grouplist_arr = keke_admin_class::get_user_group ();
		require keke_tpl::template('control/admin/tpl/user/custom');
	}
	//����û���
	function action_add(){
		global $_K,$_lang;
		//�����ȡ�����ݵ�uid��������ھ��Ǳ༭��û���������
		if ($_GET['uid']){
			$where .= 'uid ='.$_GET['uid'];
			$spaceinfo = DB::select()->from('witkey_space')->where($where)->execute();
			$spaceinfo = $spaceinfo[0];
		}
		$member_group_arr = DB::select()->from('witkey_member_group')->where('1=1')->execute();
		require keke_tpl::template('control/admin/tpl/user/custom_add');
	}
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
			$data_info = DB::select('uid')->from('witkey_space')->execute();
			Model::factory('witkey_space')->setData($array)->setWhere('uid = '.$_POST['euid'])->update();
			keke::show_msg("�ύ�ɹ�","admin/user_custom/add?uid=$_POST[euid]","success");
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