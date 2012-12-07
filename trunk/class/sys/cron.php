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
		 $where = SYS_START_TIME .">= nextruntime and allow =1";
		//$runtime = Cache::instance()->get('keke_cron_runtime');
		 $cron = DB::select()->from('witkey_cron')->where($where)
		 ->limit(0, 1)->get_one()->cached()->execute();
		//判断执行时间大于当前时间则不执行
		if($runtime>SYS_START_TIME){
			return TRUE;
		}
		
		set_time_limit ( 1000 );
		ignore_user_abort ( TRUE );
		//执行计划任务
		
		
		
		
	}
	/**
	 * 更新下次计划执行的时间
	 */
	static function set_next_time() {
		$t = self::$_crontime + time();
		Cache::instance()->set('keke_cron_runtime',$t);
	}
	
	
	/**
	 * 进程锁定
	 */
	static function is_lock(){
		
	}
	/**
	 * 进程解锁
	 */
	static function un_lock(){
		
	}
	
}
