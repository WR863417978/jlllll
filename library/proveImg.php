<?php 
session_start();
header("content-type:image/png");
$image_width=100;
$image_height=37;
$new_number = "1234567890";
$num_image = imagecreate($image_width,$image_height);
imagecolorallocate($num_image,255,255,255);
$str = "";
for($i=0;$i<4;$i++){
	//随机字体
	$font = mt_rand(3,5);
	$x = mt_rand(1,8)+$image_width*$i/4;
	$y = mt_rand(1,$image_height/4);
	$color = imagecolorallocate($num_image,mt_rand(0,200),mt_rand(0,150),mt_rand(0,200));
	$number = $new_number[rand(0,9)];
	$str.=$number;
	$x1 = mt_rand(1,32);
	$y1 = mt_rand(1,32);
	imagestring($num_image,$font,$x,$y,$number,$color);
	imageline($num_image,$x1,$y1,$x,$y,$color);//画干扰线
}
$_SESSION["yan"] = $str;
imagepng($num_image);
imagedestroy($num_image);
?>