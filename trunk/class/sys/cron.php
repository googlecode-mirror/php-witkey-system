<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * �ƻ�����Ĵ���
 * 
 * @author Michael
 * @version 3.0 2012-11-19
 *        
 */
abstract class Sys_cron {
	/**
	 * @var �Զ�ִ�мƻ���ʱ�� 1Сʱ
	 */
	protected static $_crontime = 3600;
	
	private static $_name = 'Sys_task_cron';
 
	
	/**
	 * ִ�мƻ�
	 */
	static public function run() {
		 $where = "nextruntime <= ".SYS_START_TIME ." and allow =1";
		 
		 $cron = DB::select()->from('witkey_cron')->where($where)
		 ->limit(0, 1)->get_one()->execute();
		 
		//Keke::$_log->add(Log::DEBUG, $cron['cron_name'])->write();
		
		//�жϼƻ��Ƿ���ֵ
		if(Keke_valid::not_empty($cron)===FALSE){
			return TRUE;
		}
		
		
		
		ignore_user_abort ( TRUE );
		set_time_limit ( 1000 );
		
		Keke::$_log->add(Log::INFO, $cron['cron_name'])->write();
		
		//ִ�мƻ�����,�ļ���Ϊ����ִ��
		if(!$cron['filename']){
			return TRUE;
		}
			 /* $class = new $cron['filename'];
			 $class ->batch_run(); */
		call_user_func($cron['filename'] .'::batch_run');
		
		self::set_next_time($cron);
	}
	
	/**
	 * �����´μƻ�ִ�е�ʱ��
	 */
	static function set_next_time($cron) {
		DB::update('witkey_cron')->set(array('nextruntime'))
		->value(array(SYS_START_TIME+(int)$cron['span']))
		->where("cron_id = {$cron['cron_id']}")->execute(); 
	}
	/**
	 * ����ִ��
	 */
	abstract public function batch_run();
	
	/**
	 * ��������
	 */
	static function is_lock(){
		
	}
	/**
	 * ���̽���
	 */
	static function un_lock(){
		
	}
	
}
