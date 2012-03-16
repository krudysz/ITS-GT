<?php
//=====================================================================//
/*
ITS_survey class - lab configuration file.

		Constructor: ITS_survey($user_info,$lab_info,$db_info)
		
		where:
		        $user_info = array($db_dsn,$db_name,$tb_name)
						$lab_info  = array($lab_title,$lab_intro,$lab_num,$lab_sec,$lab_sec_headers, ...
						                   $lab_sec_parts_headers,$lab_sec_parts,$_lab_sec_ques, ...
															 $_lab_sec_ans_rows,$lab_coda)
						$db_info   = array($db_question_id)
	
		ex. $ITS_survey = new ITS_survey(90001,"its","user_cpt");
								
	 Author(s): Greg Krudysz |  Sep-3-2009
	 Last Update: 
*/
//=====================================================================//
class ITS_survey
{
  private $db_dsn;
	private $db_name;
	private $tb_name;
	
	private $user_id;
	
	public $_mode;
	public $_lab_title;
	public $_lab_intro;
	public $_lab_coda;
	public $_lab_num; 
	public $_lab_sec;
	public $_lab_sec_headers;
	public $_lab_sec_parts_headers;
	public $_lab_sec_parts;
  public $_lab_sec_ques;
  public $_lab_sec_ans_rows;
	public $_lab_name;
  public $_lab_tag;
	
	private $_lab_question_idx;
	private $_question_type;
	private $_answers_permutation;
	public  $_lab_submitted = false;
	public  $_lab_completed = false;
	
