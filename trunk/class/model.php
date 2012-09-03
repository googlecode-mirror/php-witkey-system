<?php

defined ( "IN_KEKE" ) or die ( "Access Denied" );
abstract class Model {
	public $_db;
	public $_tablename;
	public $_pk;
	public $_lifetime;
	public $_replace = 0;
	public static $_where = NULL;
	public static $_instance = null;
	
	public function __construct($table_name = null) {
		$this->_db = Database::instance ();
		$this->_tablename = '`' . DBNAME . '`.`' . TABLEPRE . $table_name . '`';
	}
	/**
	 *
	 * @param string $table_name
	 *        	���� ,����Ҫ�ӱ�ǰ׺
	 *        	,����Ϊkeke_witkey_link ����дΪwiktye_link
	 * @return Model
	 */
	public static function factory($table_name) {
		if (self::$_instance [$table_name] == null) {
			$class = TABLEPRE . $table_name;
			self::$_instance [$table_name] = new $class ();
		}
		
		return self::$_instance [$table_name];
	}
	/**
	 *
	 * @return Model
	 */
	abstract public function setWhere($where);
	
	/**
	 * �ֶ���ֵ
	 * 
	 * @param $array �ֶν�ֵ������        	
	 * @return Model
	 */
	abstract public function setData($array);
	abstract public function create();
	abstract public function update();
	/**
	 *
	 * @param string $fields
	 *        	��ѯ�ֶΣ�Ĭ��ֵΪ*
	 * @param int $cache_time
	 *        	null ��ʾĬ�ϻ���,0 ��ʾ�����棬1����ʾ����1����
	 * @param
	 *        	array
	 */
	abstract public function query($fields, $cache_time);
	abstract public function del();
	abstract public function count();
	function reset() {
		self::$_where = NULL;
	}
/**
 * ��ȡ��������
 * @param String $fields  �ֶ�
 * @param String  Array $wh   ֧���ִ�������
 * @param String $uri  ��תurl
 * @param String $order  �����ִ�
 * @param int $page   ��ǰҳ��
 * @param int $count ��ҳ������ֹ��ʱ�ٴβ�ѯ
 * @param Int $page_size  ��ǰҳ������
 * @param string $ajax_dom  ajax div ��ǩ id 
 * @return multitype:<array(data,pages), string>
 */
	function get_grid($fields ,$wh = '1=1', $uri=NULL, $order = null,$page=1, $count=NULL,$page_size = 10, $ajax_dom = null) {
		
		$page or $page = 1;
		$page_size or $page_size = 10;
		$page_obj = new keke_page_class();
		if ($ajax_dom) {
			$page_obj->setAjax ( '1' );
			$page_obj->setAjaxDom ( $ajax_dom );
		}
		//��������
		if (is_array ( $wh )) {
			$where = " 1 = 1";
			$wh ['w'] = array_filter ( $wh ['w'] );
			foreach ( $wh ['w'] as $k => $v ) {
				$where .= " and $k = '$v'";
			}
		} else {
			//�ַ�����
			$where = $wh;
		}
		//ͳ�Ʊ���ܼ�¼��,���count��ֵ�����ٴβ�ѯ��ȷ����ҳֻ��һ�β�ѯ
		if(!$count){
			$this->setWhere($where);
			$count = $this->count();
		}
		if(!$uri){
			$uri = BASE_URL.'/'.Request::current()->url();
		}
		$pages = $page_obj->getPages ( $count, $page_size, $page, $uri );
		$where .= $order .= $pages ['where'];
		$this->setWhere ( $where );
		$res_info = array();
		$res_info ['data'] = $this->query($fields);
		$res_info ['pages'] = $pages;
		
		return $res_info;
		
	}
}
