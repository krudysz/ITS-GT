<?php
//error_reporting(E_ALL);
require_once("config.php");
//require_once("admin/admin_config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");
require_once(INCLUDE_DIR . "Question_Control.php");

include("menu2.php");
include "classes/ITS_survey.php";
require_once("MDB2.php");

// return to login page if not logged in   <link rel="stylesheet" href="admin/style/warmup.css" type="text/css" />
abort_if_unauthenticated();
//--------------------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Survey</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<link rel="stylesheet" href="style/warmup.css" type="text/css" />

<script src="admin/ITS.js"></script>
<script src="admin/ITS_http.js"></script>
<script src="admin/prototype.js"></script>
<script src="js/ITS_lab.js"></script>
</head>
<body>
<DIV class="main">
<?php
//<link rel="stylesheet" href="pagestyles.css">
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

//-- SURVEY QUESTION LIST --
$res =& $mdb2->query("SELECT id FROM webct WHERE category='Survey'");
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

// Question List
$list = '';
$check = '';
$TB = '';

//----- DISPLAY -----
/*
while (($row = $res->fetchRow())) {

	 $Q = new ITS_question(1,$db_name,$tb_name);
	 $Q->load_DATA_from_DB($row[0]);
	 
// Header	 
   //echo '<DIV class="survey">';
	 $title = $Q->render_TITLE();
	 
	 // Question
	 $ques = $Q->render_QUESTION_check();
	 $Q->get_ANSWERS_data_from_DB();
	 //$Q->get_ANSWERS_solution_from_DB();
	 $ques1 = $Q->render_ANSWERS('hel');
	 
	 //var_dump($ques);
	 //die();
	 $aa = "<b>AA</b> in the<p>and then some</p>";
	 $note = '<span class="footer" onmouseover="footer(this,\''.$ques1.'\',1)" onmouseout="footer(this,\'\',0)">'.$title.'</span>';
   echo $note;
}
*/
//----- DISPLAY -----
/*
// Question List
$list = '';
$check = '';
$TB = '';
while (($row = $res->fetchRow())) {
		//$list = $list.'<a href="index.php">'.$row[0].'</a><br>';
		//$check = '<input type=checkbox onclick=fcn() name=editMode><p>';
    //$edit  = '<input type=button onclick=ITS_QCONTROL_EDITMODE() name=editMode value= Edit >';

   
	 $Q = new ITS_question(1,$db_name,$tb_name);
	 $Q->load_DATA_from_DB($row[0]);
	 
// Header	 
   echo '<DIV class="survey">';
	 $title = $Q->render_TITLE();
	 $tb_hdr = new ITS_table('survey',1,2,array($row[0],$title),array(10,90),'ITS_QUESTION');
	 echo '<span style="color:red">'. $tb_hdr->str .'</span><hr>';
	 
	 // Question
	 $ques = '';
	 $ques = $ques . $Q->render_QUESTION_check();
	 $Q->get_ANSWERS_data_from_DB();
	 //$Q->get_ANSWERS_solution_from_DB();
	 $ques = $ques . $Q->render_ANSWERS('hel');
	 echo $ques.'</DIV><p>';
//$tb = new ITS_table('survey',1,2,array($ques,$check),array(80,20),'ITS_QUESTION');
//echo $tb->str;
}

//----- DISPLAY -----
/*
// Question List
$list = '';
$check = '';
while (($row = $res->fetchRow())) {
		$list = $list.'<a href="index.php">'.$row[0].'</a><br>';
		$check = $check.'<input type=checkbox onclick=fcn() name=editMode><br>';
}
// Question
   $ques = ''; //"<p><center><DIV class=main>";
	 $Q = new ITS_question(1,$db_name,$tb_name);
	 $Q->load_DATA_from_DB(58);
	 $ques = $ques . $Q->render_TITLE();
	 $ques = $ques . $Q->render_QUESTION_check();
	 $Q->get_ANSWERS_data_from_DB();
	 //$Q->get_ANSWERS_solution_from_DB();
	 $ques = $ques . $Q->render_ANSWERS('hel');
	 
	 $ques = $ques
	         ."<p>"
	         ."<input type=button onclick=ITS_QCONTROL_EDITMODE() name=editMode value= Edit >";
					 //."</DIV></center>";
$tb = new ITS_table('survey',1,3,array($list,$check,$ques),array(40,3,57));
echo $tb->str;
*/
//------------------

/*
// Print the DataGrid with the default renderer (HTML Table)
$datagrid->setRenderer('HTMLTable');
$test = $datagrid->render();

// Print rendering error if any
if (PEAR::isError($test)) {
    echo $test->getMessage();
}
//----------------------------
*/

/*
// 1. New question
if ( !isset($_SESSION['question_control']) ){
	$_SESSION['question_control'] = new Question_Control( $_SESSION['user']->id() );
}

// 3. Score submitted question (Grading.php)
if ( isset($_POST['score_question']) ){
  if (empty($_POST['answer_radio'])) {
     echo "<span class=TextHighlight>Please select an answer.</span>";
  }
  else {
	   $score = $_SESSION['question_control']->check_answer($_POST['answer_radio']);
  }
}
// 2. Display question. 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<title>Practice</title>
<link rel="stylesheet" href="admin/pagestyles.css">
</head>
<body>
<form method="post" action="practice.php">
<?php
//if (isset($score)){
//	echo "<h5>You scored $score points on the submitted problem.</h5>";
//}
//var_dump($_SESSION['question_control']);
//die();
if ( isset($_SESSION['question_control']) ){
  $_SESSION['question_control']->show_question(); 
}
*/

//--------------------------------------------------------------------//
// LAB PARAMETERS
//--------------------------------------------------------------------//
$user_info = array($db_dsn,$db_name,'webct',$_SESSION['user']->id());
$lab_title = 'Exercise #1';
$lab_intro = '';
 
$mode = 'survey'; 
$lab_num = 1;
$lab_sec = 1;
$lab_sec_headers = array('');

$lab_sec_parts_headers = array();
$lab_sec_parts = array(2);
$lab_sec_ques = array(array(1,1));
$lab_sec_ans_rows = array(array(1,1));
$survey_questions = array(1000,1097);  // 1095

$lab_coda = '';

$lab_info  = array($lab_title,$lab_intro,$lab_num,$lab_sec,$lab_sec_headers,
						       $lab_sec_parts_headers,$lab_sec_parts,$lab_sec_ques,
									 $lab_sec_ans_rows,$lab_coda,$mode);

//--------------------------------------------------------------------//
// CREATE LAB
//--------------------------------------------------------------------//		
// 1. Set up new lab													 													 
$new_lab = new ITS_survey($user_info,$lab_info,$survey_questions);

// 2. Check if lab has been taken
$new_lab->lab_check();

// 3. If lab submitted then - record answers
//                     otherwise - render lab

if (!($new_lab->_lab_completed)) { // Lab not taken: render lab

 
	if (isset($_POST['submit'])) { // if submitted by student
	  $new_lab->record_lab();
		if (isset($_POST['submit'])) { // record answers
		  $new_lab->record_lab_answers($_POST[$new_lab->_lab_name],$db_table_user_state);
		}
		
	}
	else { // render lab if has not been yet submitted
	
	  $new_lab->render_lab();
	}
}
//--------------------------------------------------------------------//
?>
<!---
<h3>Your answer:</h3><br />
<?php //$_SESSION['question_control']- >show_answer(); ?>
<br /><hr><br />
<input type="submit" name="submit_answer" value="Submit-now" />
-->
</form>
</div>
</body>
</html>
