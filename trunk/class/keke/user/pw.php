<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * PHPWind ���û���Ϣ
 * @author Michael	
 * @version 2.2 
 * 2012-11-06
 *
 */
include_once S_ROOT.'client/pw_client/uc_client.php';

class Keke_user_pw extends Keke_user {
 
	function get_user_info($uid,$fields='*'){
		return Keke_user::instance('keke')->get_user_info($uid,$fields);
	}
	
	function get_avatar($uid,$size='middle'){
		
		 $size = in_array ( $size, array ('middle', 'small' ) ) ? $size : 'middle';
		 $user_info = uc_user_get($uid,1);
		 $avatars = explode('|', $user_info['avatar']);
		 $avatar = $avatars[0];
		 if(Keke_valid::not_empty($avatar)){
		 	//�Զ���ͼƬ
		 	if(strpos($avatar, '/')!==FALSE){
		 		$path = UC_API ."/attachment/upload/$size/$avatar";
		 	}else{
		 	  //ϵͳͼƬ
		 		$path = UC_API ."/images/face/$avatar";
		 	}
		 }else{
		 	//Ĭ��ͼƬ
		 	$path =  UC_API ."/images/face/0.gif";
		 }
		 return $path;
	}
	
	function del_user($uid){
		Keke_user::instance('keke')->del_user($uid);
		uc_user_delete($uid);
	}
	
}
