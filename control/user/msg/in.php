		<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 用户中心-信息-收件箱
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_msg_in extends Control_user{
    
	/**
	 * @var 一级菜单选中项
	 */
	protected static $_default = 'msg';
    /**
     * 
     * @var 二级菜单选中项,空值不做选择
     */
	protected static $_left = 'in';
	
	function action_index($type='all'){
		//编号 发件人		主题 	时间
		$fields = '`msg_id`,`uid`,`username`,`to_uid`,`to_username`,`title`,`msg_status`,`view_status`,`on_time`';
		$query_fields = array('msg_id'=>'编号','title'=>'主题','on_time'=>'时间');
		
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'on_time';
		$base_uri = BASE_URL.'/index.php/user/msg_in/'.$type;
		$del_uri = BASE_URL.'/index.php/user/msg_in/del';
		extract($this->get_url($base_uri));
		//收件	条件
		switch ($type){
			case "all"://全部
				$where .=" and msg_status<>1 and to_uid = ".$_SESSION['uid'];
				break;
			case "unread"://未读
				$where .=" and view_status<>1 and msg_status<>1 and to_uid = ".$_SESSION['uid'];
				break;
			case "sys"://系统
				$where .=" and to_uid = ".$_SESSION['uid']." and uid<1 and msg_status<>1 ";
				break;
			case "unsys"://非系统
				$where .=" and to_uid = ".$_SESSION['uid']." and uid>1 and msg_status<>1 ";
				break;
		}
		
		$data_info = Model::factory('witkey_msg')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
		//显示分页的页数
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('user/msg/in');
	}
	function action_info(){
		$from = $_GET['from'];//用来判断是收件(in)还是发件(out)
		$date_arr = DB::select()->from('witkey_msg')->where('msg_id = '.$_GET['msg_id'])->get_one()->execute();
		if($_GET['msg_id']&& $date_arr['view_status']<1&&$from!='out'){
			DB::update('witkey_msg')->set(array('view_status'))->value(array(1))->where('msg_id = '.$_GET['msg_id'])->execute();
		}
		require Keke_tpl::template('user/msg/info');
	}
	function action_out_info(){
		self::$_left='out';
		
		require Keke_tpl::template('user/msg/info');
	}
	function action_del(){
		if($_GET['msg_id']){
			$this->del_msg_by_status($_GET['msg_id'], $_GET['status'], $_GET['is_sys']);
		}elseif($_GET['ids']){
			(array)$msg_arr = explode(',',$_GET['ids']);
			foreach ($msg_arr as $v){
				list($msg_id,$status,$is_sys) = explode('|', $v);
				$this->del_msg_by_status($msg_id, $status, !(bool)$is_sys);
			}
		}
		 
	}
	/**
	 * 删除或者改为状态
	 * @param int $msg_id
	 * @param int $status
	 * @param bool $is_sys
	 * @return int 
	 */
 	function del_msg_by_status($msg_id,$status,$is_sys){
		if($status == 0 and $is_sys==TRUE){
			return DB::update('witkey_msg')->set(array('msg_status'))->value(array(1))->where("msg_id = '$msg_id'")->execute();
		}else{
			return DB::delete('witkey_msg')->where("msg_id = '$msg_id'")->execute();
		}
	}
	//所有消息
	function action_all(){
		$this->action_index('all');
	}
	//未读消息
	function action_unread(){
		$this->action_index('unread');
	}
	//系统消息
	function action_sys(){
		$this->action_index('sys');
	}
	//非系统消息
	function action_unsys(){
		$this->action_index('unsys');
	}
}