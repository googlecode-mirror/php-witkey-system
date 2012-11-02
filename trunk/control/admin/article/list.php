<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ��̨��Ѷ������Ʋ�
 * @author Michael
 * 2012-09-26
 */

class Control_admin_article_list extends Control_admin {
 
	function action_index($type=null) {
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `art_id`,`art_cat_id`,`username`,`art_title`,`cat_type`,`listorder`,`is_show`,`is_delineas`,`is_recommend`,`art_pic`,`pub_time`,`views` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('art_id'=>$_lang['id'],'art_title'=>$_lang['name'],'pub_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/article_list";
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'pub_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ָ�����͵�����
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'article';
		}
		$where .= " and  cat_type = '$type' ";
		$uri .= "&type=$type";
		
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_article')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
        //��������
		$art_cat_arr  = Model::factory('witkey_article_category')->query('*',66666);
		//���ɼ�ֵ������
		$art_cat_arr = Keke::get_arr_by_key($art_cat_arr,'art_cat_id');
		//$sql_list = Database::instance()->get_query_list();
		//var_dump($sql_list);
		
		
		
		require Keke_tpl::template('control/admin/tpl/article/list');
	}
	
	function action_bulletin(){
		$this->action_index('bulletin');
	}
	function action_about(){
		$this->action_index('about');
	}
	function action_help(){
		$this->action_index('help');
	}
	function action_add(){
		//ʼʼ��ȫ�ֱ��������԰�����
		global $_K,$_lang;
		$type = $_GET['type'];
		$art_id = $_GET['art_id'];
		//�����ֵ���ͽ���༭״̬
		if($art_id){
			$art_info = Model::factory('witkey_article')->setWhere('art_id = '.$art_id)->query();
			$art_info = $art_info[0];
		}
		if($type == 'article' or $type =='help'){
			$cat_arr = $this->get_cate($type, $art_info['art_cat_id']);
		}
		//var_dump($case_info);
		//����ģ��
		require Keke_tpl::template('control/admin/tpl/article/add');
	}
	/**
	 * ��ȡָ�����͵ķ���
	 * @param Sting $type  article,help ... 
	 * @param Sting $index  select ->option ������
	 * @return array �������е�����
	 */
	function get_cate($type,$index){
	    $cate_arr = Model::factory('witkey_article_category')->setWhere("cat_type='$type'")->query();
		$t_arr = array ();
 		Keke::get_tree ( $cate_arr, $t_arr, 'option', $index, 'art_cat_id', 'art_cat_pid', 'cat_name' );
	    return $t_arr; 
	}
	/**
	 * ajaxɾ������ͼƬ
	 */
	static function action_del_img(){
		//���pk��ֵ����ɾ���ļ����е�art_pic
		if($_GET['pk']){
			Dbfactory::execute(" update ".TABLEPRE."witkey_article set art_pic ='' where art_id = ".intval($_GET['pk']));
		}
		//û��fid�Ͳ���fid,û��fid����ɾ���ļ�,���ڰ�ȫ����
		if(!intval($_GET['fid'])){
			$fid = Dbfactory::get_count(" select file_id from ".TABLEPRE."witkey_file where save_name = '.{$_GET['filepath']}.'");
		}else{
			$fid = $_GET['fid'];
		}
		//ɾ���ļ�
		keke_file_class::del_att_file($fid, $_GET['filepath']);
		Keke::echojson ( '', '1' );
	}
	/**
	 * ��������
	 */
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
			Keke::show_msg('ϵͳ��ʾ','admin/article_list/add?art_id='.$_POST['hdn_art_id'].'&type='.$type,'�ύ�ɹ�','success');
		}else{
			//��Ҳ��Ȼ�������(insert)�����ݿ���
			Model::factory('witkey_article')->setData($array)->create();
			Keke::show_msg('ϵͳ��ʾ','admin/article_list/add?type='.$type,'�ύ�ɹ�','success');
		}
	}
	
	function action_del(){
		//ɾ������,�����case_id ����ģ���ϵ������������е�
		if($_GET['art_id']){
			$where = 'art_id = '.$_GET['art_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'art_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_article')->setWhere($where)->del();
	}
	 
}//end