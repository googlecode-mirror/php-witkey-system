		<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-��Ϣ-�ռ���
 * @author Michael
 * @version 2.2
   2012-10-25
 */

class Control_user_msg_in extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'msg';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'in';
	
	function action_index($type='all'){
		//��� ������		���� 	ʱ��
		$fields = '`msg_id`,`to_username`,`title`,`msg_status`,`view_status`,`on_time`';
		
		
		$count = intval($_GET['count']);
		$this->_default_ord_field = 'on_time';
		$base_uri = BASE_URL.'/index.php/user/msg_in/ '.$type;
		extract($this->get_url($base_uri));
		//�ռ�	����
		switch ($type){
			case "all"://ȫ��
				$where .=" and to_uid = ".$_SESSION['uid'];
				break;
			case "unread"://δ��
				$where .=" and view_status<>1 and to_uid = ".$_SESSION['uid'];
				break;
			case "sys"://ϵͳ
				$where .=" and to_uid = ".$_SESSION['uid']." and uid<>1";
				break;
			case "unsys"://��ϵͳ
				$where .=" and to_uid = ".$_SESSION['uid']." and uid>1";
				break;
		}
		
		
		$data_info = Model::factory('witkey_msg')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$data_list = $data_info['data'];
		//��ʾ��ҳ��ҳ��
		$pages = $data_info['pages'];
		
		require Keke_tpl::template('user/msg/in');
	}
	function action_info(){
		$date_arr = DB::select()->from('witkey_msg')->where('msg_id = '.$_GET['msg_id'])->get_one()->execute();
		if($_GET['msg_id']&& $date_arr['view_status']<1){
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
			$where = 'msg_id = '.$_GET['msg_id'];
		}elseif($_GET['ids']){
			$where = 'msg_id in ('.$_GET['ids'].')';
		}
		//Model::factory('witkey_msg')->setWhere($where)->del();
	}
	//δ����Ϣ
	function action_unread(){
		$this->action_index('unread');
	}
	//ϵͳ��Ϣ
	function action_sys(){
		$this->action_index('sys');
	}
	//��ϵͳ��Ϣ
	function action_unsys(){
		$this->action_index('unsys');
	}
}