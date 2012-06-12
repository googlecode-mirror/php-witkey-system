<?php

/**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-7-6上午11:52:51
 */

keke_lang_class::load_lang_class ( 'keke_register_class' );
class keke_register_class {
	
	protected $_space_obj;
	protected $_member_obj;
	protected $_sys_config;
	protected $_reg_username;
	protected $_reg_type;
	public $_reg_pwd;
	protected $_reg_email;
	protected $_reg_ip;
	protected $_message_obj;
	protected $_check_code;
	protected $_oltime_obj;
	
	function __construct($reg_type = 1) {
		global $kekezu;
		$this->_space_obj = new Keke_witkey_space_class ();
		$this->_member_obj = new Keke_witkey_member_class ();
		$this->_sys_config = kekezu::$_sys_config;
		$this->_message_obj = new keke_msg_class ();
		$this->_oltime_obj = new Keke_witkey_member_oltime_class ();
		$this->_reg_ip = kekezu::get_ip ();
		$this->_reg_type = intval ( $reg_type );
	
		//keke_lang_class::loadlang('keke_register_class','public'); 
	}
	//用户注册成功返回userinfo
	function user_register($reg_username, $reg_pwd, $reg_email, $reg_code, $check_code = true, $old_pwd = null) {
		global $kekezu;
		global $_lang;
		$reg_uid = null;
		//初始化信息
		$this->_reg_username = $reg_username;
		$this->_reg_pwd = $reg_pwd;
		$this->_reg_email = $reg_email;
		$this->_check_code = $check_code;
		
		//判断登录的规则
		$this->check_all ( $reg_username, $reg_email, $reg_code );
		//判断是否整合
		switch (kekezu::$_sys_config ['user_intergration']) {
			case 2 :
				$notify = "UCenter";
				require_once S_ROOT . './keke_client/ucenter/client.php';
				$reg_uid = uc_user_register ( $this->_reg_username, $old_pwd, $this->_reg_email );
				$exists = dbfactory::get_count ( sprintf ( " select count(uid) from %switkey_member where uid='%d'", TABLEPRE, $reg_uid ) );
				if ($exists) { //与三方用户UID冲突
					$this->show_msg($_lang ['warning_local_and'] . $notify . $_lang ['user_table_primary_violation_notice'],'',2);
				}
				break;
			case 3 :
				$notify = "PW";
				require_once (S_ROOT . './keke_client/pw_client/uc_client.php');
				$reg_uid = uc_user_register ( $this->_reg_username, $this->_reg_pwd, $this->_reg_email );
				$exists = dbfactory::get_count ( sprintf ( " select uid from %switkey_member where uid='%d'", TABLEPRE, $reg_uid ) );
				if ($exists) { //与三方用户UID冲突
					$this->show_msg($_lang ['warning_local_and'] . $notify . $_lang ['user_table_primary_violation_notice'],'',2);
				}
				break;
		}
		return $this->save_userinfo ( $this->_reg_username, $this->_reg_email, $reg_uid ); //将数据信息写入本地库
	}
	
	//注册成功后登录
	function register_login($userinfo) {
		global $kekezu;
		global $_lang;
		global $_K;
		//写入session
		$_SESSION ['uid'] = $userinfo ['uid'];
		$_SESSION ['username'] = $userinfo ['username'];
		
		//发email通知
		if ($this->_message_obj->validate ( 'reg' ) && $this->_sys_config ['allow_reg_action'] == 0) {
			$this->_message_obj->send_message ( $userinfo ['uid'], $userinfo ['username'], 'reg', $_lang ['register_success'], array (), $userinfo ['email'] );
		}
		$c = $_COOKIE;
		$r = "index.php?do=register_wizard&refer=" . $_K ['refer'];
		/**
		 * 产生推广
		 */
		if (isset ( $_COOKIE ['user_prom_event'] )) {
			$kekezu->init_prom ();
			$prom_obj = kekezu::$_prom_obj;
			$url_data = $prom_obj->extract_prom_cookie ();
			$prom_obj->create_prom_relation ( $userinfo ['uid'], $userinfo ['username'], $url_data );
			$url_data ['p'] == 'reg' and $obj_id = $userinfo ['uid'] or $obj_id = $url_data ['o'];
			$prom_obj->create_prom_event ( $url_data ['p'], $userinfo ['uid'], $obj_id );
		}
		$synhtml = keke_user_class::user_synlogin ( $userinfo ['uid'], md5 ( $this->_reg_pwd ) );
		
		//kekezu::$_tpl_obj->xml_out ( "<h1>登录成功!</h1> $synhtml <script>window.location.href='$r'</script>" );
		if ($userinfo ['status'] == '3') {
			$_SESSION ['uid'] = '';
			$_SESSION ['username'] = '';
			$arr = explode ( "@", $userinfo ['email'] );
			$mail_url = "http://mail." . $arr [1];
			$this->show_msg($_lang ['register_success_and_excite'] . "$synhtml",$mail_url,1);
		} else {
			$this->show_msg($_lang ['register_success'] . "$synhtml",$r,1);
		}
	}
	
	//写入用户信息
	function save_userinfo($reg_username, $reg_email, $reg_uid = null) {
		
		//获取密码		
		$slt = kekezu::randomkeys ( 6 );
		$pwd = keke_user_class::get_password ( $this->_reg_pwd, $slt );
		
		$this->_member_obj->setUid ( $reg_uid );
		$this->_member_obj->setEmail ( $reg_email );
		$this->_member_obj->setUsername ( $reg_username );
		$this->_member_obj->setPassword ( $this->_reg_pwd );
		$this->_member_obj->setRand_code ( $slt );
		$reg_member_uid = $this->_member_obj->create_keke_witkey_member ();
		//写入member_oltime
		$this->_oltime_obj->setUid ( $reg_member_uid );
		$this->_oltime_obj->setUsername ( $reg_username );
		$this->_oltime_obj->setLast_op_time ( time () );
		$this->_oltime_obj->setOnline_total_time ( 0 );
		$this->_oltime_obj->create_keke_witkey_member_oltime ();
		
		if ($reg_member_uid) {
			keke_user_class::set_union_relation ( $reg_member_uid ); //更新联盟关系
			/*初始化角色等级头衔*/
			$buyer_level = keke_user_mark_class::get_mark_level ( 0, '2' ); //买家头衔
			$seller_level = keke_user_mark_class::get_mark_level ( '0', '1' ); //卖家头衔
			$this->_space_obj->setUid ( $reg_member_uid );
			$this->_space_obj->setUsername ( $reg_username );
			
			$this->_space_obj->setPassword ( $this->_reg_pwd );
			$this->_space_obj->setSec_code ( $pwd );
			$this->_space_obj->setEmail ( $reg_email );
			$this->_space_obj->setReg_time ( time () );
			// 			$this->_space_obj->setBalance(10000);
			$this->_space_obj->setReg_ip ( $this->_reg_ip );
			$this->_space_obj->setBuyer_level ( serialize ( $buyer_level ) );
			$this->_space_obj->setSeller_level ( serialize ( $seller_level ) );
			
			$space_id = $this->_space_obj->create_keke_witkey_space ();
		}
		
		$info = array ('uid' => $reg_member_uid, 'username' => $reg_username, 'email' => $reg_email );
		//判断是否需要邮件激活，并发发送激活码到指定的邮箱
		

		$this->_sys_config ['allow_reg_action'] == 1 and keke_user_class::send_email_action_user ( $info );
		return $reg_member_uid;
	}
	
	//判断数据是否合法
	function check_all($reg_username, $reg_email, $reg_code) {
		global $_lang;
		$res1 = $this->check_ip ();
		$res1 === true or $this->show_msg($res1,'index.php?do=register',2);
		
		$res2 = $this->check_username ( $reg_username );
		$res2 === true or $this->show_msg($res2,'index.php?do=register',2);
		
		$res3 = $this->check_email ( $reg_email );
		$res3 === true or $this->show_msg($res3,'index.php?do=register',2);
		
		if ($this->_check_code == true) {
			
			$res4 = $this->check_code ( $reg_code );
			$res4 === true or $this->show_msg($res4,'index.php?do=register',2);
		}
	
	}
	
	//判断用户名
	function check_username($reg_username) {
		global $_lang;
		//判断是否是email
		if (function_exists ( "filter_var" )) {
			filter_var ( $reg_username, FILTER_VALIDATE_EMAIL ) and $this->show_msg($_lang ['username_can_not_email'],'index.php?do=register',2);
			kekezu::is_mobile ( $reg_username ) and $this->show_msg($_lang ['username_can_not_phone_number'],'index.php?do=register',2);
		} else {
			
			kekezu::is_email ( $reg_username ) and $this->show_msg($_lang ['username_can_not_email'],'index.php?do=register',2);
			kekezu::is_mobile ( $reg_username ) and $this->show_msg($_lang ['username_can_not_phone_number'],'index.php?do=register',2);
		}
		
		$check_username = trim ( $reg_username );
		if (empty ( $check_username )) {
			return $_lang ['username_is_empty'];
		}
		if (kekezu::k_strpos ( $check_username )) {
			return $_lang ['username_illegal'];
		}
		if (kekezu::check_user_by_name ( $check_username, 1 )) {
			return $_lang ['user_has_exist'];
		}
		if ($this->_sys_config ['user_intergration'] == 2) {
			$result = keke_user_class::user_checkname ( $check_username );
			if ($result == - 1) {
				return $_lang ['username_illegal'];
			} else if ($result == - 2) {
				return $_lang ['contain_allow_register_words'];
			} else if ($result == - 3) {
				return $_lang ['username_has_exist'];
			}
		} else if ($this->_sys_config ['user_intergration'] == 3) {
			$result = keke_user_class::user_checkname ( $check_username );
			if ($result == - 1) {
				return $_lang ['username_invalid'];
			} else if ($result == - 2) {
				return $_lang ['username_has_exist'];
			}
		}
		$limit_username = keke_user_class::user_getprotected ();
		if ($limit_username && in_array ( $check_username, $limit_username )) {
			return $_lang ['register_fail_limit_register'];
		}
		return true;
	}
	
	//检查email
	function check_email($reg_email) {
		global $_lang;
		$check_res = keke_user_class::user_checkemail ( $reg_email );
		if ($check_res == 1) {
			return true;
		} else if ($check_res == - 4) {
			return $_lang ['email_format_error'];
		} else if ($check_res == - 5) {
			return $_lang ['email_not_allow_register'];
		} else if ($check_res == - 6) {
			return $_lang ['email_not_allow_register'];
		}
	
	}
	
	//检查验证码
	function check_code($reg_code) {
		global $_lang;
		$img = new Secode_class ();
		$res_code = $img->check ( $reg_code, 1 );
		
		if (! $res_code) {
			$this->show_msg($_lang ['reg_code_is_error'],'',2);
		} else {
			return true;
		}
	}
	
	//检查ip重复注册
	function check_ip() {
		global $_lang;
		$check_time = time () - $this->_sys_config ['reg_limit'] * 60;
		$this->_space_obj->setWhere ( "reg_ip = '$this->_reg_ip' and $check_time< reg_time" );
		$res = $this->_space_obj->query_keke_witkey_space ();
		if ($res) {
			return $_lang ['this_IP_has_registered_notice'];
		} else {
			return true;
		}
	
	}
	
	//账号绑定
	public static function register_binding($oauth_user_info, $user_info, $type) {
		global $_lang;
		$csql = "select count(*) as c from %switkey_member_oauth where source='%s' and oauth_id ='%s'";
		$c = dbfactory::get_one ( sprintf ( $csql, TABLEPRE, $type, $oauth_user_info ['account'] ) );
		if (intval ( $c ['c'] ) == 0) {
			$oauth_obj = new Keke_witkey_member_oauth_class ();
			$oauth_obj->setAccount ( $oauth_user_info ['name'] );
			$oauth_obj->setOauth_id ( $oauth_user_info ['account'] );
			$oauth_obj->setSource ( $type );
			$oauth_obj->setUid ( $user_info ['uid'] );
			$oauth_obj->setUsername ( $user_info ['username'] );
			$oauth_obj->setOn_time ( time () );
			$oauth_obj->create_keke_witkey_member_oauth () or $this->show_msg($_lang ['bind_fail'],'',2);
		} else {
			$this->show_msg($_lang ['this_user_has_bind'],'',2);
		}
		return true;
	}
	//绑定
	public static function is_oauth_bind($type, $oauth_id) {
		$sql = "select * from %switkey_member_oauth where source = '%s' and  oauth_id = '%s'";
		return dbfactory::get_one ( sprintf ( $sql, TABLEPRE, $type, $oauth_id ) );
	}
	function show_msg($content,$url='',$status) {
		global $_lang;
		switch ($this->_reg_type) {
			case "2" ://mobile
				kekezu::echojson($content,$status);
				die ();
				break;
			default:
				kekezu::show_msg($_lang['operate_notice'],$url,2,$content,'alert_right');
				break;
		}
	}
}