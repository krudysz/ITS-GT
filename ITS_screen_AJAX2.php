<?php
/*
ITS_screen_AJAX2 - script for AJAX question control objects: CANCEL | SAVE
						  when in 'Edit' mode, called from ITS_QControl.js

Author(s): Greg Krudysz
Date: May-10-2011
---------------------------------------------------------------------*/

header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); // Must do cache-control headers 
header("Pragma: no-cache");

require_once("config.php");
require_once (INCLUDE_DIR . "common.php");
require_once($MDB2_path.'MDB2.php');
include("classes/ITS_screen2.php");
include("classes/ITS_computeScores.php");

/*
$style = '<head>'
.'<script type="text/javascript" src="MathJax/MathJax.js"></script>'
.'<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />'
.'</head>';
*/
$style = '';
session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state;

//---------------------------------------//
// AJAX
//---------------------------------------//
$args = preg_split('[,]',$_GET['ajax_args']); //-- Get AJAX arguments
$Data = rawurldecode($_GET['ajax_data']);     //-- Get AJAX user data
$Data = str_replace ("'","&#39;",$Data);      // preprocess before SQL
//$Data = nl2br($Data);

/*
$action = 'recordChapterAnswer'; //$args[0];
$Data   = '819~M~4,2,5,,,';
*/

// return to login page if not logged in
if (empty($_SESSION['screen'])) { abort_if_unauthenticated();    }
else 														{ $screen = $_SESSION['screen']; }
//print_r($screen); //die();

$action = $args[0];
if (empty($_SESSION['score']))  { $score = 0; }
else 						    { $score = $_SESSION['score']; }
//echo 'ITS_screen_AJAX2: '.$screen->chapter_number.'<p>';
//print_r($score);

/*
echo 'action = '.$action.'<p>';
echo 'data   = '.$Data.'<p>';    //die();
*/

$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

