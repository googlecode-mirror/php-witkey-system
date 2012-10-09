<?php
/**
 * keke短信抽象类
 * @author Michael
 * 2012-10-08
 *
 */
abstract class  Keke_msg {
	/**
	 * @var 默认短信接口
	 */
	public static $default = 'd9';
	/**
	 * 
	 * @var 短信实例
	 */
	public static $instances = array ();
	/**
	 * 
	 * @param string $name 短信接口名称，默认为三三得九
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
	
 
}
