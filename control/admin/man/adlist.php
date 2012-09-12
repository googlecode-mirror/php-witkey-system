<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台广告列表显示页面
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * @date 2011-12-21 下午05:54:07
 * @encoding GBK
*/
class Control_admin_man_adlist extends Controller{
	function action_index(){
		//定义全局变量，加载模板和语言包
		global $_K,$_lang;
		//需要查询的字段
		$fields = '`ad_id`,`ad_name`,`target_id`,`start_time`,`end_time`,`on_time`';
		//在搜索中要显示的字段
		$query_fields = array('ad_id'=>$_lang['id'],'ad_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//页面uri
		$base_uri = BASE_URL.'/index.php/admin/man_adlist';
		//删除uri
		$edit_uri = $base_uri.'/edit';
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//默认按照'on_time'排序
		$this->_default_ord_field = 'on_time';
		//get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_ad')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		require Keke_tpl::template('control/admin/tpl/man/ad_list');
		
	}
}
/* Keke::admin_check_role(32);
$target_position_arr = array ('top' => $_lang ['top'], 'bottom' => $_lang ['bottom'], 'left' => $_lang ['left'], 'right' => $_lang ['right'], 'center' => $_lang ['center'], 'global' => $_lang ['global'] );
$ad_obj = new Keke_witkey_ad_class();//广告数据
//$target_obj = new Keke_witkey_ad_target_class(); //广告类型
$table_obj = new keke_table_class('witkey_ad');

$page = isset($page) ? intval($page) : '1' ;
$url = "index.php?do={$do}&view={$view}&ad_id={$ad_id}&ad_type={$target_id}&ad_name={$ad_name}&target_id={$target_id}&ord={$ord}&page={$page}";
//ajax修改排序
if ($action && $action=='u_order'){
	!$u_id && exit();
	!$u_value && exit();
	$ad_obj -> setListorder( intval($u_value) );
	$ad_obj -> setWhere('ad_id='.intval($u_id));
	$ad_obj -> edit_keke_witkey_ad();
	exit();
}

//操作 删除,批量删除
if (($sbt_action && $ckb) || ($ac=='del' && $ad_id)){
	// 		if (!empty($ckb) || !empty($ad_id)) {
	$ids = $ckb ? implode(',', $ckb) : intval($ad_id) ;// echo $ids;
	$ad_obj -> setWhere('ad_id in ('.$ids.')');
	$result = $ad_obj -> del_keke_witkey_ad();
	Keke::admin_system_log($_lang['delete_ads'].$ids);
	Keke::admin_show_msg($result ? $_lang['ads_delete_success'] : $_lang['no_operation'] ,"index.php?do={$do}&view={$view}&target_id={$target_id}&ord={$ord}&page={$page}",3,'',$result?'success':'warning');
	// 		} else {
	// 			Keke::admin_show_msg($_lang['choose_operate_item']);
	// 		}
}


//广告类型调用
$targets_arr =  Keke::get_table_data('*','witkey_ad_target', '', '', '', '', 'target_id');
//查询数据
//$page = isset($page) ? intval($page) : '1' ;
$pagesize = isset($page_size) ? intval($page_size) : '10' ;
$where = '1=1';
$where .= $ad_id ? ' and ad_id="'.intval($ad_id).'"' : '' ;
$where .= $target_id && !$ad_id ? ' and target_id="'.intval($target_id).'"' : '';
$where .= $ad_name && !$ad_id ? ' and ad_name like "%'.$ad_name.'%"' : '';

is_array($w['ord']) and $where .=' order by '.$ord[0].' '.$ord[1];

//is_array($ord) && $ord=$ord[0].' '.$ord[1];//implode(' ',$ord);
//$where .= $ord ? ' order by '.$ord : ''; //echo $where;
$ad_arr = $table_obj -> get_grid($where, $url, $page, $pagesize, null, 1, 'ajax_dom'); //var_dump($ad_arr);
$pages = $ad_arr['pages'];
$ad_arr = $ad_arr['data'];
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view); */
