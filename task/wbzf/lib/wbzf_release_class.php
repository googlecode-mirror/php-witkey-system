<?php

/** 
 * @author michael
 * @property 悬赏任务发布类 
 */
keke_lang_class::load_lang_class('wbzf_release_class');
class wbzf_release_class extends keke_task_release_class {
	private $_wbzf_obj;
	private $_file_obj;
	public static function get_instance($model_id, $pub_mode = 'professional') {
		static $obj = null;
		if ($obj == null) {
			$obj = new wbzf_release_class ( $model_id, $pub_mode );
		}
		return $obj;
	}
	function __construct($model_id, $pub_mode = 'professional') {
		parent::__construct ( $model_id, $pub_mode );
		$this->_wbzf_obj = new Keke_witkey_task_wbzf_class ();
		$this->_file_obj = new Keke_witkey_file_class ();
		$this->get_task_config (); //获取任务配置
		$this->_std_obj->_release_info['txt_task_cash'] and $cash = $this->_std_obj->_release_info['txt_task_cash'] or $cash=$this->_task_config['min_cash'];
		$this->_default_max_day = keke_task_release_class::get_default_max_day($cash, $model_id,$this->_task_config['min_day']);
		$this->priv_init ();
	}
	/**
	 * 发布任务权限判断
	 */
	public function priv_init() {
		$priv_arr = wbzf_priv_class::get_priv ( '', $this->_model_id, $this->_user_info, '2' );
		$this->_priv = $priv_arr ['pub'];
	}
	/**
	 * 初始化任务配置
	 * @return   void
	 */
	public function get_task_config() {
		global $model_list;
		$model_list [$this->_model_id] ['config'] and $this->_task_config = unserialize ( $model_list [$this->_model_id] ['config'] ) or $this->_task_config = array ();
	}
	/**
	 * 发布模式进行信息
	 * @param $std_cache_name session名
	 * @param $data 外部传入参数
	 */
	function pub_mode_init($std_cache_name, $data = array()) {
		global $kekezu;
		global $_lang;
		$release_info = $this->_std_obj->_release_info;
		switch ($this->_pub_mode) {
			case "professional" :
				break;
			case "guide" :
				break;
			case "onekey" :
					$sql = " select a.model_id,a.task_title,a.indus_id,a.indus_pid,a.task_cash,a.start_time,a.end_time,
					b.*,b.wb_content task_desc from %switkey_task a left join %switkey_task_wbzf b 
						on a.task_id=b.task_id where a.task_id='%d' and a.model_id='%d'";
					$task_info = db_factory::get_one ( sprintf ( $sql, TABLEPRE, TABLEPRE, $data ['t_id'], $this->_model_id ) );
				if (! $release_info) {
					$sql = " select a.model_id,a.task_title,a.indus_id,a.indus_pid,a.task_cash,a.start_time,a.end_time,a.contact,
					b.*,b.wb_content task_desc from %switkey_task a left join %switkey_task_wbzf b 
						on a.task_id=b.task_id where a.task_id='%d' and a.model_id='%d'";
					$task_info = db_factory::get_one ( sprintf ( $sql, TABLEPRE, TABLEPRE, $data ['t_id'], $this->_model_id ) );
					$task_info or kekezu::show_msg ( $_lang['operate_notice'], $_SERVER ['HTTP_REFERER'], 3, $_lang['not_exsist_relation_task_and_not_user_onekey'], "warning" );
				
					$release_info = $this->onekey_mode_format ( $task_info );
					
					$allow_time = $task_info['end_time']-$task_info['start_time'];
					$task_day   = date('Y-m-d',$allow_time+time());
					$release_info ['txt_task_day'] = $task_day;
					
					$release_info ['txt_task_cash'] = intval ( $task_info ['task_cash'] );
					$release_info ['price_rule'] = unserialize($task_info['unit_price']);
					$release_info ['delivery_platform'] = explode ( ",", $task_info ['wb_platform'] );
					$release_info ['pub_type'] = $task_info ['is_repost'];
					$release_info ['is_comment'] = $task_info ['is_comment'];
					$release_info ['is_attention'] = $task_info ['is_focus'];
					$release_info ['is_at'] = $task_info ['is_at'];
					$release_info ['zf_at_num'] = $release_info ['new_at_num'] = $task_info ['at_num'];
					
					$this->save_task_obj ( $release_info, $std_cache_name ); //信息保存
				}
				break;
		}
	}
	/**
	 * 获取任务最大允许时间
	 * @param $task_cash 任务金额
	 * @return json
	 */
	public function get_max_day($task_cash) {
		global $kekezu;
		global $_lang;
		if ($task_cash >= $this->_task_config ['min_cash']) { //任务金额满足最小要求
			$time = keke_task_release_class::get_default_max_day($task_cash, $this->_model_id,$this->_task_config['min_day']);
			kekezu::echojson ( $time, 1 ,$time);
		} else { //不满足
			kekezu::echojson ( $_lang['task_min_cash_limit_notice'] . $this->_task_config ['min_cash'], 0 );
			die ();
		}
	}
	
