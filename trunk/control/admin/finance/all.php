<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * @all ���ߣ�dengkang 
 * @version v 2.0
 * @date 2012-10-7 
 */
class Control_admin_finance_all extends Control_admin {
	
	/**
	 * ��������ʼ��ҳ��
	 * index �Ǳ���ģ�����·���Ҳ���index������͹��˰�
	 * �ӵ���ע�Ͱ�,���Ǳ���Ҫд��(*_*)!
	 */
	function action_index() {
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `fina_id`,`username`,`fina_action`,`fina_type`,`fina_cash`,`fina_cash`,`user_balance`,`fina_credit`,`user_credit`,`fina_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('fina_id'=>"������",'username'=>"�û���",'fina_cash'=>"���",'user_balance'=>"�û����");
		
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/finance_all";
		
        //��ȡ����������ֵ
        $fina_action_arr = keke_global_class::get_finance_action();		
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'fina_id';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_finance')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$fina_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
			
		require Keke_tpl::template('control/admin/tpl/finance/detail');
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
		if($_GET['fina_id']){  
			$where = 'fina_id = '.$_GET['fina_id'];
		//ɾ������,���������ͳһΪidsӴ����	
		}elseif($_GET['ids']){
			$where = 'fina_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_finance')->setWhere($where)->del();
	}
	
}
