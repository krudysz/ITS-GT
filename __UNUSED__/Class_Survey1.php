<?php
//=====================================================================//
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
class Class_Survey1 {
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
  	function render_survey() {
	//----------------------------------------------------------------------------
			//-- obtain activity: name
 			$query = 'SELECT DISTINCT name,active FROM activity WHERE term="'.$this->term.'"';
 			$res = $this->mdb2->query($query);
			if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
			$activiy_info = $res->fetchAll();

			// activity header
			$title = 'Activity';
			$tb    = new ITS_table('ITS_table_stats',1,4,array('&nbsp;','Answer','Score',preg_replace('[_]',' ',$this->term.' class')),array(10,30,30,30),'ITS_feedback_header'); 
			$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$tb->str),array(15,85),'ITS_feedback_header');
		  echo $tb_activity->str;

			//-- For each activity:
			$SUM = 0;
			for ($i = 0; $i <= count($activiy_info)-1; $i++) {
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
								
								if ($i==12){
								$Q = new ITS_question(1,'its',$this->tb_name);
	 							$Q->load_DATA_from_DB($answers[$qn][0]);
	 							$Estr = $Q->render_QUESTION_check();
	 							$Q->get_ANSWERS_data_from_DB();
	 							$Estr = $Estr.'<p><center>'.$Q->render_ANSWERS('a');
								$tscore = $Estr;
								}else{
								$tscore = '-';
								}
					 
					 // add horiz rule
					 if   ( $qn == count($answers)-1) { $hr = ''; 												 }
					 else 	 		 											{ $hr = '<hr class="ITS_feedback">'; }
					 
					 // RECORD: Question Number | Answer | Total Score | 
					 //$record = array($answers[$qn][0],$answers[$qn][1],$tscore);
					 $this->record[$i][$qn] = $tscore;
					 $SUM = $SUM + $tscore;
			  	 $tb = new ITS_table('ITS_table_stats',1,4,array('<b><a href="Question1.php?qNum='.$answers[$qn][0].'">'.($qn+1).'</a>&nbsp;</b>',$ans,$tscore,$dist),array(10,30,30,30),'ITS_feedback'); 
			  	 $list = $list.$tb->str.$hr;
			 	} // eof $qn
				}
				else {
				   $list = '';
				}
			$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$list),array(15,85),'ITS_feedback_list');
      echo $tb_activity->str;
			} // eof $i
			
			//var_dump(array_sum($this->record));
			echo $SUM;
	 }
	//----------------------------------------------------------------------------
	//==============================================================================
} //End of class ITS_statistics
?>
