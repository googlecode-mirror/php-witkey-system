<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ��̨����������Ʋ�
 * @author Administrator
 *
 */
class Control_admin_article_case extends Controller {
	function before(){
		 
	}
	function action_index() {
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `case_id`,`obj_id`,`obj_type`,`case_img`,`case_title`,`case_desc`,`case_price`,`on_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('case_id'=>$_lang['id'],'case_title'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/article_case";
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_case')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];

		require Keke_tpl::template('control/admin/tpl/article/case');
	}
	function action_add(){
		//ʼʼ��ȫ�ֱ��������԰�����
		global $_K,$_lang;
		$case_id = $_GET['case_id'];
		//�����ֵ���ͽ���༭״̬
		if($case_id){
			$case_info = Model::factory('witkey_case')->setWhere('case_id = '.$case_id)->query();
			$case_info = $case_info[0];
			$file = pathinfo($case_info['case_img'], PATHINFO_BASENAME);
		}
		//����ģ��
		require Keke_tpl::template('control/admin/tpl/article/case_add');
	}
	function action_save(){
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		//������ҵ���ж�,������ͼƬ����url��ַ
		if($_POST['showMode'] ==1){
			$link_pic = $_POST['txt_link_pic'];
		}elseif(!empty($_FILES['fle_link_pic']['name'])){
			//�ϴ��ļ��õģ������������˵��ʹ,Ҫ���Ǽ�
			$link_pic = keke_file_class::upload_file('fle_link_pic');
		}
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$array = array('link_name'=>$_POST['txt_link_name'],
				'link_url'=>$_POST['txt_link_url'],
				'link_pic'=>$link_pic,
				'listorder' => $_POST['txt_listorder'],
				'on_time'=>time(),
		);
		//���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
		if($_POST['hdn_case_id']){
			Model::factory('witkey_case')->setData($array)->setWhere("case_id = '{$_POST['hdn_case_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/article_case/add?case_id='.$_POST['hdn_case_id'],'�ύ�ɹ�','success');
		}else{
			//��Ҳ��Ȼ�������(insert)�����ݿ���
			Model::factory('witkey_link')->setData($array)->create();
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/article_case/add','�ύ�ɹ�','success');
		}
	}
	function action_del(){
		//ɾ������,�����case_id ����ģ���ϵ������������е�
		if($_GET['case_id']){
			$where = 'case_id = '.$_GET['case_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'case_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
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
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/article_case/search";
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
			//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
			$query_fields = array('task_id'=>$_lang['id'],'task_title'=>$_lang['name'],'start_time'=>$_lang['time']);
			//Ĭ�������ֶΣ����ﰴʱ�併��
			$this->_default_ord_field = 'start_time';
			//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
			extract($this->get_url($base_uri));
			//�Ѿ�����������
			$where .= ' and task_status = 8 ';
		    
			//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
			$data_info = Model::factory('witkey_task')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
			//�б�����
			$list_arr = $data_info['data'];
			//��ҳ����
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
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/article_case/search_service";
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
	 
		 
			//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
			$query_fields = array('service_id'=>$_lang['id'],'title'=>$_lang['name'],'on_time'=>$_lang['time']);
			//Ĭ�������ֶΣ����ﰴʱ�併��
			$this->_default_ord_field = 'on_time';
			//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
			extract($this->get_url($base_uri));
			//�Ѿ�����������
			$where .= ' and service_status != 1 ';
				
			 
			//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
			$data_info = Model::factory('witkey_service')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
			//�б�����
			$list_arr = $data_info['data'];
			//��ҳ����
			$pages = $data_info['pages'];
		
		 
		
		require Keke_tpl::template ( 'control/admin/tpl/article/case_search' );
	}
}


/* $case_obj = new Keke_witkey_case_class ();
//��ҳ

$w ['page_size'] and $page_size = intval ( $w ['page_size'] ) or $page_size =10;
$page and $page = intval ( $page ) or $page = '1';
$url = "index.php?do=$do&view=$view&w[case_id]=".$w['case_id']."&w[art_title]=".$w['art_title']."&w[case_auther]=".
		$w['case_auther']."&w[obj_type]=".$w['obj_type']."&w[page_size]=$page_size&w[ord]=".$w['ord']."&page=$page";

if (isset ( $ac )) { //����ɾ��
	if ($case_id) {
		switch ($ac) {
			case "del" :
				$case_obj->setWhere ( 'case_id=' . $case_id );
				$res = $case_obj->del_keke_witkey_case ();
				Keke::admin_system_log( $_lang['delete_case'].':' . $case_id );//��־��¼
				$res and Keke::admin_show_msg ( $_lang['delete_success'], $url,3,'','success' ) or Keke::admin_show_msg ( $_lang['delete_fail'], $url,3,'','warning' );
				break;
		}
	} else {
		Keke::admin_show_msg ( $_lang['del_fail_select_operate'], $url );
	}
} elseif (isset ( $sbt_action )) { //����ɾ��
	$ckb_string = $ckb;
	is_array ( $ckb_string ) and $ckb_string = implode ( ',', $ckb_string );
	if (count ( $ckb_string )) {
		$case_obj->setWhere ( 'case_id in (' . $ckb_string . ')' );
		$res = $case_obj->del_keke_witkey_case ();//ɾ��
		Keke::admin_system_log($_lang['mulit_delete_case'].':' . $ckb_string );//��־��¼
		$res and Keke::admin_show_msg ( $_lang['mulit_operate_success'], $url ,3,'','success') or Keke::admin_show_msg ( $_lang['mulit_operate_fail'], $url,3,'','warning' );
	} else
		Keke::admin_show_msg ( $_lang['mulit_del_fail_select_operate'], $url,3,'','warning' );
} else {

	$model_list = Keke::get_table_data ( '*', 'witkey_model', "model_status=1 and model_dir!='employtask'", 'listorder asc ', '', '', 'model_id', null );
	$count = $case_obj->count_keke_witkey_case();

	//	$sql = "select *,ifnull(case_title,task_title) task_title from ".TABLEPRE."witkey_case as a left join ".TABLEPRE."witkey_task as b on a.obj_id = b.task_id ";
	$sql = "select * from ".TABLEPRE."witkey_case";
	$where = ' where 1 = 1'; //��ѯ
	//����
	$w ['case_id'] and $where .= " and case_id = '".$w['case_id']."' ";
	$w ['art_title'] and $where .= " and case_title like '%".$w['art_title']."%' ";
	$w ['case_auther'] and $where .= " and case_auther like '%".$w['case_auther']."%' ";
	$w ['obj_type'] and $where .= " and obj_type = '".$w['obj_type']."' ";

	$order_where = " order by on_time desc";
	is_array($w['ord']) and $order_where= ' order by '.$w['ord']['0'].' '.$w['ord']['1'];
	$where=$where.$order_where;

	//$w ['ord'] and $where .= " order by $w['ord']" or $where .= " order by case_id desc";//����
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