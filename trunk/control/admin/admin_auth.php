<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * @todo ��̨��֤����·��
 * 2011-9-01 11:35:13
 */

keke_lang_class::package_init("auth");
keke_lang_class::loadlang("{$do}_{$view}");

$views = array ('item_list', 'info', 'list', 'item_edit' );

$view = (! empty ( $view ) && in_array ( $view, $views )) ? $view : 'item_list';
if (file_exists ( ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php' )) {
	keke_lang_class::package_init ( "auth" );
	keke_lang_class::loadlang ( "admin_$view" );
	if (! $auth_dir) { //�ں�̨������֤�װʱ�ᴫ�ݴ˱����������Դ�����Ϊ�Ƿ��ǰ�װ����������
		/**
		 *��֤��ʼ��
		 */
		$auth_item_list = keke_auth_base_class::get_auth_item (); //��ȡ��֤��Ϣ
		$keys = array_keys ( $auth_item_list );
		$auth_code or $auth_code = $keys ['0']; //Ĭ����֤��
		
		if($auth_item_list[$auth_code]){
			$auth_class = "keke_auth_" . $auth_code . "_class";
			
			$auth_obj = new $auth_class ( $auth_code ); //��ʼ����֤����
			
			$auth_item = $auth_item_list [$auth_code]; //��ȡ������֤������Ϣ
			$auth_dir = $auth_item ['auth_dir']; //��֤�ļ���·��
			keke_lang_class::loadlang ( $auth_dir );
		}else{
			Keke::show_msg($_lang['illegal_parameter_or_authmadel_delete'],"index.php?do=auth&view=item_list",3,'','warning');
		}
	}
	require ADMIN_ROOT . 'admin_' . $do . '_' . $view . '.php';
} else {
	Keke::admin_show_msg ( $_lang['404_page'],'',3,'','warning' );
}