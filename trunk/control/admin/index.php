<?php   defined ( "IN_KEKE" ) or die ( "Access Denied" );

class Control_admin_index extends Control_admin{
	
	function action_index(){
		
		global $_K,$_lang;
		
		$this->admin_init();
		
		$admin_obj=new Keke_admin();
		//һ���˵�
		$menu_arr = array (
				'config' => $_lang['global_config'],
				'article' => $_lang['article_manage'],
				'task' => $_lang['task_manage'],
				'shop' => $_lang['shop_manage'],
				'finance' => $_lang['finance_manage'],
				'user' => $_lang['user_manage'],
				'tool' => $_lang['system_tool'],
		);
		$grouplist_arr = $admin_obj->get_user_group();
		 
		/**��̨ȫ�ֲ˵���Ϣ**/
		$menu_conf = $admin_obj->get_admin_menu();
		 
		/**�Ӳ˵��б�**/
		$sub_menu_arr = $menu_conf['menu'];
		
		/**��ݷ�ʽ�б�**/
		$fast_menu_arr=$admin_obj->get_shortcuts_list();
		
		/***����״̬�ж�***/
		$check_screen_lock=$admin_obj->check_screen_lock();
	   
		
		
		require Keke_tpl::template('control/admin/tpl/index');
	}
	
	
	function admin_init(){
		/*��̨��ֹ��̬��*/
		$_K ['is_rewrite'] = 0;
		
		define ( 'ADMIN_ROOT', S_ROOT . './control/admin/' ); //��̨��Ŀ¼
		
		$_K ['admin_tpl_path'] = S_ROOT . './control/admin/tpl/'; //��̨ģ��Ŀ¼
	}
	/**
	 * �����˵�
	 */
	function action_nav(){
		global $_K,$_lang;
		//��ȡ��̨�ĸ�Ŀ¼����Ŀ¼
		$menus_arr = Keke_admin::get_admin_menu();
		$menus_arr = $menus_arr['menu'];
		
		require keke_tpl::template('control/admin/tpl/nav');
	}
	/**
	 * ��̨��������
	 */
	function action_nav_search(){
		global $_K,$_lang;
		$admin_obj=new Keke_admin;
		$arr=$admin_obj->search_nav($_GET['keyword']);
		$menus_arr[][0]['name'] =$_GET['keyword'];
		$menus_arr[][0]['items'] =$arr; 
		require Keke_tpl::template("control/admin/tpl/nav");
	}
	/**
	 * ��ݲ��� 
	 */
	function action_op(){
		global $_K,$_lang;
		$admin_obj=new Keke_admin;
		switch ($_GET['ac']){
			//���
			case "add_shortcuts":
				$admin_obj->add_fast_menu($_GET['r_id']);
				break;
			//ɾ��	
			case "rm_shortcuts":
				$admin_obj->rm_fast_menu($_GET['r_id']);
				break;
		}
	}
	 
	
}

