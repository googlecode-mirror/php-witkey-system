<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-5-24下午06:08:41
 */
class Control_admin_tool_tag extends Controller{
	/**
	 * 初始化标签列表
	 */
	function action_index(){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
		
		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `tag_id`,`tagname`,`tag_type`,`cache_time`,`tpl_type`,`on_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('tag_id'=>$_lang['id'],'file_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//tool本来是一个目录，由于没有定义tool为目录的路由,所以这个控制层的文件来too_file So这里不能写为tool/file
		$base_uri = BASE_URL."/index.php/admin/tool_tag";
		
		//添加编辑的uri,add这个action 是固定的
		$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'tag_id';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//扩展条件,搜索以外的条件
		$id = $this->request->param('id');
		
		if($id == '1'){
			$where .= " and tagname like '%活动%' ";
		}elseif($id == '2'){
			$where .= " and tagname like '%协议%'  ";
		}
		
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_tag')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		 
		require Keke_tpl::template('control/admin/tpl/tool/tag_list');
		
	}
	/**
	 * 删除标签
	 */
	function action_del(){
		//删除单条,这里的tag_id 是在模板上的请求连接中有的
		if($_GET['tag_id']){
			$where = 'tag_id = '.$_GET['tag_id'];
			//删除多条,这里的条件统一为ids哟，亲
		}elseif($_GET['ids']){
			$where = 'tag_id in ('.$_GET['ids'].')';
		}
		//输出执行删除后的影响行数，模板上的js 根据这个值来判断是否移聊tr标签到
		//注释中不能打单引，否则去注释的工具失效,蛋痛的工具啊!
		echo  Model::factory('witkey_tag')->setWhere($where)->del();
	}
	/**
	 * 初始化标签添加模板
	 */
	function action_add(){
		//始始化全局变量，语言包变量
		global $_K,$_lang;
		$tag_id = $_GET['tag_id'];
		//如果有值，就进入编辑状态
		if($tag_id){
			$tag_info = Model::factory('witkey_tag')->setWhere('tag_id = '.$tag_id)->query();
			$tag_info = $tag_info[0];
		}
		 
		//加载模板，这有点费J8话,地球人都懂的
		require Keke_tpl::template('control/admin/tpl/tool/tag_add');
	}
	/**
	 * 保成标签模板
	 */
	function action_save(){
		//防止跨域提交，你懂的
		Keke::formcheck($_POST['formhash']);
		//这里怎么说呢，定义生成sql 的字段=>值 的数组，你不懂，就是你太二了.
		$array = array('tagname'=>$_POST['txt_tagname'],
				'tag_code'=>$_POST['tar_custom_code'],
				'cache_time' => $_POST['txt_cache_time'],
				'on_time'=>time(),
		);
		//这是个隐藏字段，也就是主键的值，这个主键有值，就是要编辑(update)数据到数据库
		if($_POST['hdn_tag_id']){
			Model::factory('witkey_tag')->setData($array)->setWhere("tag_id = '{$_POST['hdn_tag_id']}'")->update();
			//执行完了，要给一个提示，这里没有对执行的结果做判断，是想偷下懒，如果执行失败的话，肯定给会报红的。亲!
			Keke::show_msg('系统提示','index.php/admin/tool_tag/add?tag_id='.$_POST['hdn_tag_id'],'提交成功','success');
		}else{
			//这也当然就是添加(insert)到数据库中
			Model::factory('witkey_tag')->setData($array)->create();
			Keke::show_msg('系统提示','index.php/admin/tool_tag/add','提交成功','success');
		}
	}
	/**
	 * 标签预览
	 */
	function action_preview(){
		$tagid = $_GET['tag_id'];
		
		$taglist =keke_loaddata_class::get_tag(1);
		$tag_info = $taglist[$tagid];
		if($tag_info['tag_type']==9){
			//预览广告
			keke_loaddata_class::preview_addgroup($tag_info['tagname'],$tag_info['loadcount']);
		}elseif($tag_info['tag_type']==5){
			//预览自定义代码 
			keke_loaddata_class::previewtag($tag_info);
		}
		 
	}
	
}

/* 
Keke::admin_check_role (29);
$tag_list = Keke::get_tag ();
$tag_obj = new Keke_witkey_tag_class ();
//$tag_type_arr = keke_glob_class::get_tag_type ();
$t    = max($t,0);

$slt_page_size and $slt_page_size=intval ( $slt_page_size ) or $slt_page_size = 10;
$page and $page=intval ( $page ) or $page = 1; 
$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&ord=$ord&tag_type=$tag_type&tpl_type=$tpl_type&type=$type&txt_title=$txt_title";
if ($op == 'del') {
	$delid = $delid ? $delid : Keke::admin_show_msg ($_lang['wrong_parameters'], $url,3,'','warning' );	
	$tag_obj->setWhere ( "tag_id='{$delid}'" );
	$tag_obj->del_keke_witkey_tag ();
	$Keke->_cache_obj->del ( 'tag_list_cache' );
	Keke::admin_system_log ( $_lang['delete_tag']."$delid" );
	Keke::admin_show_msg ($_lang['operate_success'], $url,3,'','success' );
} elseif (isset ( $sbt_action )) { //批量操作	
	if (is_array ( $ckb )) {
		$ids = implode ( ',', array_filter ( $ckb ) );
	}
	if (count ( $ids )) {
		$tag_obj->setWhere ( ' tag_id in (' . $ids . ') ' );
		$tag_obj->del_keke_witkey_tag ();
		$Keke->_cache_obj->del ( 'tag_list_cache' );
		Keke::admin_system_log ($_lang['delete_tag']. "$ids" );
		Keke::admin_show_msg ($_lang['mulit_operate_success'], $url,3,'','success' );
	} else {
		Keke::admin_show_msg ( $_lang['choose_operate_item'], $url,3,'','warning' );
	}
} else {
	
	//默认查询条件
	$where = " tag_type=5  ";
	$type or $type = 1;
	//var_dump($type);
	
	if($type==1){
	   $where .=" and tagname like '%活动%' ";
	}elseif($type==2){
	   $where .=" and tagname like '%协议%' ";
	}else{
	    $where .=" and tagname like '%任务%' ";
	}
	//$where .= " and tag_type=$tag_type ";
	strval ( $txt_title ) and $where .= " and tagname like '%$txt_title%' ";
	
	$ord ['1'] and $where .= " order by". $ord['0']. $ord['1'];	
	$t_obj = keke_table_class::get_instance ( "witkey_tag" );
	$tag_type=5;
	$d = $t_obj->get_grid ( $where, $url, $page, $slt_page_size );
	$tag_arr = $d ['data'];	
	$pages = $d ['pages'];
}

require $template_obj->template ( 'control/admin/tpl/admin_tpl_' . $view ); */