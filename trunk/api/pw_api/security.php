<?php
!defined('P_W') && exit('Forbidden');
/**
 * Basic Security Filter Service
 * @author liuhui@2010-6-30
 * @status building
 */
class S {
	/**
	 * ����������
	 * @param $param
	 * @return int
	 */
	static function int($param) {
		return intval($param);
	}
	/**
	 * �ַ�����
	 * @param $param
	 * @return string
	 */
	static function str($param) {
		return trim($param);
	}
	/**
	 * �Ƿ����
	 * @param $param
	 * @return boolean
	 */
	static function isObj($param) {
		return is_object($param) ? true : false;
	}
	/**
	 * �Ƿ�����
	 * @param $params
	 * @return boolean
	 */
	static function isArray($params) {
		return (!is_array($params) || !count($params)) ? false : true;
	}
	/**
	 * �����Ƿ��������д���
	 * @param $param
	 * @param $params
	 * @return boolean
	 */
	static function inArray($param, $params) {
		return (!$param || !is_array($params) || !in_array($param, $params)) ? false : true;
	}
	/**
	 * �Ƿ��ǲ�����
	 * @param $param
	 * @return boolean
	 */
	static function isBool($param) {
		return is_bool($param) ? true : false;
	}
	/**
	 * �Ƿ���������
	 * @param $param
	 * @return boolean
	 */
	static function isNum($param) {
		return is_numeric($param) ? true : false;
	}
	/**
	 * ������/�����ļ�
	 * @param $file
	 */
	static function import($file) {
		if (!is_file($file)) return false;
		require_once $file;
	}
	/**
	 * htmlת�����
	 * @param $param
	 * @return string
	 */
	static function htmlEscape($param) {
		return trim(htmlspecialchars($param, ENT_QUOTES));
	}
	/**
	 * ���˱�ǩ
	 * @param $param
	 * @return string
	 */
	static function stripTags($param) {
		return trim(strip_tags($param));
	}
	/**
	 * ��ʼ��$_GET/$_POSTȫ�ֱ���
	 * @param $keys
	 * @param $method
	 * @param $cvtype
	 */
	static function gp($keys, $method = null, $cvtype = 1) {
		!is_array($keys) && $keys = array($keys);
		foreach ($keys as $key) {
			if ($key == 'GLOBALS') continue;
			$GLOBALS[$key] = NULL;
			if ($method != 'P' && isset($_GET[$key])) {
				$GLOBALS[$key] = $_GET[$key];
			} elseif ($method != 'G' && isset($_POST[$key])) {
				$GLOBALS[$key] = $_POST[$key];
			}
			if (isset($GLOBALS[$key]) && !empty($cvtype) || $cvtype == 2) {
				$GLOBALS[$key] = S::escapeChar($GLOBALS[$key], $cvtype == 2, true);
			}
		}
	}
	
	/**
	 * ָ��key��ȡ$_GET/$_POST����
	 * @param $key
	 * @param $method
	 */
	static function getGP($key, $method = null) {
		if ($method == 'G' || $method != 'P' && isset($_GET[$key])) {return $_GET[$key];}
		return $_POST[$key];
	}
	/**
	 * ȫ�ֱ�������
	 */
	static function filter() {
		$allowed = array('GLOBALS' => 1,'_GET' => 1,'_POST' => 1,'_COOKIE' => 1,'_FILES' => 1,'_SERVER' => 1,
						'P_S_T' => 1);
		foreach ($GLOBALS as $key => $value) {
			if (!isset($allowed[$key])) {
				$GLOBALS[$key] = null;
				unset($GLOBALS[$key]);
			}
		}
		if (!get_magic_quotes_gpc()) {
			S::slashes($_POST);
			S::slashes($_GET);
			S::slashes($_COOKIE);
		}
		S::slashes($_FILES);
		$GLOBALS['pwServer'] = S::getServer(array('HTTP_REFERER','HTTP_HOST','HTTP_X_FORWARDED_FOR','HTTP_USER_AGENT',
													'HTTP_CLIENT_IP','HTTP_SCHEME','HTTPS','PHP_SELF',
													'REQUEST_URI','REQUEST_METHOD','REMOTE_ADDR',
													'QUERY_STRING'));
		!$GLOBALS['pwServer']['PHP_SELF'] && $GLOBALS['pwServer']['PHP_SELF'] = S::getServer('SCRIPT_NAME');
	}
	
