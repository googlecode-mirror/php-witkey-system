<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 *  �û����Ŀ��Ʋ����
 * @author Michael
 * @version 2.2
   2012-10-25
 */

abstract  class Control_user extends Controller{
    /**
     * һ�������˵�
     */
	protected static  $_nav = array(
    		  'msg'=>array('��Ϣ','msg_index'),
    		  'buyer'=>array('���','index'),
    		  'seller'=>array('������','seller_index'),
    	 	  'finance'=>array('��֧��ϸ','finance_index'),
    		  'account'=>array('�˺Ź���','account_index'),
    		  'custom'=>array('�ͻ�����','custom_index')
    		);
    protected static $_default = 'buyer';
	function __construct($request, $response){
		
	}
	
}