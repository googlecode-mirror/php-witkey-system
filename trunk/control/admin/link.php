<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
class Control_admin_link extends Controller {
	
	//��������
	function action_index() {
		global $_K,$_lang;
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		extract($_GET);
		$count = intval($count);
		//����uri
		$base_uri = BASE_URL."/index.php/admin/link";
		//��ӱ༭��uri
		$add_uri =  $base_uri.'/add';
		//ɾ��uri
		$del_uri = $base_uri.'/del';
		
		//ͨ��uri ��ס��ѯ����,��ֹ������ʧ���������
		$query_uri .= "?txt_link_id=$txt_link_id";
		$query_uri .= "&txt_link_name=$txt_link_name";
		$query_uri .= "&ord[]=$ord[0]&ord[]=$ord[1]";
		$query_uri .= "&slt_page_size=$slt_page_size";
		
		//��ѯuri 
		$uri = $base_uri.$query_uri;
		$add_uri .= $query_uri.'&page='.$page;
		$del_uri .= $query_uri.'&page='.$page;
		
		//��ʼ��where��ֵ
		$where = ' 1=1 ';
		if($txt_link_id){
			$where .= ' and link_id = '.$txt_link_id;
		}
		if($txt_link_name){
			$where .= " and link_name like '%$txt_link_name%' ";
		}
		//��ʼ��order��ֵ
		if($ord[0]){
			$order = ' order by '.$ord[0] .' '. $ord[1];
		}else{
			$order = ' order by link_id desc' ;
		}
		$data_info = Model::factory('witkey_link')->get_grid($fields,$where,$uri,$order,$page,$count,$slt_page_size);
		
		$link_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		//echo Database::instance()->get_query_num();
		require Keke_tpl::template('control/admin/tpl/link');
	}
	//�����༭
	function action_add(){
		global $_K,$_lang;
		require Keke_tpl::template('control/admin/tpl/link_add');
	}
	//ɾ��
	function action_del(){
		if($_GET['link_id']){
			echo  Model::factory('witkey_link')->setWhere('link_id = '.$_GET['link_id'])->del();
		}
	}
	//����ɾ��
	function action_del_check(){
		
	}
	
}
