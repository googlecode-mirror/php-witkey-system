<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * ������������
 * @copyright keke-tech
 * @author cc
 * @version v 2.2
 * 2010-08-29 14:37:34
 */
class Control_admin_user_marklog extends Control_admin{
	function action_index(){
		global $_K,$_lang;
		//Ҫ��ѯ���ֶ�
		$fields = '`mark_id`,`model_code`,`mark_type`,`by_username`,`username`,`mark_status`,`mark_value`,`mark_time`';
		//����ʹ�õ����ֶ�
		$query_fields = array('mark_id'=>$_lang['id'],'username'=>$_lang['name'],'mark_time'=>$_lang['time']);
		//����uri
		$base_uri=BASE_URL.'/index.php/admin/user_marklog';
		//ɾ��uri
		$del_uri=$base_uri.'/del';
		//Ĭ������
		$this->_default_ord_field = 'mark_time';
		//ͳ������
		$count = intval($_GET['count']);
		//��ȡuri����ȡ��ѯ����
		extract($this->get_url($base_uri));
		//��ѯ����
		$data_info = Model::factory('witkey_mark')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//��ȡmodel_code��model_name����ɵ�һ������
		$model_type_arr = Keke_global::get_model_type ();
		//model������
		$model_list = keke::$_model_list;
		//ͨ��model_code��ȡmodel_type��ֵ
		$model_list2 = Keke::get_arr_by_key($model_list,'model_code');
		require keke_tpl::template('control/admin/tpl/user/mark_log');
	}
	/**
	 * �����Ͷ���ɾ����¼
	 */
	function action_del(){
		if($_GET['mark_id']){
			$where .= 'mark_id = '.$_GET['mark_id'];
		}elseif($_GET['ids']){
			$where .= 'mark_id in'.'('.$_GET['ids'].')';
		}
		echo Model::factory('witkey_mark')->setWhere($where)->del();
	}
}
