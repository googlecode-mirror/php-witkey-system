<?php	defined ( "IN_KEKE" ) or exit ( "Access Denied" );
/**
 * ������������
 * @copyright keke-tech
 * @author Aqing
 * @version v 2.0
 * 2010-08-29 14:37:34
 */
class Control_admin_user_mark extends Controller{
	function action_index(){
		global $_K,$_lang;
		$mark_rule = Db::select()->from('witkey_mark_rule')->execute();
		require Keke_tpl::template('control/admin/tpl/user/mark');
	}
	/**
	 * ���ҳ���ʼ��
	 * �������mark_rule_id��Ϊ�༭
	 */
	function action_add(){
		global $_K,$_lang;
		$mark_rule_id = $_GET['mark_rule_id'];
		if ($mark_rule_id){
			$where .= 'mark_rule_id='.$mark_rule_id;
			$mark_rule_arr = Db::select()->from('witkey_mark_rule')->where($where)->execute();
			$mark_rule_arr = $mark_rule_arr[0];
		}
		require Keke_tpl::template('control/admin/tpl/user/mark_add');
	}
	/**
	 * ������ӻ��߱༭�������
	 */
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		Keke::formcheck($_POST['formhash']);
		$array = array('g_value'=>$_POST['txt_g_value'],
				'm_value'=>$_POST['txt_m_value'],
				'g_title'=>$_POST['txt_g_title'],
				'm_title'=>$_POST['txt_m_title'],
				'g_ico'=>$_POST['hdn_g_ico'].'?fid='.$_POST['hdn_g_ico_fid'],
				'm_ico'=>$_POST['hdn_m_ico'].'?fid='.$_POST['hdn_m_ico_fid'],
				);
		if ($_GET['hdn_mark_rule_id']){
			Model::factory('witkey_mark_rule')->setData($array)->setWhere('mark_rule_id='.$_GET['hdn_mark_rule_id'])->update();
			Keke::show_msg("�༭�ɹ�","admin/user_mark/add?hdn_mark_rule_id=".$_GET['hdn_mark_rule_id'],"success");
		}else{
			Model::factory('witkey_mark_rule')->setData($array)->create();
			Keke::show_msg("��ӳɹ�","admin/user_mark/add","success");
		}
	}
	/**
	 * ɾ���ϴ����ͼƬ
	 */
	static function action_del_img(){
		//���pk��ֵ����ɾ���ļ����е�art_pic
		if($_GET['pk']){
			Dbfactory::execute(" update ".TABLEPRE."witkey_article set art_pic ='' where art_id = ".intval($_GET['pk']));
		}
		//û��fid�Ͳ���fid,û��fid����ɾ���ļ�,���ڰ�ȫ����
		if(!intval($_GET['fid'])){
			$fid = Dbfactory::get_count(" select file_id from ".TABLEPRE."witkey_file where save_name = '.{$_GET['filepath']}.'");
		}else{
			$fid = $_GET['fid'];
		}
		//ɾ���ļ�
		keke_file_class::del_att_file($fid, $_GET['filepath']);
		Keke::echojson ( '', '1' );
	}
	/**
	 * ����ɾ����������
	 */
	function action_del(){
		$mark_rule_id = $_GET['mark_rule_id'];
		$where .='mark_rule_id='.$mark_rule_id;
		echo Model::factory('witkey_mark_rule')->setWhere($where)->del();
	}
}