<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * 互评规则配置
 * @copyright keke-tech
 * @author Aqing
 * @version v 2.0
 * 2010-08-29 14:37:34
 */
class Control_admin_user_mark extends Controller{
	function action_index(){
		global $_K,$_lang;
		$mark_rule = Db::select()->from('witkey_mark_rule')->execute();
// 		var_dump($mark_rule);die;
		require Keke_tpl::template('control/admin/tpl/user/mark');
	}
	function action_add(){
		global $_K,$_lang;
		$mark_rule_id = $_GET['mark_rule_id'];
		if ($mark_rule_id){
			$where .= 'mark_rule_id='.$mark_rule_id;
			$mark_rule_arr = Db::select()->from('witkey_mark_rule')->where($where)->execute();
			$mark_rule_arr = $mark_rule_arr[0];
		}
		require Keke_tpl::template('control/admin/tpl/user/mark_add');
	}
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		Keke::formcheck($_POST['formhash']);
		$array = array('g_value'=>$_POST['txt_g_value'],
				'm_value'=>$_POST['txt_m_value'],
				'g_title'=>$_POST['txt_g_title'],
				'm_title'=>$_POST['txt_m_title'],
				'g_ico'=>$_POST['hdn_g_ico'],
				'm_ico'=>$_POST['hdn_m_ico'],
				);
		if ($_GET['hdn_mark_rule_id']){
			Model::factory('witkey_mark_rule')->setData($array)->setWhere('mark_rule_id='.$_GET['hdn_mark_rule_id'])->update();
			Keke::show_msg("编辑成功","index.php/admin/user_mark/add？mark_rule_id=".$_GET['hdn_mark_rule_id'],"success");
		}else{
			Model::factory('witkey_mark_rule')->setData($array)->create();
			Keke::show_msg("添加成功","index.php/admin/user_mark/add","success");
		}
	}
	static function action_del_img(){
		//如果pk有值，则删除文件表中的art_pic
		if($_GET['pk']){
			Dbfactory::execute(" update ".TABLEPRE."witkey_article set art_pic ='' where art_id = ".intval($_GET['pk']));
		}
		//没有fid就查下fid,没有fid不能删除文件,出于安全考量
		if(!intval($_GET['fid'])){
			$fid = Dbfactory::get_count(" select file_id from ".TABLEPRE."witkey_file where save_name = '.{$_GET['filepath']}.'");
		}else{
			$fid = $_GET['fid'];
		}
		//删除文件
		keke_file_class::del_att_file($fid, $_GET['filepath']);
		Keke::echojson ( '', '1' );
	}
	function action_del(){
		$mark_rule_id = $_GET['mark_rule_id'];
		$where .='mark_rule_id='.$mark_rule_id;
		echo Model::factory('witkey_mark_rule')->setWhere($where)->del();
	}
}
/* Keke::admin_check_role ( 33 );
$url = "index.php?do=$do&view=$view&mark_rule_id=$mark_rule_id";

$mark_rule_obj = new Keke_witkey_mark_rule_class ();
if (isset ( $op )) {
	switch ($op) {
		case "edit" : //编辑
			if (intval ( $mark_rule_id )) {
				$mark_rule_obj->setWhere ( " mark_rule_id  =  " . $mark_rule_id . "" );
				$mark_info = $mark_rule_obj->query_keke_witkey_mark_rule ();
				$mark_info = $mark_info ['0'];
			}
			require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_edit" );
			break;
		case "del" :
			intval ( $mark_rule_id ) or Keke::admin_show_msg ($_lang['parameter_error_fail_to_delete'], $url,3,'','warning' );
			$mark_rule_obj->setWhere ( " mark_rule_id  =  " . $mark_rule_id . "" );
			$res = $mark_rule_obj->del_keke_witkey_mark_rule ();
			Keke::admin_system_log ($_lang['delete_credit_rules']);
			$res < 1 and Keke::admin_show_msg ($_lang['delete_fail'], $url,3,'','warning' ) or Keke::admin_show_msg ( $_lang['success_delete_a_credit_rules'], $url,3,'','success' );
			break;
		case "config":
			Keke::admin_check_role(78);
			require ADMIN_ROOT . 'admin_config_' . $view . '_'.$op.'.php';
			break;
		case "config_add":
			Keke::admin_check_role(78);
			require ADMIN_ROOT . 'admin_config_' . $view . '_'.$op.'.php';
			break;
		case "log":
			Keke::admin_check_role(79);
		   require ADMIN_ROOT . 'admin_config_' . $view . '_'.$op.'.php';
			break;
	}
} elseif ($is_submit=='1'){    //编辑
	intval ( $hdn_mark_rule_id ) and $mark_rule_obj->setWhere ( " mark_rule_id = " . intval ( $hdn_mark_rule_id ) . "" );
	$mark_rule_obj->setM_value(intval( $txt_m_value ));
	$mark_rule_obj->setG_value(intval($txt_g_value));
	$mark_rule_obj->setG_title ( $txt_g_title );
	$mark_rule_obj->setM_title ( $txt_m_title );
	$mark_rule_obj->setG_ico($hdn_g_ico);
	$mark_rule_obj->setM_ico($hdn_m_ico);
	if(intval ( $hdn_mark_rule_id )){
		Keke::admin_system_log($_lang['edit_mark_rule']);
	 	$res = $mark_rule_obj->edit_keke_witkey_mark_rule () ;
	}else{
		Keke::admin_system_log($_lang['create_mark_rule']);
		 $res = $mark_rule_obj->create_keke_witkey_mark_rule ();
	}

	if($res){
	 	$u_list = Dbfactory::query(sprintf(" select buyer_credit,seller_credit,uid from %switkey_space",TABLEPRE));
		if($u_list){
			$s  = sizeof($u_list);
			for ($i=0;$i<$s;$i++){
				$b_level = keke_user_mark_class::get_mark_level($u_list[$i]['buyer_credit'],2);
				$s_level = keke_user_mark_class::get_mark_level($u_list[$i]['seller_credit'],1);
				$sql=" UPDATE ".TABLEPRE."witkey_space set buyer_level='".serialize($b_level)."',seller_level='".serialize($s_level)."' where uid='{$u_list[$i]['uid']}'";
				$sql!=''&&Dbfactory::execute($sql);
			}
		}
	}
	$res  and Keke::admin_show_msg ($_lang['operate_notice'], $url,2,$_lang['submit_success'],'success') or Keke::admin_show_msg ($_lang['operate_notice'], $url,2,$_lang['submit_fail'],'warning' );
} else {//列表
	 
	$mark_rule = $mark_rule_obj->query_keke_witkey_mark_rule ();
	require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_{$do}_{$view}" );
	Keke::admin_check_role ( 133 );

$juese = array ("1" => $_lang['witkey'], "2" => $_lang['employer'] );

$url = "index.php?do=config&view=mark&op=config";

$mark_config_obj = keke_table_class::get_instance ( 'witkey_mark_config' );

$mark_config_id and $mark_config_arr = $mark_config_obj->get_table_info ( 'mark_config_id', intval($mark_config_id) );

foreach ( $Keke->_model_list as $k => $v ) {
	$model_list2 [$v ['model_code']] = $v ['model_name'];
}
if ($sbt_add && $fds && $hdn_mark_config_id) {
	$hdn_mark_config_id and Keke::admin_system_log ( $_lang['edit'] . $obj_name . $_lang['mark_config'] );
	$res = $mark_config_obj->save ( $fds, array ('mark_config_id' => $hdn_mark_config_id ) );
	$res and Keke::admin_show_msg ( $_lang['edit_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['edit_fail'], $url,3,'','warning' );
}

require $Keke->_tpl_obj->template ( "control/admin/tpl/admin_" . $do . "_" . $view . "_" . $op );
 } */