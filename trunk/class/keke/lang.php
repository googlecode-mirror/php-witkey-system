<?php  defined('IN_KEKE') or die('access is deined');
/**
 * ��������
 * ��ͬ�������ļ����ڲ�ͬ��Ŀ¼��ָ��Ŀ¼�ļ������Ŀ¼�µ�public.php
 * �������ȼ�,public/public.php��$dir/public.php���ټ���ָ����lang.php
 * @author Michael
 * @version 3.0 2012-12-07
 *
 */
class Keke_lang {
	
	/**
	 * @var Ĭ������Ŀ¼
	 */
	private $_default = 'public';
	/**
	 * @var ����Ŀ¼ 
	 */
	private  $_dir = 'public';
	/**
	 * @var ��ǰ���԰�   : cn,tw,en,ko
	 */
	public static $lang='cn';
	
	private static $_caches;
	
	private static $_instance;
	
	/**
	 * ���ص��ļ��б���������
	 */
	public static $_files = array();
	/**
	 * ��ǰ�����б�
	 */
	public static $lang_list =  array(
			"cn"=>"��������",
			"tw"=>"��������",
			"en"=>"English",
			"ko"=>"korea");
	/**
	 * ����
	 * @return Keke_lang
	 */
	public static function get_instance() {
		if(self::$_instance){
			return self::$_instance;
		}
		self::$_instance = new self();
		return self::$_instance;
	}
	/**
	 * ָ��Ŀ¼
	 * @param string $dir
	 * @return Keke_lang
	 */
	function set_dir($dir){
		$this->_dir = $dir;
		return $this;
	}
	
	/**
	 * ����ָ���� key ��value;
	 * @param string $key
	 * @return string
	 */
	function lang($key){
		global $_lang;
		return $_lang[$key];
	}
	/**
	 * ����lang���飬����lang��Ϊȫ�ֱ���
	 * @param string $class lang�ļ�
	 */
	public function load($class){
		global $_lang;
		$lang = array();
		$lang = $this->load_public();
		$lang = $lang +(array)$this->load_file($class); 
		foreach ($lang as $k=>$v){
			$_lang[$k] =$v;
		}
	}
	public function load_public(){
		$lang = array();
		$p_name = S_ROOT.'lang/'.$this->get_lang()."/public/public.php";
		include $p_name ;
		self::$_files[] = $p_name;
		return $lang;
	}
	/**
	 * ����ָ���������ļ�
	 * @param string $class ���԰��ļ�
	 * @param string $dir  Ŀ¼
	 * @return array
	 */
	private  function load_file($class){
		$lang  = array();
		$r = self::get_lang();
		$dir = $this->_dir;
		$file_name = S_ROOT.'lang/'.$r."/{$dir}/{$class}.php";
		//Ŀ¼�µĹ����ļ�
		$p_name = S_ROOT.'lang/'.$r."/{$dir}/public.php";
		if(file_exists($p_name) AND $this->_default != $this->_dir){
			self::$_files[] = $p_name;
			include $p_name;
		}
 		if(file_exists($file_name)){
			self::$_files[] = $file_name;
			include $file_name;
			$lang += $lang;
		}
		return $lang;
	}
	/**
	 * ������������ļ�
	 * @param string $class_name
	 */ 
	public static function load_lang_class($class_name){
		 $o  = self::get_instance();
		 $o->set_dir('public');
         $o->load($class_name);
	}
	
	/**
	 * ���ص�ǰ����
	 * @return string
	 */
	public static function get_lang(){
		$lang_arr =self::$lang_list;
		$l = Cookie::get('keke_lang');
		if(!isset($lang_arr[$l])){
			$l = self::$lang;
		}
		return $l;
	}
	/**
	 * ��������ҵĶ�Ӧ����
	 * @return array
	 */
	public static function get_curr_list(){
		global $_lang;
		return array(
				'cn'=>array('CNY',$_lang['rmb']),
				'tw'=>array('HKD',$_lang['hkd']),
				'ko'=>array('KRW',$_lang['krw']),
				'en'=>array('USD',$_lang['usd'])
		);
	}
	
}//end