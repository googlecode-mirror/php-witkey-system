<?php
/**
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * @date 2011-12-19 ����02:51:51
 * @encoding GBK
 */

defined ( 'ADMIN_KEKE' ) or die ( 'Access Denied' );
Keke::admin_check_role ( 59 );
! isset ( $op ) && $op = 'config'; //Ĭ����ת��reg_prom����ҳ��
$url = 'index.php?do=' . $do . '&view=' . $view . '&op=' . $op;
$table_name = 'witkey_prom_rule';

//�༭
if (isset ( $sbt_edit )) {
	switch ($op) {
		case 'config' : //�༭ע���ƹ�����
			$config = array ();
			$rule_obj = new Keke_witkey_prom_rule_class ();
			$rule_obj->setWhere ( 'prom_id="' . $prom_id . '"' );
			$rule_obj->setIs_open ( $prom_reg_is_open );
			$config ['auth_step'] = $allow_prom_reg; //��Чģʽ
			$rule_obj->setCash ( floatval ( $reg_cash ) ); //ע�ά��
			$rule_obj->setCredit ( floatval ( $reg_credit ) );
			$rule_obj->setConfig ( serialize ( $config ) );
			$result .= $rule_obj->edit_keke_witkey_prom_rule ();
			//�޸Ķ�Ӧ��֤��Ϣ
			$result .= dbfactory::execute ( 'update ' . TABLEPRE . $table_name . ' set cash="' . floatval( $prom_cash) . '" , credit="' . floatval ($prom_credit) . '" where prom_code="' . $allow_prom_reg . '";' );
			//�޸�basic_config��¼
			$result .= dbfactory::execute ( 'update ' . TABLEPRE . 'witkey_basic_config set v="' . intval ( $prom_reg_is_open ) . '" where k="prom_open";' );
			$result .= dbfactory::execute ( 'update ' . TABLEPRE . 'witkey_basic_config set v="' . intval ( $prom_period ) . '" where k="prom_period";' );
			
			$message = $result ? $_lang['register_prom_config_edit_success'] : $_lang['no_change'];
			Keke::admin_system_log ( $_lang['edit_register_prom_config'] );
			Keke::admin_show_msg ( $message, $url,3,'','success' );
		
		case 'pub_task' :
		case 'bid_task' :
		case 'service' :
			$ext_config = array ();
			$ckb_indus and $ext_config ['indus'] = intval ( $ckb_indus );
			$indus_p_id && $s_indus_select and $ext_config ['indus_string'] = $indus_p_id . ',' . implode ( ',', $s_indus_select );
			($ckb_model && is_array ( $ckb_model )) and $ext_config ['model'] = implode ( ',', $ckb_model );
			switch ($op) {
				case 'pub_task' :
					isset ( $pub_task_rake_type ) && $ext_config ['pub_task_rake_type'] = $pub_task_rake_type;
					//�޸�pub_task          cash,credit,rate��¼
					$pub_task_cash && $pub_task_cash = floatval ( $pub_task_cash );
					$pub_task_credit && $pub_task_credit = floatval ( $pub_task_credit );
					$pub_task_rate && $ext_config ['pub_task_rate'] = floatval ( $pub_task_rate ); //С��
					//�޸�config��¼
					$ext_config = serialize ( $ext_config );
					$result = dbfactory::execute ( 'update ' . TABLEPRE . $table_name . " set config='$ext_config',cash='" . $pub_task_cash . "' , credit='" . $pub_task_credit . "' , rate='" . $pub_task_rate . "' where prom_code='pub_task';" );
					Keke::admin_system_log ( $_lang['update_task_prom_config'] );
					$result and Keke::admin_show_msg($_lang['task_prom_config_update_success'],$url,3,'','success') or Keke::admin_show_msg( $_lang['record_no_change'],$url,3,'','warning');
					
					break;
				case 'bid_task' :
					$bid_task_rake && $ext_config ['bid_task_rake'] = intval ( $bid_task_rake );
					//�޸�config��¼
					$ext_config = serialize ( $ext_config );
					$result = dbfactory::execute ( 'update ' . TABLEPRE . $table_name . " set config='$ext_config',rate='" . intval ( $bid_task_rake ) . " ' where prom_code='bid_task';" );
					Keke::admin_system_log ( $_lang['update_bid_prom_config'] );					
					$result and Keke::admin_show_msg ($_lang['bid_prom_config_update_success'],$url,3,'','success') or Keke::admin_show_msg($_lang['record_no_change'],$url,3,'','warning');
					break;
				case 'service' :
					$ext_config = serialize ( $ext_config );
					$result = dbfactory::execute ( 'update ' . TABLEPRE . $table_name . " set rate='" . intval ( $service_rate ) . "', config='" . $ext_config . "' where prom_code='service';" );
					Keke::admin_system_log ( $_lang['update_goods_prom_config'] );					
					$result and Keke::admin_show_msg ($_lang['goods_prom_config_success'],$url,3,'','success') or Keke::admin_show_msg($_lang['record_no_change'],$url,3,'','warning');
					break;
			}
			break;
	}
}

switch ($op) {
	case 'config' : //ע���ƹ��ʼ��
		$reg_config = $kekezu->get_table_data ( '*', $table_name, ' type="reg" ', '', '', '', 'prom_code', null );
		$reg_config = $reg_config ['reg']; //һά����
		$reg_mode = unserialize ( $reg_config ['config'] ); //config����       
		$auth_step = $reg_mode ['auth_step']; //private ���� e.g $auth_step = realname_auth   �ƹ�ģʽ e.g ע�� + �ֻ���֤(����)
		$global_config = $kekezu->get_table_data ( '*', 'witkey_basic_config', ' type="prom"', '', '', '', 'k' );
		$auth_info = $kekezu->get_table_data ( '*', $table_name, ' type="auth" ', '', '', '', 'prom_code', null ); //��ά���� pk = pro_code
		$auth_step_info = $auth_info [$auth_step]; //���󶨵���֤����
		break;
	case 'pub_task' : //�����ƹ�
	case "bid_task" : //�н��ƹ�
	case "service" : //��Ʒ����
		$op == 'pub_task' || $op == 'bid_task' and $model_type = 'task' or $model_type = 'shop';
		$indus_arr = Keke::get_industry (); //��ҵ��Ϣ
		$indus_index = Keke::get_indus_by_index (); //
		$model_info = Keke::get_table_data ( 'model_id,model_dir,model_name,config,model_type', 'witkey_model', "model_status=1 and model_dir!='employtask' and model_dir!='tender' and model_type='$model_type'", '', '', '', 'model_name' );
		$prom_config = $kekezu->get_table_data ( '*', 'witkey_prom_rule', "prom_code='$op'", '', '', '', 'prom_code' );
		$prom_config = array_merge ( $prom_config [$op], unserialize ( $prom_config [$op] ['config'] ) ); //����
		

		break;
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view );	