<?php	defined ( 'IN_KEKE' ) or exit('Access Denied');
 /**
 * @copyright keke-tech
 * @author Chen
 * @version v 2.0
 * ������ص�ajax����
 * 2011-10-08����02:57:33
 */


	switch ($ajax){
		case "load":
			if($work_id){
				$file_ids = Dbfactory::get_count(sprintf(" select work_file from %switkey_task_work where work_id='%d'",TABLEPRE,$work_id));
				$file_list = keke_task_class::get_work_file($file_ids);
			}
			break;
		case "download":
			keke_file_class::file_down($file_name, $file_path);
			break;
		case "delete":
			$res = keke_file_class::del_att_file($file_id, $filepath);
			$res and Keke::echojson ( '', '1' ) or Keke::echojson ( '', '0' );
			die ();
			break;
		case "del"://ͨ��·��ɾ��
			//���������κ�һ�����ɾ�die��
			if(strtolower($_SERVER['REQUEST_METHOD'])!='post' || !isset($fid) || !isset($filepath)){	
				Keke::echojson ( '', '0' );die();
			}
			$fid = intval($fid);//file_id
			$size = Keke::escape($size);//ͼƬ�Ĳ�ͬ�ߴ�
			$res = keke_file_class::del_att_file($fid,$filepath,$size);
			$res and Keke::echojson ( '', 1 ) or Keke::echojson ( '', '0' );
			die ();
		case "goods_filedown"://�����ļ�����
			$service_info = Dbfactory::get_one(sprintf(" select file_path,submit_method,uid from %switkey_service where service_id='%d'",TABLEPRE,$_GET['sid']));
			//����Ƿ����
			$has_buy = keke_shop_class::check_has_buy($_GET['sid'], $user_info['uid']);
			if($has_buy['order_status']=='confirm'||$service_info['uid']==$user_info['uid']){
				$file_list = explode(",",$service_info['file_path']);
			}
			break;
		case "help_search":
			$keyword and $search_list = Dbfactory::query(sprintf(" select a.art_title,a.content from %switkey_article a left join %switkey_article_category b on a.art_cat_id=b.art_cat_id where b.cat_type='help' and INSTR(a.art_title,'%s')",TABLEPRE,TABLEPRE,$keyword));
		break;
	}
	
require keke_tpl_class::template("ajax/ajax_".$view);



