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
		 $where = SYS_START_TIME .">= nextruntime and allow =1";
		//$runtime = Cache::instance()->get('keke_cron_runtime');
		 $cron = DB::select()->from('witkey_cron')->where($where)
		 ->limit(0, 1)->get_one()->cached()->execute();
		//�ж�ִ��ʱ����ڵ�ǰʱ����ִ��
		if($runtime>SYS_START_TIME){
			return TRUE;
		}
		
		set_time_limit ( 1000 );
		ignore_user_abort ( TRUE );
		//ִ�мƻ�����
		
		
		
		
	}
	/**
	 * �����´μƻ�ִ�е�ʱ��
	 */
	static function set_next_time() {
		$t = self::$_crontime + time();
		Cache::instance()->set('keke_cron_runtime',$t);
	}
	
	
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
