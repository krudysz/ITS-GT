<?php
/*-----------------------------------------------------------------------
Usage: 
			 Callable: <img src="ITS_bar.php?values=25,50,100,33" class="ITS_bar">

Author(s): Greg Krudysz
Date: Sep-23-2009
-----------------------------------------------------------------------*/
  $flag = $_GET['o'];
	
switch ($flag) {
	  //----------------------------------------------//
		case 'x';
    case 'f';
    case 'b';
		//----------------------------------------------//
			  $imgW = 20; 
				$imgH = 20;
        break;
		//----------------------------------------------//		
    case 's':
		//----------------------------------------------//
		    $imgW = 40; 
				$imgH = 40;			
				break;
		//----------------------------------------------//
}
	
  // Create image
  $image=imagecreate($imgW,$imgH);
	$background_color = imagecolorallocate($image, 220, 220, 220);

  // Colors
	$colors[0] = imagecolorallocate($image,255,255,255); // white
	$colors[1] = imagecolorallocate($image,146,249, 95); // green
	$colors[2] = imagecolorallocate($image,249,111, 95); // red
  $colors[3] = imagecolorallocate($image, 95,213,249); // blue
	$colors[4] = imagecolorallocate($image,187, 93,206); // purple
	$colors[5] = imagecolorallocate($image,170,170,170); // gainsboro
	$colors[6] = imagecolorallocate($image,  0,  0,  0); // black
	
  //imagestring($image,2,2,2,$flag,$colors[6]);
  //imagefilledrectangle($image, 0, 0,$imgW,$imgH,$colors[0]);
	//imageellipse($image,$imgW/2,$imgH/2,$imgW,$imgH,$colors[5]);
	// FORWARD

switch ($flag) {
	  //----------------------------------------------//
    case 'x':
		//----------------------------------------------//
		    $t = 0.2*$imgW;
				$d = 0.3*$imgW;
        $values1 = array(0,$t,$imgW-$t,$imgH,$imgW,$imgH-$t,$t,0);
        $values2 = array(0,$imgH-$t,$t,$imgH,$imgW,$t,$imgW-$t,0);	
	      imagefilledpolygon($image,$values1,4,$colors[2]);
	      imagefilledpolygon($image,$values2,4,$colors[2]);
        break;
		//----------------------------------------------//		
    case 'f':
		//----------------------------------------------//
        $values = array(0,0,0,$imgH,$imgW,$imgH/2);
				imagefilledpolygon($image,$values,3,$colors[5]);
        break;
		//----------------------------------------------//		
    case 's':
		//----------------------------------------------//
        $values = array(0,0,0,$imgH,$imgW,$imgH/2);				
				imagefilledellipse($image,$imgW/2,$imgH/2,$imgW/2,$imgH/2,$colors[5]);			
        break;
		//----------------------------------------------//		
    case 'b':
		//----------------------------------------------//
        $values = array(0,$imgH/2,$imgW,0,$imgW,$imgH);
				imagefilledpolygon($image,$values,3,$colors[5]);
        break;			
		//----------------------------------------------//
}
	/*
	$values = array($t      ,0,
	                0       ,  0,                 			 
									$d       ,$imgH/2,
            			0      ,$imgH);, // Point 3 (x, y)
									$imgW/2 ,$imgH-$d,
									$imgW-$t,$imgH,
									$imgW   ,$imgH,
									$imgW-$d,$imgH/2,
									$imgW,  ,0,
									$imgW-$t,0,
									$imgW/2 ,$d
            			);*/
									/*
	$valuesU = array($t      ,0,
									$imgW/2  ,$d,                 			 
            			$imgW-$t ,0);
	$valuesL = array(0      ,0,
									$d  ,$imgH/2,                 			 
            			0 ,$imgH);	
	$valuesD = array($t ,$imgH,
									$imgW/2  ,$imgH-$d,                 			 
            			$imgW-$t ,$imgH);	
	$valuesR = array($imgW      ,0,
									$imgW-$d  ,$imgH/2,                 			 
            			$imgW ,$imgH);																					
	imagefilledpolygon($image,$valuesU,3,$colors[2]);
	imagefilledpolygon($image,$valuesL,3,$colors[2]);
	imagefilledpolygon($image,$valuesD,3,$colors[2]);
	imagefilledpolygon($image,$valuesR,3,$colors[2]);*/
	

	
	// Render and destroy
	header("Content-type: image/png");
  imagepng($image);
  imagedestroy($image);
?>
