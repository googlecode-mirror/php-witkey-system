<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��̨����б���ʾҳ��
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * @date 2011-12-21 ����05:54:07
 * @encoding GBK
*/
class Control_admin_man_adlist extends Controller{
	function action_index(){
		//����ȫ�ֱ���������ģ������԰�
		global $_K,$_lang;
		//��Ҫ��ѯ���ֶ�
		$fields = '`ad_id`,`ad_name`,`target_id`,`start_time`,`end_time`,`on_time`,`is_allow`';
		//��������Ҫ��ʾ���ֶ�
		$query_fields = array('ad_id'=>$_lang['id'],'ad_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//ҳ��uri
		$base_uri = BASE_URL.'/index.php/admin/man_adlist';
		//���uri
		$add_uri = $base_uri.'/add';
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//Ĭ�ϰ���'on_time'����
		$this->_default_ord_field = 'on_time';
		//get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_ad')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//��ȡad_target����name
		$targets_arr =  Keke::get_table_data('*','witkey_ad_target', '', '', '', '', 'target_id');
		require Keke_tpl::template('control/admin/tpl/man/ad_list');
		
	}
		function action_add(){
			//ʼʼ��ȫ�ֱ��������԰�����
			global $_K,$_lang;
			$target_id = $this->request->param('id');// $_GET['target_id'];
			$ac = $_GET['action'];
			$ad_id = $_GET['ad_id'];
			if($target_id&&$ac!='edit'){
				$target_info = Dbfactory::get_one(sprintf("select * from %switkey_ad_target where target_id = %d",TABLEPRE,$target_id));
				$ad_num = $target_info[ad_num];//��������
				$have_ad_num = Dbfactory::get_count(sprintf("select count(ad_id) count from %switkey_ad where target_id = %d",TABLEPRE,$target_id));
				if($have_ad_num>=$ad_num){
					Keke::show_msg ( $_lang ['ads_num_over'],'index.php/admin/man_adlist', '����', 'warning' );
				}
			}
			$ad_obj = new Keke_witkey_ad ();
			if ($sbt_action) {
				$type = 'ad_type_' . $ad_type; //����flash/text/imag/code
				switch ($ad_type) {
					case "image" :
						if ($_FILES ['ad_type_image_file']['name']) {
							$file_path = keke_file_class::upload_file ( 'ad_type_image_file', '', 1, 'ad/' ); //�ϴ��ļ�
						}else{
							$file_path = $ad_type_image_path;
						}
						break;
					case "file" :
						if ($_FILES ['ad_type_flash_file']['name']) {
							if ($flash_method == 'url') {
								$file_path = $ad_type_flash_url;
							}
							if ($flash_method == 'file') {
								$file_path = keke_file_class::upload_file ( 'ad_type_flash_file', '', 1, 'ad/' ); //�ϴ��ļ�
							}
						}
						break;
				}
			
				$file_path && $ad_obj->setAd_file ( $file_path ); //�ļ�
				var_dump($file_path);
				$ad_name = $hdn_ad_name ? $hdn_ad_name : $ad_name; //��������������(�õ�Ƭ����·�ֹ�޸�$ad_name)
				$ad_obj->setAd_name ( $ad_name ); //����
				//��ʼʱ��
				$start_time && $ad_obj->setStart_time ( strtotime ( $start_time ) );
				//����ʱ��
				$end_time && $ad_obj->setEnd_time ( strtotime ( $end_time ) );
				//����
				$ad_obj->setAd_type ( $ad_type );
				//Ͷ��λ��
				$ad_obj->setAd_position ( $ad_position );
				//��
				$width = ${$type . '_width'};
				$width && $ad_obj->setWidth ( $width );
				//��
				$height = ${$type . '_height'};
				$height && $ad_obj->setHeight ( $height );
				//url
				$url = ${$type . '_url'};
				$ad_obj->setAd_url ( $url );
				//content
				$content = ${$type . '_content'};
				$content && $ad_obj->setAd_content ( $content );
				$hdn_target_id && $ad_obj->setTarget_id ( intval ( $hdn_target_id ) );
				$ckb_tpl_type && $tpl_type = implode ( ',', $ckb_tpl_type ); //ģ������
				$ad_obj->setTpl_type ( $tpl_type );
				$ad_obj->setListorder ( intval ( $listorder ) );
				$ad_obj->setIs_allow ( intval ( $rdn_is_allow ) );
				$ad_obj->setOn_time ( time () );
			}
			require Keke_tpl::template('control/admin/tpl/man/ad_add');
		}
		/**
		 * ����ģ�����ύ�������ݵ����ݿ���
		 * ���acton ��ͨ�õģ���Ҫ��㶨���������
		 *
		 */
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
// 			$array = array('ad_name'=>$_POST['ad_name'],
// 					'ad_url'=>$_POST['ad_type_image_url'],
// 					'ad_file'=>$_POST['ad_type_image_file'],
// 					'listorder' => $_POST['txt_listorder'],
// 					'on_time'=>time(),
// 			);
			//���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
			if($_POST['hdn_link_id']){
				Model::factory('witkey_link')->setData($array)->setWhere("link_id = '{$_POST['hdn_link_id']}'")->update();
				//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/link/add?link_id='.$_POST['hdn_link_id'],'�ύ�ɹ�','success');
			}else{
				//��Ҳ��Ȼ�������(insert)�����ݿ���
				Model::factory('witkey_link')->setData($array)->create();
				Keke::show_msg('ϵͳ��ʾ','index.php/admin/link/add','�ύ�ɹ�','success');
			}
	}
	/* function action_edit(){
		$ad_obj = new Keke_witkey_ad ();
		if ($ad_type == 'text' || $ad_type == 'code') { //�������������ı����ߴ���,��ôӦ��ɾ��width,height,��Ȼ�Ű��������
			$ad_obj->setWidth ( '' );
			$ad_obj->setHeight ( '' );
		}
		$ad_obj->setWhere ( 'ad_id=' . intval ( $ad_id ) );
		$result = $ad_obj->update ();
		Keke::admin_system_log ( $_lang ['edit_ads_data'] . $ad_id );
		Keke::admin_show_msg ( $result ? $_lang ['edit_ads_success_jump_adslist'] : $_lang ['not_make_changes_return_again'], 'index.php?do=tpl&view=ad_add&ac=edit&ad_id=' . $ad_id, 3, '', $result ? 'success' : 'warning' ); //die����
		$result = $ad_obj->create_keke_witkey_ad ();
		Keke::admin_system_log ( $_lang ['add_ads_data'] );
		Keke::admin_show_msg ( $result ? $_lang ['add_ads_success'] : $_lang ['add_fail_return_again'], 'index.php?do=tpl&view=ad_list&target_id=' . $hdn_target_id, 3, '', $result ? 'success' : 'warning' ); //die����
		$page_tips = $_lang ['add'];
		$ad_data = array ();
		//$target_id && $tagname and $ad_data ['ad_name'] = $tagname; //�ӹ�������ҳ����ת����ʱ,ad_titleֻ�ܺ�$tagname��ͬ,����Ϊreadonly
		//�༭ ��ȡ��������
			empty ( $ad_id ) && Keke::admin_show_msg ( $_lang ['edit_parameter_error_jump_listpage'], 'index.php?do=tpl&view=ad_list', 3, '', 'warning' );
			$page_tips = $_lang ['edit'];
			unset ( $ad_data );
			$ad_id = intval ( $ad_id );
			$ad_obj->setWhere ( 'ad_id="' . $ad_id . '"' );
			$ad_data = $ad_obj->update();
			$ad_data = $ad_data ['0'];
			$ad_data ['tpl_type'] = explode ( ',', $ad_data ['tpl_type'] );
			$target_id = $ad_data ['target_id']; //ȡ��Ͷ��λ��
		//��ȡ��Ӧ��(һ��)���λ�����Ϣ
		if ($target_id) {
			$target_arr = Keke::get_table_data ( '*', 'witkey_ad_target', 'target_id=' . intval ( $target_id ) );
			$target_arr = $target_arr ['0'];
			/* ����ǻõ�Ƭ ,��Ҫ�ж���û�ж�Ӧ�Ĺ����,
			 * ���û����ת����������ҳ��
			* �����,��ô������ad_title����Ϊֻ��,�������޸�*/
// 			$is_slide = substr ( $target_arr ['code'], - 5 );
// 			if (strtolower ( $is_slide ) == 'slide') {
// 				$group_arr = Dbfactory::query ( 'select * from ' . TABLEPRE . 'witkey_tag where tagname="' . $target_arr ['name'] . '" and tag_type="9"' );
// 				if (! $group_arr) {
// 					Keke::admin_show_msg ( $_lang ['add_group_msg'], 'index.php?do=tpl&view=ad_group_add&ac=add&target_id=' . $target_arr ['target_id'] . '&tagname=' . $target_arr ['name'], '3', '', 'warning' );
// 				} else {
// 					$tagname = $group_arr ['0'] ['tagname'];
						
// 					$important_msg = $_lang ['name_must_same'];
// 				}
// 			}
		
// 			$ad_count = Dbfactory::get_count(" select count(ad_id) as num from  ".TABLEPRE."witkey_ad where target_id =".intval($target_id ));
// 		}
// 	} */
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
