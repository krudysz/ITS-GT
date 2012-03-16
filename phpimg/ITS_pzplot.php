<?php
/*-----------------------------------------------------------------------
Usage: 
			 Callable: <img src="ITS_pzplot.php?poles=25,50,100,33" class="ITS_pzplot">

Author(s): Greg Krudysz
Date: Oct-31-2009
Revision: Nov-1-2009
-----------------------------------------------------------------------*/
// POLES: assume real
if (isset($_GET['poles'])){  $poles = explode(",",$_GET['poles']); }
else											{  $poles = array();             }

// ZEROS
if (isset($_GET['zeros'])){  $zeros = explode(",",$_GET['zeros']); }
else											{  $zeros = array();             }

Header("Content-type: image/png");

$Width  = 200;
$Height = 200;

$UC_width = 0.8*$Width;

$img = ImageCreateTrueColor($Width,$Height);

// COLORS
$black  = imagecolorallocate($img,  0,  0,  0);
$gray   = imagecolorallocate($img,100,100,100);
$red    = imagecolorallocate($img,255,  0,  0);
$green  = imagecolorallocate($img,  0,255,  0);
$blue   = imagecolorallocate($img,  0,  0,255);
$white  = imagecolorallocate($img,255,255,255);
$COLOR_grid   = imagecolorallocate($img,215,215,215);

imagefill($img,0,0,$white);

// GRID
imagesetstyle($img,array($white,$COLOR_grid));
imagegrid($img, $Width, $Height,20, IMG_COLOR_STYLED);

// UNIT CIRCLE
imageellipse($img,$Width/2,$Height/2,$UC_width,$UC_width,$black);

// AXIS
$alength = 0.05*$UC_width;
$awidth  = 0.015*$UC_width;
arrow($img,0,$Height/2,$Width,$Height/2,$alength,$awidth,$black);
arrow($img,$Width/2,$Height,$Width/2,0,$alength,$awidth,$black);

$str_sz = 5; // string size

$x_offset = $Width/2-$str_sz/2;
$y_offset = $Width/2-1.5*$str_sz;

// POLES
for ($p=0; $p<(count($poles)/2); $p++){
  $x_pole =  $UC_width/2*$poles[2*$p+0]+$x_offset;
	$y_pole = -$UC_width/2*$poles[2*$p+1]+$y_offset;
	imagestring($img,$str_sz,$x_pole,$y_pole,'x',$red);		
}
// DEBUG:
//imagestring($img,$str_sz,0,0,$zeros,$blue);

// ZEROS
for ($z=0; $z<(count($zeros)/2); $z++){
  $x_zero =  $UC_width/2*$zeros[2*$z+0]+$x_offset;
	$y_zero = -$UC_width/2*$zeros[2*$z+1]+$y_offset;
	imagestring($img,$str_sz,$x_zero,$y_zero,'o',$blue);		
}

// watermark
//imagestring($img,1,0.8*$Width,0.95*$Height,'ITS.GT',$gray);

ImagePNG($img);
ImageDestroy($img);

function imagegrid($img, $w, $h, $s, $color)
{
    for($iw=1; $iw<$w/$s; $iw++){imageline($img, $iw*$s, 0, $iw*$s, $w, $color);}
    for($ih=1; $ih<$h/$s; $ih++){imageline($img, 0, $ih*$s, $w, $ih*$s, $color);}
}
function arrow($im, $x1, $y1, $x2, $y2, $alength, $awidth, $color) {
    $distance = sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));

    $dx = $x2 + ($x1 - $x2) * $alength / $distance;
    $dy = $y2 + ($y1 - $y2) * $alength / $distance;

    $k = $awidth / $alength;

    $x2o = $x2 - $dx;
    $y2o = $dy - $y2;

    $x3 = $y2o * $k + $dx;
    $y3 = $x2o * $k + $dy;

    $x4 = $dx - $y2o * $k;
    $y4 = $dy - $x2o * $k;

    imageline($im, $x1, $y1, $dx, $dy, $color);
    imagefilledpolygon($im, array($x2, $y2, $x3, $y3, $x4, $y4), 3, $color);
}
//-------
?>
