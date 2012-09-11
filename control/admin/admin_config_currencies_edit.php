<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 支付配置
 * @author S
 * @version v 2.0
 * 2011-12-13
 */

Keke::admin_check_role ( 2 );
$url = "index.php?do=$do&view=$view";
$default_currency =$Keke->_sys_config['currency'];
//获取要编辑的信息，从数据库取一条，且只有一条！
if(!empty($cid)){
	$sql = sprintf("select * from %switkey_currencies where currencies_id ='%d' limit 0,1",TABLEPRE,$cid);
	$currency_config = Dbfactory::get_one($sql);
}

//获取并保存，表单提交过来的值
if($conf and $sbt_edit){
	if(preg_match('/([a-z])+/i', $conf['code'])){ //货币代码只能为英文
		$currencies_obj = new keke_table_class('witkey_currencies');
		$conf['last_updated']=time();
		if($default_cur){
			$default_currency_conf = Dbfactory::execute(sprintf("update %switkey_basic_config set v='%s' where k='currency'",TABLEPRE,$default_cur));
			$_SESSION['currency'] = $default_cur;//更改默认币种、附带更改当前选择币种
		}$res = $currencies_obj->save($conf,$pk);
		if($res){
			Keke::admin_show_msg($_lang['operate_success'],$url,2,$_lang['edit_success'],"success");
		}else{
			Keke::admin_show_msg($_lang['operate_fail'],$url,2,$_lang['edit_fail'],"error");
		}
	}else{
		Keke::admin_show_msg($_lang['operate_fail'],$url,2,$_lang['currency_code_fill_error'],"error");
	}
}

require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view.'_'.$op );