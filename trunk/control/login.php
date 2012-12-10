<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 *
 * @copyright keke-tech
 * @author Michael
 * @version v 2.2 2012-11-06
 *          
 */
class Control_login extends Control_front {
	/**
	 * ��¼ҳ��
	 */
	function action_index() {
		global $_K;
		$api_open = unserialize($_K['oauth_api_open']);
		$api_name = Keke_global::get_open_api();
		
		require Keke_tpl::template ( 'login' );
	}
	/**
	 * �û���¼
	 */
	function action_login($is_bind=FALSE) {
		
		global $_K;
		Keke::formcheck ( $_POST ['formhash'] );
		$_POST = Keke_tpl::chars ( $_POST );
		$account = $_POST ['txt_account'];
		$pwd = $_POST ['pwd_password'];
		//�Զ���¼
		$remember = (bool)$_POST['auto_login'];
		//��¼����
		$type = $this->get_account_type($account);
		
		$login_obj = Keke_user_login::instance ();
		$login_obj->set_username ( $account )->set_pwd($pwd)->set_remember_me($remember);
	 	//��¼
		$res = $login_obj->login ($type);
		 
		$uri = 'login';
		if(array_key_exists((int)$res, Keke_user_login::$_status)){
			$msg = Keke_user_login::$_status[$res];
			$t = 'error';
		}else {
			$msg = '��¼�ɹ�';
			//�Ƿ�󶨵�¼
			if($is_bind===TRUE){
				$u = Keke_oauth_login::instance($_POST['type'])->get_login_info();
				if(!self::check_bind($_POST['type'], $u['username'])){
					self::user_bind($_POST['type']);
				}
			}
			if($_K ['user_intergration']!=1){
				//ͬ����¼�Ĵ���
				$msg .= $res;
			}
			$t = 'success';
			if($this->request->referrer()==$this->request->url(true)){
				$uri = 'user/index';
			}else{
				$uri = Cookie::get('last_page');
			}
		}
		Keke::show_msg ( $msg, $uri, $t );
	}
	/**
	 * �õǳ�
	 */
	function action_logout(){
		$res = Keke_user_login::instance()->logout();
		$refer = $this->request->referrer();
		Cookie::set('last_page', $refer);
		Keke::show_msg('�ɹ��˳�'.$res,$refer,'success');
	}
	/**
	 * �ж��˺ţ�ȷ���ǵ�¼����
	 * @param string $var
	 * @return int (0�û���,1uid,2����,3�ֻ�)
	 */
	function get_account_type($var){
	     if(Keke_valid::email($var)){
	     	return 2;
	     }elseif(Keke_valid::phone($var,11)){
	     	return 3;
	     }elseif(Keke_valid::digit($var)){
	     	return 1;	
	     }else{
	     	return 0;
	     }
	}
	/**
	 * OAUTH��¼
	 */
	function action_oauth(){
		global $_K;
		
		$api_open = unserialize($_K['oauth_api_open']);
		$api_name = Keke_global::get_open_api();
		$type = $_GET['type'];
		 
		if(Keke_valid::not_empty($type)){
			$u = Keke_oauth_login::instance($type)->get_login_info();
			//�������˺Ű󶨹�����ֱ�ӵ�¼�ɹ�
			$bind_info = self::check_bind($type, $u['username']);
			if(Keke_valid::not_empty($bind_info)){
				Keke_user_login::instance()->complete_login($bind_info['uid'], $bind_info['username']);
				$this->request->redirect(Cookie::get('last_page'));
			}
		}
		require Keke_tpl::template("oauth_login");
	}
	
	function action_ologin(){
		global $_K,$ouri,$code;
		$type = $_GET['type'];
		//���access_token ��ֵ,���ص�index
		if($_SESSION[$type.'_token']['access_token']){
			$this->request->redirect('login/oauth?type='.$type);
		}
		//�ص�ҳ��
		$ouri = $_K['website_url'].'/index.php/login/call/'.$type;
		//url ��ַ����
		$ouri = urlencode($ouri);
		 
		if($_GET['back']){
			Keke_oauth_login::instance($type)->get_access_token();
			$this->request->redirect('login/oauth?type='.$type);
		}else{
			//oauth ��¼��֤�ĵ�ַ
			$to_url =  Keke_oauth_login::instance($type)->get_auth_url($ouri);
			$to_url = urldecode($to_url);
			$this->request->redirect($to_url);
		}
	}
	
	function action_call(){
		global $_K;
		$type = $this->request->param('id');
		if($_GET){
			//����Ѷ��΢����������һ����չ����,ҲҪ�õ�
			$ext = http_build_query($_GET);
		}
		$uri = $_K['website_url'].'/index.php/login/ologin?back=1&type='.$type.'&'.$ext;
		$this->request->redirect($uri);
	}
	/**
	 * oatuh ��¼�ɹ���kekeϵͳ��¼��������˺����oauth �˺�
	 */
	function action_bind(){
	    $this->action_login(TRUE);
	    
	}
	/**
	 *  �ж�����˺��Ƿ��а�
	 * @param String $type   ΢������
	 * @param string $nick   ΢���˺�����
	 * @return array ���˺ŵ�uid,username
	 */
	static public function check_bind($type,$account){
		$where = "type ='$type' and account='$account'";
		return DB::select('uid,username')->from('witkey_member_oauth')->where($where)->get_one()->execute();
	}
	/**
	 * ���û���Ϣ���
	 * @param string $type (sina,ten,qq..)
	 */
	static public function user_bind($type){
		//���˺�
		$columns = array('type','account','uid','username');
		$u = Keke_oauth_login::instance($type)->get_login_info();
		$values = array($type,$u['username'],$_SESSION['uid'],$_SESSION['username']);
		//д��󶨿�
		DB::insert('witkey_member_oauth')->set($columns)->value($values)->execute();
	}
	
	
} //end