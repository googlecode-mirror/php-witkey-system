<?php
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台广告位管理
 * @copyright keke-tech
 * @author hr
 * @version v 2.1
 * @date 2011-12-21 下午05:58:43
 * @encoding GBK
 */
class Control_admin_tool_ad extends Controller{
	function action_index(){
		//加载全局变量，语言包
		global $_K,$_lang;
		//要显示的字段，即sql中要查询的字段
		$fields ='`target_id`,`name`,`ad_num`,`code`,`sample_pic`';
		//页面的uri
		$base_uri = BASE_URL."/index.php/admin/tool_ad";
		//删除uri，del是固定的
		$del_uri = $base_uri."/del";
		//不需要分页，page_size设置大
		$page_size = 100;
		//获取witkey_ad_target表的信息
		$data_info = Model::factory('witkey_ad_target')->get_grid($fields,$where,$uri,$order,$page,$count,$page_size);
		//列表数据
		$list_arr = $data_info['data'];
		//var_dump(Database::instance()->get_query_list());
		//获取target_id和已经在广告中占有的条数
		$target_ad_num = Keke::get_table_data('target_id, count(*) as num', 'witkey_ad', 'target_id is not null', '', 'target_id', '', 'target_id', null);
		while (list($key, $value) = each($list_arr)){
			$target_ad_arr[$key] = $target_ad_num[$key]['num'] ? $target_ad_num[$key]['num'] : '0';
		}
		require Keke_tpl::template('control/admin/tpl/tool/ad');
	}
}
/* Keke::admin_check_role ( 32 );

$table_name = 'witkey_ad_target';
$target_arr = Keke::get_table_data ( '*', $table_name, '', '', '', '', 'target_id', null ); //private
$target_ad_num = Keke::get_table_data('target_id, count(*) as num', 'witkey_ad', 'target_id is not null', '', 'target_id', '', 'target_id', null);
while (list($key, $value) = each($target_arr)){
	$target_ad_arr[$key] = $target_ad_num[$key]['num'] ? $target_ad_num[$key]['num'] : '0';
}

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */