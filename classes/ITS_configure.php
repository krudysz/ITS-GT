<?php
//=====================================================================//
/*
ITS_configure class - render according to configuration.

 configuration: |1|2|3|4| - |-LIST-|-TILED-|-MATRIX-|-IMAGES-|

		Constructor: ITS_configure(data_array,configuration)
		
								 ex. $data = array('a','b','c','d'); 
								     $ITS_configure = new ITS_configure($data,2);
										 echo $ITS_configure->str;
								
	 Author(s): Greg Krudysz |  Nov-10-2008
	 Last Revision: Feb-10-2012
*/
//=====================================================================//
class ITS_configure {
		
	public $str;

	// Constructor //======================================================//
  function __construct($qid,$caption,$data,$weight,$image,$configuration,$mode){
	//=====================================================================//
	    $this->caption = $caption;
	    $this->data    = $data;
		$this->weight  = $weight;
		$this->image   = $image;
		$this->config  = $configuration;
		//$this->Q       = new ITS_question($student_id,$db_name,$table_name);

		// STYLE
		switch ($mode){
			case 0: $style = 'CHOICE';		  break;
			case 1: $style = 'CHOICE_ACTIVE'; break;
			case 2: $style = 'CHOICE';        break;
		}
		//--DEBUG--// ITS_debug(array('MODE: '.$mode,'CONF: '.$this->config));
		$N = count($this->data);

		switch ($this->config){
		//-------------------------------------------//
	  case 1: //---- LIST ----// 
	    //-------------------------------------------//
		/*
			$answer_str = '<table class="'.$style.'">';
		  for ($i=1;$i<=$N;$i++){
					$answer_str = $answer_str.'<tr><td class="'.$style.'">'.$this->data[$i].'</td></tr>';  
			}
			$answer_str = $answer_str.'</table>';
	    */
	    
			$answer_str = '';
			//--DEBUG--// ITS_debug(array('MODE: '.$mode,'STYLE: '.$this->data[0]));
			//echo 'MODE '.$mode.'  STYLE '.$style ;
  			switch ($mode){
  			//-----------------------------//
    	  case 0: 
  			//-----------------------------//
  			  for ($i=1;$i<=$N;$i++){ //echo htmlspecialchars($this->caption[$i]);
    				//$tb = '<div class="ITS_ANSWER_BOXED">'.$this->caption[$i].'<span class="'.$style.'">'.$answer[$i].'</span></div>';
    				//$tb = new ITS_table('b',1,2,array($this->caption[$i],'<span class="'.$style.'">'.$this->data[$i].'</span>'),array(1,99),'ITS_ANSWER_BOXED');

						$tb = '<table class="ITS_ANSWER_BOXED"><tr><td class="ITS_ANSWER_BOXED_ALPH">'.$this->caption[$i].'</td><td class="ITS_ANSWER_BOXED"><span class="'.$style.'">'.$this->data[$i].'</span></td></tr></table>';
						//echo '<p>|table class="ITS_ANSWER_BOXED"|tr|td class="ITS_ANSWER_BOXED_ALPH"|'.$this->caption[$i].'|td|td class="ITS_ANSWER_BOXED"|span class="'.$style.'"|'.$this->data[$i].'|span|td|tr|table|<p>';
						$answer_str .= $tb;
    		  }
  			break;
  			//-----------------------------//
    	  case 1: 
  			//-----------------------------//
  			  for ($i=1;$i<=$N;$i++){  // onclick=ITS_content_select2(this)
    			  //!!! MOD SPAN -> DIV
					  $answer_str .= '<span name="ITS_MC" id="choice_'.$i.'_'.$N.'" class="'.$style.'" qid="'.$qid.'" n="'.$i.'">'.$this->data[$i].'</span>';
    		  } 
    		break;
  			//-----------------------------//
  			case 2: 
  			//-----------------------------//
				  for ($i=1;$i<=$N;$i++){ 
						$answer[$i] = $this->data[$i];
						$weight[$i] = $this->weight[$i];
						$image[$i]  = $this->image[$i];
    				$tb = '<table class="ITS_ANSWER_BOXED"><tr>'.
						  '<td class="ITS_ANSWER_BOXED_ALPH">'.$this->caption[$i].'</td>'.
						  '<td class="ITS_ANSWER_BOXED"><span class="'.$style.'">'.$answer[$i].'</span></td>'.
						  '<td><div class="CHOICE_WEIGHT">'.$weight[$i].'</div></td>'.
    				      '<td><div class="CHOICE_ACTIVE">'.$image[$i].'</div></td>'.
    				      '<td><form name="browser" action="server_browser.php"><input type="hidden" name="id" value="'.$qid.'"><input type="hidden" name="col_name" value="image'.$i.'"></form></td>'.
    				      '</tr></table>'; 				
    				$answer_str .= $tb;
    		  }
					//--DEBUG--// ITS_debug($answer);
    		break;
  		  }
		break;
	  //-------------------------------------------//
	  case 2: //---- TILED ----//
	  //-------------------------------------------//
			 $answer_str = '<table class="'.$style.'"><tr>';
		   for ($i=1;$i<=$N;$i++){
					$answer_str .= '<td>'.$this->data[$i].'</td>';  
			 }
		 	 $answer_str .= '</tr></table>';	 
		 break;
		//-------------------------------------------//
	  case 3: //---- MATRIX ----//
	  //-------------------------------------------//
			 $answer_str = '<table class="'.$style.'">';
		   for ($i=1;$i<=$N;$i++){
			 $new_row = $i%2;
			    if ($new_row) {$answer_str .= '<tr>';}
					$answer_str .= '<td>'.$this->data[$i].'</td>';  
					if (!$new_row) {$answer_str .= '</tr>';}
			 }
		 	 $answer_str .= '</table>';	 
		 break;
		//-------------------------------------------//
	  case 4: //---- IMAGES ----//
	  //-------------------------------------------//
		  /*
			 $answer_str = '';
		   for ($i=1;$i<=$N;$i++){
			    $answer_str = $answer_str.'<div class="ITS_ANSWER_IMG">'.$this->data[$i].'<br>'.$this->caption[$i].'</div>';
			 } */			
     $answer_str = '';
		 //----------------//
		   switch ($mode){
  			//-----------------------------//
    	  case 0: 
  			//-----------------------------//
           for ($i=1;$i<=$N;$i++){
        	     $answer_str .= '<span class="'.$style.'">'.$this->data[$i].'<br>'.$this->caption[$i].'</span>';
           }	
  			break;
  			//-----------------------------//
    	  case 1: // ACTIVE
  			//-----------------------------//
        	 for ($i=1;$i<=$N;$i++) {
					 //choice_'.$i.'_'.$N.'
        	     $answer_str .= '<span name="ITS_MC" id="choice_'.$i.'_'.$N.'" class="'.$style.'" qid="'.$qid.'" n="'.$i.'" onclick=ITS_content_select2(this)><div class="ITS_ANSWER_IMG">'.$this->data[$i].'</div></span>';		 
    					 //$answer_str = $answer_str.'<span class="'.$style.'" qid="'.$qid.'" n="'.$i.'"><div class="ITS_ANSWER_IMG">'.$this->data[$i].'</div></span>';
    					 //<span class="'.$style.'">'.$this->data[$i].'</span>
           }
    		break;
  			//-----------------------------//
  			case 2: 
  			//-----------------------------//
        	 for ($i=1;$i<=$N;$i++) {
			   //$answer[$i] = createEditTable('ANSWER'.$i,$this->data[$i],'ITS_ANSWER');
			   //$weight[$i] = createEditTable('WEIGHT'.$i,$this->weight[$i],'ITS_WEIGHT');
							 
				 $answer[$i] = $this->data[$i];
				 $weight[$i] = $this->weight[$i];		
							 				 
			   //$answer_str = '<table class="ITS_ANSWER_BOXED"><tr><td class="ITS_ANSWER_BOXED_ALPH">'.$this->caption[$i].'</td><td class="ITS_ANSWER_BOXED"><span class="'.$style.'">'.$answer[$i].'</span></td><td><div class="CHOICE_WEIGHT">'.$weight[$i].'</div></td></tr></table>';
        	     $answer_str .= '<span name="xxx" id="xxx" class="'.$style.'" qid="'.$qid.'" n="'.$i.'"><div class="ITS_ANSWER_IMG">'.$answer[$i].'</div></span><div class="CHOICE_WEIGHT" style="float:left">'.$weight[$i].'</div>';		 
    					 //$answer_str = $answer_str.'<span class="'.$style.'" qid="'.$qid.'" n="'.$i.'"><div class="ITS_ANSWER_IMG">'.$this->data[$i].'</div></span>';
    					 //<span class="'.$style.'">'.$this->data[$i].'</span>
           }
    		break;
  		  }
		 //----------------//
		 break;
		//-------------------------------------------//		
		}
		$this->str = '<div class="ITS_ANSER">'.$answer_str.'</div>';
		}
} //eo:class
//=====================================================================//
?>
