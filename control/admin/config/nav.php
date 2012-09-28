<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 导航菜单配置
 * @author S
 * @version v 2.0
 * 2011-12-13
 */
class Control_admin_config_nav extends Controller{
	
	function action_index(){
		global $_K,$_lang;
		//nav_list 系统初始化过了
		$nav_list = Keke::$_nav_list; 
		if(!$_POST){
			//没有提交时
			require Keke_tpl::template('control/admin/tpl/config/nav');
			die;
		}
		//当前页面有提交动作
		Keke::formcheck($_POST['formhash']);
		$nav = array_filter($_POST['nav']);
		//保存到数据库
		foreach($nav as $nav_id=>$v){
			$columns = array('nav_title','nav_url','nav_style','listorder');
			$values = array($v['nav_title'],$v['nav_url'],$v['nav_style'],$v['listorder']);
			$where = "nav_id = '".intval($nav_id)."'";
			DB::update('witkey_nav')->set($columns)->value($values)->where($where)->execute();
		}
		//更新导航菜单的缓存
		Cache::instance()->del('keke_nav');
		Keke::show_msg ($_lang['submit_success'], "index.php/admin/config_nav",'success' );
	}
	/**
	 * 判断外网地址，内网址
	 * @param unknown_type $url
	 * @return boolean
	 */
	function url_analysis($url){
	    if(strpos($url, 'http')!==FALSE){
	    	return TRUE;
	    }else{
	    	return FALSE;
	    }
	}
}
/**
 * 地址分析
 */
/* function nav_analysis($url){
	global $_K;
	 
	
	$front_route = Keke::route_output();
	$readnonly = true;
	$site_ali = parse_url($_K['siteurl']);
	$nav_ali = parse_url($url);
	if(sizeof($nav_ali)>2&&$site_ali['scheme'].'://'.$site_ali['host']!=$nav_ali['scheme'].'://'.$nav_ali['host']){//站外链接
		$readnonly=false;//允许修改
	}else{
		parse_str($nav_ali['query'],$data);
		$data['do'] or $data['do']='index';
		in_array($data['do'],$front_route) or $readnonly=false;//不存在站内路由的允许修改
	}
	return $readnonly;
} */
/* Keke::admin_check_role ( 41 );
$nav_list = Keke::get_table_data ( '*', 'witkey_nav', '', 'listorder', '', '', "nav_id");
//nav_list的json，方便JS赋值
$nav_list_json = Keke::json_encode_k(Keke::gbktoutf($nav_list));
$nav_obj = new keke_table_class ( "witkey_nav" );

$url = "index.php?do=$do&view=$view";

//是否编辑
if($ac == 'edit'){
	if(!empty($nav_id)){
		$sql = sprintf("select * from %switkey_nav where nav_id ='%d' limit 0,1",TABLEPRE,$nav_id);
		$nav_config = Dbfactory::get_one($sql);
		$readonly = nav_analysis($nav_config['nav_url']);
	}
	if($fds and $sbt_edit){
		if($set_index){ 
			$set_rs = Dbfactory::execute(sprintf("update %switkey_basic_config set v='%s' where k='set_index'",TABLEPRE,$fds['nav_style']));
		}else{
			$set_rs = Dbfactory::execute(sprintf("update %switkey_basic_config set v='index' where k='set_index'",TABLEPRE));
		}
		$res = $nav_obj->save($fds,$pk);
		($res || $set_rs) and Keke::admin_show_msg($_lang['operate_success'],$url,2,$_lang['edit_success'],"success") or Keke::admin_show_msg($_lang['operate_fail'],$url,2,$_lang['edit_fail'],"error");
	}
	require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view.'_edit' );
	die;
}
//删除导航
if ($ac == 'del') {
	$nav_obj->del ( 'nav_id', $nav_id, $url );
	Keke::admin_show_msg ($_lang['delete_nav_success'], "index.php?do=config&view=nav",3,'','success' );
}
//设为首页
if($ac=='set_index'){
	$res = Dbfactory::execute(sprintf("update %switkey_basic_config set v='%s' where k='set_index'",TABLEPRE,$nav_style));
	Keke::admin_show_msg ( $_lang['set_index_success'], "index.php?do=config&view=nav",3,'','success' );
}
if($sbt_edit){
	$sql = '';
	$nav = array_filter($nav);
	foreach($nav as $nav_id=>$v){
		$sql = ' update '.TABLEPRE.'witkey_nav set nav_title="'.$v['nav_title'].'"';
		$v['nav_url'] and $sql.=',nav_url="'.$v['nav_url'].'"';
		$sql.=',nav_style="'.$v['nav_style'].'"';
		$sql.=',listorder='.intval($v['listorder']);
		$sql.=' where nav_id='.intval($nav_id);
		Dbfactory::execute($sql);
	}
	Keke::admin_system_log('编辑后台菜单');
	Keke::admin_show_msg ('菜单编辑成功', "index.php?do=config&view=nav",3,'','success' );
}
 
require $template_obj->template ( 'control/admin/tpl/admin_config_' . $view );   */