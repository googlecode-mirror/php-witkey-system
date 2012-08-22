<?php defined ( 'IN_KEKE' ) or exit('Access Denied');

/* class Control_en extends Controller{
	
	function action_index() {
		var_dump(32322332);die;
		 $do = Request::initial()->controller();
		 var_dump($do);
		 require Keke_tpl::template ( $do );
	}
} */

class Control_en extends Controller{

	function action_index(){
			
		require Keke_tpl::template('en');
			

	}
}
die;
// $uri = Keke_Request::detect_uri();
// $request = new Keke_Request($uri);
// $d = $request->directory();
//echo $do = Request::factory()->controller();
// echo Request::initial()->controller();
if(!isset($do)){
	$do = Request::initial()->controller();
}
// echo $do;die;
require Keke_tpl::template ($do);
