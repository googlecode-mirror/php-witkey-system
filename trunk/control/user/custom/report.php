<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�ͷ�������ҳ --�ٱ�
 * @author Michael
 * @version 2.2
   2012-10-25
 */
class Control_user_custom_report extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'custom';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'report';
	/**
	 * �Ҿٱ���
	 * @param string $my
	 */
	function action_index($my = NULL){
		//����ȫ�ֱ��������԰�
		global $_lang , $_K;
		//Ҫ��ѯ���ֶ�       Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		//��� 	���ٱ��� 	���� 	ԭ�� 	���� 	״̬ 	ʱ��
		$fields = "`report_id`,`to_username`,`report_type`,`report_desc`,`report_file`,`report_status`,`on_time`,`op_result`";
		//��ѯ�ֶ�
		$query_fields = array('report_id'=>'���','on_time'=>'ʱ��','report_desc'=>'ԭ��');
		//�ܼ�¼��
		$count = intval($_GET['count']);
		//��ǰҳ
		$page = intval($_GET['page']);
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����uri
		$base_uri = BASE_URL.'/index.php/user/custom_report';
		//��ȡ��ҳ����
		extract($this->get_url($base_uri));
		//���յ��ľٱ�
		$m = $my;
		//$m&&$_SESSION['uid'] and $where .= " and to_uid = '1'".$_SESSION['uid'] or $where .=" and uid = ".$_SESSION['uid'];
		$m and $base_uri = BASE_URL.'/index.php/user/custom_report/my';
		//��ѯ     ��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_report')->get_grid($fields,$where,$base_uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//��������
		$trans_status = $this->_trans_status;
		//�ٱ�����
		$rp_type =  keke_report_class::get_report_type();
		require Keke_tpl::template('user/custom/report');
	}
	/**
	 * ���յ���
	 */
	function action_my(){
		$this->action_index('my');
	}
	
	
}