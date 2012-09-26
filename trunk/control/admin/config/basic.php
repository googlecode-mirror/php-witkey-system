<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ȫ�����ù������Ʋ�
 * @author michael
 *
 */
class Control_admin_config_basic extends  Controller {
    /**
     * ��ʼ������ҳ�棬
     * @param string $type ȷ�������Ǹ�����ģ���ļ�
     */
	function action_index($type=NULL){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
	 	//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/config_basic";
		//�����������ͣ�Ĭ��Ϊweb�� 
		//�б�����,ϵͳ��ʼ��ʱ�Ѿ�����,���������ٲ�
		$config_arr = Keke::$_sys_config;
		//�����б�
		$lang_arr = Keke::$_lang_list;
		//Ĭ��Ϊϵͳ����ģ��
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'web';
		}
		
		require Keke_tpl::template('control/admin/tpl/config/'.$type);
	}
	/**
	 * ��������
	 */
	function action_basic(){
		$this->action_index('basic');
	}
	/**
	 * seo���� 
	 */
	function action_seo(){
		$this->action_index('seo');
	}
	/**
	 * �ʼ�����
	 */
	function action_mail(){
		$this->action_index('mail');
	}
	/**
	 * ��������
	 */
	function action_sys(){
		$this->action_index('basic');
	}
	/**
	 * ������������
	 */
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		//��ֹ�����ύ���㶮��
		Keke::formcheck($_POST['formhash']);
		$type = $_POST['type'];
		//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
		$values = $_POST;
		unset($values['formhash']);
		unset($values['type']);
		//�ʼ��˺ż򵥼���һ��
		if(isset($values['account_pwd'])){
			$values['account_pwd'] = base64_encode($_POST['account_pwd']);
		}
		foreach ($values as $k=>$v) {
			$where = "k = '$k'";
			DB::update('witkey_config')->set(array('v'))->value(array($v))->where($where)->execute();
		}
		Cache::instance()->del('keke_config');
		//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
		Keke::show_msg('ϵͳ��ʾ','index.php/admin/config_basic/index?type='.$type,'�ύ�ɹ�','success');
		
	}
	/**
	 * ���Ͳ����ʼ�
	 */
	public static function action_send_mail(){
		global $_K,$_lang;
		$config_arr = Keke::$_sys_config;
		$mail = new Phpmailer_class ();
		if ($config_arr['mail_server_cat'] == "smtp") {
			$mail->IsSMTP ();
			$mail->SMTPAuth = true;
			$mail->CharSet = ($_K ['charset']);
			$mail->Host = $config_arr['smtp_url'];
			$mail->Port = $config_arr['mail_server_port'];
			$mail->Username = $config_arr['post_account'];
			$mail->Password = base64_decode($config_arr['account_pwd']);
		
		} else {
			$mail->IsMail ();
		}
		$mail->SetFrom ( $config_arr['post_account'], $config_arr['website_name'] );
		
		$mail->AddReplyTo ( $config_arr['mail_replay'], $config_arr['website_name'] );
		
		$mail->Subject = $_lang['keke_mail_testing'];
		
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
		$body = $_lang['test_mail_sent_successfully'];
		$mail->MsgHTML ( $body );
		$mail->AddAddress ( $_GET['email'], $config_arr['website_name'] );
		if (! $mail->Send ()) {
			echo  $mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}
	}
	/**
	 * ����α��̬���� 
	 */
	function action_seo_rule(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/config/seo_rule');
		
	}
	
}