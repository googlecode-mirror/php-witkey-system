<?php
 /**
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * @˵��   �����ģ��������ɶ�. Ŀ��ֻ���Ŀ�����
 * 2010-5-25����09:39:42
 */

 
$tpl_mode = 1;
 
define('ADMIN_KEKE',TRUE);
require '../../app_comm.php';

define('ADMIN_ROOT',S_ROOT.'./control/admin/');//��̨��Ŀ¼
 
$_K['admin_tpl_path']= S_ROOT.'./control/admin/tpl/';//��̨ģ��Ŀ¼

if ($do == 'previewtag')
{

	$tagid = intval($tagid);
	if (!$tagid){
		die();
	}
	$taglist = kekezu::get_tag(1);
	$tag_info = $taglist[$tagid];
	//var_dump($taglist);die();
	//Ԥ��feed
	if($tag_info['tag_type']==8){
		keke_loaddata_class::preview_feed($tag_info);
	}else if($tag_info['tag_type']==9){
		keke_loaddata_class::preview_addgroup($tag_info['tagname']);
	}//Ԥ�����
    else{
	keke_loaddata_class::previewtag($tag_info);
	}//Ԥ������
}

