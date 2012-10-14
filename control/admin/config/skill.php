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
		$query_fields = array('skill_id'=>'行业ID','skill_name'=>'技能名称','on_time'=>$_lang['time']);
		//基本uri
		$base_uri = BASE_URL.'/index.php/admin/config_skill';
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
    	require Keke_tpl::template("control/admin/tpl/config/skill");
    }
    
    function action_add(){
    	global $_K,$_lang;
    	if($_GET['skill_id']){
    		$sid = $_GET['skill_id'];
    		$skill_info = DB::select()->from('witkey_skill')->where("skill_id=$sid")->get_one()->execute();
    	}
    	//获取所有的行业数据
    	$indus_arr = DB::select()->from('witkey_industry')->execute();
    	$t_arr = array ();
    	//生成树开数组
    	Keke::get_tree ( $indus_arr, $t_arr, 'option', $skill_info['indus_id'], 'indus_id', 'indus_pid', 'indus_name' );
    	$indus_tree_arr =$t_arr;
    	unset ( $t_arr );
    	
    	require Keke_tpl::template('control/admin/tpl/config/skill_add');
    }
    function action_del(){
    	//删除单条,这里的case_id 是在模板上的请求连接中有的
		if($_GET['skill_id']){
			$where = 'skill_id = '.$_GET['skill_id'];
			//删除多条,这里的条件统一为ids哟，亲
		}elseif($_GET['ids']){
			$where = 'skill_id in ('.$_GET['ids'].')';
		}
		//输出执行删除后的影响行数，模板上的js 根据这个值来判断是否移聊tr标签到
		//注释中不能打单引，否则去注释的工具失效,蛋痛的工具啊!
		echo  Model::factory('witkey_skill')->setWhere($where)->del();
    }
    function action_save(){
    	$_POST = Keke_tpl::chars($_POST);
    	//防止跨域提交，你懂的
    	Keke::formcheck($_POST['formhash']);
    	//这里怎么说呢，定义生成sql 的字段=>值 的数组，你不懂，就是你太二了.
    	$array = array(
    			'skill_name'=>$_POST['skill_name'],
    			'indus_id'=>$_POST['indus_id'],
    			'listorder' => $_POST['txt_listorder'],
    			'on_time'=>time(),
    	);
    	$skill_id=$_POST['hdn_skill_id'];
    	//这是个隐藏字段，也就是主键的值，这个主键有值，就是要编辑(update)数据到数据库
    	if($skill_id){
    		Model::factory('witkey_skill')->setData($array)->setWhere("skill_id = '$skill_id'")->update();
    		//执行完了，要给一个提示，
    		Keke::show_msg('提交成功','admin/config_skill/add?skill_id='.$skill_id,'success');
    	}else{
    		//这也当然就是添加(insert)到数据库中
    		$cate_id = Model::factory('witkey_skill')->setData($array)->create();
    		
    		Keke::show_msg('提交成功','admin/config_skill/add','success');
    	}
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
