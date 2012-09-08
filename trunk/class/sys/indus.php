<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * 系统关系行业分类的处理类
 * 
 * @author Administrator
 *
 */
class Sys_indus {
	
	/**
	 * 获取任务、商城分类行业
	 * 很恶心的作法。将相关模型的绑定行业合并后重溯出父行业,看到就蛋疼!
	 * @param $type 类型
	 * @param $mode 模式 total=>所有，parent=>仅父级,child=>仅子集
	 * @return array
	 */
	public static function get_classify_indus($type = 'task', $mode = 'parent') {
		global $kekezu;
		// 没有值就初化始一下
		if(!Keke::$_indus_arr){
			$kekezu->init_industry();
		}
		$indus_arr = array ();
		if (in_array ( $type, array ('task', 'shop' ) )) {
			$model_list = Keke::$_model_list;
			$indus_list = Keke::$_indus_arr;
			$indus_p_list = Keke::$_indus_p_arr;
			$indus_c_list = Keke::$_indus_c_arr;
			$indus_ids = ',';
			foreach ( $model_list as $v ) {
				if ($v ['model_type'] == $type && $v ['model_status']) {
					$indus_ids .= ',' . $v ['indus_bid'];
				}
			}
			$indus_ids = array_unique ( array_filter ( explode ( ',', $indus_ids ) ) );
			switch ($mode) {
				case 'parent' :
					if (! empty ( $indus_ids )) {
						foreach ( $indus_ids as $indus_id ) {
							$indus_pid = $indus_c_list [$indus_id] ['indus_pid'];
							$indus_pid and $indus_arr [$indus_pid] = $indus_p_list [$indus_pid];
						}
					} else {
						$indus_arr = $indus_p_list;
					}
					break;
				case 'total' :
					if (! empty ( $indus_ids )) {
						foreach ( $indus_ids as $indus_id ) {
							$p		   =  $indus_list[$indus_id]['indus_pid'];
							$indus_list[$p] and $indus_arr[$p] = $indus_list[$p];
						}
						foreach($indus_c_list as $indus_id=>$v){
							$p  = $v['indus_pid'];
							$indus_arr[$p] and $indus_arr[$indus_id]=$v;
						}
					} else {
						$indus_arr = $indus_list;
					}
					break;
				case 'child' :
					if (! empty ( $indus_ids )) {
						foreach ( $indus_ids as $indus_id ) {
							$indus_c_list[$indus_id] and $indus_arr [$indus_id] =$indus_c_list[$indus_id];
						}
					} else {
						$indus_arr = $indus_c_list;
					}
					break;
			}
		}
		return $indus_arr;
	}
}

 