  //=====================================================================//
  function __construct(){
  //=====================================================================//
	 $numargs = func_num_args();
	 
	 $info = func_get_arg(0);
   $this->db_dsn  = $info[0];
	 $this->db_name = $info[1];
	 $this->tb_name = $info[2];
	 $this->user_id = $info[3];
		 
	 if ($numargs == 1) { // DEFAULT configuration:
		 $this->configure_lab();
	 }
	 else {
	   $lab_info = func_get_arg(1);
		 $db_info  = func_get_arg(2);
     $this->configure_lab($lab_info,$db_info); 
	 }
  }
  //=====================================================================//
  function configure_lab() {
  //=====================================================================//
	if (func_num_args() == 0) {
	  $this->_lab_title = "LAB (DEFAULT) TITLE";
	  $this->_lab_intro = "<b>Introduction</b><p>At the beginning ...";
	  $this->_lab_num   = 1; 
	  $this->_lab_sec   = 3;
	
	  for ($n=0; $n<$this->_lab_sec; $n++){
	    $this->_lab_sec_headers[$n] = 'Header '.($n+1);
	  }
	
    for ($n=0; $n<$this->_lab_sec; $n++){
	    $this->_lab_sec_parts_headers[$n] = 'Header part '.($n+1);
	  }
	
	  $this->_lab_sec_parts 	 = array(1,1,3);
    $this->_lab_sec_ques     = array(array(1),array(1),array(2,1,1));
    $this->_lab_sec_ans_rows = array(array(1),array(1),array(2,1,1,2));
	
	  $this->_lab_ques_idx = 1000;
		$this->_mode         = "survey";
  } 
  else {
	  $lab_info = func_get_arg(0);
		$db_info  = func_get_arg(1);

    $this->_lab_title = $lab_info[0];
    $this->_lab_intro = $lab_info[1];															 
    $this->_lab_num   = $lab_info[2];
    $this->_lab_sec   = $lab_info[3]; // Section: Parts (a,b,c): Questions (per parts): Answers (per question)
    $this->_lab_sec_headers       = $lab_info[4]; // Section header ($_lab_sec x 1) array
    $this->_lab_sec_parts_headers = $lab_info[5];
    $this->_lab_sec_parts         = $lab_info[6];
    $this->_lab_sec_ques          = $lab_info[7];
    $this->_lab_sec_ans_rows      = $lab_info[8];
    $this->_lab_coda              = $lab_info[9];
		$this->_mode                  = $lab_info[10];
		
	  $this->_lab_ques_idx = $db_info;
  }
	
  if ($this->_lab_num < 10) { $lab_num = '0'.$this->_lab_num; }
  else                      { $lab_num = $this->_lab_num;     }
  switch ($this->_mode) {
	 //-------------------------------
   case "lab":
   //-------------------------------
     $this->_lab_name = 'Warm-Up'.$lab_num;
     $this->_lab_tag  = 'lab'.$lab_num;
	 break;
	 //-------------------------------
   case "survey";
   //-------------------------------
     $this->_lab_name = 'Exercise'.$lab_num;
     $this->_lab_tag  = 'survey'.$lab_num;
	//-------------------------------
	}
  
  }
  //=====================================================================//
  function lab_check() {
  //=====================================================================//
	 // connect to database
   $mdb2 =& MDB2::connect($this->db_dsn);
   if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	 
	 // lab completed? 
	 //$query = 'SELECT '.$this->_lab_tag.' FROM users WHERE ID='.$this->user_id;
	 $query = 'SELECT survey02 FROM users WHERE ID='.$this->user_id;
	 //echo $query; die();
   $res = & $mdb2->query($query); 
   if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

	 $mdb2->disconnect();
	 
   $completed = $res->fetchRow();
	 
   if (!empty($completed[0])){
     $this->_lab_completed = true;

     echo '<p><center><DIV class="ITS_MESSAGE">'
	       .'<p>You have already completed <b>'. $this->_lab_title .'</b>.<p>'
			   .'<a href="Home.php">back</a>'
			   .'</DIV></center>';
   }
	}
  //=====================================================================//
  function record_lab() {
  //=====================================================================//
	   // connect to database
     $mdb2 =& MDB2::connect($this->db_dsn);
     if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	 
		 $time = date("m-d-y, g:i a");

     $query_str = "UPDATE users SET ".$this->_lab_tag."='".$time."' WHERE id=".$this->user_id;
	   //echo "<p>".$query_str."<p>";die();
	   // CHANGE
		 $res_time =& $mdb2->query($query_str);
	   if (PEAR::isError($res_time)) {throw new Grading_Exception($res->getMessage());}
		 
		 $mdb2->disconnect();
		 $this->_lab_completed = true;
		 
	   echo '<p><center><DIV class="ITS_MESSAGE">'
	       .'<p>Thanks, your <b>'.$this->_lab_title.'</b> answers have been recorded on '.$time.'.<p>'
				 .'<a href="Home.php">back</a><p>'
			   .'Please do not forget to logout.'
			   .'</DIV></center>';		 
  }
  //=====================================================================//
  function record_lab_answers($ans_arr,$db_table_user_state) {
  //=====================================================================//
	//echo 'record_lab_answers:<p>';
	//var_dump($this->_answers_permutation); die();
	
	// connect to database
   $mdb2 =& MDB2::connect($this->db_dsn);
   if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

	//-- Read Warm-Up array, then update user STATS table if answer exists	 
	 if ($this->_lab_completed) {
	   foreach($ans_arr as $qNum=>$qNum_array){
		    $name = $this->_lab_name.'['.$qNum.']';
				
				// CASE: MC: 
				if (is_array($qNum_array)){ $ans = implode(",", $qNum_array); }
				else											{ $ans = $qNum_array;               }
				  
				switch ($this->_question_type[$name]) {
	      //-------------------------------//
          case 'm':
        //-------------------------------//
					  $perm_str = implode(',',$this->_answers_permutation[$name]);
					  $query_str = 'INSERT IGNORE INTO '.$db_table_user_state.$this->user_id.' (question_id,answered,comment) VALUES('.$qNum.',"'.mysql_real_escape_string($ans).'","'.$perm_str.'")';		      
	        break;
	      //-------------------------------//
          default;
			  //-------------------------------//
					  $query_str = 'INSERT IGNORE INTO '.$db_table_user_state.$this->user_id.' (question_id,answered) VALUES('.$qNum.',"'.mysql_real_escape_string($ans).'")';		             
				//-------------------------------//
				}			
				  //echo 'ANSWERS: '.$ans." | ".is_string($ans)."<p> ";
					//die();

		      if (!empty($ans)) {                    
				    //echo $query_str."<p>"; //die();
				    $res = & $mdb2->query($query_str);
	          if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
	        }			
	    }//foreach
		}
		$mdb2->disconnect();
  }
  //=====================================================================//
  function render_lab() {
  //=====================================================================//
	$sec_num = '';
	
	// connect to database
   $mdb2 =& MDB2::connect($this->db_dsn);
   if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	 
	 if ($this->_lab_num < 10) { $lab_num = '0'.$this->_lab_num; }
   else                      { $lab_num = $this->_lab_num;     }
	 
	  switch ($this->_mode) {
	 //-------------------------------
   case "lab":
   //-------------------------------
     echo '<H3><div class="center">Lab #'.$this->_lab_num.': '.$this->_lab_title.'</div></H3>'
         .'<b>'.$sec_num.'  Warm-Up</b></p>'
         .'<form action="ITS_lab'.$lab_num.'.php" name="Warm-Up'.$this->_lab_num.'" method="post"><p>';
	 break;
	 //-------------------------------
   case "survey";
   //-------------------------------
     echo '<H3><div class="center">'.$this->_lab_title.'</div></H3>'
      .'<form action="ITS_pre_lab.php?activity='.$this->_lab_num.'" name="survey" method="POST"><p>';
	//-------------------------------
	}
	 
	echo $this->_lab_intro."<p>";
	//-----------//-------------//
	// Draw viewer - make into class
	//-----------//-------------//
	// OPTION 0: 1. //style="background-image: url(images/box.png)
	      $k = 0; // 
	 			for ($n = 0; $n <= $this->_lab_sec_parts[$k]-1; $n++){
					echo '<table class="ITS_QUESTION_PART">';
					$part_header = '<h3>'.($n+1).'.</h3>';
					echo '<tr><td class="ITS_QUESTION_PART_NUM">'.$part_header.'</td><td class="ITS_QUESTION_PART">';
		 			$Q = new ITS_question(1,$this->db_name,$this->tb_name);
	        $Q->load_DATA_from_DB($this->_lab_ques_idx[$n]);
					$CONTENT = $Q->render_QUESTION_check();
	        $Q->get_ANSWERS_data_from_DB();
					//$Q->get_ANSWERS_solution_from_DB();
					//echo $Q->Q_answers_permutation;
					// ANSWERS		
					$CONTENT = $CONTENT."<p>";
					switch ($this->_mode) {
	        //-------------------------------
          case "lab":
          //-------------------------------
            for ($an=1;$an<=$Q->Q_answers;$an++){
					    //echo "<p>[".($_lab_ques_idx)."][".($an)."]<p>";
					    $CONTENT = $CONTENT.'<center><textarea class="ITS_TA name"='.$this->_lab_name.'['.($this->_lab_ques_idx).']['.($an).'] width="100%" cols="100%" rows='.$this->_lab_sec_ans_rows[$k][$n].'></textarea></center>';	    
		        }
					  // Update question index
				    $this->_lab_ques_idx ++;
	        break;
	        //-------------------------------
          case "survey";
          //-------------------------------
					  //echo '<b>'.$n.'</b><p>';
			      $name = $this->_lab_name."[".($this->_lab_ques_idx[$n])."]"; //[".($an)."];			
			      //echo "<center>".$Q->render_ANSWERS($name)."</center>";
						//echo '<b>'.$name.'</b><p>'; 
						$CONTENT = $CONTENT.$Q->render_ANSWERS($name,0);
					}
					echo $CONTENT.'</td></tr></table>';
					//echo ITS_question_table($CONTENT).'</td></tr></table>';
					if ($n != $this->_lab_sec_parts[$k]-1){
						echo '<p>';//'<div class="ITS_QUESTION_BREAK"><b><font color="royalblue">&diams;</font></b></div>';
					}		
				//$this->_answers_permutation[$this->_lab_ques_idx[$n]] = $Q->Q_answers_permutation;
				//echo $Q->Q_answers_permutation;
				//die('what is perm?');
				//-----$this->_answers_permutation = $Q->Q_answers_permutation;
				//var_dump($Q->Q_answers_permutation);
		    //_answers_permutation	
					//--var_dump($Q->Q_answers_permutation);
					//--echo '<p>';
					$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
					$this->_question_type[$name] = $Q->Q_type;
	      }
				//var_dump($Q->Q_answers_permutation);
	//-----------//-------------//
	// OPTION 1: I.1.a.
	/*
	// Section ordered list
	echo '<table class="ITS_QUESTION_SECTION">';
	for ($k = 0; $k <= $this->_lab_sec-1; $k++){
	   $sec_header = ''; //$this->_lab_sec_headers[$k]
	   echo  '<tr><td class="ITS_QUESTION_SECTION">'.$sec_header.'</td><td class="ITS_QUESTION_SECTION">';
		 echo '<table class="ITS_QUESTION_PART">';
		 for ($n = 0; $n <= $this->_lab_sec_parts[$k]-1; $n++){
		    $part_header = '<b>'.($n+1).'.</b>';
				echo '<tr><td class="ITS_QUESTION_PART">'.$part_header.'</td><td class="ITS_QUESTION_PART">';
				echo '<table class="ITS_QUESTION_QUESTION">';		
	 			for ($q = 0; $q <= $this->_lab_sec_ques[$k][$n]-1; $q++){
	   			//-$q--
					$question_header = ''; //chr($q+65);
					echo '<tr><td class="ITS_QUESTION_QUESTION">'.$question_header.'</td><td class="ITS_QUESTION_QUESTION">';
		 			$Q = new ITS_question(1,$this->db_name,$this->tb_name);
	        $Q->load_DATA_from_DB($this->_lab_ques_idx[$n]);
					echo $Q->render_QUESTION_check();
	        $Q->get_ANSWERS_data_from_DB();
					//$Q->get_ANSWERS_solution_from_DB();
					
					// ANSWERS
					echo "<p>";
					switch ($this->_mode) {
	        //-------------------------------
          case "lab":
          //-------------------------------
            for ($an=1;$an<=$Q->Q_answers;$an++){
					    //echo "<p>[".($_lab_ques_idx)."][".($an)."]<p>";
					    echo "<center><textarea class=ITS_TA name=".$this->_lab_name."[".($this->_lab_ques_idx)."][".($an)."] width=100% cols=100% rows=".$this->_lab_sec_ans_rows[$k][$n]."></textarea></center>";	    
		        }
					  echo "<p>";		
				
					  // Update question index
				    $this->_lab_ques_idx ++;
	        break;
	        //-------------------------------
          case "survey";
          //-------------------------------
			      $name = $this->_lab_name."[".($this->_lab_ques_idx[$n])."]"; //[".($an)."];			
			      //echo "<center>".$Q->render_ANSWERS($name)."</center>";
						echo $Q->render_ANSWERS($name);
		      }
			    //-$q--
					echo '</td></tr>';			
	      }
				echo '</table>';
	      echo '</td></tr>';
	    }
	   echo '</table>';
	   echo '</td></tr>';
	}
	echo '</table>';
	*/
	//-----------//-------------//	
	// OPTION 3 - old
		/*
	// Section ordered list
	for ($k = 0; $k <= $this->_lab_sec-1; $k++){	
     echo $this->_lab_sec_headers[$k].'<OL type="1">';
		 echo '<DIV class="ITS_QUESTIONS_INNER">';
	   // Section parts list
	   for ($n = 0; $n <= $this->_lab_sec_parts[$k]-1; $n++){
		     //echo '<b>'.($n+1).'</b>';
				 echo '<p><b><LI>&nbsp;#</b>'; //&nbsp;
		   // Section parts question list
		   for ($q = 0; $q <= $this->_lab_sec_ques[$k][$n]-1; $q++){
          $Q = new ITS_question(1,$this->db_name,$this->tb_name);
	        $Q->load_DATA_from_DB($this->_lab_ques_idx[$n]);
	        // $Q->render_TITLE();
	        // echo $Q->render_QUESTION_simple();
					echo $Q->render_QUESTION_check();
	        $Q->get_ANSWERS_data_from_DB();
					// $Q->get_ANSWERS_solution_from_DB();
					
					// ANSWERS
					echo "<p>";
					  switch ($this->_mode) {
	        //-------------------------------
          case "lab":
          //-------------------------------
            for ($an=1;$an<=$Q->Q_answers;$an++){
					    //echo "<p>[".($_lab_ques_idx)."][".($an)."]<p>";
					    echo "<center><textarea class=ITS_TA name=".$this->_lab_name."[".($this->_lab_ques_idx)."][".($an)."] width=100% cols=100% rows=".$this->_lab_sec_ans_rows[$k][$n]."></textarea></center>";	    
		        }
					  echo "<p>";		
				
					  // Update question index
				    $this->_lab_ques_idx ++;
	        break;
	        //-------------------------------
          case "survey";
          //-------------------------------
			      $name = $this->_lab_name."[".($this->_lab_ques_idx[$n])."]"; //[".($an)."];			
			      //echo "<center>".$Q->render_ANSWERS($name)."</center>";
						echo $Q->render_ANSWERS($name);

	      //-------------------------------
	        } 			
			  }// e.of $q 		
			// echo '</DIV>';
	   }// e.of $n
	   echo "</DIV></OL>";

	}
	*/
	echo $this->_lab_coda."<p>";
	
	$mdb2->disconnect();
	
	//echo $this->_lab_tag . " | ". $this->_lab_title . "<p>";
	//die('END HERE');
	
	// SUBMIT BUTTON
	echo '<div class="center">'
      .'<input type="submit" name="submit" value="Submit '.$this->_lab_title.'">'
      .'</div>'
      .'</form>';
	}
//=====================================================================//
  function render_lab_results($hist) {
  //=====================================================================//
	$sec_num = '';
	
	 // connect to database
   $mdb2 =& MDB2::connect($this->db_dsn);
   if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	 
	 if ($this->_lab_num < 10) { $lab_num = '0'.$this->_lab_num; }
   else                      { $lab_num = $this->_lab_num;     }
	 
	  switch ($this->_mode) {
	 //-------------------------------
   case "lab":
   //-------------------------------
     echo '<H3><div class="center">Lab #'.$this->_lab_num.': '.$this->_lab_title.'</div></H3>';
	 break;
	 //-------------------------------
   case "survey";
   //-------------------------------
     echo '<H3><div class="center">'.$this->_lab_title.'</div></H3>';
	//-------------------------------
	}
	 
	echo $this->_lab_intro."<p>";
			
	// Section ordered list
	for ($k = 0; $k <= $this->_lab_sec-1; $k++){	
     echo $this->_lab_sec_headers[$k].'<UL type="A">';
 
	   // Section parts list
	   for ($n = 0; $n <= $this->_lab_sec_parts[$k]-1; $n++){
				 echo "<p><LI>";
			 
		   // Section parts question list
		   for ($q = 0; $q <= $this->_lab_sec_ques[$k][$n]-1; $q++){
          $Q = new ITS_question(1,$this->db_name,$this->tb_name);
	        $Q->load_DATA_from_DB($this->_lab_ques_idx[$n]);
	        //$Q->render_TITLE();
	        echo '<H3 style="font-color:red">'.$Q->render_QUESTION ().'</H3>';
					//echo '<H3 style="font-color:red">'.$Q->render_QUESTION_simple().'</H3>';
	        $Q->get_ANSWERS_data_from_DB();
					//$Q->get_ANSWERS_solution_from_DB();
					//echo '000000000000000000';
					// ANSWERS
					echo "<p>";
					  switch ($this->_mode) {
	        //-------------------------------
          case "lab":
          //-------------------------------
            for ($an=1;$an<=$Q->Q_answers;$an++){
					    //echo "<p>[".($_lab_ques_idx)."][".($an)."]<p>";
					    echo "<center><textarea class=ITS_TA name=".$this->_lab_name."[".($this->_lab_ques_idx)."][".($an)."] width=100% cols=100% rows=".$this->_lab_sec_ans_rows[$k][$n]."></textarea></center>";	    
		        }
					  echo "<p>";		
				
					  // Update question index
				    $this->_lab_ques_idx ++;
	        break;
	        //-------------------------------
          case "survey";
          //-------------------------------
			      $name = $this->_lab_name."[".($this->_lab_ques_idx[$n])."]"; //[".($an)."];	
						
            // DISPLAY ANSWERS
            //echo $this->_lab_ques_idx[$n];
            //die();
            if (($this->_lab_ques_idx[$n] == 399) || ($this->_lab_ques_idx[$n] == 422)){
             
              foreach ($hist[$this->_lab_ques_idx[$n]] as $resp){
                echo $resp."<hr>";
              }
            } 
						else {
							$answer_str = '';
							$width = array(5,75,20);
							$style = 'ITS';

							$answer_str = $answer_str . "<center><table class=ITS_Answer1><tr><td>";
		          for ($i=1;$i<=$Q->Q_answers;$i++){
		 	          if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			          else 			        { $style = "ITS_ANSWER";        }
				        
								//$tb_score = new ITS_table('score',1,1,array(''),array(100),'ITS_GRAPH');
							  //$tb_box   = new ITS_table('score',1,2,array($tb_score->str,''),array(90,10),'ITS');
							  //$tbs      = new ITS_table('user_score',1,2,array($tb_box->str,'80'),array(90,10),'ITS_DATA');
			          $data = array("<span class=TextAlphabet>".chr($i+64).".</span>",$Q->Q_answers_values["answer".$i],$hist[$this->_lab_ques_idx[$n]][$i-1]); 
		 	          $tb   = new ITS_table('ANSWER_'.$Q->Q_type,1,count($data),$data,$width,$style);
			          $answer_str = $answer_str . $tb->str;
		          }
							$answer_str = $answer_str . "</td><td>??</td></tr></table></center>";
							echo $answer_str;
							}
							
							//----\	
			      //echo "<center>".$Q->render_ANSWERS($name)."</center>";
	      //-------------------------------
	      } 			
	
			 }
	   }
	   echo "</UL>"; //"</OL>;
	}
	echo $this->_lab_coda."<p>";
	
	$mdb2->disconnect();
	}	
	//=====================================================================//
  function pdf_lab_results($hist) {
  //=====================================================================//
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

	$sec_num = '';
	
	// connect to database
   $mdb2 =& MDB2::connect($this->db_dsn);
   if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	 
	 if ($this->_lab_num < 10) { $lab_num = '0'.$this->_lab_num; }
   else                      { $lab_num = $this->_lab_num;     }
	 
	  switch ($this->_mode) {
	 //-------------------------------
   case "lab":
   //-------------------------------
     echo '<H3><div class="center">Lab #'.$this->_lab_num.': '.$this->_lab_title.'</div></H3>';
	 break;
	 //-------------------------------
   case "survey";
   //-------------------------------
     //=echo '<H3><div class="center">'.$this->_lab_title.'</div></H3>';
		 $pdf->Cell(40,10,$this->_lab_title);
	//-------------------------------
	}
	 
	//=echo $this->_lab_intro."<p>";
	$pdf->Cell(40,10,$this->_lab_intro);
			
	// Section ordered list
	for ($k = 0; $k <= $this->_lab_sec-1; $k++){	
     //echo $this->_lab_sec_headers[$k].'<UL type="1">';
		 $pdf->Cell(40,10,$this->_lab_sec_headers[$k]);
 
	   // Section parts list
	   for ($n = 0; $n <= $this->_lab_sec_parts[$k]-1; $n++){
				 //=echo "<p><LI>";
			 
		   // Section parts question list
		   for ($q = 0; $q <= $this->_lab_sec_ques[$k][$n]-1; $q++){
          $Q = new ITS_question(1,$this->db_name,$this->tb_name);
	        $Q->load_DATA_from_DB($this->_lab_ques_idx[$n]);
	        //$Q->render_TITLE();
	        //=echo $Q->render_QUESTION_simple();
          $pdf->Cell(40,10,$Q->render_QUESTION_simple());
	        $Q->get_ANSWERS_data_from_DB();
					//$Q->get_ANSWERS_solution_from_DB();
					
					// ANSWERS
					//=echo "<p>";
					  switch ($this->_mode) {
	        //-------------------------------
          case "lab":
          //-------------------------------
            for ($an=1;$an<=$Q->Q_answers;$an++){
					    //echo "<p>[".($_lab_ques_idx)."][".($an)."]<p>";
					    echo "<center><textarea class=ITS_TA name=".$this->_lab_name."[".($this->_lab_ques_idx)."][".($an)."] width=100% cols=100% rows=".$this->_lab_sec_ans_rows[$k][$n]."></textarea></center>";	    
		        }
					  echo "<p>";		
				
					  // Update question index
				    $this->_lab_ques_idx ++;
	        break;
	        //-------------------------------
          case "survey";
          //-------------------------------
			      $name = $this->_lab_name."[".($this->_lab_ques_idx[$n])."]"; //[".($an)."];	
						
							// DISPLAY ANSWERS
							//=echo $this->_lab_ques_idx[$n];
              $pdf->Cell(40,10,$this->_lab_ques_idx[$n]);
							//die();
							if (($this->_lab_ques_idx[$n] == 399) || ($this->_lab_ques_idx[$n] == 422)){
							  
								foreach ($hist[$this->_lab_ques_idx[$n]] as $resp){
								  //=echo "<p>".$resp."<p>";
									$pdf->Cell(40,10,$resp);
								}
							} 
							else {
							$answer_str = '';
							$width = array(5,75,20);
							$style = 'ITS';

							$answer_str = $answer_str . "<center><table class=ITS_Answer1><tr><td>";
		          for ($i=1;$i<=$Q->Q_answers;$i++){
		 	          if (($i % 2) == 0){ $style = "ITS_ANSWER_STRIPE"; }
			          else 			        { $style = "ITS_ANSWER"; }
				        
								//$tb_score = new ITS_table('score',1,1,array(''),array(100),'ITS_GRAPH');
							  //$tb_box   = new ITS_table('score',1,2,array($tb_score->str,''),array(90,10),'ITS');
							  //$tbs      = new ITS_table('user_score',1,2,array($tb_box->str,'80'),array(90,10),'ITS_DATA');
			          $data = array("<span class=TextAlphabet>".chr($i+64).".</span>",$Q->Q_answers_values["answer".$i],$hist[$this->_lab_ques_idx[$n]][$i-1]); 
		 	          $tb   = new ITS_table('ANSWER_'.$Q->Q_type,1,count($data),$data,$width,$style);
			          $answer_str = $answer_str . $tb->str;
		          }
							$answer_str = $answer_str . "</td></tr></table></center>";
							//==echo $answer_str;
							}
							
							//----\	
			      //echo "<center>".$Q->render_ANSWERS($name)."</center>";
	      //-------------------------------
	      } 			
	
			 }
	   }
	   //==echo "</UL>"; //"</OL>;
	}
	//==echo $this->_lab_coda."<p>";
	
	$mdb2->disconnect();
	$pdf->Output();

	}	
//=====================================================================//
} //eo:class
//=====================================================================//

