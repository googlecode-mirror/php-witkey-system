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
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `feed_id`,`title`,`feedtype`,`username`,`feed_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('feed_id'=>$_lang['id'],'username'=>$_lang['name'],'feed_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//tool������һ��Ŀ¼������û�ж���manΪĿ¼��·��,����������Ʋ���ļ���tool_feed So���ﲻ��дΪtool/feed
		$base_uri = BASE_URL."/index.php/admin/tool_feed";
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'feed_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_feed')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		
		//��ҳ����
		$pages = $data_info['pages'];
		//$file_type_arr = keke_global_class::get_file_type();
		$feed_type = keke_global_class::get_feed_type ();
		require Keke_tpl::template('control/admin/tpl/tool/feed');
	}
	/**
	 * ������ɾ����action ��Ҫ�Ǵ���Ҫ����ɾ��
	 * �����ɾ����
	 * ��أ�ɾ��action������ͳһdel,��Ҫ��Ϊʲô
	 * ����ɾ����������������ֵ�Ϳ���ɾ����
	 * ����ɾ���ģ���ǰ��jsƴ�Ӻõ�ids��������ֵ.js ֻ��ids ��Ӵ����Ҫд����������
	 *
	 */
	function action_del(){
		//ɾ������,�����feed_id ����ģ���ϵ������������е�
		if($_GET['feed_id']){
			$where = 'feed_id = '.$_GET['feed_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'feed_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
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
	//��ȡ�����
	$where = " 1=1 and tag_type=8 ";
	//��ѯ����
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
//��ҳ����
if ($type === 'manage') {
	$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&feedtype=$feedtype&tpl_type=$tpl_type&txt_tag_id=$txt_tag_id&type=$type&ord[]=$ord[0]&ord[]=$ord[1]";
} else {
	$url = "index.php?do=$do&view=$view&slt_page_size=$slt_page_size&page=$page&feedtype=$feedtype&tpl_type=$tpl_type&txt_feed_id=$txt_feed_id&type=$type&ord[]=$ord[0]&ord[]=$ord[1]";
}

$limit = $slt_page_size;
$Keke->_page_obj->setAjax(1);
$Keke->_page_obj->setAjaxDom("ajax_dom");
$pages = $Keke->_page_obj->getPages ( $count, $limit, $page, $url );

//��ѯ�������
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

//��������
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