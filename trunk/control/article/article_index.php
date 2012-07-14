<?php
/**
 * 文章模块--文章首页
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-12-5-上午10:37:07
 * @version V2.0
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
 

!isset($page_size) and  $page_size = 5 or $page_size= $page_size;
 !isset($page) and  $page = 1 or $page=$page; 
isset($art_cat_id) and $art_cat_id=intval($art_cat_id); 
isset($year) and 	$where .= " and year(FROM_UNIXTIME(a.pub_time))=" . intval($year);
isset($art_cat_id) and $where .= " and b.art_index like '%{$art_cat_id}%'";
$where .=" order by is_recommend desc,pub_time desc";
if(isset($static)){
	$url = $_K['siteurl']."/html/article/index/";
	
}else{
	
	$url = $_K['siteurl']."/index.php?do=article&page_size=$page_size";
	$year and  $url .='&year='.$year;
}
 
$static and $pre_url = $_K['siteurl'].'/';


$article_page_arr = get_art_list ( $page, $page_size, $url, $where,$static);
$pages = $article_page_arr['pages'];
$article_arr = $article_page_arr['date'];

//var_dump($article_arr);
$page_title= $_lang['news_center']."-" .$_K['html_title'];

require Keke_tpl::template ( "tpl/" . $_K ['template'] . "/" . $do . "/" . $view );
