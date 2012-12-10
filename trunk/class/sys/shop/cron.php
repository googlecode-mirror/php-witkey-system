<?php defined('IN_KEKE') or die('access deiend');
/**
 * ����ƻ�ִ����
 * @author Michael
 * @version 3.0 2012-12-07
 */
abstract  class Sys_shop_cron {
    
	/**
	 * @var Ĭ��Ϊ����
	 */
	private static $_default = 'goods';
	/**
	 * @var ʵ��
	 */
	public static $instance = array();
	/**
	 * 
	 * @param string $name
	 * @return Sys_task_cron
	 */
	public static function factory($name = NULL){
		if($name===NULL){
			$name = self::$_default;
		}
		if(self::$instance[$name]){
			return self::$instance[$name];
		}
		
		$class = "Control_shop_{$name}_cron";
		return self::$instance[$name]  = new $class;
	}
	/**
	 * ���嵽ѡ��
	 */
	abstract public function jg_to_xg();
	/**
	 * ѡ�㵽��ʾ
	 */
	abstract public function xg_to_gs();
    /**
     * ��ʾ������
     */	
	abstract public function gs_to_jf();
	/**
	 * ������λ����
	 */
	abstract public function jf_to_hp();
	/**
	 * ����������
	 */
	abstract public function hp_to_end();
	/**
	 * ִ��
	 */
	abstract  public function run();
	/**
	 * ����ִ��
	 */
	public static function batch_run(){
		$where = "model_type='shop' and model_status = 1";
		$models = DB::select('model_code')->from('witkey_model')->where($where)->execute();
		foreach ($models as $v){
			Sys_shop_cron::factory($v['model_code'])->run();
		}
	}
}//end