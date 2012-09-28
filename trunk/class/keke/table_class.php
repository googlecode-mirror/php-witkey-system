<?php
/**
 * 
 * ��������ɾ���飬��.
 * ע���÷������������ļ���Ϊ���ݿ��Ӧ�ֶ���
 * @author Administrator
 *
 */
class keke_table_class {
	public $_table_name;
	public $_table_obj;
	public $_page_obj;
	public $_count;
	public $_pre = 'keke_';
	public static function get_instance($table_name) {
		return new keke_table_class ( $table_name );
	}
	
	function __construct($table_name) {
		global $kekezu;
		$this->_page_obj = Keke::$_page_obj;
		$this->_table_name = $table_name;
		$table_class = ucfirst($this->_pre).$table_name ;
		$this->_table_obj = new $table_class (); 
	}
	
	/**
	 * 
	 * ��ѯ
	 * @param array $where_arr    -------��ά��������
	 * $where_arr ����ı�׼ $where_arr[w][f]=fΪ����ֶ� yΪ���ֶε�ֵ 
	 * $where[ord][f]=desc/asc  xΪ������ֶ�
	 * @param string $url_str     -------url��ַ
	 * @param int $page
	 * @param int $ajax �Ƿ�ʹ��AJAX��ҳ
	 * @param string $ajax_dom �첽����DOM��ID
	 * @return array(data,pages);
	 */
	
	function get_grid($wh = '1=1', $url_str, $page, $page_size = 10, $order = null,$ajax=0,$ajax_dom=null) {
		$page_obj = $this->_page_obj;
		if($ajax){
			$page_obj->setAjax('1');
			$page_obj->setAjaxDom($ajax_dom);
		}
		if (is_array ( $wh )) {
			$where = " 1 = 1";
			$wh [w] = array_filter ( $wh [w] );
			foreach ( $wh [w] as $k => $v ) {
				$where .= " and $k = '$v'";
			}
			
			foreach ( $wh [ord] as $k => $v ) {
				$where .= " order by $k $v";
			}
		
		} else {
			$where = $wh;
		}
		$this->_table_obj->setWhere ( $where );
		$count_query = "count_" . $this->_pre . $this->_table_name;
		$this->_count = $count = $this->_table_obj->$count_query ();
		
		$pages = $page_obj->getPages ( $count, $page_size, $page, $url_str );
		$where .= $order .= $pages [where];
		$this->_table_obj->setWhere ( $where );
		$query = "query_" . $this->_pre . $this->_table_name;
	
		$res_info [data] = $this->_table_obj->$query ();
		
		$res_info [pages] = $pages;
		
		if ($res_info) {
			return $res_info;
		} else {
			return false;
		}
	
	}
	
