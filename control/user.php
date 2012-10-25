<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 *  用户中心控制层基类
 * @author Michael
 * @version 2.2
   2012-10-25
 */

abstract  class Control_user extends Controller{
    /**
     * 一级导航菜单
     */
	protected static  $_nav = array(
    		  'msg'=>array('消息','msg_index'),
    		  'buyer'=>array('买家','index'),
    		  'seller'=>array('服务商','seller_index'),
    	 	  'finance'=>array('收支明细','finance_index'),
    		  'account'=>array('账号管理','account_index'),
    		  'custom'=>array('客户服务','custom_index')
    		);
    protected static $_default = 'buyer';
	function __construct($request, $response){
		
	}
	
}