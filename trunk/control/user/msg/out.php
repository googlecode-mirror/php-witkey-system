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
		$fields = '`msg_id`,`uid`,`username`,`to_uid`,`to_username`,`title`,`msg_status`,`view_status`,`on_time`';
		$base_uri = BASE_URL.'/index.php/user/msg_out';
		$del_uri = $base_uri.'/del';
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'on_time';
		extract($this->get_url($base_uri));
		
		//发件条件
		$where .=" and msg_status<>2 and uid = ".$_SESSION['uid'];
		
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
	function action_del(){
		if($_GET['msg_id']){
			$where = 'msg_id = '.$_GET['msg_id'];
		}elseif($_GET['ids']){
			$where = 'msg_id in ('.$_GET['ids'].')';
		}
		$this->action_msg_status($where);
	}
	function action_msg_status($where){
		$res = DB::select('msg_status,uid')->from('witkey_msg')->where($where)->get_one()->execute();
		if($res['msg_status'] == 0 && $res['uid']){
			DB::update('witkey_msg')->set(array('msg_status'))->value(array(2))->where($where)->execute();
			keke::show_msg('删除成功','/user/msg_out',"success");
		}else{
			DB::delete('witkey_msg')->where($where)->execute();
			keke::show_msg('删除成功','/user/msg_out',"success");
		}
	}
}