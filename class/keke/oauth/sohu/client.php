<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 *
 * @author Michael
 * @version 2.2
   2012-10-9
 */
require_once S_ROOT.'keke_client/weibo/sohu/SohuOAuth.php';


class Keke_oauth_sohu_client extends Keke_oauth_weibo{
     private static $_oauth_obj;
     private static $_weibo_obj;
     private static $_access_token;
     
	 function __construct(){
	 	if(self::$_oauth_obj==NULL){
	 		self::$_oauth_obj = new SohuOAuth(self::$_key, self::$_secret);
	 	}
 	 }
	 public function get_auth_url($url){
	 	//请求token
	 	$token = self::$_oauth_obj->getRequestToken($url);
	 	//返回认证地址
	 	return self::$_oauth_obj->getAuthorizeUrl1($token, $url);
	 }
	 public function get_access_token(){
	 	global $code;
	 	//$code 用户授权后产生的认证码,返回访问token s array("oauth_token" => "the-access-token", "oauth_token_secret" => "the-access-secret", "user_id" => "9436992", "screen_name" => "abraham") 
	 	$token = self::$_oauth_obj->getAccessToken($code);
	 	$_SESSION['sohu_token']['access_token'] = $token['oauth_token'];
	 	$_SESSION['sohu_token']['oauth_token_secret'] = $token['oauth_token_secret'];
	 	$_SESSION['sohu_token']['uid'] = $token['user_id'];
	 	$_SESSION['sohu_token']['uname'] = $token['screen_name'];
	 	return $token['access_token'];
	 }
	 public function check_login(){
	 	//如果token没有没有值，表示没有通过oauth 认证
	 	if(!$_SESSION['sohu_token']['access_token']){
	 		return FALSE;
	 	}else{
	 		return TRUE;
	 	}
	 }
	 function get_weibo(){
	 	self::$_weibo_obj = new SohuOAuth(self::$_key, self::$_secret,$_SESSION['sohu_token']['access_token'],$_SESSION['sohu_token']['oauth_token_secret']);
	 }
	 /**
	  * 返回搜狐微博用户信息
	  * @see Keke_oauth_weibo::get_login_info()
	  */
	 public function get_login_info(){
	 	if($this->check_login()){
	 	    $uid = $_SESSION['sohu_token']['uid'];
	 	    $uri = 'http://api.t.sohu.com/users/show/id.json';
	 	    $uinfo = self::$_weibo_obj->get($uri,array('id'=>$uid));
	 	}
	 	if(CHARSET == 'gbk'){
	 		$uinfo = Keke::utftogbk($uinfo);
	 	}
	 	return $uinfo;
	 }
	
}