<?php
//includes
	include_once("class_query.php");
	include_once("class_page.php");
	include_once("class_rating_query.php");
	include_once("class_form_data.php");
				
//connect to database
	$id=1;	
	$dbname="test";
	$server="localhost";
	$username="root";
	$password="";
	$tbname="test";
	$db=new Query($server,$username,$password,$dbname);
		
//read in the question id
	$ret=$db->execute("SELECT * FROM ".$dbname." where id=$id");
	$q=$db->get();		
	
//set page template
	$p=new Page();
	$p->startTemplate("Rating System","ITS difficulty Rating System");
	
//set the form variables
	$form=new Form_Data();
	//$form->form_start($_SERVER['PHP_SELF']);
	//$form->form_close();	
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		 
			<input type="radio" name="Difficulty_level" value="1">1	</br>	
			<input type="radio" name="Difficulty_level" value="2">2 </br>		
			<input type="radio" name="Difficulty_level" value="3">3 </br>
			<input type="radio" name="Difficulty_level" value="4">4 </br>
			<input type="radio" name="Difficulty_level" value="5">5 </br>	
		
			<input type="submit" name="Submit" value="Submit">
		
		</form>
		</br>
		
<?php
 	//increment the count of easy,moderate,difficult based on radio buttons
 	$status_1='unchecked';
 	$status_2='unchecked';
 	$status_3='unchecked';
 	$status_4='unchecked';
 	$status_5='unchecked';
 	
 	$R=$_REQUEST;
 	
 	if(isset($R['Submit'])&&($R['Submit']=="Submit")&&(isset($_POST['Difficulty_level'])))
 	{
 		$selected_radio=$_POST['Difficulty_level'];
 		if($selected_radio=='1'){
	 		$status_1='checked';
	 		$q['one']++;
	 		echo "Thank you for rating</br>";
	 		//echo $q['easy'];
 		}elseif($selected_radio=='2'){
	 		$status_2='checked';
	 		$q['two']++;
	 		echo "Thank you for rating</br>";
	 		//echo $q['moderate'];
 		}elseif($selected_radio=='3'){
	 		$status_3='checked';
	 		$q['three']++;
	 		echo "Thank you for rating</br>";
	 		//echo $q['difficult'];
 		}elseif($selected_radio=='4'){
	 		$status_4='checked';
	 		$q['four']++;
	 		echo "Thank you for rating</br>";
	 		//echo $q['difficult'];
 		}elseif($selected_radio=='3'){
	 		$status_5='checked';
	 		$q['five']++;
	 		echo "Thank you for rating</br>";
	 		//echo $q['difficult'];
 		}
 		
	}else{
		echo "Please select one of the above </br>";
	}
	
//calculate the Initial and Final Ratings
	$rate= new Rating_Query();
	$total_number=$rate->get_total_ratings($q['one'],$q['two'],$q['three'],$q['four'],$q['five']);
	$q['initial_rating']= $rate->get_initial_rating($q['one'],$q['two'],$q['three'],$q['four'],$q['five']);	
	$q['final_rating']=	$rate->get_final_rating();
	
//display rating	
	echo "</br>Current Difficulty Level :";$rate->display_rating("normal");
	
//*****************************************************************************************

//drawing the bar graph 

	//define the associative array
	$values=array(
		"1"=>$q['one'],
		"2"=>$q['two'],
		"3"=>$q['three'],
		"4"=>$q['four'],
		"5"=>$q['five']
		);
	

	//size of image
	$img_width=400;
 	$img_height=200;
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
 	$ratio= $graph_height/$max_value;	
 	
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
	echo "</br> <img src='chart1.png' alt='Ratings' />";
 
 //***********************************************************************************************	
 
//update the database	
	if(isset($R['Submit']))
	{
		$db->update_db($R['Submit'],$tbname,$q['one'],$q['two'],$q['three'],$q['four'],$q['five'],$q['initial_rating'],$q['final_rating'],$id);
	}
	
//close the page template
	$p->closeTemplate();
?>