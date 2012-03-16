<?php
 	//include paths and files
	include_once("/database/class_query.php");
	include_once("/forms/class_page.php");
	include_once("/database/class_rating_query.php");
	include_once("/forms/class_form_data.php");
	
class rating_main{
	var $count;				//counts the number of rates
	var $original_rating;	//keeps the original rating
	var $current_rating;	//keeps the current rating
	var $query;				//query object
	var $rating;			
	
	function rating_main($id,$dbname,$server,$username,$password,$tbname,$filename)
	{	
		$this->get_original_rating($server,$username,$password,$dbname,$tbname,$id);
		$this->draw_stars($filename);
		$this->animate_stars($filename);
		$this->get_current_rating();
		$this->update_rating($server,$username,$password,$dbname,$tbname,$id);
		$this->display_rating();
		echo $this->rating;
	
	}
	
	function get_original_rating($server,$username,$password,$dbname,$tbname,$id){
		//connect to database
		if(!isset($this->count))
		{
			// Create new rating_Query and getting the rating
			$this->query=new Rating_Query($server,$username,$password,$dbname);
			$ret=$this->query->execute("SELECT * FROM ".$tbname." where id=$id");
			$q=$this->query->get();
			
			//store the original rating
			$this->original_rating=$q;
			$this->current_rating=$this->original_rating;
			
			//increment the count
			$this->count=$this->count+1;
		}else{
			//change the current rating to original
			$q=$this->original_rating;
		}			
	}
	
	function get_current_rating()
	{
		if(!isset($this->count)){
			$this->rating=0;
		}else{
			//get rating from cookie and unset the cookie
			if(isset($_COOKIE['rating']))
			{				
				$this->rating=$_COOKIE['rating'];
				//setcookie("rating","",time()+3);
			}else{
				$this_rating=$_REQUEST['rating'];
			}
		}
	}
	function draw_stars($filename)
	{
		echo "Rate this:";
		//loading and drawing stars
		echo '<body onload="loadStars()">
				<div id="rating_system" align="left">
				<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="1" style="width:20px; height:20px; float:left;" />
				<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="2" style="width:20px; height:20px; float:left;" />
				<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="3" style="width:20px; height:20px; float:left;" />
				<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="4" style="width:20px; height:20px; float:left;" />
				<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="5" style="width:20px; height:20px; float:left;" />';

	}
	
	function animate_stars($filename)
	{
		echo '<div id="vote" style="font-family:tahoma; color:blue;"></div>
				</div>
				<div id="current rating">
				<script language="Javascript" src="forms/star_rating.js">
				</script></div>';
	}
	
	function update_rating($server,$username,$password,$dbname,$tbname,$id)
	{
		$q=$this->original_rating;
		$R=$this->rating;
		if(isset($R))
	 	{	
			$current_rate=$R;
		 //echo $current_rate;
			switch($current_rate)
			 	{	 	
				 	case 0:
				 		$q=$q;
				 		break;
				 	case 1:
				 		$q['one']++;
				 		break;
				 	case 2:
				 		$q['two']++;
				 		break;
				 	case 3:
				 		$q['three']++;
				 		break;
				 	case 4:
				 		$q['four']++;
				 		break;
				 	case 5:
				 		$q['five']++;
				 		break;
		 	}
		}	
		//calculate the Initial and Final Ratings
		$rate=new Rating_Query($server,$username,$password,$dbname);
		$total_number=$rate->get_total_ratings($q['one'],$q['two'],$q['three'],$q['four'],$q['five']);
		$q['initial_rating']= $rate->get_initial_rating($q['one'],$q['two'],$q['three'],$q['four'],$q['five']);	
		$q['final_rating']=	$rate->get_final_rating();
		$q['total_rates']=$total_number;
		
		//update database
		$this->query->update_db($tbname,$q['one'],$q['two'],$q['three'],$q['four'],$q['five'],$q['initial_rating'],$q['total_rates'],$q['final_rating'],$id);
		$this->current_rating=$q; 
	}
	
	
	function display_rating()
	{
		//display rating	
		echo "</br>Current Difficulty Level : ";$this->query->display_rating("normal");
		$this->draw_bargraph();
		$this->draw_piegraph();
		//$this->draw_popup();
	}
	
	function draw_popup()
	{
			echo '<script language="Javascript" src="draw_popup.js">	</script>';
			echo '<A HREF="javascript:popUp(';
			echo '"disp_graph.php")'; echo '">'; 
			echo '<img id="icon1" src="icons/bargrap1.jpg" style="width:50px; height:50px; float:left;">'; 
			echo '</A>';
	}
	
	function draw_bargraph()
	{
		$q=$this->current_rating;
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
	 	$bar_width=30;
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
		$temp_chart_file_name = "chart2.png";
		imagepng($img, $temp_chart_file_name,0);
		
		//unset the values and show image
		unset($values);
	}
	
	function draw_piegraph()
	{
		$q=$this->current_rating;
		  // Standard inclusions   
		  include_once("pChart/pData.class");
		  include_once("pChart/pChart.class");
		
		  // Dataset definition 
		  $DataSet = new pData;
		  $DataSet->AddPoint(array($q['one'],$q['two'],$q['three'],$q['four'],$q['five']),"Serie1");
		  $DataSet->AddPoint(array("Very Easy","Easy","Moderate","Hard","Very Hard"),"Serie2");
		  $DataSet->AddAllSeries();
		  $DataSet->SetAbsciseLabelSerie("Serie2");
		
		  // Initialise the graph
		  $Test = new pChart(380,250);
		  $Test->drawFilledRoundedRectangle(7,7,373,193,5,240,240,240);
		  $Test->drawRoundedRectangle(5,5,375,195,5,230,230,230);
		
		  // Draw the pie chart
		  $Test->setFontProperties("Fonts/tahoma.ttf",8);
		  $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),150,90,110,PIE_PERCENTAGE,TRUE,50,20,5);
		  $Test->drawPieLegend(280,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
		
		  $Test->Render("example10.png");
	}
}
?>