	/**
	 * 任务发布
	 * 此方法只是用来产生任务记录
	 * @param $obj_name session存储对象名
	 */
	public function pub_task() {
		$task_obj = $this->_task_obj;
		$std_obj = $this->_std_obj;
		//发布信息公共处理部
		$this->public_pubtask ();
		//根据任务总花费来确顶任务发布状态
		$task_cash = $this->_std_obj->_release_info ['txt_task_cash']; //任务金额
		$this->set_task_status ( $this->get_total_cash ( $task_cash ), $task_cash);
		//任务发布
		$task_id = $task_obj->create_keke_witkey_task ();
		return $task_id;
	}
	
	/**
	 * 
	 * 存储微博转发信息
	 * @param int $task_id
	 */
	public function save_task_wbzf($task_id) {
		$wb_platform = implode ( ',', $this->_std_obj->_release_info ['delivery_platform'] );
		if ($this->_std_obj->_release_info ['pub_type'] == 1) { //判断微博是否转发
			$at_num = $this->_std_obj->_release_info ['zf_at_num'];
			$is_at = $this->_std_obj->_release_info ['is_at'];
		} else {
			$is_at = $this->_std_obj->_release_info ['new_is_at'];
			$at_num = $this->_std_obj->_release_info ['new_at_num'];
		}
		$repost_url = serialize ( $this->_std_obj->_release_info ['repost_url'] );
		$price = serialize ( $this->_std_obj->_release_info ['price_rule'] );
		
		//获取图片地址
		$file_id = $this->_std_obj->_release_info ['file_ids'];
		if ($file_id) {
			$this->_file_obj->setWhere ( "file_id =" . $file_id );
			$file_info = $this->_file_obj->query_keke_witkey_file ();
			$wb_img = $file_info ['0'] ['save_name'];
		}
		
		$this->_wbzf_obj->setWb_platform ( $wb_platform );
		$this->_wbzf_obj->setWb_img ( $wb_img );
		$this->_wbzf_obj->setIs_at ( $is_at );
		$this->_wbzf_obj->setIs_comment ( $this->_std_obj->_release_info ['is_comment'] );
		$this->_wbzf_obj->setIs_repost ( $this->_std_obj->_release_info ['pub_type'] );
		$this->_wbzf_obj->setRepost_url ( $repost_url );
		$this->_wbzf_obj->setIs_focus ( $this->_std_obj->_release_info ['is_attention'] );
		$this->_wbzf_obj->setAt_num ( $at_num );
		$this->_wbzf_obj->setWb_content ( $this->_std_obj->_release_info ['tar_content'] );
		$this->_wbzf_obj->setUnit_price ( $price );
		$this->_wbzf_obj->setTask_id ( $task_id );
		$res = $this->_wbzf_obj->create_keke_witkey_task_wbzf ();
		return $res;
	}

}