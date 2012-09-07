<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
class Control_admin_link extends Controller {
	
	//友链管理
	function action_index() {
		global $_K,$_lang;
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		extract($_GET);
		$count = intval($count);
		//基本uri
		$base_uri = BASE_URL."/index.php/admin/link";
		//添加编辑的uri
		$add_uri =  $base_uri.'/add';
		//删除uri
		$del_uri = $base_uri.'/del';
		
		//通过uri 记住查询条件,访止条件丢失，结果不对
		$query_uri .= "?txt_case_id=$txt_case_id";
		$query_uri .= "&txt_case_name=$txt_case_name";
		$query_uri .= "&ord[]=$ord[0]&ord[]=$ord[1]";
		$query_uri .= "&slt_page_size=$slt_page_size";
		
		//查询uri 
		$uri = $base_uri.$query_uri;
		$add_uri .= $query_uri.'&page='.$page;
		$del_uri .= $query_uri.'&page='.$page;
		
		//初始化where的值
		$where = ' 1=1 ';
		if($txt_link_id){
			$where .= ' and link_id = '.$txt_link_id;
		}
		if($txt_link_name){
			$where .= " and link_name like '%$txt_link_name%' ";
		}
		//初始化order的值
		if($ord[0]){
			$order = ' order by '.$ord[0] .' '. $ord[1];
		}else{
			$order = ' order by link_id desc' ;
		}
		$data_info = Model::factory('witkey_link')->get_grid($fields,$where,$uri,$order,$page,$count,$slt_page_size);
		
		$link_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		//var_dump(Database::instance()->get_last_query());
		//echo Database::instance()->get_query_num();
		
		require Keke_tpl::template('control/admin/tpl/link');
	}
	//添加与编辑初始化
	function action_add(){
		global $_K,$_lang;
		$link_id = $_GET['link_id'];
		//如果有值，就进入编辑状态
		if($link_id){
			$link_info = Model::factory('witkey_link')->setWhere('link_id = '.$link_id)->query();
			$link_info = $link_info[0];
			$link_pic = $link_info['link_pic'];
		}
		
		if(strpos($link_pic, 'http')!==FALSE){
			//远程地址
			$mode = 1;
		}else{
			//本地图片
			$mode = 2;
		}
		require Keke_tpl::template('control/admin/tpl/link_add');
	}
	//保存数据
	function action_save(){
		Keke::formcheck($_POST['formhash']);
		if($_POST['showMode'] ==1){
			$link_pic = $_POST['txt_link_pic'];
		}elseif(!empty($_FILES['fle_link_pic']['name'])){
			$link_pic = keke_file_class::upload_file('fle_link_pic');
		}
		$array = array('link_name'=>$_POST['txt_link_name'],
				       'link_url'=>$_POST['txt_link_url'],
					   'link_pic'=>$link_pic,
					   'listorder' => $_POST['txt_listorder'],				  
				);

		if($_POST['hdn_link_id']){
			Model::factory('witkey_link')->setData($array)->setWhere("link_id = '{$_POST['hdn_link_id']}'")->update();
			Keke::show_msg('系统提示','index.php/admin/link/add?link_id='.$_POST['hdn_link_id'],'提交成功','success');
		}else{
			Model::factory('witkey_link')->setData($array)->create();
			Keke::show_msg('系统提示','index.php/admin/link/add','提交成功','success');
		}
		
		
	}
	//删除
	function action_del(){
		//删除单条
		if($_GET['link_id']){  
			$where = 'link_id = '.$_GET['link_id'];
		//删除多条	
		}elseif($_GET['link_ids']){
			$where = 'link_id in ('.$_GET['link_ids'].')';
		}
		echo  Model::factory('witkey_link')->setWhere($where)->del();
	}
	
}
