<?php  defined ( 'IN_KEKE' ) or die ( 'Access Denied' );
/*
	ģ���ļ����� tpl Ŀ¼�£� Ĭ����default�ļ��С�
	ģ�建���� data/tpl_cĿ¼�¡�
*/

class Keke_tpl {
	static function parse_code($tag_code, $tag_id, $tag_type = 'tag') {
		global $_K;
		$tplfile = 'db/' . $tag_type . '_' . $tag_id;
		$objfile = S_ROOT . 'data/tpl_c/' . str_replace ( '/', '_', $tplfile ) . '.php';
		//read
		$tag_code = Keke_tpl::parse_rule ( $tag_code );
		//write
		Keke_tpl::swritefile ( $objfile, $tag_code ) or exit ( "File: $objfile can not be write!" );
		
		return $objfile;
	
	}
	static function parse_template($tpl) {
		global $_K;
		//����ģ��
		$tplfile = S_ROOT . './' . $tpl . '.htm';
		$objfile = S_ROOT . './data/tpl_c/' . str_replace ( '/', '_', $tpl ) . '.php';
		//read
		

		if (! file_exists ( $tplfile )) {
			$tpl = str_replace ( '/' . $_K ['template'] . '/', '/default/', $tpl );
			$tplfile = S_ROOT . './' . $tpl . '.htm';
		
		}
		
		$template = Keke_tpl::sreadfile ( $tplfile );
		empty ( $template ) and exit ( "Template file : $tplfile Not found or have no access!" );
		
		$template = Keke_tpl::parse_rule ( $template, $tpl );
		//write
		Keke_tpl::swritefile ( $objfile, $template ) or exit ( "File: $objfile can not be write!" );
	
	}
	/**
	 * 
	 * ��������
	 * @param string $content  -html
	 * @param array  $sub_tpls 
	 * @param string $tpl
	 * @return string
	 */
	public static function parse_rule($template, $tpl = null) {
		global $_K;
		//($_K['inajax'])&&ob_start();
		//ob_start();
		$template = preg_replace ( "/\<\!\-\-\{include\s+([a-z0-9_\/]+)\}\-\-\>/ie", "Keke_tpl::readtemplate('\\1')", $template );
		//������ҳ���еĴ���
		$template = preg_replace ( "/\<\!\-\-\{include\s+([a-z0-9_\/]+)\}\-\-\>/ie", "Keke_tpl::readtemplate('\\1')", $template );
		
		//��ǩ����
		$template = preg_replace ( "/\<\!\-\-\{tag\s+([^!@#$%^&*(){}<>?,.\'\"\+\-\;\":~`]+)\}\-\-\>/ie", "Keke_tpl::readtag(\"'\\1'\")", $template );
		
		//������
		$template = preg_replace ( "/\<\!\-\-\{showad\((.+?)\)\}\-\-\>/ie", "Keke_tpl::showad('\\1')", $template );
		//�������
		$template = preg_replace ( "/\<\!\-\-\{showads\((.+?)\)\}\-\-\>/ie", "Keke_tpl::showads('\\1')", $template );
		
		//Ԥ�������
		$template = preg_replace ( "/\<\!\-\-\{ad_show\((.+?),(.+?)\)\}\-\-\>/ie", "Keke_tpl::ad_show('\\1','\\2')", $template );
		
		$template = preg_replace ( "/\<\!\-\-\{ad_show\((.+?)\)\}\-\-\>/ie", "Keke_tpl::ad_show('\\1')", $template );
		//��̬����
		//$template = preg_replace ( "/\<\!\-\-\{loadfeed\((.+?)\)\}\-\-\>/ie", "Keke_tpl::loadfeed('\\1')", $template );
		//ʱ�䴦��
		$template = preg_replace ( "/\{date\((.+?),(.+?)\)\}/ie", "Keke_tpl::datetags('\\1','\\2')", $template );
		//������ʾ
		$template = preg_replace ( "/{c\:(.+?)(,?)(\d?)\}/ie", "Curren::currtags('\\1','\\3')", $template );
		//ͷ����
		$template = preg_replace ( "/\{avatar\((.+?),(.+?)\)\}/ie", "Keke_tpl::avatar('\\1','\\2')", $template );
		//���ֲü�
		$template = preg_replace ( "/\{cutstr\((.+?),(.+?)\)\}/ie", "Keke_tpl::cutstr('\\1','\\2')", $template );
		//PHP����
		$template = preg_replace ( "/\<\!\-\-\{eval\s+(.+?)\s*\}\-\-\>/ies", "Keke_tpl::evaltags('\\1')", $template );
		//��ʼ����
		//����
		$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
		$template = preg_replace ( "/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template );
		$template = preg_replace ( "/([\n\r]+)\t+/s", "\\1", $template );
		$template = preg_replace ( "/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $template );
		$template = preg_replace ( "/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template );
		$template = preg_replace ( "/$var_regexp/es", "Keke_tpl::addquote('<?=\\1?>')", $template );
		$template = preg_replace ( "/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "Keke_tpl::addquote('<?php echo \\1;?>')", $template );
		//�߼�
		$template = preg_replace ( "/\{elseif\s+(.+?)\}/ies", "Keke_tpl::stripvtags('<?php } elseif(\\1) { ?>','')", $template );
		$template = preg_replace ( "/\{else\}/is", "<?php } else { ?>", $template );
		//ѭ��
		for($i = 0; $i < 6; $i ++) {
			$template = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/ies", "Keke_tpl::stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<?php } } ?>')", $template );
			$template = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/ies", "Keke_tpl::stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<?php } } ?>')", $template );
			$template = preg_replace ( "/\{if\s+(.+?)\}(.+?)\{\/if\}/ies", "Keke_tpl::stripvtags('<?php if(\\1) { ?>','\\2<?php } ?>')", $template );
		}
		//����
		$template = preg_replace ( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $template );
		//����
		$template = preg_replace ( "/ \?\>[\n\r]*\<\? /s", " ", $template );
		
		//���Ӵ���
		$template = "<?php global \$_K,\$_lang; Keke_tpl::checkrefresh('$tpl', '{$_K['timestamp']}' );?>$template<?php Keke_tpl::ob_out();?>";
		
		//�滻
		empty ( $_K ['block_search'] ) or $template = str_replace ( $_K ['block_search'], $_K ['block_replace'], $template );
		$template = str_replace("<?=", "<?php echo ", $template);
		return $template;
	}
	
