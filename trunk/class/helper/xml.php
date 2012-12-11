<?php  defined ( 'IN_KEKE' ) or die ( 'Access Dinied' );


class Xml {

	var $parser;
	var $document;
	var $stack;
	var $data;
	var $last_opened_tag;
	var $isnormal;
	var $attrs = array();
	var $failed = FALSE;

	function __construct($isnormal) {
		$this->XMLparse($isnormal);
	}
	
    static function array2xml($arr, $htmlon = TRUE, $isnormal = FALSE, $level = 1){
    	$s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
    	$space = str_repeat("\t", $level);
    	foreach($arr as $k => $v) {
    		if(!is_array($v)) {
    			$s .= $space."<item id=\"$k\">".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</item>\r\n";
    		} else {
    			$s .= $space."<item id=\"$k\">\r\n".self::array2xml($v, $htmlon, $isnormal, $level + 1).$space."</item>\r\n";
    		}
    	}
    	$s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
    	return $level == 1 ? $s."</root>" : $s;
    }
    
    static function xml2array(&$xml, $isnormal = FALSE){
    	$xml_parser = new Xml($isnormal);
    	$data = $xml_parser->parse($xml);
    	$xml_parser->destruct();
    	return $data;
    }
    
	function XMLparse($isnormal) {
		$this->isnormal = $isnormal;
		$this->parser = xml_parser_create('ISO-8859-1');//创建XML解析器
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);//对XMl解析进行选项设置
		xml_set_object($this->parser, $this);//在对象中使用 XML 解析器。使解析器可以在对象中北使用
		xml_set_element_handler($this->parser, 'open','close');
		xml_set_character_data_handler($this->parser, 'data');
	}

	function destruct() {//释放XML解析器
		xml_parser_free($this->parser);
	}

	function parse(&$data) {
		$this->document = array();
		$this->stack	= array();
		return xml_parse($this->parser, $data, true) && !$this->failed ? $this->document : '';
	}

	function open(&$parser, $tag, $attributes) {
		$this->data = '';
		$this->failed = FALSE;
		if(!$this->isnormal) {
			if(isset($attributes['id']) && !is_string($this->document[$attributes['id']])) {
				$this->document  = &$this->document[$attributes['id']];
			} else {
				$this->failed = TRUE;
			}
		} else {
			if(!isset($this->document[$tag]) || !is_string($this->document[$tag])) {
				$this->document  = &$this->document[$tag];
			} else {
				$this->failed = TRUE;
			}
		}
		$this->stack[] = &$this->document;
		$this->last_opened_tag = $tag;
		$this->attrs = $attributes;
	}

	function data(&$parser, $data) {
		if($this->last_opened_tag != NULL) {
			$this->data .= $data;
		}
	}

	function close(&$parser, $tag) {
		if($this->last_opened_tag == $tag) {
			$this->document = $this->data;
			$this->last_opened_tag = NULL;
		}
		array_pop($this->stack);
		if($this->stack) {
			$this->document = &$this->stack[count($this->stack)-1];
		}
	}

}
