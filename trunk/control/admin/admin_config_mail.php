<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ÓÊ¼þÅäÖÃ
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-18ÔçÉÏ2:37:00
 */

Keke::admin_check_role(23);
$config_basic_obj = new Keke_witkey_basic_config_class ();
$config_basic_arr = $config_basic_obj->query_keke_witkey_basic_config ();
foreach ( $config_basic_arr as $k => $v ) {
	$config_arr [$v ['k']] = $v ['v'];
}
$config_arr['account_pwd'] = base64_decode($config_arr['account_pwd']);
$url = 'index.php?do=config&view=mail';
//ÊÇ·ñ±à¼­
if (isset ( $submit )) {
	foreach ( $_POST as $k => $v ) {
		$config_basic_obj->setWhere ( "k = '$k'" );
		if($k=='account_pwd'){
		    $config_basic_obj->setV ( base64_encode($v) );
		}else{
			$config_basic_obj->setV ( $v );
		}
		$res += $config_basic_obj->edit_keke_witkey_basic_config ();
	
	}
	$Keke->_cache_obj->gc();
	Keke::admin_system_log($_lang['email_config_param']);
	if ($res) {
		$Keke->_cache_obj->set ( "keke_witkey_basic_config", $config_basic_arr );
		Keke::admin_show_msg ( $_lang['submit_success'], $url,3,'','success' );
	} else {
		Keke::admin_show_msg ( $_lang['website_config_fail'], $url,3,'','warning' );
	}

}

//ÓÊ¼þ²âÊÔ
if (isset ( $email )) {
	 
	$mail = new Phpmailer_class ();
	if ($config_arr['mail_server_cat'] == "smtp") {
		$mail->IsSMTP ();
		$mail->SMTPAuth = true;
		$mail->CharSet = ($_K ['charset']);
		$mail->Host = $config_arr['smtp_url'];
		$mail->Port = $config_arr['mail_server_port'];
		$mail->Username = $config_arr['post_account'];
		$mail->Password = $config_arr['account_pwd'];
	
	} else {
		$mail->IsMail ();
	}
	$mail->SetFrom ( $config_arr['post_account'], $config_arr['website_name'] );
	
	$mail->AddReplyTo ( $config_arr['mail_replay'], $config_arr['website_name'] );
	
	$mail->Subject = $_lang['keke_mail_testing'];
	
	$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";  
	$body = $_lang['test_mail_sent_successfully'];
	$mail->MsgHTML ( $body );
	 
	$mail->AddAddress ( $email, $config_arr['website_name'] );
	
	if (! $mail->Send ()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
	die ();
}

require  $template_obj->template ( 'control/admin/tpl/admin_config_' . $view );