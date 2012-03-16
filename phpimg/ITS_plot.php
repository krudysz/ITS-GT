<?php
/*
header ("Content-type: image/png");

$f0 = 20;

$myImage = imagecreate(640, 480);
			
$colors[0]  = imagecolorallocate($myImage,255,255,255); // white
$text_color = imagecolorallocate($myImage, 0, 126, 0);
$poly_color = imagecolorallocate($myImage, 124, 120, 224);

//calculate x-value and y-value point by point
$points = array();
for ($i=0; $i<100; $i=$i+1)
{
    //define curve's function
    $x = 2*pi()*$f0*$i; //define x-value
    $y = 10*sin($x)*$i;//define y-value
   
    //move the coordinate, append a point's x-value and y-value
    $points[] = 320+$x; //x-value
    $points[] = 240-$y;  //y-value
}

//count points
$totalPoints = count($points)/2;

//drawing title
$do = 2*pi();
//$do = 'a';
$title = 'Final Plot ('.$do.' points)';
imagestring($myImage, 3, 5, 5,  $title, $text_color);

for ($i=0; $i<$totalPoints-1; $i++)
{
    imageLine($myImage, $points[2*$i], $points[1+2*$i], $points[2+2*$i], $points[3+2*$i], $poly_color);   
}

//finalizing
imagepng($myImage);
imagedestroy($myImage);
*/
//------------------
Header("Content-type: image/png");
$Width=200;
$Height=290;

$img = ImageCreateTrueColor($Width, $Height);

$bg = imagecolorallocate($img, 255, 255, 255);

imagefill($img, 0, 0, $bg);

$grid = imagecolorallocate($img, 225, 245, 9);

imagesetstyle($img, array($bg, $grid));
imagegrid($img, $Width, $Height, 10, IMG_COLOR_STYLED);
//makegrid($img, $Width, $Height, 10, $grid);

ImagePNG($img);
ImageDestroy($img);

function imagegrid($image, $w, $h, $s, $color)
{
    for($iw=1; $iw<$w/$s; $iw++){imageline($image, $iw*$s, 0, $iw*$s, $w, $color);}
    for($ih=1; $ih<$h/$s; $ih++){imageline($image, 0, $ih*$s, $w, $ih*$s, $color);}
}
?>
