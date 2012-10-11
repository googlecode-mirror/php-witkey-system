<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 *
 * @author Michael
 * @version 2.2
   2012-10-11
 */

class Control_admin_auth_list extends Controller{
    
	 
	
    /**
     * 认证后台管理的入口
     */
	function action_index(){
		$name = $this->request->param('id');
		$class = "Control_auth_{$name}_admin_list";
		$obj = new $class($this->request,$this->response);
		$obj->action_index();
	}
}
