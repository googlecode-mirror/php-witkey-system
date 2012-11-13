<?php
/**
 * �û��ռ�����
 * �жϵ�ǰ�û��Ǹ��˻�����ҵ
 * @copyright keke-tech
 * @author shang
 * @version v 2.0
 * 2010-6-13����11:25:00
 */

defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$member_id = intval ( $member_id );
Keke_lang::package_init($do);
$member_info = Keke::get_user_info($member_id);
$e_route_arr = array ("index", "statistic", "goods","member","intr","case","task" );//ֻ����һά��������������,��Ȼ����ҳ�Ļõ�Ƭ��(��ʾ,����)��Ӱ��
$e_banner_keys = array('index'=>'sy','intr'=>'gsjs','member'=>'qycy','task'=>'xgrw','goods'=>'spzs','case'=>'cgal','statistic'=>'gstj');
$p_route_arr = array ("index", "info", "goods","statistic"); 

//������Ϣ
 $shop_obj = new Keke_witkey_shop_class();
 $shop_obj->setWhere("uid = ".intval($member_id));
 $p_shop_info = $shop_obj->query_keke_witkey_shop();
 if (!$p_shop_info){//�ռ仹û�п�ͨ
 	$jump_url = $member_id == $uid ? 'index.php?do=user&view=setting&op=space' : 'index.php';
 	Keke::show_msg($_lang['this_user_no_open_space'], $jump_url);
 }
 $p_shop_info = $p_shop_info['0'];
 $e_shop_info = $p_shop_info;
 
 $banner_column = 'banner';//��Ӧ�����ݿ��ֶ�,��ҳ�õƺ�����ҳ��ֱ���ڲ�ͬ�ֶ�,��������Ǳ仯��_��ҵ�ռ�
 if (!$view || $view=='index'){//��ҵ�ռ�
 	$banner_column = 'homebanner';//��ҳ��Ӧ�Ļõ�Ƭ�ֶ�
 	$e_route_arr = array_slice($e_route_arr, 0, 5);//Ĭ��
 }
 
if($e_shop_info[$banner_column]){
	$banner_arr = unserialize($p_shop_info[$banner_column]);
}else{
	$banner_arr = array(
			//'sy'=>'tpl/default/img/enterprise/banner_img.png',
			'gsjs'=>'tpl/default/img/enterprise/banner_img.png',
			'qycy'=>'tpl/default/img/enterprise/qycy_banner.png',
			'xgrw'=>'tpl/default/img/enterprise/rw_banner.png',
			'spzs'=>'tpl/default/img/enterprise/sp_banner.png',
			'cgal'=>'tpl/default/img/enterprise/suc_banner.png',
			'gstj'=>'tpl/default/img/enterprise/gstj_banner.png');
}
//ajax�ı���ҵ�ռ�banner
if ($ac=='up_pic'){
	$banner_keys = $e_banner_keys;//array('index'=>'sy','intr'=>'gsjs','member'=>'qycy','task'=>'xgrw','goods'=>'spzs','case'=>'cgal','statistic'=>'gstj');
	$img_type = $banner_keys[$view] ? $banner_keys[$view] : 'sy';//����
	$ext = '.jpg,.jpeg,.png,.gif';
	if ($sbt){
		if ($p_shop_info['shop_type'] != 2 || $member_id!=$uid){//�ж�Ȩ��
			Keke::echojson($_lang['insufficient_permissions'],'0',array('type'=>$img_type,'file'=>$file_name));die();
		}
		if ($view=='index' || !$view){//��ҳ�õ�Ƭ
			if (!isset($slide_index) || intval($slide_index)>4){
				Keke::echojson(intval($slide_index).$_lang['insufficient_permissions'],'0',array('type'=>$img_type,'file'=>$file_name));die();
			}
			unset($banner_arr); $banner_arr = unserialize($p_shop_info[$banner_column]);//��������Ļ�,���ֻ����һ��ͼƬ,��ô������(Ĭ�ϵ�)ͼƬ���޷���ʾ
			$banner_arr[intval($slide_index)] = $file_name;
		}else{
			$banner_arr[$img_type] = $file_name;
		}
		$banner = serialize($banner_arr);
		$sql = sprintf("update %switkey_shop set %s='%s' where shop_id=%d",TABLEPRE,$banner_column,$banner,$e_shop_info['shop_id']);
		$result = dbfactory::execute($sql);
		Keke::echojson('',$result ? '1' : '0',array('type'=>$img_type,'file'=>$file_name));
		die();
	}else{
		$title=$_lang['change_the_slide'];
		require Keke_tpl::template(SKIN_PATH."/space/e_uppic");
		die();
	}
}


 //��������Ʒ�� 
$pub_num = intval(dbfactory::get_count(sprintf(" select count(service_id) count from %switkey_service where uid='%d' and service_status='2'",TABLEPRE,$member_id),0,null,3600));

 $p_shop_info['shop_type'] == 2 and $type = "e" or $type="p"; 

in_array( $view, $p_shop_info['shop_type'] == 2 ? $e_route_arr : $p_route_arr) or $view = "index";

if ($p_shop_info['shop_backstyle'] ){//�ռ䱳��ͼƬ����ʾ
	$shop_backstyle = unserialize($p_shop_info['shop_backstyle']);
	$shop_backstyle = implode(' ', array_values($shop_backstyle));//��������implode��?
}
 $ip = Keke::get_ip();

 if($_COOKIE['ip']!=1){
  	dbfactory::execute ( sprintf ( " update %switkey_shop set views=views+1 where uid=%d",TABLEPRE, $member_id));
	$_COOKIE['ip']='1';
  setcookie("ip",1,time()+3600*24,COOKIE_PATH);
 }

Keke_lang::package_init("space");
Keke_lang::loadlang("{$type}_{$view}");

 $p_shop_nav = array(
 		"index"=>$_lang['home'],
 		"info"=>$_lang['person_info'],
 		"goods"=>$_lang['my_goods']."[$pub_num]",
 		"statistic"=>$_lang['user_total']);
 $e_shop_nav = array(
 		"index"=>$_lang['home'],
 		"intr"=>$_lang['company_info'],
 		"member"=>$_lang['e_member'],
 		"task"=>$_lang['relation_task'],
 		"goods"=>$_lang['goods_display'],
 		"case"=>$_lang['success_case'],
 		"statistic"=>$_lang['company_total']);


require S_ROOT . "control/space/{$type}_{$view}.php";
