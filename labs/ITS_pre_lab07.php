<?php
//error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");
include "classes/ITS_survey.php";
require_once("MDB2.php");

session_start();
abort_if_unauthenticated();
//--------------------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title>Lab #7</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="css/pagestyles.css" type="text/css">
<link rel="stylesheet" href="css/warmup.css" type="text/css" />
<script src="js/ITS_admin.js"></script>
<script src="js/ITS.js"></script>
<script src="js/ITS_http.js"></script>
<script src="js/prototype.js"></script>
<script src="js/ITS_lab.js"></script>
</head>
<body class="ITS">
<?php include("menu2.php");?>
<div class="main">
<?php
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

//-- SURVEY QUESTION LIST --
$res =& $mdb2->query("SELECT id FROM webct WHERE category='Survey'");
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

// Question List
$list  = '';
$check = '';
$TB    = '';
//--------------------------------------------------------------------//
// LAB PARAMETERS
//--------------------------------------------------------------------//
$user_info = array($db_dsn,$db_name,'webct',$_SESSION['user']->id());
$lab_num   = 7;
$lab_title = 'Lab #'.$lab_num.': FIR Filtering of Digital Images';
$lab_intro = '';
 
$mode    = 'survey'; 
$lab_sec = 1;
$lab_sec_headers = array('');

$lab_sec_parts_headers = array('(a) ','(b) ','(c) ','(d) ','(e) ');
$lab_sec_parts = array(2);
$lab_sec_ques = array(array(1,1));
$lab_sec_ans_rows = array(array(1,1));

// randomy pick one
$q1 = array(1208,1209);
$q1 = $q1[rand(0,1)];
$survey_questions = array($q1,1210);
//$survey_questions = array(2034,2035,2036,2037,2038,2039);

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
</form>
</div>
</body>
</html>
