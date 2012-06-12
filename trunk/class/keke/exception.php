<?php

class keke_exception extends Exception {
	public static $php_errors = array (E_ERROR => 'Fatal Error', E_USER_ERROR => 'User Error', E_PARSE => 'Parse Error', E_WARNING => 'Warning', E_USER_WARNING => 'User Warning', E_STRICT => 'Strict', E_NOTICE => 'Notice', E_RECOVERABLE_ERROR => 'Recoverable Error' );
	public static $error_view = '';
	public function __construct($message, array $variables = NULL, $code = 0) {
		if (defined ( 'E_DEPRECATED' )) {
			keke_exception::$php_errors [E_DEPRECATED] = 'Deprecated';
		}
		$this->code = $code;
		$message =empty($variables)? $message:strtr ( $message, $variables );
		parent::__construct ( $message, ( int ) $code );
	}
	public function __toString() {
		return keke_exception::text ( $this );
	}
	public static function handler(Exception $e) {
		try {
			$type = get_class ( $e );
			$code = $e->getCode ();
			$message = $e->getMessage ();
			$file = $e->getFile ();
			$line = $e->getLine ();
			$trace = $e->getTrace ();
			if ($e instanceof ErrorException) {
				if (isset ( keke_exception::$php_errors [$code] )) {
					$code = keke_exception::$php_errors [$code];
				}
				if (version_compare ( PHP_VERSION, '5.3', '<' )) {
					for($i = count ( $trace ) - 1; $i > 0; -- $i) {
						if (isset ( $trace [$i - 1] ['args'] )) {
							$trace [$i] ['args'] = $trace [$i - 1] ['args'];
							unset ( $trace [$i - 1] ['args'] );
						}
					}
				}
			}
			$error = keke_exception::text ( $e );
			if(is_object(kekezu::$_log)){
				kekezu::$_log->add(log::ERROR, $error);
				$strace = keke_exception::text($e)."\n--\n" . $e->getTraceAsString();
				kekezu::$_log->add(log::STRACE, $strace);
                //生成日志文件
				kekezu::$_log->write();
			}
			$data ['type'] = $type;
			$data ['code'] = $code;
			$data ['message'] = $message;
			$data ['file'] = $file;
			$data ['line'] = $line;
			// $data['trace'] = $trace;
			$vars = array ('_SESSION', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER' );
			
			$data ['trace'] = array_reverse ( keke_debug::trace ( $trace ) );

			require S_ROOT . './control/show_error.php';
			die ();
		} catch ( Exception $e ) {
			ob_get_level () and ob_clean ();
			echo keke_exception::text ( $e ), "\n";
			exit ( 1 );
		}
	}
	public static function text(Exception $e) {
		return sprintf ( '%s [ %s ]: %s ~ %s [ %d ]', get_class ( $e ), $e->getCode (), strip_tags ( $e->getMessage () ), keke_debug::path ( $e->getFile () ), $e->getLine () );
	}
}

?>