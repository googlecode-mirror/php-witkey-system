<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * @todo ��̨��֤��Ŀ��װ��ɾ��
 * 2011-9-01 11:35:13
 */

Keke::admin_check_role ( 38 );
class Control_admin_auth_item_list extends Controller{

	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;

		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `auth_code`,`auth_title`,`auth_day`,`auth_cash`,`auth_expir`,`auth_open`,`update_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('auth_code'=>$_lang['id'],'auth_title'=>$_lang['username'],'update_time'=>$_lang['order_type']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//finance������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���finance_recharge So���ﲻ��дΪfinance/recharge
		$base_uri = BASE_URL."/index.php/admin/auth_item_list";

		//��ӱ༭��uri,add���action �ǹ̶���
		//$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'update_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_auth_item')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//�û���
		$group_arr = keke_admin_class::get_user_group ();

		//var_dump($list_arr);die;
		require Keke_tpl::template('control/admin/tpl/auth/item_list');

	}

}
/* $auth_item_obj = new Keke_witkey_auth_item_class ();
$url = "index.php?do=$do&view=$view";

//ɾ����֤��Ŀ
if ($ac === 'del') {
	keke_auth_fac_class::del_auth ( $auth_code, 'auth_item_cache_list' ); //����ɾ��
	Keke::admin_system_log ( $_lang['delete_auth'] . $auth_code ); //��־��¼
} elseif (isset ( $sbt_add )) {
	keke_auth_fac_class::install_auth ( $auth_dir ); //������֤��Ŀ
	Keke::admin_system_log ( $_lang['add_auth'] . $auth_dir ); //��־��¼
} elseif (isset ( $sbt_action ) && $sbt_action === $_lang['mulit_delete']) { //����ɾ��
	keke_auth_fac_class::del_auth ( $ckb, 'auth_item_cache_list' ); //��������
	Keke::admin_system_log ( $_lang['mulit_delete_auth'] . $ckb );
} else {
	$where = ' 1 = 1  ';
	intval ( $page_size ) or $page_size = 10 and $page_size = intval ( $page_size );
	$auth_item_obj->setWhere ( $where );
	$count = $auth_item_obj->count_keke_witkey_auth_item ();
	$page or $page = 1 and $page = intval ( $page );
	$Keke->_page_obj->setAjax(1);
	$Keke->_page_obj->setAjaxDom("ajax_dom");
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url );
	$where .= " order by listorder asc ";
	$auth_item_obj->setWhere ( $where . $pages ['where'] );
	$auth_item_arr = $auth_item_obj->query_keke_witkey_auth_item ();
}

require $Keke->_tpl_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */