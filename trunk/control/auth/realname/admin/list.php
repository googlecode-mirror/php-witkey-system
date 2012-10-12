<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ����ʵ����֤�б�ҳ
 * @author Michael
 * @version 2.2
   2012-10-11
 */
class Control_auth_realname_admin_list extends Controller {
	/**
	 * ��ʼ����̨�б�ҳ
	 * ��ʾ���е���֤��¼������˵ļ�¼����ǰ��
	 */
	function action_index(){
	   global $_K,$_lang;
	   /* echo $_K['directory'];
	   echo "<br>";
	   echo $_K['control'];  */
	   $fields = ' `r_id`,`uid`,`username`,`realname`,`id_card`,`id_pic`,`cash`,`start_time`,`auth_status`,`end_time`';
	   //Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
	   $query_fields = array('r_id'=>$_lang['id'],'realname'=>$_lang['name'],'start_time'=>$_lang['time']);
	   //�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
	   $count = intval($_GET['count']);
	   //����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
	   $base_uri = BASE_URL."/index.php/auth/realname_admin_list";
	   //��ӱ༭��uri,add���action �ǹ̶���
	   $add_uri =  $base_uri.'/add';
	   //ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
	   $del_uri = $base_uri.'/del';
	   //Ĭ�������ֶΣ����ﰴʱ�併��
	   $this->_default_ord_field = 'start_time';
	   //����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
	   extract($this->get_url($base_uri));
	 
	   //��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
	   $data_info = Model::factory('witkey_auth_realname')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
	   //�б�����
	   $list_arr = $data_info['data'];
	   //��ҳ����
	   $pages = $data_info['pages'];
	   
	   require Keke_tpl::template ( 'control/auth/realname/tpl/admin_list' );
	}
	/**
	 * ��ʼ����֤��Ϣҳ��
	 */
	function action_add(){
		global $_K,$_lang;
		
		require Keke_tpl::template ( 'control/auth/realname/tpl/admin_info' );
	}
	/**
	 * ��֤ͨ��
	 */
	function action_pass(){
		 global $_lang;
		 $auth_code = 'realname';
		 if($_GET['u_id']){
		 	$uid = $_GET['u_id'];
		 }else{
		 	$uid = $_POST['ckb'];
		 }
		 Keke_user_auth::pass($uid, $auth_code);
		 Keke::show_msg($_lang['submit_success'],'index.php/auth/realname_admin_list','success');
	}
	/**
	 * ��֤��ͨ��
	 */
	function action_no_pass(){
		global $_lang;
		$auth_code = 'realname';
		if($_GET['u_id']){
			$uid = $_GET['u_id'];
		}else{
			$uid = $_POST['ckb'];
		}
		Keke_user_auth::no_pass($uid, $auth_code);
		Keke::show_msg($_lang['submit_success'],'index.php/auth/realname_admin_list','success');
	}
	/**
	 * ����ɾ�������ɾ�� 
	 */
	function action_del(){
		global $_lang;
		$auth_code = 'realname';
		if($_GET['u_id']){
			$uid = $_GET['u_id'];
		}else{
			$uid = $_POST['ckb'];
		}
		Keke_user_auth::no_pass($uid, $auth_code);
		Keke::show_msg($_lang['submit_success'],'index.php/auth/realname_admin_list','success');
	}
}

/* $realname_obj = new Keke_witkey_auth_realname_class (); //ʵ����ʵ����֤��
$url = "index.php?do=" . $do . "&view=" . $view . "&auth_code=" . $auth_code . "&w[page_size]=" . $w [page_size] . "&w[realname_a_id]=" . $w [realname_a_id] . "&w[username]=" . $w [username] . "&w[auth_status]=" . $w [auth_status]; //��ת��ַ
if (isset ( $ac )) {
	switch ($ac) {
		case "pass" : //����ͨ����֤����
			kekezu::admin_system_log($obj.$_lang['pass_realname_auth']);
			$auth_obj->review_auth ( $realname_a_id, 'pass' );
			break;
		case "not_pass" : //������ͨ����֤����
			kekezu::admin_system_log($obj.$_lang['nopass_realname_auth']);
			$auth_obj->review_auth ( $realname_a_id, 'not_pass' );
			break;
			;
		case 'del' : //����ɾ����֤����
			kekezu::admin_system_log($obj.$_lang['delete_realname_auth']);
			$auth_obj->del_auth ( $realname_a_id );
			break;
	}
} elseif (isset ( $sbt_action )) {
	$keyids = $ckb;

	switch ($sbt_action) {
		case $_lang['mulit_delete'] : //����ɾ��
			kekezu::admin_system_log($_lang['mulit_delete_realname_auth']);
			$auth_obj->del_auth ( $keyids );
			break;
			;
		case $_lang['mulit_pass'] : //�������
			kekezu::admin_system_log($_lang['mulit_pass_realname_auth']);
			$auth_obj->review_auth ( $keyids, 'pass' );
			break;
			;
		case $_lang['mulit_nopass'] : //���������

			kekezu::admin_system_log($_lang['mulit_nopass_realname']);
			$auth_obj->review_auth ( $keyids, 'not_pass' );
			break;
	}
} else //�б�
{
	$where = " 1 = 1 "; //Ĭ�ϲ�ѯ����
	($w ['auth_status'] === "0" and $where .= " and auth_status = 0 ") or ($w ['auth_status'] and $where .= " and auth_status = '$w[auth_status]' "); //������֤״̬
	intval ( $w ['realname_a_id'] ) and $where .= " and realname_a_id = " . intval ( $w ['realname_a_id'] ) . ""; //������֤���
	$w ['username'] and $where .= " and username like '%" . $w ['username'] . "%' "; //������֤����
	$where.=" order by realname_a_id desc ";
	intval ( $w ['page_size'] ) and $page_size = intval ( $w ['page_size'] ) or $page_size = 10; //ÿҳ��ʾ��������Ĭ��10
	$realname_obj->setWhere ( $where ); //��ѯͳ��
	$count = $realname_obj->count_keke_witkey_auth_realname ();
	intval ( $page ) or $page = 1 and $page = intval ( $page );
	$kekezu->_page_obj->setAjax(1);
	$kekezu->_page_obj->setAjaxDom("ajax_dom");
	$pages = $kekezu->_page_obj->getPages ( $count, $page_size, $page, $url );
	//��ѯ�������
	$realname_obj->setWhere ( $where . $pages [where] );
	$realname_arr = $realname_obj->query_keke_witkey_auth_realname ();
	require $kekezu->_tpl_obj->template ( "auth/" . $auth_dir . "/control/admin/tpl/auth_list" );
} */