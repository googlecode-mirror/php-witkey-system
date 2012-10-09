<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 短信配置
 */
class Control_admin_config_msg extends Controller{
    /**
     * 短信接口配置
     */
	function  action_index(){
    	global $_K,$_lang;
    	
    	require Keke_tpl::template('control/admin/tpl/config/msg_config');
    }
    /**
     * 保存配置信息
     */
    function action_config_save(){
    	global $_lang;
    	Keke::formcheck($_POST['formhash']);
    	unset($_POST['formhash']);
    	foreach ($_POST as $k=>$v) {
    		$where = "k = '$k'";
    		DB::update('witkey_config')->set(array('v'))->value(array($v))->where($where)->execute();
    	}
    	Cache::instance()->del('keke_config');
    	//执行完了，要给一个提示，这里没有对执行的结果做判断，是想偷下懒，如果执行失败的话，肯定给会报红的。亲!
    	Keke::show_msg($_lang['submit_success'],'index.php/admin/config_msg','success');
    }
    /**
     * 短信发送
     */
    function action_send(){
    	global $_K,$_lang;
    	if(!$_POST){
    		require Keke_tpl::template('control/admin/tpl/config/msg_send');
    		die;
    	}
    	$tar_content=Keke_tpl::chars($_POST['tar_content']);
    	//普通用户组,也就是给所有用户发短信
    	if($_POST['slt_type']=='normal'){
    		$tel_arr=Dbfactory::query(" select mobile from ".TABLEPRE."witkey_space where mobile is not null ");
    		//将手机号用逗号隔开
    		foreach($tel_arr as $v){
    			if($v['mobile']){
    			 $txt_tel .= $v['mobile'].",";
    			}
    		}
    		//去掉最后的逗号
    		$txt_tel = rtrim($txt_tel,',');
    	}else{
    		$txt_tel = $_POST['txt_tel'];
    	}
    	//发送短信
    	$m = Keke_sms::instance()->send($txt_tel,$tar_content);
    	var_dump($m);die;
    	if($m>0){
    	 	Keke::show_msg($_lang['sms_send_success'],"index.php/admin/config_msg/send",'success');
    	}else{
    		Keke::show_msg($_lang['sms_send_fail'],"index.php/admin/config_msg/send",'warning');
    			
    	}
    	
    }
    /**
     * 短信发送获取用户信息
     */
    function action_get_user(){
    	global $_lang;
    	$u  = $_POST['u'];
    	$type= $_POST['type'];
    	//判断条件是UID 还是username
    	$type=='uid' and $where=" uid='$u' " or $where=" INSTR(username,'$u')>0 ";
    	//获取用户信息
    	$user_info=Dbfactory::get_one(" select uid,username,phone,mobile from ".TABLEPRE."witkey_space where $where ");
    	if(!$user_info){
    		//查无此人
    		Keke::echojson($_lang['he_came_from_mars'],'3'); 
    	}else{
    		if(!$user_info['mobile']){
    			//此人没有手机
    			Keke::echojson($_lang['no_record_of_his_cellphone'],'2'); 
    		}else{
    			//手机找到了
    			Keke::echojson($user_info['mobile'],'1'); 
    		}
    	}
    }
    /**
     * 短信模板
     */
    function action_tpl(){
    	global $_K,$_lang;
    	 
    	require Keke_tpl::template('control/admin/tpl/config/msg_tpl');
    }
    /**
     * 短信模板编辑
     */
    function action_tpl_add(){
    	global $_K,$_lang;
    	 
    	require Keke_tpl::template('control/admin/tpl/config/msg_tpl_add');
    }
    /**
     * 短信模板信息保存
     */
    function action_tpl_save(){
    	
    }
    	
}


/* Keke::admin_check_role(66);
require '../../keke_client/sms/sms.php';
$account_info = $Keke->_sys_config; //手机账号信息
$mobile_u = $account_info ['mobile_username'];
$mobile_p = $account_info ['mobile_password'];
$op and $op = $op or $op = 'config';

$url = "index.php?do=$do&view=$view&op=$op";
switch ($op) {
	case "config" :
		if (! isset ( $sbt_edit )) {
			$bind_info = check_bind ( 'mobile_username' );
		} else { //添加、编辑\
			 
			foreach ( $conf as $k => $v ) {
				if (check_bind ( $k )) {
					
					$res .= Dbfactory::execute ( " update " . TABLEPRE . "witkey_basic_config set v='$v' where k='$k'" );
				} else {
				//	Keke::admin_system_log('创建了手机平台');
					$res .= Dbfactory::execute ( " insert into " . TABLEPRE . "witkey_basic_config values('','$k','$v','mobile','','')" );
				}
			}
			$Keke->_cache_obj->gc();
			Keke::admin_system_log($_lang['edit_mobile_log']);
			if ($res)
				Keke::admin_show_msg ( $_lang['binding_cellphone_account_successfully'], "index.php?do=$do&view=$view&op=config",3,'','success' );
			else
				Keke::admin_show_msg ( $_lang['binding_cellphone_account_fail'], "index.php?do=$do&view=$view&op=config",3,'','warning' );
		
		}
		break;
	case "manage" :
		if ($remain_fee) {
			if ($mobile_p && $mobile_u) {
				$sms = new sms('','','getbalance');
				$m   = $sms->send();
				if (! $m) {
					Keke::echojson ( $_lang['get_user_info_fail'], "2" );
					die ();
				} else {
					Keke::echojson ($m, "1" );
					die ();
				}
			} else {
				Keke::admin_show_msg ( $_lang['not_bind_cellphone_account'], "index.php?do=$do&view=$view&op=config",3,'','warning' );
			}
		
		}
		break;
}
 检测绑定账号是否存在 
 
function check_bind($k) {
	return Dbfactory::get_count ( " select k from " . TABLEPRE . "witkey_basic_config where k='$k'" );
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */