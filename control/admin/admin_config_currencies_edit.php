<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ֧������
 * @author S
 * @version v 2.0
 * 2011-12-13
 */

Keke::admin_check_role ( 2 );
$url = "index.php?do=$do&view=$view";
$default_currency =$Keke->_sys_config['currency'];
//��ȡҪ�༭����Ϣ�������ݿ�ȡһ������ֻ��һ����
if(!empty($cid)){
	$sql = sprintf("select * from %switkey_currencies where currencies_id ='%d' limit 0,1",TABLEPRE,$cid);
	$currency_config = Dbfactory::get_one($sql);
}

//��ȡ�����棬�����ύ������ֵ
if($conf and $sbt_edit){
	if(preg_match('/([a-z])+/i', $conf['code'])){ //���Ҵ���ֻ��ΪӢ��
		$currencies_obj = new keke_table_class('witkey_currencies');
		$conf['last_updated']=time();
		if($default_cur){
			$default_currency_conf = Dbfactory::execute(sprintf("update %switkey_basic_config set v='%s' where k='currency'",TABLEPRE,$default_cur));
			$_SESSION['currency'] = $default_cur;//����Ĭ�ϱ��֡��������ĵ�ǰѡ�����
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