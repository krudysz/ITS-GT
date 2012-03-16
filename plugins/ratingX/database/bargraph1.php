//*****************************************************************************************

//drawing the bar graph 

	//define the associative array
	$values=array(
		"Very Easy"=>$q['one'],
		"Easy"=>$q['two'],
		"Moderate"=>$q['three'],
		"Hard"=>$q['four'],
		"Very Hard"=>$q['five']
		);
	

	//size of image
	$img_width=400;
 	$img_height=250;
	$margins=20;
	$graph_width=$img_width - $margins * 2;
 	$graph_height=$img_height - $margins * 2;
 
 	
 	//create image
 	$img=imagecreate($img_width,$img_height);
 	
 	//size of bars
 	$bar_width=40;
 	$total_bars=count($values);
 	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1); 
 	
 	//defining the colors
 	$bar_color=imagecolorallocate($img,0,64,128);
 	$background_color=imagecolorallocate($img,240,240,255);
 	$border_color=imagecolorallocate($img,200,200,200);
 	$line_color=imagecolorallocate($img,220,220,220); 
 	
 	//draw borders
 	imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
 	imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color); 
	
 	//find the scaling factor
 	$max_value=max($values);
 	if($max_value!=0)
 	{
	 	$ratio= $graph_height/$max_value;	
 	}else{
	 	$ratio=1;
 	}
 	
 	//draw the horizontal lines
 	$horizontal_lines=20;
 	$horizontal_gap=$graph_height/$horizontal_lines;
 	for($i=1;$i<=$horizontal_lines;$i++)
 	{
		$y=$img_height - $margins - $horizontal_gap * $i ;
		imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,0,5,$y-5,$v,$bar_color);
 	}
 	
 	//draw the bars
 	for($i=0;$i< $total_bars; $i++)
 	{
		list($key,$value)=each($values);
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width;
		$y1=$margins +$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins;
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
		imagestring($img,0,$x1+3,$y1-10,$value,$bar_color);
		imagestring($img,0,$x1+3,$img_height-15,$key,$bar_color);
	} 
	
	//display the image
	$temp_chart_file_name = "chart1.png";
	imagepng($img, $temp_chart_file_name,0);
	
	//unset the values and show image
	unset($values);
	//echo "</br> <img src='chart1.png' alt='Ratings' />";
 
 //***********************************************************************************************	