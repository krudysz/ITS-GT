<?php
/*Class for handling statistical displays*/
//==============================================================================
class ITS_stats {
//==============================================================================

	private $users; 		// Stores user id
	private $user_name; // Stores username
	private $stats;     // Stores statistical data to be output
	public  $data;      // Stores all raw data from database
	public  $hist;
	private $str;       // Stores html string
	private $mdb2;
			
  function __construct() 
	{
	  global $db_dsn;
	  
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
			
	  $this->mdb2 = $mdb2;				 
	  //$query = "SELECT name,question_id,answered,score FROM stats_$user_id LEFT JOIN question ON stats_$user_id.question_id=question.id LEFT JOIN concept ON question.concept_id=concept.id";
	  //$this->data =& $mdb2->query($query);
		//if (PEAR::isError($this->res)) { throw new Exception($this->res->getMessage()); }
		
		//self::get_data();
  }
	//============================================================================
	function get_data() {
  //Creates matrix holding raw questions and answer data of all users to be used 
	//for processing by other functions. Run in constructor
	//============================================================================
	  //Get user data of all users
	  $query = "SELECT id FROM users WHERE status='Spring_2009' AND survey01 IS NOT NULL";
  	$users =& $this->mdb2->query($query);
	  if (PEAR::isError($this->users)) {throw new Exception($users->getMessage());}
		
		// USER vector
		for ($count = 0; $count < $users->NumRows(); $count++){
			 $this->user_id[$count] = $users->fetchOne();
		}
		
		// DATA vector: [USER ID][QUESTION ID][ANSWER]
		foreach ($this->user_id as $student_id){
		   $query = "SELECT question_id,answered FROM stats_".$student_id;   
		   $res_data =& $this->mdb2->query($query);
			 
			 while (($row = $res_data->fetchRow())) {
         // Assuming MDB2's default fetchmode is MDB2_FETCHMODE_ORDERED
				 $data[$student_id][$row[0]] = $row[1];													
		   }
		}	 
    // DATA vector: [USER ID][QUESTION ID][ANSWER]
 		$this->data = $data; 
	}// End of get_res;
	//============================================================================
  function get_stats($questions){
	//============================================================================
		//$query = "SELECT qtype FROM webct WHERE;   
		//$res_data =& $this->mdb2->query($query);
			/* 
		while (($row = $res_data->fetchRow())) {
      // Assuming MDB2's default fetchmode is MDB2_FETCHMODE_ORDERED
			$data[$student_id][$row[0]] = $row[1];													
		}
			 */
	
		 foreach ($questions as $qn){
		   $hist[$qn] = array(0,0,0,0,0,0);
			 
			 $idx = 0;
		   foreach ($this->user_id as $user_id){		
			    //echo ($this->data[$user_id][$qn])."|".isset($this->data[$user_id][$qn])."<p>";
			    if (isset($this->data[$user_id][$qn])){
					  if (($qn == 399) || ($qn == 422)){
						   $hist[$qn][$idx] = $this->data[$user_id][$qn];
							 $idx++;
						} 
						else {
		           $hist[$qn][(ord($this->data[$user_id][$qn])-65)]++;
						}
				  }
		   }
			 //echo "<p>".$qn." :".$hist[$qn][0]." | ".$hist[$qn][1]." | ".$hist[$qn][2]." | ".$hist[$qn][3]." | ".$hist[$qn][4]." | ".$hist[$qn][5];
		   //die();
		 }
		 $this->hist = $hist;
	}
	//============================================================================
  function display_all_users(){
	//Displays average scores of all users in a bar graph
	//============================================================================
	
	  echo "</p><h4>Average Scores for All Users</h4><br />";
    
	  // obtain all sums from raw data
		foreach ($this->user_id as $student_id){
		  $res_s_a[0] = 0;//stores sum of score to calculate average
			$num_ques = 0;//stores number of questions attempted
			for ($r = 0; isset($this->data[$student_id][$r][5]); $r++){ //go through all questions attempted 
			  $res_s_a[0] += $this->data[$student_id][$r][5]; 
				$num_ques++;
			}
			//echo "<p>".$res_s_a[0]." --- ".$num_ques;
			$res_s_a[1] = $res_s_a[0]/$num_ques;
 	    $this->stats[$student_id] = $res_s_a;
		}
		
	  foreach ($this->user_id as $student_id){ //Calculate avg for each student
		  $student = $this->users[$student_id-1]; //Stores user name
	    $avg = round(100*$this->stats[$student_id][1]); 
			//ITS_Table class used to display bar graphs
		  $tb_score = new ITS_table('score',1,2,array('&nbsp;','&nbsp;'),array($avg,100-$avg),'ITS_STATS_SCORED', 'ITS_STATS_FILL');
		  $tb = new ITS_table('user_score',1,2,array($student_id.") "."<a href='learning_analysis.php?Page=UserAnalysis&id=$student_id' >".$student[3]."</a> <div style='text-align:right;'>".$avg."%</div>",$tb_score->str ),array(20,80),'ITS');
		  echo $tb->str;
	  }
		echo "</p><a href='javascript:javascript:history.go(-1)'>back</a>";
	}//End of display_avg()
	
