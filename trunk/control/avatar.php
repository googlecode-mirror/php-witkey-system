<?php defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * flash ͷ���Ͻ���
 * @author Administrator
 *
 */
class Control_avatar extends Controller{
	function action_index(){
		$a = $_GET['a'];
		if($a){
			$method = $a;
			$uid = $_GET['input'];
			$class = new Keke_user_avatar();
			echo $data=$class->$method($uid);
		}else{
			exit('�ϴ���������!');
		}
	}
}


