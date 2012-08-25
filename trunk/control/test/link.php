<?php defined ( 'IN_KEKE' ) or exit('Access Denied');

class Control_test_link extends Controller{
	
	 protected  $_uri = 'index.php/test_link';
	/**
	 * ϵͳ��ʼ��
	 * ����ֻ��ҳ�������ʾʹ��
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
	 * ���������
	 */
	function action_add(){
		global $_K;
		 
		//����
		Keke::formcheck($_POST['formhash']);
				
		//�ֶ���֤
		$p = Keke_validation::factory($_POST)
				//��������֤
				->rules('link_name', array(array('not_empty',array(':value',$_POST['link_name'])),array('email',array(':value',$_POST['link_name']))))
				//��������֤
				->rule('link_url','Keke_valid::not_empty',array(':value',$_POST['link_url']))
				->rule('link_pic','Keke_valid::not_empty',array(':value',$_POST['link_pic']));
		if(!$p->check()){
			$e = $p->errors();
			Keke::show_msg('ϵͳ��ʾ!',$this->_uri,$e,'warning',999999);
		}
		//�������ݿ�
		$link_obj=new Keke_witkey_link();
		$link_obj->setLink_name($_POST['link_name']);
		$link_obj->setLink_url($_POST['link_url']);
		$link_obj->setLink_pic($_POST['link_pic']);
		//����Ӱ������
		$res = $link_obj->create(0);
		if($res){
			keke::show_msg('ϵͳ��ʾ',$this->_uri,'��ӳɹ�!','success');
		}else{
			keke::show_msg('ϵͳ��ʾ',$this->_uri,'���ʧ��!','warning');
		}
		$this->action_index();
	}
	
	function action_ajax(){
		global $_K,$_lang;
		$title = "��¼����";
		require Keke_tpl::template('ajax/ajax_test');
	}
	
	
 
}

