<?php
/*
ITS_control - script for AJAX question control objects: CANCEL | SAVE
						  when in 'Edit' mode, called from ITS_QControl.js

Author(s): Greg Krudysz
Date: Jan-29-2010
---------------------------------------------------------------------*/
require_once("config.php");
require_once($MDB2_path.'MDB2.php');
include("classes/ITS_table.php");
include("classes/ITS_configure.php");
include("classes/ITS_question.php");
include("classes/ITS_statistics.php");
include("classes/ITS_screen2.php");

session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state;

//---------------------------------------//
// POST METHODS
//---------------------------------------//
if (isset($_POST['XXX'])){
  $screen = $_SESSION['screen'];
  //$str = $screen->recordQuestion($_POST['XXX']);
  //echo $str;
}
//---------------------------------------//
// AJAX
//---------------------------------------//
else{
//-- Get AJAX arguments
$args = preg_split('[,]',$_GET['ajax_args']);

//-- Get AJAX user data
$Data = rawurldecode($_GET['ajax_data']);
// preprocess before SQL
$Data = str_replace ("'","&#39;",$Data);
//$Data = nl2br($Data);

$screen = $_SESSION['screen'];
$action = $args[0];

/*
echo 'action = '.$action.'<p>';
echo 'data   = '.$Data.'<p>';    die();
*/

$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
//-----------------------------------------------//
switch ($action){
    //-------------------------------------------//
	  case 'message':
	  //-------------------------------------------//
		  $str = $Data;
			break;
		//-------------------------------------------//
	  case 'updateHeader':
	  //-------------------------------------------//
		  //echo $Data;die();
			$header_str    = $screen->getHeader($Data,1);
			$str = $header_str;
		  break;
		//-------------------------------------------//
	  case 'getContent':
	  //-------------------------------------------//
		//die();
		  $str = $screen->getContent();
		  break;
		 //-------------------------------------------//
	  case 'showExercises':
	  //-------------------------------------------//
		  $str = $screen->exercisesContent();
		  break;
		//-------------------------------------------//
	  case 'getIndex':
	  //-------------------------------------------//
		  $str = $screen->getChapter('index',$Data);
		  break;
		//-------------------------------------------//
	  case 'getChapter':
	  //-------------------------------------------//
		  $str = $screen->getChapter('chapter',$Data);
		  break;
		//-------------------------------------------//
	  case 'updateLab':
	  //-------------------------------------------//
			$prop = preg_split('[,]',$Data);
			//eval('$screen->lab_'.$prop[0].' = '.$prop[1]);
			switch ($prop[0]){
	  	  case 'index':  $screen->lab_index  = $prop[1]; break;
				case 'active': $screen->lab_active = $prop[1]; break;
			}
			if ($screen->lab_active == 'Survey') { $screen->lab_active = 14; }
			//echo $screen->lab_active.' @ '.$screen->lab_index.'<p>';//die();
			
		  $str = $screen->updateLab($screen->lab_active,$screen->lab_index);
		  break;
		//-------------------------------------------//
	  case 'updateConcept':
	  //-------------------------------------------//
		  $prop = preg_split('[,]',$Data);
			switch ($prop[0]){
				case 'active': $screen->chapter_active = $prop[1]; break;
				case 'alpha':  $screen->chapter_alpha  = $prop[1]; break;
			}
		  $str = $screen->updateConcept($screen->chapter_active);
		  break;
		//-------------------------------------------//
	  case 'recordAnswer':
	  //-------------------------------------------//
		  //var_dump($Data);die('recordAnswer');
		  $data = preg_split('[~]',$Data);
			//echo $data[0].' -- '.$data[1].' -- '.$data[2].'<p>';
			$screen->recordQuestion($data[0],$data[1],$data[2]);
			//$screen->lab_index = $screen->lab_index + 1;
			$str = $screen->getContent();
		  break;	
		//-------------------------------------------//
	  case 'getAnswer':
	  //-------------------------------------------//
		  //var_dump($Data);die('getAnswer');
		  $data = preg_split('[,]',$Data);
		  $answer_str = $screen->getAnswer($data[0],$data[1]);
			$screen->recordQuestion($data[0],$data[1]);

			$str = $answer_str;
		  break;	
		//-------------------------------------------//
	  case 'answerLab':
	  //-------------------------------------------//
		$str = 'NULL';
		//die($screen->id);
		/*
			$prop = preg_split('[,]',$Data);
			//eval('$screen->lab_'.$prop[0].' = '.$prop[1]);
			switch ($prop[0]){
	  	  case 'index':  $screen->lab_index  = $prop[1]; break;
				case 'active': $screen->lab_active = $prop[1]; break;
			}
			*/
			
		  //$str = $screen->updateLab($screen->lab_active,$screen->lab_index);
		  break;
		//-------------------------------------------//
	  case 'getScreen':
	  //-------------------------------------------//
		  $screen_str = $screen->getScreen($Data);
		  break;	
		//-------------------------------------------//
	  case 'showFigures':
	  //-------------------------------------------//	
      $str = $screen->showFigures($Data);
		  break;		
		//-------------------------------------------//
	  case 'showPage':
	  //-------------------------------------------//	
      $str = $screen->showPage($Data);
		  break;		
		//-------------------------------------------//
		 case 'newChapter':
	  //-------------------------------------------//	
		  $str = $screen->newChapter($Data);
		  break;	
		//-------------------------------------------//			
}
//-----------------------------------------------//
echo $str;
}
/*----------------------------------------------------------------------------*/
//echo '<span style="border:2px solid yellow">'.strftime('%H:%M:%S').'</span>';
/*
//-- Connect to DB
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){
			throw new Question_Control_Exception($mdb2->getMessage());
}	

$Q = new ITS_question(1,$db_name,$tb_name);
$Q->load_DATA_from_DB($qNum);

//die($Control);
// JS: encodeURIComponent() -> PHP: rawurldecode()
// PHP: rawurlencode() -> JS: decodeURIComponent()
switch ($Control){
		//-------------------------------------------//
	  case 'PREV':
		case 'NEXT':
		case 'TEXT':
	  //-------------------------------------------//
	    echo $Q->render_TITLE();
	    echo $Q->render_QUESTION_check()."<p>";
	    $Q->get_ANSWERS_data_from_DB();
	    //$Q->get_ANSWERS_solution_from_DB();
	    echo $Q->render_ANSWERS('a');
			
		  break;
		//-------------------------------------------//
	  case 'CANCEL':
	  //-------------------------------------------//
		//-- evaluate corresponding method based on target={TITLE|QUESTION|IMAGE|...}
		$field = strtolower(str_replace("ITS_","",$Target));
		
		if ((strcmp($field,"title")==0) || (strcmp($field,"question")==0)){
			 $str = "echo \$Q->Q_".$field.";";
			 eval($str);
		}else{
			 $query = "SELECT ".$field." from ".$tb_name."_".$Q->Q_type." WHERE id=".$qNum.";";
			 $res =& $mdb2->query($query);
	 
	 		 if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
			 
			 $row = $res->fetchRow();
    	 $answer = $row[0];
			 echo $answer;
		}
		 break;
	  //-------------------------------------------//
	  case 'SAVE':
	  //-------------------------------------------//
		//var_dump($Data);
		//die();
		$field = strtolower(str_replace("ITS_","",$Target));
		//echo $field; die();
		
		if ((strcmp($field,"title")==0) || (strcmp($field,"question")==0)){
		   $query="UPDATE ".$tb_name." SET ".$field."='".$Data."' WHERE id=".$qNum;
		}else{
		   $query="UPDATE ".$tb_name."_".$Q->Q_type." SET ".$field."='".$Data."' WHERE id=".$qNum;
		}
		//echo $query;  die();
		$res =& $mdb2->query($query);
 		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

		echo $Data;
		break;
		//-------------------------------------------//
}
*/
?>

