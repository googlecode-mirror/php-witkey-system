<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 计划任务的处理
 * 
 * @author Michael
 * @version 2.2 2012-11-19
 *        
 */
class Sys_cron {
	/**
	 * @var 自动执行计划的时间 1小时
	 */
	protected static $_crontime = 3600;
	/**
	 * 执行计划
	 */
	static public function run() {
		 
		$runtime = Cache::instance()->get('keke_cron_runtime');
		 
		//判断执行时间大于当前时间则不执行
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
	 * 更新下次计划执行的时间
	 */
	static function update_cron_time() {
		$t = self::$_crontime + time();
		Cache::instance()->set('keke_cron_runtime',$t);
	}
	
	
}
