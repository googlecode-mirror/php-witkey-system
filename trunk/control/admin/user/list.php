<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
��̨�û�·��
*/
class Control_admin_user_list extends Controller{
	function action_index(){
		global $_K,$_lang;
		//��Ҫ����ʾ���ֶ�
		$fields = '`uid`,`username`,`group_id`,`user_type`,`status`,`reg_time`,`reg_ip`,`credit`,`balance`,`recommend` ';
		//�������õ����ֶ�
		$query_fields = array('uid'=>$_lang['id'],'username'=>$_lang['name'],'reg_time'=>$_lang['time']);
		//����uti
		$base_uri = BASE_URL.'/index.php/admin/user_list';
		//ͳ�Ʋ�ѯ����������
		$count = intval($_GET['count']);
		//ɾ��url
		$del_uri = $base_uri.'/del';
		//Ĭ�ϲ�ѯ�ֶ�
		$this->_default_ord_field = 'reg_time';
		//��ȡ��ѯ����uri
		extract($this->get_url($base_uri));
		//��ҳ��ѯ������
		$data_info = Model::factory('witkey_space')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//��֤�û���û�п����̣��Ƽ����
		$shop_open = DB::select('shop_id')->from('witkey_shop')->where('1=1')->execute();
		$shop_open = $shop_open['0'];
		require keke_tpl::template('control/admin/tpl/user/list');
	}
	/**
	 * �Ƽ��û�
	 */
	function action_recommend(){
		$uid = $_GET['uid'];
		$where .= ' uid='.$uid;
		$page = $_GET['page'];
		Dbfactory::update('update keke_witkey_space set recommend=1 where '.$where); 
		keke::show_msg("ϵͳ��ʾ","index.php/admin/user_list?page=$page","�Ƽ��ɹ�","success");
	}
	/**
	 * ȡ���Ƽ� �û�
	 */
	function action_moverecommend(){
		$uid = $_GET['uid'];
		$where .= ' uid='.$uid;
		$page = $_GET['page'];
		Dbfactory::update('update keke_witkey_space set recommend=0 where '.$where);
		keke::show_msg("ϵͳ��ʾ","index.php/admin/user_list?page=$page","ȡ���Ƽ��ɹ�","success");
	}
	/**
	 * �����û�
	 */
	function action_disable(){
		$uid = $_GET['uid'];
		$where .= ' uid='.$uid;
		$page = $_GET['page'];
		Dbfactory::update('update keke_witkey_space set status=2 where '.$where);
		keke::show_msg("ϵͳ��ʾ","index.php/admin/user_list?page=$page","���óɹ�","success");
	}
	/**
	 * �����û�
	 */
	function action_able(){
		$uid = $_GET['uid'];
		$where .= ' uid='.$uid;
		$page = $_GET['page'];
		Dbfactory::update('update keke_witkey_space set status=1 where '.$where);
		keke::show_msg("ϵͳ��ʾ","index.php/admin/user_list?page=$page","���óɹ�","success");
	}
	/**
	 * ����������ɾ���û�
	 */
	function action_del(){
		$uid = $_GET['uid'];
		if ($uid){
		$where .= ' uid='.$uid;
		}elseif($_GET['ids']) {
			$where .= 'uid in'.'('.$_GET['ids'].')' ;
		}
		echo Model::factory('witkey_space')->setWhere($where)->del();
	}
}

