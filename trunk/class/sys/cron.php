<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �ƻ�����Ĵ���
 * 
 * @author Michael
 * @version 2.2 2012-11-19
 *        
 */
class Sys_cron {
	/**
	 * @var �Զ�ִ�мƻ���ʱ�� 1Сʱ
	 */
	protected static $_crontime = 3600;
	/**
	 * ִ�мƻ�
	 */
	static public function run() {
		global $_K;
		var_dump(self::$_crontime+SYS_START_TIME,$_K['cronnextrun']);
		//�ж�ʱ��
		if(self::$_crontime+SYS_START_TIME>$_K['cronnextrun']){
			return TRUE;
		}
		
		set_time_limit ( 1000 );
		ignore_user_abort ( TRUE );
		
		
		
		register_shutdown_function ( array (
				'Sys_cron',
				'update_cron_time' 
		) );
	}
	/**
	 * �����´μƻ�ִ�е�ʱ��
	 */
	static function update_cron_time() {
		die('����ִ��');
		$t = self::$_crontime + time();
		$where = "k='cronnextrun'";
		Cache::instance()->del('keke_config');
        return (bool)DB::update('witkey_config')->set(array('v'))->value(array($t))->where($where)->execute(); 		
	}
	
	
}
