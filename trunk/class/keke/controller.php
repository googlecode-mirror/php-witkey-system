<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * Abstract controller class. Controllers should only be created using a [Request].
 *
 * Controllers methods will be automatically called in the following order by
 * the request:
 *
 *     $controller = new Controller_Foo($request);
 *     $controller->before();
 *     $controller->action_bar();
 *     $controller->after();
 *
 * The controller action should add the output it creates to
 * `$this->response->body($output)`, typically in the form of a [View], during the
 * "action" part of execution.
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2008-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Keke_Controller {

	/**
	 * @var  Request  Request that created the controller
	 */
	public $request;

	/**
	 * @var  Response The response that will be returned from controller
	 */
	public $response;
    
	public $_url;
	
	/**
	 * @var �б�ҳ�ϵ�Ĭ�������ֶ�
	 */
	public $_default_ord_field;
	
	/**
	 * Creates a new controller instance. Each controller must be constructed
	 * with the request object that created it.
	 *
	 * @param   Request   $request  Request that created the controller
	 * @param   Response  $response The request's response
	 * @return  void
	 */
	public function __construct(Keke_Request $request, Keke_Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;
	}

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		// global $_K;
		// $this->_K = $_K;
		 //$this->_url = BASE_URL.'/'.$this->request->url();
		// Nothing by default
	}

	/**
	 * Automatically executed after the controller action. Can be used to apply
	 * transformation to the request response, add extra output, and execute
	 * other custom code.
	 *
	 * @return  void
	 */
	public function after()
	{
		
		// Nothing by default
	}
	/**
	 * ��ȡ���ݷ�ҳ�������uri 
	 * @param string $base_uri
	 * @return multitype:string number
	 */
	function get_url($base_uri){
		$r = array();
		//��ʼ��where��ֵ
		$where = ' 1=1 ';
		if(strpos($base_uri, '?')!==false){
			$query_uri = '&';
		}else{
			$query_uri = '?';
		}
		
		//�ֶ�������
		if($_GET['slt_fields']  and $_GET['txt_condition']){
			//ʱ��Ĳ�ѯ����,ʱ���ֶ��뺬��time�������е㲻�Ͻ��������ж�����ֶ��ǲ���ʱ���ֶ�,����!
			if(strtotime($_GET['txt_condition']) and strpos($_GET['slt_fields'], 'time')!==false){
				//�ֶ�ֵΪʱ��ʱ
				$c =  $_GET['txt_condition'];
				//��������ݿ��е�on_time �ֶα�����ʱ���
				$f =  "FROM_UNIXTIME(`{$_GET['slt_fields']}`,'%Y-%m-%d')";
	
			}else{
				//��ʱ�������
				$c = $_GET['txt_condition'];
				$f = "{$_GET['slt_fields']}";
			}
			//�����like ������ֵҪ��%
			if($_GET['slt_cond']=='like'){
				$c = "%$c%";
			}
			//ƴ��url�ֶ�
			$where .= "and $f {$_GET['slt_cond']} '$c'";
				
			$query_uri .= "slt_cond={$_GET['slt_cond']}";
			$query_uri .= "&slt_fields={$_GET['slt_fields']}&txt_condition={$_GET['txt_condition']}";
		}
		if($_GET['page_size']){
			$query_uri .= '&page_size='.$_GET['page_size'];
		}
		//ҳ��
		$_GET['page'] and $page = $_GET['page'] or $page = 1;
	    //��ҳ���ӵ�uri��ȥ
	    $query_uri .= '&page='.$page;
		//�����uri,f��ʾҪ������ֶ�
		if($_GET['f']){
			$query_uri .= '&f='.$_GET['f'].'&ord='.$_GET['ord'];
		}
		//��ѯuri
		$uri = $base_uri.$query_uri;
		//����Ĭ�������ֶ�
		if(!isset($_GET['f'])){
			//Ĭ�ϰ�ʱ������
			$_GET['f'] = $this->_default_ord_field;
			//Ĭ�ϰ�������
			$_GET['ord'] = 0;
		}
		//�����ǣ�����js �еı���
		//����
		if(isset($_GET['ord']) and $_GET['ord']==1){
			$ord_tag = 0;
			$ord_char = '��';
			//����
		}elseif(isset($_GET['ord']) and $_GET['ord']==0){
			$ord_tag = 1;
			$ord_char = '��';
		}
	
	
		//���������
		if(isset($_GET['f'])){
			//$ord_tag ����1Ϊ���򣬷���Ϊ����
			$t = $ord_tag==1?'desc':'asc';
			//������������
			$order = " order by {$_GET['f']} $t ";
		}
		$r['where'] = $where;
		$r['query_uri'] =$query_uri;
		$r['uri'] = $uri;
		$r['ord_tag']=$ord_tag;
		$r['ord_char']=$ord_char;
		$r['order'] = $order;
		$r['page']=$page;
		return $r;
	}
	/**
	 * ˢ�µ�ǰҳ��
	 */
	function refer(){
		$this->request->redirect($this->request->referrer());
	}

} // End Controller