/* $views = array("add","list","charge","custom_list","group_add","group_list","custom_add");

$view = (! empty ( $view ) && in_array ( $view, $views )) ? $view : 'add';

require "admin_user_$view.php";$page_obj = $Keke->_page_obj; */
//list
/* $edituid and $memberinfo_arr = Keke::get_user_info ( $edituid );
$table_class = new keke_table_class ( 'witkey_space' );
$member_class = new keke_table_class ( 'witkey_member' );
//��ѯ
$url_str = "index.php?do=$do&view=$view&space[username]={$space['username']}&space[uid]={$space['uid']}&page_size=$page_size&ord=$ord&slt_static=$static";

$grouplist_arr = keke_admin_class::get_user_group ();
switch ($op) {
	case "del" : //ɾ��
		$del_uid = keke_user_class::user_delete ( $edituid );
		Keke::admin_system_log ( Keke::lang ( 'delete_member}' ) . $memberinfo_arr ['username'] );
		$del_uid and Keke::admin_show_msg ( $_lang['operate_success'], "index.php?do=user&view=list", 3, '', 'success' ) or Keke::admin_show_msg ( $_lang['operate_fail'], "index.php?do=user&view=list", 3, '', 'warning' );
		break;
	case "disable" : //�����û�
		$sql = sprintf ( "update  %switkey_space set status=2 where uid =%d", TABLEPRE, $edituid );
		Dbfactory::execute ( $sql );
		$v_arr = array ($_lang['username'] => $memberinfo_arr['username'], $_lang['website_name'] => $Keke->_sys_config['website_name'] );
		keke_shop_class::notify_user ( $memberinfo_arr ['uid'], $memberinfo_arr ['username'], 'freeze', $_lang['user_freeze'], $v_arr );
		Keke::admin_system_log ( $_lang['unfreeze_member'] . $memberinfo_arr ['username'] );
		Keke::admin_show_msg ( $_lang['operate_success'], "index.php?do=user&view=list", 3, '', 'success' );
		break;
	case "able" : //�ⶳ�û�
		Keke::admin_check_role ( 24 );
		
		$sql = sprintf ( "update  %switkey_space set status=1 where uid =%d", TABLEPRE, $edituid );
		Dbfactory::execute ( $sql );
		$v_arr = array ($_lang['username'] => $memberinfo_arr['username'], $_lang['website_name'] => $Keke->_sys_config['website_name'] );
		keke_msg_class::notify_user ( $memberinfo_arr ['uid'], $memberinfo_arr ['username'], 'unfreeze', $_lang['user_unfreeze'], $v_arr );
		Keke::admin_system_log ( $_lang['unfreeze_member'] . $memberinfo_arr ['username'] );
		Keke::admin_show_msg ( $_lang['operate_success'], "index.php?do=user&view=list", 3, '', 'success' );
		break;
	case 'recommend'://�Ƽ�
		$sql = sprintf ( "update  %switkey_space set recommend=1 where uid =%d", TABLEPRE, $edituid );
		Dbfactory::execute ( $sql );
		Keke::admin_system_log ( $_lang['recommend'] . $memberinfo_arr ['username'] );
		Keke::admin_show_msg ( $_lang['operate_success'], $url_str.'&page='.$page, 3, '', 'success' );
		
		break;
	case 'move_recommend'://ȡ���Ƽ�
		$sql = sprintf ( "update  %switkey_space set recommend=0 where uid =%d", TABLEPRE, $edituid );
		Dbfactory::execute ( $sql );
		Keke::admin_system_log ( $_lang['move_recommend'] . $memberinfo_arr ['username'] );
		Keke::admin_show_msg ( $_lang['operate_success'], $url_str.'&page='.$page, 3, '', 'success' );
		break;
}

if ($sbt_action && is_array ( $ckb )) {
	
	$ids = implode ( ',', $ckb );
	$sql = sprintf ( "select uid,username from %switkey_space where uid in (%s)", TABLEPRE, $ids );
	$space_arr = Dbfactory::query ( $sql );
	switch ($sbt_action) {
		case $_lang['mulit_delete'] : //����ɾ��
			$table_class->del ( 'uid', $ckb );
			$member_class->del ( 'uid', $ckb );
			Keke::admin_system_log ( $_lang['delete_user'] . "$ids" );
			Keke::admin_show_msg ( $_lang['operate_success'], 'index.php?do=user&view=list', 3, $_lang['mulit_operate_success'], 'success' );
			break;
		case $_lang['mulit_disable'] : //��������
			

			$sql = sprintf ( "update  %switkey_space set status=2 where uid in (%s)", TABLEPRE, $ids );
			Dbfactory::execute ( $sql ); //�ı��û�״̬ 
			foreach ( $space_arr as $v ) { //�ʼ�֪ͨ
				$v_arr = array ($_lang['username'] => $v['username'], $_lang['website_name'] => $Keke->_sys_config['website_name'] );
				keke_shop_class::notify_user ( $v ['uid'], $v ['username'], 'freeze', $_lang['user_freeze'], $v_arr );
			}
			Keke::admin_system_log ( $_lang['freeze_user'] . "$ids" );
			Keke::admin_show_msg ( $_lang['operate_success'], 'index.php?do=user&view=list', 3, $_lang['mulit_disable'], 'success' );
			break;
		case $_lang['mulit_use'] : //��������
			

			$sql = sprintf ( "update  %switkey_space set status=1 where uid in (%s)", TABLEPRE, $ids );
			Dbfactory::execute ( $sql ); //�ı��û�״̬ 
			foreach ( $space_arr as $v ) { //�ʼ�֪ͨ
				$v_arr = array ($_lang['username'] => $v['username'], $_lang['website_name'] => $Keke->_sys_config['website_name']);
				keke_msg_class::notify_user ( $v ['uid'], $v ['username'], 'unfreeze', $_lang['user_unfreeze'], $v_arr );
			}
			Keke::admin_show_msg ( $_lang['operate_success'], 'index.php?do=user&view=list', 3, $_lang['mulit_open_operate_success'], 'success' );
			break;
	}
} else {
	$where_str = " 1=1 ";
	//ÿҳ��ʾ��������Ĭ��10
	$page or $page = 1;
	$slt_page_size = intval ( $slt_page_size ) ? intval ( $slt_page_size ) : 10;
	$space ['uid'] and $where_str .= "and uid='{$space['uid']}' ";
	$space ['username'] and $where_str .= "and username like '%{$space['username']}%' ";
	$slt_static == 1 and $where_str .= "and status=1 ";
	$slt_static == 2 and $where_str .= "and status=2 ";
	$ord and $where_str .= " order by {$ord['0']} {$ord['1']}" or $where_str .= " order by uid desc";	
	$res = $table_class->get_grid ( $where_str, $url_str, $page, $slt_page_size, null,1,'ajax_dom');
	$userlist_arr = $res ['data'];
	var_dump('$userlist_arr');die();
	$pages = $res ['pages'];
	$uids = array();
	foreach((array)$userlist_arr as $v){
		$uids[] = $v['uid'];
	}
	$shop_open = Keke::get_table_data('shop_id,uid','witkey_shop','uid in ('.implode(',',$uids).')','','','','uid');
}



 */