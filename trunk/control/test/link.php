<?php defined ( 'IN_KEKE' ) or exit('Access Denied');

class Control_test_link extends Controller{
	
	/**
	 * 系统初始化
	 */
	function action_index(){
		global $_K,$_lang;
		//$link_arr = Model::factory('witkey_link')->query('`link_id`,`link_name`,`link_url`',300);
		//var_dump($link_arr);
		//$sql = sprintf("select link_id,link_name,link_url from %switkey_link" ,TABLEPRE);
		//Database::instance()->query($sql,Database::SELECT,1);
		//$img =  Keke_captcha::instance('black')->render(false);
		require Keke_tpl::template('test/link');
	}
	/**
	 * 添加友连接
	 */
	function action_add(){
		global $_K;
		$p = Keke_validation::factory($_POST)->rule('email', 'Keke_valid::email',array(':value',$_POST['email']));
		if(!$p->check()){
			$e = $p->errors();
			Keke::show_msg('系统提示!','index.php/en',$e);
		}
		$this->action_index();
	}
	
	function action_ajax(){
		global $_K,$_lang;
		$title = "登录测试";
		require Keke_tpl::template('ajax/ajax_test');
	}
	
	
 
}

