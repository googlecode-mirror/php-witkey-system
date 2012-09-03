<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
class Control_admin_link extends \Controller {
	
	//友链管理
	function action_index() {
		global $_K,$_lang;
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		extract($_GET);
		$count = intval($count);
		
		$uri = BASE_URL."/index.php/admin/link";
		//通过uri 记住查询条件,访止条件丢失，结果不对
		$uri .= "?txt_link_id=$txt_link_id";
		$uri .= "&txt_link_name=$txt_link_name";
		$uri .= "&ord[]=$ord[0]&ord[]=$ord[1]";
		$uri .= "&page_size=$slt_page_size";
		
		$where = ' 1=1 ';
		if($txt_link_id){
			$where .= ' and link_id = '.$txt_link_id;
		}
		if($txt_link_name){
			$where .= " and link_name like '%$txt_link_name%' ";
		}
		
		if($ord[0]){
			$order = ' order by '.$ord[0] .' '. $ord[1];
		}else{
			$order = ' order by link_id desc' ;
		}
		
		
		$data_info = Model::factory('witkey_link')->get_grid($fields,$where,$uri,$order,$page,$count,$slt_page_size);
		
		$link_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		//echo Database::instance()->get_query_num();
		require Keke_tpl::template('control/admin/tpl/link');
	}
	//添加与编辑
	function action_add(){
		global $_K,$_lang;
		require Keke_tpl::template('control/admin/tpl/link_add');
	}
	
}
