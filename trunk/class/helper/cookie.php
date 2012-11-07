<?php  defined ( 'IN_KEKE' ) or die ( 'Access Dinied' );
class Cookie {
	public static $_pre = COOKIE_PRE;
	public static $_expiration = COOKIE_TTL;
	public static $_path = COOKIE_PATH;
	public static $_domain = COOKIE_DOMAIN;
	public static $_secure = FALSE;
	public static $_httponly = TRUE;
	public static $_salt = ENCODE_KEY;
	public static function get($key, $default = NULL) {
		//ǰ׺+key = ʵ�ʵ�KEY
		$key = self::$_pre . $key;
		//key�����ڣ����ؿ�
		if (! isset ( $_COOKIE [$key] )) {
			return $default;
		}
		//cookie ��ֵ
		$cookie = $_COOKIE[$key];
		//�ҳ������������ݵķָ���
		$split = strlen(Cookie::salt($key, NULL));
		if (isset($cookie[$split]) AND $cookie[$split] === '~'){
			//�ָ�hash��value
			list ($hash, $value) = explode('~', $cookie, 2);
		    //����������salt ����hash�ȶ�
			if (Cookie::salt($key, $value) === $hash){
				// ��Ч��cookieֵ
				return $value;
			}
			// ��֤��Ч��ɾ�����cookie 
			Cookie::delete($key);
		}
	    //����Ĭ��ֵ	
		return $default;
	}
	public static function set($name, $value, $expiration = NULL) {
		if ($expiration === NULL) {
			$expiration = Cookie::$_expiration;
		}
		if ($expiration !== 0) {
			$expiration += time ();
		}
		// ���salt��cookie ��ֵ��
		$name = self::$_pre.$name;
		$value = Cookie::salt($name, $value).'~'.$value;
		return setcookie ( $name, $value, $expiration, self::$_path, self::$_domain, self::$_secure, self::$_httponly );
	}
	public static function delete($name) {
		$name = self::$_pre.$name;
		//�Ƴ�cookie �е�ֵ
		unset ( $_COOKIE [$name] );
		//����cookie ����
		return setcookie ( $name, NULL, - 86400, self::$_path, self::$_domain, self::$_secure, self::$_httponly );
	}
	public static function salt($name, $value){
		// ��֤saltֵ
		if ( ! Cookie::$_salt){
			throw new Keke_exception('salt not empty,plase set salt');
		}
		// ȷ���û�����
		$agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';
		return sha1($agent.$name.$value.Cookie::$_salt);
	}
}