<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * Word captcha class.
 *
 * @package		Captcha
 * @subpackage	Captcha_Word
 * @author		Michael Lavers
 * @author		Kohana Team
 * @copyright	(c) 2008-2010 Kohana Team
 * @license		http://kohanaphp.com/license.html
 */
class Keke_captcha_word extends Keke_captcha_basic
{
	/**
	 * Generates a new Captcha challenge.
	 *
	 * @return string The challenge answer
	 */
	public function generate_challenge()
	{
		// Load words from the current language and randomize them
		$words = Keke_captcha_config::get('words');   
		shuffle($words);

		// Loop over each word...
		foreach ($words as $word)
		{
			// ...until we find one of the desired length
			if (abs(Keke_captcha::$config['complexity'] - strlen($word)) < 2)
				return strtoupper($word);
		}
		
		// Return any random word as final fallback
		return strtoupper($words[array_rand($words)]);
	}

} // End Captcha Word Driver Class