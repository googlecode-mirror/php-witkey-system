<?php
/**
 * this not free,powered by keke-tech
 * @auther �Ž�
 * encoding GBK  last-modify 2011-2-24
 */
Keke_lang::load_lang_class('keke_global');

class Keke_global {
	

	public static function get_value_add_type(){
		global $_lang;
			return array ("workhide" => $_lang['workhide'], "top" => $_lang['task_top'], "urgent" => $_lang['task_urgent'],"map"=>$_lang['map_locate']);
	}
	public static function get_payitem_type(){
		global $_lang;
		return array("task"=>$_lang['task_pub'],"work"=>$_lang['witkey_submit'],"task_service"=>$_lang['task_pub_goods_pub']);
	}
	public static function withdraw_status(){
		global $_lang;
		return array("0"=>$_lang['wait_audit'],"1"=>$_lang['has_success'],"2"=>$_lang['has_fail']);
	}
	/**
	 * message_send����
	 */
	public static function get_message_send_type(){
		global $_lang;
		return array(
			   array("1"=>"send_msg",
		             "2"=>"send_mail",
		             "3"=>"send_sms"
		             ),
				array("send_msg"=>$_lang['send_msg'],
		             "send_mail"=>$_lang['send_email'],
		             "send_sms"=>$_lang['send_mobile_sms']
		             )
		       
		);
	}
	/**
	 * ��Ϣ���ͷ����������
	 */
	public static function get_message_send_obj(){
		global $_lang;
		return array("task"=>$_lang['task'],"service"=>$_lang['goods'],"space"=>$_lang['space'],"user"=>$_lang['user'],"found"=>$_lang['funds'],'safe'=>$_lang['safe'],"trans"=>$_lang['rights']);
	} 
  	/**
	 * feed����
	 * 
	 */
	public static function get_feed_type(){
		global $_lang;
		return array("pub_task"=>$_lang['pub_task'],
		"join_work"=>$_lang['join_work'],
		"task_pay"=>$_lang['pay_task_cost'],
		"task_prom"=>$_lang['from_prom_task'],
		"vip"=>$_lang['become_vip'],
		"withdraw"=>$_lang['withdraw'],
		"work_accept"=>$_lang['task_bid'],
		"work_delay"=>$_lang['task_delay'],
		"add_service"=>$_lang['create_service'],
		'user_index'=>$_lang['website_feed'],
		"user_talent"=>$_lang['lastest_user_feed'],
		"index_all"=>$_lang['taking_place_in'],
		"bank_auth"=>$_lang['bank_auth'],
		"pub_work"=>$_lang['pub_work'],
		"realname_auth"=>$_lang['realname_auth'],
		"enterprise_auth"=>$_lang['enterprise_auth'],
		"email_auth"=>$_lang['email_auth'],
		"weibo_auth"=>$_lang['weibo_auth'],
		"realname_auth"=>$_lang['realname_auth'],
		"task_pay"=>$_lang['get_commission'],
		"default"=>$_lang['default']);
	}

	public static function get_event_status() {
		global $_lang;
		return array ("0" => $_lang['has_grant'], "1" => $_lang['not_grant'])
		;
	}
    public static function get_tag_type() {
    	global $_lang;
		return array ( 
		"1" => array("1"=>$_lang['task'],"2"=>"task"),
		"2" =>array("1"=>$_lang['articles'],"2"=>"article"),
		"3" =>array("1"=>$_lang['task_class'],"2"=>"category"),
		"4" =>array("1"=>$_lang['self_defined_sql'],"2"=>"autosql"),
		"5" =>array("1"=>$_lang['self_defined_code'],"2"=>"autocode"),
		"6" =>array("1"=>$_lang['goods'],"2"=>"service"),
		//"7" =>array("1"=>$_lang['articles_class'],"2"=>"artcate"),
		)
		;
	}

	
	public static function get_open_api(){
		global $_lang;
		$r = array(
		'sina'=>array('name'=>$_lang['sina_weibo'],'ico'=>'sina'),
		'ten'=>array('name'=>$_lang['tenxun_weibo'],'ico'=>'ten'),
		'qq'=>array('name'=>$_lang['qq_number'],'ico'=>'qq'), 
		'taobao'=>array('name'=>$_lang['taobao'],'ico'=>'taobao'), 
		'netease'=>array('name'=>$_lang['wangyi_weibo'],'ico'=>'netease'),
		'sohu'=>array('name'=>$_lang['sohu_weibo'],'ico'=>'sohu'),
		'alipay'=>array('name'=>$_lang['alipay'],'ico'=>'alipay'),
	);
		return $r;
	} 
	