	//============================================================================
  function display_all_questions($graph){
	//$graph == false :Displays table of raw data of questions. Includes no. of 
	//users who attempted the question, no. of users who answered correctly
	//$graph == true :Displays bar graph of average score of each question 
	//============================================================================	

	
		$count = 0;
		foreach ($this->user_id as $student_id){
		  for ($question = 0; isset($this->data[$student_id][$question]); $question++){
			  $found = false;
			  for ($n = 0; isset($data[$n][1]); $n++){
				  if ($data[$n][1] == $this->data[$student_id][$question][1]){
					  $found = true;
						$data[$n][4]++;
						$data[$n][5] += $this->data[$student_id][$question][5];
					} //if 
				} //for
				if (!$found){
				  $data[$count][0] = $count+1;
					$data[$count][1] = $this->data[$student_id][$question][1];
					$data[$count][2] = $this->data[$student_id][$question][2];
					$data[$count][3] = $this->data[$student_id][$question][3];
					$data[$count][4]++;
					$data[$count][5] += $this->data[$student_id][$question][5];
					$data[$count][6] = $this->data[$student_id][$question][6];
  				$data[$count][7] = $this->data[$student_id][$question][7];
					$data[$count][8] = $this->data[$student_id][$question][8];
					$count++;
				}
			}//for
		}//foreach

		if (!$graph){ //Display table
		  echo "</p><h4>Statistics of All Questions</h4><br />";
  		echo "</p><a href='learning_analysis.php?Page=AllQuestions&Graph=1'>View Graph of Avg Scores</a></p>";	
			
			//Obtain table headers through stats_1 table
		  $query = "DESCRIBE stats_1";
    	$label =& $this->mdb2->queryCol($query);
	    if (PEAR::isError($label)) {throw new Exception($label->getMessage());}
    
		  //Change labels from 'answered' and 'score' to 'Users Attempted' and 'Users Correct'
			$label[4] = 'Users Attempted';
		  $label[5] = 'Users Correct';
    
		  //$this->display_helper displays a table using the $label and $data
		  $this->display_helper($label, $data, '');		
		} else { //Display graph
		  echo "</p><h4>Average Score of All Questions</h4><br/>";
		  echo "</p><a href='learning_analysis.php?Page=AllQuestions&Graph=0'>View Table of Question Data</a></p>";	
			
		  for ($n = 0; isset($data[$n][5]); $n++) { //Go through each question id
	      $avg = round(100*$data[$n][5]/$data[$n][4]); //Average score
				$question_id = $data[$n][1]; //Stores question_id to display in graph
		    $tb_score = new ITS_table('score',1,2,array('&nbsp;','&nbsp;'),array($avg,100-$avg),'ITS_STATS_SCORED', 'ITS_STATS_FILL');
		    $tb = new ITS_table('user_score',1,2,array("<a href='learning_analysis.php?Page=QuestionAnalysis&Question_id=$question_id&Option=0' >".$data[$n][1]."</a><div style='text-align:right;'>".$avg."%</div>",$tb_score->str ),array(20,80),'ITS');
		    echo $tb->str;
			}	//for
			
			//echo "</p><a href='learning_analysis.php$backlink'>back</a></p>";
			echo "</p><a href='javascript:javascript:history.go(-1)'>back</a>";
	  }//if else

	} //end of display_all_questions()
	
	//============================================================================
  function display_ans($user_id){
	//Displays answer history of $user_id
	//============================================================================
	  $student = $this->users[$user_id-1]; //Stores user name
		echo "</p><h4>$student[3]'s Scores to Questions</h4>";
		
		//Obtain headings for table
  	$query = "DESCRIBE stats_$user_id";
  	$label =& $this->mdb2->queryCol($query);
	  if (PEAR::isError($label)) {throw new Exception($label->getMessage());}

		//$this->display_helper displays a table using the label and data, 3rd argument
		//is used to choose the page to link back to (currently replaced by javascript history(-1))
    $this->display_helper($label, $this->data[$user_id], '?Page=AllUsers');
	
	}//End of display_ans()

