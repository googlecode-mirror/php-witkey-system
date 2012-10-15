<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ֧�ܹ���
 * @author Michael
 * @version 2.2
   2012-10-10
 */

class Control_admin_config_skill extends Controller{
    
	function action_index(){
    	global $_K,$_lang;
    	//ѡ��Ҫ��ѯ���ֶΣ������б�����ʾ
		$fields = '`skill_id`,`indus_id`,`skill_name`,`listorder`,`on_time`';
		//�������õ����ֶ�
		$query_fields = array('skill_id'=>'��ҵID','skill_name'=>'��������','on_time'=>$_lang['time']);
		//����uri
		$base_uri = BASE_URL.'/index.php/admin/config_skill';
		//ͳ�Ʋ�ѯ�����ļ�¼��������
		$count = intval($_GET['count']);
		//Ĭ�������ֶ�
		$this->_default_ord_field = 'on_time';
		//������ѯ������
		extract($this->get_url($base_uri));
		//��ȡ��ҳ����ز���
		$data_info = Model::factory('witkey_skill')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//�б�����
		$skill_arr = $data_info['data'];
		$pages = $data_info['pages'];

		$indus_option_arr = DB::select()->from('witkey_industry')->execute();
		$indus_show_arr = Keke::get_arr_by_key($indus_option_arr,'indus_id');
    	require Keke_tpl::template("control/admin/tpl/config/skill");
    }
    
    function action_add(){
    	global $_K,$_lang;
    	if($_GET['skill_id']){
    		$sid = $_GET['skill_id'];
    		$skill_info = DB::select()->from('witkey_skill')->where("skill_id=$sid")->get_one()->execute();
    	}
    	//��ȡ���е���ҵ����
    	$indus_arr = DB::select()->from('witkey_industry')->execute();
    	$t_arr = array ();
    	//������������
    	Keke::get_tree ( $indus_arr, $t_arr, 'option', $skill_info['indus_id'], 'indus_id', 'indus_pid', 'indus_name' );
    	$indus_tree_arr =$t_arr;
    	unset ( $t_arr );
    	
    	require Keke_tpl::template('control/admin/tpl/config/skill_add');
    }
    function action_del(){
    	//ɾ������,�����case_id ����ģ���ϵ������������е�
		if($_GET['skill_id']){
			$where = 'skill_id = '.$_GET['skill_id'];
			//ɾ������,���������ͳһΪidsӴ����
		}elseif($_GET['ids']){
			$where = 'skill_id in ('.$_GET['ids'].')';
		}
		//���ִ��ɾ�����Ӱ��������ģ���ϵ�js �������ֵ���ж��Ƿ�����tr��ǩ��
		//ע���в��ܴ���������ȥע�͵Ĺ���ʧЧ,��ʹ�Ĺ��߰�!
		echo  Model::factory('witkey_skill')->setWhere($where)->del();
    }
    function action_save(){
    	$_POST = Keke_tpl::chars($_POST);
    	//��ֹ�����ύ���㶮��
    	Keke::formcheck($_POST['formhash']);
    	//������ô˵�أ���������sql ���ֶ�=>ֵ �����飬�㲻����������̫����.
    	$array = array(
    			'skill_name'=>$_POST['skill_name'],
    			'indus_id'=>$_POST['indus_id'],
    			'listorder' => $_POST['txt_listorder'],
    			'on_time'=>time(),
    	);
    	$skill_id=$_POST['hdn_skill_id'];
    	//���Ǹ������ֶΣ�Ҳ����������ֵ�����������ֵ������Ҫ�༭(update)���ݵ����ݿ�
    	if($skill_id){
    		Model::factory('witkey_skill')->setData($array)->setWhere("skill_id = '$skill_id'")->update();
    		//ִ�����ˣ�Ҫ��һ����ʾ��
    		Keke::show_msg('�ύ�ɹ�','admin/config_skill/add?skill_id='.$skill_id,'success');
    	}else{
    		//��Ҳ��Ȼ��������(insert)�����ݿ���
    		$cate_id = Model::factory('witkey_skill')->setData($array)->create();
    		
    		Keke::show_msg('�ύ�ɹ�','admin/config_skill/add','success');
    	}
    }
    
}

/* Keke::admin_check_role ( 8);
$table_obj = new keke_table_class ( "witkey_skill" );

//������ҵ�����˵�
$temp_arr = array ();
$indus_option_arr = Keke::get_industry ();
Keke::get_tree ( $indus_option_arr, $temp_arr,"option",$w[indus_pid] );
$indus_option_arr = $temp_arr;
unset ( $temp_arr );
is_array($indus_arr)&&sort ( $indus_arr );
$indus_show_arr = array();
Keke::get_tree($indus_arr, $indus_show_arr,'cat',NULL,'indus_id','indus_pid','indus_name');
$indus_show_arr = Keke::get_table_data('*',"witkey_industry","",'indus_id','','','indus_id');
$where = ' 1 = 1';


$order_where.=" order by on_time desc ";
$url = "index.php?do=$do&view=$view&w[indus_pid]={$w[indus_pid]}&w[skill_name]={$w[skill_name]}
&page_size=$page_size&page=$page
&$ord[0]={$ord[1]}";

intval ( $page_size ) and $page_size = intval ( $page_size ) or $page_size = 10;
intval ( $page ) and $page = intval ( $page ) or $page = 1;

if(isset($sbt_search)){
	$w [indus_id]  and $where .= " and indus_id = $w[indus_id]";
	strval ( $w [skill_name] ) and $where .= " and skill_name like '%$w[skill_name]%'";
	$ord [1] and $order_where = " order by $ord[0] $ord[1]";
}

$where =$where.$order_where;

$r = $table_obj->get_grid ( $where, $url, $page, $page_size );
$skill_arr = $r [data];
$pages = $r [pages];

if ($ac == 'del') {
	$skill_log = keke_table_class::all_table_info("witkey_skill", array("skill_id"=>$skill_id));
	$res = $table_obj->del('skill_id', $skill_id);
	Keke::admin_system_log($_lang['delete_skill'].":".$skill_log[skill_name]);
	$res and Keke::admin_show_msg($_lang['delete_success'], $url,'3','','success') or Keke::admin_show_msg($_lang['delete_fail'], $url,'3','','warning');
}
//����ɾ��
if ($sbt_action) {
	if (! count($ckb)){
		Keke::admin_show_msg ($_lang['choose_operation'], $url ,'3','','warning');
	}else{
		$res = $table_obj->del ('skill_id',$ckb);

		Keke::admin_system_log($_lang['mulit_delete_skill']);
		$res and Keke::admin_show_msg($_lang['delete_success'], $url,'3','','success') or Keke::admin_show_msg($_lang['delete_fail'], $url,'3','','warning');
	}
}
//�ݹ�����б�
$temp_arr = array ();
Keke::get_tree ( $indus_arr, $temp_arr, 'option', NULL, 'indus_id', 'indus_pid', 'indus_name' );
$indus_arr = $temp_arr;

unset ( $temp_arr );
require $Keke->_tpl_obj->template ( 'control/admin/tpl/admin_task_' . $view );

 */