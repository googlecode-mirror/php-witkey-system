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
	 *        	表名 ,不需要加表前缀
	 *        	,表名为keke_witkey_link 可以写为wiktye_link
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
	 * 字段设值
	 * 
	 * @param $array 字段健值对数组        	
	 * @return Model
	 */
	abstract public function setData($array);
	abstract public function create();
	abstract public function update();
	/**
	 *
	 * @param string $fields
	 *        	查询字段，默认值为*
	 * @param int $cache_time
	 *        	null 表示默认缓存,0 表示不缓存，1，表示缓存1秒钟
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
 * 获取网格数据
 * @param String $fields  字段
 * @param String  Array $wh   支持字串与数组
 * @param String $uri  跳转url
 * @param String $order  排序字串
 * @param int $page   当前页数
 * @param int $count 总页数，防止分时再次查询
 * @param Int $page_size  当前页的条数
 * @param string $ajax_dom  ajax div 标签 id 
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
		//数组条件
		if (is_array ( $wh )) {
			$where = " 1 = 1";
			$wh ['w'] = array_filter ( $wh ['w'] );
			foreach ( $wh ['w'] as $k => $v ) {
				$where .= " and $k = '$v'";
			}
		} else {
			//字符条件
			$where = $wh;
		}
		//统计表的总记录数,如果count有值不用再次查询，确保分页只有一次查询
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
