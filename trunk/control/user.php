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
    		  'buyer'=>array('���','buyer_index'),
    		  'seller'=>array('������','seller_index'),
    	 	  'finance'=>array('��֧��ϸ','finance_index'),
    		  'account'=>array('�˺Ź���','account_index'),
    		  'custom'=>array('�ͻ�����','custom_index')
    		);
    protected static $_default = 'buyer';
    
    /**
     * ��Ϣ���� 
     */
    protected static $_msg_nav  = array(
    		'index'=>array('д����','msg_index'),
    		'in'=>array('�ռ���','msg_in'),
    		'send'=>array('������','msg_send'),
    		);
    
    /**
     * ��ҵ���
     */
    protected static $_buyer_nav = array(
    		'index'=>array('�ҷ�������','buyer_index'),
    		'goods'=>array('�������Ʒ','buyer_goods'),
    		'mark'=>array('���۹���','buyer_mark'),
    		);
    /**
     * ���ҵ���
     */
    protected static $_seller_nav =array(
    		'index'=>array('���������','seller_index'),
    		'goods'=>array('�ҷ�������Ʒ','seller_goods'),
    		'mark'=>array('���۹���','seller_mark'),
    		);
    /**
     * �˺ŵ���
     */
    protected static $_account_nav = array(
    		'index'=>array('��������','account_index'),
    		'detail'=>array('��ϸ����','account_detail'),
    		'safe'=>array('�˺Ű�ȫ','account_safe'),
    		'auth'=>array('�˺���֤','account_auth'),
    		);
    /**
     * ��֧����
     */
    protected static $_finanac_nav = array(
    		'index'=>array('���׼�¼','seller_mark'),
    		'index'=>array('��Ҫ��ֵ','seller_mark'),
    		'mark'=>array('��Ҫ����','seller_mark'),
    		'mark'=>array('��֧��ϸ','seller_mark'),
    		'index'=>array('��ֵ��¼','seller_index'),
    		'goods'=>array('���ּ�¼','seller_goods'),
    		);
    /**
     * �ͷ�����
     */
    protected static $_custom_nav = array(
    		
    		
    		);
    
}