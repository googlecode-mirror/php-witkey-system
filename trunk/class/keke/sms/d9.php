<?php
/**
 * 短信发送接口V2.2
 * 三三得九的短信接口
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
				'username'=>"65974:".$_K['mobile_username'],//"65974:admin", //机构代码+账号
				'password'=>$_K['mobile_password'],//'27804005',
				'presendTime'=>date('Y-m-d H:i:s',time()),
				'to'=>$mobiles,
				'content'=>$content
		);
	}
	/**
	 * 发送手机短信
	 * @see Keke_sms::send()
	 */
	public function send($mobiles,$content){
		$this->init_params($mobiles,$content);
		$url = self::GATEWAY;
		//通过数组生成字符串
		$q   = http_build_query($this->_params);
		//判断curl_init 方式存在
		if(function_exists("curl_init")){
			//判断请求方式为get时连接$q
			$this->_method=='get' and $url.=$q;
			//提交请求
			$m	 = Keke::curl_request($url,false,$this->_method,$this->_params);
		//判断fscokeopen方法	
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
			throw new Keke_exception('短信发送错误代码: :err',array(':err'=>$e));
		}
		$num = ltrim($e,'OK:');
		/* if($num<0){
			$err = array(
				'-1'=>'用户名或密码错误',
				'-2'=>'余额不足',
				'-3'=>'号码太长，不能超过1000条一次提交',
				'-4'=>'无合法号码',
				'-5'=>'内容包含不合法文字',
				'-6'=>'内容太长',
				'-7'=>'内容为空',
				'-8'=>'定时时间格式不对',
				'-9'=>'修改密码失败',
				'-10'=>'用户当前不能发送短信',
				'-11'=>'Action参数不正确',
				'-100'=>'系统错误'
			);
			$message = array(':e'=>$e,':err'=>$err[$e]);
			Keke::$_log->add(Log::WARNING,"错误码::e,详细::err", $message)->write();
		} */
		return $num;
	}
}