<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 全局配置管理控制层
 * @author michael
 *
 */
class Control_admin_config_model extends  Controller {
    /**
     * 初始化加载页面，
     * @param string $type 确定加载那个配置模板文件
     */
	function action_index($type=NULL){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
	 	//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = BASE_URL."/index.php/admin/config_basic";
		//定义配置类型，默认为web型 
		//列表数据,系统初始化时已经有了,这里无须再查
		$config_arr = Keke::$_sys_config;
		//语言列表
		$lang_arr = Keke::$_lang_list;
		//默认为只显示任务相关的任务模型
		if(!isset($type)){
			$type = 'task';
		}
		//模型列表,已经初始化过，不用再查
		$list = Keke::$_model_list;
		$model_list = array();
		//对模型进行筛选，原来是放在模板上的
		foreach ($list as $k=>$v){
			if($v['model_type']==$type){
				$model_list[$k] = $v;
			}
		} 
		
		require Keke_tpl::template('control/admin/tpl/config/model');
	}
	/**
	 * 显示商城的相关模型
	 */
	function action_shop(){
		$this->action_index('shop');
	}
	/**
	 * 模型安装，并更新模型缓存
	 */
	function action_install(){
		 
	}
	/**
	 * 卸载任务模型
	 */
	function action_unstall(){
		
	} 
	/**
	 * 保存配置数据
	 */
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		//防止跨域提交，你懂的
		Keke::formcheck($_POST['formhash']);
		$type = $_POST['type'];
		//这里怎么说呢，定义生成sql 的字段=>值 的数组，你不懂，就是你太二了.
		$values = $_POST;
		unset($values['formhash']);
		unset($values['type']);
		//邮件账号简单加密一下
		if(isset($values['account_pwd'])){
			$values['account_pwd'] = base64_encode($_POST['account_pwd']);
		}
		foreach ($values as $k=>$v) {
			$where = "k = '$k'";
			DB::update('witkey_config')->set(array('v'))->value(array($v))->where($where)->execute();
		}
		Cache::instance()->del('keke_config');
		//执行完了，要给一个提示，这里没有对执行的结果做判断，是想偷下懒，如果执行失败的话，肯定给会报红的。亲!
		Keke::show_msg('系统提示','index.php/admin/config_basic/index?type='.$type,'提交成功','success');
		
	}
	/**
	 * 发送测试邮件
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
	 * 加载伪静态规则 
	 */
	function action_seo_rule(){
		global $_K,$_lang;
		
		require Keke_tpl::template('control/admin/tpl/config/seo_rule');
		
	}
	
}
