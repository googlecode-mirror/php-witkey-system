<?php
/**
 * @todo ͼƬ�����ࡢ����,����
 * @version v 2.0
 * @author hr
 */
	class keke_img_class {
		
		private static $_img_width;
		private static $_img_height;
		private static $_img_type;	  //ԭͼ������(intö������)
		private static $quality=100; //���ɵ�ͼƬ������(������ͼƬ�������̶��Լ����ɵ��ļ��Ĵ�С)
// 		private static $cut_position='center';//����ʱ��,�����￪ʼ����(left,right,center)
		
		function __construct(){}
		
		/**
		 * ������ͼƬ���ųɲ�ͬ�ı���, $size�����ж��,���һ����������Ϊboolֵ,Ϊtrue��ʱ���ʾΪ����
		 * e.g keke_img_class::resize('example.jpg', array(100,100), array(200,200,'target.jpg'), true)
		 * @param string $image
		 * @param array $size Ϊ����Ϊ2��3���������� array(width, height, target_address��ѡ)
		 * @return boolean
		 */
		public static function resize($image, $size){
			self::init_img_info($image); 
			$arg_length = func_num_args();//��������
			if($arg_length==1){
				return false;
			}
			$arguments = func_get_args();//��������
			
			/* �ж��Ƿ���Ҫ����ͼƬ,���Ҿ���ѭ���ĳ���*/
			$cut = false;
			$last_arg = $arguments[$arg_length-1];
			$length = $arg_length-1;
			if (is_bool($last_arg)){
				if ($arg_length<=2){ return false;}//��������²�����������Ϊ3
				$length = $arg_length-2;
				$cut = $last_arg; //$last_arg==true && $cut=true;
			}
			
			$orig_width = self::$_img_width;
			$orig_height = self::$_img_height;
			$success = (int)0;
			for ($i=1;$i<=$length;$i++){
				if (!is_array($arguments[$i])){
					return false;
				}
				//width <= height ����ԭͼ�̵�һ��������
				if ($orig_width <= $orig_height){
					$according = $arguments[$i][0]/$orig_width;//���ݿ�ȵİٷֱ�
				} else {
					$according = $arguments[$i][1]/$orig_height;//���ݸ߶ȵİٷֱ�
				}
				$width = $orig_width * $according;
				$height = $orig_height * $according;
				$target = isset($arguments[$i][2]) ? $arguments[$i][2] : '';
				$result =  self::resize_pic($image, $width, $height, $target);
				//�����ź��ͼƬ�ٽ��м��в���
				if ($cut==true && file_exists($target)){
					$img_size_arr = getimagesize($target);
					$target_width = $arguments[$i][0];//��
					$target_height = $arguments[$i][1];//��
					$cut_x = $img_size_arr[0] < $target_width ? '0' : ($img_size_arr[0] - $target_width)/2;
					$cut_y = $img_size_arr[1] < $target_height ? '0' : ($img_size_arr[1] - $target_height)/2;
					self::cut_pic($target, $target_width,$target_height,$cut_x, $cut_y);
				}
				$result==true && $success++;
			}
			return $success>0 ? true : false;
		}

		/**
		 * ����ͼƬ������
		 * @param $image
		 * @param $width
		 * @param $height
		 * @param $targetfile Ҫ���ɵ�ͼƬ������(Ĭ��)
		 */
		public static function resize_pic($image, $width, $height, &$targetfile=''){
			$size = min($width, $height);
			if($targetfile==''){
				if(strtolower(substr($image, 0, 4))=='http'){ return false;}//�����Զ��url,����$targetfileΪ��,��û��Ȩ�������µ�file
				$targetfile = self::get_filepath_by_size($image, $size, false);
			}
			$result = self::cut_image($image, $targetfile, $width, $height);
			return $result;
		}
		
		/**
		 * ���г�ԭͼ��һ����,�������ɵ�ͼĬ�ϻḲ��ԭ����,����������е���Զ��url,��ôӦ�ø�$targetfileһ��ֵ,������ܱ���(Ȩ�޲���)
		 * @param $image
		 * @param $cut_width
		 * @param $cut_height
		 * @param $cut_x
		 * @param $cut_y
		 */
		public static function cut_pic($image, $cut_width,$cut_height,$cut_x=0, $cut_y=0, $targetfile=''){
			$targetfile=='' && $targetfile = $image;
			$result = self::cut_image($image, $targetfile, $cut_width, $cut_height,$cut_width,$cut_height,$cut_x, $cut_y);
			return $result;
		}
		
		/**
		 * ͼƬ�ü�������(Ĭ��) private
		 * @param string $image
		 * @param string $targetfile Ҫ���ɵ��ļ����ļ���
		 * @param int $new_width Ҫ���ɵ�ͼƬ�Ŀ��
		 * @param int $new_height Ҫ����ͼƬ�ĸ߶�
		 * @param int $cut_width �ü���ԭͼ�Ŀ��(��Բü�)
		 * @param int $cut_height
		 * @param int $cut_x ��ԭͼ���￪ʼ�ü�
		 * @param int $cut_y
		 * @return boolean
		 */
		private static function cut_image($image, $targetfile, $new_width, $new_height,$cut_width='',$cut_height='',$cut_x=0, $cut_y=0){
			if (!self::$_img_width || !self::$_img_height){
				self::init_img_info($image);
			}
			$cut_width=='' && $cut_width = self::$_img_width;//�����ֵ,�Ǿ��Ǽ���,�����������(Ĭ��)
			$cut_height=='' && $cut_height= self::$_img_height;
			
			if (!self::$_img_type) return false;
			$extend = '';
			switch (intval(self::$_img_type)){
				case 1: $extend='gif'; break;
				case 2: $extend='jpeg'; break;
				case 3: $extend='png'; break;
// 				case 6: $extend='bmp'; break;//bmp��ʽ������֧��
				default: return false; break;//����֧�ֵĸ�ʽ
			}
			$img_creat_method = 'imagecreatefrom' . $extend ;
			$source = $img_creat_method($image);
			$target_source = imagecreatetruecolor ( $new_width, $new_height );
			//imagecopyresampled ( resource���� , ԭͼresource, ��ͼx, ��ͼy, ԭͼx , ԭͼy , ��ͼ��, ��ͼ�� , ԭͼ�� , ԭͼ�� )
			imagecopyresampled ( $target_source, $source, 0, 0, $cut_x, $cut_y, $new_width, $new_height, $cut_width, $cut_height);//bool
			
			$img_method = 'image' . $extend;
			if ($img_method=='imagepng'){
				$result = $img_method ( $target_source, $targetfile);
			}else{
				$quality = self::$quality ? intval(self::$quality) : 100;
				$result = $img_method ( $target_source, $targetfile, $quality);
			}
			imagedestroy($target_source);
			return $result;//bool result
		}
		
		/**
		 * ��ʼ��ͼƬ��Ϣ
		 * @param string $imgPath
		 * @return;
		 */
		private static function init_img_info($image){
			$img_arr = getimagesize($image);
			if(!$img_arr) { return false; }
			self::$_img_width = $img_arr[0] ? $img_arr[0] : false;
			self::$_img_height = $img_arr[1] ? $img_arr[1] : false;
			self::$_img_type = $img_arr[2] ? $img_arr[2] : false;
// 			return true;
		}
		
		/**
		 * ����(ԭ)ͼƬ·�����Ҷ�Ӧ�ĳߴ��ͼƬ,
		 * ���$default=true(�Ƿ���ʾĬ��),���Ҽ���Ӧ�ļ�������,�򷵻�ϵͳĬ��ͼƬ
		 * @param string $file
		 * @param int $size
		 * @param bool $default ���ͼƬ�����ڵĻ�,�Ƿ���ʾĬ��ͼƬ
		 * @return string
		 */
		public static function get_filepath_by_size($file, $size, $default=true){
			$basename = basename($file);
			$dirname = dirname($file).'/';
			$new_path = $dirname . $size . '_' .$basename;
			if ($default==true && !file_exists($new_path)){
				$new_path = $size==210 ? SKIN_PATH.'/img/shop/shop_default_big.jpg' :'resource/img/system/kppw.jpg';
			}
			return $new_path;
		}
		
// 		/**
// 		 * ��ȡ�ļ���չ��
// 		 * @param string $filename
// 		 */
// 		private static function get_extend($filename, $method_pre=false){
// 			$ext = '';
// 			$last_pos = strrpos($filename, '.');
// 			if ($last_pos===false){
// 				return false;
// 			}
// 			$ext = strtolower(substr($filename, $last_pos+1));
// 			return $ext;
// 		}
		
		
		
	}
	
	