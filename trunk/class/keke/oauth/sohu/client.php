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
	 	//����token
	 	$request_token = self::$_oauth_obj->getRequestToken($url);
	 	$_SESSION['sohu']['oauth_token'] = $token = $request_token['oauth_token'];
	 	$_SESSION['sohu']['oauth_token_secret'] = $request_token['oauth_token_secret'];
	 	//������֤��ַ
	 	return self::$_oauth_obj->getAuthorizeUrl1($token, $url);
	 }
	 public function get_access_token(){
	 	$code = $_GET['oauth_verifier'];
	 	//$code �û���Ȩ���������֤��,���ط���token s array("oauth_token" => "the-access-token", "oauth_token_secret" => "the-access-secret", "user_id" => "9436992", "screen_name" => "abraham") 
	 	self::$_oauth_obj = new SohuOAuth(self::$_key, self::$_secret,$_SESSION['sohu']['oauth_token'],$_SESSION['sohu']['oauth_token_secret']);
	 	//�ٷ��ķ���ע���Ǵ���ģ����صĽ����ע�Ͳ�һ��Ҫע��
	 	$token = self::$_oauth_obj->getAccessToken($code);
	 	$_SESSION['sohu_token']['access_token'] = $token['oauth_token'];
	 	$_SESSION['sohu_token']['oauth_token_secret'] = $token['oauth_token_secret'];
	 	return $token['access_token'];
	 }
	 public function check_login(){
	 	//���tokenû��û��ֵ����ʾû��ͨ��oauth ��֤
	 	if(!$_SESSION['sohu_token']['access_token']){
	 		return FALSE;
	 	}else{
	 		return TRUE;
	 	}
	 }
 
	 /**
	  * �����Ѻ�΢���û���Ϣ
	  * @see Keke_oauth_weibo::get_login_info()
	  * @return  array (size=28)
  'id' => string '236964765' (length=9)
  'screen_name' => string '��Ž�' (length=6)
  'name' => string '' (length=0)
  'location' => string '������,������' (length=13)
  'description' => string '' (length=0)
  'url' => string '' (length=0)
  'gender' => string '1' (length=1)
  'profile_image_url' => string 'http://s4.cr.itc.cn/i/3/avt48.png' (length=33)
  'protected' => string '1' (length=1)
  'followers_count' => string '2' (length=1)
  'profile_background_color' => string '' (length=0)
  'profile_text_color' => string '' (length=0)
  'profile_link_color' => string '' (length=0)
  'profile_sidebar_fill_color' => string '' (length=0)
  'profile_sidebar_border_color' => string '' (length=0)
  'friends_count' => string '46' (length=2)
  'created_at' => string 'Thu Oct 20 10:39:04 +0800 2011' (length=30)
  'favourites_count' => int 0
  'utc_offset' => string '' (length=0)
  'time_zone' => string '' (length=0)
  'profile_background_image_url' => string '' (length=0)
  'notifications' => string '' (length=0)
  'geo_enabled' => boolean false
  'statuses_count' => int 0
  'following' => string '1' (length=1)
  'verified' => boolean false
  'lang' => string 'zh_cn' (length=5)
  'contributors_enabled' => boolean false
	  */
	 public function get_login_info(){
	 	if($this->check_login()){
	 		//��ȡ��ǰ���û���Ϣ
	 		$url = 'http://api.t.sohu.com/users/show.json';
	 		self::$_oauth_obj = new SohuOAuth(self::$_key, self::$_secret,$_SESSION['sohu_token']['access_token'],$_SESSION['sohu_token']['oauth_token_secret']);
	 		$uinfo =self::$_oauth_obj->get($url);
	 	}
	 	if(CHARSET == 'gbk'){
	 		$uinfo = Keke::utftogbk($uinfo);
	 	}
	 	
	 	return $uinfo;
	 }
	
}