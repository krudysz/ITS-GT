<?php
$w=40;
$h=20;
$im = ImageCreate($w,$h);
$white=ImageColorAllocate($im,255,255,255);
ImageFilledRectangle($im,0,0,$w,$h,$white);
imagecolortransparent ($im, $white);
ImageTTFText ($im, $h+ceil($h/3)+1, 0, -1, $h-1, $col1, "arialbd.ttf", "O");
ImageTTFText ($im, $h+ceil($h/3)+1, 0, $w-$h, $h-1, $col1, "arialbd.ttf", "O");
ImageTTFText ($im, $h+ceil($h/3)+1, 0, 1, $h-1, $col1, "arialbd.ttf", "O");
ImageTTFText ($im, $h+ceil($h/3)+1, 0, $w-$h-2, $h-1, $col1, "arialbd.ttf", "O");
$points=array(
    1,round($h/2),
    round($h/4),$h-round($h/4),
    round($h/2),$h,
    $w-(round($h/2)),$h,
    $w-(round($h/4)),$h-round($h/4),
    $w-2,round($h/2),
    $w-round($h/4),round($h/4),
    $w-round($h/2),0,
    round($h/2),0,
    round($h/4),round($h/4)
);
imagefilledpolygon ($im, $points, 10, $col1);

header("content-type: image/gif");
header("Content-Disposition: filename=name.gif");
ImageGif($im);
ImageDestroy($im);
?>
