<?php
/**
 * ajax������Ʋ�,ҳ�洫��title��������
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');
$sina_app_id = Keke::$_sys_config['sina_app_id'];
$sohu_app_id = Keke::$_sys_config['sohu_app_id'];
$seo_title = Keke::$_sys_config['seo_title'];
intval($oid) or $oid = null;
strval($title) and $title .= "@".Keke::$_sys_config['website_name'] or $title = Keke::$_sys_config['website_name'];
//������title Ҫ��ת�봦��
strtolower(CHARSET)=='gbk' and $utitle = urlencode(Keke::gbktoutf($title)) or $utitle = urlencode($title);
require Keke::$_tpl_obj->template ( 'ajax/ajax_share' );