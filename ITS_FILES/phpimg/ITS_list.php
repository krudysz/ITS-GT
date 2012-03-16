<?php
/*-----------------------------------------------------------------------
Usage: 
			 Callable: <img src="ITS_list.php?values=25,50,100,33" class="ITS_list">

Author(s): Greg Krudysz
Date: Oct-02-2009
-----------------------------------------------------------------------*/

$values = $_GET['values'];
$values = explode(",",$values);

$labels = $_GET['labels'];
$label = explode(",",$labels);
//$dd = $_GET['labels'];

Header("Content-type: image/png");

  $N = count($values); // number of values

  $imgW = 140;
  $imgH = 80;

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
	//imagestring($image,2,0,0,$label[0],$colors[4]);
	
  // Create bar charts
	$space  = $imgW*0.25; // spacing
  
  for ($i=0; $i<($N); $i++){
	  imagestring($image,3,0,$i*15,$values[$i].'%',$colors[7]);
	  //imagestring($image,3,0,$i*15,'('.$values[$i].'%)',$colors[4]);
		//imagestring($image,4,$space*1.3,$i*15,($i+1).'. ',$colors[6]);
		imagestring($image,3,$space*1.3,$i*15,$label[$i],$colors[8]);
	}
	// vertical line
	imageline($image,$space,0,$space,$imgH,$colors[9]);
	
	// Render and destroy
  imagepng($image);
  imagedestroy($image);
?>
