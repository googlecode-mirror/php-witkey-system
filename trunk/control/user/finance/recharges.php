<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź�����ҳ-�û���ֵ��¼
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_finance_recharges extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'finance';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'recharges';
	
	function action_index(){
		//���		��� 	�˻� 	״̬ 	ʱ��
		$fields = '`rid`,`cash`,`bank`,`status`,`pay_time`';
		$query_fields = array('rid'=>'���','status'=>'״̬','pay_time'=>'ʱ��');
		
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'pay_time';
		$base_uri = BASE_URL.'/index.php/user/finance_recharges	';
		extract($this->get_url($base_uri));
		//�ռ�	����
		$where .= ' and uid = '.$_SESSION['uid'];
		$data_info = Model::factory('witkey_recharge')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('user/finance/recharges');
	}
}