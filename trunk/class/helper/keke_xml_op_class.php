<?php

class keke_xml_op_class {
	
	var $_filepath;    //xml�ļ�·��  
	var $_xmlnode;    //XML�ļ��ڵ�
	var $_nodevalue;   //XML�ڵ��ֵ
	var $_nodeattr;    //XML�ڵ������ 
	var $_doc;
	var $_xpath;
    var $_K;
	function __construct($filepath='')
	{
		global $_K;
		$this->_K = $_K;
		$this->_filepath = $filepath;
		$this->_doc = new DOMDocument();
		$this->_doc->load($this->_filepath);
		$this->_xpath = new DOMXPath($this->_doc);
	}
    /**
	 * ��ȡXML�ڵ��ֵ
	 */
	function get_xml_node($nodename='') {
		$node = $this->_doc->getElementsByTagName ( $nodename );
		return $node->item ( 0 )->nodeValue;
	
	}
	/**
	 * �����޸�XML�ڵ��ֵ
	 *
	 * @return unknown
	 */
	function setxml($nodename='',$nodevalue='') {
		global $_K;
		if ($_K['charset']=="gbk"){
			$nodevalue = Keke::gbktoutf($nodevalue);
		}
		$node = $this->_doc->getElementsByTagName ( $nodename );
		$node->item ( 0 )->nodeValue = $nodevalue;
		return $this->_doc->save ( $this->_filepath );
	}
	/**
	 *��ȡxml����������array
	 *
	 * @param unknown_type $xml_path
	 * @return  xml_array;
	 */
	static function get_xml_toarr($xml_path=''){
		global $_K;
		$xml_o =  simplexml_load_file($xml_path); 
		$xml_arr = Keke::objtoarray($xml_o);
        if ($_K['charset']=="gbk"||$_K['charset']=="GBK"){
        	return  Keke::utftogbk($xml_arr);
        }
        else {
        	
        	return $xml_arr;
        }
       
	}
	/**
	 * ����һ���ڵ�
	 *
	 * @param string $nodename
	 * @param string $nodetext
	 */
	function  create_node($nodename='',$nodetext=''){
	  if($this->_K['charset']!='utf-8'){
		   $nodename = Keke::gbktoutf($nodename);
		   $nodetext = Keke::gbktoutf($nodetext);
		}
	  $xmlroot = $this->_doc->getElementsByTagName('root')->item(0);
	  $ele = new DOMElement($nodename,$nodetext);
	  $xmlroot->appendChild($ele);
	  $this->_doc->save($this->_filepath);
	}
	/**
	 * �����ӽڵ�
	 *
	 * @param Element $ele
	 * @param string $nodename
	 * @param string $nodetext
	 */
	function create_child_node($ele='',$nodename='',$nodetext=''){
		if($this->_K['charset']!='utf-8'){
		   $nodename = Keke::gbktoutf($nodename);
		   $nodetext = Keke::gbktoutf($nodetext);
		}
		$child_node = new DOMElement($nodename,$nodetext);
		$ele->appendChild($child_node);
		$this->_doc->save($this->_filepath);
	}
	/**
	 * ��ӽڵ�����
	 *
	 * @param Element $element
	 * @param string $attrname
	 * @param string $attrvalue
	 */
	function create_node_attr($element='',$attrname='',$attrvalue=''){
	if($this->_K['charset']!='utf-8'){
		   $attrname = Keke::gbktoutf($attrname);
		   $attrvalue = Keke::gbktoutf($attrvalue);
		}
		$attr = new DOMAttr($attrname,$attrvalue);
		$element->appendChild($attr);
		$this->_doc->save($this->_filepath);
	}
	/**
	 * ɾ���ڵ�
	 *
	 * @param Element $element    
	 * 
	 */
	function reomve_node($element){
		$element->parentNode->removeChild($element);
		$this->_doc->save($this->_filepath);
	}
	/**
	 * �ڵ��ѯ���ؽڵ����
	 *
	 * @param string $query   '/root/book'
	 * @param int  $item
	 * @return Element
	 */
	function query_node($query,$item){
		$node  = $this->_xpath->query($query)->item($item);
		return $node;
	}
	

}

?>