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
		 
		$runtime = Cache::instance()->get('keke_cron_runtime');
		 
		//�ж�ִ��ʱ����ڵ�ǰʱ����ִ��
		if($runtime>SYS_START_TIME){
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
		$t = self::$_crontime + time();
		Cache::instance()->set('keke_cron_runtime',$t);
	}
	
	
}
