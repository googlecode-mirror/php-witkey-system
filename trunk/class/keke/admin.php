<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/** 
 * @copyright keke-tech
 * @version v 2.2
 * @Modify by Chen
 */
Keke_lang::load_lang_class('keke_admin_class');
class Keke_admin {
	public $_uid;
	public function __construct() {
		$_SESSION ['uid'] and $this->_uid = $_SESSION ['uid'];
	}
	static function get_admin_menu() {
		global $_lang,$_K;
		$menuset_arr = Cache::instance()->get('admin_menu');
		
		if (! $menuset_arr) {
			$resource_obj = new Keke_witkey_resource();
			$resource_obj->setWhere ( "1=1 order by listorder asc" );
			$resource_arr = $resource_obj->query();
			$resource_submenu_obj = new Keke_witkey_resource_submenu();
			$resource_submenu_obj->setWhere ( "1=1 order by listorder" );
			$resource_sub_arr = $resource_submenu_obj->query ();
			
			$temp_arr = array ();
			$temp_arr2 = array ();
			$resource_set_arr = array ();
			$submenu_set_arr = array ();
			foreach ( $resource_arr as $r_tp ) {
				$resource_set_arr [$r_tp ['resource_id']] = $r_tp;
				$temp_arr [$r_tp ['submenu_id']] [] = $r_tp;
			}
			
			foreach ( $resource_sub_arr as $r_tp ) {
				$submenu_set_arr [$r_tp ['submenu_id']] = $r_tp;
				$temp_arr2 [$r_tp ['menu_name']] [] = array ('name' => $r_tp ['submenu_name'], 'items' => $temp_arr [$r_tp ['submenu_id']] );
			}
			
			$resource_arr = $temp_arr2;
			$menuset_arr = array ( 'menu' => $resource_arr, 'submenu' => $submenu_set_arr, 'resource' => $resource_set_arr );
			
            Cache::instance()->set('admin_menu', $menuset_arr,999999);
		}
		return $menuset_arr;
	}
	
	static function get_user_group() {
		global $kekezu;
		$group_arr = Keke::$_cache_obj->get ( "member_group_cache" );
		if (! $group_arr) {
			$membergroup_obj = new Keke_witkey_member_group ();
			$group_arr = $membergroup_obj->query();
			$temp_arr = array ();
			foreach ( $group_arr as $v ) {
				$temp_arr [$v ['group_id']] = $v;
				$temp_arr [$v ['group_id']] ['group_roles'] = explode ( ',', $v ['group_roles'] );
			}
			$group_arr = $temp_arr;
			Keke::$_cache_obj->set ( 'member_group_cache', $group_arr, null );
		}
		
		return $group_arr;
	}
	/**
	 * ����
	 */
	function screen_lock() {
		$_SESSION ['lock_screen'] = 1;
	}
	/**
	 * �������״̬
	 */
	function check_screen_lock() {
		$screen_lock = '0';
		isset ( $_SESSION ['lock_screen'] ) && $_SESSION ['lock_screen'] == 1 and $screen_lock = '1';
		return $screen_lock;
	}
	/**
	 * �������
	 * @param $unlock_num ʣ���������
	 * @param $unlock_pwd ��������
	 */
	function screen_unlock($unlock_num, $unlock_pwd) {
		global $kekezu;
		global $_lang;
				
		if ($unlock_num > 0) { //�����ж�
			/**��ȡ��ǰ��¼�û�����**/
			$admin_pwd = Dbfactory::get_count ( " select password from " . TABLEPRE . "witkey_member where uid = '" . $_SESSION ['uid'] . "'" );
			$unlock_pwd = md5 ( $unlock_pwd ); //��������
			if ($admin_pwd == $unlock_pwd) {
				$_SESSION ['lock_screen'] = '0';
				Keke::echojson ( '', '2' );
				die (); //�����ɹ�
			} else {
				if ($unlock_num > 1) {
					$_SESSION ['allow_times']=--$unlock_num;
					keke::echojson ( $_lang['unlock_fail'].".", '1', $unlock_num );
					die (); //����ʧ��
				} else { //���һ�β���ʧ��
					$_SESSION ['allow_times']='0';
					$_SESSION ['lock_screen'] = '0';
					$_SESSION ['admin_uid'] = '';
					$_SESSION ['admin_username'] = '';
					keke::echojson ( $_lang['wrong_times_much_login_again'], '0' );
					die ();
				}
			}
		}
	}
	/**
	 * ��̨Ȩ���ж�
	 * @param $roleid Ȩ�ޱ��
	 */
	function admin_check_role($roleid) {
		global $_K, $admin_info;
		$grouplist_arr = self::get_user_group ();
		
		if ($_SESSION ['uid'] != ADMIN_UID && ! in_array ( $roleid, $grouplist_arr [$admin_info ['group_id']] ['group_roles'] )) {
			echo "<script>location.href='index.php?do=main'</script>";
			die ();
		}
	}
	/**
	 * ��̨��������
	 * @param $ser_resource ����������
	 * @todo  ֧���ӵ���ֱ������
	 */
	function search_nav($ser_resource) {
		$resource_info = dbfactory::query ( " select resource_name,resource_url from " . TABLEPRE . "witkey_resource where INSTR(resource_name,'$ser_resource') > 0 " );
		if ($resource_info)
			return $resource_info;
		else {
			return dbfactory::query ( "select resource_name,resource_url from " . TABLEPRE . "witkey_resource a left join " . TABLEPRE . "witkey_resource_submenu b
			 on a.submenu_id = b.submenu_id where INSTR(b.submenu_name,'$ser_resource')>0" );
		}
	}
	/**
	 * ��ȡ��ݷ�ʽ
	 */
	public function get_shortcuts_list() {
		return dbfactory::query ( " select b.resource_id,b.resource_name,b.resource_url from " . TABLEPRE . "witkey_shortcuts a left join " . TABLEPRE . "witkey_resource b on a.resource_id = b.resource_id where a.uid = '$this->_uid' order by a.s_id desc " );
	}
	/**
	 *��ӿ�ݣ����ò˵�) 
	 *@param $uid  �û�id
	 *@param $r_id �������
	 */
	function add_fast_menu($r_id) {
		global $_lang;
		$shortcuts_obj = new Keke_witkey_shortcuts ();
		$in_shortcuts_list = Dbfactory::get_count( " select resource_id from " . TABLEPRE . "witkey_shortcuts where resource_id = '$r_id'" );
		if (! $in_shortcuts_list) {
			$shortcuts_obj->setUid ( $this->_uid );
			$shortcuts_obj->setResource_id ( $r_id );
			$success = $shortcuts_obj->create ();
			if ($success) {
				Keke::echojson ( $_lang['shortcuts_add_success'], '4' );
				die ();
			} else {
				Keke::echojson ( $_lang['shortcuts_add_fail'], '1' );
				die ();
			}
		} else {
			Keke::echojson ( $_lang['the_shortcuts_has_exist'], '0' );
			die ();
		}
	}
	/**
	 * �Ƴ���ݲ˵� 
	 * @param $r_id �������
	 */
	function rm_fast_menu($r_id) {
		global $_lang;
// 		$shortcuts_obj = new Keke_witkey_shortcuts ();
		$shortcuts_list = dbfactory::get_one ( " select uid,resource_id from " . TABLEPRE . "witkey_shortcuts where resource_id = '$r_id' and uid = '$this->_uid'" );
		if ($shortcuts_list) {
			if ($shortcuts_list ['uid'] != $this->_uid) {
				Keke::echojson ( $_lang['not_delete_others_shortcuts'], '2' );
			} else {
				$success = dbfactory::execute ( " delete from " . TABLEPRE . "witkey_shortcuts where resource_id = '$r_id' and uid = '$this->_uid'" );
				if ($success) {
					Keke::echojson ( $_lang['shortcuts_delete_success'], '4' );
					die ();
				} else {
					Keke::echojson ( $_lang['shortcuts_delete_fail'], '3' );
					die ();
				}
			}
		} else {
			Keke::echojson ( $_lang['please_choose_shortcut_menu'], '0' );
			die ();
		}
	}
	/**
	 * ��ȡ�ļ�����
	 */
	static function get_article_cate() {
		return Keke::get_table_data ( "*", "witkey_article_category", "", "listorder asc ", "", "", "art_cat_id", null );
	
	}
	/**
	 * ��̨��¼
	 * @param $username �û���
	 * @param $password ����
	 * @param $allow_times ʣ�ೢ�Դ���
	 */
	public function admin_login($username, $password, $allow_times, $formhash='') {
		global $_lang;
		global $kekezu;
	
		$login_limit = $_SESSION ['login_limit']; //�û���¼����ʱ��
		$remain_times = $login_limit - time (); //�����ٴε�¼ʱ��
		if ($login_limit && $remain_times > 0) { //���ڵ�¼ʱ�����Ʋ���ʱ��δ��
			$kekezu->echojson ( "login limit!", "8" );
			die ();
		} else {
			if (!Keke::formcheck($formhash, true)) {//���hashֵ
				$_SESSION ['allow_times'] -= 1;
				-- $allow_times == 0 and $this->set_login_limit_time ( '1' );
				$hash = Keke::formhash();
				$kekezu->echojson($_lang['repeat_form_submit'], 6, array('times'=>$allow_times, 'formhash'=>$hash));
				die();
			}
		
			$user_info = Keke_user_login::instance('keke')->set_username($username)
						 ->set_pwd($password)->login();
			//( $username, $password ); //�û���Ϣ
			$hash = Keke::formhash();
			if ($user_info === - 1) {
				$_SESSION ['allow_times'] -= 1;
				-- $allow_times == 0 and $this->set_login_limit_time ( '1' );
				$kekezu->echojson ( $_lang['username_input_error'], "6", array('times'=>$allow_times, 'formhash'=>$hash) );
				die ();
			} else if ($user_info === - 2) {
				$_SESSION ['allow_times'] -= 1;
				-- $allow_times == 0 and $this->set_login_limit_time ( '1' );
				$kekezu->echojson ( $_lang['password_input_error'], "5", array('times'=>$allow_times, 'formhash'=>$hash) );
				die ();
			}
			if (! $user_info) { 
				$_SESSION ['allow_times'] -= 1;
				-- $allow_times == 0 and $this->set_login_limit_time ( '1' );
				$kekezu->echojson ( $_lang['login_fail'], "4", array('times'=>$allow_times, 'formhash'=>$hash) );
				die ();
			} else {  
				$user_info = Keke_user::instance()->get_user_info( $user_info ); //��ȡ�û���Ϣ
			}
			$roles = self::get_user_roles($user_info['uid']);
		 
			if (! $user_info) {
				$_SESSION ['allow_times'] -= 1;
				-- $allow_times == 0 and $this->set_login_limit_time ( '1' );
				$kekezu->echojson ( $_lang['no_rights_login_backstage'], "3", array('times'=>$allow_times, 'formhash'=>$hash) );
				die ();
			} elseif (empty($roles)) {
				$_SESSION ['allow_times'] -= 1;
				-- $allow_times == 0 and $this->set_login_limit_time ( '1' );
				$kekezu->echojson ( $_lang['no_rights_login_backstage'], "2", array('times'=>$allow_times, 'formhash'=>$hash) );
				die ();
			} else {
				$_SESSION ['admin_uid'] =  $user_info ['uid'];
				$_SESSION ['admin_username'] = $user_info ['username'];
				$_SESSION['admin_gid']  = $user_info['group_id'];
				Keke::admin_system_log ( $user_info ['username'] . date ( 'Y-m-d H:i:s', time () ) . $_lang['login_system'] );
				
				$this->set_login_limit_time ();
				$kekezu->echojson ( $_lang['login_success'], "1" );
				die ();
			}
		}
	}
	/**
	 * ��ȡ�û�������Դid
	 * @param int $uid
	 * @return string
	 */
	public static function get_user_roles($uid = NULL){
		if($uid===NULL){
			$uid = $_SESSION['admin_uid'];
		}
		//�ж��û����Ƿ���Ȩ��
		$sql = "SELECT b.group_roles FROM :Pwitkey_space as a ".
				"left join :Pwitkey_member_group as b ".
				"on a.group_id = b.group_id ".
				"where a.uid = :uid ";
		
		return DB::query($sql)->tablepre(':P')->param(':uid', $uid)->get_count()->execute();
		 
	}
	/**
	 * ���õ�¼����ʱ��
	 * @param unknown_type $t
	 */
	public function set_login_limit_time($t = '') {
		$t and $_SESSION ['login_limit'] = time () + 3600 or $_SESSION ['login_limit'] = '';
	}
	/**
	 * ���Դ�������
	 * @param $times ʣ�����
	 */
	public function times_limit($times = null) {
		if (isset ( $times )) {
			$allow_times = $times;
		} else { //��ʼ��
			$_SESSION ['allow_times'] and $allow_times = $_SESSION ['allow_times'] or $allow_times = $_SESSION ['allow_times'] = '5';
		}
		return $allow_times;
	}
} 