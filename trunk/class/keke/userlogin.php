<?php

/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-7-6����11:52:51
 */
keke_lang_class::load_lang_class ( 'keke_user_login_class' );
class keke_userlogin {
	protected $_space_obj;
	protected $_login_uid;
	protected $_username;
	protected $_password;
	protected $_account;
	protected $_space_info;
	//protected $_pwd;
	protected $_auth_record_obj;
	protected $_auth_email_obj;
	protected $_sys_config;
	protected $_accout_type;
	protected $_login_type;
	
	function __construct() {
		//��Ϣ��ʼ��
		global $kekezu;
		$this->_space_obj = new keke_witkey_space();
		$this->_auth_record_obj = new keke_witkey_auth_record();
		$this->_auth_email_obj = new keke_witkey_auth_email();
		$this->_sys_config = kekezu::$_sys_config;
	
		//�û���¼	
	

	}
	function account_init($account) {
		$this->_account = $account;
	
		//CHARSET=='gbk' and $account = kekezu::utftogbk($account);
	

	}
	function password_init($password) {
		$this->_password = $password;
	}
	function login_type($login_type) {
		$this->_login_type = intval ( $login_type );
	}
	/**
	 * �û���¼
	 * @param string $account
	 * @param string $password
	 * @param string $code   ��֤��
	 * @param int  $login_type 0=��ͨ��¼,1=oauthlogin,3=ͷ����¼
	 * @return string|multitype:unknown Ambigous <>
	 */
	function user_login($account, $password, $code = null, $login_type = 0) {
		global $_lang;
		($login_type == 3 and strtolower ( CHARSET ) == 'gbk') and $account = kekezu::utftogbk ( $account );
		$this->account_init ( $account );
		$this->password_init ( $password );
		$this->login_type ( $login_type );
		
		$code and $this->check_code ( $code );
		$accout_type = $this->get_login_type ();
		switch ($this->_sys_config ['user_intergration']) {
			case "1" :
				//�жϵ�¼��ʽ
				switch ($accout_type) {
					case 'mobile' :
						$user_info = $this->valid_moble_auth ();
						break;
					case 'email' :
						$user_info = $this->valid_email_auth ();
						break;
					case 'username' :
						$user_info = $this->valid_username ();
						break;
				}
				if ($user_info ['password'] !== $password) {
					$this->show_msg ( $_lang ['password_input_error'], 3 );
				} elseif ($user_info ['status'] == 2) {
					
					$this->show_msg ( $_lang ['account_has_freeze_or_unactivate'], 4 );
				} elseif ($user_info ['status'] == 3) {
					$this->show_msg ( $_lang ['account_has_not_excited'], 6 );
				}
				break;
			case "2" :
			case "3" :
				$accout_type != 'username'&&$this->show_msg ( $_lang ['integrated_model_nust_use_name'], 5 );
				$user_info = $this->user_intergration ( $account, $password );
				break;
		}
		
		return $user_info;
	}
	
