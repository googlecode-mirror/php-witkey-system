<?php
	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2011-9-2
*/
class Control_admin_tool_feed extends Controller{
	function action_index(){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
		
		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `feed_id`,`title`,`feedtype`,`username`,`feed_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('feed_id'=>$_lang['id'],'username'=>$_lang['name'],'feed_time'=>$_lang['time']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//tool本来是一个目录，由于没有定义man为目录的路由,所以这个控制层的文件来tool_feed So这里不能写为tool/feed
		$base_uri = BASE_URL."/index.php/admin/tool_feed";
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'feed_time';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_feed')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		
		//分页数据
		$pages = $data_info['pages'];
		//$file_type_arr = keke_global_class::get_file_type();
		$feed_type = keke_global_class::get_feed_type ();
		require Keke_tpl::template('control/admin/tpl/tool/feed');
	}
	/**
	 * 这里是删除的action 主要是处理要单条删除
	 * 与多条删除。
	 * 规矩，删除action的名称统一del,不要问为什么
	 * 单条删除，传主键名称与值就可以删除了
	 * 多条删除的，是前端js拼接好的ids传过来的值.js 只传ids 的哟。不要写成主键名称
	 *
	 */
	function action_del(){
		//删除单条,这里的feed_id 是在模板上的请求连接中有的
		if($_GET['feed_id']){
			$where = 'feed_id = '.$_GET['feed_id'];
			//删除多条,这里的条件统一为ids哟，亲
		}elseif($_GET['ids']){
			$where = 'feed_id in ('.$_GET['ids'].')';
		}
		//输出执行删除后的影响行数，模板上的js 根据这个值来判断是否移聊tr标签到
		//注释中不能打单引，否则去注释的工具失效,蛋痛的工具啊!
		echo  Model::factory('witkey_feed')->setWhere($where)->del();
	}
}


/* Keke::admin_check_role (57);

$feed_obj = new Keke_witkey_feed_class ();

$tag_obj = new Keke_witkey_tag_class ();
$feed_type = keke_glob_class::get_feed_type ();

$template_arr = Dbfactory::query ( " select tpl_title from " . TABLEPRE . "witkey_template", 1, null );

$type or $type = 'data';
intval ( $slt_page_size ) or $slt_page_size = 10;
intval ( $page ) or $page = 1;

if ($type == 'data' || ! isset ( $type )) {
	$where = " 1 = 1 ";
	$txt_feed_id and $where .= " and feed_id=$txt_feed_id ";
	$txt_title and $where .= " and title like '%" . $txt_title . "%' ";
} elseif ($type === 'manage') {
	//获取广告组
	$where = " 1=1 and tag_type=8 ";
	//查询条件
	$txt_tag_id and $where .= " and tag_id = $txt_tag_id ";
	$tpl_type or $tpl_type = $_K [template];
	$tpl_type == 1 or $where .= " and tpl_type like '%" . $tpl_type . "%' ";

}

if($ord [1]){
	$where .= " order by $ord[0] $ord[1] ";
}else{
	$where .= " order by feed_time desc";
}
if ($type == 'data' || ! isset ( $type )) {
	$feed_obj->setWhere ( $where );
	$count = $feed_obj->count_keke_witkey_feed ();
}
if ($type == 'manage') {
	$tag_obj->setWhere ( $where );
	$count = $tag_obj->count_keke_witkey_tag ();
}
//分页条件
if ($type === 'manage') {
	$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&feedtype=$feedtype&tpl_type=$tpl_type&txt_tag_id=$txt_tag_id&type=$type&ord[]=$ord[0]&ord[]=$ord[1]";
} else {
	$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&feedtype=$feedtype&tpl_type=$tpl_type&txt_feed_id=$txt_feed_id&type=$type&ord[]=$ord[0]&ord[]=$ord[1]";
}

$limit = $slt_page_size;
$Keke->_page_obj->setAjax(1);
$Keke->_page_obj->setAjaxDom("ajax_dom");
$pages = $Keke->_page_obj->getPages ( $count, $limit, $page, $url );

//查询结果数组
if ($type == 'data' || ! isset ( $type ) || $type == '') {
	$feed_obj->setWhere ( $where . $pages [where] );
	$feed_arr = $feed_obj->query_keke_witkey_feed ();
}
if ($type == 'manage') {
	$tag_obj->setWhere ( $where . $pages [where] );
	$feed_arr = $tag_obj->query_keke_witkey_tag ();
}
//var_dump($feed_arr);
foreach ($feed_arr as $k=>$v) {
	$title_arr = unserialize($v[title]);
	$title_str =' <a href="../../'.$title_arr[feed_username][url].'" target="_blank">'.$title_arr[feed_username][content].'</a>'.$title_arr[action][content].'
		<a href="../../'.$title_arr[event][url].'" target="_blank">'.$title_arr[event][content].'</a>';
	$v[title] = $title_str;
	$new_feed_arr[] = $v;
}

$feed_arr = $new_feed_arr;


if ($ac == 'del') {
	$delid or Keke::admin_show_msg ( $_lang['err_parameter'], $url,3,'','warning' );
	if ($type == 'data' || ! isset ( $type ) || $type == '') {
		$feed_obj->setWhere ( "feed_id='{$delid}'" );
		$res = $feed_obj->del_keke_witkey_feed ();
	} else if ($type == 'manage') {
		$tag_obj->setWhere ( "tag_id='{$delid}'" );
		$res = $tag_obj->del_keke_witkey_tag ();
	}
	if ($res) {
		Keke::admin_show_msg ( $_lang['delete_success'], $url ,3,'','success' );
	} else {
		Keke::admin_show_msg ( $_lang['delete_fail'], $url ,3,'','warning' );
	}
}

//批量操作
if (isset ( $sbt_action ) && $sbt_action == $_lang['mulit_delete']) {
	if (is_array ( $ckb )) {
		$ids = implode ( ',', $ckb );
	}
	if ($ids) {

		if ($type == 'data' || ! isset ( $type ) || $type == '') {
			$feed_obj->setWhere ( ' feed_id in (' . $ids . ') ' );
			$res = $feed_obj->del_keke_witkey_feed ();
		} else if ($type == 'manage') {
			$tag_obj->setWhere ( ' tag_id in(' . $ids . ')' );
			$res = $tag_obj->del_keke_witkey_tag ();
		}
		if ($res) {
			Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url,3,'','success' );
		} else {
			Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url ,3,'','warning');
		}

	} else {
		Keke::admin_show_msg ( $_lang['choose_operate_item'], $url ,3,'','warning');
	}
}

require $Keke->_tpl_obj->template ( 'control/admin/tpl/admin_tpl_' . $view . '_' . $type ); */