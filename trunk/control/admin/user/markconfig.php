<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * ��������
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-10-22-����04:10:03
 * @version V2.0
 */
class Control_admin_user_markconfig extends Control_admin{
	function action_index(){
		global $_K,$_lang;
		//��ȡmark_config�������
		$list_arr = db::select()->from('witkey_mark_config')->execute();
		//��ȡmodel������ݣ�ֱ��ģ���ȡ��
		Keke::init_model();
		$model_arr = Keke::$_model_list;
		//model_arr�������� 
		$model_arr = Keke::get_arr_by_key($model_arr,'model_code');
		require keke_tpl::template('control/admin/tpl/user/mark_config');
	}
	function action_edit(){
		global $_K,$_lang;
		$mark_config_id = $_GET['mark_config_id'];
		$where .='mark_config_id='.$mark_config_id;
		//��ȡmark_configָ��mark_config_id�������
		$list_arr = db::select()->from('witkey_mark_config')->where($where)->get_one()->execute();
		//��ȡmodel������ݣ�ֱ��ģ���ȡ��
		Keke::init_model();
		$model_arr = Keke::$_model_list;
		//model_arr�����ع�
		$model_arr = Keke::get_arr_by_key($model_arr,'model_code');
		require keke_tpl::template('control/admin/tpl/user/mark_config_edit');
	}
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		Keke::formcheck($_POST['formhash']);
		//��Ҫ���µ�����
		$array = array('good'=>$_POST['good'],
				'normal'=>$_POST['normal'],
				'bad'=>$_POST['bad'],
				);
		//����mark_config�������
		$where = "mark_config_id ='{$_POST['hdn_mark_config_id']}'";
		Model::factory('witkey_mark_config')->setData($array)->setWhere($where)->update();
		Keke::show_msg("�ύ�ɹ�","admin/user_markconfig","success");
	}
	function action_del(){
		$mark_config_id = $_GET['mark_config_id'];
		$where .='mark_config_id='.$mark_config_id;
		echo Model::factory('witkey_mark_config')->setWhere($where)->del();
	}	
}
