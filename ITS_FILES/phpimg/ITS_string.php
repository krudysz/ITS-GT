<?php
/*-----------------------------------------------------------------------
Usage: 
			 Callable: <img src="ITS_list.php?values=25,50,100,33" class="ITS_list">

Author(s): Greg Krudysz
Date: Nov-04-2009
-----------------------------------------------------------------------*/
Header("Content-type: image/png");

  $imgW = 200;
  $imgH = 20;
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
	imagestring($image,5,0,5,' A P P E N D I X',$colors[6]);
	imagestring($image,10,$imgW/2,$imgH/2,'A',$colors[2]);
	CenterImageString($image,$imgW,"A",5,10, $colors[2]); 
	
	// Render and destroy
  imagepng($image);
  imagedestroy($image);
	
	function CenterImageString($image, $image_width, $string, $font_size, $y, $color)
 {
 $text_width = imagefontwidth($font_size)*strlen($string);
 $center = ceil($image_width / 2);
 $x = $center - (ceil($text_width/2));
 ImageString($image, $font_size, $x, $y, $string, $color);
 } 
?>
