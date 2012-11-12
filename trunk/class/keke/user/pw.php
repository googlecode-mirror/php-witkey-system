<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * PHPWind 的用户信息
 * @author Michael	
 * @version 2.2 
 * 2012-11-06
 *
 */
include_once S_ROOT.'client/pw_client/uc_client.php';

class Keke_user_pw extends Keke_user {
 
	function get_user_info($uid,$fields){
		return Keke_user::instance('keke')->get_user_info($uid,$fields);
	}
	
	function get_avatar($uid,$size){
		 $size = in_array ( $size, array ('middle', 'small' ) ) ? $size : 'middle';
		 $path = UC_API ."/attachment/$size/middle/$uid/$uid.jpg";
         if(Keke::remote_file_exists($path)){
         	return $path;
         }else{
         	return UC_API ."/images/face/1.gif";
         }
	}
	 
	
	function del_user($uid){
		Keke_user::instance('keke')->del_user($uid);
		uc_user_delete($uid);
	}
	
}
