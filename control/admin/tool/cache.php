<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台缓存清除
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-19下午09:25:13
 */
class Control_admin_tool_cache extends Controller{
    
	//初始化页面 
	function action_index(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/tool/cache');
	}
	//清除缓存
	function action_del(){
		global $_lang;
		$cache_path = S_ROOT.'data/cache/';
		$tpl_path = S_ROOT.'data/tpl_c/';
		
		$file_obj = new keke_file_class;
		$msg = '';
		// 清除数据缓存
		if($_GET['ckb_obj_cache']){
			Cache::instance()->del_all();
			$msg = $_lang['object_cache_empty'];
		}
		//清除模板缓存
		if($_GET['ckb_tpl_cache']==1){
			$file_obj->delete_files($tpl_path);
			$msg.= $_lang['template_cache_empty'];
		}
		//ajax请求响应 
		if($_GET['ajax']=='1'){
			Keke::echojson(1,1);
		}else{
		 //普通表单请求响应	
			Keke::show_msg($msg,'admin/tool_cache','success');
		}
		
		
	}
	
}
