<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź�����ҳ-�û���ֵ
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_finance_offrecharge extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'finance';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'offrecharge';
	
	function action_index(){
		$fields = '`pay_id`,`payment`,`type`,`config`,`pay_name`,`status`';
		
		$base_uri = BASE_URL.'/index.php/finance/offrecharge';
		$del_uri = $base_uri.'/del';
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'pay_id';
		//��ȡ��ҳ����
		extract($this->get_url($base_uri));
		//����
		$where .= " and type = 'offline' and status = 1 ";
		
		$data_info = Model::factory('witkey_pay_api')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
// 		var_dump($data_list);
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('user/finance/offrecharge');
	}
	function get_bank_pic(){
		$bank_pic = array(
				
				);
	}
	function get_ten_bank_type(){
		static $bank = array(
				"1001"=>"17",
				"1002"=>"10",
				"1003"=>"2",
				"1004"=>"9",
				"1005"=>"1",
				"1006"=>"4",
				"1008"=>"8",
				"1009"=>"27",
				"1010"=>"18",
				"1020"=>"5",
				"1021"=>"7",
				"1022"=>"3",
				"1024"=>"20",
				"1025"=>"22",
				"1027"=>"6",
				"1032"=>"11",
				"1033"=>"14",
				"1052"=>"19",
				"8001"=>"logo",
		);
		return $bank;
	}
}