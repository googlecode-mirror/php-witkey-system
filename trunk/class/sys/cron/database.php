<?php defined('IN_KEKE') OR die('access deiend');
/**
 * ���ݿ��Ż�����
 * @author Michael
 *
 */
Class Sys_cron_database {
	
	
	public static function batch_run(){

		$table_arr = DB::query ( "SHOW TABLE STATUS FROM `" . DBNAME . "` LIKE '" . TABLEPRE . "%'" )->execute();
		foreach ($table_arr as $v){
			//Name
			DB::query( "OPTIMIZE TABLE " . $v['Name'],Database::UPDATE)->execute();
		}
		
	}
	
}