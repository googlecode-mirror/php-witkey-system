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
			$class = new keke_useravatar_class();
			echo $data=$class->$method($uid);
		}else{
			exit('�ϴ���������!');
		}
	}
}

/* if ($a) {
	$method = $a;
	$uid = $input;
	$class = new keke_user_avatar_class();
	echo $data=$class->$method($uid);	
	exit ();
}else{
    exit('parame is error');
} */