	//�����֤��
	function check_code($login_code) {
		global $_lang;
		if ($login_code) {
			$img = new Secode_class ();
			$login_code = $img->check ( $login_code, 1 );
			if (! $login_code) {
				$this->show_msg ( $_lang ['verification_code_input_error'], 7 );
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	
	//ƥ���¼��ʽ      �����˺�����
	function get_login_type() {
		if (kekezu::is_email ( $this->_account )) {
			$this->_account_type = 'email';
		} elseif (kekezu::is_mobile ( $this->_account )) {
			$this->_account_type = 'mobile';
		} else {
			$this->_account_type = 'username';
		}
		return $this->_account_type;
	}
	
	//�ֻ������ж�
	function valid_moble_auth() {
		global $_lang;
		$sql = sprintf ( "select * from %switkey_auth_record where auth_code='mobile' and auth_status = 1", TABLEPRE, $this->_account );
		$auth_arr = db_factory::get_one ( $sql );
		if ($auth_arr) {
			$user_info = keke_user_class::get_user_info ( $auth_arr [uid] );
			$user_info [login_type] = 'mobile';
			return $user_info;
		} else {
			$this->show_msg ( $_lang ['no_tel_auth_not_login'], 5 );
		}
	}
	
	//���������ж�
	function valid_email_auth() {
		global $_lang;
		$this->_auth_email_obj->setWhere ( "email  = '$this->_account' and auth_status=1" );
		$auth_info = $this->_auth_email_obj->query_keke_witkey_auth_email ();
		$auth_info = $auth_info [0];
		if ($auth_info) {
			$user_info = keke_user_class::get_user_info ( $auth_info [uid] );
			$user_info [login_type] = 'email';
			return $user_info;
		} else {
			$this->show_msg ( $_lang ['no_email_auth_not_login'], 5 );
		}
	
	}
	
	//�˺ŵ�¼�ж�
	function valid_username() {
		global $_lang;
		$user_info = kekezu::get_user_info ( $this->_account, 1 );
		
		if ($user_info) {
			$user_info ['login_type'] = 'username';
			return $user_info;
		} else {
			
			$this->show_msg ( $_lang ['you_input_password_not_right'], 3 );
		}
	}
	
	//�ж��Ƿ����û�����
	function user_intergration($username, $pwd) {
		global $_lang;
		//û�п��û�����
		switch ($this->_sys_config ['user_intergration']) {
			case 2 :
				$notify = "UCenter";
				require_once S_ROOT . './keke_client/ucenter/client.php';
				//$pwd Ϊ��MD5
				$uc_info = uc_user_login ( $username, $pwd );
				if ($uc_info ['0'] > 0) {
					$u = array ('uid' => $uc_info ['0'], 'username' => $uc_info ['1'], 'email' => $uc_info ['3'] );
				} else {
					$u = $uc_info ['0'];
				}
				break;
			case 3 :
				
				$notify = "PW";
				require_once (S_ROOT . './keke_client/pw_client/uc_client.php');
				//$PWD  
				$uc_info = uc_user_login ( $username, $pwd, 0 );
				if ($uc_info ['status'] != 1) {
					
					$u = $uc_info ['status'];
				} else {
					$u = array ('uid' => $uc_info ['uid'], 'username' => $uc_info ['username'], 'email' => $uc_info ['email'] );
				}
				
				break;
		}
		
		if ($u == - 2) {
			$this->show_msg ( $_lang ['you_input_password_not_right'], 3 );
		} elseif ($u == - 1) {
			$this->show_msg ( $_lang ['you_input_username_not_exist'], 4 );
		} else {
			$exists = db_factory::get_count ( sprintf ( " select uid from %switkey_member where uid='%d' ", TABLEPRE, $u ['uid'] ) );
			if (! $exists) { //�������û�UID��ͻ
				$reg_obj = new keke_register_class ();
				$reg_obj->_reg_pwd = $pwd;
				$reg_obj->save_userinfo ( $u ['username'], $u ['email'], $u ['uid'] );
			}
		}
		
		//is_array($u) and $user_info = kekezu::get_user_info($u['uid']);
		

		return $u;
	}
	
	//�����û���Ϣ
	public function save_user_info($user_info, $ckb_cookie = 1, $login_type = 0, $oauth_login = 1) {
		global $kekezu, $_K;
		global $_lang;
		$_SESSION ['uid'] = $user_info ['uid'];
		$_SESSION ['username'] = $user_info ['username'];
		/*	$_SESSION [$uid."_".kekezu::$_sys_config[website_url]] = 1;*/
		$oauth_login = intval ( $oauth_login );
		$login_type = $this->_login_type;
		if (isset($ckb_cookie)&&$ckb_cookie == 1) {
			$login_arr = array ('uid' => $user_info ['uid'], 'username' => $user_info ['username'] );
			$c_v = keke_encrypt_class::encode ( serialize ( $login_arr ) );
			setcookie ( "user_login", $c_v, time () + 3600 * 24 );
		}
		
		if ($_K ['refer']) {
			$r = $_K ['refer'];
		
		} else {
			$r = 'index.php';
		}
		if ($this->_sys_config ['user_intergration'] != 1) { //����֮���  ��ȡͬ����½��js 
			$synhtml = keke_user_class::user_synlogin ( $user_info ['uid'], $this->_password );
		}
		$synhtml = isset($synhtml)?$synhtml:"";
		$user_obj = new Keke_witkey_space_class ();
		$user_obj->setLast_login_time ( time () );
		$user_obj->setWhere ( "uid = '{$user_info['uid']}'" );
		$user_obj->edit_keke_witkey_space ();
		
		keke_user_class::set_union_relation ( $user_info ['uid'] ); //�������˹�ϵ
		db_factory::execute ( sprintf ( "update %switkey_member_oltime set last_op_time=%d where uid = %d", TABLEPRE, time (), $user_info ['uid'] ) );
		
		/**
		 * �ƹ��ϵ����
		 */
		if (isset($_COOKIE ['user_prom_event'])&&$_COOKIE ['user_prom_event']) {
			$kekezu->init_prom ();
			$prom_obj = kekezu::$_prom_obj;
			$url_data = $prom_obj->extract_prom_cookie ();
			$url_data ['p'] == 'reg' or $prom_obj->create_prom_relation ( $user_info ['uid'], $user_info ['username'], $url_data, '2' );
		}
		
		if ($login_type == 1) {
			if (strtolower ( $_SERVER ['REQUEST_METHOD'] ) == 'post') {
				kekezu::show_msg ( $_lang ['notice_message'], $r, 1, $_lang ['login_success'] . "$synhtml", "alert_right" );
			} elseif (strtolower ( $_SERVER ['REQUEST_METHOD'] ) == 'get') {
				echo "$synhtml<script>window.location.href='$r';</script>";
				die ();
			}
		
		} else if ($login_type == 3) {
			
			$info = $user_info;
			$return_info ['uid'] = $info ['uid'];
			$return_info ['username'] = $info ['username'];
			$return_info ['balance'] = intval ( $info ['balance'] );
			$return_info ['credit'] = intval ( $info ['credit'] );
			$return_info ['pic'] = keke_user_class::get_user_pic ( $user_info ['uid'] );
			$return_info ['syn'] = $synhtml;
			($user_info ['uid'] == ADMIN_UID || $user_info ['group_id'] > 0) and $return_info ['is_admin'] = 1;
			kekezu::echojson ( $_lang ['login_success'], '1', $return_info );
			die ();
		
		} else {
			
			if ($oauth_login == 1) {
				kekezu::show_msg ( $_lang ['notice_message'], $r, 1, $_lang ['login_success'] . "$synhtml", "alert_right" );
			
		//kekezu::$_tpl_obj->xml_out ( "<h1>��¼�ɹ�!</h1> $synhtml <script>window.location.href='$r'</script>" );
			} else {
				echo "$synhtml<script>window.location.href='$r';hideWindow('oauth_login_frm1')</script>";
			}
		}
	}
	
	/*	//��
	function login_binding_account($oauth_user_info, $user_info, $type) {
		
		$oauth_obj = new Keke_witkey_member_oauth_class ();
		$oauth_obj->setAccount ( $oauth_user_info ['name'] );
		$oauth_obj->setOauth_id ( $oauth_user_info ['account'] );
		$oauth_obj->setSource ( $type );
		$oauth_obj->setUid ( $user_info [uid] );
		$oauth_obj->setUsername ( $user_info [username] );
		$oauth_obj->setOn_time ( time () );
		$oauth_obj->create_keke_witkey_member_oauth () or kekezu::show_msg ( '��ʾ��Ϣ', 'index.php?do=user_login', 2, '��ʧ��' );
	
	}*/
	function show_msg($content, $status) {
		global $_lang;
		switch ($this->_login_type) {
			case "3" :
				kekezu::echojson ( $content, $status );
				die ();
				break;
			case "0" :
			case "1" :
				kekezu::show_msg ( $_lang ['friendly_notice'], '', 5, $content );
				break;
		}
	}

}