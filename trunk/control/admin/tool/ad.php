<?php
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨���λ����
 * @copyright keke-tech
 * @author hr
 * @version v 2.1
 * @date 2011-12-21 ����05:58:43
 * @encoding GBK
 */
class Control_admin_tool_ad extends Controller{
	function action_index(){
		//����ȫ�ֱ��������԰�
		global $_K,$_lang;
		//Ҫ��ʾ���ֶΣ���sql��Ҫ��ѯ���ֶ�
		$fields ='`target_id`,`name`,`ad_num`,`code`,`sample_pic`';
		//ҳ���uri
		$base_uri = BASE_URL."/index.php/admin/tool_ad";
		//ɾ��uri��del�ǹ̶���
		$del_uri = $base_uri."/del";
		//����Ҫ��ҳ��page_size���ô�
		$page_size = 100;
		//��ȡwitkey_ad_target�����Ϣ
		$data_info = Model::factory('witkey_ad_target')->get_grid($fields,$where,$uri,$order,$page,$count,$page_size);
		//�б�����
		$list_arr = $data_info['data'];
		//var_dump(Database::instance()->get_query_list());
		//��ȡtarget_id���Ѿ��ڹ����ռ�е�����
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