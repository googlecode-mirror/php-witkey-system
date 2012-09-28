<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * 互评配置
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-10-22-下午04:10:03
 * @version V2.0
 */
class Control_admin_user_markconfig extends Controller{
	function action_index(){
		global $_K,$_lang;
		//读取mark_config表的数据
		$list_arr = db::select()->from('witkey_mark_config')->execute();
		//读取model表的数据，直接模板读取，
		$model_arr = Keke::$_model_list;
		//model_arr数组重组 
		$model_arr = Keke::get_arr_by_key($model_arr,'model_code');
		require keke_tpl::template('control/admin/tpl/user/mark_config');
	}
	function action_edit(){
		global $_K,$_lang;
		$mark_config_id = $_GET['mark_config_id'];
		$where .='mark_config_id='.$mark_config_id;
		//读取mark_config指定mark_config_id表的数据
		$list_arr = db::select()->from('witkey_mark_config')->where($where)->execute();
		$list_arr = $list_arr[0];
		//读取model表的数据，直接模板读取，
		$model_arr = Keke::$_model_list;
		//model_arr数组重组
		$model_arr = Keke::get_arr_by_key($model_arr,'model_code');
		require keke_tpl::template('control/admin/tpl/user/mark_config_edit');
	}
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		Keke::formcheck($_POST['formhash']);
		//需要更新的数据
		$array = array('good'=>$_POST['good'],
				'normal'=>$_POST['normal'],
				'bad'=>$_POST['bad'],
				);
		//更新mark_config表的数据
		Model::factory('witkey_mark_config')->setData($array)->update();
		Keke::show_msg("提交成功","index.php/admin/user_markconfig","success");
	}
	function action_del(){
		$mark_config_id = $_GET['mark_config_id'];
		$where .='mark_config_id='.$mark_config_id;
		echo Model::factory('witkey_mark_config')->setWhere($where)->del();
	}	
}
/* Keke::admin_check_role ( 78 );

$juese = array ("1" => $_lang['witkey'], "2" => $_lang['employer'] );

$url = "index.php?do=$do&view=$view&op=$op&mark_config_id=$mark_config_id";

$mark_config_obj = keke_table_class::get_instance ( 'witkey_mark_config' );

if ($ac == 'del' && $mark_config_id) {
	Keke::admin_system_log ( $_lang['delete_mark_config'] );
	$mark_config_obj->del ( 'mark_config_id', $mark_config_id ) and Keke::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['delete_faile'], $url,3,'','warning' );

}
foreach ( $Keke->_model_list as $k => $v ) {
	$model_list2 [$v ['model_code']] = $v ['model_name'];
}

$mark_config_arr = $mark_config_obj->get_grid ( '1=1', $url, '', 14 );

$mark_config_arr = $mark_config_arr ['data'];

require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_" . $op ); */