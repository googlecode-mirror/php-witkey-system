<?php
/**
 * ���ŷ��ͽӿ�V2.2
 * �����þŵĶ��Žӿ�
 * @author Michael
 * 2012-10-08
 *
 */
class Keke_sms_d9 extends Keke_sms {
	const GATEWAY="http://GATEWAY.IEMS.NET.CN/GsmsHttp?";
	static $charset = "gbk";
	protected $_method='post';
	private $_action ;
	private $_params;
	public $_error;
	
	private function init_params($mobiles,$content){
		global $_K;
		strtolower(CHARSET)==self::$charset or $content = Keke::utftogbk($content);
		$this->_params = array(
				'username'=>"65974:".$_K['mobile_username'],//"65974:admin", //��������+�˺�
				'password'=>$_K['mobile_password'],//'27804005',
				'presendTime'=>date('Y-m-d H:i:s',time()),
				'to'=>$mobiles,
				'content'=>$content
		);
	}
	/**
	 * �����ֻ�����
	 * @see Keke_sms::send()
	 */
	public function send($mobiles,$content){
		$this->init_params($mobiles,$content);
		$url = self::GATEWAY;
		//ͨ�����������ַ���
		$q   = http_build_query($this->_params);
		//�ж�curl_init ��ʽ����
		if(function_exists("curl_init")){
			//�ж�����ʽΪgetʱ����$q
			$this->_method=='get' and $url.=$q;
			//�ύ����
			$m	 = Keke::curl_request($url,false,$this->_method,$this->_params);
		//�ж�fscokeopen����	
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
		$e = trim($e);
		//if($e =='ERROR:eBalance'){
		if(strpos($e, 'ERROR')!==FALSE){
			throw new Keke_exception('���ŷ��ʹ������: :err',array(':err'=>$e));
		}
		$num = ltrim($e,'OK:');
		/* if($num<0){
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
			$message = array(':e'=>$e,':err'=>$err[$e]);
			Keke::$_log->add(Log::WARNING,"������::e,��ϸ::err", $message)->write();
		} */
		return $num;
	}
}