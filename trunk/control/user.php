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
    		  'buyer'=>array('买家','buyer_index'),
    		  'seller'=>array('服务商','seller_index'),
    	 	  'finance'=>array('收支明细','finance_index'),
    		  'account'=>array('账号管理','account_index'),
    		  'custom'=>array('客户服务','custom_index')
    		);
    protected static $_default = 'buyer';
    
    /**
     * 消息导航 
     */
    protected static $_msg_nav  = array(
    		'index'=>array('写短信','msg_index'),
    		'in'=>array('收件箱','msg_in'),
    		'send'=>array('发件箱','msg_send'),
    		);
    
    /**
     * 买家导航
     */
    protected static $_buyer_nav = array(
    		'index'=>array('我发的任务','buyer_index'),
    		'goods'=>array('我买的商品','buyer_goods'),
    		'mark'=>array('评价管理','buyer_mark'),
    		);
    /**
     * 卖家导航
     */
    protected static $_seller_nav =array(
    		'index'=>array('参与的任务','seller_index'),
    		'goods'=>array('我发布的商品','seller_goods'),
    		'mark'=>array('评价管理','seller_mark'),
    		);
    /**
     * 账号导航
     */
    protected static $_account_nav = array(
    		'index'=>array('基本资料','account_index'),
    		'detail'=>array('详细资料','account_detail'),
    		'safe'=>array('账号安全','account_safe'),
    		'auth'=>array('账号认证','account_auth'),
    		);
    /**
     * 收支导航
     */
    protected static $_finanac_nav = array(
    		'index'=>array('交易记录','seller_mark'),
    		'index'=>array('我要充值','seller_mark'),
    		'mark'=>array('我要提现','seller_mark'),
    		'mark'=>array('收支明细','seller_mark'),
    		'index'=>array('充值记录','seller_index'),
    		'goods'=>array('提现记录','seller_goods'),
    		);
    /**
     * 客服导航
     */
    protected static $_custom_nav = array(
    		
    		
    		);
    
}