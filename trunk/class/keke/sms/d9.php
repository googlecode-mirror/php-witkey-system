<?php
/**
 * ���ŷ��ͽӿ�V2.2
 * �����þŵĶ��Žӿ�
 * @author Michael
 * 2012-10-08
 *
 */
class Keke_sms_d9 extends Keke_sms {
	
    const GATE = 'http://ws.iems.net.cn/GeneralSMS/ws/SmsInterface?wsdl';
	private static $_params;
	public static  $_error;
	
	private static function init_params($mobiles,$content){
		global $_K;
		if(CHARSET=='gbk'){
			$content = Keke::gbktoutf($content);
		}
		self::$_params = array(
				'username'=>$_K['mobile_username'].":admin", //��������+�˺�"
				'password'=>$_K['mobile_password'], 
				'to'=>$mobiles,
				'content'=>$content
		);
	}
	/**
	 * �����ֻ�����
	 * @see Keke_sms::send()
	 */
	public function send($mobiles,$content){
		self::init_params($mobiles,$content);
	    $client = new nusoap(self::GATE,true);
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8 = false;
		$client->xml_encoding = 'utf-8';
		$parameters	= array(self::$_params['username'],self::$_params['password'],'',self::$_params['to'],self::$_params['content'],'','0|0|0|0');
		$str=$client->call('clusterSend',$parameters);
		if (!($err=$client->getError())==null) {
			die("sms send error:".$err);
		}
		
		$obj = simplexml_load_string($str);
		$code = (int)$obj->code;
		if($code){
			return $this->error($code);
		}else{
			throw new Keke_exception($str);
		}
	}
 
	public static function  get_userinfo(){
		self::init_params('', '');
		$client = new nusoap(self::GATE,true);
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8 = false;
		$client->xml_encoding = 'utf-8';
		
		$parameters	= array(self::$_params['username'],self::$_params['password']);
		$str=$client->call('getUserInfo',$parameters);
		if (!($err=$client->getError())==null) {
			throw new Keke_exception("sms api error:".$err);
		}
		if(CHARSET == 'gbk'){
			$str = Keke::utftogbk($str);
		}
		$obj = simplexml_load_string($str);
		$arr  =Keke::objtoarray($obj);
		$user = array();
		$user['balance'] = (float)$obj->balance;
		$user['price'] =(float) $obj->smsPrice;
		return $user;
	}
	public function error($e){
		$err = array(
				'1000'=>'�����ɹ�',
				'1001'=>'�û������ڻ��������',
				'1002'=>'�û���ͣ��',
				'1003'=>'����',
				'1004'=>'����Ƶ��',
				'1005'=>'���ݳ���',
				'1006'=>'�Ƿ��ֻ�����',
				'1007'=>'�ؼ��ֹ���',
				'1008'=>'���պ�����������',
				'1009'=>'�ʻ�����',
				'1010'=>'������ʽ����',
				'1011'=>'��������',
				'1012'=>'���ݿⷱæ',
				'1013'=>'�Ƿ�����ʱ��');
		return $err[$e];
	}
}