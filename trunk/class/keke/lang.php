<?php defined('IN_KEKE') or die('access is deined');
class Keke_lang {
	
	private static $_init_lang_set = array();
	//�������Լ�
	private static $_dir = 'public';
	//��ǰ����
	public static $_lang='cn';
	
	private static $_caches;
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
	
 	public static function lang($key,$class=null,$dir=null){
		$r = self::getlang($key, $class, $dir);
		$dir or $dir = self::$_dir;
		$class or $class = 'public';
	 	//(KEKE_DEBUG==1 and !$r) and  $r = "lang:$key not found  please edit langfile lang/cn/{$dir}/{$class}.php"; 
		return $r;
	}
	/**
	 * ����Ŀ¼
	 * @param string $dir
	 * @return Keke_lang
	 */
	public static function set_dir($dir){
		self::$_dir = $dir;
		return self;
	}
	/**
	 * ����lang���飬����lang��Ϊȫ�ֱ���
	 * @param string $class lang�ļ�
	 * @param string $dir Ŀ¼
	 */
	public static function loadlang($class,$dir=null){
		global $_lang;
		(array)$lang = self::load_lang_file($class,$dir); 
		foreach ($lang as $k=>$v){
			$_lang[$k] =$v;
		 }
	}
	
	
	private static function getlang($key,$class,$dir=null){
		if ($class){
			$lang = self::load_lang_file($class,$dir);
			return $lang[$key];
		}else {
			return self::$_init_lang_set[$key];
		}
	}
	/**
	 * ���ص�ǰ����
	 * @return string
	 */
	public static function get_lang(){
		$lang_arr =self::$lang_list;
		$l = Cookie::get('keke_lang');
		if(!isset($lang_arr[$l])){
			$l = self::$_lang;
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
	/**
	 * ����ָ���������ļ�
	 * @param string $class ���԰��ļ�
	 * @param string $dir  Ŀ¼
	 * @return array
	 */
	private static function load_lang_file($class,$dir=null){
		$lang  = array();
		$r = self::get_lang();
		$dir or $dir = self::$_dir;
		$file_name = S_ROOT.'lang/'.$r."/{$dir}/{$class}.php";
		self::$_files[] = $file_name;
 		if(file_exists($file_name)){
			self::$_caches[$dir.$class] = $file_name;
			include $file_name;
		}
		return $lang;
		
	}
	 
	public  static function load_lang_class($class_name=null){
         self::loadlang($class_name,'public');
	}
	
}//end