<?php   defined ( "IN_KEKE" ) or die ( "Access Denied" );

class Control_admin_index extends Controller{
	
	function action_index(){
		
		global $_K,$_lang;
		
		$this->check_user();
		
		$this->admin_init();
		
		$admin_obj=new keke_admin_class();
		//һ���˵�
		$menu_arr = array (
				'config' => $_lang['global_config'],
				'article' => $_lang['article_manage'],
				'task' => $_lang['task_manage'],
				'shop' => $_lang['shop_manage'],
				//'finance' => $_lang['finance_manage'],
				'user' => $_lang['user_manage'],
				'tool' => $_lang['system_tool'],
				//'keke'=>$_lang['witkey_union'],
				'demo'=>'MVC��ʾ',
		
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
	
	function check_user(){
        global $_K;
        if(! $_SESSION ['admin_uid'] || $_K['user_info']['group_id'] == 0){
			echo "<script>window.parent.location.href='".BASE_URL."/index.php/admin/login';</script>";
		}
	}
	
	function admin_init(){
		/*��̨��ֹ��̬��*/
		$_K ['is_rewrite'] = 0;
		
		define ( 'ADMIN_ROOT', S_ROOT . './control/admin/' ); //��̨��Ŀ¼
		
		$_K ['admin_tpl_path'] = S_ROOT . './control/admin/tpl/'; //��̨ģ��Ŀ¼
	}
	
	function action_op(){
		
		global $_K,$_lang;
		$admin_obj=new keke_admin_class();
		$_GET = $_POST+$_GET;
		/**������**/
		switch ($_GET['ac']){
			case "nav_search"://��������
				$nav_search=$admin_obj->search_nav($_GET['keyword']);
				require Keke_tpl::template("control/admin/tpl/admin_" .$_GET['ac']);
				die();
				break;
			case "lock":
				$admin_obj->screen_lock();//����
				break;
			case "unlock":
				$unlock_times=$admin_obj->times_limit($_GET['unlock_num']);//�����¼���Դ���
				$admin_obj->screen_unlock($_GET['unlock_num'],$_GET['unlock_pwd']);//����
				require Keke_tpl::template("control/admin/tpl/admin_screen_lock");
				die();
				break;
			case "add_shortcuts":
				$admin_obj->add_fast_menu($_GET['r_id']);
				break;
			case "rm_shortcuts":
				$admin_obj->rm_fast_menu($_GET['r_id']);
				break;
		}
	}
	 
	
}

/*��̨��ֹ��̬��*/
/* $_K ['is_rewrite'] = 0;

define ( 'ADMIN_ROOT', S_ROOT . './control/admin/' ); //��̨��Ŀ¼

$_K ['admin_tpl_path'] = S_ROOT . './control/admin/tpl/'; //��̨ģ��Ŀ¼

$dos = array ('static','preview','database_manage','permission','prom', 'main', 'side', 'menu', 'tpl', 'index', 'config', 'article',  'art_cat', 'edit_art_cat', 'finance', 'task', 'model', 'tool', 'user', 'login', 'logout', 'button_a', 'user_integration', 'score_config', 'score_rule', 'mark_config', 'mark_rule', 'mark_addico', 'mark_mangeico', 'auth',  'shop', 'group', 'rule', 'case', 'relation_info','nav','msg','trans','keke','payitem');

(! empty ( $do ) && in_array ( $do, $dos )) or $do = 'index';

$admin_info = kekezu::get_user_info ( $_SESSION ['uid'] );
if($do != 'login' && $do != 'logout'){
	if(! $_SESSION ['auid'] || ! $_SESSION ['uid'] || $admin_info ['group_id'] == 0){
		echo "<script>window.parent.location.href='index.php?do=login';</script>";
		die();
	}
}


keke_lang_class::package_init("admin");
keke_lang_class::loadlang("admin_$do");
$view and 	keke_lang_class::loadlang("admin_{$do}_$view");
$op and keke_lang_class::loadlang("admin_{$do}_{$view}_{$op}");
keke_lang_class::loadlang("admin_screen_lock");
$language      = $kekezu->_lang;


$menu_arr = array (
'config' => $_lang['global_config'], 
'article' => $_lang['article_manage'],
'task' => $_lang['task_manage'], 
'shop' => $_lang['shop_manage'],
'finance' => $_lang['finance_manage'], 
'user' => $_lang['user_manage'], 
'tool' => $_lang['system_tool'],
'keke'=>$_lang['witkey_union'],

		);

/**
 * ��̨ҵ�������
 */
/* $admin_obj=new keke_admin_class();

require ADMIN_ROOT . 'admin_' . $do . '.php';  */
 