	public static function get_bank(){
		global $_lang;
		 return array (
		 'aboc' => $_lang['aboc'], 
		 'ccb' => $_lang['ccb'], 
		 'icbc' =>$_lang['icbc'], 
		 'cmb' => $_lang['cmb'],
		 'bocm' => $_lang['bocm'],
		 'spdb' => $_lang['spdb'],
		 'cmbc' => $_lang['cmbc'],
		 'ccb' => $_lang['ccb'],
		 'psbc' => $_lang['psbc'],
		 'cib' => $_lang['cib'], 
		 'hx' => $_lang['huaxia_bank'],
		 'boc'=>$_lang['boc'],
		 'tenpay'=>$_lang['tenpay'],
	 	 'alipayjs'=>$_lang['alipayjs'],
	 	 'yeepay'=>$_lang['yeepay'],
		 'chinabank'=>$_lang['chinabank'],
		 'paypal'=>$_lang['paypal'],
		 'boc'=>$_lang['boc'],
		 );
	} 
	/**
	 * ���м��
	 * @return multitype:string
	 */
	public static function get_bank_code(){
		global $_lang;
		return array(
				'1'=>'aboc',//ũ��
				'2'=>'ccb',//����
				'3'=>'ceb',//�������
				'4'=>'cmbc',//��������
				'5'=>'bocm',//����
				'6'=>'gdb',//�㷢
				'7'=>'ccb',//����
				'8'=>'sdb',//���ڷ�չ����
				'9'=>'spdb',//�Ϻ��ֶ���չ	
				'10'=>'icbc',//����
				'11'=>'bob',//��������
				'12'=>'cib',//��ҵ����
				'13'=>'gyl',//������
				'14'=>'udpay',//����ͨ
				'15'=>'icbcq',//����������ҵ	
				'16'=>'boc',//�й�����
				'17'=>'cmb',//��������
				'18'=>'pa',//ƽ������
				'19'=>'boc',//�й�����
				'20'=>'bos',//�Ϻ�����
				'21'=>'nbcb',//��������
				'22'=>'hx',//��������
				'23'=>'hkb',//��������
				'24'=>'njcb',//�Ͼ�����
				'25'=>'jsb',//��������
				'26'=>'hzb',//��������
				'27'=>'psbc',//�й���������
				);
	}
	 	
	/**
	 * ģ������
	 */
	public static function get_model_type(){
		global $_lang;
		return array("mreward"=>$_lang['more_reward'],"preward"=>$_lang['piece_reward'],"sreward"=>$_lang['single_reward'],"dtender"=>$_lang['deposit_tender'],"tender"=>$_lang['normal_tender'],"goods"=>$_lang['witkey_goods'],"service"=>$_lang['witkey_service'],"match"=>$_lang['match'],'wbzf'=>$_lang['wbzf'],'wbdj'=>$_lang['wbdj'],'taobao'=>$_lang['taobao']);	
	}
	
	/**
	 * ��ֵ��������
	 */
	public static function get_charge_type(){
		global $_lang;
		return array("online"=>$_lang['online_recharge'],"offline"=>$_lang['offline_recharge'],"task"=>$_lang['task_recharge'],"delay"=>$_lang['fare_delay']);
		
	}
	/**
	 * �����Ǽ�
	 * @return array
	 */
	public static function get_mark_star(){
		global $_lang;
		return array("1"=>$_lang['one_star'],"2"=>$_lang['two_star'],"3"=>$_lang['three_star'],"4"=>$_lang['four_star'],"5"=>$_lang['five_star']);
	}
	
	/**
	 * 
	 * ��ȡaouth��¼��ʽ
	 */
	public static function get_oauth_type(){
		global $_lang;
		return array(
		'sina'=>array('name'=>$_lang['sina_weibo'],'ico'=>'sina'),
		'ten'=>array('name'=>$_lang['tenxun_weibo'],'ico'=>'ten'),
		'qq'=>array('name'=>$_lang['tenxun_qq'],'ico'=>'qq'), 
		'netease'=>array('name'=>$_lang['wangyi_weibo'],'ico'=>'netease'),
		'sohu'=>array('name'=>$_lang['sohu_weibo'],'ico'=>'sohu'),
		'taobao'=>array('name'=>$_lang['taobao'],'ico'=>'taobao'),
 
		);
	}


	/**
	 * 
	 * ��ȡ�������� 
	 */
	public static function get_task_type(){ 
		global $_lang;
		
		return array("1"=>$_lang['single_reward'],"2"=>$_lang['more_reward'],"3"=>$_lang['piece_reward'],"4"=>$_lang['normal_tender'],"5"=>$_lang['deposit_tender'],"6"=>$_lang['works'],"7"=>$_lang['service']); 
		
	}
	
	public static function get_fina_charge_type(){
		global $_lang;
		return array("user_charge"=>$_lang['online_recharge'],
					 "offline_charge"=>$_lang['offline_recharge'],
					 'order_charge'=>$_lang['order_charge'],
					'admin_charge'=>$_lang['admin_charge']);
	}
	
