<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Generate PHP Class version 2.0 deisgn michael</title>
 <style>
body{ font-size:1em; lineheight:1.5em; background:#fefefe; color:#666;}
*{ margin:0; padding:0;list-style:none;}
.top-nav{ background:#06a; padding:10px; color:#03a;}
	.top-nav a{ dispaly:inline-block; padding:5px 10px; color:#fff; background:#69f;text-decoration:none;}
	.top-nav a:hover{color:#06a; background:#fff;}
code{ background:#ffc; color:#666; display:block; padding:10px;}
h1{ margin:10px;font-size:2em; color:#000; }
h3{font-size:1.2em;margin:10px;}
fieldset{margin:10px; padding:10px;}
ul{margin:10px; display:block;}
li{ padding:10px;}
table,td,th{ border:1px solid #ccc;margin:10px auto;padding:5px;}
tr:hover{ background:#ffa;}
caption{font-weight:bold; }
 </style>
		 
</head>
<body>

<div class="top-nav">
	<a href="index.php">模型生成</a>|<a href="bulid.php">后台编辑</a> |<a href="bulid_list.php">后台列表</a>|<a href="filelist.php">文件下载</a> 
</div>
<code>生成代码均为utf-8编码，请注意转码</code>
 
<h1>Php代码生成器1.5</h1>
<fieldset>
    	<legend>
    		参数设置
    	</legend>
		  <form action="" method="post" accept-charset="utf-8" >
            <ol>
                <li>数据库类型：<select id="sltDbtype"  name="sltDbtype" style="width:160px;"  ><option value="mysql">Mysql</option></select>
                </li>
                <li>
					数据库主机：<input type="text" value="localhost" maxlength="50" id="textDbhost" name="textDbhost" limit="required:true" len="2-50" msg="数据库主机不能为空!" >
				</li>
				<li>
					数据库名称：<input type="text" value="keke_witkey" maxlength="50" id="txtDbname" name="txtDbname" limit="required:true" len="2-50" msg="数据库名称不能为空!" >
				</li>
				<li>
					数据库用户：<input type="text" value="root" maxlength="30" id="txtDbuser" name="txtDbuser" limit="required:true" len="2-50" msg="数据库用户名不能为空!">
				</li>
				<li>
					数据库密码：<input type="text" value="123456" maxlength="15" id="txtDbpassword" name="txtDbpassword" limit="required:true" len="2-50" msg="数据库密码不能为空!">
				</li>
				<li>
					数据库编码：<input type="text" value="utf8" maxlength="10" id="txtDbchare" name="txtDbchare" limit="required:true" len="2-50" msg="数据库编码不能为空!">
				</li>
				<li>
					表前缀：<input type="text" value="keke_" maxlength="10" id="txtTablepre" name="txtTablepre" limit="required:true" len="2-50" msg="表前缀不能为空!">
				</li>
				
            </ol>
            <p style="padding-left:180px">
                <input type="submit" name="sbtGenerate" value="生成" />
				<input type="reset" value="reset">
            </p>
        </form>
 </fieldset>
 <?php 
 error_reporting(0);
 if(isset($_POST['sbtGenerate']))
 {
     $dbtype= $_POST['sltDbtype'];
     $dbhost = $_POST['textDbhost'];
     $dbname = $_POST['txtDbname'];
     $dbuser = $_POST["txtDbuser"];
     $dbpwd = $_POST['txtDbpassword'];
     $dbchare = $_POST['txtDbchare'];
     $tablepre = $_POST['txtTablepre'];  //表前缀
     $dir = dirname(__FILE__.DIRECTORY_SEPARATOR);  //当前目录
     $class_path = $dir."\\model";  //phpclas目录
     if(!file_exists($class_path))   //判断目录是否存在，如果不存在则创建phpclass目录
     {
     	mkdir($class_path,0777);
     }
    $mysqlink =  mysql_connect($dbhost,$dbuser,$dbpwd);//连接数据库
    if(!$mysqlink) die("数据库连接失败!");
    mysql_select_db($dbname,$mysqlink); //选择数据库
    mysql_query("set names {$dbchare}"); //设置数据库编码
    $mytables = mysql_list_tables($dbname,$mysqlink);  //获取数据库内所有表 
    $tables = array();  //建立表数组
    while ($row = mysql_fetch_row($mytables)) {   //循环表结果集
    	$tables[] = $row[0];   //将表名存到tables数组
    }
    foreach ($tables as $table) {   //循环表内的字段
    	$query = "select * from {$table}";
    	$result = mysql_query($query);
    	$i= 0 ;
    	$fields = array();
    	while ($i < mysql_num_fields($result)) {
    		$fields[$i]['name']  = mysql_field_name($result,$i);  //字段名称
    		$fields[$i]['type'] = mysql_field_type($result,$i);   //字段类型
    		$fields[$i]['flags']  = mysql_field_flags($result,$i); //字段属性
    		$i++;
    	}
    	//print_r($fields);
    	//定义私有变量
    	$private = "protected static \$_data = array ();"; ////定义静态的$data数组
    	$set = "";  //定义setfunction 
    	$get = "";  //定义getfunction
    	$data = "\t\t \$data =  array(); \r"; //定义$data数组
    	$data_item = "";
    	
    	foreach ($fields as $field) {
    		$field_name = $field['name'];  //字段名
    		$field_type = $field['type'];  //字段类型
    		$field_flags = $field['flags'];  //字段属性
    		if(strpos($field['flags'],"primary_key")!==FALSE){
    			$primary_key = $field_name; //定义主键
    			//$private .=  "\t public \$_".$field['name'].";  \r";  //获取所有私有变量
    		}
    		
    		$set .="\t\tpublic function set".ucfirst($field_name)."(\$value){ \r";
    		$set .= "\t\t\t self::\$_data ['".$field_name."'] = \$value;\r";
    		$set .= "\t\t\t return \$this ; \r";
    		$set .= "\t\t}\r";
    		$get .= "\t\tpublic function get".ucfirst($field_name)."(){\r";
    		$get .="\t\t\t return self::\$_data ['".$field_name."']; \r";
    		$get .="\t\t}\r";
    		
    		
    		/* $data_item .= "\t\t\tif(!is_null(\$this->_{$field_name})){ \r";
    		$data_item .= "\t\t\t\t \$data['{$field_name}']=\$this->_{$field_name};\r";
    		$data_item .= "\t\t\t}\r"; */
    		
    	}
    	//$data .= "\t\t\t\t\t \$item \r";
    	//$data .= "\t\t\t\t\t ); \r";
    	
    		$set .="\t\tpublic function setWhere(\$value){ \r";
    		$set .= "\t\t\t self::\$_data ['where'] = \$value;\r";
    		$set .= "\t\t\t return \$this; \r";
    		$set .= "\t\t}\r";
    		$get .= "\t\tpublic function getWhere(){\r";
    		$get .="\t\t\t return self::\$_data ['where']; \r";
    		$get .="\t\t}\r";
    	//cache
    	/* $get.= "\t\tpublic function getCache_config() {\r";
		$get .= "\t\t\treturn \$this->_cache_config;\r";
		$get.="\t\t}\r";
    	$set.= "\t\tpublic function setCache_config(\$_cache_config) {\r";
		$set.= "\t\t\t \$this->_cache_config = \$_cache_config; \r";
	    $set .= "\t\t}\r"; */
    		
    	/* $cusset = "
    	   public  function __set(\$property_name, \$value) {\r
		 		\$this->\$property_name = \$value; \r
			}\r
			public function __get(\$property_name) { \r
				if (isset ( \$this->\$property_name )) { \r
					return \$this->\$property_name; \r
				} else { \r
					return null; \r
				} \r
			}\r
    	";
		    function create() {
		$res = $this->_db->insert ( $this->_tablename, self::$_data, 1, $this->_replace );
		$this->reset();
		return $res;
	}
    	 */
    	
    	$tablefre = substr($table,strlen($tablepre)) ; //得到表后缀
    	$construct = " function  __construct(){ \r";  //构造方法
    	$construct .= "\t\t\t parent::__construct ( '".$tablefre."' );\r";
    	$construct .= "\t\t }\r";
    	
    	$create = "/**\r";
    	$create .= "\t\t * insert into  {$table}  ,or add new record\r";
    	$create .= "\t\t * @return int last_insert_id\r";
    	$create .= "\t\t */\r";
        $create .= "\t\tfunction create(\$return_last_id=1){\r";
        $create .= "\t\t \$res = \$this->_db->insert ( \$this->_tablename, self::\$_data, \$return_last_id, \$this->_replace ); \r";
        $create .= "\t\t \$this->reset(); \r";
    	$create .= "\t\t\t return \$res; \r";
    	$create .= "\t\t } \r";
    	
    	$edit = "/**\r";
    	$edit .= "\t\t * update table {$table}\r";
    	$edit .= "\t\t * @return int affected_rows\r";
    	$edit .= "\t\t */\r";
	    $edit.= "\t\tfunction update() {\r";
		$edit.= "\t\t\t	if (\$this->getWhere()) { \r";
		$edit.= "\t\t\t\t	\$res =  \$this->_db->update ( \$this->_tablename, self::\$_data, \$this->getWhere());\r";
		$edit.= "\t\t\t	} elseif (isset ( self::\$_data ['".$primary_key."'] )) { \r";
		$edit.= "\t\t\t\t		self::\$_data ['where'] = array ('".$primary_key."' => self::\$_data ['".$primary_key."'] );\r";
		$edit.= "\t\t\t\t		unset(self::\$_data['".$primary_key."']);\r";
		$edit.= "\t\t\t\t		\$res = \$this->_db->update ( \$this->_tablename, self::\$_data, \$this->getWhere() );\r";
		$edit.= "\t\t\t	}\r";
		$edit.= "\t\t\t	\$this->reset();\r";
		$edit.= "\t\t\t	return \$res;\r";
		$edit.= "\t\t}";
    	
    	$query = "/**\r";
       	$query .= "\t\t * query table: {$table},if isset where return where record,else return all record\r";
    	$query .= "\t\t * @return array \r";
    	$query .= "\t\t */\r";
    	$query .= "\t\tfunction query(\$cache_time = 0){ \r";
    	$query .= "\t\t\t if(\$this->getWhere()){ \r";
    	$query .= "\t\t\t\t \$sql = \"select * from \$this->_tablename where \".\$this->getWhere(); \r";
    	$query .= "\t\t\t }else{ \r";
    	$query .= "\t\t\t\t \$sql = \"select * from \$this->_tablename\"; \r";
    	$query .= "\t\t\t } \r";
    	$query .= "\t\t\t \$this->reset();\r";
    	$query .= "\t\t\t return \$this->_db->cached ( \$cache_time )->cache_data ( \$sql );\r";
    	$query .= "\t\t } \r";
    	
    	$count = "/**\r";
       	$count .= "\t\t * query count {$table} records,if iset where query by where \r";
    	$count .= "\t\t * @return int count records\r";
    	$count .= "\t\t */\r";
    	$count .= "\t\tfunction count(){ \r";
    	$count .= "\t\t\t if(\$this->getWhere()){ \r";
    	$count .= "\t\t\t\t \$sql = \"select count(*) as count from \$this->_tablename where \".\$this->getWhere(); \r";
    	$count .= "\t\t\t } \r";
    	$count .= "\t\t\t else{ \r";
    	$count .= "\t\t\t\t \$sql = \"select count(*) as count from \$this->_tablename\"; \r";
    	$count .= "\t\t\t } \r";
    	$count .= "\t\t\t \$this->reset(); \r";
    	$count .= "\t\t\t return \$this->_db->get_count ( \$sql ); \r";
    	$count .= "\t\t } \r";
    	
    	$delete = "/**\r";
    	$delete .= "\t\t * delete table {$table}, if isset where delete by where \r";
    	$delete .= "\t\t * @return int deleted affected_rows \r";
    	$delete .= "\t\t */\r";
    	$delete .= "\t\tfunction del(){ \r";
    	$delete .= "\t\t\t if(\$this->getWhere()){ \r";
    	$delete .= "\t\t\t\t \$sql = \"delete from \$this->_tablename where \".\$this->getWhere(); \r";
    	$delete .= "\t\t\t } \r";
    	$delete .= "\t\t\t else{ \r";
    	$delete .= "\t\t\t\t \$sql = \"delete from \$this->_tablename where {$primary_key} = \$this->_{$primary_key} \"; \r";
    	$delete .= "\t\t\t } \r";
    	$delete .= "\t\t\t \$this->reset(); \r";
    	$delete .= "\t\t\t return \$this->_db->query ( \$sql, database::DELETE ); \r";
    	$delete .= "\t\t } \r";
    	$tb = strtolower($table);
    	//生成类文件
    	$filcontent = <<<EOT
<?php
	class {$tb}{
	    {$private}
	    {$construct}	    
	    {$get}
	    {$set}
	    {$create}
	    {$edit}
	    {$query}
	    {$count}
	    {$delete}
   } //end 
EOT;
    	 
    	$filename = $class_path."\\".$tb.".php" ;  //文件路径+文件名
    	if(file_exists($filename)) //判断类文件是否存在
    	{
    	    unlink($filename);	    //如果存在则删除
    	    $handle = fopen($filename,"w+");   //创建新文件
    		fwrite($handle,$filcontent); //写内容
    		fclose($handle);
    	}
    	else 
    	{
    		$handle = fopen($filename,"w+");   //创建新文件
    		fwrite($handle,$filcontent); //写内容
    		fclose($handle);
    	}
    	
    	
    	
    }
    
    //print_r($tables);
    echo mysql_error($mysqlink);   //显示mysql 错误
    mysql_close($mysqlink); //关闭数据库
    echo "<h1 style='color:red'>生成成功<h1>";   
 }
 
 ?>
 <hr>
 <p style="font-size: 12px;float: right">程序最后更新时间为：2012-5-29</p>
</body>
</html>