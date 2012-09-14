<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ��̨��Ѷ���������Ʋ�
 * @author Administrator
 *
 */
class Control_admin_article_cate extends Controller {

	/**
	 * ���·����б�
	 */
	function action_index() {
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `art_cat_id`,`art_cat_pid`,`cat_name`,`is_show`,`cat_type`,`art_index`,`listorder`,`on_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('art_cat_id'=>$_lang['id'],'cat_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/article_cate";
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		$where .= " and cat_type = 'article' ";
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_article_category')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		$temp_arr = array();
	//var_dump($art_cat_arr);
		Keke::get_tree($list_arr,$temp_arr,'cat','','art_cat_id','art_cat_pid','cat_name');
		$list_arr = $temp_arr;
		unset($temp_arr);
		//var_dump($list_arr);
		//��ҳ����
		$pages = $data_info['pages'];

		require Keke_tpl::template('control/admin/tpl/article/cate');
	}
	/**
	 * ���������б�
	 */
	function action_help(){
		
	}
	/**
	 * �������
	 */
	function action_add(){
		//ʼʼ��ȫ�ֱ��������԰�����
		global $_K,$_lang;
		$case_id = $_GET['art_cat_id'];
		//�����ֵ���ͽ���༭״̬
		if($case_id){
			$case_info = Model::factory('witkey_article_category')->setWhere('art_cat_id = '.$case_id)->query();
			$case_info = $case_info[0];
			$file = pathinfo($case_info['case_img'], PATHINFO_BASENAME);
		}
		//var_dump($case_info);
		//����ģ��
		require Keke_tpl::template('control/admin/tpl/article/cate_add');
	}
	/**
	 * ���ౣ��
	 */
	function action_save(){
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		 
	    if(!empty($_FILES['fle_case_img']['name'])){
			//�ϴ��ļ��õģ������������˵��ʹ,Ҫ���Ǽ�
			$case_img = keke_file_class::upload_file('fle_case_img');
		}
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$array = array('case_title'=>$_POST['txt_task_title'],
				'case_price'=>$_POST['txt_case_price'],
				'case_img'=>$case_img,
				'obj_type' => $_POST['case_type']=='search'?'task':'service',
				'obj_id'=>$_POST['obj_id'],
				'on_time'=>time(),
		);
		//���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
		if($_POST['hdn_art_cat_id']){
			Model::factory('witkey_article_category')->setData($array)->setWhere("case_id = '{$_POST['hdn_art_cat_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/article_case/add?art_cat_id='.$_POST['hdn_art_cat_id'],'�ύ�ɹ�','success');
		}else{
			//��Ҳ��Ȼ�������(insert)�����ݿ���
			Model::factory('witkey_article_category')->setData($array)->create();
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/article_case/add','�ύ�ɹ�','success');
		}
	}
	/**
	 * ����ɾ��
	 */
	function action_del(){
		//ɾ������,�����case_id ����ģ���ϵ������������е�
		if($_GET['art_cat_id']){
			$where = 'art_cat_id = '.$_GET['art_cat_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'art_cat_id in ('.$_GET['ids'].')';
		}
		echo  Model::factory('witkey_article_category')->setWhere($where)->del();
	}
	 
}
/* $cat_obj = new Keke_witkey_article_category_class ();
$file_obj = new keke_file_class();
$table_obj = new keke_table_class ( "witkey_article_category" );
//������Ѷ���������
$cat_all_arr = Keke::get_table_data('*',"witkey_article_category",'','','','','art_cat_id');

$url = "index.php?do=$do&view=$view&type=$type&w[art_cat_pid]={$w[art_cat_pid]}&w[cat_name]={$w[cat_name]}
&$ord[0]={$ord[1]}";

if ($ac == 'del') { //ɾ��
	//var_dump($art_cat_id);die();
	$table_obj->del ( 'art_cat_id', $art_cat_id, $url );
	Keke::admin_show_msg($_lang['delete_success'],'index.php?do=article&view=cat_list&type='.$type,3,'','success');
} elseif (isset ( $sbt_action )) {
	if ($edit_cat_name_arr) { //�༭
		foreach ( $edit_cat_name_arr as $k => $v ) {
			$cat_obj->setWhere ( "art_cat_id = $k" );
			$cat_obj->setCat_name ( $v );
			$cat_obj->edit_keke_witkey_article_category ();

		}

		Keke::admin_system_log ( $_lang['edit_article_category'] );
	} elseif ($add_cat_name_arr) { //ɾ��
			
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
} elseif ($ac === 'editlistorder') { //������
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
	//��ѯ����
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
	//������ҵ�����˵�
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
 