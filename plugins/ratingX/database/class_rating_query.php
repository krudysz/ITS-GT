<?php
include_once("class_query.php");
include_once("/math/math1.php");

class Rating_Query extends Query{
	var $total_ratings;
	var $initial_rating;
	var $final_rating;
	
	function get_total_ratings($var1,$var2,$var3,$var4,$var5)
	{
		$var=$var1+$var2+$var3+$var4+$var5;
		$this->total_ratings=$var;
		return $var;
	}
	
	function get_initial_rating($var1,$var2,$var3,$var4,$var5)
	{
		$var=($var1*1)+($var2*2)+($var3*3)+($var4*4)+($var5*5);
		$this->initial_rating=$var;
		return $var;		
	}
	
	function get_final_rating()
	{
		if($this->total_ratings!=0)
		{
			$var=($this->initial_rating)/($this->total_ratings);
			$this->final_rating=$var;
		}else{
			$var=0;
		}
		return $var;
	}
	
	function display_rating($style)
	{
		$var=$this->final_rating;
		switch($style)
		{
			case "normal":
			{
				if($var<=1)
					echo "Very Easy";
				elseif($var<=2)
					echo "Easy";
				elseif($var<=3)
					echo "Moderate";
				elseif($var<=4)
					echo "Hard";
				elseif($var<=5)
					echo "Very Hard";
				else 
					echo "Not Rated";
				break;
			}
			default:
			{
				if($var<=1)
					echo "Very Easy";
				elseif($var<=2)
					echo "Easy";
				elseif($var<=3)
					echo "Moderate";
				elseif($var<=4)
					echo "Hard";
				elseif($var<=5)
					echo "Very Hard";
				else 
					echo "Not Rated";
				break;
			}
		}
	}
		
}
?>