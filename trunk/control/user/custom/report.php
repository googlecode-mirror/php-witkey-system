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
	
	function action_index(){
		//����ȫ�ֱ��������԰�
		global $_lang , $_K;
		//Ҫ��ѯ���ֶ�       Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		//��� 	���ٱ��� 	���� 	ԭ�� 	���� 	״̬ 	ʱ��
		$fields = "`report_id`,`to_username`,`report_type`,`report_desc`,`report_file`,`report_status`,`on_time`,`op_result`";
		//�ܼ�¼��
		$count = intval($_GET['count']);
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����uri
		$base_uri = BASE_URL.'/index.php/user/custom_report';
		extract($this->get_url($base_uri));
		//���յ��ľٱ�
		$my = $_GET['my'];
		$my&&$_SESSION['uid'] and $where .= " and to_uid = '1'".$_SESSION['uid'] or $where .=" and uid = ".$_SESSION['uid'];
		$my and $base_uri = BASE_URL.'/index.php/user/custom_report?my=1';
		//��������
		$sear_key = $_GET['search_key'];
		$sear_key and $where .= " and report_desc like'%".$sear_key."%'";
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
}