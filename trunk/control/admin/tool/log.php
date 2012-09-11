<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ϵͳ��־
 * @copyright keke-tech
 * @author Monkey
 * @version v 2.0
 * 2010-5-24����03:46:14
 */
class Control_admin_tool_log extends Controller{
	
	function action_index(){
		//����ȫ�ֱ��������԰���ֻҪ����ģ�壬����Ǳ���Ҫ����.��
		global $_K,$_lang;
		
		//Ҫ��ʾ���ֶ�,��SQl��SELECTҪ�õ����ֶ�
		$fields = ' `log_id`,`username`,`log_content`,`log_ip`,`log_time` ';
		//Ҫ��ѯ���ֶ�,��ģ������ʾ�õ�
		$query_fields = array('log_id'=>$_lang['id'],'log_content'=>$_lang['name'],'log_time'=>$_lang['time']);
		//�ܼ�¼��,��ҳ�õģ��㲻���壬���ݿ���Ƕ��һ�εġ�Ϊ���ٸ�Sql��䣬�����Ҫд�ģ���!
		$count = intval($_GET['count']);
		//tool������һ��Ŀ¼������û�ж���toolΪĿ¼��·��,����������Ʋ���ļ���too_file So���ﲻ��дΪtool/file
		$base_uri = BASE_URL."/index.php/admin/tool_log";
		
		//��ӱ༭��uri,add���action �ǹ̶���
		//$add_uri =  $base_uri.'/add';
		//ɾ��uri,delҲ��һ���̶��ģ�д�������ģ���������
		$del_uri = $base_uri.'/del';
		//Ĭ�������ֶΣ����ﰴʱ�併��
		$this->_default_ord_field = 'log_time';
		//����Ҫ��ˮһ�£�get_url���Ǵ����ѯ������
		extract($this->get_url($base_uri));
		//��ȡ�б��ҳ���������,����$where,$uri,$order,$page������get_url����
		$data_info = Model::factory('witkey_system_log')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$list_arr = $data_info['data'];
		//��ҳ����
		$pages = $data_info['pages'];
		//�û���
		$group_arr = keke_admin_class::get_user_group ();
		
		require Keke_tpl::template('control/admin/tpl/tool/log');
		
	}
	/**
	 * ��־��¼��ɾ��,֧�ֵ����ɾ��
	 */
	function action_del(){
		//ɾ������,�����file_id ����ģ���ϵ������������е�
		if($_GET['log_id']){
			$where = 'log_id = '.$_GET['log_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'log_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_system_log')->setWhere($where)->del();
		
	}
	
	
}
/* 
Keke::admin_check_role (19);
$group_arr = keke_admin_class::get_user_group ();
//��ʼ������
$table_obj = keke_table_class::get_instance ( 'witkey_system_log' );
intval ( $page ) or $page = 1;
intval ( $wh ['page_size'] ) or $wh ['page_size'] = 10;
$url = "index.php?do=$do&view=$view&txt_username=$txt_username&txt_start_time=$txt_start_time&txt_log_id=$txt_log_id&txt_end_time=$txt_end_time&page=$page&w[ord]={$w['ord']}&wh[slt_page_size]={$wh['slt_page_size']}";
//ɾ��
if ($ac == 'del') {
	$res = $table_obj->del ( 'log_id', $log_id );
	Keke::admin_system_log($_lang['delete_sys_log'] . $log_id ); //��־��¼
	$res and Keke::admin_show_msg ($_lang['delete_success'], $url,3,'','success' ) or Keke::admin_show_msg ($_lang['delete_fail'], $url,3,'','warning' );
} elseif ($ac == 'del_all') {
	$sql = sprintf ( "TRUNCATE TABLE %switkey_system_log", TABLEPRE );
	Dbfactory::execute ( $sql );
	Keke::admin_system_log( $_lang['empty_system_log'] );
	Keke::admin_show_msg ( $_lang['empty_system_log_success'], $url,3,'','success' );
} elseif ($sbt_action) {
	$res = $table_obj->del ( 'log_id', $ckb );
	Keke::admin_system_log( $_lang['mulit_delete_log'] );
	$res and Keke::admin_show_msg ($_lang['mulit_operate_success'], $url,3,'','success' ) or Keke::admin_show_msg ($_lang['mulit_operate_fail'], $url,3,'','warning' );

} else {
	//��ȡ��ѯ����
	$where = " 1 = 1";
	empty ( $txt_log_id ) or $where .= " and log_id =" . intval ( $txt_log_id );
	empty ( $txt_end_time ) or $where .= " and log_time <" . Keke::sstrtotime ( $txt_end_time );
	empty ( $txt_start_time ) or $where .= " and log_time >" . Keke::sstrtotime ( $txt_start_time );
	empty ( $txt_username ) or $where .= " and username like '%$txt_username%'";
	empty ( $txt_content ) or $where .= " and log_content like '%$txt_content%'";
	if(is_array($w['ord'])){
		$where .= ' order by '.$w['ord']['0'].' '.$w['ord']['1'];
	}else{
 
			$where .= " order by log_id desc";
	}
	 

	//��ѯ
	$d = $table_obj->get_grid ( $where, $url, $page, $wh ['page_size'],null,1,'ajax_dom' );
	$log_data = $d ['data'];
	$pages = $d ['pages'];

}

require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view ); */