<?php
/**
 * µҐТі№ЬАн
 */
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
if($art_id){
	$art_info = db_factory::get_one(sprintf("select * from %switkey_article where art_id='%d'",TABLEPRE,$art_id));
	$page_title=$art_info['art_title'].' - '.$_K['html_title'];
}
require keke_tpl_class::template('single');