	/**
	 * ·��ת��
	 * @param $fileName
	 * @param $ifCheck
	 * @return string
	 */
	static function escapePath($fileName, $ifCheck = true) {
		if (!S::_escapePath($fileName, $ifCheck)) {
			exit('Forbidden');
		}
		
		return $fileName;
	}
	/**
	 * ˽��·��ת��
	 * @param $fileName
	 * @param $ifCheck
	 * @return boolean
	 */
	static function _escapePath($fileName, $ifCheck = true) {
		$tmpname = strtolower($fileName);
		$tmparray = array('://',"\0");
		$ifCheck && $tmparray[] = '..';
		if (str_replace($tmparray, '', $tmpname) != $tmpname) {
			return false;
		}
		return true;
	}
	/**
	 * Ŀ¼ת��
	 * @param unknown_type $dir
	 * @return string
	 */
	static function escapeDir($dir) {
		$dir = str_replace(array("'",'#','=','`','$','%','&',';'), '', $dir);
		return trim(preg_replace('/(\/){2,}|(\\\){1,}/', '/', $dir), '/');
	}
	/**
	 * ͨ�ö�����ת��
	 * @param $mixed
	 * @param $isint
	 * @param $istrim
	 * @return mixture
	 */
	static function escapeChar($mixed, $isint = false, $istrim = false) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = S::escapeChar($value, $isint, $istrim);
			}
		} elseif ($isint) {
			$mixed = (int) $mixed;
		} elseif (!is_numeric($mixed) && ($istrim ? $mixed = trim($mixed) : $mixed) && $mixed) {
			$mixed = S::escapeStr($mixed);
		}
		return $mixed;
	}
	/**
	 * �ַ�ת��
	 * @param $string
	 * @return string
	 */
	static function escapeStr($string) {
		$string = str_replace(array("\0","%00","\r",'\0','%00','\r'), '', $string); //modified@2010-7-5
		$string = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $string);
		$string = str_replace(array("%3C",'<'), '&lt;', $string);
		$string = str_replace(array("%3E",'>'), '&gt;', $string);
		$string = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $string);
		return $string;
	}
	/**
	 * �������
	 * @param $var
	 */
	static function checkVar(&$var) {
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				S::checkVar($var[$key]);
			}
		} elseif (P_W != 'admincp') {
			$var = str_replace(array('..',')','<','='), array('&#46;&#46;','&#41;','&#60;','&#61;'), $var);
		} elseif (str_replace(array('<iframe','<meta','<script'), '', $var) != $var) {
			global $basename;
			$basename = 'javascript:history.go(-1);';
			adminmsg('word_error');
		}
	}
	
	/**
	 * ����ת��
	 * @param $array
	 */
	static function slashes(&$array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					S::slashes($array[$key]);
				} else {
					$array[$key] = addslashes($value);
				}
			}
		}
	}
	
	/**
	 * ��ȡ����������
	 * @param $keys
	 * @return string
	 */
	static function getServer($keys) {
		$server = array();
		$array = (array) $keys;
		foreach ($array as $key) {
			$server[$key] = NULL;
			if (isset($_SERVER[$key])) {
				$server[$key] = str_replace(array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e'), '', $_SERVER[$key]);
			}
		}
		return is_array($keys) ? $server : $server[$keys];
	}
	
	/**
	 * ͨ�ö����ͻ��ת�庯��
	 * @param $var
	 * @param $strip
	 * @param $isArray
	 * @return mixture
	 */
	static function sqlEscape($var, $strip = true, $isArray = false) {
		if (is_array($var)) {
			if (!$isArray) return " '' ";
			foreach ($var as $key => $value) {
				$var[$key] = trim(S::sqlEscape($value, $strip));
			}
			return $var;
		} elseif (is_numeric($var)) {
			return " '" . $var . "' ";
		} else {
			return " '" . addslashes($strip ? stripslashes($var) : $var) . "' ";
		}
	}
	/**
	 * ͨ��","�ַ���������ת�����ַ�
	 * @param $array
	 * @param $strip
	 * @return string
	 */
	static function sqlImplode($array, $strip = true) {
		return implode(',', S::sqlEscape($array, $strip, true));
	}
	/**
	 * ��װ���� key=value ��ʽ��SQL��ѯ���ֵ insert/update 
	 * @param $array
	 * @param $strip
	 * @return string
	 */
	static function sqlSingle($array, $strip = true) {
		if (!S::isArray($array)) return ''; // modified@2010-7-2
		$array = S::sqlEscape($array, $strip, true);
		$str = '';
		foreach ($array as $key => $val) {
			$str .= ($str ? ', ' : ' ') . $key . '=' . $val;
		}
		return $str;
	}
	/**
	 * ��װ���� key=value ��ʽ��SQL��ѯ��� insert
	 * @param $array
	 * @param $strip
	 * @return string
	 */
	static function sqlMulti($array, $strip = true) {
		if (!S::isArray($array)) return ''; // modified@2010-7-2
		$str = '';
		foreach ($array as $val) {
			if (!empty($val) && S::isArray($val)) { //modified@2010-7-2
				$str .= ($str ? ', ' : ' ') . '(' . S::sqlImplode($val, $strip) . ') ';
			}
		}
		return $str;
	}
	/**
	 * ��װSQL��ѯ���������� 
	 * @param $start
	 * @param $num
	 * @return string
	 */
	static function sqlLimit($start, $num = false) {
		return ' LIMIT ' . ($start <= 0 ? 0 : (int) $start) . ($num ? ',' . abs($num) : '');
	}
}