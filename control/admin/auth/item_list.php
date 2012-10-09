<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * @todo 后台认证项目安装、删除
 * 2011-9-01 11:35:13
 */

Keke::admin_check_role ( 38 );
class Control_admin_auth_item_list extends Controller{

	function action_index(){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;

		//要显示的字段,即SQl中SELECT要用到的字段
		$fields = ' `auth_code`,`auth_title`,`auth_day`,`auth_cash`,`auth_expir`,`auth_open`,`update_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('auth_code'=>$_lang['id'],'auth_title'=>$_lang['username'],'update_time'=>$_lang['order_type']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//finance本来是一个目录，由于没有定义tool为目录的路由,所以这个控制层的文件来finance_recharge So这里不能写为finance/recharge
		$base_uri = BASE_URL."/index.php/admin/auth_item_list";

		//添加编辑的uri,add这个action 是固定的
		//$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//默认排序字段，这里按时间降序
		$this->_default_ord_field = 'update_time';
		//这里要口水一下，get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_auth_item')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		//用户组
		$group_arr = keke_admin_class::get_user_group ();

		//var_dump($list_arr);die;
		require Keke_tpl::template('control/admin/tpl/auth/item_list');

	}

}
/* $auth_item_obj = new Keke_witkey_auth_item_class ();
$url = "index.php?do=$do&view=$view";

//删除认证项目
if ($ac === 'del') {
	keke_auth_fac_class::del_auth ( $auth_code, 'auth_item_cache_list' ); //单条删除
	Keke::admin_system_log ( $_lang['delete_auth'] . $auth_code ); //日志记录
} elseif (isset ( $sbt_add )) {
	keke_auth_fac_class::install_auth ( $auth_dir ); //增加认证项目
	Keke::admin_system_log ( $_lang['add_auth'] . $auth_dir ); //日志记录
} elseif (isset ( $sbt_action ) && $sbt_action === $_lang['mulit_delete']) { //批量删除
	keke_auth_fac_class::del_auth ( $ckb, 'auth_item_cache_list' ); //批量操作
	Keke::admin_system_log ( $_lang['mulit_delete_auth'] . $ckb );
} else {
	$where = ' 1 = 1  ';
	intval ( $page_size ) or $page_size = 10 and $page_size = intval ( $page_size );
	$auth_item_obj->setWhere ( $where );
	$count = $auth_item_obj->count_keke_witkey_auth_item ();
	$page or $page = 1 and $page = intval ( $page );
	$Keke->_page_obj->setAjax(1);
	$Keke->_page_obj->setAjaxDom("ajax_dom");
	$pages = $Keke->_page_obj->getPages ( $count, $page_size, $page, $url );
	$where .= " order by listorder asc ";
	$auth_item_obj->setWhere ( $where . $pages ['where'] );
	$auth_item_arr = $auth_item_obj->query_keke_witkey_auth_item ();
}

require $Keke->_tpl_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */