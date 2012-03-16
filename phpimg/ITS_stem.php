<?php
/*-----------------------------------------------------------------------
Usage: type: t=|i|r|t|tr| - impulse|rectangle|triangle|trapezoid
			 data: d=x1,x2,...,height
			 title: |{x(t)}|title_str
			 
Examples:			 

			 impulse: x(t) = 2*delta(t-1)
			 Callable: <img src="ITS_stem.php?t=i&d=1,2" class="ITS_pzplot">

Author(s): Greg Krudysz
Date: Nov-15-2009
Revision: Mar-01-2010
-----------------------------------------------------------------------*/
// TYPE
if (isset($_GET['t'])) {  $type = $_GET['t']; }
else									 {  $type = '';         }

// DATA
if (isset($_GET['d'])) {  $data = explode(",",$_GET['d']); }
else									 {  $data = array();                 }

//var_dump($data);

// TITLE
if (isset($_GET['title'])) {  $title = $_GET['title']; }
else									     {  $title = 'x(t)';        }
//=====================================================================//
Header("Content-type: image/png");

$Width  = 175;
$Height = 125;
$unit   = $Width/7;

$img = ImageCreateTrueColor($Width,$Height);

// COLORS
$black  = imagecolorallocate($img,  0,  0,  0);
$gray   = imagecolorallocate($img,100,100,100);
$red    = imagecolorallocate($img,255,  50,  50);
$green  = imagecolorallocate($img,  0,255,  0);
$blue   = imagecolorallocate($img,  0,  0,255);
$white  = imagecolorallocate($img,255,255,255);
$COLOR_grid   = imagecolorallocate($img,215,215,215);

$color = array($blue,$black,$red);
imagefill($img,0,0,$white);

// GRID
imagesetstyle($img,array($white,$COLOR_grid));
imagegrid($img, $Width, $Height,$unit,IMG_COLOR_STYLED);

// AXIS
$xB 	   = (1*$Width)/7;
$yB 		 = (6/10)*$Height;
$alength = 0.04*$Width;
$awidth  = 0.012*$Width;

arrow($img,0,$yB,$Width,$yB,$alength,$awidth,$black);
arrow($img,$xB,$Height,$xB,0,$alength,$awidth,$black);

// x/y-label
imagestring($img,3,0.95*$Width,$yB+$unit/2,'t',$black);
imagestring($img,3,$xB+$unit/4,$unit/4,$title,$black);

// OBJECTs
for ($n=0;$n<count($data);$n=$n+2) {
  impulse($img,$xB,$yB,$unit,$data[$n],$data[$n+1],$color);
}

// watermark
//imagestring($img,2,0.7*$Width,0.9*$Height,'ITS',$blue);

ImagePNG($img);
ImageDestroy($img);
//----------------------------------------------------------//
function impulse($img,$xB,$yB,$U,$xoffset,$height,$color){
   $x0 = $xB + $xoffset*$U;
	 $y0 = $yB - $height*$U;
	 $xoff = $U/10;
	 $yoff = $U/2;

   imagelineV($img,$x0,$yB,$x0,$y0,$color[2]);
   imagefilledellipse($img,$x0,$y0,$U/3,$U/3,$color[0]);
	 
	 if ($x0 != $xB){ 
	   if ($y0 > $yB) { $yoff = -1.4*$yoff; }		
	   imagestring($img,2,$x0-$xoff,$yB+$yoff,$xoffset,$color[1]);
	 }
}
//----------------------------------------------------------//
function imagelineH($img,$x1,$y1,$x2,$y2,$color) {
   $d=1;
   imageline($img,$x1-$d,$y1+$d,$x2+$d,$y2+$d,$color);
	 imageline($img,$x1-$d,$y1,$x2+$d,$y2,$color);
	 imageline($img,$x1-$d,$y1-$d,$x2+$d,$y2-$d,$color);
}
//----------------------------------------------------------//
function imagelineV($img,$x1,$y1,$x2,$y2,$color) {
   $d=1;
   imageline($img,$x1-$d,$y1,$x2-$d,$y2-$d,$color);
	 imageline($img,$x1,$y1,$x2,$y2-$d,$color);
	 imageline($img,$x1+$d,$y1,$x2+$d,$y2-$d,$color);
}
//----------------------------------------------------------//
function imagegrid($img, $w, $h, $s, $color) {
    for($iw=1; $iw<$w/$s; $iw++){imageline($img, $iw*$s, 0, $iw*$s, $w, $color);}
    for($ih=1; $ih<$h/$s; $ih++){imageline($img, 0, $ih*$s, $w, $ih*$s, $color);}
}
//----------------------------------------------------------//
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
//----------------------------------------------------------//
?>
