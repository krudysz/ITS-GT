<?php
/*-----------------------------------------------------------------------
Usage: 
			 Callable: <img src="ITS_list.php?values=25,50,100,33" class="ITS_list">

Author(s): Greg Krudysz
Date: Nov-04-2009
-----------------------------------------------------------------------*/

$values = $_GET['values'];
//$values = '1,2,3,4,1,2,3,4,1,2,3,4,9,8,7,6';
$values = explode(',',$values);

$size = $_GET['size'];
Header("Content-type: image/png");

  $N = count($values); // number of values

  $imgW = 140;
  $imgH = 140;

  // Create image
  $image=imagecreate($imgW,$imgH);

  // Colors
	$colors[0] = imagecolorallocate($image,255,255,255); // white
	$colors[1] = imagecolorallocate($image,146,249, 95); // green
	$colors[2] = imagecolorallocate($image,249,111, 95); // red
  $colors[3] = imagecolorallocate($image, 95,213,249); // blue
	$colors[4] = imagecolorallocate($image,187, 93,206); // purple
	$colors[5] = imagecolorallocate($image,168,119,101); // brown
	$colors[6] = imagecolorallocate($image,  0,  0,  0); // black
	$colors[7] = imagecolorallocate($image,102,102,102); // grey
	$colors[8] = imagecolorallocate($image,  0,  0,204); // blue
	$colors[9] = imagecolorallocate($image,192,192,192); // silver

	//-- imagestring($image,font,x,y,str,color);
	imagestring($image,5,0,0,'%',$colors[2]);
	
  // Create bar charts
	$space  = $imgW/$size;        // spacing
	$xcen   = 5;
	$xoff   = 15;//1.5*$space;   // x-offset
	$yoff   = 20;//1.5*$space;   // y-offset
	
	// Header: 'A. B. C. ...'
  for ($x=0; $x<$size; $x++){
	  imagestring($image,3,$x*$space+$xoff+$xcen,0,chr($x+65),$colors[8]);
	}
	
	// horizontal line
	imageline($image,0,0.8*$space,$imgH,0.8*$space,$colors[9]);
	
	for ($y=0; $y<$size; $y++){
	  // Header: '1.'
	  imagestring($image,3,0,$y*0.9*$space+$yoff,($y+1).'.',$colors[6]);
	for ($x=0; $x<$size; $x++){//($size-1)
		if (!($values[$size*$y+$x]==0)){
	     imagestring($image,2,$x*$space+$xoff+$xcen/2,$y*0.9*$space+$yoff,$values[$size*$y+$x],$colors[7]);
		}
		imageline($image,$x*$space+$xoff,0,$x*$space+$xoff,$imgH,$colors[9]);
		}
	}
	
	// Render and destroy
  imagepng($image);
  imagedestroy($image);
?>
