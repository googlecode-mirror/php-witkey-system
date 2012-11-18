<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-信息-发件箱
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_msg_out extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'msg';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'out';
	
	function action_index(){
		global $_K,$_lang;
		$fields = '`msg_id`,`username`,`to_username`,`title`,`msg_status`,`on_time`';
		$base_uri = BASE_URL.'/index.php/user/msg_out';
		$del_uri = $base_uri.'/del';
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'on_time';
		extract($this->get_url($base_uri));
		
		//发件条件
		$where .=" and uid = ".$_SESSION['uid'];
		
		$data_info = Model::factory('witkey_msg')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		$data_list = $data_info['data'];
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('user/msg/out');
	}
	//编辑
	function action_edit(){
		if($_GET['msg']){
			$date_arr = DB::select()->from('witkey_msg')->where('msg_id = '.$_GET['msg_id'])->get_one()->execute();
		}
	}
}