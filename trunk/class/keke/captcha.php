<?php

defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * Keke_captcha abstract class.
 *
 * @package Keke_captcha
 * @author Michael Lavers
 * @author Kohana Team
 * @copyright (c) 2008-2010 Kohana Team
 * @license http://kohanaphp.com/license.html
 */
abstract class Keke_captcha {
	/**
	 *
	 * @var object Keke_captcha singleton
	 */
	public static $instance;
	
	/**
	 *
	 * @var string Style-dependent Keke_captcha driver
	 */
	protected $driver;
	
	/**
	 *
	 * @var array Default config values
	 */
	public static $config = array ('style' => 'basic', 'width' => 150, 'height' => 50, 'complexity' => 4, 'background' => '', 'fontpath' => '', 'fonts' => array (), 'promote' => FALSE );
	
	/**
	 *
	 * @var string The correct Keke_captcha challenge answer
	 */
	protected $response;
	
	/**
	 *
	 * @var string Image resource identifier
	 */
	protected $image;
	
	/**
	 *
	 * @var string Image type ("png", "gif" or "jpeg")
	 */
	protected $image_type = 'png';
	
	/**
	 * Singleton instance of Keke_captcha.
	 *
	 * @param $group string
	 *       	 Config group name
	 * @return Keke_captcha_basic
	 */
	public static function instance($style = 'default') {
		if (! isset ( Keke_captcha::$instance )) {
			// Load the configuration for this group
			$config = Keke_captcha_config::get ( $style );
			// Set the Keke_captcha driver class name
			$class = 'Keke_captcha_' . $config ['style'];
			
			// Create a new Keke_captcha instance
			Keke_captcha::$instance=$Keke_captcha= new $class ( $style );
			
			// Save Keke_captcha response at shutdown
			register_shutdown_function(array($Keke_captcha,'update_response_session'));
		}
		return Keke_captcha::$instance;
	}
	
	/**
	 * Constructs a new Keke_captcha object.
	 *
	 * @throws Kohana_Exception
	 * @param
	 *       	 string Config group name
	 * @return void
	 */
	public function __construct($group = NULL) {
		// Create a singleton instance once
		empty ( Keke_captcha::$instance ) and Keke_captcha::$instance = $this;
		
		// No config group name given
		if (! is_string ( $group )) {
			$group = 'default';
		}
		
		// Load and validate config group
		if (! is_array ( $config = Keke_captcha_config::get ( $group ) ))
			throw new keke_exception ( 'Keke_captcha group not defined in :group configuration', array (':group' => $group ) );
			
			// All Keke_captcha config groups inherit default config group
		if ($group !== 'default') {
			// Load and validate default config group
			if (! is_array ( $default = Keke_captcha_config::get ( 'default' ) ))
				throw new keke_exception ( 'Keke_captcha group not defined in :group configuration', array (':group' => 'default' ) );
				
				// Merge config group with default config group
			$config += $default;
		}
		
		// Assign config values to the object
		foreach ( $config as $key => $value ) {
			if (array_key_exists ( $key, Keke_captcha::$config )) {
				Keke_captcha::$config [$key] = $value;
			}
		}
		
		// Store the config group name as well, so the drivers can access it
		Keke_captcha::$config ['group'] = $group;
		
		// If using a background image, check if it exists
		if (! empty ( $config ['background'] )) {
			Keke_captcha::$config ['background'] = str_replace ( '\\', '/', realpath ( $config ['background'] ) );
			
			if (! is_file ( Keke_captcha::$config ['background'] ))
				throw new keke_exception ( 'The specified file, :file, was not found.', array (':file' => Keke_captcha::$config ['background'] ) );
		}
		
		// If using any fonts, check if they exist
		if (! empty ( $config ['fonts'] )) {
			Keke_captcha::$config ['fontpath'] = str_replace ( '\\', '/', realpath ( $config ['fontpath'] ) ) . '/';
			
			foreach ( $config ['fonts'] as $font ) {
				if (! is_file ( Keke_captcha::$config ['fontpath'] . $font ))
					throw new keke_exception ( 'The specified file, :file, was not found.', array (':file' => Keke_captcha::$config ['fontpath'] . $font ) );
			}
		}
		
		// Generate a new challenge
		$this->response = $this->generate_challenge ();
	}
	
	/**
	 * Update Keke_captcha response session variable.
	 *
	 * @return void
	 */
	public function update_response_session() {
		// Store the correct Keke_captcha response in a session
		$_SESSION ['Keke_captcha_response'] = sha1 ( strtoupper ( $this->response ) );
	}
	
	/**
	 * Validates user's Keke_captcha response and updates response counter.
	 *
	 * @staticvar integer $counted Keke_captcha attempts counter
	 * @param $response string
	 *       	 User's Keke_captcha response
	 * @return boolean
	 */
	public static function valid($response) {
		// Maximum one count per page load
		
		static $counted = null;
		
		// User has been promoted, always TRUE and don't count anymore
		if (Keke_captcha::instance ()->promoted ())
			return TRUE;
			
			// Challenge result
		$result = ( bool ) (sha1 ( strtoupper ( $response ) ) === $_SESSION ['Keke_captcha_response']);
		 
		// Increment response counter
		if ($counted !== TRUE) {
			$counted = TRUE;
			
			// Valid response
			if ($result === TRUE) {
				Keke_captcha::instance ()->valid_count ( $_SESSION ['Keke_captcha_valid_count'] + 1 );
			} 			// Invalid response
			else {
				Keke_captcha::instance ()->invalid_count ( $_SESSION ['Keke_captcha_invalid_count'] + 1 );
			}
		}
		
		return $result;
	}
	
