<?php defined ( "IN_KEKE" ) or die ( "Access Denied" );
/**
 * Alpha captcha class.
 *
 */
class Keke_captcha_alpha extends Keke_captcha 
{
	/**
	 *	��������ַ���
	 * @return string 
	 */
	public function generate_challenge(){
		 
		$text = Keke::randomkeys(max(1, Keke_captcha::$config['complexity']));
		return $text;
	}

	/**
	 * �����֤ͼ��
	 * @param boolean $html  
	 * @return mixed
	 */
	public function render($html = TRUE)
	{
		
		// ����    $this->image
		$this->image_create(Keke_captcha::$config['background']);

		// ������������
		if (empty(Keke_captcha::$config['background'])){
			$color1 = imagecolorallocate($this->image, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
			$color2 = imagecolorallocate($this->image, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
			$this->image_gradient($color1, $color2);
		}

		// ��������Բ��
		for ($i = 0, $count = mt_rand(10, Keke_captcha::$config['complexity'] * 3); $i < $count; $i++){
			$color = imagecolorallocatealpha($this->image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255), mt_rand(80, 120));
			$size = mt_rand(5, Keke_captcha::$config['height'] / 3);
			imagefilledellipse($this->image, mt_rand(0, Keke_captcha::$config['width']), mt_rand(0, Keke_captcha::$config['height']), $size, $size, $color);
		}

		// �������ֵĴ�С����
		$default_size = min(Keke_captcha::$config['width'], Keke_captcha::$config['height'] * 2) / strlen($this->response);
		$spacing = (int) (Keke_captcha::$config['width'] * 0.9 / strlen($this->response));

		// �����ַ�������
		$color_limit = mt_rand(96, 160);
		$chars = 'ABEFGJKLPQRTVY';

		// Ϊÿ����֤�ַ����ò�ͬ������
		for ($i = 0, $strlen = strlen($this->response); $i < $strlen; $i++)
		{
			// ����ж�������ʹ�ò�ͬ������
			$font = Keke_captcha::$config['fontpath'].Keke_captcha::$config['fonts'][array_rand(Keke_captcha::$config['fonts'])];

			$angle = mt_rand(-40, 20);
			// ��ͼƬ�߶ȣ����������С
			$size = $default_size / 10 * mt_rand(8, 12);
			$box = imageftbbox($size, $angle, $font, $this->response[$i]);

			// �����ַ�����ʼ����
			$x = $spacing / 4 + $i * $spacing;
			$y = Keke_captcha::$config['height'] / 2 + ($box[2] - $box[5]) / 4;

			// �滭��֤���ַ�
			// ����������ɫ����С����ת����
			$color = imagecolorallocate($this->image, mt_rand(150, 255), mt_rand(200, 255), mt_rand(0, 255));

			// ���ַ�д��ͼƬ��
			imagefttext($this->image, $size, $angle, $x, $y, $color, $font, $this->response[$i]);

			//�滭��ghots�����ַ���ĸ
			$text_color = imagecolorallocatealpha($this->image, mt_rand($color_limit + 8, 255), mt_rand($color_limit + 8, 255), mt_rand($color_limit + 8, 255), mt_rand(70, 120));
			$char = $chars[mt_rand(0, 13)];
			imagettftext($this->image, $size * 2, mt_rand(-45, 45), ($x - (mt_rand(5, 10))), ($y + (mt_rand(5, 10))), $text_color, $font, $char);
		}
		
		return $this->image_render($html);
	}

} // End Captcha Alpha Driver Class