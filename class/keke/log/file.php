<?php

class keke_log_file extends keke_log_write {
	
	protected $_directory;
	/**
	 * 设置日志的存放目录
	 * @param string $directory
	 * @throws keke_exception
	 */
	public function __construct($directory = 'data/log') {
		if (! is_dir ( $directory ) or ! is_writable ( $directory )) {
			throw new keke_exception ( 'Directory ?dir must be writable', array ('?dir' => keke_debug::path ( $directory ) ) );
		}
 		$this->_directory = realpath ( $directory ) . DIRECTORY_SEPARATOR;
	}
	/**
	 * 文件模式来记录日志
	 * @see keke_log_write::write()
	 */
	public function write(array $messages) {
		$directory = $this->_directory . date ( 'Y' );
		$directory .= DIRECTORY_SEPARATOR . date ( 'm' );
		if (! is_dir ( $directory )) {
			mkdir ( $directory, 02777,true );
		}
		$filename = $directory . DIRECTORY_SEPARATOR . date ( 'd' ) . EXT;
		if (! file_exists ( $filename )) {
			file_put_contents ( $filename,   '<?php defined(\'IN_KEKE\') or die(\'No direct script access.\'); ?>' . PHP_EOL );
			chmod ( $filename, 0666 );
		}
		foreach ( $messages as $message ) {
			// Write each message into the log file
			// Format: time --- level: body
			file_put_contents ( $filename, PHP_EOL . $message ['time'] . ' --- ' . $this->_log_levels [$message ['level']] . ': ' . $message ['body'], FILE_APPEND );
		}
	}
}

