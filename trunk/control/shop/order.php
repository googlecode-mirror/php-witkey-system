<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ��̨�����б�������
 * @author Michael
 * @version 2.2
   2012-10-21
 */
keke_lang_class::loadlang ('list','shop');
abstract class Control_shop_order extends Control_admin{
    
	/**
	 *
	 * @var ����id
	 */
	protected  $_sid ;
	protected  $_base_uri;
	
	function before(){
		$this->_sid = intval($_GET['sid']);
		$this->_base_uri  = BASE_URL."/index.php/shop/".$this->_model_code."_admin_order";
	}
	
}