<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * ��Ʒҵ������
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_shop_goods_base extends Control_shop_base{
    
	/**
	 * ��Ʒ״̬
	 * �ϼܺ��¼�״̬�ǶԷ����߶��ԣ����ú���������Թ���Ա����
	 */
	public static function get_goods_status() {
		global $_lang;
		return array ("2" => $_lang ['on_shelf'], "3" => $_lang ['down_shelf']);
	}
	
	/**
	 * ������Ʒ����״̬
	 * Enter description here ...
	 */
	public static function get_order_status() {
		global $_lang;
		return array ('wait' => $_lang ['wait_buyer_pay'], 'ok' => $_lang ['buyer_haved_pay'], 'send' => $_lang ['seller_haved_service'], 'confirm' => $_lang ['trade_complete'], 'close' => $_lang ['trade_close'], 'arbitral' => $_lang ['order_arbitrate'], 'complete' => $_lang ['trade_complete'] );
	}
 
}