<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź�����ҳ-��֧��ϸ
 * @author Michael
 * @version 3.0
   2012-12-03
 */

class Control_user_finance_detail extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'finance';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'detail';
	
	function action_index(){
		
		 $this->get_data();
	}
	
	function action_in(){
		$this->get_data('in');
	}
	function action_out(){
		$this->get_data('out');
	}
	
	/**
	 * ������ϸ
	 * @param string $type (in,out)
	 */
	function get_data($type=NULL){
		
		$fields = "`fina_type`,`fina_cash`,`fina_credit`,`user_balance`,`user_credit`,`fina_mem`,`fina_time`";
		
		$query_fields = array('fina_cash'=>'���','fina_mem'=>'����','fina_time'=>'ʱ��');
		
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'fina_time';
		$b_uri = BASE_URL.'/index.php/user/finance_detail';
		$base_uri = $b_uri.'/'.$type;
 
		extract($this->get_url($base_uri));
		
		$where .= ' and uid = '.$_SESSION['uid'];
		if($type!==NULL){
			//������֧��
			$where .= " and fina_type='$type'";
		}
		
		$data_info = Model::factory('witkey_finance')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		//echo $pages['page'];die;
		
		require Keke_tpl::template('user/finance/detail');
	}
	
	
	
}