<?php
//=====================================================================//
/*
ITS_question class - render ITS question according to type.

 type: |S|M|P|C|MC|O| - |short answer|matching|paragraph|calculated|multiple choice|other

		Constructor: ITS_question(student_id,file_name,db_name,table_name)
		
								 ex. $ITS_question = new ITS_question(90001,"its","user_cpt");
								
	 Author(s): Greg Krudysz |  Aug-28-2008
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
	
	public $Q_type;					 	// question type can take: |S|M|P|C|MC|O|
	public $Q_num;
	public $Q_title;
	public $Q_question;
	public $Q_image;
	public $Q_answers;
	public $Q_category;
	public $style;
	
	var $Q_answers_fields = array();
	var $Q_answers_values = array();
	var $Q_weights_values = array();
	var $edit_flag=0;
	
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
	  $this->student_id  	= $student_id;
		$this->db_name      = $db_name;
    $this->tb_name      = $table_name;
		$this->cpt_array	  = array();
		$this->cpt_attrib   = array();
		$this->style				= 'ITS';
	}
	//=====================================================================//
  function load_DATA_from_DB($q_num){
	//=====================================================================//
	  // Pull out webct data
		$ans = "select id,qtype,title,question,image,answers,category from " . $this->tb_name . " WHERE id=" . $q_num;
		$res = mysql_query($ans);
		if (!$res) { die('Query execution problem in ITS_question: ' . msql_error()); }
		$data = mysql_fetch_array($res);

	  self::load_DATA($data);	
	}
  //=====================================================================//
  function load_DATA($data){
	//=====================================================================//
		$this->Q_num			= $data[0];				
		$this->Q_type 		= strtolower($data[1]);
		$this->Q_title 		= $data[2];
		$this->Q_question = $data[3];
		$this->Q_image 		= $data[4];
		$this->Q_answers 	= $data[5];
		$this->Q_category = $data[6];
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
		  $query = "SELECT vals from ".$this->tb_name."_".$this->Q_type." WHERE id=".$this->Q_num;
		  $res = mysql_query($query);
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
	 //die('HELLO O');
	 return $question_simple_str;
	}
	//=====================================================================//
	function render_QUESTION(){
	//=====================================================================//
	 $TABLE_QUESTION = createEditTable('QUESTION',$this->Q_question,"ITS_QUESTION");
	 //$question_str = "<p><DIV class=Question>" . $TABLE_QUESTION;
	 $question_str = $TABLE_QUESTION;
	 
	 //$question_str = $this->Q_question;
	 
	 //--- IMAGE ----------------//
	 if (!empty($this->Q_image)){
	 	 $img = '<p><center>'
		 		   .'<img class="ITS_IMAGE" src="'.$this->Q_image.'" alt="'.$this->Q_image.'">'
				   .'</center>';
	 	 //$tb = new ITS_table('question_image',1,3,array('',$img,''),array(49,2,49),'ITS_QUESTION');
		 //$question_str = $question_str . $tb->str;
		 
		 $question_str = $question_str . $img;
	 }
	 //--------------------------//
	 
	 //$question_str = $question_str . "</DIV>";
	 $question_str = $question_str;
	 
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
	function render_ANSWERS($name){
	//=====================================================================//
	 $answer_str = '';
	 switch ($this->Q_type){
		//-------------------------------------------//
	  case 's':
	  //-------------------------------------------//
		 $answer_str = $answer_str 
		      ."<form action=score.php method=post name=form1>"
		 			."<textarea id=TXA_ANSWER name=TXA_ANSWER width=100% cols=80% rows=3></textarea>"		
		 			."<p></form>";
		 break;
	  //-------------------------------------------//
	  case 'mc':
	  //-------------------------------------------//
		 $rows = $this->Q_answers;
		 $width = array(2,2,96);

		 $answer_str = $answer_str . '<table class="ITS_Answer1"><tr><td>';
		 for ($i=1;$i<=$rows;$i++){
		 	 if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			 else 			       { $style = "ITS_ANSWER"; }
				
			//$style = $this->style;
			//echo $this->Q_answers_values["answer".$i];
			//die('IN ITS_Q');
		   $edit_tb = createEditTable('ANSWER'.$i,trim($this->Q_answers_values["answer".$i]),$style);		 
		 	 
			 // solution check and selection
			 $checked = 'false';
			 
			 //if (!empty($this->Q_weights_values)){
			 //   if ($this->Q_weights_values[$i-1]==100){ $checked = 'checked = true'; }
			 //}	  
			 $chk  = "<input type=radio name=".$name." value=".chr($i+64)." ".$checked.">";
			 //$chk  = "<input type=checkbox name=".$name."[".($i)."] value=".chr($i+64)." ".$checked.">";

			 $data = array("<span class=TextAlphabet>".chr($i+64).".</span>",$chk,$edit_tb); 
		 	 $tb   = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$style);
			 $answer_str = $answer_str.$tb->str;
		 }
		 $answer_str = $answer_str."</td></tr></table>";
		 $answer_str = '<DIV class="ITS_ANSWER">'.$answer_str.'</div>';
    break;	
		//-------------------------------------------//
	  case 'p':
	  //-------------------------------------------//
    	// TEMPLATE
		 $template = $this->Q_answers_values;
		 if (!empty($template)){
		 		$TABLE_TEMPLATE = createEditTable('TEMPLATE',$template[0],"ITS_TEMPLATE");
	   		$answer_str = $answer_str . "<p>".$TABLE_TEMPLATE;
		 }
		 
		 // ANSWERS
		 for ($n=1;$n<=$this->Q_answers;$n++){  
		    $answer_str = $answer_str."<textarea id=TXA_ANSWER name=".$name." cols=90% rows=3></textarea>";
				/*
				    ."<form action=score.php method=post onsubmit=return whatFile() name=form1>"
		        ."<textarea id=TXA_ANSWER name=TXA_ANSWER width=100% cols=90% rows=3></textarea>"			
		 		    ."</form>";
						*/
		 }
     break;
		//-------------------------------------------//
	  case 'm':
	  //-------------------------------------------//	 
		 $n = $this->Q_answers;
		 $answers_values = $this->Q_answers_values;
		 $L_answers = $answers_values[0];
		 $R_answers = $answers_values[1];
		 
		 // construct ANSWERS table
		 $rows = $this->Q_answers;
		 $width = array(1,99);
		 
		 // Left table
		 $tb_L_str = '';
		 for ($i=1;$i<=$n;$i++){
		 	  if (!($L_answers["L".$i]=='NULL')){	 
		   		$edit_tb = createEditTable('L'.$i,$L_answers["L".$i],"ITS_ANSWER");
			    $data    = array("<span class=TextAlphabet>".chr($i+64).".</span>",$edit_tb); 
		 	    $tb_L    = new ITS_table('ANSWER_'.$this->Q_type.'_'.$i,1,count($data),$data,$width,$this->style);
			    $tb_L_str = $tb_L_str.$tb_L->str;
				}
		 }
		 
	 	 // Right table
		 $tb_R_str = '';	
		 for ($i=1;$i<=$n;$i++){
		    if (!($R_answers["R".$i]=='NULL')){
		    	$edit_tb = createEditTable('R'.$i,$R_answers["R".$i],"ITS_ANSWER");		 
		 	 		$data    = array("<input type=text size=1 maxlength=1 name=new_class value=?>",$edit_tb); 
		 	 		$tb_R    = new ITS_table('ANSWER_'.$this->Q_type,1,count($data),$data,$width,$this->style);
			 		$tb_R_str = $tb_R_str.$tb_R->str;
				}
		 }				
							
		$tb = new ITS_table('ANSWER_'.$this->Q_type,1,2,array($tb_L_str,$tb_R_str),array(50,50),'ITS');		
		$answer_str = $answer_str . $tb->str;
		break;
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
		$Table= "<table class=".$style." border=1>"
			."<tr>"
			."<td class=".$style." width=100%>"
			."<span id=ITS_".$TargetName."_TARGET>"
			.$Target
			."</span>"
			."</td>"
			."<td class=".$style.">"
			."<span class=ITS_QCONTROL id=ITS_".$TargetName.">"
			."<b></b>"
			."</span>"
			."</td>"
			."</tr>"
			."</table>";
	 return $Table;
	}
//=====================================================================//
?>

