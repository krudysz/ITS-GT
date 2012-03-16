<?php
//error_reporting(E_ALL);
//include("config.php");
require_once("Grading.php");
//require_once("BNT.php");
require_once("MDB2.php");
include "classes/ITS_question.php";
//include("admin\admin_config.php");

define('DEFAULT_QUESTION_ID',70089);

class Question_Control_Exception extends Exception {}
class Question_control
{
//const DEFAULT_QUESTION_ID = 60234;	// default question id for first time user

private $_user_id;
private $_index;
private $_current_question_id;
private $_current_question_data;
private $_question_mode = 'SURVEY';  // |'BNT'|'SURVEY'|
private $_survey_questions = array(70089,70033,70016,70030,70028,70019,70023); //,70028,70031,70034);
private $_survey_completed = false;
//==============================================================================
public function __construct($user_id)
//==============================================================================
{	
	global $db_dsn, $db_table_user_state;

	$mdb2 =& MDB2::connect($db_dsn);
	if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	
	$res =& $mdb2->query("SELECT id,question_id FROM $db_table_user_state$user_id");
	$res_id =& $mdb2->query("SELECT answered FROM $db_table_user_state$user_id WHERE answered IS NOT NULL");
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $nrows = $res->numRows();

	//echo $nrows;
	//die();
	if ($nrows == 0){
	  // no question records found for this user
		$this->_index = 1;
		$this->_current_question_id = DEFAULT_QUESTION_ID;
		
		// Record question number in STATS
		//$res->free();
		//$query_str = "INSERT INTO $db_table_user_state$this->_user_id (question_id) VALUES($this->_current_question_id)";
		//$res = & $mdb2->query($query_str);
		//if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
	}
	elseif ($res_id->numRows() >= count($this->_survey_questions)) {
	   $this->_survey_completed = true;
	}
	else{
		$row = $res->fetchRow(MDB2_FETCHMODE_ASSOC,$nrows-1);
		$this->_index = $row['id'];
		$this->_current_question_id = $row['question_id']; 
	}
	
	$res->free();
	$mdb2->disconnect();

	$this->_user_id = $user_id;
	
	// continue with survey if it is still in progress
	if (!$this->_survey_completed){
	  $this->get_question_data();
	}
}
//==============================================================================
private function get_question_data()
//==============================================================================
{
	global $db_dsn, $db_table_question, $db_table_user_state;
	
	$mdb2 =& MDB2::connect($db_dsn);
	if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	
	echo "<p>SELECT * FROM $db_table_question WHERE id=$this->_current_question_id<p>";
	//die();
	//var_dump($this->_current_question_id); // TESTING HERE
	
	$res =& $mdb2->query("SELECT * FROM $db_table_question WHERE id=$this->_current_question_id");
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

	if ($res->numRows() == 0){
		throw new Question_Control_Exception("Error: Question id: $this->_current_question_id not found.");
	}
	
	$this->_current_question_data = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	$res->free();

	//-- update user STATS table --//
	echo "INSERT IGNORE INTO $db_table_user_state$this->_user_id (id,question_id) VALUES($this->_index,$this->_current_question_id)";
	$query_str = "INSERT IGNORE INTO $db_table_user_state$this->_user_id (id,question_id) VALUES($this->_index,$this->_current_question_id)";
	$res->free();

	$res = & $mdb2->query($query_str);
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
	
	$mdb2->disconnect();
}
//==============================================================================
public function show_question()
//==============================================================================
{
	global $db_dsn,$db_name,$tb_name;
	
	if ($this->_survey_completed){
	/*
	  // redirect to analysis page
	  header("Location: http://" . $_SERVER['HTTP_HOST']
		. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
		. "/learning_analysis.php");
 */
	  echo "<p><center><DIV class=Question>"
	    ."<p>You have already answered all the questions.<br/>"
			."</DIV></center>";
	}
	else {
	// connect to database
  $mdb2 =& MDB2::connect($db_dsn);
  if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
  
	// Show code for this concept
	/*
	$query = "SELECT code FROM concept WHERE id=(SELECT concept_id FROM question WHERE id=$this->_current_question_id)";
	//echo $query;
	$res =& $mdb2->query($query);
	$row=$res->fetchRow(MDB2_FETCHMODE_ASSOC);
	
	if (!empty($row['code'])){
	//  echo "<p>".$row['code']."<p>";
	  echo "<p>".ITS_LaTeX($row['code'])."<p>";
	}
	*/
	//die();
	echo "<span class=ITS_text><b>".$this->_index." [ ".count($this->_survey_questions)." ] </b></span><p>";
	
	switch ($this->_current_question_data['format']) {
	//-------------------------------
  case "LV":
  //-------------------------------
	$gui_name = "Pez Demo";
    echo "<iframe COMPUTER_NAME='http://ganglia2:8080' " 
		    ."src='http://ganglia2:8080/LV86_OBJ.html' "
		    ."WIDTH=500 HEIGHT=500 "
				."marginheight=0 marginwidth=0 "
				."frameborder=0 scrolling=0"
				."</iframe><br>";

	  // SUBMIT BUTTON
		echo "<p><input type=submit name=score_question value=Submit>";
	break;
	 //-------------------------------
  default:
  //-------------------------------
    $Q = new ITS_question(1,$db_name,$tb_name);
	  $Q->load_DATA_from_DB($this->_current_question_data['resource']);
	  //$Q->render_TITLE();
	  $Q->render_QUESTION_check();
	  $Q->get_ANSWERS_data_from_DB();
	  //$Q->get_ANSWERS_solution_from_DB();
	  $Q->render_ANSWERS();
	  $mdb2->disconnect();
	//-------------------------------
	}
	}
}
//==============================================================================
public function check_answer($answer_array)
//==============================================================================
{
	// get evidence
	$evidence = Grading::grade($this->_index,$this->_current_question_data,$answer_array,GRADING_PARTIAL_CREDIT);
	//echo "<p>QC: evi: $evidence </p>";
	//var_dump($evidence);
	
	switch ($this->_question_mode) {
	//-------------------------------
  case "BNT":
  //-------------------------------
	  // connect to BNT and receive next concept (maybe the same concept)
	  $next_concept = BNT::submit($this->_user_id,$this->_current_question_data['concept_id'],$evidence, $this->_current_question_id);
	
	  $this->next_question($next_concept);		// sets new _current_question_id
		$this->_index = $this->_index + 1;
	  $this->get_question_data();							// fetch new question data from database
	  break;
	//-------------------------------
  case "SURVEY":
	//-------------------------------
	  //echo "<span class=ITS_text><b>".$this->_index."|".count($this->_survey_questions)."</b></span><p>";
	
	  if ($this->_index < count($this->_survey_questions)){
		   $this->_current_question_id = $this->_survey_questions[$this->_index];
			 $this->_index = $this->_index + 1;
	     $this->get_question_data();							// fetch new question data from database
	  }
	  else {
		   $this->_survey_completed = true;
			 unset($_SESSION['question_control']);
	     echo "<p><center><DIV class=Question>"
	     ."<p>Thanks, your answers have been recorded."
			 ."</DIV></center>";	
	  }
    break;
	//-------------------------------
  }
	
	return $evidence;
}
//==============================================================================
private function next_question($concept)
//==============================================================================
{ // TODO: find next question...	

	// questions available
	global $db_dsn,$db_table_user_state;
	
	$mdb2 =& MDB2::connect($db_dsn);
	if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	// questions already worked on $concept
	// answered
	//echo "<p>SELECT question_id,score from $db_table_user_state$this->_user_id LEFT JOIN question ON $db_table_user_state$this->_user_id.question_id = question.id WHERE question.concept_id = $concept";
	//die();
	$res =& $mdb2->query("SELECT question_id,score from $db_table_user_state$this->_user_id LEFT JOIN question ON $db_table_user_state$this->_user_id.question_id = question.id WHERE question.concept_id = $concept");
	
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
	$answered = array();
	//while ($res->fetchInto($row,MDB2_FETCHMODE_ASSOC)) {
	//	$answered[$row['question_id']] = $row['score'];
	//}
		while ($row=$res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
		$answered[$row['question_id']] = $row['score'];
	}
	// var_dump($answered);

	$res->free();
	// select questions from QUESTION_DB for given "concept" (currently concept = 7 - incorrectly [should be concept #])
	//echo "<p>SELECT resource from question WHERE concept_id = $concept";
	//var_dump($answered);
	//die();
	$res =& $mdb2->query("SELECT id from question WHERE concept_id = $concept");
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

	$question_list = array();
	// also replacing fetchInto
	while ($row=$res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
		if ( !array_key_exists($row['id'],$answered) ){
			$question_list[] = $row['id'];
			break;		// TODO: get the top one FOR NOW
		}
	}
	//var_dump($question_list); die();
	$res->free();	
	$mdb2->disconnect();

	if (!empty($question_list)){
		$this->_current_question_id = $question_list[0];
	}
	else{				// all questions are answered.. pick the one with lowest score
		asort($answered);
		reset($answered);
		$this->_current_question_id = key($answered);
	}
	
	//++$this->_current_question_id;
}
//==============================================================================
public function show_answer()
//==============================================================================
{
	global $answer_dir, $answer_file_ext;
	readfile("$answer_dir/" . $this->_current_question_data['answer_file'] . ".$answer_file_ext");
}
//==============================================================================
}
?>

