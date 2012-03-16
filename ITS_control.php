<?php
/*
ITS_control - script for AJAX question control objects: CANCEL | SAVE
			  when in 'Edit' mode, called from ITS_QControl.js

Author(s): Greg Krudysz
Last Update: Feb-19-2012
---------------------------------------------------------------------*/
require_once("config.php");
require_once($MDB2_path.'MDB2.php');
require_once("classes/ITS_tag.php");
require_once("classes/ITS_search.php");
require_once("classes/ITS_table.php");
require_once("classes/ITS_configure.php");
require_once("classes/ITS_question.php");

session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state;

//-- Get AJAX arguments
$args    = split('[,]',$_GET['ajax_args']);
$qid     = $args[0];
$Control = $args[1];
$Target  = $args[2]; // target = {TITLE|QUESTION|IMAGE|...}

//-- Get AJAX user data
$Data = rawurldecode($_GET['ajax_data']);
// preprocess before SQL
$Data = str_replace ("'","&#39;",$Data);
//$Data = nl2br($Data);

//echo '<span style="border:2px solid yellow">'.strftime('%H:%M:%S').'</span>';

//-- Connect to DB
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}	
$Q = new ITS_question(1,$db_name,$tb_name);
$Q->load_DATA_from_DB($qid);

//die($Control);
// JS: encodeURIComponent() -> PHP: rawurldecode()
// PHP: rawurlencode() -> JS: decodeURIComponent()
switch ($Control){
		//-------------------------------------------//
	    case 'PREV':
		case 'NEXT':
		case 'TEXT':
	    //-------------------------------------------//		
	    //die('xxx');
		$adminNav = $Q->render_Admin_Nav($qid,$Q->Q_type,'ITS_button');
		
	    $nav = $adminNav
		  .'<div id="importQuestionContainer">'
      	  .'<form id="QTI2form" action="upload_QTIfile.php" enctype="multipart/form-data" method="post">'
          .'<table><tr>'
          .'<td><label for="files">QTI file</label></td>'
          .'<td><input type="file" name="file" id="file"></td>'
          .'<td><input id="file_upload" name="file_upload" type="file"></td>'
          .'<td><input type="submit" name="submit" value="Submit" id="QTIsubmit"></td>'
      	  .'</tr></table></form></div>'
	      .$Q->render_QUESTION().'<p>';
  
	    $Q->get_ANSWERS_data_from_DB();
	      
	      //$Q->get_ANSWERS_solution_from_DB();
	      echo $nav.$Q->render_ANSWERS('a',2).$Q->render_data();
		  break;
		//-------------------------------------------//
	  case 'CANCEL':
	  //-------------------------------------------//
		//-- evaluate corresponding method based on target={TITLE|QUESTION|IMAGE|...}
		$field = strtolower(str_replace("ITS_","",$Target));
		
		switch ($field):
        case 'title':
    		case 'question':
    		case 'image':
			case 'category':
				  $str = "echo \$Q->Q_".$field.";";
				  $str = latexCheck2($str,$Q->mimetex_path);
			      eval($str);
            break;
        default:
            $query = "SELECT ".$field." FROM ".$tb_name."_".$Q->Q_type." WHERE id=".$qid.";";
            $res =& $mdb2->query($query);          
            if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
            
            $row = $res->fetchRow();
            $answer = $row[0];
            echo $answer;
		endswitch;
	
		 break;
	  //-------------------------------------------//
	  case 'SAVE':
	  //-------------------------------------------//
		// DEBUG: var_dump($Data);//die();
		$field = strtolower(str_replace("ITS_","",$Target));
		//echo 'DEBUG: '.$Data; die();
		
		switch ($field):
    case 'title':
		case 'question':
		case 'image':
		case 'answers':
		case 'category':
        $query = "UPDATE ".$tb_name." SET ".$field."='".addslashes($Data)."' WHERE id=".$qid;
        break;
    default:
        $query = "UPDATE ".$tb_name."_".$Q->Q_type." SET ".$field."='".addslashes($Data)."' WHERE id=".$qid;
		endswitch;

		//echo $query;  die();
		$res =& $mdb2->query($query);
 		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

		// Pre-process string for output:
		$str = $Q->renderFieldCheck($Data);
		echo $str;
		break;
		//-------------------------------------------//
}
//=====================================================================//
	function latexCheck2($str,$path) {
//=====================================================================//
	 $pattern = "/<latex>(.*?)<\/latex>/i";
   if (preg_match($pattern, $str, $matches)) {
				$replacement = '<img latex="'.$matches[1].'" src="'.$path.$matches[1].'"/>';
    		$str = preg_replace($pattern, $replacement, $str);	
   }
	 return $str;	
}
//=====================================================================//
?>
