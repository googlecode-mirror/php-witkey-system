<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 *  �û����Ŀ��Ʋ����
 * @author Michael
 * @version 2.2
   2012-10-25
 */
define('USER_URL', PHP_URL.'/user');
abstract  class Control_user extends Controller{
	protected $uid ;
	protected $username;
	protected $group_id;
    /**
     * һ�������˵�
     */
	protected static  $_nav = array(
    		  'msg'=>array('��Ϣ','msg_index'),
    		  'buyer'=>array('���','buyer_index'),
    		  'seller'=>array('������','seller_index'),
    	 	  'finance'=>array('��֧��ϸ','finance_recharge'),
    		  'account'=>array('�˺Ź���','account_index'),
    		  'custom'=>array('�ͻ�����','custom_report')
    		);
    protected static $_default = 'buyer';
    
    /**
     * ��Ϣ���� 
     */
    protected static $_msg_nav  = array(
    		'index'=>array('д����','msg_index'),
    		'in'=>array('�ռ���','msg_in'),
    		'out'=>array('������','msg_out'),
    		);
    
    /**
     * ��ҵ���
     */
    protected static $_buyer_nav = array(
    		'index'=>array('�ҷ�������','buyer_index'),
    		'goods'=>array('�������Ʒ','buyer_goods'),
    		'faver'=>array('�ҵ��ղ�','buyer_faver'),
    		'mark'=>array('���۹���','buyer_mark'),
    		);
    /**
     * ���ҵ���
     */
    protected static $_seller_nav =array(
    		'shop'=>array('���̹���','seller_shop'),
    		'index'=>array('�Ҳ��������','seller_index'),
    		'order'=>array('����������Ʒ','seller_order'),
    		'goods'=>array('�ҷ�������Ʒ','seller_goods'),
    		'mark'=>array('���۹���','seller_mark'),
    		);
    /**
     * �˺ŵ���
     */
    protected static $_account_nav = array(
    		'basic'=>array('��������','account_basic'),
    		'detail'=>array('��ϸ����','account_detail'),
    		'safe'=>array('�˺Ű�ȫ','account_safe'),
    		'auth'=>array('�˺���֤','account_auth'),
    		'bind'=>array('�˺Ű�','account_bind'),
    		'prom'=>array('�ƹ�׬Ǯ','account_prom'),
    		);
    /**
     * ��֧����
     */
    protected static $_finance_nav = array(
    		'recharge'=>array('��Ҫ��ֵ','finance_recharge'),
    		'withdraw'=>array('��Ҫ����','finance_withdraw'),
    		'detail'=>array('��֧��ϸ','finance_detail'),
    		'recharges'=>array('��ֵ��¼','finance_recharges'),
    		'withdraws'=>array('���ּ�¼','finance_withdraws'),
    		'prom'=>array('�ƹ�����','finance_prom'),
    		);
    /**
     * �ͷ�����
     */
    protected static $_custom_nav = array(
    		'report'=>array('�ٱ�','custom_report'),
    		'steer'=>array('����','custom_steer'),
    );
    
    function __construct($request,$response){
    	parent::__construct($request, $response);
    	//Keke_user_login::instance()->auto_login();
    	$this->uid = $_SESSION['uid'];
    	$this->username = $_SESSION['username'];
    	if(Keke_user_login::instance()->logged_in()===FALSE){
    		Cookie::set('last_page', $this->request->uri());
    		$this->request->redirect('login');
    	}
    	$res = Keke_user::instance()->get_user_info($this->uid,'group_id');
    	$this->group_id = $res['group_id'];
    	if($this->group_id==3){
    		unset(self::$_account_nav['detail']);
    	}
    }
    
    
}