<?php  defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
/**
 * ϵͳ��ϵ��ҵ����Ĵ�����
 * 
 * @author Administrator
 *
 */
class Sys_indus {
	
	/**
	 * ��ȡ�����̳Ƿ�����ҵ
	 * �ܶ��ĵ������������ģ�͵İ���ҵ�ϲ������ݳ�����ҵ,�����͵���!
	 * @param $type ����
	 * @param $mode ģʽ total=>���У�parent=>������,child=>���Ӽ�
	 * @return array
	 */
	public static function get_classify_indus($type = 'task', $mode = 'parent') {
		global $kekezu;
		// û��ֵ�ͳ���ʼһ��
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
	/**
	 * ��[indus_pid][indus_id] ������������
	 * ��ҵ�����ع�����Ҫ���ں�̨��ҵ���ݹ����б�
	 * @param int $pid  ����ҵid
	 * @return array
	 */
	public static function get_indus_by_index( $pid = NULL) {
		 
		//ָ������ҵ,����NULlΪ��������ҵ
		$indus_arr = self::get_industry ( $pid );
		$indus_index_arr = array ();
		//�ع���ҵ����,�Ը�IDΪ����
		foreach ( $indus_arr as $v ) {
			$indus_index_arr [$v ['indus_pid']] [$v ['indus_id']] = $v;
		}
		return $indus_index_arr;
	}
	/**
	 * ��ȡָ���ĸ���ҵ,Ĭ�Ϸ������е���ҵ����
	 * @param int $pid
	 * @param bool $cache
	 * @return array
	 */
	public static function get_industry($pid = NULL, $cache = 0) {
		! is_null ( $pid ) and $where = " indus_pid = '" . intval ( $pid ) . "'";
		$indus_arr = Keke::get_table_data ( '*', "witkey_industry", $where, "listorder", '', '', 'indus_id', $cache );
		return $indus_arr;
	
	}
	/**
	 * ��ȡ��ҵ��
	 * @param int $select_indus
	 * @param string $type (option,cat,list)
	 * @return array
	 */
	public static function get_indus_tree($select_indus,$type="option"){
		$indus_arr = DB::select()->from('witkey_industry')->execute();
		//����
		sort ( $indus_arr );
		$temp_arr = array ();
		//������������
		Keke::get_tree ( $indus_arr, $temp_arr, $type, $select_indus, 'indus_id', 'indus_pid', 'indus_name' );
		return $temp_arr;
	}
}

 