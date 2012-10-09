<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 *
 * @author Michael
 * @version 2.2
   2012-10-9
 */
require_once S_ROOT.'keke_client/weibo/sina/saetv2.ex.class.php';


class Keke_oauth_sina_client extends Keke_oauth_weibo{
     private static $_oauth_obj;
     private static $_weibo_obj;
     private static $_access_token;
     
	 function __construct(){
	 	if(self::$_oauth_obj==NULL){
	 		self::$_oauth_obj = new SaeTOAuthV2(self::$_key, self::$_secret);
	 	}
 	 }
	 public function get_auth_url($url){
	 	return self::$_oauth_obj->getAuthorizeURL($url);
	 }
	 public function get_access_token(){
	 	global $ouri,$code;
	 	$keys =   array('code'=>$code,'redirect_uri'=>$ouri);
	 	$token = self::$_oauth_obj->getAccessToken('code',$keys);
	 	$_SESSION['sina_token'] = $token;
	 	return $token['access_token'];
	 }
	 public function check_login(){
	 	//如果token没有没有值，表示没有通过oauth 认证
	 	if(!$_SESSION['sina_token']['access_token']){
	 		return FALSE;
	 	}else{
	 		self::$_weibo_obj = new SaeTClientV2(self::$_key, self::$_secret,$_SESSION['sina_token']['access_token']);
	 		return TRUE;
	 	}
	 }
	 public function get_login_info(){
	 	if($this->check_login()){
	 	    $uid = $_SESSION['sina_token']['uid'];
	 	     
	 	    $uinfo = self::$_weibo_obj->show_user_by_id($uid);
	 	}
	 	var_dump(Keke::utftogbk($uinfo));
	 }
	
}