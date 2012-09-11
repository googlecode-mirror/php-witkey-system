<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 技能合并
 * @copyright keke-tech
 * @author Michael
 * @version v 2.0
 * 2010-5-17下午02:25:13
 */

Keke::admin_check_role(20);
$indus_p_arr = $Keke->_indus_p_arr;  
$url = "index.php?do=$do&view=$view";
if (isset($sbt_edit)){
        $to_indus_id or Keke::admin_show_msg($_lang['target_industry_not_top'],$url,2,'','warning');
		//获取被合并的行业
		$indus_hb_arr = Keke::get_industry($to_indus_id);
		//改变他们的indus_pid
		foreach ($indus_hb_arr as  $k=>$v){
			Dbfactory::execute("update ".TABLEPRE."witkey_industry set indus_pid = $slt_indus_id where indus_id = {$v['indus_id']}"); 
		}
		//删除被合并的父类
		Dbfactory::execute("delete from ".TABLEPRE."witkey_industry where indus_id = $to_indus_id");
		//更新相应的indus_pid 
		Dbfactory::execute("update ".TABLEPRE."witkey_task set indus_pid = $slt_indus_id where indus_pid = $to_indus_id");
		//Dbfactory::execute("update ".TABLEPRE."witkey_shop set indus_pid = $slt_indus_id where indus_pid = $to_indus_id");
		Dbfactory::execute("update ".TABLEPRE."witkey_service set indus_pid = $slt_indus_id where indus_pid = $to_indus_id");  
		Keke::admin_show_msg($_lang['industry_union_success'],$url,3,'','success');
} 
require_once $template_obj->template ( 'control/admin/tpl/admin_'.$do.'_' . $view );
