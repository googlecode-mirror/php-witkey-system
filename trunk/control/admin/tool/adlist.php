<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨����б���ʾҳ��
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * @date 2011-12-21 ����05:54:07
 * @encoding GBK
*/
class Control_admin_tool_adlist extends Controller{
	function action_index(){
		//����ȫ�ֱ���������ģ������԰�
		global $_K,$_lang;
		//��Ҫ��ѯ���ֶ�
		$fields = '`ad_id`,`ad_name`,`target_id`,`ad_type`,`start_time`,`end_time`,`on_time`,`is_allow`';
		//��������Ҫ��ʾ���ֶ�
		$query_fields = array('ad_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//ҳ��uri
		$base_uri = BASE_URL.'/index.php/admin/tool_adlist';
		//���uri
		$add_uri = $base_uri.'/add';
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//Ĭ�ϰ���'on_time'����
		$this->_default_ord_field = 'on_time';
		//get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		$target_id =$_GET['target_id'];
		if($target_id){
			$where .= "and target_id =$target_id" ;
		}
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_ad')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//��ȡad_target����name
		$targets_arr =  Keke::get_table_data('*','witkey_ad_target', '', '', '', '', 'target_id');
		require Keke_tpl::template('control/admin/tpl/tool/ad_list');
	}
		function action_add(){
			//ʼʼ��ȫ�ֱ��������԰�����
			global $_K,$_lang;
			//��ȡad_id,�༭״̬�»���ֵ
			$ad_id = $_GET['ad_id'];
			//��ȡ���λID��������ι��λ�Ĺ����
			$target_id = $_GET['target_id'];
			//�����λ��ad_target������ݶ�����
			$target_arr = DB::select('*')->from('witkey_ad_target')->where('target_id=' . intval ( $target_id ))->execute();
			//��ad_target���Ӧ��target_id��Ӧ����Ϣ������
			$target_arr = $target_arr ['0'];
			//��������
			$ad_num = $target_arr['ad_num'];
			//���ڹ��λ�Ѿ����˵Ĺ����
			$have_ad_num = Dbfactory::get_count(sprintf("select count(*) count from %switkey_ad where target_id = %d",TABLEPRE,$target_id));
			//���й�����������й����жϣ������Ҫ�жϣ��༭����Ҫ�ж�
			if($have_ad_num>=$ad_num and !$ad_id){
				Keke::show_msg ( $_lang['operate_notice'],'index.php/admin/tool_adlist',$_lang ['ads_num_over'] , 'warning' );
			}
			//������ڻ�ȡ��ad_id����Ϊ�༭��ʽ
			if($ad_id){
				//��ʾ�ı���Ϊ�༭�����ĵ����԰�
				$page_tips = $_lang['edit'];
				//ͨ��ad_id��ad���е����ݶ�����
				$ad_data = DB::select()->from('witkey_ad')->where('ad_id='.$ad_id)->execute();
				$ad_data = $ad_data[0];
			}else {
				//��ʾ������ӣ�
				$page_tips = $_lang['add'];
			}
				
			require Keke_tpl::template('control/admin/tpl/tool/ad_add');
		}
		/**
		 * ����ģ�����ύ�������ݵ����ݿ���
		 * ���acton ��ͨ�õģ���Ҫ��㶨���������
		 *
		 */
		function action_save(){
			//��ֹ�����ύ���㶮��
			Keke::formcheck($_POST['formhash']);
			//����flash/text/imag/code
			$type = 'ad_type_' . $_POST['ad_type']; 
			//ȷ�Ϲ���ģʽ��ʲô��file/code/image/flash
			switch ($_POST['ad_type']) {
				case "image" :
					if ($_FILES ['ad_type_image_file']['name']) {
						$file_path = keke_file_class::upload_file ( 'ad_type_image_file', '', 1, 'ad/' ); //�ϴ��ļ�
					}else{
						$file_path = $_POST['ad_type_image_path'];
					}
					break;
				case "file" :
					if ($_FILES ['ad_type_flash_file']['name']) {
						if ($_POST['flash_method'] == 'url') {
							$file_path = $_POST['ad_type_flash_url'];
						}
						if ($_POST['flash_method'] == 'file') {
							$file_path = keke_file_class::upload_file ( 'ad_type_flash_file', '', 1, 'ad/' ); //�ϴ��ļ�
						}
					}
					break;
			}
			//Ҫ�������ݵ���Ϣ�����������н���
			$width = $_POST[$type . '_width'];
			$height = $_POST[$type . '_height'];
			$url = $_POST[$type . '_url'];
			$content = $_POST[$type.'_content'];
			//�������ݿ���ֶ�
			$array = array(
					//��������
					'ad_name' => $_POST['ad_name'],
					//��ʼ�ͽ���ʱ��
					'start_time' => strtotime($_POST['start_time']),
					'end_time' => strtotime($_POST['end_time']),
					//�ļ���·�������image��flash�д���
					'ad_file' => $file_path,
					//�������
					'ad_type' =>$_POST['ad_type'],
					//�����imageʱ��ͼƬ�ĸߺͿ�
					'width' => $width,
					'height' => $height,
					//���imageʱͼƬӳ��ĵ�ַ
					'ad_url' => $url,
					//���file��codeʱ�ı�������
					'ad_content' => $content,
					//����
					'listorder' => $_POST['listorder'],
					//�Ƿ����
					'is_allow' => $_POST['rdn_is_allow'],
					//�����ӻ��߱༭֮���ʱ��
					'on_time' => time(),
					);
			//�жϴ�������ֵ��û��ad_id(����)��ֵ����Ϊ�༭ģʽ��û��Ϊ��ӣ�ֵ���ᱣ�棬
			if($_POST['hdn_ad_id']){
				Model::factory('witkey_ad')->setData($array)->setWhere("ad_id = '{$_POST['hdn_ad_id']}'")->update();
				//�༭����֮����ת���༭ҳ�棬ͨ��ad_id���жϣ����ݵĲ��������´��ж�Ϊ�༭ģʽ
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/tool_adlist/add?ad_id='.$_POST['hdn_ad_id'],'�ύ�ɹ�','success');
			}else{
				//��Ҳ��Ȼ�������(insert)�����ݿ���
				Model::factory('witkey_ad')->setData($array)->create();
				//ϵͳ��ʾ���֮��ҳ����ת�����ҳ�棬���Լ������
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/tool_adlist/add ','�ύ�ɹ�','success');
			}
	}
}
/* Keke::admin_check_role(32);
$target_position_arr = array ('top' => $_lang ['top'], 'bottom' => $_lang ['bottom'], 'left' => $_lang ['left'], 'right' => $_lang ['right'], 'center' => $_lang ['center'], 'global' => $_lang ['global'] );
$ad_obj = new Keke_witkey_ad_class();//�������
//$target_obj = new Keke_witkey_ad_target_class(); //�������
$table_obj = new keke_table_class('witkey_ad');

$page = isset($page) ? intval($page) : '1' ;
$url = "index.php?do={$do}&view={$view}&ad_id={$ad_id}&ad_type={$target_id}&ad_name={$ad_name}&target_id={$target_id}&ord={$ord}&page={$page}";
//ajax�޸�����
if ($action && $action=='u_order'){
	!$u_id && exit();
	!$u_value && exit();
	$ad_obj -> setListorder( intval($u_value) );
	$ad_obj -> setWhere('ad_id='.intval($u_id));
	$ad_obj -> edit_keke_witkey_ad();
	exit();
}

//���� ɾ��,����ɾ��
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


//������͵���
$targets_arr =  Keke::get_table_data('*','witkey_ad_target', '', '', '', '', 'target_id');
//��ѯ����
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
