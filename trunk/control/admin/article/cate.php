<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * 后台资讯分类管理控制层
 * @author Administrator
 *
 */
class Control_admin_article_cate extends Controller {

	/**
	 * 文章分类列表
	 */
	function action_index() {
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `art_cat_id`,`art_cat_pid`,`cat_name`,`is_show`,`cat_type`,`art_index`,`listorder`,`on_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('art_cat_id'=>$_lang['id'],'cat_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = BASE_URL."/index.php/admin/article_cate";
		//添加编辑的uri,add这个action 是固定的
		$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'on_time';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		$where .= " and cat_type = 'article' ";
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_article_category')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		$temp_arr = array();
	//var_dump($art_cat_arr);
		Keke::get_tree($list_arr,$temp_arr,'cat','','art_cat_id','art_cat_pid','cat_name');
		$list_arr = $temp_arr;
		unset($temp_arr);
		//var_dump($list_arr);
		//分页数据
		$pages = $data_info['pages'];

		require Keke_tpl::template('control/admin/tpl/article/cate');
	}
	/**
	 * 帮助分类列表
	 */
	function action_help(){
		
	}
	/**
	 * 分类添加
	 */
	function action_add(){
		//始始化全局变量，语言包变量
		global $_K,$_lang;
		$case_id = $_GET['art_cat_id'];
		//如果有值，就进入编辑状态
		if($case_id){
			$case_info = Model::factory('witkey_article_category')->setWhere('art_cat_id = '.$case_id)->query();
			$case_info = $case_info[0];
			$file = pathinfo($case_info['case_img'], PATHINFO_BASENAME);
		}
		//var_dump($case_info);
		//加载模板
		require Keke_tpl::template('control/admin/tpl/article/cate_add');
	}
	/**
	 * 分类保存
	 */
	function action_save(){
		//防止跨域提交，你懂的
		Keke::formcheck($_POST['formhash']);
		 
	    if(!empty($_FILES['fle_case_img']['name'])){
			//上传文件用的，这个对新手来说好使,要就是简单
			$case_img = keke_file_class::upload_file('fle_case_img');
		}
		//这里怎么说呢，定义生成sql 的字段=>值 的数组，你不懂，就是你太二了.
		$array = array('case_title'=>$_POST['txt_task_title'],
				'case_price'=>$_POST['txt_case_price'],
				'case_img'=>$case_img,
				'obj_type' => $_POST['case_type']=='search'?'task':'service',
				'obj_id'=>$_POST['obj_id'],
				'on_time'=>time(),
		);
		//这是个隐藏字段，也就是主键的值，这个主键有值，就是要编辑(update)数据到数据库
		if($_POST['hdn_art_cat_id']){
			Model::factory('witkey_article_category')->setData($array)->setWhere("case_id = '{$_POST['hdn_art_cat_id']}'")->update();
			//执行完了，要给一个提示，这里没有对执行的结果做判断，是想偷下懒，如果执行失败的话，肯定给会报红的。亲!
			Keke::show_msg('系统提示','index.php/admin/article_case/add?art_cat_id='.$_POST['hdn_art_cat_id'],'提交成功','success');
		}else{
			//这也当然就是添加(insert)到数据库中
			Model::factory('witkey_article_category')->setData($array)->create();
			Keke::show_msg('系统提示','index.php/admin/article_case/add','提交成功','success');
		}
	}
	/**
	 * 分类删除
	 */
	function action_del(){
		//删除单条,这里的case_id 是在模板上的请求连接中有的
		if($_GET['art_cat_id']){
			$where = 'art_cat_id = '.$_GET['art_cat_id'];
			//删除多条,这里的条件统一为ids哟，亲
		}elseif($_GET['ids']){
			$where = 'art_cat_id in ('.$_GET['ids'].')';
		}
		echo  Model::factory('witkey_article_category')->setWhere($where)->del();
	}
	 
}
/* $cat_obj = new Keke_witkey_article_category_class ();
$file_obj = new keke_file_class();
$table_obj = new keke_table_class ( "witkey_article_category" );
//所有资讯分类的数组
$cat_all_arr = Keke::get_table_data('*',"witkey_article_category",'','','','','art_cat_id');

$url = "index.php?do=$do&view=$view&type=$type&w[art_cat_pid]={$w[art_cat_pid]}&w[cat_name]={$w[cat_name]}
&$ord[0]={$ord[1]}";

if ($ac == 'del') { //删除
	//var_dump($art_cat_id);die();
	$table_obj->del ( 'art_cat_id', $art_cat_id, $url );
	Keke::admin_show_msg($_lang['delete_success'],'index.php?do=article&view=cat_list&type='.$type,3,'','success');
} elseif (isset ( $sbt_action )) {
	if ($edit_cat_name_arr) { //编辑
		foreach ( $edit_cat_name_arr as $k => $v ) {
			$cat_obj->setWhere ( "art_cat_id = $k" );
			$cat_obj->setCat_name ( $v );
			$cat_obj->edit_keke_witkey_article_category ();

		}

		Keke::admin_system_log ( $_lang['edit_article_category'] );
	} elseif ($add_cat_name_arr) { //删除
			
		foreach ( $add_cat_name_arr as $k => $aindarr ) {
			foreach ( $aindarr as $kk => $v ) {
				if (! $v)
					continue;
				$cat_obj->_art_cat_id = null;
				$cat_obj->setCat_name ( $v );
				$cat_obj->setArt_cat_pid ( $k );
				$cat_obj->setListorder ( $add_cat_name_listarr [$k] [$kk] ? $add_cat_name_listarr [$k] [$kk] : 0 );
				$cat_obj->setOn_time ( time () );
				if($type=='art'){
					$cat_type='article';
				}else{
					$cat_type='help';
				}
				$cat_obj->setCat_type($cat_type);
				$res = $cat_obj->create_keke_witkey_article_category ();
				$res and Dbfactory::execute(sprintf("update %switkey_article_category set art_index = '%s' where art_cat_id = $res ",TABLEPRE,$cat_all_arr[$k]['art_index'].'{'.$res.'}'));
			}
		}
		Keke::admin_system_log ( $_lang['delete_article_cat'] );
	}
	$file_obj->delete_files(S_ROOT."./data/data_cache/");
	$file_obj->delete_files(S_ROOT.'./data/tpl_c/');
	Keke::admin_show_msg ( $_lang['operate_success'], 'index.php?do=' . $do . '&view=' . $view.'&type='.$type,3,'','success' );
} elseif ($ac === 'editlistorder') { //改排序
	if ($iid) {
		$cat_obj->setWhere ( 'art_cat_id=' . $iid );
		$cat_obj->setListorder ( $val );
		$cat_obj->edit_keke_witkey_article_category ();
	}
} else {
	$where = ' 1 = 1 ';
	$types =  array ('help', 'art');
	$type = (! empty ( $type ) && in_array ( $type, $types )) ? $type : 'art';
	switch ( $type ){
		case 'art':
			$art_cat_arr = Keke::get_table_data('*',"witkey_article_category","art_cat_pid =1 or art_cat_id = 1"," art_cat_id desc",'','','art_cat_id',null);
			$where.=" and cat_type='article' ";
			Keke::admin_check_role(14);
			break;
			;
		case 'help':
			$art_cat_arr = Keke::get_table_data('*',"witkey_article_category","cat_type='help'"," art_cat_id desc",'','','art_cat_id',null);
			$where.=" and cat_type='help' ";
			Keke::admin_check_role(44);
	}
	//查询条件
	if (isset ( $sbt_search )) {
		intval ( $w [art_cat_pid] ) and $where .= " and art_cat_pid = $w[art_cat_pid]";
		strval ( $w [cat_name] ) and $where .= " and cat_name like '%$w[cat_name]%'";
		$ord [1] and $where .= " order by $ord[0] $ord[1]";
	}
	//var_dump($where);
	$cat_arr = Keke::get_table_data ( "*", "witkey_article_category", $where, "", "", "", "", 0 );
	sort ( $cat_arr );

	if (! $w) {
		$t_arr = array ();
		Keke::get_tree ( $cat_arr, $t_arr, 'cat', NULL, 'art_cat_id', 'art_cat_pid', 'cat_name' );
		$cat_show_arr = $t_arr;
		//var_dump($t_arr);die();
		unset ( $t_arr );
	} else {
		//sort($indus_arr);
		$cat_show_arr = $cat_arr;
	}
	//var_dump($cat_show_arr);
	//搜索行业下拉菜单
	//	$temp_arr = array ();
	//	$indus_option_arr = Keke::get_industry ();
	//	Keke::get_tree ( $indus_option_arr, $temp_arr, "option", $w [indus_pid] );
	//	$indus_option_arr = $temp_arr;
	//	unset ( $temp_arr );
	//	$indus_index_arr = Keke::get_indus_by_index ();

	$temp_arr = array();
	//var_dump($art_cat_arr);
	Keke::get_tree($art_cat_arr,$temp_arr,'option',$w [art_cat_pid],'art_cat_id','art_cat_pid','cat_name');
	$cat_option_arr = $temp_arr;
	unset($temp_arr);
	$cat_index_arr = get_cat_by_index ();
}

function sortTree($nodeid, $arTree) {
	$res = array ();
	for($i = 0; $i < sizeof ( $arTree ); $i ++)
		if ($arTree [$i] ["indus_pid"] == $nodeid) {
		array_push ( $res, $arTree [$i] );
		$subres = sortTree ( $arTree [$i] ["indus_id"], $arTree );
		for($j = 0; $j < sizeof ( $subres ); $j ++)
			array_push ( $res, $subres [$j] );
	}
	return $res;
}
function get_cat_by_index($cat_type='1', $pid = NULL){
global $Keke;
$cat_index_arr = $Keke->_cache_obj->get ( 'cat_index_arr' . $cat_type . '_' . $pid );
		if (! $cat_index_arr) {
		$cat_arr = get_cat ( $pid );
		$cat_index_arr = array ();
		foreach ( $cat_arr as $cat ) {
		$cat_index_arr [$cat ['art_cat_pid']] [$cat ['art_cat_id']] = $cat;
}
$Keke->_cache_obj->set ( 'cat_index_arr' . $cat_type . '_' . $pid, $cat_index_arr, 3600 );
}
return $cat_index_arr;
}
function get_cat($pid = NULL, $cache = NULL) {

! is_null ( $pid ) and $where = " art_cat_pid = '" . intval ( $pid ) . "'";

$cat_arr = Keke::get_table_data ( '*', "witkey_article_category", $where, "listorder", '', '', 'art_cat_id', $cache );

return $cat_arr;

}
require  $template_obj->template('control/admin/tpl/admin_'. $do .'_'. $view); */
 