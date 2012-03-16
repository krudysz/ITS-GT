<?php
//=====================================================================//
/*
ITS_question class - render ITS question according to type.

 type: |S|M|P|C|MC|O| - |short answer|matching|paragraph|calculated|multiple choice|other

		Constructor: ITS_question(student_id,file_name,db_name,table_name)
		
								 ex. $ITS_question = new ITS_question(90001,"its","user_cpt");
								
	 Author(s): Greg Krudysz | Aug-28-2008
	 Last Revision: Nov-04-2010
*/
//=====================================================================//
class ITS_question
{
  var $student_id;				// ITS student ID
	var $file_name;					// initial CPT file name (e.g. "ch7_iCPT.txt")
	var $db_name;					 	// DB name (e.g. "its")
	var $tb_name;			  		// question table name
	var $max_cols;					// max number of prob. entries for each node
	var $cpt_array;					// CPT array
	var $cpt_attrib;				// CPT table attributes
	
	public $Q_type;					// question type can take: |S|M|P|C|MC|O|
	public $Q_num;
	public $Q_title;
	public $Q_question;
	public $Q_image;
	public $Q_answers;
	public $Q_answers_config;
	public $Q_question_config;
	public $Q_category;
	public $style;
	public $Q_answers_permutation;
	
	var $Q_answers_fields = array();
	var $Q_answers_values = array();
	var $Q_weights_values = array();
	var $edit_flag = 0;
	
	public function set_Q_type($type)   { $this->Q_type = $type; }
	public function get_Q_type()        { return $this->Q_type;  }
	
	public function set_Q_title($title) { $this->Q_title = $title; }
	public function get_Q_title()       { return $this->Q_title;   }
	
	public function set_Q_question($question) { $this->Q_question = $question; }
	public function get_Q_question()       		{ return $this->Q_question;      }
	
  public function set_Q_answers($answers)  { $this->Q_answers = $answers; }
	public function get_Q_answers()       	 { return $this->Q_answers;     }
	
