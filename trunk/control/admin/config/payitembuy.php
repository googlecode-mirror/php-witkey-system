<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ֧������
 * @author Michael	
 * @version v 2.2
 * 2012-10-01
 */
class Control_admin_config_payitembuy extends Control_admin{
	/**
	 * ��ֵ�������¼�б�
	 */
	function action_index(){
		global $_K,$_lang;
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�	<td>{$v['record_id']}</td>
		$fields = ' `record_id`,`item_code`,`use_type`,`username`,`obj_type`,`use_cash`,`use_num`,`on_time`';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('record_id'=>$_lang['id'],'item_code'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/config_payitembuy";
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
	 
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_payitem_record')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		
		$add_service_type = keke_global_class::get_value_add_type ();
		
		$buy_use_type = array ("buy" => $_lang['buy'], "spend" => $_lang['spend'] );
		
		//�û������ܽ��
		
		$all_buy_pro =(float)DB::select('sum(use_cash*use_num)')->from('witkey_payitem_record')->where("use_type='buy'")->get_count()->execute();
		
		
		require Keke_tpl::template('control/admin/tpl/config/payitem_buy');
	}
	 
	
}