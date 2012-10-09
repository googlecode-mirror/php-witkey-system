<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ��������
 */
class Control_admin_config_msg extends Controller{
    /**
     * ���Žӿ�����
     */
	function  action_index(){
    	global $_K,$_lang;
    	
    	require Keke_tpl::template('control/admin/tpl/config/msg_config');
    }
    /**
     * ����������Ϣ
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
    	//ִ�����ˣ�Ҫ��һ����ʾ������û�ж�ִ�еĽ�����жϣ�����͵���������ִ��ʧ�ܵĻ����϶����ᱨ��ġ���!
    	Keke::show_msg($_lang['submit_success'],'index.php/admin/config_msg','success');
    }
    /**
     * ���ŷ���
     */
    function action_send(){
    	global $_K,$_lang;
    	if(!$_POST){
    		require Keke_tpl::template('control/admin/tpl/config/msg_send');
    		die;
    	}
    	$tar_content=Keke_tpl::chars($_POST['tar_content']);
    	//��ͨ�û���,Ҳ���Ǹ������û�������
    	if($_POST['slt_type']=='normal'){
    		$tel_arr=Dbfactory::query(" select mobile from ".TABLEPRE."witkey_space where mobile is not null ");
    		//���ֻ����ö��Ÿ���
    		foreach($tel_arr as $v){
    			if($v['mobile']){
    			 $txt_tel .= $v['mobile'].",";
    			}
    		}
    		//ȥ�����Ķ���
    		$txt_tel = rtrim($txt_tel,',');
    	}else{
    		$txt_tel = $_POST['txt_tel'];
    	}
    	//���Ͷ���
    	$m = Keke_sms::instance()->send($txt_tel,$tar_content);
    	var_dump($m);die;
    	if($m>0){
    	 	Keke::show_msg($_lang['sms_send_success'],"index.php/admin/config_msg/send",'success');
    	}else{
    		Keke::show_msg($_lang['sms_send_fail'],"index.php/admin/config_msg/send",'warning');
    			
    	}
    	
    }
    /**
     * ���ŷ��ͻ�ȡ�û���Ϣ
     */
    function action_get_user(){
    	global $_lang;
    	$u  = $_POST['u'];
    	$type= $_POST['type'];
    	//�ж�������UID ����username
    	$type=='uid' and $where=" uid='$u' " or $where=" INSTR(username,'$u')>0 ";
    	//��ȡ�û���Ϣ
    	$user_info=Dbfactory::get_one(" select uid,username,phone,mobile from ".TABLEPRE."witkey_space where $where ");
    	if(!$user_info){
    		//���޴���
    		Keke::echojson($_lang['he_came_from_mars'],'3'); 
    	}else{
    		if(!$user_info['mobile']){
    			//����û���ֻ�
    			Keke::echojson($_lang['no_record_of_his_cellphone'],'2'); 
    		}else{
    			//�ֻ��ҵ���
    			Keke::echojson($user_info['mobile'],'1'); 
    		}
    	}
    }
    /**
     * ����ģ��
     */
    function action_tpl(){
    	global $_K,$_lang;
    	 
    	require Keke_tpl::template('control/admin/tpl/config/msg_tpl');
    }
    /**
     * ����ģ��༭
     */
    function action_tpl_add(){
    	global $_K,$_lang;
    	 
    	require Keke_tpl::template('control/admin/tpl/config/msg_tpl_add');
    }
    /**
     * ����ģ����Ϣ����
     */
    function action_tpl_save(){
    	
    }
    	
}


/* Keke::admin_check_role(66);
require '../../keke_client/sms/sms.php';
$account_info = $Keke->_sys_config; //�ֻ��˺���Ϣ
$mobile_u = $account_info ['mobile_username'];
$mobile_p = $account_info ['mobile_password'];
$op and $op = $op or $op = 'config';

$url = "index.php?do=$do&view=$view&op=$op";
switch ($op) {
	case "config" :
		if (! isset ( $sbt_edit )) {
			$bind_info = check_bind ( 'mobile_username' );
		} else { //��ӡ��༭\
			 
			foreach ( $conf as $k => $v ) {
				if (check_bind ( $k )) {
					
					$res .= Dbfactory::execute ( " update " . TABLEPRE . "witkey_basic_config set v='$v' where k='$k'" );
				} else {
				//	Keke::admin_system_log('�������ֻ�ƽ̨');
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
 �����˺��Ƿ���� 
 
function check_bind($k) {
	return Dbfactory::get_count ( " select k from " . TABLEPRE . "witkey_basic_config where k='$k'" );
}
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */