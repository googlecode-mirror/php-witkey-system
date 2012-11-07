<?php	defined ( 'IN_KEKE' ) or die ( 'Access Denied' );

/**
 * ��֤�����
 * 
 * @author Administrator
 *        
 */
abstract class Keke_captcha {
	/**
	 *
	 * @var object ����
	 */
	public static $instance;
	
	/**
	 *
	 * @var string Style-dependent Keke_captcha driver
	 */
	// protected $driver;
	
	/**
	 *
	 * @var array Ĭ������
	 */
	public static $config = array (
			'style' => 'basic',
			'width' => 150,
			'height' => 50,
			'complexity' => 4,
			'background' => '',
			'fontpath' => '',
			'fonts' => array (),
			'promote' => false 
	);
	
	/**
	 *
	 * @var string ��֤���ַ���
	 */
	protected $response;
	
	/**
	 *
	 * @var string Image ͼ����Դ
	 */
	protected $image;
	
	/**
	 *
	 * @var string Image ͼ������ ("png", "gif" or "jpeg")
	 */
	protected $image_type = 'png';
	
	/**
	 * ��ȡ��֤�����ĵ���
	 *
	 * @param $style string
	 *        	��������(alpha,basic,black,math,riddle,word);
	 * @return Keke_captcha_basic
	 */
	public static function instance($w=150,$h=50,$style = 'basic') {
		if (isset ( Keke_captcha::$instance )) {
			return Keke_captcha::$instance;
		}
		// ��ȡ����
		$config = Keke_captcha_config::get ();
		// ������֤�������
		$class = 'Keke_captcha_'.$style;
		
		// ����ʵ��
		Keke_captcha::$instance = $Keke_captcha = new $class ( 'default',$w,$h );
		
		// �ر�ʵ��ʱ����session
		 register_shutdown_function ( array (
				$Keke_captcha,
				'update_response_session' 
		) ); 
		
		return Keke_captcha::$instance;
	}
	
	public function __construct($group = NULL,$w,$h) {
		
		empty ( Keke_captcha::$instance ) and Keke_captcha::$instance = $this;
		
		// ����Ĭ�ϵ�����
		if ($group=== NULL) {
			$group = 'default';
		}
		
		// �������ã������׳��쳣
		if (! is_array ( $config = Keke_captcha_config::get ( $group ) ))
			throw new Keke_exception ( 'Keke_captcha group not defined in :group configuration', array (
					':group' => $group 
			) );
			
			 
		if ($group !== 'default') {
			 
			if (! is_array ( $default = Keke_captcha_config::get ( 'default' ) ))
				throw new Keke_exception ( 'Keke_captcha group not defined in :group configuration', array (
						':group' => 'default' 
				) );
				
				// �ϲ�������Ϣ
			$config += $default;
		}
		
		// �ع����õĽ�ֵ��
		foreach ( $config as $key => $value ) {
			if (array_key_exists ( $key, Keke_captcha::$config )) {
				Keke_captcha::$config [$key] = $value;
			}
		}
		
	    if($w){
	    	Keke_captcha::$config['width'] = $w;
	    }
	    if($h){
	    	Keke_captcha::$config['height'] = $h;
	    }
	    
		Keke_captcha::$config ['group'] = $group;
		
		// �жϱ���ͼƬ�Ƿ����
		if (! empty ( $config ['background'] )) {
			Keke_captcha::$config ['background'] = str_replace ( '\\', '/', realpath ( $config ['background'] ) );
			
			if (! is_file ( Keke_captcha::$config ['background'] ))
				throw new Keke_exception ( 'The specified file, :file, was not found.', array (
						':file' => Keke_captcha::$config ['background'] 
				) );
		}
		
		// �ж�ָ���������ļ��Ƿ����
		if (! empty ( $config ['fonts'] )) {
			Keke_captcha::$config ['fontpath'] = str_replace ( '\\', '/', realpath ( $config ['fontpath'] ) ) . '/';
			
			foreach ( $config ['fonts'] as $font ) {
				if (! is_file ( Keke_captcha::$config ['fontpath'] . $font ))
					throw new Keke_exception ( 'The specified file, :file, was not found.', array (
							':file' => Keke_captcha::$config ['fontpath'] . $font 
					) );
			}
		}
		
		// �����µ��ַ���
		$this->response = $this->generate_challenge ();
	}
	
	/**
	 * ���������Session���
	 *
	 * @return void
	 */
	public function update_response_session() {
		
		// ��ȡSession ��ֵ
		$_SESSION ['Keke_captcha_response'] = sha1 ( strtoupper ( $this->response ) );
	}
	
	/**
	 * ��֤�û��������֤�룬���Ҹ����������Ĵ���
	 *
	 * @staticvar integer $counted Keke_captcha ͳ�Ʊ��
	 * @param $response string     �û������ֵ
	 * @return boolean
	 */
	public static function valid($response) {
		// ÿҳ����ͳ�ƴ���
		
		static $counted = null;
		
		// �����������ȡ���ƣ��������κ�ͳ��
		if (Keke_captcha::instance ()->promoted ()){
			return TRUE;
		}
		//var_dump($_SESSION);die;	
		// ����ж�
		$result = ( bool ) (sha1 ( strtoupper ( $response ) ) === $_SESSION ['Keke_captcha_response']);
		
		// ������+1
		if ($counted !== TRUE) {
			$counted = TRUE;
			
			// ��֤����
			if ($result === TRUE) {
				//��Ч����+1
				self::$instance->valid_count ( $_SESSION ['Keke_captcha_valid_count'] + 1 );
			}else {
				//��Ч����+1
				self::$instance->invalid_count ( $_SESSION ['Keke_captcha_invalid_count'] + 1 );
			}
		}
		
		return $result;
	}
	
	/**
	 * ͨ��Session ��ȡ��֤�Ĵ���
	 *
	 * @param $new_count integer   ���µ�ͳ�ƴ���
	 * @param $invalid boolean    �Ƿ��ȡ��Ч�ļ���
	 * @return integer ͳ��ֵ
	 */
	public function valid_count($new_count = NULL, $invalid = FALSE) {
		// �Ե�Session ����
		$session = ($invalid === TRUE) ? 'Keke_captcha_invalid_count' : 'Keke_captcha_valid_count';
		
		// ����ͳ�ƴ���
		if ($new_count !== NULL) {
			$new_count = ( int ) $new_count;
			
			// ���ü����� = ɾ�� session
			if ($new_count < 1) {
				unset ( $_SESSION [$session] );
			} 			// ���ü���������ֵ
			else {
				$_SESSION [$session] = ( int ) $new_count;
			
			}
			
			// �����µ�ͳ��
			return ( int ) $new_count;
		}
		
		// ���ص�ǰͳ��
		return ( int ) $_SESSION [$session];
	}
	
	/**
	 * get or set ��Ч��ͳ��ֵ
	 *
	 * @param $new_count integer  �µĴ���
	 * @return integer 
	 */
	public function invalid_count($new_count = NULL) {
		return $this->valid_count ( $new_count, TRUE );
	}
	
	/**
	 * ������Ӧ���󣬲�ɾ����Ӧ��Session
	 *
	 * @return void
	 */
	public function reset_count() {
		$this->valid_count ( 0 );
		$this->valid_count ( 0, TRUE );
	}
	
	/**
	 * �Ƿ��ж���֤����
	 * responses.
	 *
	 * @param $threshold integer
	 *        	Valid response count threshold
	 * @return boolean
	 */
	public function promoted($threshold = NULL) {
		// Promotion has been disabled
		if (Keke_captcha::$config ['promote'] === FALSE)
			return FALSE;
			
			// Use the config threshold
		if ($threshold === NULL) {
			$threshold = Keke_captcha::$config ['promote'];
		}
		
		// Compare the valid response count to the threshold
		return ($this->valid_count () >= $threshold);
	}
	
	/**
	 * ���ͼƬ
	 *
	 * @return mixed
	 */
	public function __toString() {
		return $this->render ( TRUE );
	}
	
	/**
	 * ����ͼ������
	 *
	 * @param $filename string  �ļ���
	 * @return string boolean type ("png", "gif" or "jpeg")
	 */
	public function image_type($filename) {
		switch (strtolower ( substr ( strrchr ( $filename, '.' ), 1 ) )) {
			case 'png' :
				return 'png';
			
			case 'gif' :
				return 'gif';
			
			case 'jpg' :
			case 'jpeg' :
				//����jpeg����ΪGD2�ķ�������jpeg
				return 'jpeg';
			
			default :
				return FALSE;
		}
	}
	
