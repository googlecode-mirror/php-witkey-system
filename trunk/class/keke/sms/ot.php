<?php
/**
 * ���ŷ��ͽӿ�V2.0
 * @author Chen tao
 *
 */
class Keke_sms_ot extends Keke_sms{
	const GATEWAY="http://59.42.249.36/sms/http/Sms3.aspx?";
	static $charset = "utf-8";
	protected $_method;
	private $_action;
	private $_params;
	public $_error;
	
	public function __construct($mobiles,$content,$action='sendsms',$method="post"){
		$this->_action = $action;
		$this->_method = strtolower($method);
		$this->init_params($mobiles,$content);
	}
	private function init_params($mobiles,$content){
		strtolower(CHARSET)==self::$charset or $content = Keke::gbktoutf($content);
		$this->_params = array(
				'action'=>$this->_action,
				'username'=>Keke::$_sys_config['mobile_username'],
				'userpwd'=>Keke::$_sys_config['mobile_password'],
				'timing'=>'',
				'mobiles'=>$mobiles,
				'content'=>$content
		);
	}
	public function send(){
		$url = self::GATEWAY;
		$q   = http_build_query($this->_params);
		if(function_exists("curl_init")){
			$this->_method=='get' and $url.=$q;
			$m	 = Keke::curl_request($url,false,$this->_method,$this->_params);
		}elseif(function_exists('fsockopen')){
			$url.=$q;
			$m   = Keke::socket_request($url,false);
		}else{
			$url.=$q;
			$m 	 = file_get_contents($url);
		}
		return $this->error($m);
	}
	public function error($e){
		if($e<0){
			$err = array(
				'-1'=>'�û������������',
				'-2'=>'����',
				'-3'=>'����̫�������ܳ���1000��һ���ύ',
				'-4'=>'�޺Ϸ�����',
				'-5'=>'���ݰ������Ϸ�����',
				'-6'=>'����̫��',
				'-7'=>'����Ϊ��',
				'-8'=>'��ʱʱ���ʽ����',
				'-9'=>'�޸�����ʧ��',
				'-10'=>'�û���ǰ���ܷ��Ͷ���',
				'-11'=>'Action��������ȷ',
				'-100'=>'ϵͳ����'
			);
			if(KEKE_DEBUG){
				$message = array(':e'=>$e,':err'=>$err[$e]);
				Keke::$_log->add(Log::WARNING,"������::e,��ϸ::err", $message)->write();
				
			}
		}
		return $e;
	}
}