	/**
	 * �༭/���
	 * @param array $fileds   ----------Ҫ�༭�ֶ����� $fds['�ֶ���']
	 * @param array $pk  --------�༭����array("id"=>$id)
	 */
	function save($fields, $pk = array()) {
		foreach ( $fields as $k => $v ) {
			$kk = ucfirst ( $k );
			$set_query = "set" . $kk;
			$this->_table_obj->$set_query ( $v );
		}
		$keys = array_keys ( $pk );
		$key = $keys [0];
		
		//�༭
		if (! empty ( $pk [$key] )) {
			$this->_table_obj->setWhere ( " $key = '" . $pk [$key] . "'" );
			$edit_query = "edit_" . $this->_pre . $this->_table_name;
			$res = $this->_table_obj->$edit_query ();
		} else {
			$create_query = "create_" . $this->_pre . $this->_table_name;
			$res = $this->_table_obj->$create_query ();
		}
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * ����ɾ��
	 * @param string $pk ------�������
	 * @param array $val ------����ɾ���������Ϊ����id������,�����ַ���
	 * @return int Ӱ�������
	 */
	function del($pk, $val, $url = null) {
		
		if (! $val) {
			return false;
		}
		if (is_array ( $val ) && ! empty ( $val )) {
			$ids = implode ( ',', $val );
			$this->_table_obj->setWhere ( " $pk in ($ids)" );
		} elseif ($val) {
			$this->_table_obj->setWhere ( "$pk = " . $val );
		}
		$del_query = "del_" . $this->_pre . $this->_table_name;
		return $this->_table_obj->$del_query ();
	}
	
	/**
	 * 
	 * ��ȡ�༭ҳ�����ϸ��Ϣ
	 * @param string $index_key    -----������
	 * @param string $index_val		------����ֵ
	 */
	function get_table_info($index_key, $index_val) {
		$this->_table_obj->setWhere ( " $index_key = '$index_val'" );
		$query = "query_" . $this->_pre . $this->_table_name;
		$table_info = $this->_table_obj->$query ();
		$table_info = $table_info [0];
		if ($table_info) {
			return $table_info;
		} else {
			return false;
		}
	}
	
	
	
	/**
	 * 
	 * �����������ݼ� �����Դ���in()��д��
	 * @param string $fname   --- �ֶ��� 
	 * @param sting $str	----���� /����(���Ϊ���֣��������1-x�����ݼ�,���Ϊ�������������ݼ�)
	 */
	public static function generate_row($fname, $str) {
		$a = "";
		if (is_numeric ( $str )) {
			$a .= "select 1 as $fname";
			for($i = 2; $i <= $str; $i ++) {
				$a .= " union all select $i";
			}
		} elseif (is_array ( $str )) {
			foreach ( $str as $k => $v ) {
				
				if ($k == 0) {
					$a .= " select $v as $fname";
				} else {
					$a .= " union all select $v ";
				}
			}
		}
		return $a;
	}
	 
	
	/**
	 * 
	 * update������� ֧���ֶ�����    
	 * @param string $table_name ----����
	 * @param array $fiedsarr  ----�ֶ�����
	 * @param array $wherearr  ------ ��������
	 */
	public static function updateself($table_name, $fiedsarr, $wherearr) {
		
		$size = sizeof ( $fiedsarr );
		$keys = array_keys ( $fiedsarr );
		for($i = 0; $i < $size; $i ++) {
			//�ж��Ƿ������һ���ֶ�,���һ���ֶβ��Ӷ���
			stristr ( $fiedsarr [$keys [$i]], '`' ) != false and $value = $fiedsarr [$keys [$i]] or $value = "'{$fiedsarr[$keys[$i]]}'";
			$i == $size - 1 and $set_value .= "`$keys[$i]` = $value " or $set_value .= " `$keys[$i]` = $value,";
		}
		$size = sizeof ( $wherearr );
		$keys = array_keys ( $wherearr );
		$where = " 1=1 ";
		for($i = 0; $i < $size; $i ++) {
			$where .= " and  `$keys[$i]` = '{$wherearr[$keys[$i]]}'";
		}
	 
		return dbfactory::execute ( " update ".TABLEPRE. $table_name . " set $set_value where $where" );
	}
	/**
	 * ��ѯ�������  
	 * @param unknown_type $where
	 * @param unknown_type $order
	 * @param unknown_type $w
	 * @param unknown_type $p
	 * @return multitype:Ambigous <string, unknown> unknown
	 */
	public static function format_condit_data($where,$order,$w=array(),$p = array()){
		global $kekezu;
		$arr = array();
		if (! empty ( $w )) {
			$w = array_filter ( $w );
			foreach ( $w as $k => $v ) {
				$where .= " and $k = '$v' ";
			}
		}
		$order and $where.=" order by $order ";
		if (! empty ( $p )) {
			$page_obj = Keke::$_page_obj;
			$count = intval ( dbfactory::execute ($where ));
			$pages = $page_obj->getPages ( $count, $p ['page_size'], $p ['page'], $p ['url'], $p ['anchor'] );
			$where .= $pages ['where'];
		}
		$arr['where']  = $where;
		$arr['pages']  = $pages;
		return $arr;
	}
	
	
	
	/**
	 * 
	 * ����������ȡ�����ϸ��Ϣ
	 * @param string $table_name
	 * @param string $pk_field
	 */
	public static function all_table_info($table_name,$arr){ 
		 list($key,$val)= each($arr);
		 $sql = sprintf("select * from %s where %s='%s'",TABLEPRE.$table_name,$key,$val);
		 return dbfactory::query($sql);
	}
	
	
	
	
	
	
	
}