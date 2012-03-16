<?php
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");
include "classes/ITS_survey.php";
require_once($MDB2_path.'MDB2.php');

session_start();
abort_if_unauthenticated();
//------------------------------------------//
// obtain lab # from link (ITS_statistics.php generates them)
$lab_num = $_GET['activity'];
$mode    = 'survey';

//--------------------------------------------------------------------//
// LAB PARAMETERS
//--------------------------------------------------------------------//
$user_info = array($db_dsn,$db_name,'webct',$_SESSION['user']->id());
$lab_title = 'Lab #'.$lab_num;
$lab_intro = '';

switch ($lab_num) {
	//-------------------------------
   case 1:
  //-------------------------------
     $survey_questions = array(1000,1097);   
	   break;
	//-------------------------------
   case 2:
  //-------------------------------
     $survey_questions = array(1190,1195);   
	   break;
	//-------------------------------
   case 3:
  //-------------------------------
     $survey_questions = array(1196,1197,1198);   
	   break;
	//-------------------------------
   case 4:
  //-------------------------------
     $survey_questions = array(1199,1200);   
	   break;		 		 
	//-------------------------------
   case 5:
  //-------------------------------
     $survey_questions = array(1201,1202);   
	   break;
	//-------------------------------
   case 6:
  //-------------------------------
	   $lab_title = $lab_title.': A/D and D/A Conversion';
		 $lab_intro = $lab_intro.'<div style="margin:15px;">
<b>Sampling and Aliasing</b></p>Use the <tt>con2dis</tt>
GUI to do the following exercises. The parameters of the <i>continuous-time input</i> signal are
its frequency <tt>&fnof;<sub>0</sub></tt> in Hz, and its phase <tt>&phi;</tt> in rads; 
the amplitude of <tt>x(t)</tt> is always one.  The sampling rate of the A/D 
converter and the D/A converter are identical: <tt>&fnof;<sub>s</sub></tt> in samples/sec 
(or hertz).</div><p><div class="lab_note" style="margin:15px;padding:10px">In all 
cases, write a concise explanation of your answer. &ldquo;Trial and error&rdquo; 
is not a legitimate justification, so try to write something based on the 
theory you have learned.</div>';
     $survey_questions = array(1203,1204,1205,1206,1207);   
	   break;		 
	//-------------------------------
   case 7:
  //-------------------------------
	   $lab_title = $lab_title.': FIR Filtering of Digital Images';
     $q1 = array(1208,1209);
     $q1 = $q1[rand(0,1)];
     $survey_questions = array($q1,1210);   
	   break;
	//-------------------------------
   case 8:
  //-------------------------------
		 $lab_intro = $lab_intro.'<div class="lab_note"><font color="royalblue">Instruction:</font> For parts 2 and 3, indicate your answers in the right-hand table by writing a corresponding letter from the left-hand table, as shown in the example below:</font><p><center><img class="ITS" src="images/matching_example.png" style="position:relative;width:100%;border: 3px solid silver;"></center><p></div>';															 
     $survey_questions = array(19,1211,1212);   
	   break;
	//-------------------------------
   case 9:
  //-------------------------------
     // randomy pick one
		 $q1 = array(77,93);
		 $q1 = $q1[rand(0,1)];
		 $survey_questions = array($q1,1214);   
	   break;
	//-------------------------------
   case 10:
  //-------------------------------
		 $survey_questions = array(1215,1216);   
	   break;    		         
	//-------------------------------
   case 11:
  //-------------------------------
		 $survey_questions = array(1217,1218);   
	   break;   	
	//-------------------------------
   case 12:
  //-------------------------------
		 $survey_questions = array(1219,1220);   
	   break;   
	//-------------------------------
   case 13:
  //-------------------------------
	   $lab_title = 'ECE 2025 Survey';
		 $survey_questions = array(742,730,538,261,799,1078,400,2117,365,235,1221,520,399,422);
	 //$survey_questions = array(742,730,538,1059,261,799,1078,400,2117,58,393,365,262,235,520,399,422);   
	   break;	 	 
	//-------------------------------
   default:
		  $score = NULL;
	//-------------------------------
}
$lab_sec = 1;
$lab_sec_headers = array('I','II','III');

$lab_sec_parts_headers = array('(a) ','(b) ','(c) ','(d) ','(e) ');
$lab_sec_parts = array(count($survey_questions));

$lab_sec_ques = array(array_fill(0,count($survey_questions),1));
$lab_sec_ans_rows = array(array_fill(0,count($survey_questions),1));

$lab_coda = '';
$lab_info  = array($lab_title,$lab_intro,$lab_num,$lab_sec,$lab_sec_headers,
						       $lab_sec_parts_headers,$lab_sec_parts,$lab_sec_ques,
									 $lab_sec_ans_rows,$lab_coda,$mode);
//--------------------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title>Lab #<?php echo $lab_num;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="css/main.css" type="text/css">
<link rel="stylesheet" href="css/pagestyles.css" type="text/css">
<link rel="stylesheet" href="css/warmup.css" type="text/css" />
<link rel="stylesheet" href="css/question.css" type="text/css" />
<script src="js/ITS_admin.js"></script>
<script src="js/ITS.js"></script>
<script src="js/ITS_http.js"></script>
<script src="js/prototype.js"></script>
<script src="js/ITS_lab.js"></script>
</head>
<body class="ITS">
<?php 
include("menu2.php");
?>
<div class="main">
<?php
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

//--------------------------------------------------------------------//
// CREATE LAB
//--------------------------------------------------------------------//		
if (isset($_SESSION[$lab_title])){ 
	 $session_lab = $_SESSION[$lab_title];
	 //var_dump($session_lab);
 
 	 // 2. Check if lab has been taken
	 $session_lab->lab_check();

	 // 3. If lab submitted then - record answers
	 //                     otherwise - render lab
	 if (!($session_lab->_lab_completed)) { // Lab not taken: render lab
	 
	 		if (isset($_POST['submit'])) {   // if submitted by student
			   echo 'record_lab';
	    	 $session_lab->record_lab();
				 if (isset($_POST['submit'])) { // record answers
		  	 		$session_lab->record_lab_answers($_POST[$session_lab->_lab_name],$db_table_user_state);
		  	 }
	 		} else { // render lab if has not been yet submitted
	    	$session_lab->render_lab();
	 		}
	 }else{ echo "Lab already taken";}
} else { // no session lab found -> new_lab
	// 1. Set up new lab												 													 
	$new_lab = new ITS_survey($user_info,$lab_info,$survey_questions);
	$new_lab->render_lab();
	
	$_SESSION[$lab_title] = $new_lab;
}
//--------------------------------------------------------------------//
?>
</form>
</div>
</body>
</html>
