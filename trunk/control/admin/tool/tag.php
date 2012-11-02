<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-5-24����06:08:41
 */
class Control_admin_tool_tag extends Control_admin{
	/**
	 * ��ʼ����ǩ�б�
	 */
	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `tag_id`,`tagname`,`tag_type`,`cache_time`,`tpl_type`,`on_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('tag_id'=>$_lang['id'],'file_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//tool������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���too_file So���ﲻ��дΪtool/file
		$base_uri = BASE_URL."/index.php/admin/tool_tag";
		
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'tag_id';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��չ����,�������������
		$id = $this->request->param('id');
		
		if($id == '1'){
			$where .= " and tagname like '%�%' ";
		}elseif($id == '2'){
			$where .= " and tagname like '%Э��%'  ";
		}
		
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_tag')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		 
		require Keke_tpl::template('control/admin/tpl/tool/tag_list');
		
	}
	/**
	 * ɾ����ǩ
	 */
	function action_del(){
		//ɾ������,�����tag_id ����ģ���ϵ������������е�
		if($_GET['tag_id']){
			$where = 'tag_id = '.$_GET['tag_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'tag_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_tag')->setWhere($where)->del();
	}
	/**
	 * ��ʼ����ǩ���ģ��
	 */
	function action_add(){
		//ʼʼ��ȫ�ֱ��������԰�����
		global $_K,$_lang;
		$tag_id = $_GET['tag_id'];
		//�����ֵ���ͽ���༭״̬
		if($tag_id){
			$tag_info = Model::factory('witkey_tag')->setWhere('tag_id = '.$tag_id)->query();
			$tag_info = $tag_info[0];
		}
		 
		//����ģ�壬���е��J8��,�����˶�����
		require Keke_tpl::template('control/admin/tpl/tool/tag_add');
	}
	/**
	 * ���ɱ�ǩģ��
	 */
	function action_save(){
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$array = array('tagname'=>$_POST['txt_tagname'],
				'tag_code'=>$_POST['tar_custom_code'],
				'cache_time' => $_POST['txt_cache_time'],
				'on_time'=>time(),
		);
		//���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
		if($_POST['hdn_tag_id']){
			Model::factory('witkey_tag')->setData($array)->setWhere("tag_id = '{$_POST['hdn_tag_id']}'")->update();
			//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
			Keke::show_msg('�ύ�ɹ�','admin/tool_tag/add?tag_id='.$_POST['hdn_tag_id'],'success');
		}else{
			//��Ҳ��Ȼ�������(insert)�����ݿ���
			Model::factory('witkey_tag')->setData($array)->create();
			Keke::show_msg('�ύ�ɹ�','admin/tool_tag/add','success');
		}
	}
	/**
	 * ��ǩԤ��
	 */
	function action_preview(){
		$tagid = $_GET['tag_id'];
		
		$taglist =keke_loaddata_class::get_tag(1);
		$tag_info = $taglist[$tagid];
		if($tag_info['tag_type']==9){
			//Ԥ�����
			keke_loaddata_class::preview_addgroup($tag_info['tagname'],$tag_info['loadcount']);
		}elseif($tag_info['tag_type']==5){
			//Ԥ���Զ������ 
			keke_loaddata_class::previewtag($tag_info);
		}
		 
	}
	
}