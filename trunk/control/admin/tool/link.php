<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ��̨��������Ŀ�����
 * @author michael
 *
 */

class Control_admin_tool_link extends Control_admin {
	
	/**
	 * ���������ʼ��ҳ��
	 * index �Ǳ���ģ�����·���Ҳ���index������͹��˰�
	 * �ӵ���ע�Ͱ�,���Ǳ���Ҫд��(*_*)!
	 */
	function action_index() {
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('link_id'=>$_lang['id'],'link_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/tool_link";
		
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_link')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$link_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		
 		
		require Keke_tpl::template('control/admin/tpl/tool/link');
	}
	
	//�����༭��ʼ��
	function action_add(){
		//ʼʼ��ȫ�ֱ��������԰�����
		global $_K,$_lang;
		$link_id = $_GET['link_id'];
		//�����ֵ���ͽ���༭״̬
		if($link_id){
			$link_info = Model::factory('witkey_link')->setWhere('link_id = '.$link_id)->query();
			$link_info = $link_info[0];
			$link_pic = $link_info['link_pic'];
		}
		//��http�ľ���url��ַ������ˮ��
		if(strpos($link_pic, 'http')!==FALSE){
			//Զ�̵�ַ
			$mode = 1;
		}else{
			//����ͼƬ
			$mode = 2;
		}
		//����ģ�壬���е��J8��,�����˶�����
		require Keke_tpl::template('control/admin/tpl/tool/link_add');
	}
	/**
	 * ����ģ�����ύ�������ݵ����ݿ���
	 * ���acton ��ͨ�õģ���Ҫ��㶨���������
	 * 
	 */
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
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
		if($_POST['hdn_link_id']){
			Model::factory('witkey_link')->setData($array)->setWhere("link_id = '{$_POST['hdn_link_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('�ύ�ɹ�','admin/tool_link/add?link_id='.$_POST['hdn_link_id'],'success');
		}else{
		 //��Ҳ��Ȼ�������(insert)�����ݿ���	
			Model::factory('witkey_link')->setData($array)->create();
			Keke::show_msg('�ύ�ɹ�','admin/tool_link/add','success');
		}
		
		
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
		//ɾ������,�����link_id ����ģ���ϵ������������е�
		if($_GET['link_id']){  
			$where = 'link_id = '.$_GET['link_id'];
		//ɾ������,���������ͳһΪidsӴ����	
		}elseif($_GET['ids']){
			$where = 'link_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_link')->setWhere($where)->del();
	}
	
}
