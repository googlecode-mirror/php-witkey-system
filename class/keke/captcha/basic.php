<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * Basic captcha class.
 *
 * @package		Captcha
 * @subpackage	Captcha_Basic
 * @author		Michael Lavers
 * @author		Kohana Team
 * @copyright	(c) 2008-2010 Kohana Team
 * @license		http://kohanaphp.com/license.html
 */
class Keke_captcha_basic extends Keke_captcha 
{
	/**
	 * Generates a new Captcha challenge.
	 *
	 * @return string The challenge answer
	 */
	public function generate_challenge()
	{
		// Complexity setting is used as character count
		$text = Keke::randomkeys(max(1, Keke_captcha::$config['complexity']));
		
		return $text;
	}

	/**
	 * Outputs the Captcha image.
	 *
	 * @param boolean $html HTML output
	 * @return mixed
	 */
	public function render($html = TRUE)
	{
		
		// Creates $this->image
		$this->image_create(Keke_captcha::$config['background']);

		// Add a random gradient
		if (empty(Keke_captcha::$config['background']))
		{
			$color1 = imagecolorallocate($this->image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(150, 255));
			$color2 = imagecolorallocate($this->image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(150, 255));
			$this->image_gradient($color1, $color2);
		}

		// Add a few random lines
		for ($i = 0, $count = mt_rand(5, Keke_captcha::$config['complexity'] * 4); $i < $count; $i++)
		{
			$color = imagecolorallocatealpha($this->image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(100, 255), mt_rand(50, 120));
			imageline($this->image, mt_rand(0, Keke_captcha::$config['width']), 0, mt_rand(0, Keke_captcha::$config['width']), Keke_captcha::$config['height'], $color);
		}

		// Calculate character font-size and spacing
		$default_size = min(Keke_captcha::$config['width'], Keke_captcha::$config['height'] * 2) / (strlen($this->response) + 1);
		$spacing = (int) (Keke_captcha::$config['width'] * 0.9 / strlen($this->response));

		// Draw each Captcha character with varying attributes
		for ($i = 0, $strlen = strlen($this->response); $i < $strlen; $i++)
		{
			// Use different fonts if available
			$font = Keke_captcha::$config['fontpath'].Keke_captcha::$config['fonts'][array_rand(Keke_captcha::$config['fonts'])];

			// Allocate random color, size and rotation attributes to text
			$color = imagecolorallocate($this->image, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
			$angle = mt_rand(-40, 20);

			// Scale the character size on image height
			$size = $default_size / 10 * mt_rand(8, 12);
			$box = imageftbbox($size, $angle, $font, $this->response[$i]);

			// Calculate character starting coordinates
			$x = $spacing / 4 + $i * $spacing;
			$y = Keke_captcha::$config['height'] / 2 + ($box[2] - $box[5]) / 4;

			// Write text character to image
			imagefttext($this->image, $size, $angle, $x, $y, $color, $font, $this->response[$i]);
		}
        
		// Output
		return $this->image_render($html);
	}

} // End Captcha Basic Driver Class