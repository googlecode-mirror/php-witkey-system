<?php	defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 后台广告列表显示页面
 * @copyright keke-tech
 * @author hr
 * @version v 2.0
 * @date 2011-12-21 下午05:54:07
 * @encoding GBK
*/
class Control_admin_man_adlist extends Controller{
	function action_index(){
		//定义全局变量，加载模板和语言包
		global $_K,$_lang;
		//需要查询的字段
		$fields = '`ad_id`,`ad_name`,`target_id`,`start_time`,`end_time`,`on_time`,`is_allow`';
		//在搜索中要显示的字段
		$query_fields = array('ad_id'=>$_lang['id'],'ad_name'=>$_lang['name'],'on_time'=>$_lang['time']);
		//页面uri
		$base_uri = BASE_URL.'/index.php/admin/man_adlist';
		//添加uri
		$add_uri = $base_uri.'/add';
		//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
		$count = intval($_GET['count']);
		//默认按照'on_time'排序
		$this->_default_ord_field = 'on_time';
		//get_url就是处理查询的条件
		extract($this->get_url($base_uri));
		//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
		$data_info = Model::factory('witkey_ad')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
		//列表数据
		$list_arr = $data_info['data'];
		//分页数据
		$pages = $data_info['pages'];
		//获取ad_target表中name
		$targets_arr =  Keke::get_table_data('*','witkey_ad_target', '', '', '', '', 'target_id');
		require Keke_tpl::template('control/admin/tpl/man/ad_list');
		
	}
		function action_add(){
			//始始化全局变量，语言包变量
			global $_K,$_lang;
			$target_id = $this->request->param('id');// $_GET['target_id'];
			$ac = $_GET['action'];
			$ad_id = $_GET['ad_id'];
			if($target_id&&$ac!='edit'){
				$target_info = Dbfactory::get_one(sprintf("select * from %switkey_ad_target where target_id = %d",TABLEPRE,$target_id));
				$ad_num = $target_info[ad_num];//允许广告数
				$have_ad_num = Dbfactory::get_count(sprintf("select count(ad_id) count from %switkey_ad where target_id = %d",TABLEPRE,$target_id));
				if($have_ad_num>=$ad_num){
					Keke::show_msg ( $_lang ['ads_num_over'],'index.php/admin/man_adlist', '将在', 'warning' );
				}
			}
			$ad_obj = new Keke_witkey_ad ();
			if ($sbt_action) {
				$type = 'ad_type_' . $ad_type; //类型flash/text/imag/code
				switch ($ad_type) {
					case "image" :
						if ($_FILES ['ad_type_image_file']['name']) {
							$file_path = keke_file_class::upload_file ( 'ad_type_image_file', '', 1, 'ad/' ); //上传文件
						}else{
							$file_path = $ad_type_image_path;
						}
						break;
					case "file" :
						if ($_FILES ['ad_type_flash_file']['name']) {
							if ($flash_method == 'url') {
								$file_path = $ad_type_flash_url;
							}
							if ($flash_method == 'file') {
								$file_path = keke_file_class::upload_file ( 'ad_type_flash_file', '', 1, 'ad/' ); //上传文件
							}
						}
						break;
				}
			
				$file_path && $ad_obj->setAd_file ( $file_path ); //文件
				var_dump($file_path);
				$ad_name = $hdn_ad_name ? $hdn_ad_name : $ad_name; //优先是用隐藏域(幻灯片情况下防止修改$ad_name)
				$ad_obj->setAd_name ( $ad_name ); //名字
				//开始时间
				$start_time && $ad_obj->setStart_time ( strtotime ( $start_time ) );
				//结束时间
				$end_time && $ad_obj->setEnd_time ( strtotime ( $end_time ) );
				//类型
				$ad_obj->setAd_type ( $ad_type );
				//投放位置
				$ad_obj->setAd_position ( $ad_position );
				//宽
				$width = ${$type . '_width'};
				$width && $ad_obj->setWidth ( $width );
				//高
				$height = ${$type . '_height'};
				$height && $ad_obj->setHeight ( $height );
				//url
				$url = ${$type . '_url'};
				$ad_obj->setAd_url ( $url );
				//content
				$content = ${$type . '_content'};
				$content && $ad_obj->setAd_content ( $content );
				$hdn_target_id && $ad_obj->setTarget_id ( intval ( $hdn_target_id ) );
				$ckb_tpl_type && $tpl_type = implode ( ',', $ckb_tpl_type ); //模板类型
				$ad_obj->setTpl_type ( $tpl_type );
				$ad_obj->setListorder ( intval ( $listorder ) );
				$ad_obj->setIs_allow ( intval ( $rdn_is_allow ) );
				$ad_obj->setOn_time ( time () );
			}
			require Keke_tpl::template('control/admin/tpl/man/ad_add');
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
// 			$array = array('ad_name'=>$_POST['ad_name'],
// 					'ad_url'=>$_POST['ad_type_image_url'],
// 					'ad_file'=>$_POST['ad_type_image_file'],
// 					'listorder' => $_POST['txt_listorder'],
// 					'on_time'=>time(),
// 			);
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
	/* function action_edit(){
		$ad_obj = new Keke_witkey_ad ();
		if ($ad_type == 'text' || $ad_type == 'code') { //如果广告类型是文本或者代码,那么应该删除width,height,不然排版会有问题
			$ad_obj->setWidth ( '' );
			$ad_obj->setHeight ( '' );
		}
		$ad_obj->setWhere ( 'ad_id=' . intval ( $ad_id ) );
		$result = $ad_obj->update ();
		Keke::admin_system_log ( $_lang ['edit_ads_data'] . $ad_id );
		Keke::admin_show_msg ( $result ? $_lang ['edit_ads_success_jump_adslist'] : $_lang ['not_make_changes_return_again'], 'index.php?do=tpl&view=ad_add&ac=edit&ad_id=' . $ad_id, 3, '', $result ? 'success' : 'warning' ); //die掉了
		$result = $ad_obj->create_keke_witkey_ad ();
		Keke::admin_system_log ( $_lang ['add_ads_data'] );
		Keke::admin_show_msg ( $result ? $_lang ['add_ads_success'] : $_lang ['add_fail_return_again'], 'index.php?do=tpl&view=ad_list&target_id=' . $hdn_target_id, 3, '', $result ? 'success' : 'warning' ); //die掉了
		$page_tips = $_lang ['add'];
		$ad_data = array ();
		//$target_id && $tagname and $ad_data ['ad_name'] = $tagname; //从广告组添加页面跳转过来时,ad_title只能和$tagname相同,并且为readonly
		//编辑 获取单条数据
			empty ( $ad_id ) && Keke::admin_show_msg ( $_lang ['edit_parameter_error_jump_listpage'], 'index.php?do=tpl&view=ad_list', 3, '', 'warning' );
			$page_tips = $_lang ['edit'];
			unset ( $ad_data );
			$ad_id = intval ( $ad_id );
			$ad_obj->setWhere ( 'ad_id="' . $ad_id . '"' );
			$ad_data = $ad_obj->update();
			$ad_data = $ad_data ['0'];
			$ad_data ['tpl_type'] = explode ( ',', $ad_data ['tpl_type'] );
			$target_id = $ad_data ['target_id']; //取出投放位置
		//获取对应的(一条)广告位相关信息
		if ($target_id) {
			$target_arr = Keke::get_table_data ( '*', 'witkey_ad_target', 'target_id=' . intval ( $target_id ) );
			$target_arr = $target_arr ['0'];
			/* 如果是幻灯片 ,则要判断有没有对应的广告组,
			 * 如果没有跳转至广告组添加页面
			* 如果有,那么将广告的ad_title设置为只读,不允许修改*/
// 			$is_slide = substr ( $target_arr ['code'], - 5 );
// 			if (strtolower ( $is_slide ) == 'slide') {
// 				$group_arr = Dbfactory::query ( 'select * from ' . TABLEPRE . 'witkey_tag where tagname="' . $target_arr ['name'] . '" and tag_type="9"' );
// 				if (! $group_arr) {
// 					Keke::admin_show_msg ( $_lang ['add_group_msg'], 'index.php?do=tpl&view=ad_group_add&ac=add&target_id=' . $target_arr ['target_id'] . '&tagname=' . $target_arr ['name'], '3', '', 'warning' );
// 				} else {
// 					$tagname = $group_arr ['0'] ['tagname'];
						
// 					$important_msg = $_lang ['name_must_same'];
// 				}
// 			}
		
// 			$ad_count = Dbfactory::get_count(" select count(ad_id) as num from  ".TABLEPRE."witkey_ad where target_id =".intval($target_id ));
// 		}
// 	} */
}
/* Keke::admin_check_role(32);
$target_position_arr = array ('top' => $_lang ['top'], 'bottom' => $_lang ['bottom'], 'left' => $_lang ['left'], 'right' => $_lang ['right'], 'center' => $_lang ['center'], 'global' => $_lang ['global'] );
$ad_obj = new Keke_witkey_ad_class();//广告数据
//$target_obj = new Keke_witkey_ad_target_class(); //广告类型
$table_obj = new keke_table_class('witkey_ad');

$page = isset($page) ? intval($page) : '1' ;
$url = "index.php?do={$do}&view={$view}&ad_id={$ad_id}&ad_type={$target_id}&ad_name={$ad_name}&target_id={$target_id}&ord={$ord}&page={$page}";
//ajax修改排序
if ($action && $action=='u_order'){
	!$u_id && exit();
	!$u_value && exit();
	$ad_obj -> setListorder( intval($u_value) );
	$ad_obj -> setWhere('ad_id='.intval($u_id));
	$ad_obj -> edit_keke_witkey_ad();
	exit();
}

//操作 删除,批量删除
if (($sbt_action && $ckb) || ($ac=='del' && $ad_id)){
	// 		if (!empty($ckb) || !empty($ad_id)) {
	$ids = $ckb ? implode(',', $ckb) : intval($ad_id) ;// echo $ids;
	$ad_obj -> setWhere('ad_id in ('.$ids.')');
	$result = $ad_obj -> del_keke_witkey_ad();
	Keke::admin_system_log($_lang['delete_ads'].$ids);
	Keke::admin_show_msg($result ? $_lang['ads_delete_success'] : $_lang['no_operation'] ,"index.php?do={$do}&view={$view}&target_id={$target_id}&ord={$ord}&page={$page}",3,'',$result?'success':'warning');
	// 		} else {
	// 			Keke::admin_show_msg($_lang['choose_operate_item']);
	// 		}
}


//广告类型调用
$targets_arr =  Keke::get_table_data('*','witkey_ad_target', '', '', '', '', 'target_id');
//查询数据
//$page = isset($page) ? intval($page) : '1' ;
$pagesize = isset($page_size) ? intval($page_size) : '10' ;
$where = '1=1';
$where .= $ad_id ? ' and ad_id="'.intval($ad_id).'"' : '' ;
$where .= $target_id && !$ad_id ? ' and target_id="'.intval($target_id).'"' : '';
$where .= $ad_name && !$ad_id ? ' and ad_name like "%'.$ad_name.'%"' : '';

is_array($w['ord']) and $where .=' order by '.$ord[0].' '.$ord[1];

//is_array($ord) && $ord=$ord[0].' '.$ord[1];//implode(' ',$ord);
//$where .= $ord ? ' order by '.$ord : ''; //echo $where;
$ad_arr = $table_obj -> get_grid($where, $url, $page, $pagesize, null, 1, 'ajax_dom'); //var_dump($ad_arr);
$pages = $ad_arr['pages'];
$ad_arr = $ad_arr['data'];
require $template_obj->template ( 'control/admin/tpl/admin_' . $do . '_' . $view); */
