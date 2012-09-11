<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台任务模型入口 
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-8-13上午04:49:25
 */



$model_id or Keke::admin_show_msg ( $_lang['error_model_param'], "index.php?do=info",3,'','warning' );

$model_info = Dbfactory::get_one ( " select * from " . TABLEPRE . "witkey_model where model_id = '$model_id'" );

if (! $model_info ['model_status']) {
	header ( "location:index.php?do=config&view=model" );
	die ();
}


keke_lang_class::package_init ( "task_{$model_info ['model_dir']}" );
keke_lang_class::loadlang ( "admin_{$do}_{$view}" );
keke_lang_class::loadlang("task_{$view}");
keke_lang_class::package_init ( "shop" );
keke_lang_class::loadlang("{$model_info [model_dir]}_{$view}");


require S_ROOT . $model_info ['model_type'] . "/" . $model_info ['model_dir'] . "/control/admin/admin_route.php";


