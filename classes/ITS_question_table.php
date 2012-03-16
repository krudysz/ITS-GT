<?php
//echo ITS_question_table();
//=====================================================================//
	function ITS_question_table() //,$images,$style)
//=====================================================================//
  {	// 
	 $numargs = 1; //func_num_args(); 
	 $content = 'in  "ITS_question_table().php"'; //func_get_arg(0);
	 
	 //var_dump($content);
	 //die();
	 //$images  = $info[1];
	 //$style   = $info[2];
	 
	 $style = 'ITS_QUESTION_BORDER';

	 $ul = 'images/line_ul.png';
	 $ur = 'images/line_ur.png';
	 $ll = 'images/line_ll.png';
   $lr = 'images/line_lr.png';
	 
	 $hz = 'images/line_horiz.png';
	 $vt = 'images/line_vert.png';
	 // render table
	 $frame = '<table class='.$style.'><tr>'
			 		 .'<td style="background-repeat:no-repeat;background-image: url('.$ul.')" class='.$style.'></td>'
			 		 .'<td style="background-repeat:repeat-x;background-image:  url('.$hz.')" class='.$style.'></td>'
			 		 .'<td style="background-repeat:no-repeat;background-image: url('.$ur.')" class='.$style.'></td>'
			 		 .'</tr><tr>'
					 .'<td style="background-repeat:repeat-y;background-image: url('.$vt.')" class='.$style.'</td>'
					 .'<td>'.$content.'</td>'
					 .'<td style="background-repeat:repeat-y;background-image: url('.$vt.')" class='.$style.'</td>'
					 .'</tr><tr>'
					 .'<td style="background-repeat:no-repeat;background-image: url('.$ll.')" class='.$style.'></td>'
					 .'<td style="background-repeat:repeat-x; background-image: url('.$hz.')" class='.$style.'></td>'
					 .'<td style="background-repeat:no-repeat;background-image: url('.$lr.')" class='.$style.'></td>'
					 .'</tr></table>';
		return $frame;			 
}
//=====================================================================//
?>