	static function addquote($var) {
		$var =  str_replace ( "\\\"", "\"", preg_replace ( '/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s', "['\\1']", $var ) );
		return $var;
	}
	/**
	 * ת��ҳ��������ַ���,��ֹsqlע��
	 * @param string,array $value
	 * @param bool $double_encode
	 * @return string ��ȫ���ַ�
	 */
	public static function chars($value, $double_encode = FALSE)
	{
		if(CHARSET==='gbk'){
			$charset = 'iso-8859-1';
		}else{
			$charset = CHARSET;
		}
		if(is_array($value) or is_object($value)){
			foreach ($value as $k=>$v){
			   $value[$k]=Keke_tpl::chars($v,$double_encode);
			}
		}else{
			$value = htmlspecialchars( (string) $value, ENT_QUOTES, $charset, $double_encode);
		}
		return $value;
	}
	static function striptagquotes($expr) {
		$expr = preg_replace ( '/\<\?\=(\\\$.+?)\?\>/s', "\\1", $expr );
		$expr = str_replace ( "\\\"", "\"", preg_replace ( '/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s', "[\\1]", $expr ) );
		return $expr;
	}
	
	static function evaltags($php) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--EVAL_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php " . Keke_tpl::stripvtags ( $php ) . " ?>";
		return $search;
	}
	
	static function datetags($parameter, $value) {
		global $_K;
		$_K ['i'] ++;
		$search = "<!--DATE_TAG_{$_K['i']}-->";
		$_K ['block_search'] [$_K ['i']] = $search;
		$_K ['block_replace'] [$_K ['i']] = "<?php if({$value}){echo date({$parameter},{$value}); } ?>";
		return $search;
	}
	
	//������
	static function showad($adid) {
		global $_K;
		$content = '<!--{eval Keke_loaddata::ad(' . $adid . ')}-->';
		return $content;
	
	}
	/**
	 * ��ʾָ��λ�õĹ��
	 * @param $target ���λ�ô���
	 * @param $do	     ��ǰ·��DO
	 */
	static function ad_show($target, $is_slide = null) {
		global $_K, $do;
		$content = '<!--{eval Keke_loaddata::ad_show(\'' . $target . '\',\'' . $do . '\',\'' . $is_slide . '\')}-->';
		return $content;
	}
	static function runwidget($widgetname) {
		global $_K;
		$content = '<!--{eval $widgetname = \'' . $widgetname . '\'; include(S_ROOT.\'widget/run.php\')}-->';
		return $content;
	}
	
	//���Ⱥ����
	static function showads($adname) {
		$content = '<!--{eval Keke_loaddata::adgroup(' . $adname . ')}-->';
		return $content;
	}
	
	/**
	 * ͷ�����
	 */
	static function avatar($uid, $size) {
		return '<!--{eval echo "<img class=pic_'.$size.' src=".Keke_user::instance()->get_avatar('.$uid.','.$size.')." >"}-->';
	}
	/**
	 * ���ֲü�
	 * @param string $string
	 * @param int $length
	 * @return string
	 */
	static function cutstr($string,$length){
		return '<!--{eval echo Keke::cutstr('.$string.','.$length.')}-->';
	}
	static function stripvtags($expr, $statement = '') {
		$res = preg_replace ( "/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr );
		$expr = str_replace ( "\\\"", "\"", $res );
		$statement = str_replace ( "\\\"", "\"", $statement );
		return $expr . $statement;
	}
	
	static function readtemplate($name) {
		global $_K;
		
		$tpl = Keke_tpl::tpl_exists ( $name );
		$tplfile = S_ROOT . './' . $tpl . '.htm';
		
		$sub_tpls [] = $tpl;
		
		if (! file_exists ( $tplfile )) {
			$tplfile = str_replace ( '/' . $_K ['template'] . '/', '/default/', $tplfile );
		}
		$content = trim ( Keke_tpl::sreadfile ( $tplfile ) );
		return $content;
	}
	
	static function readtag($name) {
		global $kekezu; 	
		$content = '<!--{eval Keke_loaddata::readtag(' . $name . ')}-->';
		return $content;
	
	}
	
	/* static function loadfeed($name) {
		$content = '<!--{eval Keke_loaddata::readfeed(' . $name . ')}-->';
		return $content;
	
	} */
	
	//��ȡ�ļ�����
	static function sreadfile($filename) {
		$content = '';
		if (function_exists ( 'file_get_contents' )) {
			@$content = file_get_contents ( $filename );
		} else {
			if (@$fp = fopen ( $filename, 'r' )) {
				@$content = fread ( $fp, filesize ( $filename ) );
				@fclose ( $fp );
			}
		}
		return $content;
	}
	
	//д���ļ�
	static function swritefile($filename, $writetext, $openmod = 'w') {
		if (@$fp = fopen ( $filename, $openmod )) {
			flock ( $fp, 2 );
			fwrite ( $fp, $writetext );
			fclose ( $fp );
			return true;
		} else {
			return false;
		}
	}
	//�ж��ַ���$haystack���Ƿ�����ַ�$needle ���ص�һ�γ��ֵ�λ��   �����Ⱥ� �жϾ������  uican 2009-12-03
	static function strexists($haystack, $needle) {
		return ! (strpos ( $haystack, $needle ) === FALSE);
	}
	
	static function tpl_exists($tplname) {
		global $_K;
		is_file ( S_ROOT . "tpl/" . $_K ['template'] . "/" . $tplname . ".htm" ) and $tpl = "tpl/{$_K['template']}/$tplname" or $tpl = $tplname;
		return $tpl;
	}
	
	static function template($name) {
		global $_K;
		
		$tpl = Keke_tpl::tpl_exists ( $name );
		$objfile = S_ROOT . 'data/tpl_c/' . str_replace ( '/', '_', $tpl ) . '.php';
		if(! file_exists ( $objfile ) or ! TPL_CACHE){
			Keke_tpl::parse_template ( $tpl );
		}

		return $objfile;
	}
	
	
	/**
	 * //��ģ����¼�� 
	 *
	 * @param string $subfiles ģ��·��
	 * @param int $mktime ʱ��  
	 * @param string $tpl  ��ǰҳ��ģ��
	 */
	static function checkrefresh($tpl, $mktime) {
		global $_K;
		if ($tpl) {
			$tplfile = S_ROOT . './' . $tpl . '.htm';
			(! file_exists ( $tplfile )) and $tplfile = str_replace ( '/' . $_K ['template'] . '/', '/default/', $tplfile );
			$submktime = filemtime ( $tplfile );
			($submktime > $mktime) and Keke_tpl::parse_template ( $tpl );
		}
	}
	
	//�������
	static function ob_out() {
		global $_K,$_lang;
		
		$content = ob_get_contents ();
		
		$preg_searchs = $preg_replaces = $str_searchs = $str_replaces = array();
		//if ($_K ['is_rewrite'] == 1) {
			
			/* $preg_searchs [] = '/\<a\s*href\=\"index\.php\/(.+?)\#(\w+)\"/ie';
			$preg_replaces [] = 'Keke_tpl::rewrite_url(\'index-\',\'\\1\',\'\\2\')';
			
			$preg_searchs [] = '/\<a\s*href\=\"index\.php\"/i';
			$preg_replaces [] = '<a href="index.html"';
			
			$preg_searchs [] = '/\<a\s*href\=\"http\:\/\/(.*)\/index\.php\?(.+?)\#(\w+)\"/ie';
			$preg_replaces [] = 'Keke_tpl::rewrite_url(\'http://\\1/index-\',\'\\2\',\'\\3\')';
			
			$preg_searchs [] = '/\<a\s*href\=\"index\.php\?(.+?)\"/ie';
			$preg_replaces [] = 'Keke_tpl::rewrite_url(\'index-\',\'\\1\')';  */
			
			//$content = strtr($content, array('/index.php'=>''));
			
 			//$content = strtr($content, array('/index.php'=>''));
		//}
		//$content = strtr($content, array('/index.php'=>''));
		
		
		if ($_K ['inajax']) {
			$preg_searchs [] = '/([\x01-\x09\x0b-\x0c\x0e-\x1f])+/';
			$preg_replaces [] = ' ';
			
			$str_searchs [] = ']]>';
			$str_replaces [] = ']]&gt;';
		}
		
		if ($preg_searchs) {
			$content = preg_replace ( $preg_searchs, $preg_replaces, $content );
		}
		if ($str_searchs) {
			$content = trim ( str_replace ( $str_searchs, $str_replaces, $content ) );
		}
		 
		Keke_tpl::obclean ();
		($_K ['inajax']) and self::xml_out ( $content );
		
		//echo $content;

	}
	static function obclean() {
		global $_K;
		 if($_K['inajax']==1){
		 	ob_end_clean();
		 	ob_start();
		 }
		 
	}
	static function rewrite_url($pre, $para, $hot = '') {
		$str = '';
		parse_str ( $para, $joint );
	 
		$s = array_filter ( $joint );
		$url = http_build_query ( $s );
		
		$url = str_replace ( array ("do=", '&', '=' ), array ("", '-', '-' ), $url );
		 
		$hot = $hot ? "#" . $hot : '';
		return '<a href="'.$url . '.html' . $hot . '"';
	}
	static function xml_out($content) {
		
		header ( "Expires: -1" );
		header ( "Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/xml; charset=".CHARSET );
		echo '<' . "?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n";
		echo "<root><![CDATA[" . trim ( $content ) . "]]></root>";
		exit ();
	}

}