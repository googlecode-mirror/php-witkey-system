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
		$query_fields = array('title'=>'主题','on_time'=>'时间');
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
 
	function action_del(){
		if($_GET['msg_id']){
			//删除命令是否来于查看页面
			if($_GET['ac'] == 'view'){
				$next_msg_id = $this->to_next_one($_GET['msg_id']);
				$this->del_msg_by_status($_GET['msg_id'], $_GET['status'], $_GET['is_sys']);
				$next_msg_id and Keke::show_msg('删除成功','user/msg_in/info?&from=out&msg_id='.$next_msg_id) or Keke::show_msg('删除成功','user/msg_out');
			}else{
				$this->del_msg_by_status($_GET['msg_id'], $_GET['status'], $_GET['is_sys']);
			}
			
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
			return DB::update('witkey_msg')->set(array('msg_status'))->value(array(2))->where("msg_id = '$msg_id'")->execute();
		}else{
			return DB::delete('witkey_msg')->where("msg_id = '$msg_id'")->execute();
		}
	}
	function to_next_one($msg_id){
		return $res = DB::select('msg_id')->from('witkey_msg')->where(' msg_status<>2 and msg_id <'.$msg_id.' and uid='.$_SESSION['uid'])->get_count()->execute();
	}
}