<?php
/**
 * @author       Administrator
 */
keke_lang_class::load_lang_class('keke_auth_fac_class');
class keke_auth_fac_class {
	/**
	 * ��ȡ�û���֤��¼
	 * @param $uid
	 */
	public static function user_auth_info($uid) {
		$auth_list = Keke::get_table_data ( "*", "witkey_auth_record", "uid='$uid'", '', '', '', 'auth_code' );
		return $auth_list;
	}
	/**
	 * ��ȡĳ�û��ύ������֤�ļ�¼��Ϣ
	 * @param int $uid
	 * @param int $auth_status ��֤״̬
	 */
	public static function get_submit_auth_record($uid,$auth_status='1'){
		$auth_item = keke_auth_base_class::get_auth_item();
		$auth_code = array_keys($auth_item);
		return self::auth_check($auth_code, $uid,$auth_status);
	}
	/**
	 * ��ȡ��֤ͼƬ
	 * @param $auth_code
	 * @param $uid user id 
	 * @return   $img_list
	 */
	public static function get_auth_imghtm($auth_code, $uid) {
		global $_lang;
		$auth_list = self::user_auth_info ( $uid );
		$config_list = self::get_auth_item_list();
		$img_list = '';
		foreach ( $config_list as $c ) {
			if (! $c ['auth_open'])
				continue;
			$str = '';
			$str .= '<img src="';
			$str .= 'data/uploads/' . 'ico/';
			$str .= $auth_list [$c ['auth_code']] ['auth_status'] ? $c ['auth_small_ico'] : $c ['auth_small_n_ico'];
			$str .= '" align="absmiddle" title="' . $c ['auth_title'];
			$str .= $auth_list [$c ['auth_code']] ['auth_status'] ? $_lang['has_pass'] : $_lang['not_pass'];
			$str .= '" width="15">&nbsp;';
			
			$img_list .= $str;
		
		}
		return $img_list;
	
	}
	/**
	 * �����֤��Ŀ�� 
	 * @param $auth_dir auth dir
	 * @return   void
	 */
	public static function install_auth($auth_dir) {
		global $_lang;
		$tab_obj       = keke_table_class::get_instance("witkey_auth_item");
		if ($auth_dir) {
			$file_path = S_ROOT . "./auth/" . $auth_dir . "/control/admin/auth_config_inc.php";
			if(file_exists ( $file_path )){
				require $file_path;
			$exists    = dbfactory::get_count(" select auth_code from ".TABLEPRE."witkey_auth_item where auth_dir = '$auth_dir'");
			$exists and Keke::admin_show_msg($_lang['auth_item_exist_add_fail'],$_SERVER['HTTP_REFERER'],'3','','error');
			
			$res=$tab_obj->save($auth_config);//��װ
			
			if (file_exists ( S_ROOT . "./auth/" .$auth_dir. "/control/admin/install_sql.php" )) {
				require S_ROOT . "./auth/" . $auth_dir. "/control/admin/install_sql.php";
			}
			$res and Keke::admin_system_log ( $_lang['add_auth_item'] . "$res" );
			$res and Keke::admin_show_msg($_lang['auth_item_add_success'],$_SERVER['HTTP_REFERER'],'3');
			}else{
				
				Keke::admin_show_msg($_lang['unknow_error_add_fail'],$_SERVER['HTTP_REFERER'],'3','','error');
			}
		} else {
			Keke::admin_show_msg($_lang['unknow_error_add_fail'],$_SERVER['HTTP_REFERER'],'3','','error');
		}
	}
	/**
	 * ɾ����֤��Ŀ
	 * @param $auth_code auth item name  str/arr
	 * @return   void
	 */
	public static function del_auth($auth_code, $cash_name) {
		global $kekezu;
		global $_lang;
		$auth_item_obj = new Keke_witkey_auth_item_class();
		if (isset ( $auth_code )) {
			switch (is_array ( $auth_code )) {
				case "0" :
					$auth_item     = keke_auth_base_class::get_auth_item($auth_code);
					$auth_item['auth_small_ico']   and keke_file_class::del_file($auth_item['auth_small_ico']);
					$auth_item['auth_small_n_ico'] and keke_file_class::del_file($auth_item['auth_small_n_ico']); 
					$auth_item['auth_big_ico']     and keke_file_class::del_file($auth_item['auth_big_ico']); 
					$auth_item_obj->setWhere ("auth_code='$auth_code'" );
					$res = $auth_item_obj->del_keke_witkey_auth_item ();
					$res and Keke::$_cache_obj->del ( $cash_name );
					$res and Keke::admin_system_log ( $_lang['delete_auth_item'] . $auth_item['auth_title'] );
					
					if (file_exists ( S_ROOT . "./auth/" . $auth_item['auth_dir'] . "/control/admin/uninstall_sql.php" )) {
						require S_ROOT . "./auth/" . $auth_item['auth_dir'] . "/control/admin/uninstall_sql.php";
					}
					$res and Keke::admin_show_msg($_lang['auth_item_delete_success_notice'],$_SERVER['HTTP_REFERER'],'3')	or Keke::admin_show_msg($_lang['auth_item_delete_fail'],$_SERVER['HTTP_REFERER'],'3','','error');
					break;
				case "1" :
					$auth_code_str=implode(",",$auth_code);
					if (sizeof ( $auth_code_str )) {
						$auth_item_obj->setWhere ( " FIND_IN_SET(auth_code,'$auth_code_str')>0" );
						$res = $auth_item_obj->del_keke_witkey_auth_item ();
						$res and Keke::admin_system_log ( $_lang['delete_auth_item']."$auth_code_str" );
						$res and Keke::admin_show_msg($_lang['auth_item_mulit_delete_success'],$_SERVER['HTTP_REFERER'],'3') or $res and Keke::admin_show_msg($_lang['auth_item_mulit_delete_fail'],$_SERVER['HTTP_REFERER'],'3','','error');
					}
					break;
			}
		}
	}
	/**
	 * ����/�༭��֤��
	 * @param $auth_code 			��֤����
	 * @param  $data  				�ⲿ������֤��
	 * @param  $pk                  �༭��Ŀ
	 * @param  $big_ico_name		��֤��ͼ
	 * @param  $small_ico_name	 	��֤Сͼ
	 * @param  $small_n_ico_name	��֤Ĭ��Сͼ
	 * @param $conf                 ��չ����
	 */
	public static function edit_item($auth_code,$data,$pk=null,$big_ico_name=null,$small_ico_name=null,$small_n_ico_name=null,$conf=array()){
		global $kekezu;
		global $_lang;
		$auth_item     = keke_auth_base_class::get_auth_item($auth_code);
		$auth_item or Keke::admin_show_msg($_lang['auth_item_edit_fail_notice'],"index.php?do=auth&view=item_list",'3','','error');
		$tab_obj       = keke_table_class::get_instance("witkey_auth_item");
		//echo $big_ico_name;
		$big_ico_name and $data['auth_big_ico'] = $big_ico_name=='delete' ? '' : $big_ico_name;//keke_file_class::upload_file($big_ico_name);//��֤��ͼƬ�ϴ�
		$small_ico_name and $data['auth_small_ico'] = $small_ico_name=='delete' ? '' : $small_ico_name; //keke_file_class::upload_file($small_ico_name);//��֤��ͼƬ�ϴ�
		$small_n_ico_name and $data['auth_small_n_ico'] = $small_n_ico_name=='delete' ? '' : $small_n_ico_name; //keke_file_class::upload_file($small_n_ico_name);//��֤ǰͼƬ�ϴ�
		
		$data['update_time'] = time();
// 		var_dump($data);
		$res=$tab_obj->save($data,$pk);//����.�༭
		if($res){
			Keke::$_cache_obj->del('auth_item_cache_list');
			Keke::admin_system_log($_lang['edit_auth_item'].$auth_item['auth_title']);
			Keke::admin_show_msg($_lang['auth_item_edit_success'],$_SERVER['HTTP_REFERER'],3,'','success');
		}else{
			Keke::admin_show_msg($_lang['auth_item_edit_fail'],$_SERVER['HTTP_REFERER'],3,'','warning');
		}
	}
	/**
	 * �û���֤��¼��֤
	 * @param  $auth_code sting or array()��֤���� ��ȡ��һ�����
	 * @param  $uid �û���
	 * @param  $auth_status ��֤״̬
	 * @return $auth_info
	 */
	public static function auth_check($auth_code,$uid,$auth_status='1'){
		if(!is_array($auth_code)){
			$auth_table=TABLEPRE."witkey_auth_".$auth_code;
			$data = dbfactory::get_one(" select * from ".$auth_table."  where uid ='$uid' and auth_status='$auth_status'");
			return $data;
		}else{
			$t = implode(",",$auth_code);
			return dbfactory::query(" select a.auth_code,a.auth_status,b.auth_title,b.auth_small_ico from ".TABLEPRE."witkey_auth_record a left join ".TABLEPRE."witkey_auth_item b on a.auth_code=b.auth_code where a.uid ='$uid' and FIND_IN_SET(a.auth_code,'$t') and a.auth_status='$auth_status'",1,3600);
		}
	}
}