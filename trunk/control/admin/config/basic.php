<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ȫ�����ù�����Ʋ�
 * @author michael
 *
 */
class Control_admin_config_basic extends  Controller {

	function action_index($type=NULL){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
	 	//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/config_basic";
		//�����������ͣ�Ĭ��Ϊweb�� 
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'web';
		}
		$where = " type='$type'";
		$data_info =DB::select()->from('witkey_config')->where($where)->execute(); 
		//�б�����
		$list_arr = $data_info[0];
		require Keke_tpl::template('control/admin/tpl/config/basic');
	}
	
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		$type = $_POST['type'];
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$array = array('art_title'=>$_POST['txt_art_title'],
				'art_cat_id'=>$_POST['slt_cat_id'],
				'art_pic'=>$_POST['hdn_art_pic'],
				'content' => $_POST['txt_content'],
				'seo_title'=>$_POST['txt_seo_title'],
				'seo_keyword'=>$_POST['txt_seo_keyword'],
				'seo_desc'=>$_POST['txt_seo_desc'],
				'username'=>$_POST['txt_username'],
				'art_source'=>$_POST['txt_art_source'],
				'listorder'=>$_POST['txt_listorder'],
				'is_recommend'=>$_POST['ckb_is_recommend']=='on'?1:0,
				'cat_type'=>$type,
				'pub_time'=>time(),
		);
		 
		//���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
		if($_POST['hdn_art_id']){
			Model::factory('witkey_article')->setData($array)->setWhere("art_id = '{$_POST['hdn_art_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/article_list/add?art_id='.$_POST['hdn_art_id'].'&type='.$type,'�ύ�ɹ�','success');
		}else{
			//��Ҳ��Ȼ�������(insert)�����ݿ���
			Model::factory('witkey_article')->setData($array)->create();
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/article_list/add?type='.$type,'�ύ�ɹ�','success');
		}
	}
	
}
