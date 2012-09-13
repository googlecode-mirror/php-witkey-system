<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * 后台案例管理控制层
 * @author Administrator
 *
 */
class Control_admin_article_case extends Controller {
	function before(){
		 
	}
	function action_index() {
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `case_id`,`obj_id`,`obj_type`,`case_img`,`case_title`,`case_desc`,`case_price`,`on_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('case_id'=>$_lang['id'],'case_title'=>$_lang['name'],'on_time'=>$_lang['time']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = BASE_URL."/index.php/admin/article_case";
		//添加编辑的uri,add这个action 是固定的
		$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'on_time';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_case')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];

		require Keke_tpl::template('control/admin/tpl/article/case');
	}
	function action_add(){
		//始始化全局变量，语言包变量
		global $_K,$_lang;
		$case_id = $_GET['case_id'];
		//如果有值，就进入编辑状态
		if($case_id){
			$case_info = Model::factory('witkey_case')->setWhere('case_id = '.$case_id)->query();
			$case_info = $case_info[0];
			$file = pathinfo($case_info['case_img'], PATHINFO_BASENAME);
		}
		//加载模板
		require Keke_tpl::template('control/admin/tpl/article/case_add');
	}
	function action_save(){
		//防止跨域提交，你懂的
		Keke::formcheck($_POST['formhash']);
		//这里是业务判断,连接是图片还有url地址
		if($_POST['showMode'] ==1){
			$link_pic = $_POST['txt_link_pic'];
		}elseif(!empty($_FILES['fle_link_pic']['name'])){
			//上传文件用的，这个对新手来说好使,要就是简单
			$link_pic = keke_file_class::upload_file('fle_link_pic');
		}
		//这里怎么说呢，定义生成sql 的字段=>值 的数组，你不懂，就是你太二了.
		$array = array('link_name'=>$_POST['txt_link_name'],
				'link_url'=>$_POST['txt_link_url'],
				'link_pic'=>$link_pic,
				'listorder' => $_POST['txt_listorder'],
				'on_time'=>time(),
		);
		//这是个隐藏字段，也就是主键的值，这个主键有值，就是要编辑(update)数据到数据库
		if($_POST['hdn_case_id']){
			Model::factory('witkey_case')->setData($array)->setWhere("case_id = '{$_POST['hdn_case_id']}'")->update();
			//执行完了，要给一个提示，这里没有对执行的结果做判断，是想偷下懒，如果执行失败的话，肯定给会报红的。亲!
			Keke::show_msg('系统提示','index.php/admin/article_case/add?case_id='.$_POST['hdn_case_id'],'提交成功','success');
		}else{
			//这也当然就是添加(insert)到数据库中
			Model::factory('witkey_link')->setData($array)->create();
			Keke::show_msg('系统提示','index.php/admin/article_case/add','提交成功','success');
		}
	}
	function action_del(){
		//删除单条,这里的case_id 是在模板上的请求连接中有的
		if($_GET['case_id']){
			$where = 'case_id = '.$_GET['case_id'];
			//删除多条,这里的条件统一为ids哟，亲
		}elseif($_GET['ids']){
			$where = 'case_id in ('.$_GET['ids'].')';
		}
		//输出执行删除后的影响行数，模板上的js 根据这个值来判断是否移聊tr标签到
		//注释中不能打单引，否则去注释的工具失效,蛋痛的工具啊!
		echo  Model::factory('witkey_case')->setWhere($where)->del();
	}
	function action_search(){
		global $_K,$_lang;
		$model_type_arr  = keke_global_class::get_task_type();
		/* Keke::$_page_obj->setAjax(1);
		Keke::$_page_obj->setAjaxDom('ajax_dom'); */
		$search_type = $_GET['search_type'];
		$search_id = $_GET['search_id'];
		$page_size = $_GET['page_size'];
		$fields = ' * ';
		//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = BASE_URL."/index.php/admin/article_case/search";
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
			//要查询的字段,在模板中显示用的
			$query_fields = array('task_id'=>$_lang['id'],'task_title'=>$_lang['name'],'start_time'=>$_lang['time']);
			//默认排序字段，这里按时间降序
			$this->_default_ord_field = 'start_time';
			//这里要口水一下，get_url就是处理查询的条件
			extract($this->get_url($base_uri));
			//已经结束的任务
			$where .= ' and task_status = 8 ';
		    
			//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
			$data_info = Model::factory('witkey_task')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
			//列表数据
			$list_arr = $data_info['data'];
			//分页数据
			$pages = $data_info['pages'];
 
		 
		
		require Keke_tpl::template ( 'control/admin/tpl/article/case_search' );
	}
	
	function action_search_service(){
		global $_K,$_lang;
		//$model_type_arr  = keke_global_class::get_task_type();
		/* Keke::$_page_obj->setAjax(1);
		 Keke::$_page_obj->setAjaxDom('ajax_dom'); */
		$search_type = $_GET['search_type'];
		$search_id = $_GET['search_id'];
		$page_size = $_GET['page_size'];
		$fields = ' * ';
		//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = BASE_URL."/index.php/admin/article_case/search_service";
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
	 
		 
			//要查询的字段,在模板中显示用的
			$query_fields = array('service_id'=>$_lang['id'],'title'=>$_lang['name'],'on_time'=>$_lang['time']);
			//默认排序字段，这里按时间降序
			$this->_default_ord_field = 'on_time';
			//这里要口水一下，get_url就是处理查询的条件
			extract($this->get_url($base_uri));
			//已经结束的任务
			$where .= ' and service_status != 1 ';
				
			 
			//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
			$data_info = Model::factory('witkey_service')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
			//列表数据
			$list_arr = $data_info['data'];
			//分页数据
			$pages = $data_info['pages'];
		
		 
		
		require Keke_tpl::template ( 'control/admin/tpl/article/case_search' );
	}
}


/* $case_obj = new Keke_witkey_case_class ();
//分页

$w ['page_size'] and $page_size = intval ( $w ['page_size'] ) or $page_size =10;
$page and $page = intval ( $page ) or $page = '1';
$url = "index.php?do=$do&view=$view&w[case_id]=".$w['case_id']."&w[art_title]=".$w['art_title']."&w[case_auther]=".
		$w['case_auther']."&w[obj_type]=".$w['obj_type']."&w[page_size]=$page_size&w[ord]=".$w['ord']."&page=$page";

if (isset ( $ac )) { //单个删除
	if ($case_id) {
		switch ($ac) {
			case "del" :
				$case_obj->setWhere ( 'case_id=' . $case_id );
				$res = $case_obj->del_keke_witkey_case ();
				Keke::admin_system_log( $_lang['delete_case'].':' . $case_id );//日志记录
				$res and Keke::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['delete_fail'], $url,3,'','warning' );
				break;
		}
	} else {
		Keke::admin_show_msg ( $_lang['del_fail_select_operate'], $url );
	}
} elseif (isset ( $sbt_action )) { //批量删除
	$ckb_string = $ckb;
	is_array ( $ckb_string ) and $ckb_string = implode ( ',', $ckb_string );
	if (count ( $ckb_string )) {
		$case_obj->setWhere ( 'case_id in (' . $ckb_string . ')' );
		$res = $case_obj->del_keke_witkey_case ();//删除
		Keke::admin_system_log($_lang['mulit_delete_case'].':' . $ckb_string );//日志记录
		$res and Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url ,3,'','success') or Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url,3,'','warning' );
	} else
		Keke::admin_show_msg ( $_lang['mulit_del_fail_select_operate'], $url,3,'','warning' );
} else {

	$model_list = Keke::get_table_data ( '*', 'witkey_model', "model_status=1 and model_dir!='employtask'", 'listorder asc ', '', '', 'model_id', null );
	$count = $case_obj->count_keke_witkey_case();

	//	$sql = "select *,ifnull(case_title,task_title) task_title from ".TABLEPRE."witkey_case as a left join ".TABLEPRE."witkey_task as b on a.obj_id = b.task_id ";
	$sql = "select * from ".TABLEPRE."witkey_case";
	$where = ' where 1 = 1'; //查询
	//条件
	$w ['case_id'] and $where .= " and case_id = '".$w['case_id']."' ";
	$w ['art_title'] and $where .= " and case_title like '%".$w['art_title']."%' ";
	$w ['case_auther'] and $where .= " and case_auther like '%".$w['case_auther']."%' ";
	$w ['obj_type'] and $where .= " and obj_type = '".$w['obj_type']."' ";

	$order_where = " order by on_time desc";
	is_array($w['ord']) and $order_where= ' order by '.$w['ord']['0'].' '.$w['ord']['1'];
	$where=$where.$order_where;

	//$w ['ord'] and $where .= " order by $w['ord']" or $where .= " order by case_id desc";//排序
	$Keke->_page_obj->setAjax(1);
	$Keke->_page_obj->setAjaxDom("ajax_dom");
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url );
	$sql.=$where.$pages['where'];
	$case_arr =Dbfactory::query($sql);

}

//add
$case_obj = new Keke_witkey_case_class ();
$task_obj = new Keke_witkey_task_class ();
$case_id and $case_info = Dbfactory::get_one ( " select * from " . TABLEPRE . "witkey_case where case_id ='$case_id'" );
$txt_task_id and $case_info = Dbfactory::get_one ( " select * from " . TABLEPRE . "witkey_task where task_id = '$txt_task_id'" );

$url ="index.php?do=case&view=list" ;
if ($ac == 'ajax' && $id&&$obj) {
	case_obj_exists ( $id, $obj ) and Keke::echojson ( $_lang['echojson_msg'],1 ) or Keke::echojson ( $_lang['echojosn_erreor_msg'],0 );
}

if (isset ( $sbt_edit )) { 

	if ($hdn_case_id) {
		$case_obj->setCase_id ( $hdn_case_id );
	}else{
			if (case_obj_exists($fds['obj_id'],$case_type)) {
			$case_obj->setObj_id ( $fds ['obj_id'] );
			}
	}
	
	//var_dump($_POST);die();
	//var_dump($_FILE);die();
	$case_obj->setObj_type ( $case_type );
	$case_obj->setCase_auther ( $fds ['case_auther'] );
	$case_obj->setCase_price ( $fds ['case_price'] );
	$case_obj->setCase_desc ( Keke::escape($fds ['case_desc']) );
	$case_obj->setCase_title ( Keke::escape($fds ['case_title']) );
	$case_obj->setOn_time ( time () );
	$case_img = $hdn_case_img or ($case_img = keke_file_class::upload_file ( "fle_case_img" ));
	$case_obj->setCase_img ($case_img );
	
	if ($hdn_case_id) {
		$res = $case_obj->edit_keke_witkey_case ();
		Keke::admin_system_log ( $_lang['edit_case'].':' . $hdn_case_id ); 
		$res and Keke::admin_show_msg ( $_lang['modify_case_success'], 'index.php?do=case&view=lise',3,'','success' ) or Keke::admin_show_msg ( $_lang['modify_case_fail'], 'index.php?do=case&view=lise',3,'','warning' );
	}else{
		$res = $case_obj->create_keke_witkey_case ();
		Keke::admin_system_log ( $_lang['add_case'] ); 
		$res and Keke::admin_show_msg ( $_lang['add_case_success'],'index.php?do=case&view=lise',3,'','success' ) or Keke::admin_show_msg ( $_lang['add_case_fail'],'index.php?do=case&view=add',3,'','warning' );
	}
}
function case_obj_exists($id, $obj = 'task') {
	if ($obj == 'task') {
		$search_obj = Dbfactory::get_count ( sprintf ( "select count(task_id) from %switkey_task where task_id='%d' ", TABLEPRE, $id ) );
	} elseif ($obj =='service') {
		$search_obj = Dbfactory::get_count ( sprintf ( "select count(service_id) from %switkey_service where service_id='%d' ", TABLEPRE, $id ) );
	}
	if ($search_obj) {
		return true;
	} else {
		return false;
	}
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */