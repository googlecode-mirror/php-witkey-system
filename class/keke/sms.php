<?php
/**
 * keke�ֻ����ų�����
 * @author Michael
 * 2012-10-08
 *
 */
abstract class  Keke_sms {
	/**
	 * @var Ĭ�϶��Žӿ�
	 */
	public static $default = 'd9';
	/**
	 * 
	 * @var ����ʵ��
	 */
	public static $instances = array ();
	/**
	 * 
	 * @param string $name ���Žӿ����ƣ�Ĭ��Ϊ�����þ�,e.g (d9,..)
	 * @return Keke_sms_d9
	 */
	public static function instance($name = null ) {
		if ($name === null) {
			$name = Keke_msg::$default;
		}
		if (isset ( Keke_msg::$instances [$name] )) {
			return Keke_msg::$instances [$name];
		}
		$class = "Keke_sms_{$name}";
		Keke_msg::$instances [$name] = new $class ();
		return Keke_msg::$instances [$name];
	}
	
	/**
	 * ���Ͷ���
	 * @param $mobiles �ֻ���
	 * @param $content ��������
	 */
	abstract public function send($mobiles,$content);
	/**
	 * �����¼
	 */
	abstract public function error($e);
	
}