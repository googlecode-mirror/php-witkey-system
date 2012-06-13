<?php
/**
 * ajax分享控制层,页面传递title过来即可
 */

defined ( 'IN_KEKE' ) or exit('Access Denied');
$sina_app_id = Keke::$_sys_config['sina_app_id'];
$sohu_app_id = Keke::$_sys_config['sohu_app_id'];
$seo_title = Keke::$_sys_config['seo_title'];
intval($oid) or $oid = null;
strval($title) and $title .= "@".Keke::$_sys_config['website_name'] or $title = Keke::$_sys_config['website_name'];
//搜索的title 要作转码处理
strtolower(CHARSET)=='gbk' and $utitle = urlencode(Keke::gbktoutf($title)) or $utitle = urlencode($title);
require Keke::$_tpl_obj->template ( 'ajax/ajax_share' );