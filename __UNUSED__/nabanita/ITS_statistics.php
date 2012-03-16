<?php
//=====================================================================//
//=====Nabanita Editing=====//
/*
ITS_statistics class - generate and rander statistical displays.

 user_role: 'admin'|'instr'|'student'

		Constructor: ITS_statistics(student_id,class_term,user_role)
		
								 ex. $ITS_stats = new ITS_statistics(45,'Fall_2009','student');
								
	 Author(s): Greg Krudysz |  Aug-28-2008
	 Last Revision: Nov-30-2009
*/
//=====================================================================//

//==============================================================================
class ITS_statistics {
//==============================================================================

	private $id;    		// Stores user id
	private $user_name; // Stores username
	private $role;			// user role: admin | inst | student
	private $term;      // 
	private $stats;     // Stores statistical data to be output
	public  $data;      // Stores all raw data from database
	public  $hist;
	private $mdb2;
			
  function __construct($id,$term,$role) {
	  global $db_dsn,$tb_name;
		
		$this->id   = $id;
		$this->term = $term;
		$this->role = $role;
	  //$this->db_name      = $db_name;
    $this->tb_name = $tb_name;
		$this->record = array();
	  
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
			
	  $this->mdb2 = $mdb2;				 
	  //$query = "SELECT name,question_id,answered,score FROM stats_$user_id LEFT JOIN question ON stats_$user_id.question_id=question.id LEFT JOIN concept ON question.concept_id=concept.id";
		
		//self::main();
  }
	//----------------------------------------------------------------------------
  	function main() {
	//----------------------------------------------------------------------------
	
			/**NAB EDIT START**/
			if(!isset($_GET['title']))
				$titleVar = "ITS";
			else
				$titleVar = $_GET['title'];
			//echo '<div class="top_header">' . $titleVar .'</div>';
			
			$lablinks = array();
			$lablinks[] = 'LAB EXERCISES';
			$labs = array();
			$chapterlinks = array();
			$chapterlinks[] = 'CHAPTERS FROM TEXTBOOK';
			$chapters = array();
			$questions = array();
			
			$getActiveLabquery = 'SELECT DISTINCT name,active FROM activity WHERE term="Fall_2009"';
 			$getActiveLabres = $this->mdb2->query($getActiveLabquery);
			if (PEAR::isError($getActiveLabres)) {throw new Question_Control_Exception($res->getMessage());}
				$activeLabs_info = $getActiveLabres->fetchAll();
			for ($i = 0; $i <= count($activeLabs_info)-1; $i++) { //count($activiy_info)-1
			  $activeLabs_name = $activeLabs_info[$i][0];
			  //$labs = $activeLabs_name;
			  $labs = $i+1;
			  $lablinks[] = '<a href="http://localhost/ITS/Home.php?title='.$labs.'">'.$activeLabs_name.'</a>';
			}
			$LabListTab = new ITS_table('LabList',1,14,$lablinks,array(15,15,15,15,15,15,15,15,15,15,15,15,15,15),'InnerTable');
			echo '<div class="next_header">'.$LabListTab->str.'</div>';
			
			//to display list of appendix
			for($i=65;$i<=67;$i++) {
				$chapters[] = 'Appendix '.chr($i);
				$chapterlinks[] = '<a href="http://localhost/ITS/Home.php?title=Appendix '.chr($i).'">Appendix '.chr($i).'</a>';
			}
			$ChapterListTab = new ITS_table('ChapterList',1,4,$chapterlinks,array(20,20,20,20),'InnerTable');
			echo '<div class="next_header">'.$ChapterListTab->str.'</div><br>';
			
			/**NAB EDIT END**/
					
			//-- obtain activity: name
 			$query = 'SELECT DISTINCT name,active FROM activity WHERE term="'.$this->term.'"';
 			$res = $this->mdb2->query($query);
			if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
			$activiy_info = $res->fetchAll();

			// activity header
			//$title = 'Activity';
			//$tb    = new ITS_table('ITS_table_stats',1,4,array('&nbsp;','Answer','Score',preg_replace('[_]',' ',$this->term.' class')),array(10,30,30,30),'ITS_feedback_header'); 
			//$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$tb->str),array(15,85),'ITS_feedback_header');
		  //echo $tb_activity->str;
				
			//-- For each activity:
			$SUM = 0;
			for ($i = 0; $i <= count($activiy_info)-1; $i++) { //count($activiy_info)-1
			  $activiy_name = $activiy_info[$i][0];
				$activiy_ACTIVE = $activiy_info[$i][1];
			
			  if (($i+1) < 10) { $num = '0'.($i+1); }
				else             { $num = $i+1;       } 

				//----***--------//
				$query = 'SELECT id FROM users WHERE status="'.$this->term.'" AND '.$activiy_name.' IS NOT NULL';  
				$res =& $this->mdb2->query($query);
    		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
				$activity_users = $res->fetchCol();
				//----***--------//
				
				// activity completed?
				$query = 'SELECT '.$activiy_name.' FROM users WHERE id='.$this->id;
    		$res =& $this->mdb2->query($query);
    		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
				$activity_completed = $res->fetchRow();
				$activity_COMPLETE = $activity_completed[0];
				
				$stats_SHOW = FALSE;
				
				if (strcmp($activiy_name,'survey01')==0){$title_name = 'Survey';           }
				else                                    {$title_name = 'Exercise #'.($i+1);}					
				$title = '<font color="royalblue">'.$title_name.'</font>';
				
				switch ($this->role) {
				  //----------------------//
    			case 'admin':
					//----------------------//
					  // TITLE  
            $title = '<a href="ITS_pre_lab.php?activity='.($i+1).'">'.$title.'</a>';
					/**NAB EDIT START**/	
						//$stats_SHOW = TRUE;
						$stats_SHOW = FALSE;
					/**NAB EDIT END**/
          break;
					//----------------------//
    		  default:
					//----------------------//
					// TITLE: IF active & !complete => link, ELSE name
					// OTHER: IF !active & complete => show, ELSE nothing
					  if ($activiy_ACTIVE) {
						  if (!($activity_COMPLETE)){
					      // TITLE  
                $title = '<a href="ITS_pre_lab.php?activity='.($i+1).'">'.$title.'</a>';
					    }
						} 
						else {
						  if ($activity_COMPLETE) { $stats_SHOW = TRUE; }
						}
						//----------------------//
					} //eof.switch

				// ACTIVITY TITLE	
				/**NAB EDIT START**/
				//$tb_title = new ITS_table('ITS_activity',1,1,array($title),array(100),'ITS_feedback_list');
				//echo $tb_title->str;
				/**NAB EDIT END**/
									
				//echo '<p>'.$activiy_ACTIVE.' | '.$activity_COMPLETE.' | '.$stats_SHOW.'<p>';
        if ($stats_SHOW) {
				//-- Result: question_id | answered => for user's activity and term 
				if ($activity_COMPLETE) {
			 		 $query = "SELECT activity.question_id,answered,qtype,answers FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) LEFT JOIN stats_$this->id ON (activity.question_id=stats_$this->id.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder"; 
				} else { 
			 		 $query = "SELECT question_id,NULL,qtype,answers FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder"; 
				}
				//echo $query;die();
				$res =&$this->mdb2->query($query);
       	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
		   	$answers = $res->fetchAll();

			 	$list = '';
			 	//-- LIST of questions
			 	for ($qn=0; $qn <= count($answers)-1; $qn++){
					 $qtype    = strtolower($answers[$qn][2]);			
					 $Nanswers = $answers[$qn][3];  

					 // if NO activity users => NO distribution
					 if (count($activity_users)) {
			 			   $DATA  = $this->get_question_data($answers[$qn][0],$activity_users);
						   $stats = $this->get_question_stats($DATA,$qtype,$Nanswers);
						 	 $dist  = $this->get_question_dist($stats,$answers[$qn][0],$qtype);
					 } else {
						   $dist  = '';
					 }
					 // if NO user answer => NO answer/score
					 //$score = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
					 if (is_null($answers[$qn][1])) {
						  	$ans   = '&nbsp;';
						    $score = '&nbsp;';	
								$tscore = NULL;					
					 } else {
					      //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
						 		$score  = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
						    $ans    = $this->render_question_answer($score,$answers[$qn][1],$qtype);
								$tscore = $this->get_total_score($score,$answers[$qn][1],$qtype);
					 } 
					 
					 // add horiz rule
					 if   ( $qn == count($answers)-1) { $hr = ''; 												 }
					 else 	 		 											{ $hr = '<hr class="ITS_feedback">'; }

					 // RENDER QUESTION PREVIEW
					$Pstr = '<table class="ITS_QUESTION_PART">';
					$part_header = '<h3>'.($qn+1).'.</h3>';
					$Pstr = $Pstr.'<tr><td class="ITS_QUESTION_PART_NUM">'.$part_header.'</td><td class="ITS_QUESTION_PART">';
	 				$Q = new ITS_question(1,'its',$this->tb_name);
					$Q->load_DATA_from_DB($answers[$qn][0]);
					$Pstr = $Pstr.$Q->render_QUESTION_check();
				  $Pstr = '<b>'.$Pstr.'</b>';
					
					 // RECORD: Question Number | Answer | Total Score | 
					 //$record = array($answers[$qn][0],$answers[$qn][1],$tscore);
					 $this->record[$i][$qn] = $tscore;
					 $SUM = $SUM + $tscore;
					 
					 if ($i == 12) { //survey							
  						if (($qn==count($answers)-1) OR ($qn==count($answers)-2)){
  						  $ua_str = '';
  						  for ($ua=0; $ua <= count($DATA)-1; $ua++){
  								if (($ua % 2) == 0){ $style = "ITS_SURVEY_STRIPE"; }
  	 		          else 			         { $style = "ITS_SURVEY";        }
  								
  							  $ua_str = $ua_str.'<DIV class='.$style.'>'.$DATA[$ua].'</DIV>';
  							}
      				 $ua_str = $ua_str;
      			   $preview = $Pstr.'<p>'.$ua_str;
      	       $preview = '<DIV class="ITS_PREVIEW">'.$preview.'</DIV>';
							 $preview =	$preview.'</td></tr></table>';
      		 	   $tb = new ITS_table('ITS_survey',1,1,array($preview),array(100),'ITS_feedback'); 
       	       $list = $list.$tb->str.$hr;
						 }else{
						 $Q->get_ANSWERS_data_from_DB();
      	  	 $preview = $Pstr.$Q->render_ANSWERS('a');
						 $preview = '<DIV class="ITS_PREVIEW">'.$preview.'</DIV>';
						 $preview =	$preview.'</td></tr></table>';
						 $tb = new ITS_table('ITS_survey',1,2,array($preview,$dist),array(70,30),'ITS_feedback'); 
	  	       $list = $list.$tb->str.$hr;
						 }
					 } else {	 // not survey
					   $Q->get_ANSWERS_data_from_DB();
      	     $preview = $Pstr.$Q->render_ANSWERS('a');
					   $preview = '<DIV class="ITS_PREVIEW">'.$preview.'</DIV>';
					 	 $preview =	$preview.'</td></tr></table>';
						 //$preview = '';
					   //'<b><a href="Question1.php?qNum='.$answers[$qn][0].'">'.($qn+1).'</a>&nbsp;</b>'
						 $user = $this->render_user_answer($ans,$tscore,$dist);
			  	   $tb = new ITS_table('ITS_table_stats',1,2,array($preview,$user),array(70,30),'ITS_feedback'); 
			  	   $list = $list.$tb->str.$hr;
					 }
			 	} // eof $qn
				}
				else {
				   $list = '';
				}
			echo $list;
			//$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$list),array(15,85),'ITS_feedback_list');
      //echo $tb_activity->str;
			} // eof $i
			
			//var_dump(array_sum($this->record));
			echo $SUM;
	 } 
	//----------------------------------------------------------------------------
  	function render_user_answer($answer,$score,$dist) {
	//---------------------------------------------------------------------------- 
	 		//$user = new ITS_table('ITS_table_stats',2,2,array('Answer',$answer,'Score',$score),array(20,80),'ITS_SCORE');
			
			$user = '<table class="ITS_SCORE">'
						 .'<tr><td class="ITS_SCORE" style="text-align:right">Answer</td><td class="ITS_SCORE">'.$answer.'</td></tr>'
			       .'<tr><td class="ITS_SCORE" style="width:20%;text-align:right">Score</td><td class="ITS_SCORE">'.$score.'</td></tr>'
						 .'</table>';
			$user_ans = $user.'<p>'.$dist;
			return $user_ans;
		}
	//----------------------------------------------------------------------------
  	function get_record() {
	//----------------------------------------------------------------------------
				//-- obtain activity: name
 			$query = 'SELECT DISTINCT name,active FROM activity WHERE term="'.$this->term.'"';
 			$res = $this->mdb2->query($query);
			if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
			$activiy_info = $res->fetchAll();
				
			//-- For each activity:
			$r = 0;
			for ($i = 0; $i <= count($activiy_info)-1; $i++) {
			  $activiy_name = $activiy_info[$i][0];
				$activiy_ACTIVE = $activiy_info[$i][1];
			
			  if (($i+1) < 10) { $num = '0'.($i+1); }
				else             { $num = $i+1;       } 
				
				// activity completed?
				$query = 'SELECT '.$activiy_name.' FROM users WHERE id='.$this->id;
    		$res =& $this->mdb2->query($query);
    		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
				$activity_completed = $res->fetchRow();
				$activity_COMPLETE = $activity_completed[0];
				
				//-- Result: question_id | answered => for user's activity and term 
				if ($activity_COMPLETE) {
			 		 $query = "SELECT activity.question_id,answered,qtype,answers FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) LEFT JOIN stats_$this->id ON (activity.question_id=stats_$this->id.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder"; 
				} else { 
			 		 $query = "SELECT question_id,NULL,qtype,answers FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder"; 
				}
				//echo $query;die();
				$res =&$this->mdb2->query($query);
       	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
		   	$answers = $res->fetchAll();

			 	$list = '';
			 	//-- LIST of questions
			 	for ($qn=0; $qn <= count($answers)-1; $qn++){
					 $qtype    = strtolower($answers[$qn][2]);			
					 $Nanswers = $answers[$qn][3];  

					 // if NO user answer => NO answer/score
					 //$score = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
					 //echo $answers[$qn][1];
					 if (is_null($answers[$qn][1])) {
						  	$ans   = '&nbsp;';
						    $score = '&nbsp;';	
								$tscore = 'NULL';					
					 } else {
					      //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
						 		$score  = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
								$tscore = $this->get_total_score($score,$answers[$qn][1],$qtype);
					 } 
					 
					 // RECORD: Question Number | Answer | Total Score | 
					 //$record = array($answers[$qn][0],$answers[$qn][1],$tscore);
					 $this->record[$r] = $tscore;
					 $r++;
			 	} // eof $qn
			} // eof $i
	
	return $this->record;
	} 
	//----------------------------------------------------------------------------
  	function get_total_score($score,$qanswer,$qtype) {
	//----------------------------------------------------------------------------
	  //var_dump($score); die();
		switch ($qtype) {
	  //-------------------------------
     case 'm':
    //-------------------------------  
			if (is_array($score)){ $total_score = array_sum($score[0]); }
			else 							   { $total_score = $score;  							}
			break;
	  //-------------------------------
     default:
		  $total_score = $score;
	  //-------------------------------
		}
		// format to display only 2 sig digits
		$total_score = round(100*$total_score)/100;
		
		return $total_score;
	}
	//----------------------------------------------------------------------------
  	function get_question_score($qid,$qanswer,$qtype) {
	//----------------------------------------------------------------------------
	//echo 'type: '.$qanswer; die();
		switch ($qtype) {
	  //-------------------------------
     case 'mc':
    //-------------------------------
		  if (empty($qanswer)){ $score = 0; }
			else { 
      $query = 'SELECT weight'.(ord($qanswer)-64).' FROM '.$this->tb_name.'_'.$qtype.' WHERE id='.$qid; 	 
			//echo $query.'<p>'; //die();
			$res =& $this->mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
			$result = $res->fetchRow();	
			$score = $result[0];		 
			}       
	   break;
	  //-------------------------------
     case 'm':
    //-------------------------------		
		  // Obtain number of questions
		  $fields = 'L1,L2,L3,L4,L5,L6,L7,L8,L9,L10,L11,L12,L13,L14,L15,L16,L17,L18,R19,L20,L21,L22,L23,L24,L25,L26,L27';
			$query = 'SELECT '.$fields.' FROM webct_m WHERE id='.$qid; 	 

			$res =& $this->mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
			$result = $res->fetchRow();
			$Nchoices = count(array_filter($result));
			
      $query = 'SELECT lower(answered),comment FROM stats_'.$this->id.' WHERE question_id='.$qid; 	 
			$res =& $this->mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
			$result = $res->fetchAll();
			
			/* USE ONLY FIRST ANSWER !! */
			$score = array();
			for ($an=0; $an <= (count($result)-1); $an++){
					$answered = explode(',',$result[$an][0]);
					$config = explode(',',$result[$an][1]);
						
					for ($v=0; $v <= (count($answered)-1); $v++){
			  	  //echo $answered[$v].' | '.chr($config[$v]+96).'<p>';
						if (empty($answered[$v])) { 	
						  $score[$an][$v] =	NULL;
						} else {
						  //echo '<p>'.(int)($answered[$v]==chr($config[$v]+96)).'<p>';
							//echo '<p>'.$answered[$v];
  			      $score[$an][$v] = 100*((int)($answered[$v]==chr($config[$v]+96)))/$Nchoices;
						}					
			   }
			}
	   break;
	  //-------------------------------
		     case 'c':
    //-------------------------------
		  // user answer:
		  $answer = explode(',',$qanswer);
			
			$query = 'SELECT * FROM '.$this->tb_name.'_'.$qtype.' WHERE id='.$qid;  
			$res =& $this->mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
			$result = $res->fetchRow();	
			
			// Obtain values and range
			$Nvals   = $result[(count($result)-1)];
			$formula = $result[1];	// formula
			
			// IF user-stored value exists, use it in formula, ELSE get min_val{i}
			$val_idx = 2; 
			for ($v=0; $v <= ($Nvals-1); $v++){		
			  if (count($answer)<=1){ $replace[$v] = $result[3*($v+1)]; } // min_val{v}
				else 									{ $replace[$v] = $answer[$v+1];     }
				
				//echo '<p>REPLACE: '.$result[$val_idx].'<p>';
			  $formula = str_replace('{'.$result[$val_idx].'}',$replace[$v],$formula);
				$val_idx = $val_idx + 3;
			}
			//var_dump($formula);die();
			eval("\$solution=$formula;");

			// SCORE: (user_answer-solution)/solution
			$chunks = preg_split("/[\s,=]+/",$answer[0]);
			//var_dump($chunks);die();
			for ($a=0; $a <= count($chunks)-1; $a++){
			  //echo $chunks[$a].'<p>';
			  if (is_numeric($chunks[$a])) {
				  $toll_array[$a] = abs(1-($chunks[$a]/$solution));
					//echo '<p>'.$toll_array[$a].' | '.$chunks[$a].'<p>';
				}else{
				   $tmp = '';
					 //echo $chunks[$a];//die();
					 eval('$tmp="'.$chunks[$a].'";');
					 //echo $tmp;die();
					 if (is_numeric($tmp)) {
					   $toll_array[$a] = abs(1-($tmp/$solution));
					 }else{
					 //die('not num');
					 eval("\$tmp=\"$chunks[$a]\";");
					 $toll_array[$a] = abs(1-($chunks[$a]/$solution));
					 //echo '<p>'.$toll_array[$a].' | '.$chunks[$a].'<p>';
					 } 
				}
			}
			// obtain highest tolerance
			//print_r($toll_array);die();
			sort($toll_array);
			$toll = $toll_array[0];
			//die();
			// tolerate 0.1 deviation: 10%
			if ($toll < 0.1){ $score = 100; }
			else 					  { $score = 0;   }	  

			//if ($qid==1097){$score=100;};
			//echo '<p>FORMULA: '.$formula.' -- '.$answer[0].' | '.$solution.' | '.$toll.'<p>'; 		
	   break;
	  //-------------------------------
     default:
		  $score = NULL;
	  //-------------------------------
	  }		
			return $score;
    }
	//----------------------------------------------------------------------------
  	function get_question_data($qid,$user_list) {
	//----------------------------------------------------------------------------
			$idx = 0;
  		$DATA = array();
			// iterate thru all users
			for ($u=0; $u <= count($user_list)-1; $u++){
		     $query = 'SELECT answered FROM stats_'.$user_list[$u].' WHERE question_id='.$qid; 	
				   
				 $res =& $this->mdb2->query($query);
       	 if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
		       
	   		 $result = $res->fetchCol();	 

				 //die();
				 for ($aN=0; $aN <= count($result)-1; $aN++){
				   if (!empty($result[$aN])) {
			       $DATA[$idx] = $result[$aN];
				     $idx = $idx + 1;
			     } else {
				    // echo 'empty at: '.$user_list[$u].' | '.$qid.' | '.$result[0].'<p>'; 
		       }
				 }
		  }
			return $DATA;
    }
	//----------------------------------------------------------------------------
  	function get_question_stats($data,$qtype,$Nanswers) {
	//----------------------------------------------------------------------------	
	  $stats = '';
		switch ($qtype) {
		//-------------------------------
     case 'm':
    //-------------------------------	
		// Initialize: DATAT[choice][letter] = 0
		$keys = range('a',chr(($Nanswers-1)+97));
		$vals = array_fill(0,($Nanswers),0);
		$choices = array_combine($keys,$vals);
		$stats = array_fill(0,($Nanswers),$choices);
		
		// for each record
		$Nrecords = count($data);
		for ($r=0; $r <= ($Nrecords-1); $r++){
		   $record = explode(",",strtolower($data[$r]));
			 //echo '<p>'.$data[$r].'<p>';
			 for ($x=0; $x <= ($Nanswers-1); $x++){
			   $letter = $record[$x]; //'A'
				 //echo '<p>'.$letter.'| - '.in_array($letter,$keys).'<p>';
				 if (!empty($letter) && in_array($letter,$keys)){   
		       $stats[$x][$letter] = $stats[$x][$letter]+1/$Nrecords;
				   //echo '<p>dataT['.$x.']['.$letter.']=<p>';
				 }
			 }
		}		  
		// round to (x%) 
		$N = count($stats);
		for ($r=0; $r <= ($N-1); $r++){
			 for ($x=0; $x <= ($N-1); $x++){
			   $stats[$r][chr($x+97)] = round(100*$stats[$r][chr($x+97)]);
			 }
		} 
	   break;
	  //-------------------------------
     case 'mc':
    //-------------------------------
      $data_count = array_count_values($data);
			
      for ($mc=0; $mc <= ($Nanswers-1); $mc++){
				// determine if any answers exist, if not set to 0
				if (array_key_exists(chr($mc+65),$data_count)){
					$stats[$mc] = round(100*($data_count[chr($mc+65)]/count($data)));
				}else{
					$stats[$mc] = 0;
				}
      	//$label[$mc]  = chr($mc+65); 	 	
			}				        
	   break;
		//-------------------------------
     case 'p':
    //-------------------------------
		//var_dump($data);
		//die();
		break;
	  //-------------------------------
     case 'c':
    //-------------------------------
		  $L = 5; // list length
		  $data_count = array_count_values($data);
			arsort($data_count);
			$data_count = array_slice($data_count,0,$L);
			
			$idx = 0;
			foreach ($data_count as $key => $value) {
      	$label[$idx]  = $key;
      	$values[$idx] = $value;
				$idx = $idx + 1; 	
			}	
			$stats = array($label,$values);
		 break;
	  //-------------------------------
	  }
		return $stats; 
   }
	//----------------------------------------------------------------------------
  	function render_question_answer($score,$answer,$qtype) {
	//----------------------------------------------------------------------------	
		// if answer: CORRECT | INCORRECT |
		  switch ($qtype) {
		  //-------------------------------
       case 'm':
      //-------------------------------
			  $ans = '';
		  	$answer = explode(",",$answer);
				
				$list = array();
				for ($a=0; $a <= (count($answer)-1); $a++){
				  //echo $score[0][$a].'<p>';
				  if (empty($answer[$a])){ $ans_str = '&nbsp;';                }
					else 									 { $ans_str = strtoupper($answer[$a]); }

					 //echo 'IE: '.$score[0][$a].'<p>';
					if (is_null($score[0][$a])) {// answer: NULL
					   $list[$a] = '<span class="TextAlphabet">'.($a+1).'. </span><span class="ITS_null">'.$ans_str.'</span><br>';	
				  } elseif ($score[0][$a] > 0) {
					   $list[$a] = '<span class="TextAlphabet">'.($a+1).'. </span><span class="ITS_correct">'.$ans_str.'</span><br>';
		      } else {
			  	   $list[$a] = '<span class="TextAlphabet">'.($a+1).'. </span><span class="ITS_incorrect">'.$ans_str.'</span><br>';
		      }
				}
				$tb = new ITS_table('name',count($list),1,$list,array(100),'ITS_LIST');
				$ans = $tb->str;
				/*
				if (is_null($score)) {// answer: NULL
				  $ans   = '<div class="ITS_feedback" style="border:1px solid black">'.$answer.'</div>';
				} elseif ($score) {
		  		$ans = '<div class="ITS_feedback">'.$answer.'</div>';
		    } else {
			  	$ans = '<div class="ITS_feedback"><span class="ITS_incorrect">'.$answer.'</span></div>';
		    }
				*/		        
		   break;
		  //-------------------------------
       default:
      //-------------------------------
			  if (is_null($score)) { // answer: NULL
				  $ans   = '<div class="ITS_feedback" style="border:1px solid black">'.$answer.'</div>';
			  } elseif ($score) { 
		  		$ans = '<div class="ITS_feedback"><span class="ITS_correct">'.$answer.'</span></div>';
		    } else {
			  	$ans = '<div class="ITS_feedback"><span class="ITS_incorrect">'.$answer.'</span></div>';
		    }
	    //-------------------------------		
		}			 	  							
		return $ans; 
   }
	//----------------------------------------------------------------------------
  	function get_question_dist($stats,$qid,$qtype) {
	//----------------------------------------------------------------------------	
    $dist = '';
		switch ($qtype) {
		//-------------------------------
     case 'm':
    //-------------------------------
			// Obtain number of questions
		  $fields = 'L1,L2,L3,L4,L5,L6,L7,L8,L9,L10,L11,L12,L13,L14,L15,L16,L17,L18,R19,L20,L21,L22,L23,L24,L25,L26,L27';
			$query = 'SELECT '.$fields.' FROM webct_m WHERE id='.$qid; 	 

			$res =& $this->mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
			$result = $res->fetchRow();
			$Nchoices = count(array_filter($result));
			
			//-- draw distribution
			/*
			$values = '';
			for ($v=0; $v <= (count($stats)-1); $v++){
			  if ($v==0) { $sep = '';  }
				else 			 { $sep = ','; }
			  $values = $values.$sep.implode(",",$stats[$v]);
			}*/
			
			$data = array();
			$data = range('A',chr($Nchoices+64));
			foreach ($data as &$val){
			  $val = '<font color="blue"><b>'.$val.'</b></font>';
			}
			array_unshift($data,'<font color="green"><b>%</b></font>');

			$idx = $Nchoices+1;
			for ($r=0; $r <= (count($stats)-1); $r++){
			   $data[$idx] = '<b>'.($r+1).'.</b>';
				 $idx++;
			  for ($c=0; $c <= ($Nchoices-1); $c++){
				  //echo $stats[$r][chr($c+97)].'<p>';
			    $data[$idx] = $stats[$r][chr($c+97)];
				  $idx++;
			  }
			}
			$width = array_fill(0,$Nchoices+1,100/$Nchoices);
			$tb = new ITS_table('name',(count($stats)+1),($Nchoices+1),$data,$width,'ITS_SCORE SMALL_FONT');
			$dist = $tb->str;
			//$dist = '<img src="phpimg/ITS_matrix.php?size='.$Nchoices.'&values='.$values.'" class="ITS_list">';		        
		 break;
	  //-------------------------------
     case 'mc':
    //-------------------------------
			//-- draw distribution
			$values = implode(",",$stats);
			$dist = '<img src="phpimg/ITS_bar.php?values='.$values.'" class="ITS_list">';		        
	   break;
	  //-------------------------------
     case 'c':
    //-------------------------------
			$idx = 0;
			for ($r=0; $r <= (count($stats[0])-1); $r++){
			   $data[$idx]   = $stats[1][$r].'%';
				 $data[$idx+1] = $stats[0][$r];
				 $idx = $idx + 2;
			}
			$width = array(20,80);
			$tb = new ITS_table('DIST',count($stats[0]),2,$data,$width,'ITS_LIST');
			$dist = $tb->str;
			//$dist = '<img src="phpimg/ITS_list.php?values='.$values.'&labels='.$label.'" class="ITS_list">';
		  break;
		//-------------------------------
     default:
    //-------------------------------
		  $dist = 'default';
	  //-------------------------------
	  }
		$caption = '<DIV class="ITS_CAPTION">'.preg_replace('[_]',' ',$this->term).'</DIV>';
		$class = new ITS_table('ITS_table_stats',2,1,array($caption,$dist),array(100),'ITS_DIST');
		$dist = $class->str;	
		return $dist; 
   }
	 //----------------------------------------------------------------------------
  	function render_survey() {
	//----------------------------------------------------------------------------
			//-- obtain activity: name
 			$query = 'SELECT DISTINCT name,active FROM activity WHERE term="'.$this->term.'"';
 			$res = $this->mdb2->query($query);
			if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
			$activiy_info = $res->fetchAll();

			// activity header
			$title = 'Activity';
			$tb    = new ITS_table('ITS_table_stats',1,2,array('FALL 2009 SURVEY QUESTIONS',$this->term.' class'),array(70,30),'ITS_feedback_header'); 
			//$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$tb->str),array(15,85),'ITS_feedback_header');
		  echo $tb->str;
				
			$i = 12; // survey
			  $activiy_name = 'lab13'; //$activiy_info[$i][0];
				$activiy_ACTIVE = $activiy_info[$i][1];
			
			  if (($i+1) < 10) { $num = '0'.($i+1); }
				else             { $num = $i+1;       } 

				//----***--------//
				$query = 'SELECT id FROM users WHERE status="'.$this->term.'" AND '.$activiy_name.' IS NOT NULL';  
				$res =& $this->mdb2->query($query);
    		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
				$activity_users = $res->fetchCol();
				//----***--------//
				
				// activity completed?
				$query = 'SELECT '.$activiy_name.' FROM users WHERE id='.$this->id;
    		$res =& $this->mdb2->query($query);
    		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
				$activity_completed = $res->fetchRow();
				$activity_COMPLETE = $activity_completed[0];
				
				$stats_SHOW = FALSE;
				
				if (strcmp($activiy_name,'survey01') == 0 ) {
				       $title_name = 'Survey';            }
				else{	 $title_name = 'Exercise #'.($i+1); }
						
				$title = '<font color="royalblue">'.$title_name.'</font>';
				
					// TITLE: IF active & !complete => link, ELSE name
					// OTHER: IF !active & complete => show, ELSE nothing
					  if ($activiy_ACTIVE) {
						  if (!($activity_COMPLETE)){
					      // TITLE  
                $title = '<a href="ITS_pre_lab.php?activity='.($i+1).'">'.$title.'</a>';
					    }
						} 
						else {
						  if ($activity_COMPLETE) { $stats_SHOW = TRUE; }
						}

				//echo '<p>'.$activiy_ACTIVE.' | '.$activity_COMPLETE.' | '.$stats_SHOW.'<p>';
        if ($stats_SHOW) {
				//-- Result: question_id | answered => for user's activity and term 
				if ($activity_COMPLETE) {
			 		 $query = "SELECT activity.question_id,answered,qtype,answers FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) LEFT JOIN stats_$this->id ON (activity.question_id=stats_$this->id.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder"; 
				} else { 
			 		 $query = "SELECT question_id,NULL,qtype,answers FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder"; 
				}
				//echo $query;die();
				$res =&$this->mdb2->query($query);
       	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
		   	$answers = $res->fetchAll();

			 	$list = '';
			 	//-- LIST of questions
			 	for ($qn=0; $qn <= count($answers)-1; $qn++){
					 $qtype    = strtolower($answers[$qn][2]);			
					 $Nanswers = $answers[$qn][3];  

					 // if NO activity users => NO distribution
					 if (count($activity_users)) {
			 			   $DATA  = $this->get_question_data($answers[$qn][0],$activity_users);
						   $stats = $this->get_question_stats($DATA,$qtype,$Nanswers);
						 	 $dist  = $this->get_question_dist($stats,$answers[$qn][0],$qtype);
					 } else {
						   $dist  = '';
					 }
					      //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
						 		$score  = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
						    $ans    = $this->render_question_answer($score,$answers[$qn][1],$qtype);
								
								$Q = new ITS_question(1,'its',$this->tb_name);
	 							$Q->load_DATA_from_DB($answers[$qn][0]);
	 							$Estr = $Q->render_QUESTION_check();
								$Estr = '<b>'.$Estr.'</b>';
								
								if (($qn==count($answers)-1) OR ($qn==count($answers)-2)){
								  $ua_str = '<UL>';
								  for ($ua=0; $ua <= count($DATA)-1; $ua++){
										if (($ua % 2) == 0){ $style = "ITS_SURVEY_STRIPE"; }
			 		          else 			         { $style = "ITS_SURVEY";        }
										
									  $ua_str = $ua_str.'<LI><DIV class='.$style.'>'.$DATA[$ua].'</DIV>';
									}
								  $ua_str = $ua_str.'</UL>';
								  $tscore = $Estr.'<p>'.$ua_str;
								}else{
	 							  $Q->get_ANSWERS_data_from_DB();
	 							  $Estr = $Estr.'<p><center>'.$Q->render_ANSWERS('a');
								  $tscore = $Estr;
								}
					 
					 // add horiz rule
					 if   ( $qn == count($answers)-1) { $hr = ''; 												 }
					 else 	 		 											{ $hr = '<hr class="ITS_feedback">'; }
					 
					 if (($qn==count($answers)-1) OR ($qn==count($answers)-2)){
					   $tb = new ITS_table('ITS_survey',1,1,array($tscore),array(100),'ITS_survey'); 
			  	   $list = $list.$tb->str.$hr;
					 }else{
			  	   $tb = new ITS_table('ITS_survey',1,2,array($tscore,$dist),array(70,30),'ITS_survey'); 
			  	   $list = $list.$tb->str.$hr;
					 }
			 	} // eof $qn
				}
				else {
				   $list = '';
				}
				
			echo $list;
			//$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$list),array(15,85),'ITS_feedback_list');
      //echo $tb_activity->str;
	 }
	//----------------------------------------------------------------------------
//==============================================================================
} //End of class ITS_statistics
?>
