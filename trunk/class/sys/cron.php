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
		global $_K;
		var_dump(self::$_crontime+SYS_START_TIME,$_K['cronnextrun']);
		//判断时间
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
	 * 更新下次计划执行的时间
	 */
	static function update_cron_time() {
		die('不会执行');
		$t = self::$_crontime + time();
		$where = "k='cronnextrun'";
		Cache::instance()->del('keke_config');
        return (bool)DB::update('witkey_config')->set(array('v'))->value(array($t))->where($where)->execute(); 		
	}
	
	
}
