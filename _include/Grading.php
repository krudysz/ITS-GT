<?php
//error_reporting(E_ALL);
require_once("config.php");
require_once("MDB2.php");
require_once(INCLUDE_DIR . "User.php");

define('GRADING_PARTIAL_CREDIT',1);

class Grading_Exception extends Exception {}
class Grading
{
//==============================================================================
public static function grade($id,$question_data,$ans,$algorithm)
//==============================================================================
{
	global $db_dsn,$db_table_question,$db_table_user_state;
	
	$mdb2 =& MDB2::connect($db_dsn);
	if (PEAR::isError($mdb2)){throw new Grading_Exception($mdb2->getMessage());}
	
	//$res =& $mdb2->query("SELECT user_answer FROM user_state WHERE user_id=2");
	//if (PEAR::isError($res)) {throw new Grading_Exception($res->getMessage());}

	$format = "";
	$solution = "";
	$user_answer = "";

/*
	if ($res1->numRows() == 0){throw new Grading_Exception("Error: question_id: $question_id not found. Abort.");}
	else{
		$row1 = $res1->fetchRow(MDB2_FETCHMODE_ASSOC);
		$user_answer = $row1['user_answer'];
	}
*/	
	
	$solution = $question_data['solution'];
	$score=0;
	$parsed_ans;
	switch ($question_data['format']){
		case 'MC':		// mutiple choice
			$parsed_ans = $ans[0];
			if ($parsed_ans == $solution){
				$score = 1;
			}
			else{
				$score = 0;
			}
			//echo "<h5>You scored $score points on the submitted problem.</h5>";
			break;
		case 'LV': // labview
		$parsed_ans = $user_answer;
		   if ($user_answer == $solution){
 			   $score = 1;
				 }
				 else{
				 $score = 0;
				 }
				 //echo "<h5>You scored $score points on the submitted problem.</h5>";
				 //(_POST['ans']) = $score;
		break;
		default:
			throw new Grading_Exception("Unknown question type. question id: $question_id");
			break;
	}
	
	$res =& $mdb2->query("UPDATE $db_table_user_state{$_SESSION['user']->id()} SET answered='$parsed_ans',score=$score WHERE id=$id");
	if (PEAR::isError($res)) {throw new Grading_Exception($res->getMessage());}

	$mdb2->disconnect();

	return $score;
}
//==============================================================================
}
?>

