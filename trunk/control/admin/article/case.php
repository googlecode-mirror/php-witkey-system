<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * ��̨����������Ʋ�
 * @author Michael	
 * 2012-09-26
 */
class Control_admin_article_case extends Control_admin {
 
	function action_index() {
		 
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
		 
		$case_id = $_GET['case_id'];
		//�����ֵ���ͽ���༭״̬
		if($case_id){
			$case_info = Model::factory('witkey_case')->setWhere('case_id = '.$case_id)->query();
			$case_info = $case_info[0];
			$file = pathinfo($case_info['case_img'], PATHINFO_BASENAME);
		}
		//var_dump($case_info);
		//����ģ��
		require Keke_tpl::template('control/admin/tpl/article/case_add');
	}
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
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
		if($_POST['hdn_case_id']){
			Model::factory('witkey_case')->setData($array)->setWhere("case_id = '{$_POST['hdn_case_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('ϵͳ��ʾ','index.php/admin/article_case/add?case_id='.$_POST['hdn_case_id'],'�ύ�ɹ�','success');
		}else{
			//��Ҳ��Ȼ�������(insert)�����ݿ���
			Model::factory('witkey_case')->setData($array)->create();
			Keke::show_msg('ϵͳ��ʾ','admin/article_case/add','�ύ�ɹ�','success');
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
		$model_type_arr  = Keke_global::get_task_type();
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
		//$model_type_arr  = Keke_global::get_task_type();
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
