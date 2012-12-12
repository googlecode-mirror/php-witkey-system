<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �û�����-�˺Ź���-��������
 * @author Michael
 * @version 3.0
   2012-12-11
 */

class Control_user_account_detail extends Control_user{
    
	/**
	 * @var һ���˵�ѡ����
	 */
	protected static $_default = 'account';
    /**
     * 
     * @var �����˵�ѡ����,��ֵ����ѡ��
     */
	protected static $_left = 'detail';
	/**
	 * ��������
	 */
	function action_index(){
		$where = "uid = $this->uid";
		$works  = DB::select()->from('witkey_member_work')->where($where)->execute();
		require Keke_tpl::template('user/account/work_list');
	}
	/**
	 * ������������
	 */
	function action_work_save(){
		
	}
	/**
	 * ����֤��
	 */
	function action_skill(){
		$where = "uid = $this->uid";
		$certs = DB::select()->from('witkey_member_cert')->where($where)->execute();
		$year = $this->get_year();

		require Keke_tpl::template('user/account/skill');
	}
	/**
	 * ����֤�鱣��
	 */
	function action_skill_save(){
		$cid = $_POST['cid'];
		$name = $_POST['name'];
		$year = $_POST['year'];
		$res = (array)keke_file_class::upload_file('pic');
		$pics = array();
	 
		foreach ($_FILES['pic']['name'] as $k=>$v){
			    if($v){
					$pics[$k] = array_shift($res);
			    }else{
			    	$pics[$k]= null;
			    }
		}
		 
		$data = array_map(NULL,$cid,$name,$year,$pics);
		
		foreach ($data as $v){
			$columns = array('cid','uid','name','year','pic');
			$values = array($v[0],$this->uid,$v[1],$v[2],$v[3]);
			$arr = array_combine($columns, $values);
			if($v[0]){
				Model::factory('witkey_member_cert')->setData($arr)->setWhere("cid=$v[0]")->update();
			}else{
				Model::factory('witkey_member_cert')->setData($arr)->create();
			}
		}
		$this->request->redirect('user/account_detail/skill');
	}
	/**
	 * ����֤��ɾ�� 
	 */
	function action_skill_del(){
		$cid = $_GET['cid'];
		$pic = $_GET['pic'];
		$res = Model::factory('witkey_member_cert')->setWhere("cid = $cid and uid = $this->uid")->del();
		if($res){
			unlink(S_ROOT.$pic);
		}
	}
	/**
	 * ���ܱ�ǩ
	 */
	function action_skill_tag(){
		$where = "uid = $this->uid";
	    $skills = DB::select('skill_ids')->from('witkey_space')->where($where)->get_count()->execute();	
		//�û�ѡ�еļ���
		if($skills){
	    	$skills = explode(',', trim($skills,','));
		}
	    $indus_arr =  Sys_indus::get_indus_tree(0);
	    
	    //���ȡ������ǩ
	    $sql = "SELECT skill_name \n".
				"FROM `:keke_witkey_skill` AS t1 JOIN \n".
				"(SELECT ROUND(RAND() * (SELECT MAX(skill_id) FROM `:keke_witkey_skill`)) AS id) AS t2 \n".
				"WHERE t1.skill_id >= t2.id \n".
				"ORDER BY t1.skill_id ASC LIMIT 6";
	    $tags = DB::query($sql)->tablepre(':keke_')->execute();
	    
	    require Keke_tpl::template('user/account/skill_tag');
	}
	/**
	 * ���ܱ�ǩ����
	 */
	function action_tag_save(){
		$skills = $_POST['skill'];

		$use_skill = DB::select('skill_ids')->from('witkey_space')->where("uid= $this->uid")->get_count()->execute();
		if($use_skill){
			$use_skill = explode(',', trim($use_skill,','));
		}
		$c = sizeof((array)$use_skill);
// 		echo $c;die;
		$t = array();
		for($i=0;$i<(5-$c);$i++){
		 $t[] = $skills[$i];	
		}
		$tags = implode(',', $t);
		if($tags){
			$sql = "update :keke_witkey_space set skill_ids = concat(skill_ids,',$tags') where uid = $this->uid";
			DB::query($sql,Database::UPDATE)->tablepre(':keke_')->execute();
		}
		$this->request->redirect('user/account_detail/skill_tag');
	}
	function action_get_tag(){
		$indus = $_GET['indus'];
		$res = (array)DB::select('skill_name')->from('witkey_skill')->where("indus_id=$indus")->execute();
		
		foreach ($res as $k=>$v){
		  echo  "<input id=\"s$k\" type=\"checkbox\" name=\"skill[]\" value=\"{$v['skill_name']}\"><label for=\"s$k\">{$v['skill_name']}</label>";
		}
	}
	/**
	 * ���ܱ�ǩɾ��
	 */
	function action_tag_del(){
		$tag  = $_GET['tag'];
		$sql = "update `:keke_witkey_space` \n".
				"set skill_ids = ( REPLACE(skill_ids,'$tag,','') )\n".
				",skill_ids = (replace(skill_ids,'$tag',''))\n".
				" where uid = :uid";
		DB::query($sql,Database::UPDATE)->tablepre(':keke_')->param(':uid', $this->uid)->execute();
	}
	
	/**
	 * �����20��
	 * @return  array
	 */
	function get_year(){
		$y = date('Y',(int)SYS_START_TIME);
		$o = array();
		for($i=0;$i<=20;$i++){
		    $o[] = ($y-$i);	
		}
		return $o;
	}
}