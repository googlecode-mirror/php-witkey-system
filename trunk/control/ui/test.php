<?php
class Contorl_ui_test extends Controller {
	
	function action_index(){
		global $_K,$_lang;
		
		require Keke_tpl::template('ui/test');
	}
}

?>