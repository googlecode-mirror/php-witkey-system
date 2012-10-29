<?php  defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * 后台配置列表
 * @author Michael
 * @version 2.2
   2012-10-19
 */

class Control_shop_goods_admin_list extends Control_shop_list{
	/**
	 * @var 模型代码
	 */
	public  $_model_code   = 'goods';
 
	/**
	 * 商品列表页
	 */
    function action_index(){
    	global $_K,$_lang;
    	
    	//要显示的字段,即SQl中SELECT要用到的字段
    	$fields = ' `sid`,`title`,`username`,`price`,`unite_price`,`service_time`,`unit_time`,`sale_num`,`status`,`on_time`,`is_top`';
    	//要查询的字段,在模板中显示用的
    	$query_fields = array('sid'=>$_lang['id'],'title'=>$_lang['name'],'price'=>$_lang['cash']);
    	//总记录数,分页用的，你不定义，数据库就是多查一次的。为了少个Sql语句，你必须要写的，亲!
    	$count = intval($_GET['count']);
    	//基本uri,当前请求的uri ,本来是能通过Rotu类可以得出这个uri,为了程序灵活点，自己手写好了
    	$base_uri = $this->_base_uri;
    	//添加编辑的uri,add这个action 是固定的
    	$add_uri =  $base_uri.'/add';
    	//删除uri,del也是一个固定的，写成其它的，你死定了
    	$del_uri = $base_uri.'/del';
    	//默认排序字段，这里按时间降序
    	$this->_default_ord_field = 'sid';
    	//这里要口水一下，get_url就是处理查询的条件
    	extract($this->get_url($base_uri));
    	//查指定类型的商品
    	$model_id = DB::select('model_id')->from('witkey_model')->where("model_code='$this->_model_code'")->get_count()->execute();
    	$where  .= " and model_id = $model_id";
    	//获取列表分页的相关数据,参数$where,$uri,$order,$page来自于get_url方法
    	$data_info = Model::factory('witkey_service')->get_grid($fields,$where,$uri,$order,$page,$count,$_GET['page_size']);
    	//列表数据
    	$list_arr = $data_info['data'];
    	//分页数据
    	$pages = $data_info['pages'];
    	
    	$shop_status = Control_shop_goods_base::get_shop_status();
    
     	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/list');
    }
    /**
     * 商品编辑
     */
    public function action_add(){
    	global  $_K ,$_lang;
    	$shop_id = $this->_shop_id;
    	 //获取商品信息
        $shop_info = $this->get_shop_info();
         
        $base_uri = $this->_base_uri;
        $process_arr = Control_shop_list::can_operate($shop_info['shop_status']);
        $indus_option_arr = Sys_indus::get_indus_tree($shop_info['indus_id']);
        //单赏商品状态
        $status_arr = Control_shop_goods_base::get_shop_status();
        //获取商品的增值项
        $payitem_list = Sys_payitem::get_shop_payitem($this->_shop_id);
        
        $file_list = Control_shop_list::get_shop_file($this->_shop_id);
         
    	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/edit');
    }
    
    /**
     * 商品保存
     */
    public function action_save(){
    	$shop_id = $_POST['shop_id'];
    	if(!$shop_id){
    		return FALSE;
    	}
    	Keke::formcheck($_POST['formhash']);
    	$array = array('shop_title'=>$_POST['shop_title'],
    			'indus_id'=>$_POST['slt_indus_id'],
    			'shop_desc'=>$_POST['shop_desc']);
    	$where = "shop_id = $shop_id";
    	Model::factory('witkey_shop')->setData($array)->setWhere($where)->update();
    	$this->request->redirect($this->request->referrer());
    	
    }
    
    /**
     * 上架
     */
    public function action_recommend(){
    	 $this->set_recommend();
    }
    /**
     * 下架
     */
    public function action_unrecommend(){
    	//改变商品的is_top 为0
    	$this->set_unrecommend();
    }
     
    /**
     * 删除商品，如何商品没有进行中的订单，则可以删除
     */
    public function  action_del(){
    	echo $this->del_service();
    }
     
    /**
     * 删除指定搞件
     * 删除稿件的同时要删除稿件留言表，稿件附件表,附件
     */
    public function action_work_del(){
    	$work_id = $_GET['work_id'];
    	//删除对应的文件
    	$files = DB::select('save_name')->from('witkey_file')->where("obj_id = '$work_id' and obj_type='work'");
    	foreach ($files as $v){
           $path = S_ROOT.$v['save_name'];
           if(file_exists($path)){
           	  unlink($path);
           } 		
    	}
    	//删除关联的三张表
    	$sql = "delete a,b,c from ".TABLEPRE."witkey_shop_work as a \n".
				"left join ".TABLEPRE."witkey_comment as b\n".
				"on b.obj_id = a.work_id and b.obj_type='work'\n".
				"left join ".TABLEPRE."witkey_file as c \n".
				"on a.work_id = c.obj_id and c.obj_type='work'\n".
				"where a.work_id = '$work_id'";
		echo DB::query($sql,Database::DELETE);				
    }
    /**
     * 商品留言列表页
     */
    public function action_comment(){
    	global  $_K ,$_lang;
    	$shop_id = $this->_shop_id;
    	$base_uri = $this->_base_uri;
    	//获取商品信息
    	$comments = DB::select()->from('witkey_comment')->where("obj_id = '$shop_id' and obj_type='shop' ")->execute(); 
    	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/shop_comment');
    }
    /**
     * 删除商品留言
     */
    public function action_comment_del(){
    	$comment_id = $_GET['comment_id'];
    	echo DB::delete('witkey_comment')->where("comment_id = '$comment_id'")->execute();
    }
    /**
     * 商品互评列表页
     */
    public function action_mark(){
    	global  $_K ,$_lang;
    	$shop_id = $this->_shop_id;
    	$base_uri = $this->_base_uri;
    	//获取商品信息
    	//$shop_info = $this->get_shop_info();
    	$where = "model_code = '$this->_model_code' and origin_id = '$shop_id'";
    	$marks = DB::select()->from('witkey_mark')->where($where)->execute();
    	//互评状态
    	$mark_status = Keke_user_mark::get_mark_status();
    	//互评项
    	require Keke_tpl::template('control/shop/'.$this->_model_code.'/tpl/admin/shop_mark');
    }
    
  

    
}