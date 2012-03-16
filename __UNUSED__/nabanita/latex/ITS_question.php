<?php
//=====================================================================//
/*
ITS_question class - render ITS question according to type.

 type: |S|M|P|C|MC|O| - |short answer|matching|paragraph|calculated|multiple choice|other

		Constructor: ITS_question(student_id,file_name,db_name,table_name)
		
								 ex. $ITS_question = new ITS_question(90001,"its","user_cpt");
								
	 Author(s): Greg Krudysz | Aug-28-2008
	 Last Revision: Feb-27-2011
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
	var $fields;
	var $timestamp;
	
	public $Q_type;					// question type: |S|M|P|C|MC|O|
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
		$this->fields       = 'id,qtype,title,question,image,answers,answers_config,question_config,category';
	}
	//=====================================================================//
  function load_DATA_from_DB($q_num) {
	//=====================================================================//	
	  // Pull out webct data
		$query = 'SELECT '.$this->fields.' FROM '.$this->tb_name.' WHERE id='.$q_num;
		//echo '<p>'.$query;
		$res = mysql_query($query);
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
	function render_QUESTION_check($conf){ // mode: (0-rand) | (1-DB) parameters
	//=====================================================================// 
	  if ($this->Q_type == 'c'){ // replace question variable {v} with rv //
		//echo 'MODE: '.$mode.' at '.date('l jS \of F Y h:i:s A').'<p>';
		
		  // echo '<font color="blue">render_QUESTION_check()</font>:';
			$query = "SELECT vals FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		  //echo $query;
			$res  = mysql_query($query);
		  $vals = mysql_fetch_array($res);
		
		  // get token fields
		 	$fields = "val1,min_val1,max_val1";
		 	for ($i=2;$i<=$vals[0];$i++){
		 			$fields = $fields.",val".$i.",min_val".$i.",max_val".$i;
		 	}
		
		 	$query = " SELECT ".$fields." from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 	//echo $query;
			$res = mysql_query($query);
		 	if (!$res) { die('Query execution problem in ITS_question: '.msql_error()); }
		 	$vdata = mysql_fetch_array($res);
		
		 	$question = $this->Q_question;
			
			$mode = intval(empty($conf));
			//echo 'MODE: '.$mode.' at '.date('l jS \of F Y h:i:s A').'<p>';
			switch ($mode) {
    		//-------------------------------------------//	
        case 0:
    		//-------------------------------------------//
            $vals = explode(',',$conf);
      		 	for ($i=1; $i<=count($vals) ;$i++) {
      				 //echo $vdata["val".$i].' '.$vals[($i-1)];
      		 		 $question = str_replace($vdata["val".$i],$vals[($i-1)],$question);
      		 	}
            break;
    		//-------------------------------------------//		
        default:
    		//-------------------------------------------//
            $rnv = array();
      		 	for ($i=1;$i<=$vals[0];$i++){
      			   if ($vdata["min_val".$i]==0 & $vdata["max_val".$i]==1) { 
      				   $rnv[($i-1)] = rand(1,9)/10;  // fraction 0.x
      				 } else {
      				   $rnv[($i-1)] = rand($vdata["min_val".$i],$vdata["max_val".$i]);
      				 }
      		 		 //echo $vdata["min_val".$i].'___'.$vdata["max_val".$i].'<br>';
      				 
      				 //echo $vdata["val".$i].' '.$rnv[($i-1)];
      		 		 $question = str_replace($vdata["val".$i],$rnv[($i-1)],$question);
      				 $this->Q_answers_permutation[$i] = $rnv[($i-1)];
      		 	}
						//var_dump($this->Q_answers_permutation);
            break;
    		//-------------------------------------------//
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
	  //NABANITA START
	  $pattern = "/<latex>(.*?)<\/latex>/";
      if(preg_match($pattern, $ques_str, $matches)){
      	  //echo $matches[1]; 
		  $replacement = "<img src=\"".$this->mimetex_path.$matches[1]."\" />"; 
		  $ques_str = preg_replace($pattern, $replacement, $ques_str);	
      }
	  //NABANITA END
	  
		$ques1 = preg_replace("/MIMETEX_PATH/",$this->mimetex_path,$ques_str);
		$ques  = preg_replace("/RESOURCE_PATH/",$this->files_path,$ques1);
			
	  $TABLE_QUESTION = createEditTable('QUESTION',$ques,"ITS_QUESTION");
	 
	  //--- IMAGE ----------------//
	  //echo getcwd() . "\n"; //die();
	  //echo $this->files_path;
	  $img = '';
	  if (!empty($this->Q_image)){ $img = '<img src="'.$this->files_path.$this->Q_image.'" class="ITS_question_img" alt="'.$this->files_path.$this->Q_image.'">'; }
	  else{  											 $img = '';	 														 }
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
		 $weights = "weight1";
		 for ($i = 2; $i <= $n; $i++){
		 		 $fields .= ",answer".$i;
				 $weights .= ",weight".$i;
		 }
		 
		 $query = "SELECT ".$fields." FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 $res = mysql_query($query);
		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $answers = mysql_fetch_array($res);
		 
		 $query = "SELECT ".$weights." FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 $res = mysql_query($query);
		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $weights = mysql_fetch_array($res);
		 
		 $this->Q_answers_fields = $fields;
		 $this->Q_answers_values = $answers;
		 $this->Q_weights_values = $weights;
    break;
		//-------------------------------------------//
	  case 'p':
	  //-------------------------------------------//
    	// TEMPLATE
		 $query = "SELECT template FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
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
		
		 $Lquery = "SELECT ".$L_fields." FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 $Rquery = "SELECT ".$R_fields." FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num; 		
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
		 $query = "SELECT vals FROM ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		 $res = mysql_query($query);
		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $Nanswers = mysql_fetch_array($res);
			
		 //$n = $this->Q_answers;
		 $fields = 'formula';
		 for ($i = 1; $i <= $Nanswers[0]; $i++){
		 		 $fields .= ',val'.$i.',min_val'.$i.',max_val'.$i;
		 }
		 
		 $query = 'SELECT '.$fields.' FROM '.$this->tb_name.'_'.$this->Q_type.' WHERE id='.$this->Q_num;
		 //echo $query.'<p>';
		 $res = mysql_query($query);
		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		 $answers = mysql_fetch_array($res);
		 
		 $this->Q_answers_fields = explode(',',$fields);
		 $this->Q_answers_values = $answers;
		 //$this->Q_weights_values = $weights;
		 break;
   }
	}
	//=====================================================================//
	function render_ANSWERS($name,$mode) { // MODE: 1-Question | 2-EDIT
	//=====================================================================//
	 $answer_str = '';																											 
	 //echo 'MODE '.$mode.'<p>';
	 
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
		 //var_dump($rows);
		 $width = array(2,2,96);
		 //echo '$mode: '.$mode;
		 $answer = array();
		 $str = '<p><div class="ITS_ANSWER_IMG">';
		 		for ($i=1;$i<=$rows;$i++){
		 	 		if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			 		else 			        { $style = "ITS_ANSWER"; }

					if ($this->Q_answers_config == 3) {$style = "ITS_ANSWER";}
			 		// solution check and selection
			 		$checked = 'false';
			 	  
					$weight[$i] = $this->Q_weights_values[$i-1];
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
					
					  //NABANITA START
					  $pattern = "/<latex>(.*?)<\/latex>/";
				      if(preg_match($pattern, $ans, $matches)){
				      	  //echo $matches[1]; 
						  $replacement = "<img src=\"".$this->mimetex_path.$matches[1]."\" />"; 
						  $ans = preg_replace($pattern, $replacement, $ans);	
				      }
					  //NABANITA END
	  
					//$ans = '|IMG src="MIMETEX_PATH\text{real}\left(sqrt{2}e^{j(123t+0.25pi)}\right)"|';
					$ans2 = preg_replace("/MIMETEX_PATH/",$this->mimetex_path,$ans);
					$ans2 = preg_replace("/RESOURCE_PATH/",$this->files_path,$ans2);
					//echo $ans2;
					//echo ' | '.$b.'<p>';//die($ans);
					//$ans = $this->Q_answers_values["answer".$i];
					$answer[$i] = trim($ans2);
				}
				//var_dump($this->Q_answers_config);//die();
				$answer_str = new ITS_configure($this->Q_num,$caption,$answer,$weight,$this->Q_answers_config,$mode);
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
		 
		 switch ($mode){
		    //-------------------------------------------//
        case 2:
        //-------------------------------------------//
          $inactive = '_inactive';
        break;
        //-------------------------------------------//
        case 1:
        //-------------------------------------------//
          $inactive = '';
          shuffle($R); 
          //$R = array(-1,7,2,-6,3,5,4); // C,E,G,F,B
          //$R = array(3,-1,7,4,2,-6,5); // C,E,G,F,B
          //echo '<p>AFTER SHUFFLE: '.implode(',',$R).'<p>';
          //print_r($R); echo '<p>count(R) mode=1: '.count($R).'<p>';
        break;
        //-------------------------------------------//
        default:
        //-------------------------------------------//
          $inactive = '_inactive';  //print_r($this->Q_answers_permutation);
          
          // Config from DB
          $query = 'SELECT comment FROM stats_'.$this->student_id.' WHERE question_id='.$this->Q_num.' ORDER BY id';
          //echo 'IN MODE=default<p>'.$query; //die();
          $res = mysql_query($query);
          if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
          $C = mysql_fetch_array($res);
					//echo 'C<p>'; //print_r($C);//echo count($C); die();
          $R = explode(',',$C[0]); //$this->Q_answers_permutation;
          //print_r($R);
          //echo '<p>AFTER SHUFFLE (INACTIVE): '.implode(',',$R).'<p>';die();
		 }	 

		 // construct ANSWERS table
		 $rows  = $this->Q_answers;
		 $width = array(10,40,50);
		 //--------------------------------//
		 // LEFT TABLE
		 //--------------------------------//
		 $nn = 0;
		 $tb_L_str = '';

		 $class = 'ansCheck';
		 $nn = count($R);
		 //echo '<p>nn: ';print_r($R);die();
		
		 $ii = 1; $ik = 1;
		 for ($i=1;$i<=$nn;$i++){	 
		   $check_NULL  = !strcmp($L_answers["L".$i],'NULL');
			 $check_EMPTY = empty($L_answers["L".$i]);
			 if (!(($check_NULL) OR ($check_EMPTY))) {	
				  if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			    else 			        { $style = "ITS_ANSWER";        }
					
					$bank = '';
					for ($b=1;$b<=$n;$b++) {
					  //echo '<p>id='.$L_idx[$i-1].'_'.$b.'_'.$nn.'_'.$n.'<p>';
						$bank .= '<label class="'.$class.$inactive.'" id="label_check_'.$i.'_'.$b.'_'.$nn.'_'.$n.'" for="check_'.$i.'_'.$b.'_'.$nn.'_'.$n.'"><input type="checkbox" class="'.$class.'" id="check_'.$i.'_'.$b.'_'.$nn.'_'.$n.'" name="checkL"/>'.chr($b+64).'</label>';
					}
			    $style   = '';
					//echo '<p>'.$L_idx[$i-1].' - '.$L_answers["L".$L_idx[$i-1]].'<p>';
					//DEBUG: $edit_tb = createEditTable('L'.$i,"L".$i.' * '.$L_answers["L".$i],$style);
          switch ($mode) {
					  case 0: $ans = $L_answers["L".$i];  break;
            case 1: $ans = $L_answers["L".$i];  break;
            case 2: $ans = createEditTable('L'.$i,$L_answers["L".$i],$style); break;
          }
			    $data[$ii-1] = '<b>'.$ik.'. </b>';
					$data[$ii]   = '<div class="'.$class.'">'.$bank.'</div>';
					//NABANITA START
					  $pattern = "/<latex>(.*?)<\/latex>/";
				      if(preg_match($pattern, $ans, $matches)){
				      	  //echo $matches[1]; 
						  $replacement = "<img src=\"".$this->mimetex_path.$matches[1]."\" />"; 
						  $ans = preg_replace($pattern, $replacement, $ans);	
				      }
					  //NABANITA END
					$ans2 = preg_replace("/MIMETEX_PATH/",$this->mimetex_path,$ans);
					$ans2 = preg_replace("/RESOURCE_PATH/",$this->files_path,$ans2);
					$data[$ii+1] = $ans2; 

		 	    //$tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$class);
					//$tb_L_str = $tb_L_str.$tb_L->str;
					//$tb_L_str .= '<li name="matchingLeft">'.$tb_L->str.'</li>';		
					$ii = $ii+3; $ik++;
			 }				
		 }
		 $tb_L = new ITS_table('ANSWER_M_Left',count($data)/3,3,$data,array(1,4,95),$class);
		 $tb_L_str = $tb_L->str;
		 //$tb_L_str = '<ul id="sortable1" class="ITS_ANSWER_M">'.$tb_L->str.'</ul>';		 
		 //--------------------------------//
	 	 // RIGHT TABLE
		 //--------------------------------//
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
				  //var_dump($R); //echo ($R[$i-1]).' # '.(abs($R[$i-1])).'<p>';
					$ans  = $R_answers["R".(abs($R[$i-1]))];
					//NABANITA START
					  $pattern = "/<latex>(.*?)<\/latex>/";
				      if(preg_match($pattern, $ans, $matches)){
				      	  //echo $matches[1]; 
						  $replacement = "<img src=\"".$this->mimetex_path.$matches[1]."\" />"; 
						  $ans = preg_replace($pattern, $replacement, $ans);	
				      }
					  //NABANITA END
					$ans2 = preg_replace("/MIMETEX_PATH/",$this->mimetex_path,$ans);
					$ans2 = preg_replace("/RESOURCE_PATH/",$this->files_path,$ans2);
					
					$edit_tb = createEditTable('R'.$i,$ans2,$style);
					//==$tb_R = new ITS_table('RT'.$this->Q_type,1,2,array($label,$edit_tb),array(30,70),'ITSxx');
					$data    = array($label,$edit_tb); 
					$style = 'CHOICE_ACTIVE';
					$tb_R_str .= '<tr><td class="ITS_ANSWER_M">'.$label.'</td><td class="ITS_ANSWER_M"><div class="'.$style.'">'.$edit_tb.'</div></td></tr>';	 
				//}
		 }				
		$tb_R_str .= '</table>';
		$this->Q_answers_permutation = $R;			
							
		$tb = new ITS_table('ANSWER_'.$this->Q_type,1,2,array($tb_L_str,$tb_R_str),array(50,50),'ITS');		
    $answer_str = '<div class="ITS_ANSWER">'.$tb->str.'</div>';
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
		$style = '';
		$class = 'ITS';  
		  /*for ($i=1;$i<=$N;$i++){ 
		     $answer[$i] = createEditTable('ANSWER'.$i,$this->data[$i],'ITS_ANSWER');
		     $weight[$i] = createEditTable('WEIGHT'.$i,$this->weight[$i],'ITS_WEIGHT');
				 $tb = '<table class="ITS_ANSWER_BOXED"><tr><td class="ITS_ANSWER_BOXED_ALPH">'.$this->caption[$i].'</td><td class="ITS_ANSWER_BOXED"><span class="'.$style.'">'.$answer[$i].'</span></td><td><div class="CHOICE_WEIGHT">'.$weight[$i].'</div></td></tr></table>';
				 $answer_str .= $tb;
		  }*/
		 // ANSWERS
     switch ($mode) {
       case 0:  $answer_str = ''; break;
			 case 2:  $answer_str = ''; 
			    $vals   = $this->Q_answers_values;
					$fields = $this->Q_answers_fields;	
		
          $tb_C_str = '<table class="ITS_ANSWER_C">';
          $edit_tb = createEditTable('formula',$vals[0],$style);
          $tb_C_str .= '<tr><th colspan="3">formula</th></tr>'
										  .'<tr><td colspan="3" class="ITS_ANSWER_C">'.$edit_tb.'</td></tr>'
          			      .'<tr><th>value</th><th>min value</th><th>max value</th></tr>';
          $Nvals = (count($fields)-1)/3;				
					
          for ($f=0; $f < $Nvals; $f++ ) {
            $val_tb = createEditTable('val'.($f+1),$vals[3*$f+1],$style);
          	$min_tb = createEditTable('min_val'.($f+1),$vals[3*$f+2],$style);
          	$max_tb = createEditTable('max_val'.($f+1),$vals[3*$f+3],$style);
            //$answer_str .= '<font color="blue">'.$f.'</font> = '.$vals[$f].'<br>';
            $tb_C_str .= '<tr><td class="ITS_ANSWER_C">'.$val_tb.'</td><td class="ITS_ANSWER_C">'.$min_tb.'</td><td class="ITS_ANSWER_C">'.$max_tb.'</td></tr>';
          }
          $tb_C_str .= '</table>';
				
        //$tb = new ITS_table('ANSWER_C',1,1,$tb_C_str,array(100),$class);
        //$answer_str = '<center><div class="ITS_ANSWER">'.$tb_C_str.'</div></center>';
				$answer_str = '<center>'.$tb_C_str.'</center>';
			 break;
       default: $answer_str = $answer_str.'<textarea class="TXA_ANSWER" id="ITS_TA" name="'.$name.'"></textarea>';
     } 	
		/*	 
		     ."<form action=score.php method=post onsubmit=return whatFile() name=form1>"
		     ."<textarea id=TXA_ANSWER name=TXA_ANSWER></textarea>"		
				 ."<p></form>";		 
		 */
		 break;
		//-------------------------------------------//
	 }
	 //$mysqldate = date( 'Y-m-d H:i:s', $phpdate );
	 $this->timestamp = date('Y-m-d H:i:s');
	 //echo '<span style="color:#800000;background:pink">ITS_question.php :: render_ANSWERS</span><p>';
	 
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
	function createQuestion(){
	//=====================================================================//
	
	}
  //=====================================================================//
	function renderQuestionForm($action){
	//=====================================================================//
	  $class  = 'text ui-widget-content ui-corner-all ITS_Q';
	  $fields = explode(',',$this->fields);
		
		$form = '<form id="Qform"><fieldset><table class="ITS_newQuestion">';
		for ($i=2; $i<count($fields); $i++) {
		     $name = 'Q_'.$fields[$i];
				 $label = '<label for="'.$fields[$i].'">'.preg_replace('/_/',' ',$fields[$i]).'</label>';
				 //--------------------------//
				 //echo $fields[$i].'<br>';
				 switch ($fields[$i]){
        		   //+++++++++++++++++++++++++++++++++++++++++++//
        	     case 'answers':
        	     //+++++++++++++++++++++++++++++++++++++++++++//
        				   $ansMax = 10;
        				   $Nans = $this->$name;
        					 $sel = '<select id="'.$fields[$i].'" name="'.$fields[$i].'" qid="'.$this->Q_num.'"  style="float:right">';
        					 for ($a=1; $a<=$ansMax; $a++) {
        					     if ($a==$Nans) { $issel = 'selected="selected"'; }
        							 else           { $issel = ''; }
        							 $sel .= '<option '.$issel.' value="'.($a).'">'.$a.'</option>';
        					 }
        				   $sel .= '</select>';
					 
        	 switch ($this->Q_type){
        		   //-------------------------------------------//
        	     case 's':
        	     //-------------------------------------------//
        		   break;
        	     //-------------------------------------------//
        	     case 'mc':
        	     //-------------------------------------------//
        			 $ans = '<table id="ITS_Qans" class="ITS_Qans">';
        			 $n = $this->Q_answers;
               for ($a=1; $a<=$n; $a++) {      
                 $answer_label = '<label for="answer'.$a.'">'.'answer&nbsp;'.$a.'</label>';
                 $answer_field = '<input type="text" name="answer'.$a.'" id="answer'.$a.'" value="'.htmlspecialchars($this->Q_answers_values[$a-1]).'" class="'.$class.'" />';
                 $weight_label = '<label for="weight'.$a.'">'.'weight&nbsp;'.$a.'</label>';
                 $weight_field = '<input type="text" name="weight'.$a.'" id="weight'.$a.'" value="'.htmlspecialchars($this->Q_weights_values[$a-1]).'" class="'.$class.'" />';
        				 $ans .= '<tr><td width="10%">'.$answer_label.'</td><td width="60%">'.$answer_field.'</td><td width="10%">'.$weight_label.'</td><td width="5%">'.$weight_field.'</td></tr>';
							 }
							 $ans .= '</table>';
							 $form .= '<tr><td>'.$label.'<br>'.$sel.'<span id="ansUpdate" class="ansUpdate" action="'.$action.'">update</span></td><td>'.$ans.'</td></tr>';
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
					 //$form .= '<tr>';
				 break;
				 //+++++++++++++++++++++++++++++++++++++++++++//
        	     case 'question':
         //+++++++++++++++++++++++++++++++++++++++++++//
				     $field = '<textarea name="'.$fields[$i].'" id="'.$fields[$i].'" class="'.$class.'" style="height:100px">'.htmlspecialchars($this->$name).'</textarea>';
	           $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">'.$label.'</td><td colspan="5">'.$field.'</td></tr>';
				 break;
				 //+++++++++++++++++++++++++++++++++++++++++++//
        	     case 'question_config':
				//+++++++++++++++++++++++++++++++++++++++++++//
  				   $conf = $this->$name;
						 if ($conf==2){ $cmt = '&nbsp; Question with image on the side'; }
						 else         { $cmt = ''; }
  					 $sel = '<select id="'.$fields[$i].'" name="'.$fields[$i].'" style="margin:0">';
  					 for ($a=1; $a<=2; $a++) {
  					     if ($a==$conf) { $issel = 'selected="selected"'; }
  							 else           { $issel = '';                    }
  							 $sel .= '<option '.$issel.' value="'.($a).'">'.$a.'</option>';
  					 }
  				   $sel  .= '</select>';
						 $field = $sel.$cmt;
	           $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">'.$label.'</td><td colspan="5">'.$field.'</td></tr>';		 
 				 break;
				//+++++++++++++++++++++++++++++++++++++++++++//			 
							 case 'answers_config':
         //+++++++++++++++++++++++++++++++++++++++++++//
  				   $Nans = $this->$name;
						 $cmtArr = array("LIST","TILED","MATRIX","IMAGES");
  					 $sel = '<select id="'.$fields[$i].'" name="'.$fields[$i].'" style="margin:0">';
						 $issel = ''; 
						 $cmt = '';
  					 for ($a=1; $a<=4; $a++) {
  					     if ($a==$Nans) { $issel = 'selected="selected"'; $cmt = '&nbsp;'.$cmtArr[$a-1]; }
								 else { 				  $issel = ''; $cmt = '';  }
  							 $sel .= '<option '.$issel.' value="'.($a).'">'.$a.'</option>';
  					 }
  				   $sel  .= '</select>';
						 $field = $sel.$cmt;
	           $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">'.$label.'</td><td colspan="5">'.$field.'</td></tr>';
				 break;
				 //+++++++++++++++++++++++++++++++++++++++++++//			 
							 case 'category':
         //+++++++++++++++++++++++++++++++++++++++++++//
				     $C = $this->$name;
						 $issel = '';
						 $sel = '<select id="'.$fields[$i].'" name="'.$fields[$i].'" style="margin:0">';
        		 $query = 'SELECT DISTINCT category FROM '.$this->tb_name.' GROUP BY category';
						 //echo $query.'<p>';
        		 $res = mysql_query($query);
        		 if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
        		 //$categories = mysql_fetch_row($res);
						 //var_dump($categories);
						 for ($c=0; $c<mysql_num_rows($res); $c++) {
								 $val = mysql_result($res,$c);
								 //echo $c.' - '.$val.' - '.$C.' - '.mysql_num_rows($res).'<br>';
  					     if ($C==$val) { $issel = 'selected="selected"'; }
								 else          { $issel = '';                    }
  							 $sel .= '<option '.$issel.' value="'.$val.'">'.$val.'</option>';
  					 }
  				   $sel  .= '</select>';
	           $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">'.$label.'</td><td colspan="5">'.$sel.'</td></tr>';
				     mysql_free_result($res);
				  break;
				 //+++++++++++++++++++++++++++++++++++++++++++//
        	     default:
         //+++++++++++++++++++++++++++++++++++++++++++//
				     $field = '<input type="text" name="'.$fields[$i].'" id="'.$fields[$i].'" value="'.htmlspecialchars($this->$name).'" class="'.$class.'" />';
	           $form .= '<tr><td style="width:10%;padding:0.25em;text-align:right">'.$label.'</td><td colspan="5">'.$field.'</td></tr>';
		     }
		}
		$buttons = '<div id="cancelDialog" class="ITS_button" style="float:right">Cancel</div>'
						  .'<div id="submitDialog" class="ITS_button" style="float:right">Create New Question</div>';		
	  $form .= '</table>'.$buttons.'</fieldset></form>';
    $dialog = '<div id="dialog-form" title="Create new Question" style="display:none">'.$form.'</div>';
	/*
			$dialog = '<div id="dialog-form" title="Create new Question" style="display:none">'
      	  .'<p class="validateTips">To create a templated question just click on the "Create Question" button.</p><br>'
      	  .'<form>'
      	  .'<fieldset>'
      	  .'<table class="ITS_newQuestion"><tr>'
      		.'<td style="width:20%"></td>'.$Qtitle_label
      		.'<td style="width:80%"></td>'
      		.'</tr><tr><td><label for="Qimage">Image</label></td>'
      		.'<td><input type="text" name="Qimage" id="Qimage" value="'.$image.'" class="text ui-widget-content ui-corner-all ITS_Q" /></td>'
      		.'</tr><tr><td><label for="Qquestion">Question</label></td>'
      		.'<td><input type="text" name="Qquestion" id="Qquestion" value="'.$question.'" style="height:150px" class="text ui-widget-content ui-corner-all ITS_Q" /></td>'
          .'</tr><tr><td><label for="Qanswers">No. Answers</label>&nbsp;&nbsp;'
      		.'<select id="Qanswers" style="float:right">'
          .'<option value="1">1</option>'
          .'<option value="2">2</option>'
          .'<option value="3">3</option>'
          .'<option value="4" selected="selected">4</option>'
      		.'<option value="5">5</option>'
          .'</select></td><td></td>'
          .'</tr><tr><td><label for="Qcategory">Category</label></td>'
      		.'<td><input type="text" name="Qcategory" id="Qcategory" value="'.$category.'" class="text ui-widget-content ui-corner-all ITS_Q" /></td>'
      	  .'</tr></table><p><div id="cancelDialog" class="ITS_button" style="float:right">Cancel</div><div id="submitDialog" class="ITS_button" style="float:right">Create New Question</div>'
      	  .'</fieldset></form></div>';
					
				*/	
		
		return $dialog;
	 }	  
	//=====================================================================//
	
} //eo:class

//=====================================================================//
	function createEditTable($TargetName,$Target,$style) {
//=====================================================================//
  	// eg. createEditTable('TITLE','This is my title');
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

