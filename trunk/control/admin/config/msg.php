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
     * ����ģ���б�
     */
    function action_tpl(){
    	global $_K,$_lang;
    	if($_POST['hdn']){
    		//���������ύ������
    		//�б���ϵ���������
    		foreach ($_POST['hdn'] as $k1=>$v1){
    			//�жϵ�ǰ���Ƿ���checked
    			if($_POST['ckb'][$k1]){
    				//ѭ��checked��ֵ��û��checked�ĸ���
    				foreach ($_POST['ckb'] as $k=>$v ){
    					$v['send_msg'] = intval($v['send_msg'])+0;
    					$v['send_mail'] = intval($v['send_mail'])+0;
    					$v['send_sms'] = intval($v['send_sms'])+0;
    				}
    			}else{
    				//һ����û��ѡset 0
    				$v['send_msg'] = 0;
    				$v['send_mail'] = 0;
    				$v['send_sms'] = 0;
    			}
    			//�ֶ�
    			$columns = array('send_msg','send_mail','send_sms');
    			//ֵ
    			$values = array($v['send_msg'],$v['send_mail'],$v['send_sms']);
    			//����Ϊÿһ��
    			$where = "tpl_id = '$k1'";
    			//ִ�и���
    			DB::update('witkey_msg_tpl')->set($columns)->value($values)->where($where)->execute();
    		}
    		$obj = $_POST['hdn_obj'];
    		if($obj){
    			$uri = "?obj=$obj";
    		}
    		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_msg/tpl'.$uri,'success');
    	}
    	//�ֻ����ʼ���վ����
    	$message_send_type = keke_global_class::get_message_send_type ();
    	
    	//���Ŷ��� eg (task,service)
		$message_send_obj  = keke_global_class::get_message_send_obj();
		//�ֶ�
 		$fields = ' `tpl_id`,`k`,`obj`,`desc`,`on_time`,`send_sms`,`send_mail`,`send_msg`';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('tpl_id'=>$_lang['id'],'desc'=>$_lang['name'],'on_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//����uri,��ǰ�����uri ,��������ͨ��Rotu����Եó����uri,Ϊ�˳������㣬�Լ���д����
		$base_uri = BASE_URL."/index.php/admin/config_msg/tpl";
		//��ӱ༭��uri,add���action �ǹ̶���
		$add_uri =  $base_uri.'_add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'on_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ָ�����͵�����
		if(isset($_GET['obj'])){
			$obj = $_GET['obj'];
			$where .= " and  obj = '$obj' ";
			$uri .= "&obj=$obj";
		}
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_msg_tpl')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		
    	require Keke_tpl::template('control/admin/tpl/config/msg_tpl');
    }
    /**
     * ����ģ��༭
     */
    function action_tpl_add(){
    	global $_K,$_lang;
    	$tpl_id = $_GET['tpl_id'];
    	//ģ��ID�ж�
    	if($tpl_id){
    		//ģ�������,�½��б���
    		$msg_tpl_arr = DB::select('tpl_id,k,desc')->from('witkey_msg_tpl')->cached(3600,'keke_msg_tpl')->execute();
    		//ģ������
    		$msg_tpl_info = DB::select('msg_tpl,sms_tpl,send_sms,send_mail,send_msg')->from('witkey_msg_tpl')->where("tpl_id='$tpl_id'")->execute();
    		$msg_tpl_info = $msg_tpl_info[0];
    		//��������
    		$message_send_type = keke_global_class::get_message_send_type ();
    	}
    	require Keke_tpl::template('control/admin/tpl/config/msg_tpl_add');
    }
    /**
     * ����ģ����Ϣ����
     */
    function action_tpl_save(){
    	global $_lang;
    	Keke::formcheck($_POST['formhash']);
    	if(!$_POST['hdn_tpl_id']){
    		Keke::show_msg($_lang['submit_fail'],'index.php/admin/config_msg/tpl_add?tpl_id='.$_POST['hdn_tpl_id'],'warning');
    	} 
    	$_POST = Keke_tpl::chars($_POST);
    	//�Ƿ��з�����
    	if($_POST['ckb']){
    		$send_sms = $_POST['ckb']['send_sms'];
    		$send_msg = $_POST['ckb']['send_msg'];
    		$send_mail = $_POST['ckb']['send_mail'];
    	}else{
    		//û�У�set 0
    		$send_sms =  $send_mail = $send_msg = 0;
    	}
    	//�ʼ�ģ��
    	$msg_tpl = $_POST['txt_msg'];
    	//�ֻ�����ģ��
    	
    	$sms_tpl = $_POST['txt_sms'];
        $array = array('send_sms'=>$send_sms,
        		'send_mail'=>$send_mail,
        		'send_msg'=>$send_msg,
        		'msg_tpl'=>$msg_tpl,
        		'sms_tpl'=>$sms_tpl);
    	//����
    	$where = "tpl_id ='{$_POST['hdn_tpl_id']}'";
    	//����
    	Model::factory('witkey_msg_tpl')->setData($array)->setWhere($where)->update();
    	Keke::show_msg($_lang['submit_succes'],'index.php/admin/config_msg/tpl_add?tpl_id='.$_POST['hdn_tpl_id'],'success');
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