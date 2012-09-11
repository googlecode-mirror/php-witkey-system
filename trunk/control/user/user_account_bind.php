<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8����06:42:39
 * @property �û����԰󶨶��oauth�˺ţ��˺Ű󶨺��¼ �������а󶨣�
 * ���û�а� ����¼��Ҫ��һ�£����԰������˺ţ��������½��˺�
 * ��ҳ��Ҫ�����˺ŵİ������󶨱���
 */


//�󶨱����

$oauth_obj = new Keke_witkey_member_oauth_class();

/**
 * ��ȡ����Ϣ
 */ 
$api_name = keke_glob_class::get_open_api();
$oauth_url = $Keke->_sys_config['website_url']."/index.php?do=$do&view=$view&op=$op&ac=$ac&type=$type";
$res = Keke::get_table_data('*','witkey_member_oauth',"uid=$uid","","source",6,"source");
$url = "index.php?do=$do&view=$view&op=$op";
/**
 * �����˺���Ϣ$res��$r������кϲ�
 */
if (is_array ( $api_open )) {
	foreach ( $api_open as $key => $value ) {
		$value = array ("open" => $value );
		if ($res [$key]) {
			$t [$key] = array_merge ( $value, $res [$key] );
		} else {
			$t [$key] = $value;
		}
	}
}
switch ($ac) {
	case 'bind':   //��oauth�˺�
		if($type){
			switch($type=="alipay_trust"){
				case true:
					$interface = "sns_bind";
					require S_ROOT."/payment/alipay_trust/order.php";
					header("Location:".$request);
					break;
				case false:					
					$oa = new keke_oauth_login_class($type);
					if(!$_SESSION['auth_'.$type]['last_key']){						
						 $oauth_vericode = $oauth_vericode;
						 $oa->login($call_back,$oauth_url);						 						 
					}else{						
					   $oauth_user_info = $oa->get_login_user_info();
					}
					//�������û��Ƿ��Ѿ��󶨹�
					$is_bind = Dbfactory::get_count("select count(id) from ".TABLEPRE."witkey_member_oauth  where source ='$type' and oauth_id='{$oauth_user_info['account']}' and uid='$uid'");
					$is_bind and Keke::show_msg($_lang['operate_notice'],$url,3,$_lang['account_been_bind'],'warning');
					//�õ��û���Ϣ���а�
					$oauth_obj->setAccount($oauth_user_info['name']);
					
					//echo $oauth_user_info['name'];die();
					$oauth_obj->setOauth_id($oauth_user_info['account']);
					$oauth_obj->setSource($type);
					$oauth_obj->setUid($uid);
					$oauth_obj->setUsername($username);
					$oauth_obj->setOn_time(time());
					$oauth_obj->create_keke_witkey_member_oauth() and Keke::show_msg($_lang['operate_notice'],$url,2,$_lang['bind_success'],'success')  or Keke::show_msg($_lang['operate_notice'],$url,2,$_lang['bind_fail'],'warning');
			break;
			}
		}		
	break;
	case 'unbind':  //�����
		if(abs(intval($id))){
			switch($type=="alipay_trust"){
				case true:
					$interface = "cancel_bind";
					require S_ROOT."/payment/alipay_trust/order.php";
					header("Location:".$request);
					break;
				case false:
				   unset($_SESSION['auth_'.$type]['last_key']);
				   $oauth_obj->setId($id);
				   $oauth_obj->del_keke_witkey_member_oauth() and Keke::show_msg($_lang['operate_notice'],$url,2,$_lang['unbind_success'],'success')  or Keke::show_msg($_lang['operate_notice'],$url,2,$_lang['unbind_fail'],'warning') ;
				break;
			}
		}
	break;
}

require keke_tpl_class::template ( "user/" . $do ."_" . $op );
