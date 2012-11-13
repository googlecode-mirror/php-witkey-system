<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ϵͳ��־
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-5-24����03:46:14
 */
class Control_admin_tool_log extends Control_admin{
	
	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `log_id`,`username`,`log_content`,`log_ip`,`log_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('log_id'=>$_lang['id'],'log_content'=>$_lang['name'],'log_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//tool������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���too_file So���ﲻ��дΪtool/file
		$base_uri = BASE_URL."/index.php/admin/tool_log";
		
		//��ӱ༭��uri,add���action �ǹ̶���
		//$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'log_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_system_log')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//�û���
		$group_arr = Keke_admin::get_user_group ();
		
		require Keke_tpl::template('control/admin/tpl/tool/log');
		
	}
	/**
	 * ��־��¼��ɾ��,֧�ֵ����ɾ��
	 */
	function action_del(){
		//ɾ������,�����file_id ����ģ���ϵ������������е�
		if($_GET['log_id']){
			$where = 'log_id = '.$_GET['log_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'log_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_system_log')->setWhere($where)->del();
		
	}
	
} //end
