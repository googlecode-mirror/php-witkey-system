<?php define ( 'IN_KEKE', TRUE );

include '../app_boot.php';



//����ˮӡ
$mark = Image::factory(S_ROOT.'test/crop_form.jpg');

$image = Image::factory(S_ROOT.'test/dynamic-600.jpg');

//���ô�С
//$mark->resize($image->width,$image->height);

//$mark->crop($width, $height);
//ˮӡ
//$image->watermark($mark,NULL,NULL,50)->save('watermark.jpg');
//�ؼ�
// $image->crop(100, 100)->save('watermark.jpg');

//ˮƽ,��ֱ��ת
//$image->flip(Image::VERTICAL)->save('watermark.jpg');
//��ӳ
//$image->reflection($image->height,50,TRUE);
//��
//$image->sharpen(50);
//��ת
$image->rotate(180);

$image->save('watermark.jpg');

echo "<img src='watermark.jpg'>";


