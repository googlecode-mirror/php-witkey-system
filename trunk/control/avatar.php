<?php defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * �û�ͼ�����
 * this not free,powered by keke-tech
 * @author jiujiang
 * @charset:GBK  last-modify 2011-10-15-����09:04:22
 * @version V2.0
 * 
 */
/**
 * @param 
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
