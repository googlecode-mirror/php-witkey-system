<?php	defined ( "IN_KEKE" ) or die ( "Access Denied" );

class Keke_captcha_config {
   public static function get($style='default'){
   	
   	$a =  array(
   			'default' => array(
   					'style'      	=> 'riddle',
   					'width'      	=> 100,
   					'height'     	=> 30,
   					'complexity' 	=> 4,
   					'background' 	=> '',
   					'fontpath'   	=> S_ROOT.'resource/img/gdfonts/',
   					'fonts'      	=> array('DejaVuSerif.ttf'),
   					'promote'    	=> FALSE,
   			),
   			// Words of varying length for Captcha_Word to pick from
   			// Note: use only alphanumeric characters
   			'words' => array
   			(
   					'cd', 'tv', 'it', 'to', 'be', 'or',
   					'sun', 'car', 'dog', 'bed', 'kid', 'egg',
   					'bike', 'tree', 'bath', 'roof', 'road', 'hair',
   					'hello', 'world', 'earth', 'beard', 'chess', 'water',
   					'barber', 'bakery', 'banana', 'market', 'purple', 'writer',
   					'america', 'release', 'playing', 'working', 'foreign', 'general',
   					'aircraft', 'computer', 'laughter', 'alphabet', 'kangaroo', 'spelling',
   					'architect', 'president', 'cockroach', 'encounter', 'terrorism', 'cylinders',
   			),
   			// Riddles for Captcha_Riddle to pick from
   			// Note: use only alphanumeric characters
   			'riddles' => array
   			(
   					array('中国的首都? (重庆 or 北京)', '北京'),
   					array('Do you hate spam? (yes or no)', 'yes'),
   					array('Are you a robot? (yes or no)', 'no'),
   					array('Fire is... (hot or cold)', 'hot'),
   					array('The season after fall is...', 'winter'),
   					array('Which day of the week is it today?', strftime('%A')),
   					array('Which month of the year are we in?', strftime('%B')),
   			),
   	);
   	return $a[$style];
   }
}
