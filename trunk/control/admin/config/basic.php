<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 全局配置管理控制层
 * @author michael
 *
 */
class Control_admin_config_basic extends  Controller {

	function action_index($type=NULL){
		//定义全局变量与语言包，只要加载模板，这个是必须要定义.操
		global $_K,$_lang;
	 	//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
		$base_uri = BASE_URL."/index.php/admin/config_basic";
		//定义配置类型，默认为web型 
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}elseif(!isset($type)){
			$type = 'web';
		}
		$where = " type='$type'";
		$data_info =DB::select()->from('witkey_config')->where($where)->execute(); 
		//列表数据
		$list_arr = $data_info[0];
		require Keke_tpl::template('control/admin/tpl/config/basic');
	}
	
	function action_save(){
		$_POST = Keke_tpl::chars($_POST);
		//防止跨域提交，你懂的
		Keke::formcheck($_POST['formhash']);
		$type = $_POST['type'];
		//这里怎么说呢，定义生成sql 的字段=>值 的数组，你不懂，就是你太二了.
		$array = array('art_title'=>$_POST['txt_art_title'],
				'art_cat_id'=>$_POST['slt_cat_id'],
				'art_pic'=>$_POST['hdn_art_pic'],
				'content' => $_POST['txt_content'],
				'seo_title'=>$_POST['txt_seo_title'],
				'seo_keyword'=>$_POST['txt_seo_keyword'],
				'seo_desc'=>$_POST['txt_seo_desc'],
				'username'=>$_POST['txt_username'],
				'art_source'=>$_POST['txt_art_source'],
				'listorder'=>$_POST['txt_listorder'],
				'is_recommend'=>$_POST['ckb_is_recommend']=='on'?1:0,
				'cat_type'=>$type,
				'pub_time'=>time(),
		);
		 
		//这是个隐藏字段，也就是主键的值，这个主键有值，就是要编辑(update)数据到数据库
		if($_POST['hdn_art_id']){
			Model::factory('witkey_article')->setData($array)->setWhere("art_id = '{$_POST['hdn_art_id']}'")->update();
			//执行完了，要给一个提示，这里没有对执行的结果做判断，是想偷下懒，如果执行失败的话，肯定给会报红的。亲!
			Keke::show_msg('系统提示','index.php/admin/article_list/add?art_id='.$_POST['hdn_art_id'].'&type='.$type,'提交成功','success');
		}else{
			//这也当然就是添加(insert)到数据库中
			Model::factory('witkey_article')->setData($array)->create();
			Keke::show_msg('系统提示','index.php/admin/article_list/add?type='.$type,'提交成功','success');
		}
	}
	
}
