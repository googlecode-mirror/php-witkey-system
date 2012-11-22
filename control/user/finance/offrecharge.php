<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-账号管理首页-用户充值
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_finance_offrecharge extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'finance';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'offrecharge';
	
	function action_index(){
		$fields = '`pay_id`,`payment`,`type`,`config`,`pay_name`,`status`';
		
		$base_uri = BASE_URL.'/index.php/finance/offrecharge';
		$del_uri = $base_uri.'/del';
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'pay_id';
		//获取分页条件
		extract($this->get_url($base_uri));
		//条件
		$where .= " and type = 'offline' and status = 1 ";
		
		$data_info = Model::factory('witkey_pay_api')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
// 		var_dump($data_list);
		//显示分页的页数
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