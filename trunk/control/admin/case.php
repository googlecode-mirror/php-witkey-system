<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

class Control_admin_case extends Controller{
	
	function action_index(){
		global $_K,$_lang;
		$fields = ' `case_id`,`obj_id`,`obj_type`,`case_img`,`case_title`, ';
		$fields  .= '`case_price`,`on_time`';
		
		extract($_GET);
		$count = intval($count);
		//基本uri
		$base_uri = BASE_URL."/index.php/admin/case";
		//添加编辑的uri
		$add_uri =  $base_uri.'/add';
		//删除uri
		$del_uri = $base_uri.'/del';
		
		//通过uri 记住查询条件,访止条件丢失，结果不对
		$query_uri .= "?txt_case_id=$txt_case_id";
		$query_uri .= "&txt_case_title=$txt_case_title";
		$query_uri .= "&slt_obj_type=$slt_obj_type";
		$query_uri .= "&ord[]=$ord[0]&ord[]=$ord[1]";
		$query_uri .= "&slt_page_size=$slt_page_size";
		
		//查询uri
		$uri = $base_uri.$query_uri;
		$add_uri .= $query_uri.'&page='.$page;
		$del_uri .= $query_uri.'&page='.$page;
		
		//初始化where的值
		$where = ' 1=1 ';
		if($txt_case_id){
			$where .= ' and case_id = '.$txt_case_id;
		}
		if($txt_case_title){
			$where .= " and case_title like '%$txt_case_title%' ";
		}
		//初始化order的值
		if($ord[0]){
			$order = ' order by '.$ord[0] .' '. $ord[1];
		}else{
			$order = ' order by case_id desc' ;
		}
		
		$data_info = Model::factory('witkey_case')->get_grid($fields,$where,$uri,$order,$page,$count,$slt_page_size);
		
		$case_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('control/admin/tpl/case');
	}
	
	function action_add(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/case_add');
		
	}
	function action_del(){
		//删除单条
		if($_GET['case_id']){
			$where = 'case_id = '.$_GET['case_id'];
			//删除多条
		}elseif($_GET['case_ids']){
			$where = 'case_id in ('.$_GET['case_ids'].')';
		}
		echo  Model::factory('witkey_case')->setWhere($where)->del();
	}
	
}