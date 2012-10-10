<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ��ҵ����
 * @author Michael
 * @version 2.2
   2012-10-10
 */

class Control_admin_config_indus extends Controller{
    /**
     * ��ҵ�б�
     */    
	function action_index(){
		global $_K,$_lang;
		$indus_arr = DB::select()->from('witkey_industry')->execute();
		//Keke::get_table_data ( "*", "witkey_industry", '', "", "", "", "", 0 );
		sort ( $indus_arr );
		$del_uri = BASE_URL.'/index.php/admin/config_indus/del';
		$add_uri = BASE_URL.'/index.php/admin/config_indus/add';
		$t_arr = array ();
		Keke::get_tree ( $indus_arr, $t_arr, 'cat', NULL, 'indus_id', 'indus_pid', 'indus_name' );
		$indus_tree_arr =$t_arr;
		unset ( $t_arr );
		//print_r($indus_tree_arr);die;
		$indus_index_arr = Sys_indus::get_indus_by_index ();
		require Keke_tpl::template("control/admin/tpl/config/indus");
	}
	
	/**
	 * ����������ҵ����
	 */
	function action_save(){
		global $_lang;
		Keke::formcheck($_POST['formhash']);
		 
		//��ҵ��������,indus_id => indus_name
		$names = $_POST['names'];
		$orders = $_POST['orders'];
		$no = array();
		//�ϲ�Ҫ���µ�����������
		foreach ($names as $k=>$v){
			$no[$k] = array('name'=>$v,'order'=>$orders[$k]);
		}
		//Ҫ���µ����ݲ��뵽���ݿ�
		foreach ($no as $k=>$v){
			$columns = array('indus_name','listorder');
			$values = array($v['name'],$v['order']);
			$where = "indus_id = '$k'";
			$res += DB::update('witkey_industry')->set($columns)->value($values)->where($where)->execute();
		}
		//��������ҵ����
		$add_indus_name_listarr  = $_POST['add_indus_name_listarr'];
		//��������ҵ����
		$add_indus_name_arr = $_POST['add_indus_name_arr'];
		//�ϲ�����������������
		$add_arr = array();
		if($add_indus_name_arr){
			foreach ($add_indus_name_arr as $k=>$v) {
				$t = array();
				foreach ($v as $i=>$j){
				  $t[] = array('pid'=>$k,'name'=>$v[$i],'order'=>$add_indus_name_listarr[$k][$i]);
				}
				$add_arr[$k] = $t;
			}
		}
		//���������ݲ��뵽��
		if($add_arr){
			foreach ($add_arr as $k=>$v){
				$columns = array('indus_pid','indus_name','listorder');
				foreach ($v as $v1){
					$values = array($v1['pid'],$v1['name'],$v1['order']);
					DB::insert('witkey_industry')->set($columns)->value($values)->execute();
				}
			}
		}
		Keke::show_msg($_lang['submit_success'],'index.php/admin/config_indus','success');
	}
	/**
	 * ɾ����ҵ����
	 */
	function action_del(){
		
	}
	
}