	/**
	 * Gets or sets the number of valid Keke_captcha responses for this session.
	 *
	 * @param $new_count integer
	 *       	 New counter value
	 * @param $invalid boolean
	 *       	 Trigger invalid counter (for internal use only)
	 * @return integer Counter value
	 */
	public function valid_count($new_count = NULL, $invalid = FALSE) {
		// Pick the right session to use
		$session = ($invalid === TRUE) ? 'Keke_captcha_invalid_count' : 'Keke_captcha_valid_count';
		
		// Update counter
		if ($new_count !== NULL) {
			$new_count = ( int ) $new_count;
			
			// Reset counter = delete session
			if ($new_count < 1) {
				unset ( $_SESSION [$session] );
			} 			// Set counter to new value
			else {
				$_SESSION [$session] = ( int ) $new_count;
			
			}
			
			// Return new count
			return ( int ) $new_count;
		}
		
		// Return current count
		return ( int ) $_SESSION [$session];
	}
	
	/**
	 * Gets or sets the number of invalid Keke_captcha responses for this
	 * session.
	 *
	 * @param $new_count integer
	 *       	 New counter value
	 * @return integer Counter value
	 */
	public function invalid_count($new_count = NULL) {
		return $this->valid_count ( $new_count, TRUE );
	}
	
	/**
	 * Resets the Keke_captcha response counters and removes the count sessions.
	 *
	 * @return void
	 */
	public function reset_count() {
		$this->valid_count ( 0 );
		$this->valid_count ( 0, TRUE );
	}
	
	/**
	 * Checks whether user has been promoted after having given enough valid
	 * responses.
	 *
	 * @param $threshold integer
	 *       	 Valid response count threshold
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
	 * Magically outputs the Keke_captcha challenge.
	 *
	 * @return mixed
	 */
	public function __toString() {
		return $this->render ( TRUE );
	}
	
	/**
	 * Returns the image type.
	 *
	 * @param $filename string
	 *       	 Filename
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
				// Return "jpeg" and not "jpg" because of the GD2 function names
				return 'jpeg';
			
			default :
				return FALSE;
		}
	}
	
	/**
	 * Creates an image resource with the dimensions specified in config.
	 * If a background image is supplied, the image dimensions are used.
	 *
	 * @throws Kohana_Exception If no GD2 support
	 * @param $background string
	 *       	 Path to the background image file
	 * @return void
	 */
	public function image_create($background = NULL) {
		// Check for GD2 support
		if (! function_exists ( 'imagegd2' ))
			throw new keke_exception ( 'Keke_captcha.requires_GD2' );
			
			// Create a new image (black)
		$this->image = imagecreatetruecolor ( Keke_captcha::$config ['width'], Keke_captcha::$config ['height'] );
		
		// Use a background image
		if (! empty ( $background )) {
			// Create the image using the right function for the filetype
			$function = 'imagecreatefrom' . $this->image_type ( $background );
			$this->background_image = $function ( $background );
			
			// Resize the image if needed
			if (imagesx ( $this->background_image ) !== Keke_captcha::$config ['width'] or imagesy ( $this->background_image ) !== Keke_captcha::$config ['height']) {
				imagecopyresampled ( $this->image, $this->background_image, 0, 0, 0, 0, Keke_captcha::$config ['width'], Keke_captcha::$config ['height'], imagesx ( $this->background_image ), imagesy ( $this->background_image ) );
			}
			
			// Free up resources
			imagedestroy ( $this->background_image );
		}
	}
	
	/**
	 * Fills the background with a gradient.
	 *
	 * @param $color1 resource
	 *       	 GD image color identifier for start color
	 * @param $color2 resource
	 *       	 GD image color identifier for end color
	 * @param $direction string
	 *       	 Direction: 'horizontal' or 'vertical', 'random' by default
	 * @return void
	 */
	public function image_gradient($color1, $color2, $direction = NULL) {
		$directions = array ('horizontal', 'vertical' );
		
		// Pick a random direction if needed
		if (! in_array ( $direction, $directions )) {
			$direction = $directions [array_rand ( $directions )];
			
			// Switch colors
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
		
		// Execute the gradient loop
		for($i = 0; $i <= $steps; $i ++) {
			$r2 = $color1 ['red'] - floor ( $i * $r1 );
			$g2 = $color1 ['green'] - floor ( $i * $g1 );
			$b2 = $color1 ['blue'] - floor ( $i * $b1 );
			$color = imagecolorallocate ( $this->image, $r2, $g2, $b2 );
			
			imageline ( $this->image, $x1, $y1, $x2, $y2, $color );
		}
	}
	
	/**
	 * Returns the img html element or outputs the image to the browser.
	 *
	 * @param $html boolean
	 *       	 Output as HTML
	 * @return mixed HTML, string or void
	 */
	public function image_render($html) {
		if ($html === TRUE) {
			return '<img src="index.php?do=captcha" width="' . Keke_captcha::$config ['width'] . '" height="' . Keke_captcha::$config ['height'] . '" alt="Keke_captcha" class="Keke_captcha" />';
		}
		 
		header ( "Expires: Sun, 1 Jan 2000 12:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . "GMT" );
		header ( "Cache-Control: no-store, no-cache, must-revalidate" );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		header ( "Pragma: no-cache" );
		header('Content-Type: image'.$this->image_type);
		$f = 'image'.$this->image_type;
		$f($this->image);

		
		imagedestroy ( $this->image );
	}
	
	/*
	 * DRIVER METHODS
	 */
	
	/**
	 * Generate a new Keke_captcha challenge.
	 *
	 * @return string The challenge answer
	 */
	abstract public function generate_challenge();
	
	/**
	 * Output the Keke_captcha challenge.
	 *
	 * @param $html boolean
	 *       	 Render output as HTML
	 * @return mixed
	 */
	abstract public function render($html = TRUE);

} // End Keke_captcha Class
