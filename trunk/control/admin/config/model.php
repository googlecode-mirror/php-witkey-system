<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ȫ�����ù�����Ʋ�
 * @author michael
 *
 */
class Control_admin_config_model extends  Controller {
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
		//Ĭ��Ϊֻ��ʾ������ص�����ģ��
		if(!isset($type)){
			$type = 'task';
		}
		//ģ���б�,�Ѿ���ʼ�����������ٲ�
		$list = Keke::$_model_list;
		$model_list = array();
		//��ģ�ͽ���ɸѡ��ԭ���Ƿ���ģ���ϵ�
		foreach ($list as $k=>$v){
			if($v['model_type']==$type){
				$model_list[$k] = $v;
			}
		} 
		
		require Keke_tpl::template('control/admin/tpl/config/model');
	}
	/**
	 * ��ʾ�̳ǵ����ģ��
	 */
	function action_shop(){
		$this->action_index('shop');
	}
	/**
	 * ģ�Ͱ�װ��������ģ�ͻ���
	 */
	function action_install(){
		 
	}
	/**
	 * ж������ģ��
	 */
	function action_unstall(){
		
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
