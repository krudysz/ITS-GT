<?php
/*-----------------------------------------------------------------------
Usage: 
			 Callable: <img src="ITS_bar.php?values=25,50,100,33" class="ITS_bar">
Caveat: 
			 Supports only 8 bars				 

Author(s): Greg Krudysz
Date: Sep-23-2009
Last Update: May-04-2011
-----------------------------------------------------------------------*/

$values = $_GET['values'];
$values = explode(",",$values);
Header("Content-type: image/png");

  $N = count($values); // number of values

  $imgW = 140;
  $imgH = 60;

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
	$colors[7] = imagecolorallocate($image,120,120,255); // violet

  // Create bar charts
	$space  = $imgW*0.03; // spacing 5px
  $Nwidth = ($imgW-($N+1)*$space)/$N;
	$top    = 0.3*$imgH;
	$base   = 0.7*$imgH;

  for ($i=0; $i<($N); $i++){
	  imagefilledrectangle($image,$i*($Nwidth+$space)+$space,$base,($i+1)*($Nwidth+$space),$base-(0.4*$imgH*$values[$i]/100),$colors[$i+1]);
    imagestring($image,3,$i*($Nwidth+$space)+$space+$Nwidth/3,$base+$space,chr($i+65),$colors[6]);
	  
		if ($i == ($N-1)){	$val = $values[$i].'%';  }
		else             {  $val = $values[$i];      }
		imagestring($image,3,$i*($Nwidth+1.2*$space)+$space,$space,$val,$colors[6]);
	}
	// baseline
	imageline($image,$space,$base,$imgW-$space,$base,$colors[6]);
	//imageline($image,$space,$top,$imgW-$space,$top,$colors[6]);
	// $i*($Nwidth+$space)+$space+($Nwidth)/2
	
	// Render and destroy
  imagepng($image);
  imagedestroy($image);
?>
