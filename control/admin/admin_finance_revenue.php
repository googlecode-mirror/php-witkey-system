<?php	defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
/*
 * @author Chen
 * @version v 2.0
 * @todo 营收
 * 2011-9-01 11:35:13
 */

kekezu::admin_check_role('152');
$model_list = $kekezu->_model_list;
$today = date('Y-m-d',time());
$st or $st = $today;
$ed   or $ed   = $today;
$f_sql = sprintf(' and  fina_time between %s and %s',strtotime($st),strtotime($ed)+24*3600);//财务表时间区间
$w_sql = sprintf(' and  applic_time between %s and %s',strtotime($st),strtotime($ed)+24*3600);//提现表时间区间

if($st==$ed&&$st==$today){
	$desc = $_lang['today'];
}elseif($st==$ed&&$ed!=$today){
	$desc = $st;
}else{
	$desc = $st.'~'.$ed;
}
$desc = '<font color="red">'.$desc.'</font>';
/**
 * 财务概况。默认当天
 */
$in = db_factory::get_one(sprintf(' select sum(abs(fina_cash)) c,sum(fina_credit) s from %switkey_finance where fina_type = "in"',TABLEPRE).$f_sql);//收入
$fina_in = $in['c']+$in['s'];
$fina_pro= db_factory::get_count(sprintf(' select sum(site_profit) c from %switkey_finance where site_profit>0 ',TABLEPRE).$f_sql);//盈利
$fina_wid= db_factory::get_count(sprintf(' select sum(withdraw_cash) c from %switkey_withdraw where 1 = 1 ',TABLEPRE).$w_sql);//提现
/**
 * 收支统计。默认当天
 */
$ops = array('in','profit','withdraw');
in_array($op,$ops) or $op ='in';
switch ($op){
	case 'in'://收入
		$sql       = ' select sum(fina_cash) cash,sum(fina_credit) credit,count(fina_id) count ';
		$t_sql     = sprintf(' ,model_id from %switkey_finance a 
					   left join %switkey_task b on a.obj_id=b.task_id where  fina_action="pub_task"  
					    and model_id>0 ',TABLEPRE,TABLEPRE);
		$task 	   = db_factory::query($sql.$t_sql.$f_sql.' group by model_id',1,3600);//赏金托管
		
		$s_sql     = sprintf(' ,model_id from %switkey_finance a 
					   left join %switkey_service b on a.obj_id=b.service_id where obj_type="service" and fina_type = "in" 
					    and model_id>0 ',TABLEPRE,TABLEPRE);
		$service   = db_factory::query($sql.$s_sql.$f_sql.' group by model_id',1,3600);//服务销售
		$payitem   = db_factory::get_one($sql.sprintf(' from %switkey_finance where fina_type="out"
						 and obj_type="payitem" ',TABLEPRE).$f_sql,1,3600);//增值购买
		$r_sql     = sprintf(' ,obj_type from %switkey_finance
						 where INSTR(obj_type,"_charge")>0  and fina_type = "in"',TABLEPRE);
		$charge    = db_factory::query($sql.$r_sql.$f_sql.' group by obj_type ',1,3600);//用户充值
		$fina_type = keke_glob_class::get_fina_charge_type();
		break;
	case 'profit'://盈利
		$sql      = sprintf(' select sum(site_profit) c from %switkey_finance where site_profit>0 ',TABLEPRE);
		$task     = db_factory::get_count($sql.' and obj_type="task" '.$f_sql,0,'c',3600);
		$service  = db_factory::get_count($sql.' and obj_type="service" '.$f_sql,0,'c',3600);
		$payitem  = db_factory::get_count($sql.' and obj_type="payitem" '.$f_sql,0,'c',3600);
		$auth     = db_factory::get_count($sql.' and INSTR(obj_type,"_auth")>0 '.$f_sql,0,'c',3600);
		$withdraw = db_factory::get_count(sprintf(' select sum(fee) c from %switkey_withdraw 
					where withdraw_status=2 ',TABLEPRE).$w_sql,0,'c',3600);
		$p_all    = floatval($task+$service+$payitem+$auth+$withdraw);
		break;
	case 'withdraw'://提现
		$list     = db_factory::query(sprintf(' select sum(withdraw_cash) cash,sum(fee) fee,
					count(withdraw_id) count,pay_type from %switkey_withdraw where 1 = 1 ',TABLEPRE)
					.$w_sql.' group by pay_type',1,3600);
		$list&&$list = kekezu::get_arr_by_key($list,'pay_type');
		$bank_arr = keke_glob_class::get_bank();
		$pay_online = kekezu::get_payment_config('','online');
		break;
}
require keke_tpl_class::template('control/admin/tpl/admin_finance_revenue');