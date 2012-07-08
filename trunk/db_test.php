<?php
//$star = microtime(true);
define ( 'IN_KEKE', TRUE );
include 'app_boot.php';

$john = array('name' => 'john', 'children' => array('fred', 'paul', 'sally', 'jane'));
  $mary = array('name' => 'mary', 'children' => array('jane'));
  
$m = array_merge($john,$mary);
$d = $john+$mary;
$c = merge($john, $mary);
var_dump($m,$d,$c);

  function merge(array $a1, array $a2)
{
	$result = array();
	for ($i = 0, $total = func_num_args(); $i < $total; $i++)
	{
	// Get the next array
	$arr = func_get_arg($i);

	// Is the array associative?
	$assoc = is_assoc($arr);

	foreach ($arr as $key => $val)
	{
	if (isset($result[$key]))
	{
	if (is_array($val) AND is_array($result[$key]))
	{
	if (is_assoc($val))
		{
		// Associative arrays are merged recursively
			$result[$key] = merge($result[$key], $val);
		}
		else
		{
		// Find the values that are not already present
		$diff = array_diff($val, $result[$key]);

		// Indexed arrays are merged to prevent duplicates
		$result[$key] = array_merge($result[$key], $diff);
		}
		}
		else
		{
		if ($assoc)
		{
		// Associative values are replaced
		$result[$key] = $val;
		}
			elseif ( ! in_array($val, $result, TRUE))
				{
				// Indexed values are added only if they do not yet exist
				$result[] = $val;
		}
		}
		}
		else
		{
		// New values are added
		$result[$key] = $val;
	}
	}
	}

		return $result;
	}
	function is_assoc(array $array)
	{
		// Keys of the array
		$keys = array_keys($array);
	
		// If the array keys of the keys match the keys, then the array must
		// not be associative (e.g. the keys array looked like {0:0, 1:1...}).
		return array_keys($keys) !== $keys;
	}
die();  

list($decimal) = array_values(localeconv());
$a = preg_match('/^-?+(?=.*[\d])[\d]*+'.preg_quote($decimal).'?+[\d]*+$/D', (string) $str,$out);
 

$img =  Keke_captcha::instance()->render();

 
if($_POST){
 
	 
	  if(  Keke::formcheck($formhash)){
	  $a = Keke_captcha::valid($code);
	 	var_dump($a);
	 }  
	 
	  die();
	 //Keke_captcha::valid($code);
 
}
 
/*  $b = array('ad_type','ad_name');
 $a  = array('9','update_name');
 $c = array_combine($b, $a);
 var_dump($c);
die(); 
 */
///$res = Model::factory('witkey_ad')->setData(array('ad_name'=>'sdsdsd','ad_content'=>'content'))->create();

//var_dump($res);
//对象对删除测试
// $res = DB::delete()->table('witkey_ad')->where('ad_id = :id')->param(":id", "261")->execute();
//对象化更新
/* $res = DB::update()->table('witkey_ad')
		->set(array('ad_type','ad_name'))
	  	->value(array('19','name'))
		->where('ad_id = 264')->execute(); */


//对象化插入
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
//require keke_tpl_class::template('en');

// $end = microtime(true);
 //var_dump ( $end-$star,Keke::execute_time() );

require keke_tpl_class::template('en');
var_dump ( $end-$star,Keke::execute_time() );


 