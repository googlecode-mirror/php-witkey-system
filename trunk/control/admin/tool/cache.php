<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨�������
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-19����09:25:13
 */
class Control_admin_tool_cache extends Controller{
    
	//��ʼ��ҳ�� 
	function action_index(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/tool/cache');
	}
	//�������
	function action_del(){
		global $_lang;
		$cache_path = S_ROOT.'data/cache/';
		$tpl_path = S_ROOT.'data/tpl_c/';
		
		$file_obj = new keke_file_class;
		$msg = '';
		// ������ݻ���
		if($_GET['ckb_obj_cache']){
			Cache::instance()->del_all();
			$msg = $_lang['object_cache_empty'];
		}
		//���ģ�建��
		if($_GET['ckb_tpl_cache']==1){
			$file_obj->delete_files($tpl_path);
			$msg.= $_lang['template_cache_empty'];
		}
		//ajax������Ӧ 
		if($_GET['ajax']=='1'){
			Keke::echojson(1,1);
		}else{
		 //��ͨ��������Ӧ	
			Keke::show_msg($msg,'admin/tool_cache','success');
		}
		
		
	}
	
}