	/**
	 * ����һ��ͼ����Դ
	 * ����б���ͼ�Ļ���Ҳ֧��
	 *
	 * @throws Kohana_Exception If no GD2 support
	 * @param $background string     ����ͼ��Ƭ��·��
	 * @return void
	 */
	public function image_create($background = NULL) {
		// �ж��Ƿ�֧��GD2
		if (! function_exists ( 'imagegd2' ))
			throw new Keke_exception ( 'Keke_captcha.requires_GD2' );
			
		// ����һ������ͼ (black)
		$this->image = imagecreatetruecolor ( Keke_captcha::$config ['width'], Keke_captcha::$config ['height'] );
		
		// ʹ�ñ���ͼƬ
		if (! empty ( $background )) {
			// ����ͼƬ��ʹ�öԾ��ڵķ���
			$function = 'imagecreatefrom' . $this->image_type ( $background );
			$this->background_image = $function ( $background );
			
			// ����ͼƬ��С�������Ҫ
			if (imagesx ( $this->background_image ) !== Keke_captcha::$config ['width'] or imagesy ( $this->background_image ) !== Keke_captcha::$config ['height']) {
				imagecopyresampled ( $this->image, $this->background_image, 0, 0, 0, 0, Keke_captcha::$config ['width'], Keke_captcha::$config ['height'], imagesx ( $this->background_image ), imagesy ( $this->background_image ) );
			}
			
			// �ͷ���Դ
			imagedestroy ( $this->background_image );
		}
	}
	
	/**
	 * ��䱳��ͼ
	 *
	 * @param $color1 resource       ��ʼ��ɫ
	 * @param $color2 resource       ������ɫ
	 * @param $direction string      ˮƽ����ַ����������ַ�ʽ
	 * @return void
	 */
	public function image_gradient($color1, $color2, $direction = NULL) {
		$directions = array (
				'horizontal',
				'vertical' 
		);
		
		// ���ѡ��һ������
		if (! in_array ( $direction, $directions )) {
			$direction = $directions [array_rand ( $directions )];
			
			// ��ɫ����
			if (mt_rand ( 0, 1 ) === 1) {
				$temp = $color1;
				$color1 = $color2;
				$color2 = $temp;
			}
		}
		
		// Extract RGB values
		$color1 = imagecolorsforindex ( $this->image, $color1 );
		$color2 = imagecolorsforindex ( $this->image, $color2 );
		
		// Preparations for the gradient loop
		$steps = ($direction === 'horizontal') ? Keke_captcha::$config ['width'] : Keke_captcha::$config ['height'];
		
		$r1 = ($color1 ['red'] - $color2 ['red']) / $steps;
		$g1 = ($color1 ['green'] - $color2 ['green']) / $steps;
		$b1 = ($color1 ['blue'] - $color2 ['blue']) / $steps;
		
		if ($direction === 'horizontal') {
			$x1 = & $i;
			$y1 = 0;
			$x2 = & $i;
			$y2 = Keke_captcha::$config ['height'];
		} else {
			$x1 = 0;
			$y1 = & $i;
			$x2 = Keke_captcha::$config ['width'];
			$y2 = & $i;
		}
		
		// �����Ⱦ
		for($i = 0; $i <= $steps; $i ++) {
			$r2 = $color1 ['red'] - floor ( $i * $r1 );
			$g2 = $color1 ['green'] - floor ( $i * $g1 );
			$b2 = $color1 ['blue'] - floor ( $i * $b1 );
			$color = imagecolorallocate ( $this->image, $r2, $g2, $b2 );
			
			imageline ( $this->image, $x1, $y1, $x2, $y2, $color );
		}
	}
	
	/**
	 * �����֤��
	 *  html �ĵ�����index.php/do=captcha
	 * @param $html boolean  ���html,�������ͼƬ
	 * @return mixed Image, void
	 */
	public function image_render($html) {
		
		if ($html === TRUE) {
			return '<img src="'.BASE_URL.'/index.php/captcha" width="' . Keke_captcha::$config ['width'] . '" height="' . Keke_captcha::$config ['height'] . '" id="Keke_captcha" class="Keke_captcha" />';
		}
		header ( "Expires: Sun, 1 Jan 2000 12:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . "GMT" );
		header ( "Cache-Control: no-store, no-cache, must-revalidate" );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		header ( "Pragma: no-cache" );
		header ( 'Content-Type: image' . $this->image_type );
		$f = 'image' . $this->image_type;
		$f ( $this->image );
		
		imagedestroy ( $this->image );
	}
	
	/**
	 * �����ַ���
	 *
	 * @return string ����룬���Ƕ�Ӧ���ַ���
	 */
	abstract public function generate_challenge();
	
	/**
	 * ����ͼƬ
	 *
	 * @param $html boolean ��Ⱦ��ͼ���ǩ
	 * @return mixed
	 */
	abstract public function render($html = TRUE);

} 