	// Constructor //======================================================//
  function __construct($student_id,$db_name,$table_name){
	//=====================================================================//
		global $db_dsn,$db_name,$tb_name,$db_table_user_state,$mimetex_path,$files_path;
		
	  $this->student_id  	= $student_id;
		$this->db_name      = $db_name;
    $this->tb_name      = $table_name;
		$this->cpt_array	  = array();
		$this->cpt_attrib   = array();
		$this->style				= 'ITS';
		$this->mimetex_path = $mimetex_path;
		$this->files_path   = $files_path;
	}
	//=====================================================================//
  function load_DATA_from_DB($q_num) {
	//=====================================================================//	
	  // Pull out webct data
		$ans = "select id,qtype,title,question,image,answers,answers_config,question_config,category from " . $this->tb_name . " WHERE id=" . $q_num;
		//echo '<p>'.$ans;
		$res = mysql_query($ans);
		if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		$data = mysql_fetch_array($res);
		
		//mysql_close($link);

	  self::load_DATA($data);	
	}
  //=====================================================================//
  function load_DATA($data) {
	//=====================================================================//
		$this->Q_num			= $data[0];				
		$this->Q_type 		= strtolower($data[1]);
		$this->Q_title 		= $data[2];
		$this->Q_question = $data[3];
		$this->Q_image 		= $data[4];
		$this->Q_answers 	= $data[5];
		if (isset($data[6])){ $this->Q_answers_config  = $data[6];  }
		else 								{ $this->Q_answers_config  = 1;         } // default
		if (isset($data[7])){ $this->Q_question_config = $data[7];  }
		else 								{ $this->Q_question_config = 1;         } // default
		$this->Q_category = $data[8];
	}
	//=====================================================================//
	function render_TITLE(){
	//=====================================================================//
  	// Question info and debug
	  echo "<input type=hidden id=ITS_question_info value=".$this->Q_num." name=".$this->Q_type.">";
	
	  $TABLE_TITLE = createEditTable('TITLE',$this->Q_title,"ITS");
	  
		$title_str = "<p><DIV class=SubHeading>".$TABLE_TITLE."</DIV>";
		return $title_str;
	}
	//=====================================================================//
	function render_QUESTION_check(){
	//=====================================================================// 
	  if ($this->Q_type == 'c'){ // replace question variable {v} with rv //
		  $query = "SELECT vals FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		  $res  = mysql_query($query);
		  $vals = mysql_fetch_array($res);
		
		  // get token fields
		 	$fields = "val1,min_val1,max_val1";
		 	for ($i=2;$i<=$vals[0];$i++){
		 			$fields = $fields.",val".$i.",min_val".$i.",max_val".$i;
		 	}
		
		 	$query = "SELECT ".$fields." from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 	$res = mysql_query($query);
		 	if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 	$vdata = mysql_fetch_array($res);
		
		 	$question = $this->Q_question;
		 	$rnv = array();
		 	for ($i=1;$i<=$vals[0];$i++){
		 		 $rnv[($i-1)] = rand($vdata["min_val".$i],$vdata["max_val".$i]);
		 		 $question = str_replace($vdata["val".$i],$rnv[($i-1)],$question);
		 	}
		 	$this->Q_question = $question;
	  }
	  $question_check_str = self::render_QUESTION();
		return $question_check_str;
	} 
	//=====================================================================//
	function render_QUESTION_simple(){
	//=====================================================================//
	 //echo "<DIV class=Question>".$this->Q_question."</DIV>";
	 $question_simple_str = $this->Q_question;
	 
	 return $question_simple_str;
	}
	//=====================================================================//
	function render_QUESTION() {
	//=====================================================================//
	  $ques_str = $this->Q_question;
		$ques1 = preg_replace("/MIMETEX_PATH/",$this->mimetex_path,$ques_str);
		$ques  = preg_replace("/RESOURCE_PATH/",$this->files_path,$ques1);
		
	 $TABLE_QUESTION = createEditTable('QUESTION',$ques,"ITS_QUESTION");
	 
	 //--- IMAGE ----------------//
	 //echo getcwd() . "\n"; //die();
	 //echo $this->files_path;
	 $img = '';
	 if (!empty($this->Q_image)){ $img = '<img src="'.$this->files_path.$this->Q_image.'" alt="'.$this->files_path.$this->Q_image.'">'; }
	 else{  											$img = '';	 														 }
	 //--------------------------//
	//echo $this->Q_question_config;

	//if ($this->Q_question_config == 2){ //---- TITLED ----//
	   //$question_str = '<table><tr><td>'.$TABLE_QUESTION.'</td></tr></table>'
		 /*
		 $question_str = '<div class="ITS_question_text2">'.$TABLE_QUESTION.'</div>'
		 							 	.'<div class="ITS_question_img">'.$img.'</div>';
		 */
										
		 //$question_str = '<ul class="ITS_list" style="display: inline;"><li>'.$TABLE_QUESTION.'</li><li>'.$img.'</li></ul>';
		 //$tb = new ITS_table('question_image',1,2,array($TABLE_QUESTION,$img),array(60,40),'ITS_QUESTION');
		 //$question_str = $tb->str;
	//}else{
	 //$question_str = "<p><DIV class=Question>" . $TABLE_QUESTION;
	 //$question_str = $this->Q_question;
	 //$question_str = $question_str . "</DIV>";
	 //}
	 $question_str = '<div class="ITS_question_text">'.$img.$TABLE_QUESTION.'</div>';
	 return $question_str;
	}
	//=====================================================================//
	function get_data(){
	//=====================================================================//
	$data = array($this->Q_num,
								$this->Q_type,
								$this->Q_title,
								$this->Q_question,
								$this->Q_image,
								$this->Q_answers,
								$this->Q_category);
	return $data;
	}
  //=====================================================================//
	function get_ANSWERS_data_from_DB(){
	//=====================================================================//
	 switch ($this->Q_type){
		//-------------------------------------------//
	  case 's':
	  //-------------------------------------------//
		 break;
	  //-------------------------------------------//
	  case 'mc':
	  //-------------------------------------------//
		 $n = $this->Q_answers;
		 $fields = "answer1";
		 for ($i = 2; $i <= $n; $i++){
		 		 $fields = $fields.",answer".$i;
		 }
		 $query = "SELECT ".$fields." from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;

		 $res = mysql_query($query);
		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $answers = mysql_fetch_array($res);
		 
		 $this->Q_answers_fields = $fields;
		 $this->Q_answers_values = $answers;
    break;
		//-------------------------------------------//
	  case 'p':
	  //-------------------------------------------//
    	// TEMPLATE
		 $query = "SELECT template from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 $res = mysql_query($query);
		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $template = mysql_fetch_array($res);
		 if (!empty($template)){
		 		$this->Q_answers_values = $template;
		 }
		 break;
		//-------------------------------------------//
	  case 'm':
	  //-------------------------------------------//
		 $n = $this->Q_answers;
		 $L_fields = "L1";
		 $R_fields = "R1";
		 for ($i = 2; $i <= $n; $i++){
		 		 $L_fields = $L_fields.",L".$i;
				 $R_fields = $R_fields.",R".$i;
		 }
		
		 $Lquery = "SELECT ".$L_fields." from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 $Rquery = "SELECT ".$R_fields." from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num; 		
		 $Lres = mysql_query($Lquery);
		 $Rres = mysql_query($Rquery);
		 if (!$Lres) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $L_answers = mysql_fetch_array($Lres);
		 $R_answers = mysql_fetch_array($Rres);
		 
		 $this->Q_answers_fields = array($L_fields,$R_fields);
		 $this->Q_answers_values = array($L_answers,$R_answers);
		break;
		//-------------------------------------------//
	  case 'c':
	  //-------------------------------------------//
		 break;
   }
	}
	//=====================================================================//
	function render_ANSWERS($name,$mode){
	//=====================================================================//
	 $answer_str = '';
	 switch ($this->Q_type){
		//-------------------------------------------//
	  case 's':
	  //-------------------------------------------//
		 $answer_str = $answer_str 
		      .'<form action=score.php method=post name=form1>'
		 			.'<textarea class=TXA_ANSWER name=TXA_ANSWER width=100% cols=80% rows=3></textarea>'		
		 			.'<p></form>';
		 break;
	  //-------------------------------------------//
	  case 'mc':
	  //-------------------------------------------//
		 $rows = $this->Q_answers;
		 $width = array(2,2,96);
		 
		 $answer = array();
		 $str = '<p><div class="ITS_ANSWER_IMG">';
		 		for ($i=1;$i<=$rows;$i++){
		 	 		if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			 		else 			        { $style = "ITS_ANSWER"; }

					if ($this->Q_answers_config == 3) {$style = "ITS_ANSWER";}
			 		// solution check and selection
			 		$checked = 'false';
			 
			 		//if (!empty($this->Q_weights_values)){
			 		//   if ($this->Q_weights_values[$i-1]==100){ $checked = 'checked = true'; }
			 		//}	  
					//echo $name.'<p>';
			 		$chk  = '<input type="radio" name="'.$name.'" id="'.$name.'" value="'.chr($i+64).'" "'.$checked.'">';
			 		//$chk  = "<input type=checkbox name=".$name."[".($i)."] value=".chr($i+64)." ".$checked.">";

			 		//$data = array('<span id="TextAlphabet'.chr($i+64).'" class="TextAlphabet">'.chr($i+64).'.</span>',$chk,$edit_tb); 
		 	 		//$tb   = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$style);
			 		//$answer[$i] = $tb->str;

					//$style = "ITS_ANSWER";
					//$edit_tb2 = createEditTable('ANSWER'.$i,'<span id="TextAlphabet'.chr($i+64).'" class="TextAlphabet">'.chr($i+64).'.</span>'.trim($this->Q_answers_values["answer".$i]),$style);
					//$str = $str.'<li name="answerLab_active" id="answerLab_active" onclick=ITS_content_select(this)><span id="TextAlphabet'.chr($i+64).'" class="TextAlphabet">'.chr($i+64).'.</span>'.trim($this->Q_answers_values["answer".$i]).'</li>';
				  
					$caption[$i] = '<span id="TextAlphabet'.chr($i+64).'" class="TextAlphabet">'.chr($i+64).'.</span>';  //chr($i+64)
					//$answer[$i]  = trim($this->Q_answers_values["answer".$i]);
					
					$ans = $this->Q_answers_values["answer".$i];
					//$ans = '|IMG src="MIMETEX_PATH\text{real}\left(sqrt{2}e^{j(123t+0.25pi)}\right)"|';
					$ans2 = preg_replace("/MIMETEX_PATH/",$this->mimetex_path,$ans);
					$ans2 = preg_replace("/RESOURCE_PATH/",$this->files_path,$ans2);
					
					//echo ' | '.$b.'<p>';//die($ans);
					//$ans = $this->Q_answers_values["answer".$i];
					$answer[$i] = trim($ans2);
				}
				//var_dump($this->Q_answers_config);//die();
				$answer_str = new ITS_configure($this->Q_num,$caption,$answer,$this->Q_answers_config,$mode);
				//$str = $str.'</ul>';
				$str = $answer_str->str;
				
		 $answer_str = '<div class="ITS_ANSWER">'.$str.'</div>';
    break;	
		//-------------------------------------------//
	  case 'p':
	  //-------------------------------------------//
    	// TEMPLATE
		 $template = $this->Q_answers_values;
		 if (!empty($template)){
		 		$TABLE_TEMPLATE = createEditTable('TEMPLATE',$template[0],"ITS_TEMPLATE");
	   		$answer_str = $answer_str .'<br>'.$TABLE_TEMPLATE;
		 }
		 
		 // ANSWERS
		 for ($n=1;$n<=$this->Q_answers;$n++){  
		    $answer_str = $answer_str.'<textarea class="ITS_TA" id="TXA_ANSWER" name="'.$name.'"></textarea>';
		 }
     break;
		//-------------------------------------------//
	  case 'm':
	  //-------------------------------------------//	 
		 //$this->mode = 1;
		 $n = $this->Q_answers;
		 $answers_values = $this->Q_answers_values;
		 $L_answers = $answers_values[0];
		 $R_answers = $answers_values[1];

		 $ii = 1;
		 $L_list = '';
		 for ($i=1;$i<=$n;$i++) {
			//echo $L_answers["L".$i].' ~ '.$R_answers["R".$i].'<p>';
			$check_NULL  = !strcmp($L_answers["L".$i],'NULL');
			$check_EMPTY = empty($L_answers["L".$i]);
			if (!(($check_NULL) OR ($check_EMPTY))) { $L[$i-1] = $i;   } 
			else 									 									{ $L[$i-1] = -$i; } //$L_list .= $i.','; }//$nn++; }
			//$L_answers["L".$ii] = $L_answers["L".$i];
			//$ii++;
		 }
		 $R = $L; //range(1,count($R_answers)/2);
		 //print_r($R); die();
		 //echo '$mode:'.$mode;
		 if ( $mode ) { 
		    $inactive = '';
				//$Lperm = $L;
				shuffle($R); 
				//$R = array(-1,7,2,-6,3,5,4); // C,E,G,F,B
				//$R = array(3,-1,7,4,2,-6,5); // C,E,G,F,B
				//echo '<p>AFTER SHUFFLE: '.implode(',',$R).'<p>';
				//print_r($R); echo '<p>count(R) mode=1: '.count($R).'<p>';
		 }
		 else         { 
		    $inactive = '_inactive';  //print_r($this->Q_answers_permutation);
				
				// Config from DB
				$query = 'SELECT comment FROM stats_'.$this->student_id.' WHERE question_id='.$this->Q_num.' ORDER BY id';
				//echo $query;die();
        $res = mysql_query($query);
		    if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		    $C = mysql_fetch_array($res);
				//print_r($C);//echo count($C); die();
				//---------
				$R = explode(',',$C[0]); //$this->Q_answers_permutation;
				//print_r($R);
				//echo '<p>AFTER SHUFFLE (INACTIVE): '.implode(',',$R).'<p>';die();
				//echo '<p>count(R) mode=0: '.count($R).'<p>'; die();
		 }

		 // construct ANSWERS table
		 $rows  = $this->Q_answers;
		 $width = array(10,40,50);
		 
		 // Left table
		 $nn = 0;
		 $tb_L_str = '';

		 //echo '<p>';print_r($L_idx);die();
		 $nn = count($R);
		 $ii = 1;
		 for ($i=1;$i<=$nn;$i++){	 
		   $check_NULL  = !strcmp($L_answers["L".$i],'NULL');
			 $check_EMPTY = empty($L_answers["L".$i]);
			 if (!(($check_NULL) OR ($check_EMPTY))) {	
				  if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
					
					$bank = '';
					for ($b=1;$b<=$n;$b++) {
					  //echo '<p>id='.$L_idx[$i-1].'_'.$b.'_'.$nn.'_'.$n.'<p>';
						$bank .= '<label class="ansCheck'.$inactive.'" id="label_check_'.$i.'_'.$b.'_'.$nn.'_'.$n.'" for="check_'.$i.'_'.$b.'_'.$nn.'_'.$n.'"><input type="checkbox" class="ansCheck" id="check_'.$i.'_'.$b.'_'.$nn.'_'.$n.'" name="checkL"/>'.chr($b+64).'</label>';
					}
			    $style   ='';
					//echo '<p>'.$L_idx[$i-1].' - '.$L_answers["L".$L_idx[$i-1]].'<p>';
					//DEBUG: $edit_tb = createEditTable('L'.$i,"L".$i.' * '.$L_answers["L".$i],$style);
		   		$edit_tb = createEditTable('L'.$i,$L_answers["L".$i],$style);
					$idx     = '<b>'.$ii.'. </b>';
					$banks   = '<div class="ansCheck">'.$bank.'</div>';
			    $data    = array($idx,$banks,$edit_tb); 
		 	    $tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$style);
					//$tb_L_str = $tb_L_str.$tb_L->str;
					$tb_L_str .= '<li name="matchingLeft">'.$tb_L->str.'</li>';		
					$ii++;
			 }				
		 }
		 //$tb_L_str = '<DIV class="ITS_ANSWER">'.$tb_L_str.'</div>';
		 $tb_L_str = '<ul id="sortable1" class="connectedSortable">'.$tb_L_str.'</ul>';	 //connectedSortable
		 //die('EHO: '.$nn);
		 
	 	 // Right table
		 //##
		 //print_r($R_answers);die();
		 $widthR = array(10,90);
		 $tb_R_str = '<table class="ITS_ANSWER_BOXED">';	
		 for ($i=1;$i<=$n;$i++){
		 		//##echo '<p>'.$i.' - '.$R[$i-1].' - '.$R_answers["R".abs($R[$i-1])].'<p>'; 
		    //if ( $R[$i-1] > 0 ) { //
				//if (!(is_null($R_answers["R".abs($R[$i-1])]))){
					if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
					//$style = '';	
					
					$label = '<span class="TextAlphabet">'.chr($i+64).'</span>';
					//DEBUG: $edit_tb = createEditTable('R'.$i,'('.$R[$i-1].') * '.$R_answers["R".abs($R[$i-1])],$style);
					//echo ($R[$i-1]).' # '.(abs($R[$i-1])).'<p>';
					$edit_tb = createEditTable('R'.$i,$R_answers["R".(abs($R[$i-1]))],$style);
					//==$tb_R = new ITS_table('RT'.$this->Q_type,1,2,array($label,$edit_tb),array(30,70),'ITSxx');
					$data    = array($label,$edit_tb); 
					$style = 'ZZZ'; //CHOICE_ACTIVE
					$tb_R_str .= '<tr><td class="ITS_ANSWER_BOXED_ALPH">'.$label.'</td><td class="ITS_ANSWER_BOXED"><div class="'.$style.'">'.$edit_tb.'</div></td></tr>';	 
				//}
		 }				
		$tb_R_str .= '</table>';	
		$this->Q_answers_permutation = $R;			
							
		$tb = new ITS_table('ANSWER_'.$this->Q_type,1,2,array($tb_L_str,$tb_R_str),array(50,50),'ITS');		
		$answer_str = $answer_str . $tb->str;

		break;
		/* ======== OLD SOLUTION ========== 
		 $n = $this->Q_answers;
		 $answers_values = $this->Q_answers_values;
		 $L_answers = $answers_values[0];
		 $R_answers = $answers_values[1];
		 
		 $R = range(1,count($R_answers)/2);
		 shuffle($R);

		 // construct ANSWERS table
		 $rows  = $this->Q_answers;
		 $width = array(1,99);
		 
		 // Left table
		 $tb_L_str = '';
		 for ($i=1;$i<=$n;$i++){
			$check_NULL = !strcmp($L_answers["L".$i],'NULL');
			$check_EMPTY = empty($L_answers["L".$i]);
			//echo $L_answers["L".$i].'<p>'; //(!$check_EMPTY)
		 	  if (!(($check_NULL) OR ($check_EMPTY))) {
				  if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
			  
		   		$edit_tb = createEditTable('L'.$i,$L_answers["L".$i],$style);
			    $data    = array("<span class=TextAlphabet>".chr($i+64).".</span>",$edit_tb); 
		 	    $tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$style);
			    $tb_L_str = $tb_L_str.$tb_L->str;
				}
		 }
		 $tb_L_str = '<DIV class="ITS_ANSWER">'.$tb_L_str.'</div>';
		 
	 	 // Right table
		 $tb_R_str = '';	
		 for ($i=1;$i<=$n;$i++){
		    if (!(is_null($R_answers["R".$i]))){
					if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
		    	$edit_tb = createEditTable('R'.$i,$R_answers["R".$R[$i-1]],$style);		 
		 	 		
					$data    = array('<input type=text size=1 maxlength=1 name="'.$name.'" id="'.$name.'" value="">',$edit_tb); 	
					//$data    = array('<input type=text size=1 maxlength=1 name="'.$name.'['.$i.']" value="">',$edit_tb); 
		 	 		$tb_R    = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$style);
			 		$tb_R_str = $tb_R_str.$tb_R->str;
				}
		 }				
		 $tb_R_str = '<DIV class="ITS_ANSWER">'.$tb_R_str.'</div>';
							
		$this->Q_answers_permutation = $R;			
							
		$tb = new ITS_table('ANSWER_'.$this->Q_type,1,2,array($tb_L_str,$tb_R_str),array(50,50),'ITS');		
		$answer_str = $answer_str . $tb->str;
		
		$new = '<ol id="selectable">'.
        	 '<li class="ui-state-default">1</li>'.
        	 '<li class="ui-state-default">2</li>'.
        	 '<li class="ui-state-default">3</li>'.
        	 '<li class="ui-state-default">4</li>'.
           '</ol>';
		echo $new;		 

		break;
		======== OLD SOLUTION ==========*/
		//-------------------------------------------//
	  case 'c':
	  //-------------------------------------------//
		 // ANSWERS
		 $answer_str = $answer_str
		     .'<textarea class="TXA_ANSWER" id="ITS_TA" name="'.$name.'"></textarea>';		
			
			//."<textarea id=TXA_ANSWER name=TXA_ANSWER></textarea>";	 	 
		/*	 
				 $answer_str = $answer_str
		     ."<form action=score.php method=post onsubmit=return whatFile() name=form1>"
		     ."<textarea id=TXA_ANSWER name=TXA_ANSWER></textarea>"		
				 ."<p></form>";		 
		 */
		 break;
		//-------------------------------------------//
	 }
	 return $answer_str;
	  // SUBMIT BUTTON
		//echo "<p><input type=submit name=score_question value=Submit>";
  }
		//=====================================================================//
	function render_ANSWERSY($name,$mode){
	//=====================================================================//
	 $answer_str = '';
	 switch ($this->Q_type){
		//-------------------------------------------//
	  case 'm':
	  //-------------------------------------------//	
		 $n = $this->Q_answers;
		 $answers_values = $this->Q_answers_values;
		 $L_answers = $answers_values[0];
		 $R_answers = $answers_values[1];
		 
		 $R = range(1,count($R_answers)/2);
		 shuffle($R);

		 // construct ANSWERS table
		 $rows  = $this->Q_answers;
		 $width = array(1,99);
		 
		 // Left table
		 $tb_L_str = '';
		 for ($i=1;$i<=$n;$i++){
			$check_NULL = !strcmp($L_answers["L".$i],'NULL');
			$check_EMPTY = empty($L_answers["L".$i]);
			//echo $L_answers["L".$i].'<p>'; //(!$check_EMPTY)
		 	  if (!(($check_NULL) OR ($check_EMPTY))) {
				  if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
			    $style ='';
		   		$edit_tb = createEditTable('L'.$i,$L_answers["L".$i],$style);
					/*
			    $data    = array("<span class=TextAlphabet>".chr($i+64).".</span>",$edit_tb); 
		 	    $tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$style);
					$tb_L_str = $tb_L_str.$tb_L->str;
					*/
					$tb_L_str .= '<li class="ui-state-highlight">'.$edit_tb.'</li>';						
				} else {
				  $edit_tb = createEditTable('L'.$i,$L_answers["L".$i],'ITS_ANSWER');
				  $tb_L_str .= '<li class="ui-state-highlight">&nbsp;</li>';
				}
		 }
		 //$tb_L_str = '<DIV class="ITS_ANSWER">'.$tb_L_str.'</div>';
		 $tb_L_str = '<ul id="sortable1" class="connectedSortable">'.$tb_L_str.'</ul>';	
		 
	 	 // Right table
		 $tb_R_str = '';	
		 for ($i=1;$i<=$n;$i++){
		    //if (!(is_null($R_answers["R".$i]))){
					if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
		    	$edit_tb = createEditTable('R'.$i,$R_answers["R".$R[$i-1]],$style);		 
		 	 		/*
					$data    = array('<input type=text size=1 maxlength=1 name="'.$name.'" id="'.$name.'" value="">',$edit_tb); 	
					//$data    = array('<input type=text size=1 maxlength=1 name="'.$name.'['.$i.']" value="">',$edit_tb); 
		 	 		$tb_R    = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$style);
			 		$tb_R_str = $tb_R_str.$tb_R->str;
					*/
					//$tb_R_str .= '<input type="radio" id="radio'.$i.'" name="radio" /><label for="radio'.$i.'">C.</label>';			
					//'<div class="radio'.$i.'">'.
					$tb_R_str .= '<form>'.
          	'<div class="radio" style="display:inline;">'.
          		'<input type="checkbox" id="radio'.$i.'1" name="radio" /><label for="radio'.$i.'1">A.</label>'.
          		'<input type="checkbox" id="radio'.$i.'2" name="radio" /><label for="radio'.$i.'2">B.</label>'.
          		'<input type="checkbox" id="radio'.$i.'3" name="radio" /><label for="radio'.$i.'3">C.</label>'.
							'<input type="checkbox" id="radio'.$i.'N" name="radio" checked="checked"/><label for="radio'.$i.'N">&nbsp;</label>'.
							'<span style="float:right;border:1px solid red;vertical-align:middle;">'.$edit_tb.'</span>'.
          	'</div>'.
          '</form>';
				//}
		 }				
		 //$tb_R_str = '<DIV class="ITS_ANSWER">'.$tb_R_str.'</div>';
		$tb_R_str = '<div style="float:left;border:1px solid brown;width: 60%">'.$tb_R_str.'</div>';		
		$this->Q_answers_permutation = $R;			
							
		$tb = new ITS_table('ANSWER_'.$this->Q_type,1,2,array($tb_L_str,$tb_R_str),array(50,50),'ITS');		
		$answer_str = $answer_str . $tb->str;

		break;
		//-------------------------------------------//
	 }
	 return $answer_str;
	  // SUBMIT BUTTON
		//echo "<p><input type=submit name=score_question value=Submit>";
  }
	//=====================================================================//
	function render_ANSWERSX($name,$mode) {
	//=====================================================================//
	 $answer_str = '';
	 switch ($this->Q_type){
		//-------------------------------------------//
	  case 'm':
	  //-------------------------------------------//	 
		
		 $n = $this->Q_answers;
		 $answers_values = $this->Q_answers_values;
		 $L_answers = $answers_values[0];
		 $R_answers = $answers_values[1];
		 
		 $R = range(1,count($R_answers)/2);
		 shuffle($R);

		 // construct ANSWERS table
		 $rows  = $this->Q_answers;
		 $width = array(1,99);
		 
		 // Left table
		 $tb_L_str = '';
		 for ($i=1;$i<=$n;$i++){
			$check_NULL = !strcmp($L_answers["L".$i],'NULL');
			$check_EMPTY = empty($L_answers["L".$i]);
			//echo $L_answers["L".$i].'<p>'; //(!$check_EMPTY)
		 	  if (!(($check_NULL) OR ($check_EMPTY))) {
				  if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
			    $style ='';
		   		$edit_tb = createEditTable('L'.$i,$L_answers["L".$i],$style);
					/*
			    $data    = array("<span class=TextAlphabet>".chr($i+64).".</span>",$edit_tb); 
		 	    $tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$style);
					$tb_L_str = $tb_L_str.$tb_L->str;
					*/
					$tb_L_str .= '<li class="ui-state-highlight">'.$edit_tb.'</li>';
					
					
				} else {
				  $edit_tb = createEditTable('L'.$i,$L_answers["L".$i],'ITS_ANSWER');
				  $tb_L_str .= '<li class="ui-state-highlight">&nbsp;</li>';
				}
		 }
		 //$tb_L_str = '<DIV class="ITS_ANSWER">'.$tb_L_str.'</div>';
		 $tb_L_str = '<ul id="sortable1" class="connectedSortable">'.$tb_L_str.'</ul>';
		 
	 	 // Right table
		 $tb_R_str = '';	
		 for ($i=1;$i<=$n;$i++){
		    if (!(is_null($R_answers["R".$i]))){
					if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
		    	$edit_tb = createEditTable('R'.$i,$R_answers["R".$R[$i-1]],$style);		 
		 	 		/*
					$data    = array('<input type=text size=1 maxlength=1 name="'.$name.'" id="'.$name.'" value="">',$edit_tb); 	
					//$data    = array('<input type=text size=1 maxlength=1 name="'.$name.'['.$i.']" value="">',$edit_tb); 
		 	 		$tb_R    = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$style);
			 		$tb_R_str = $tb_R_str.$tb_R->str;
					*/
					$tb_R_str .= '<li class="ui-state-highlight">'.$edit_tb.'</li>';
				}
		 }				
		 //$tb_R_str = '<DIV class="ITS_ANSWER">'.$tb_R_str.'</div>';
		$tb_R_str = '<ul id="sortable2" class="connectedSortable">'.$tb_R_str.'</ul>';			
		$this->Q_answers_permutation = $R;			
							
		$tb = new ITS_table('ANSWER_'.$this->Q_type,1,2,array($tb_L_str,$tb_R_str),array(50,50),'ITS');		
		$answer_str = $answer_str . $tb->str;

		break;
		//-------------------------------------------//
	 }
	 return $answer_str;
	  // SUBMIT BUTTON
		//echo "<p><input type=submit name=score_question value=Submit>";
  }
  //=====================================================================//
	function get_ANSWERS_solution_from_DB(){
	//=====================================================================//
  	switch ($this->Q_type){
		//-------------------------------------------//
	  case 's':
	  //-------------------------------------------//
		 break;
	  //-------------------------------------------//
	  case 'mc':
	  //-------------------------------------------//
		 $n = $this->Q_answers;
		 $fields = "weight1";
		 for ($i = 2; $i <= $n; $i++){
		 		 $fields = $fields.",weight".$i;
		 }
		 $query = "SELECT ".$fields." from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;

		 $res = mysql_query($query);
		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $weights = mysql_fetch_array($res);
		
		 $this->Q_weights_values = $weights;
    break;
		//-------------------------------------------//
	  case 'p':
	  //-------------------------------------------//
     break;
		//-------------------------------------------//
	  case 'm':
	  //-------------------------------------------//	  
		die('need solution');

		break;
		//-------------------------------------------//
	  case 'c':
	  //-------------------------------------------//
		 break;
   }
  }
  //=====================================================================//
	function set_ANSWERS_solution($solution){
	//=====================================================================//
		switch ($this->Q_type){
		   //-------------------------------------------//
	     case 's':
	     //-------------------------------------------//
		   break;
	     //-------------------------------------------//
	     case 'mc':
	     //-------------------------------------------//
		   $n = $this->Q_answers;
		   for ($i = 0; $i <= $n-1; $i++){
			 		 if (($solution-1) ==  $i){
					        $weights[$i]=100; 
					 }else{ $weights[$i]=0;   
					 }
		   }
			 $this->Q_weights_values = $weights;
       break;
		   //-------------------------------------------//
	     case 'p':
	     //-------------------------------------------//
       break;
		   //-------------------------------------------//
	     case 'm':
	     //-------------------------------------------//	  

		   break;
		   //-------------------------------------------//
	     case 'c':
	     //-------------------------------------------//
		   break;
    }
	 }
  //=====================================================================//
	function get_ANSWERS_solution(){
	//=====================================================================//
		switch ($this->Q_type){
		   //-------------------------------------------//
	     case 's':
	     //-------------------------------------------//
		   break;
	     //-------------------------------------------//
	     case 'mc':
	     //-------------------------------------------//
		   $n = $this->Q_answers;
			 $mx_weight = max($this->Q_weights_values);

		   for ($i = 0; $i <= $n-1; $i++){
			 		 if ($this->Q_weights_values[$i] == $mx_weight){return chr(65+$i);}
		   }
       break;
		   //-------------------------------------------//
	     case 'p':
	     //-------------------------------------------//
       break;
		   //-------------------------------------------//
	     case 'm':
	     //-------------------------------------------//	  

		   break;
		   //-------------------------------------------//
	     case 'c':
	     //-------------------------------------------//
		   break;
    }
	 }	 
	//=====================================================================//
	
} //eo:class

//=====================================================================//
	function createEditTable($TargetName,$Target,$style)
//=====================================================================//
  {	// eg. createEditTable('TITLE','This is my title');
		$Table= "<table class=".$style.">"
			."<tr>"
			.'<td class="'.$style.'">'
			.'<span id="ITS_'.$TargetName.'_TARGET">'
			.$Target
			.'</span>'
			.'</td>'
			.'<td class="'.$style.'">'
			.'<span class="ITS_QCONTROL" id="ITS_'.$TargetName.'">'
			."<b></b>"
			."</span>"
			."</td>"
			."</tr>"
			."</table>";
	 return $Table;
	}
//=====================================================================//
?>

