<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

class Control_admin_link extends Controller {
	
	//友链管理
	function action_index() {
		global $_K,$_lang;
		
		//要显示的字段,即SQl中要用到的字段
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('link_id'=>$_lang['id'],'link_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//总记录数
		$count = intval($_GET['count']);
		//基本uri
		$base_uri = BASE_URL."/index.php/admin/link";
		//添加编辑的uri
		$add_uri =  $base_uri.'/add';
		//删除uri
		$del_uri = $base_uri.'/del';
		
	    extract($this->get_url($base_uri));

		$data_info = Model::factory('witkey_link')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$link_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		//查询当前的Sql
		//var_dump(Database::instance()->get_last_query());
		//查询当的sql数量
		//echo Database::instance()->get_query_num();
		
		require Keke_tpl::template('control/admin/tpl/link');
	}
	/**
	 * 
	 * @param string $base_uri
	 * @return multitype:string number
	 */
	function get_url($base_uri){
		$r = array();
		//初始化where的值
		$where = ' 1=1 ';
		$query_uri = '?';
		//字段与条件
		if($_GET['slt_fields']  and $_GET['txt_condition']){
			//时间的查询处理
			if(strtotime($_GET['txt_condition'])){
				//字段值为时间时
				$c =  $_GET['txt_condition'];
				//这里的数据库中的on_time 字段必须是时间戳
				$f =  "FROM_UNIXTIME(`{$_GET['slt_fields']}`,'%Y-%m-%d')";
				
			}else{
				//非时间的条件
				$c = $_GET['txt_condition'];
				$f = "`{$_GET['slt_fields']}`";
			}
			//如果是like 条件的值要加%
			if($_GET['slt_cond']=='like'){
				$c = "%$c%";
			}
			//拼接url字段
			$where .= "and $f {$_GET['slt_cond']} '$c'";
			
			$query_uri .= "slt_cond={$_GET['slt_cond']}";
			$query_uri .= "&slt_fields={$_GET['slt_fields']}&txt_condition={$_GET['txt_condition']}";
		}
		if($_GET['page_size']){
			$query_uri .= '&page_size='.$_GET['page_size'];
		}
		//页数
		$_GET['page'] and $page = $_GET['page'] or $page = 1;
		
		//排序的uri,f表示要排序的字段
		if($_GET['f']){
			$query_uri .= '&f='.$_GET['f'].'&ord='.$_GET['ord'];
		}
		//查询uri
		$uri = $base_uri.$query_uri;
		//排序标记，定义js 中的变量
		//降序
		if(isset($_GET['ord']) and $_GET['ord']==1){
			$ord_tag = 0;
			$ord_char = '↓';
			//升序
		}elseif(isset($_GET['ord']) and $_GET['ord']==0){
			$ord_tag = 1;
			$ord_char = '↑';
		}else{
			//默认不显示
			$ord_tag = 0;
			$ord_char = '';
		}
		
		
		//排序的条件
		if(isset($_GET['f'])){
			$t = $ord_tag==1?'desc':'asc';
			$order = " order by {$_GET['f']} $t ";
		}
		$r['where'] = $where;
		//$r['query_uri'] =$query_uri;
		$r['uri'] = $uri;
		$r['ord_tag']=$ord_tag;
		$r['ord_char']=$ord_char;
		$r['order'] = $order;
		$r['page']=$page;
		return $r;		
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