	/**
	 *
	 * ��ȡ�ղ�����
	 */
	static function get_favor_type(){
		global $_lang;
		return array("task"=>$_lang['task'],"work"=>$_lang['work'],"shop"=>$_lang['shop'],"case"=>$_lang['case'],'service'=>$_lang['goods']);
	}
 
	/**
	 * 
	 * ��ȡ��ҵ�ռ���ͼƬ·�� 
	 */
	public static function  get_e_space_style(){
		global $_lang;
		return array(
					"default"=>"data/uploads/space/e_default.jpg",
					"hs"=>"data/uploads/space/e_hs.jpg",
					"js"=>"data/uploads/space/e_js.jpg",
					"qy"=>"data/uploads/space/e_qy.jpg",
					"ty"=>"data/uploads/space/e_ty.jpg",
					"zs"=>"data/uploads/space/e_zs.jpg");
	}
	/**
	 * 
	 * ��ȡ��ҵ�ռ���ͼƬ���� 
	 */
	public static function  get_e_space_name(){
		global $_lang;
		return array("default"=>$_lang['bule_classic'],"hs"=>$_lang['gray_country'],"js"=>$_lang['golden_boundless'],"qy"=>$_lang['akiba_story'],"ty"=>$_lang['days_wing'],"zs"=>$_lang['purple_country']);
	}
	/**
	 * 
	 * ��ȡ���˿ռ���ͼƬ·�� 
	 */
	public static function  get_p_space_style(){
		global $_lang;
		return array("default"=>"data/uploads/space/p_default.jpg",
							"bh"=>"data/uploads/space/p_bh.jpg",
							"lsjd"=>"data/uploads/space/p_lsjd.jpg",
							"lj"=>"data/uploads/space/p_lj.jpg",
							"qxy"=>"data/uploads/space/p_qxy.jpg",
							"qxyy"=>"data/uploads/space/p_qxyy.jpg");
	}
	/**
	 * 
	 * ��ȡ���˿ռ���ͼƬ���� 
	 */
	public static function  get_p_space_name(){
		global $_lang;
		return array("default"=>$_lang['default'],"bh"=>$_lang['lily'],"lsjd"=>$_lang['bule_classic'],"lj"=>$_lang['lj'],"qxy"=>$_lang['qxy'],"qxyy"=>$_lang['qxyy']);
	}
	
	
	/**
	 * 
	 * ��ȡ��������
	 */
	
	public static function get_file_type(){ 
		global $_lang;
		return array("task"=>$_lang['task_attachment'],"work"=>$_lang['work_attachment'],"agreement"=>$_lang['agreement_attachment'],"user_cert"=>$_lang['auth_attachment'],"space"=>$_lang['user_space']);
	
	}
	/**
	 * 
	 * ÿ��ģ������״̬������
	 */
	public static function get_taskstatus_desc(){
		global $_lang;
		return array (
			
				"2" =>array("desc"=>$_lang['submit_deadline'],"time"=>"sub_time") ,
				"3" =>array("desc"=>$_lang['choose_end'],"time"=>"end_time"), 
				"4" =>array("desc"=>$_lang['vote_end'],"time"=>""),
				"5" =>array("desc"=>$_lang['publicity_end'],"time"=>""),
				"6" =>array("desc"=>$_lang['delivery'],"time"=>""),
				"7" =>array("desc"=>$_lang['freezing'],"time"=>""),
				"8" =>array("desc"=>$_lang['has_end'],"time"=>"end_time"),
				"9" =>array("desc"=>$_lang['fail'],"time"=>"")
			 
				 
				 
			
		);
	 
	}

	/**
	 * 
	 *	��ֵ����ʱ�䣬˳������
	 *	������������
	 *		  ��һ��ʱ����top����ʱ��
	 *		 �ڶ���ʱ�����Ӽ��Ľ���ʱ��
	 *		�Դ����ƣ�
	 *		ע��(�������ֵ��������Ҫ���´�����)
	 */
	public static function get_payitem_arr(){
		$payitem_arr = array("top","urgent");
		return $payitem_arr;
	}
	/**
	 * �����������ֻ��ɺ���
	 * @param number $num ����(�����ֵ,���ض�Ӧ��ֵ,û�з�������)
	 * @return array/string 
	 */
	public static function num2ch($num=''){
		$ch_arr = array(1=>'һ', 2=>'��', 3=>'��', 4=>'��', 5=>'��', 6=>'��', 7=>'��', 8=>'��', 9=>'��', 10=>'ʮ');
		if ($num!='' && array_key_exists((int)$num, $ch_arr)){
			return $ch_arr[(int)$num];
		}
		return $ch_arr;
	}

}