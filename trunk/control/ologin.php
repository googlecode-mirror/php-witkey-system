<?php defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

/**
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-5-27����9:55:00
 */
class Control_ologin extends Controller{
	function action_index(){
		global $_K,$_lang;
		$api_open = unserialize($_K['oauth_api_open']);
		$api_name = keke_global_class::get_open_api();
		$type = $_GET['type'];
		if($type){
			$u = Keke_oauth_login::instance($type)->get_login_info();
			var_dump($u);
		}
		require Keke_tpl::template("oauth_login");
	}
	
	function action_login(){
		 global $_K,$ouri,$code;
	     $type = $_GET['type'];
	     //���access_token ��ֵ,���ص�index
	     if($_SESSION[$type.'_token']['access_token']){
	     	$this->request->redirect('ologin?type='.$type);
	     }
	     //�ص�ҳ��
	     $ouri = $_K['website_url'].'/index.php/ologin/call/'.$type;
	     //url ��ַ����
	     $ouri = urlencode($ouri);
	  
	     if($_GET['back']){
 	     	Keke_oauth_login::instance($type)->get_access_token();
 	     	header('Location:'.$_K['website_url'].'/index.php/ologin?type='.$type);
 	     	die;
 	     }else{
 	     	$to_url =  Keke_oauth_login::instance($type)->get_auth_url($ouri);
 	     	$to_url = urldecode($to_url);
 	     	header("Location:".$to_url);
 	     }
	}
	function action_call(){
		global $_K;
		$type = $this->request->param('id');
		if($_GET){
			//����Ѷ��΢����������һ����չ����,ҲҪ�õ�
			$ext = http_build_query($_GET);
		}
		$uri = $_K['website_url'].'/index.php/ologin/login?back=1&type='.$type.'&'.$ext;
		$this->request->redirect($uri);
	}
	
}