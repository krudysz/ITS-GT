<?php
/*
ITS_control - script for AJAX question control objects: CANCEL | SAVE
						  when in 'Edit' mode, called from ITS_QControl.js

Author(s): Greg Krudysz
Date: Jan-18-2011
---------------------------------------------------------------------*/
require_once("config.php");
require_once($MDB2_path.'MDB2.php');
include("classes/ITS_table.php");
include("classes/ITS_configure.php");
include("classes/ITS_question.php");

session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state;

//-- Get AJAX arguments
$args = split('[,]',$_GET['ajax_args']);
$qNum    = $args[0];
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
/*		
style="position:absolute;top:0;right:0;z-index:3"
*/		
$style = '<head>'
	.'<link href="css/ITS_QTI.css" rel="stylesheet" type="text/css" />'
	.'<link href="css/swfupload.css" rel="stylesheet" type="text/css" />'
  .'<script type="text/javascript" src="swfupload/swfupload.js"></script>'
	.'<script type="text/javascript" src="uploadify/jquery.uploadify.v2.1.4.min.js"></script>'
	.'<link href="uploadify/uploadify.css" type="text/css" rel="stylesheet" />'
  .'<script type="text/javascript" src="uploadify/swfobject.js"></script>'
	.'</head>';
	
	  $nav = '<input type="button" class="ITS_button" id="createQuestion" name="new" value="New" qid="'.$qNum.'">'
          .'<input type="button" class="ITS_button" id="cloneQuestion" name="clone" value="Clone" qid="'.$qNum.'">'
					.'<input type="button" class="ITS_button" id="importQuestion" name="import" value="import QTI" qid="'.$qNum.'">'
					.'<input type="button" class="ITS_button" onclick="ITS_QCONTROL_EDITMODE(this)" name="editMode" value="Edit" status="true">'
          .'<input type="button" class="ITS_button" id="deleteQuestion" name="delete" value="Delete" qid="'.$qNum.'">'
					.'<div id="importQuestionContainer">'
      		.'<form id="QTI2form" action="upload_QTIfile.php" enctype="multipart/form-data" method="post">'
          .'<table><tr>'
          .'<td><label for="files">QTI file</label></td>'
          .'<td><input type="file" name="file" id="file"></td>'
          .'<td><input id="file_upload" name="file_upload" type="file"></td>'
          .'<td><input type="submit" name="submit" value="Submit" id="QTIsubmit"></td>'
      		.'</tr></table></form></div>'
					.$Q->render_TITLE()
	        .$Q->render_QUESTION()."<p>";
	    $Q->get_ANSWERS_data_from_DB();
	    //$Q->get_ANSWERS_solution_from_DB();
	    echo $style.$nav.$Q->render_ANSWERS('a',2);
			
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
		// DEBUG: var_dump($Data);//die();
		$field = strtolower(str_replace("ITS_","",$Target));
		//echo $field; die();
		
		//NABANITA START
		if ((strcmp($field,"title")==0) || (strcmp($field,"question")==0)){
		   $query="UPDATE ".$tb_name." SET ".$field."='".addslashes($Data)."' WHERE id=".$qNum;
		}else{
		   $query="UPDATE ".$tb_name."_".$Q->Q_type." SET ".$field."='".addslashes($Data)."' WHERE id=".$qNum;
		}
		//NABANITA END
		
		//echo $query;  die();
		$res =& $mdb2->query($query);
 		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

		echo $Data;
		break;
		//-------------------------------------------//
}
?>
