<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );

class Control_admin_link extends Controller {
	/**
	 * 友链管理初始化页面
	 * index 是必须的话，否则路由找不到index，程序就挂了啊
	 * 坑爹的注释啊,这是必须要写的(*_*)!
	 */
	function action_index() {
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
		
		//要显示的字段,即SQl中要用到的字段
		$fields = ' `link_id`,`link_name`,`link_url`,`listorder`,`on_time` ';
		//要查询的字段,在模板中显示用的
		$query_fields = array('link_id'=>$_lang['id'],'link_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = BASE_URL."/index.php/admin/link";
		//添加编辑的uri,add这个action 是固定的
		$add_uri =  $base_uri.'/add';
		//删除uri,del也是一个固定的，写成其它的，你死定了
		$del_uri = $base_uri.'/del';
		//这里要口水一下，get_url就是处理查询的条件
	    extract($this->get_url($base_uri));

		$data_info = Model::factory('witkey_link')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		
		$link_arr = $data_info['data'];
		$pages = $data_info['pages'];
		
		//查询当前的Sql
		//var_dump(Database::instance()->get_last_query());
		//查询当的sql数量
		//echo Database::instance()->get_query_num();
		
		require Keke_tpl::template('control/admin/tpl/link');
	}
	/**
	 * 
	 * @param string $base_uri
	 * @return multitype:string number
	 */
	function get_url($base_uri){
		$r = array();
		//初始化where的值
		$where = ' 1=1 ';
		$query_uri = '?';
		//字段与条件
		if($_GET['slt_fields']  and $_GET['txt_condition']){
			//时间的查询处理
			if(strtotime($_GET['txt_condition'])){
				//字段值为时间时
				$c =  $_GET['txt_condition'];
				//这里的数据库中的on_time 字段必须是时间戳
				$f =  "FROM_UNIXTIME(`{$_GET['slt_fields']}`,'%Y-%m-%d')";
				
			}else{
				//非时间的条件
				$c = $_GET['txt_condition'];
				$f = "`{$_GET['slt_fields']}`";
			}
			//如果是like 条件的值要加%
			if($_GET['slt_cond']=='like'){
				$c = "%$c%";
			}
			//拼接url字段
			$where .= "and $f {$_GET['slt_cond']} '$c'";
			
			$query_uri .= "slt_cond={$_GET['slt_cond']}";
			$query_uri .= "&slt_fields={$_GET['slt_fields']}&txt_condition={$_GET['txt_condition']}";
		}
		if($_GET['page_size']){
			$query_uri .= '&page_size='.$_GET['page_size'];
		}
		//页数
		$_GET['page'] and $page = $_GET['page'] or $page = 1;
		
		//排序的uri,f表示要排序的字段
		if($_GET['f']){
			$query_uri .= '&f='.$_GET['f'].'&ord='.$_GET['ord'];
		}
		//查询uri
		$uri = $base_uri.$query_uri;
		//排序标记，定义js 中的变量
		//降序
		if(isset($_GET['ord']) and $_GET['ord']==1){
			$ord_tag = 0;
			$ord_char = '↓';
			//升序
		}elseif(isset($_GET['ord']) and $_GET['ord']==0){
			$ord_tag = 1;
			$ord_char = '↑';
		}else{
			//默认不显示
			$ord_tag = 0;
			$ord_char = '';
		}
		
		
		//排序的条件
		if(isset($_GET['f'])){
			$t = $ord_tag==1?'desc':'asc';
			$order = " order by {$_GET['f']} $t ";
		}
		$r['where'] = $where;
		//$r['query_uri'] =$query_uri;
		$r['uri'] = $uri;
		$r['ord_tag']=$ord_tag;
		$r['ord_char']=$ord_char;
		$r['order'] = $order;
		$r['page']=$page;
		return $r;		
	}
	//添加与编辑初始化
	function action_add(){
		//始始化全局变量，语言包变量
		global $_K,$_lang;
		$link_id = $_GET['link_id'];
		//如果有值，就进入编辑状态
		if($link_id){
			$link_info = Model::factory('witkey_link')->setWhere('link_id = '.$link_id)->query();
			$link_info = $link_info[0];
			$link_pic = $link_info['link_pic'];
		}
		//有http的就是url地址，不口水了
		if(strpos($link_pic, 'http')!==FALSE){
			//远程地址
			$mode = 1;
		}else{
			//本地图片
			$mode = 2;
		}
		//加载模板，这有点费J8话,地球人都懂的
		require Keke_tpl::template('control/admin/tpl/link_add');
	}
	/**
	 * 保存模板上提交到的数据到数据库中
	 * 这个acton 是通用的，不要随便定义这个名称
	 * 
	 */
	function action_save(){
		//防止跨域提交，你懂的
		Keke::formcheck($_POST['formhash']);
		//这里是业务判断,连接是图片还有url地址
		if($_POST['showMode'] ==1){
			$link_pic = $_POST['txt_link_pic'];
		}elseif(!empty($_FILES['fle_link_pic']['name'])){
			//上传文件用的，这个对新手来说好使,要就是简单
			$link_pic = keke_file_class::upload_file('fle_link_pic');
		}
		//这里怎么说呢，定义生成sql 的字段=>值 的数组，你不懂，就是你太二了.
		$array = array('link_name'=>$_POST['txt_link_name'],
				       'link_url'=>$_POST['txt_link_url'],
					   'link_pic'=>$link_pic,
					   'listorder' => $_POST['txt_listorder'],				  
		);
        //这是个隐藏字段，也就是主键的值，这个主键有值，就是要编辑(update)数据到数据库
		if($_POST['hdn_link_id']){
			Model::factory('witkey_link')->setData($array)->setWhere("link_id = '{$_POST['hdn_link_id']}'")->update();
			//执行完了，要给一个提示，这里没有对执行的结果做判断，是想偷下懒，如果执行失败的话，肯定给会报红的。亲!
			Keke::show_msg('系统提示','index.php/admin/link/add?link_id='.$_POST['hdn_link_id'],'提交成功','success');
		}else{
		 //这也当然就是添加(insert)到数据库中	
			Model::factory('witkey_link')->setData($array)->create();
			Keke::show_msg('系统提示','index.php/admin/link/add','提交成功','success');
		}
		
		
	}
	/**
	 * 这里是删除的action 主要是处理要单条删除
	 * 与多条删除。
	 * 规矩，删除action的名称统一del,不要问为什么
	 * 单条删除，传主键名称与值就可以删除了
	 * 多条删除的，是前端js拼接好的ids传过来的值.js 只传ids 的哟。不要写成主键名称
	 * 
	 */
	function action_del(){
		//删除单条,这里的link_id 是在模板上的请求连接中有的
		if($_GET['link_id']){  
			$where = 'link_id = '.$_GET['link_id'];
		//删除多条,这里的条件统一为ids哟，亲	
		}elseif($_GET['ids']){
			$where = 'link_id in ('.$_GET['ids'].')';
		}
		//输出执行删除后的影响行数，模板上的js 根据这个值来判断是否移聊tr标签到
		//注释中不能打单引，否则去注释的工具失效,蛋痛的工具啊!
		echo  Model::factory('witkey_link')->setWhere($where)->del();
	}
	
}
