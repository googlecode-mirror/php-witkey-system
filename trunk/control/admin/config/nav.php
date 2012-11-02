<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * �����˵�����
 * @author Michael
 * @version v 2.2
 * 2012-09-28
 */
class Control_admin_config_nav extends Control_admin{
	
	function action_index(){
		global $_K,$_lang;
		//nav_list ϵͳ��ʼ������
		Keke::init_nav();
		$nav_list = Keke::$_nav_list;
		//Ĭ����ҳ
		$default_index = $_K['set_index'];
		 
		if(!$_POST){
			//û���ύʱ
			require Keke_tpl::template('control/admin/tpl/config/nav');
			die;
		}
		//��ǰҳ�����ύ����
		Keke::formcheck($_POST['formhash']);
		$nav = array_filter($_POST['nav']);
		//�������±��浽���ݿ�
		foreach($nav as $nav_id=>$v){
			//�ֶ�����
			$columns = array('nav_title','nav_url','nav_style','listorder');
			//ֵ����
			$values = array($v['nav_title'],$v['nav_url'],$v['nav_style'],$v['listorder']);
			//����
			$where = "nav_id = '".intval($nav_id)."'";
			//����
			DB::update('witkey_nav')->set($columns)->value($values)->where($where)->execute();
		}
		
		//���µ����˵��Ļ���
		Cache::instance()->del('keke_nav');
		Keke::show_msg ($_lang['submit_success'], "admin/config_nav",'success' );
	}
	/**
	 * �ж�������ַ������ַ
	 * @param unknown_type $url
	 * @return boolean
	 */
	function url_analysis($url){
	    if(strpos($url, 'http')!==FALSE){
	    	return FALSE;
	    }else{
	    	return TRUE;
	    }
	}
	/**
	 * ��ʼ�����ҳ��
	 */
	function action_add(){
		global $_K,$_lang;
		//nav_list ϵͳ��ʼ������
		Keke::init_nav();
		$nav_list = Keke::$_nav_list;
        if(isset($_GET['nav_id'])){
        	$nav_arr = $nav_list[$_GET['nav_id']];
        	//�Ƿ�ֻ��
        	$readonly = $this->url_analysis($nav_arr['nav_url']);
        }
		require Keke_tpl::template('control/admin/tpl/config/nav_add');
	}
	/**
	 * �����ύ�ĵ�������
	 */
	function action_save(){
		global $_lang;
		//form���
		Keke::formcheck($_POST['formhash']);
		//����
	    $array = array('nav_title'=>$_POST['nav_title'],
	    		'nav_url'=>$_POST['nav_url'],
	    		'nav_style'=>$_POST['nav_style'],
	    		'listorder'=>$_POST['listorder'],
	    		'newwindow'=>$_POST['newwindow'],
	    		'ishide'=>$_POST['ishide']);
	   
		if($_POST['hdn_nav_id']){
			//����
			$where = "nav_id = '{$_POST['hdn_nav_id']}'";
			//����
			Model::factory('witkey_nav')->setData($array)->setWhere($where)->update();
			//show_msg ��ת�ĵ�ַ
			$url = "?nav_id=".$_POST['hdn_nav_id'];
		}else{
			//���
			Model::factory('witkey_nav')->setData($array)->create();
			$url = NULL;
		}
		if($_POST['set_index']){
			$this->action_set_index($_POST['nav_style']);
		}else{
			$this->action_set_index('index');
		}
		Cache::instance()->del('keke_nav');
		Keke::show_msg($_lang['submit_success'],'admin/config_nav/add'.$url,'success');
	}
	/**
	 * ɾ�������˵�
	 */
	function action_del(){
		global $_K,$_lang;
		$nav_id = intval($_GET['nav_id']);
		echo DB::delete('witkey_nav')->where('nav_id='.$nav_id)->execute();
	}
	/**
	 * ��Ϊ��ҳ
	 * @param  $nav_style  Ҫ��Ϊ��ҳ������ʽ
	 * @return boolean
	 */
	function action_set_index($nav_style=NULL){
		global $_lang;
		//���Ϊ�գ����ȡget��ֵ
		if($nav_style===NULL){
			$nav_style = $_GET['nav_style'];
		}
		//�������ݿ⣬����ָ������ҳ
		$res =DB::update('witkey_config')->set(array('v'))->value(array($nav_style))->where("k='set_index'")->execute();
		//�������
		Cache::instance()->del('keke_config');
		
		if($_GET['nav_style']){
			Keke::show_msg ( $_lang['set_index_success'], "admin/config_nav",'success' );
		}else{
			return (bool)$res;
		}
	}
}//end