<?php   
/**
 *
 * @author Michael
 * @version 2.2
   2012-10-12
 */

define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

$base_uri = BASE_URL.'/show_msg.php';
$type = 'success';
if($_GET['type']){
	$type = $_GET['type'];
}
show_msg('�ɹ���ʾ��,����','show_msg.php',$type);



/**
 * ����ҳ����ת��ʾ
 *@param $content ��ʾ��Ϣ $_lang['submit_success']�ύ�ɹ�,$_lang['submit_fail']�ύʧ��
 *@param $url ��תurl
 * @param $type string
 *        	inajax
 *        	{'alert_info'=>'��ʾ','alert_right'=>'�ɹ�','confirm_info'=>'ȷ��','alert_error'=>'����'}
 *        	��ajax {'info'=>Ĭ��,'success'=>'�ɹ�','warning'=>'����'}
 *@param $title ���⣬Ĭ��Ϊ��ϵͳ��ʾ��
 *@param $time ��תҳ��ʾʱ�䣬Ĭ��Ϊ3��
 */
 
 function show_msg( $content = "", $url = "",  $type = 'info',$title = NULL,$time = 3) {
		global $_K, $basic_config, $username, $uid, $nav_list, $_lang;
		$r = $_REQUEST;
		$msgtype = $type;
		if($title===NULL){
			$title = $_lang['sys_tips'];
		}
		//û��http����base_url
		if(strpos($url, 'http')===FALSE){
			$url = BASE_URL."/$url";
		}
		require Keke_tpl::template ( 'show_msg_ui' );
		die ();
	}