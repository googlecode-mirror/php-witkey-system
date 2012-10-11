<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 支能管理
 * @author Michael
 * @version 2.2
   2012-10-10
 */

class Control_admin_config_skill extends Controller{
    
	function action_index(){
    	global $_K,$_lang;
    	//选择要查询的字段，将在列表中显示
		$fields = '`skill_id`,`indus_id`,`skill_name`,`listorder`,`on_time`';
		//搜索中用到的字段
		$query_fields = array('indus_id'=>'行业名称','skill_name'=>'技能名称','on_time'=>$_lang['time']);
		//基本uri
		$base_uri = BASE_URL.'/index.php/admin/user_custom';
		//统计查询出来的记录的总条数
		$count = intval($_GET['count']);
		//默认排序字段
		$this->_default_ord_field = 'on_time';
		//处理查询的条件
		extract($this->get_url($base_uri));
		//获取分页的相关参数
		$data_info = Model::factory('witkey_skill')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$skill_arr = $data_info['data'];
		$pages = $data_info['pages'];

		$indus_option_arr = DB::select()->from('witkey_industry')->execute();
		$indus_show_arr = Keke::get_arr_by_key($indus_option_arr,'indus_id');
		
		
/* 		
 		$temp_arr = array ();
        foreach ($indus_option_arr as $v){
			$indus_option_arr = $v;
		}
		Keke::get_tree ( $indus_option_arr, $temp_arr,"option",$indus_option_arr[indus_pid]);
		var_dump($temp_arr);die;
		$indus_option_arr = Keke::get_arr_by_key($indus_option_arr,'indus_name');
		$indus_option_arr = $temp_arr;
		$indus_option_arr = $indus_option_arr[0];
		foreach ($indus_option_arr as $v){
			var_dump($v[indus_name]);
		}
		die;
		var_dump($indus_option_arr);die; */
		
		
    	require Keke_tpl::template("control/admin/tpl/config/skill");
    }
    function action_add(){
    	global $_K,$_lang;
    	require Keke_tpl::template('control/admin/tpl/config/skill_add');
    }
}

/* Keke::admin_check_role ( 8);
$table_obj = new keke_table_class ( "witkey_skill" );

//搜索行业下拉菜单
$temp_arr = array ();
$indus_option_arr = Keke::get_industry ();
Keke::get_tree ( $indus_option_arr, $temp_arr,"option",$w[indus_pid] );
$indus_option_arr = $temp_arr;
unset ( $temp_arr );
is_array($indus_arr)&&sort ( $indus_arr );
$indus_show_arr = array();
Keke::get_tree($indus_arr, $indus_show_arr,'cat',NULL,'indus_id','indus_pid','indus_name');
$indus_show_arr = Keke::get_table_data('*',"witkey_industry","",'indus_id','','','indus_id');
$where = ' 1 = 1';


$order_where.=" order by on_time desc ";
$url = "index.php?do=$do&view=$view&w[indus_pid]={$w[indus_pid]}&w[skill_name]={$w[skill_name]}
&page_size=$page_size&page=$page
&$ord[0]={$ord[1]}";

intval ( $page_size ) and $page_size = intval ( $page_size ) or $page_size = 10;
intval ( $page ) and $page = intval ( $page ) or $page = 1;

if(isset($sbt_search)){
	$w [indus_id]  and $where .= " and indus_id = $w[indus_id]";
	strval ( $w [skill_name] ) and $where .= " and skill_name like '%$w[skill_name]%'";
	$ord [1] and $order_where = " order by $ord[0] $ord[1]";
}

$where =$where.$order_where;

$r = $table_obj->get_grid ( $where, $url, $page, $page_size );
$skill_arr = $r [data];
$pages = $r [pages];

if ($ac == 'del') {
	$skill_log = keke_table_class::all_table_info("witkey_skill", array("skill_id"=>$skill_id));
	$res = $table_obj->del('skill_id', $skill_id);
	Keke::admin_system_log($_lang['delete_skill'].":".$skill_log[skill_name]);
	$res and Keke::admin_show_msg($_lang['delete_success'], $url,'3','','success') or Keke::admin_show_msg($_lang['delete_fail'], $url,'3','','warning');
}
//批量删除
if ($sbt_action) {
	if (! count($ckb)){
		Keke::admin_show_msg ($_lang['choose_operation'], $url ,'3','','warning');
	}else{
		$res = $table_obj->del ('skill_id',$ckb);

		Keke::admin_system_log($_lang['mulit_delete_skill']);
		$res and Keke::admin_show_msg($_lang['delete_success'], $url,'3','','success') or Keke::admin_show_msg($_lang['delete_fail'], $url,'3','','warning');
	}
}
//递归分类列表
$temp_arr = array ();
Keke::get_tree ( $indus_arr, $temp_arr, 'option', NULL, 'indus_id', 'indus_pid', 'indus_name' );
$indus_arr = $temp_arr;

unset ( $temp_arr );
require $Keke->_tpl_obj->template ( 'control/admin/tpl/admin_task_' . $view );

 */
