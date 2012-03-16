<?php
/***********************************************************************************
functions:
	1. average($v1,$v2,$v3,$v4,$v5)			: returns the average value
	2. total_sum($v1,$v2,$v3,$v4,$v5)		: returns the sum of values
	3. find_each_percentage($value,$total)	: returns the % of value in total
	4. get_percentage($v1,$v2,$v3,$v4,$v5)  : changes values to %s
*************************************************************************************/
class math1{
	function average($varray)
	{
			$i=0;
			$avg=0;
			foreach($varray as $v)
			{
				$i=$i+1;
				$avg=$avg+$i*$v;
			}	
			return $avg;
	}
	
	function total_sum($v1,$v2,$v3,$v4,$v5)
	{
		$total_sum=$v1+$v2+$v3+$v4+$v5;
		return $total_sum;
	}
	
	function find_each_percentage($value,$total)
	{
		$percent=($value/$total)*100;
		return $percent;
	}
	
	function get_percentage($v1,$v2,$v3,$v4,$v5)
	{
		$total=$this->total_sum($v1,$v2,$v3,$v4,$v5);
		
		$out1=$this->find_each_percentage($v1,$total);
		$out2=$this->find_each_percentage($v2,$total);
		$out3=$this->find_each_percentage($v3,$total);
		$out4=$this->find_each_percentage($v4,$total);
		$out5=$this->find_each_percentage($v5,$total);
		
		$arr=array($out1,$out2,$out3,$out4,$out5);
		return $arr;
	}
}
?>