	//============================================================================
  function display_helper($label, $data, $backlink){
	//Displays table using headers from $label and data from $data
	//$backlink allows function call to choose which page to link back to,
	//currently replaced by javascript history(-1)
	//============================================================================
		$Table = "<table cellpadding=10 border=1>";
		$Table = $Table."<tr>";
		
		foreach ($label as $heading){
			$Table = $Table."<td>".$heading."</td>";	
		}
		$Table = $Table."</tr>";

		for ($count = 0; isset($data[$count]); $count++){
		  $Table = $Table."<tr>";
			$question = $data[$count];
			$col = 0;
			foreach ($question as $detail){
				if ($col == 1) { //inserting link question id
				  $Table = $Table."<td><a href='learning_analysis.php?Page=QuestionAnalysis&Question_id=$detail&Option=0' >".$detail."</a></td>";
				}
				else {
				  $Table = $Table.'<td>'.$detail.'</td>';
				}
				$col++;
			}
			$Table = $Table."</tr>";
		}
		
		$Table = $Table."</table>";
		
		echo $Table; //output html code to display table
		//echo "</p><a href='learning_analysis.php$backlink'>back</a></p>";
		echo "</p><a href='javascript:javascript:history.go(-1)'>back</a>";
	}//end of display_helper()
	
  //============================================================================
  function display_question_stats($question_id, $opt){
  // Displays Question based analysis, opt number allows different statistical 
	// anaylyses to be performed
	// Currently, ($opt == 0) displays statistics based on answers being correct or wrong,
	// ($opt == 1) displays statistics of all possible answers
	// To add more statistics, create array of options to expect from matrix
	// and pass question_id, options, result, column number to obtain data from into 
	// $this->display_question_stats_helper
  //============================================================================
	
	  echo "</p><h4>Question $question_id Statistics</h4><br />";
	
		if ($opt == 0) { //Display Graphs of Score
		  echo "</p><b>Score Analysis </b></p>";
			echo "</p><a href='learning_analysis.php?Page=QuestionAnalysis&Question_id=$question_id&Option=1' >View Response Analysis</a></p>";
			
		  // Display scores
		  $options = array ('0','1');
		  $res['0'] = 0;
		  $res['1'] = 0;
		  $this->display_question_stats_helper($question_id, $options, $res, 5);
		} elseif ($opt == 1) { //Display Graphs of Answers
		  echo "</p><b>Response Analysis </b></p>";
	    echo "</p><a href='learning_analysis.php?Page=QuestionAnalysis&Question_id=$question_id&Option=0' >View Score Analysis</a></p>";
			
			// Display answers
		  $options = array ('A','B','C','D');
		  $res['A'] = 0;
		  $res['B'] = 0;
		  $res['C'] = 0;
		  $res['D'] = 0;
		  $this->display_question_stats_helper($question_id, $options, $res, 4);
		}
		//echo "</p><a href='learning_analysis.php' >back</a>";
		echo "</p><a href='javascript:javascript:history.go(-1)'>back</a>";
	}//End of display_question_stats()
	
  //============================================================================
  function display_question_stats_helper($question_id, $options, $res, $col){
  //This function acts as a helper to display graphs from specific columns, $col, 
	//from the database.
	//============================================================================
		
		$num_id = 0;//Stores number of scores taken to calculate avg
		foreach ($this->user_id as $student_id){ //Obtain data from matrix through $col column
			for ($r = 0; isset($this->data[$student_id][$r][1]); $r++){
			  if ($this->data[$student_id][$r][1] == $question_id) {
				  $num_id++;
					$res[$this->data[$student_id][$r][$col]]++;  
				}			
			}
		}

	  foreach ($options as $ans) { //Display graph using percentages
	    $avg = round(100*$res[$ans]/$num_id);
		  $tb_score = new ITS_table('score',1,2,array('&nbsp;','&nbsp;'),array($avg,100-$avg),'ITS_STATS_SCORED', 'ITS_STATS_FILL');
		  $tb = new ITS_table('user_score',1,2,array($ans."<div style='text-align:right;'>".$avg."%</div>",$tb_score->str ),array(20,80),'ITS');
		  echo $tb->str;
	  }
	}//End of display_question_stats_helper($question_id, $col)
	
} //End of class ITS_stats
?>
