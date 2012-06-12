<?php
/**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * 2011-10-8下午06:42:39
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );

in_array($opp, array('grow','mark')) or $opp= 'grow';
$ac_url=$origin_url."&op=credit";
/**
 * 星级数组
 */
$star_arr=keke_global_class::get_mark_star();
switch ($opp) {
	case "grow" :
		/**信誉**/
		$credit_level = unserialize($user_info['buyer_level']);
		/**能力**/
		$able_level =  unserialize($user_info['seller_level']);
		/*发布、中标、购买服务、销售服务款项统计*/
		$found_count = kekezu::get_table_data ( " sum(fina_cash) cash,sum(fina_credit) credit,count(fina_id) count,fina_action ", "witkey_finance", " uid='$uid' and fina_action in ('pub_task','task_bid','buy_service','sale_service') ", "", " fina_action ", "", "fina_action" );
		
		/**卖家辅助评价**/
		$saler_aid=keke_user_mark_class::get_user_aid($uid,'1',null,'1');
		/**买家辅助评价**/
		$buyer_aid=keke_user_mark_class::get_user_aid($uid,'2',null,'1');
		break;
	case "mark" :
		$mark_obj=new Keke_witkey_mark_class();//互评实例
		$where=" 1 = 1 ";
		intval($page) or $page="1";
		intval($page_size) or $page_size="5";
		!isset($mark_status) and $mark_status='n';//某人评价状态
		//$mark_type      or         $mark_type='1';//默认评价类型为威客
		$role_type      or         $role_type="1";//默认评论发起者角色类型1=>他人  2=>自己
		$url=$ac_url."&opp=$opp&mark_status=$mark_status&mark_type=$mark_type&role_type=$role_type&page_size=$page_size&page=$page";
		/**筛选条件**/
		
		$role_type=='1' and $where.=" and uid='$uid'" or $where.=" and by_uid='$uid' ";//角色类型为1=>我是被评价者uid  2=>我是评价者by_uid
		//$mark_type      and $where.=" and mark_type  ='$mark_type' ";//默认评价时类型为威客
	
		/**统计**/
		$mark_count=kekezu::get_table_data(" count(mark_id) count,mark_status","witkey_mark",$where,"","mark_status ","","mark_status");

		$mark_status!='n'&&isset($mark_status) and $where.=" and mark_status='$mark_status' ";
	
		/***分页统计*/
		$mark_obj->setWhere($where);
		$count=intval($mark_obj->count_keke_witkey_mark());//总计
		
		$pages=kekezu::$_page_obj->getPages($count, $page_size, $page, $url,"#userCenter");
		
		/**互评信息**/
		$mark_obj->setWhere($where.$pages['where']);

		$mark_list=$mark_obj->query_keke_witkey_mark();
	
		break;
}

function gen_star($num,$name){
	$j = round($num*2);
    $str = "";  
	for($i=1;$i<11;$i++){
     $str .= "<input name=\"star_$name\" type=\"radio\" class=\"star {split:2}\" value=\"$i\" 
     disabled=\"disabled\"";
     if($j==$i) $str .= " checked />";
    }
       
    return $str;
}

require keke_tpl_class::template ( "user/" . $do . "_" . $op.'_'.$opp );