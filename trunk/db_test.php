<?php
//$star = microtime(true);
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

$e = -1;
$err = array(
		'-1'=>'�û������������',
		'-2'=>'����',
		'-3'=>'����̫�������ܳ���1000��һ���ύ',
		'-4'=>'�޺Ϸ�����',
		'-5'=>'���ݰ������Ϸ�����',
		'-6'=>'����̫��',
		'-7'=>'����Ϊ��',
		'-8'=>'��ʱʱ���ʽ����',
		'-9'=>'�޸�����ʧ��',
		'-10'=>'�û���ǰ���ܷ��Ͷ���',
		'-11'=>'Action��������ȷ',
		'-100'=>'ϵͳ����'
); 
 
$message = array(':e'=>$e,':err'=>$err[$e]);
Keke::$_log->add(Log::WARNING,"������::e,��ϸ::err", $message)->write();

die;
 
if($_POST){
	 
/* 	if(  Keke::formcheck($formhash) ){
	  
	// 	����֤���÷�
		//$c = Keke_valid::email($code);
	    $p = Keke_validation::factory($_POST)->rule('code', 'Keke_valid::email',array(':value',$code))
	    //��֤�����ֶΡ�,��֤���ʽ,�ֶε�ֵ
	    ->rule('ip', 'Keke_valid::ip',array(':value',$ip))
	    ->rule('url', 'Keke_valid::url',array(':value',$url))
	    ->rule('phone', 'Keke_valid::phone',array(':value',$phone));
	    if($p->check()){
	    	Keke::show_msg('title','db_test.php','success','right');
	    }
	    	
	    $e = $p->errors();
	    
	   var_dump($e);
	   die();
		
		
	}  */ 
}	 
	//  die();
	 //Keke_captcha::valid($code);
 
/* }else{
	//��֤����÷�
	$img =  Keke_captcha::instance()->render();
} */
 
/*  $b = array('ad_type','ad_name');
 $a  = array('9','update_name');
 $c = array_combine($b, $a);
 var_dump($c);
die(); 
 */
///$res = Model::factory('witkey_ad')->setData(array('ad_name'=>'sdsdsd','ad_content'=>'content'))->create();

//var_dump($res);
//�����ɾ������
// $res = DB::delete()->table('witkey_ad')->where('ad_id = :id')->param(":id", "261")->execute();
//���󻯸���
/* $res = DB::update()->table('witkey_ad')
		->set(array('ad_type','ad_name'))
	  	->value(array('19','name'))
		->where('ad_id = 264')->execute(); */


//���󻯲���
//$res = DB::insert()->into('witkey_ad')->set(array('ad_name','ad_content'))->value(array('10','ad_insert'))->execute();


//$res = DB::delete('witkey_ad')->where('ad_id=266')->execute();






/* $aas=new keke_witkey_ad();
$aas->setAd_content($value)->setAd_file($value)->setAd_name($value)->create(); */

// Cache::instance()->generate_id($id)->set(null, $val);

//DB::query($sql)->param($param, $value)->cached()->execute();

// DB::select()->from($table)->where($where)->execute();

/* Keke::$_log->add(Log::STRACE, 'debug_test')->write(); */


//var_dump($res);



/* $end = microtime(true);
echo $end-$star; */
//var_dump ( $end-$star,Keke::execute_time() );
//require Keke_tpl::template('en');

// $end = microtime(true);
 //var_dump ( $end-$star,Keke::execute_time() );

require Keke_tpl::template('en');
var_dump ( $end-$star,Keke::execute_time() );


 