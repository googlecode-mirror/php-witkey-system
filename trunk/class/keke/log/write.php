<?php
/**
 * �����࣬����д��־�ķ���
 * log_fie,log_sys,�̳д���
 * @author Administrator
 *
 */
abstract class Keke_log_write {
	/**
	 * Numeric log level to string lookup table.
	 * 
	 * @var array
	 */
	protected $_log_levels = array (
			LOG_EMERG => 'EMERGENCY',
			LOG_ALERT => 'ALERT',
			LOG_CRIT => 'CRITICAL',
			LOG_ERR => 'ERROR',
			LOG_WARNING => 'WARNING',
			LOG_NOTICE => 'NOTICE',
			LOG_INFO => 'INFO',
			LOG_DEBUG => 'DEBUG',
			8 => 'STRACE' 
	);
	
	/**
	 * 
	 * д��־
	 * $writer->write($messages);
	 *
	 * @param
	 *        	array messages
	 * @return void
	 */
	abstract public function write(array $messages);
	
	/**
	 * ���������Ψһ��ʶ��
	 *
	 * echo $writer;
	 *
	 * @return string
	 */
	final public function __toString() {
		return spl_object_hash ( $this );
	}

}
