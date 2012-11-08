<?php  defined ( "IN_KEKE" ) or  die ( "Access Denied" );
/**
 * 用户基类
 * @author michael
 * @version 2.2 
 * 2012-11-6
 *
 */
abstract class Keke_user {
   
	/**
	 *
	 * @var 登录类型
	 */
	public static $_type = array (
			1 => 'keke',
			2 => 'uc',
			3 => 'pw'
	);
	

}