//die($action);
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
		  $header_str = $screen->getHeader($Data,1);
		  $str = $header_str;
		  break;
	  //-------------------------------------------//
	  case 'getContent':
	  //-------------------------------------------//
		  $data = preg_split('[,]',$Data);  // data = [mode]
		  //echo $data[0]; die();
		  $screen->mode = $data[0];
		  $str = $screen->getContent();
		  break;
	 //-------------------------------------------//
	  case 'surveyMode':
	  //-------------------------------------------//
		  $data = preg_split('[,]',$Data);  // data = [ch,offset]
		  $screen->mode = 'question';
		  //$screen->chapter_number = $data[0];
		  $str = $screen->surveyMode($screen->chapter_number,$data[1]);
		  break;		
	  //-------------------------------------------//
	  case 'practiceMode':
	  //-------------------------------------------//
		  $data = preg_split('[,]',$Data);
		  $screen->chapter_number = $data[0];
		  $str = $screen->getChapter('practice',$Data);
		  break;				
	  //-------------------------------------------//
	  case 'reviewMode':
	  //-------------------------------------------//
		  $data = preg_split('[,]',$Data);  // data = [ch,offset]
			//echo $Data.'<p>'; die();
			//echo $data[0].' @ '.$data[1].'<p>'; die();
		  $screen->chapter_number = $data[0];
			//if (!isset($data[1])) { $data[1] = 0; }
		  $str = $screen->reviewMode($screen->chapter_number,$data[1]);
		  break;
	  //-------------------------------------------//
	  case 'reviewUpdate':
	  //-------------------------------------------//
		  $data = preg_split('[,]',$Data);  // data = [ch,offset]
			//echo $Data.'<p>'; die();
			//echo $data[0].' @ '.$data[1].'<p>'; die();
		  $screen->chapter_number = $data[0];
		  $str = $screen->reviewUpdate($screen->chapter_number,$data[1]);
		  
			//if (!isset($data[1])) { $data[1] = 0; }
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
	  case 'getSurvey':
	  //-------------------------------------------//
			//$prop = preg_split('[,]',$Data);
			//if ($screen->lab_active == 'Survey') { $screen->lab_active = 14; }
			//echo $screen->lab_active.' @ '.$screen->lab_index.'<p>';//die();
			
		  //$str = $screen->updateLab(14,14);
			$str = $screen->getChapter('survey',1);
			//$str = 'hello';
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
		  //var_dump($Data);//die();
		  $data = preg_split('[~]',$Data);
			//echo $data[0].' -- '.$data[1].' -- '.$data[2].'<p>';
			$screen->recordQuestion($data[0],$data[1],$data[2]);
			//$screen->lab_index = $screen->lab_index + 1;
			//$str = $screen->getContent();
			$str = '';
		  break;		
		//-------------------------------------------//
	  case 'recordChapterAnswer':
	  //-------------------------------------------//
		    //echo '<pre style="color:blue">';var_dump($Data); echo '</pre>';//
			//die('ITS_screen_AJAX2.php: recordChapterAnswer');
		    
		    $data = preg_split('[~]',$Data);
			$screen->chapter_number = $data[4];

			$screen->mode = $data[6]; // question | practice | survey
			//echo 'recordChapterAnswer: '.$screen->chapter_number.' | java ch: '.$data[4].'<p>';
			//echo 'recordChapterAnswer: '.$data[0].' -- '.$data[1].' -- '.$data[2].' -- '.$data[3].' -- '.$data[4].' -- '.$data[6].'<p>';
			$screen->recordQuestion($data[0],$data[1],$data[2],explode(',',$data[3]),$data[5]); //$screen->question_info);
			//$str = $screen->getContent();
			$screen->question_completed = true;
			//echo '<font color="blue">$screen->getQuestion()</font><p>';
			$ques = $screen->getQuestion($data[0],$data[3]); // (qid,conf)
			$ans  = $screen->getAnswer($data[0],$data[1],$data[2],$data[3]);
			$nav  = $screen->getNavigation($ans,$data[0]);
			
			$str  = $ques.$nav;
			
		  break;
		//-------------------------------------------//
	  case 'recordSurveyAnswer':
	  //-------------------------------------------//
		    //var_dump($Data);//die();
		    $data = preg_split('[~]',$Data);
			$screen->chapter_number = $data[4];
			$screen->mode           = $data[6];
			$screen->recordQuestion($data[0],$data[1],$data[2],explode(',',$data[3]),$data[5]); //$screen->question_info);
			$screen->question_completed = true;
			$str = $screen->getChapter('survey',1);
		  break;			
		//-------------------------------------------//
	  case 'recordRating':
	  //-------------------------------------------//
	  	$data = preg_split('[~]',$Data);
			$screen->recordRating($data[0],$data[1]);
			//$str = $data;
	  	break;
		  //-------------------------------------------//
	  case 'updateScores':
	  //-------------------------------------------//
	  	//$data = preg_split('[~]',$Data);
			//var_dump($score->chapterArray); //die();
			$str = $score->renderChapterScores();
	  	break;
		//-------------------------------------------//
	  case 'getAnswer':
	  //-------------------------------------------//
		  //var_dump($Data);die();
		  $data = preg_split('[,]',$Data);
		  $answer_str = $screen->getAnswer($data[0],$data[1]);
			//$screen->recordQuestion($data[0],$data[1]);
          			//$ans = $this->getUserAnswer($qid,$qtype,$answered);
            		//$navigation_str = $this->getNavigation($ans);
			$str = 'this';//$answer_str;
		  break;	
		//-------------------------------------------//
	  case 'showAnswer':
	  //-------------------------------------------//	
		  $data = preg_split('[,]',$Data);
			//getAnswer($qid,$qtype,$answered);
		  $answer_str = $screen->getAnswer($data[0],$data[1],$data[2]);
			$str = '<div class="navContainer">'.
					    $answer_str.'<input type="submit" class="ITS_submit" name="next" value="&raquo;">'.
			       '</div>';
			
			//$form = '<form action="javascript:ITS_question_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.',\''.$qtype.'\');" name="ITS_SubmitForm" id="ITS_SubmitForm">';
      //$str = $answer_str->str; //'Answer goes here'; //$screen->showPage($Data);
		  break;
		//-------------------------------------------//
	  case 'answerLab':
	  //-------------------------------------------//
		$str = 'NULL';
		//die($screen->id);
    		/*----
    			$prop = preg_split('[,]',$Data);
    			//eval('$screen->lab_'.$prop[0].' = '.$prop[1]);
    			switch ($prop[0]){
    	  	  case 'index':  $screen->lab_index  = $prop[1]; break;
    				case 'active': $screen->lab_active = $prop[1]; break;
    			}
    		----*/
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
		  $data = preg_split('[,]',$Data);
		  $str  = $screen->newChapter($data[0],$data[1]);
		  break;	
		//-------------------------------------------//			
		 case 'getResource':
	  //-------------------------------------------//	
		    $query = 'SELECT tag_id FROM webct WHERE id=404';
	        $res =& $mdb2->queryRow($query);		
			$tags = explode(',',$res[0]);
			$str = '';
			foreach ($tags as $tid) { 
		    //echo '<p>'.$uid.'<p>';
				$query = 'SELECT comment FROM stats_'.$uid.' WHERE question_id=335';
				$str .= $tid.' + ';
				//$res =& $mdb2->query($query);
				//$answers = $res->fetchAll();
		}
		    //echo '<p>'.$uid.'<p>';
		    //$query = 'ALTER TABLE stats_'.$uid.' ADD time_start INTEGER UNSIGNED, ADD time_end INTEGER UNSIGNED, ADD course_id INT(11)';
				//$query = 'SELECT comment FROM stats_'.$uid.' WHERE question_id=335';
				//echo $query.'<p>';
				//$res =& $mdb2->query($query);
				//$answers = $res->fetchAll();
			
			//$str = ; //$screen->newChapter($Data);
		  break;	
		//-------------------------------------------//
}
//-----------------------------------------------//
echo $style.$str;
?>
