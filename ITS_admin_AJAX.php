<?php
/*
ITS_admin_AJAX - script for AJAX admins

Author(s): Greg Krudysz
Date: Apr-16-2011
---------------------------------------------------------------------*/
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate");            // Must do cache-control headers 
header("Pragma: no-cache");

require_once("config.php");
require_once($MDB2_path.'MDB2.php');
include("classes/ITS_table.php");
include("classes/ITS_configure.php");
include("classes/ITS_question.php");
include("classes/ITS_statistics.php");
include("classes/ITS_screen2.php");
include("classes/ITS_users.php");
include("classes/ITS_computeScores.php");
//include("jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js");

$style = '<head>'
        .'<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />'
        .'<link type="text/css" href="css/ITS_question.css" rel="stylesheet" />'
				.'</head>';

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
else {
//-- Get AJAX arguments
$args = preg_split('[,]',$_GET['ajax_args']);
$action = $args[0];

//-- Get AJAX user data
//$Data = rawurldecode($_GET['ajax_data']);
$Data = $_GET['ajax_data'];
// preprocess before SQL
$Data = str_replace ("'","&#39;",$Data);
//$Data = nl2br($Data);

/*
$action = 'recordChapterAnswer'; //$args[0];
$Data = '819~M~4,2,5,,,';
*/
/*
echo 'action = '.$action.'<p>';
echo 'data   = '.$Data.'<p>';    die();
*/

$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

//-----------------------------------------------//
switch ($action){ 
    //-------------------------------------------//
	  case 'uploadImage':
		 $str = var_dump($_FILES);
	  //-------------------------------------------//
		 //die('here');
		 /*
		  $data  = preg_split('[~]',$Data);
		  $uploaddir = '/var/www/ITS/FILES/images/';
	      $uploadfile = $uploaddir . basename($_FILES['ITS_image']['name']);

if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
    $str = '<font color="green">File is valid, and was successfully uploaded.</br></font>';
} else {
    $str = '<font color="red">Possible file upload attack!<br></font>';
}*/
			break;		
    //-------------------------------------------//
	  case 'getConcept':
	  //-------------------------------------------//
		  $data  = preg_split('[~]',$Data);
		  $query = 'SELECT * FROM stats_'.$id;  //die($query);
      $res   = & $mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
      $list  = $usr->add_user($data[0],$data[1],$data[2],$data[3]);
			$str   = $list;
			break;
    //-------------------------------------------//			
	  /*
		case 'saveQuestion':
	  //-------------------------------------------//
      $data = preg_split('[~]',$Data);
      		// DEBUG: var_dump($Data);//die();
      $field = strtolower(str_replace("ITS_","",$Target));
      //echo 'DEBUG: '.$Data; die();
      
      switch ($field):
      case 'title':
      case 'question':
      case 'image':
      case 'category':
          $query = "UPDATE ".$tb_name." SET ".$field."='".addslashes($Data)."' WHERE id=".$qNum;
      //die($query);
          break;
      default:
          $query = "UPDATE ".$tb_name."_".$Q->Q_type." SET ".$field."='".addslashes($Data)."' WHERE id=".$qNum;
      endswitch;
      
      //echo $query;  die();
      $res =& $mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
      
      // Pre-process string for output:
      $str = latexCheck2($Data,$Q->mimetex_path);
			break;*/
    //-------------------------------------------//
	  case 'addUser':
	  //-------------------------------------------//
		  $data = preg_split('[~]',$Data);
		  $usr  = new ITS_users($data[2]);
      $list = $usr->add_user($data[0],$data[1],$data[2],$data[3]);
			$str  = $list;
			break;
    //-------------------------------------------//
	  case 'orderProfile':
	  //-------------------------------------------//
		  $data = preg_split('[~]',$Data);
		  $tr   = new ITS_statistics($data[0],$data[1],$data[2]);
      $list = $tr->render_profile2($data[3],$data[4]);
			$str  = $list;
			break;
    //-------------------------------------------//
	  case 'deleteDialog':
	  //-------------------------------------------//
		  $id = $Data;

      //$query = 'SELECT last_name FROM users WHERE id='.$id;
			$query = 'DELETE FROM stats_'.$id;  //die($query);
      $res   = & $mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
      $str = $query;
      //$user = $res->fetchRow();
			//$str = $user[0];
			break;
		//-------------------------------------------//			
			  case 'getQuestionMeta':
	  //-------------------------------------------//
		    $id = $Data;
			$query = 'SELECT id,title,image,category,tag_id FROM webct WHERE id='.$id;
			//echo $query;		//
			
			$res   = & $mdb2->query($query);
			$meta  = $res->fetchRow();
			$title = $meta[1];
			$image = $meta[2];
			$category = $meta[3];
			$tags  = $meta[4];
			if (empty($tags)) { $str = '<p><b>-- no data --</b></p>'; }
			else {	 			
  			$query = 'SELECT name FROM tags WHERE id IN ('.$tags.')';
  			
  			$res = & $mdb2->query($query);
  			$tagNames = $res->fetchCol();
  
  			$tagList = '';
  			for ($i=0; $i < count($tagNames); $i++) {
  			  //$tagList .= '<input type="button" class="logout" value="'.$tagNames[$i].'">';
					$tagList .= '<span class="ITS_tag">'.$tagNames[$i].'</span>';
  			}		
  			
			$sts = '<center><table class="ITS_ANSWER_C">'.
               '<tr><th id="title">Title</th><th>Image</th><th>Category</th><th id="tags">Tags</th></tr>'.
               '<tr><td>'.$title.'</td><td>'.$image.'</td><td>'.$category.'</td><td>'.$tagList.'</td></tr>'.
               '</table></center>';
			$str = $sts;
			//die($str);
			}
			break;
		//-------------------------------------------//			
			  case 'createQuestion':
	  //-------------------------------------------//
				$data   = preg_split('[~]',$Data);
				$action = $data[0];  //var_dump($data);die();
				$type   = $data[1];
				$student_id = 1;
				$obj = new ITS_question($student_id,$db_name,$tb_name);
				switch ($action){
					  //---------------//
					case 'new':
						//---------------//
						$N = 4;  // default number of answers 
						// data: id,qtype,title,question,image,answers,answers_config,question_config,category
						$data = array(NULL,$type,'','',NULL,$N,1,1,NULL);
						$obj->load_DATA($data);
						$obj->Q_answers_values = array_fill(0,$N,NULL);
						$obj->Q_weights_values = array_fill(0,$N,NULL);
						break;
						//---------------//
					case 'clone':
						//---------------//
							$q_num = $data[1]; //die($data[1]);
							$obj->load_DATA_from_DB($q_num);
							$obj->get_ANSWERS_data_from_DB();
						break;
						//---------------//
				}
				$str = $obj->renderQuestionForm($Data);
				break;		
		//-------------------------------------------//			
			  case 'addQuestion':
	  //-------------------------------------------//
		$str = '';
		$Qfield_key = array();
		$Qfield_str = array();
		$Afield_key = array();
		$Afield_str = array();		
    $fields     = explode("&",$Data);
		$Qidx = 0;  $Aidx = 0;
		
    foreach($fields as $field){
      $field_key_value = explode("=",$field);
      $key   = urldecode($field_key_value[0]);
      $value = urldecode($field_key_value[1]);
			
			//DEBUG: echo $key.' --- '.$value.'<p>';
			//if (!empty($value)) {
			  switch ($key){
      	  case 'qtype';
  				case 'title';
  				case 'image';
  				case 'question';
  				case 'answers';
  				case 'answers_config';
  				case 'question_config';
  				case 'category';
  				case 'tag_id'; 
            $Qfield_key[$Qidx] = $key; 
  			    $Qfield_str[$Qidx] = addslashes($value); //htmlspecialchars($value,ENT_QUOTES);	
						echo $Qfield_key[$Qidx].' - '.$Qfield_str[$Qidx].'<p>';
  					$Qidx ++;	
  			  break;
  			  default:
  			    $Afield_key[$Aidx] = $key; 
  			    $Afield_str[$Aidx] = addslashes($value); //htmlspecialchars($value,ENT_QUOTES);	
  					$Aidx ++;
				}
			//}//*/
    }

		// QUESTION SQL INSERT
		// title,question,image,answers,answers_config,question_config,category
		$Qquery_fields = implode(',',$Qfield_key);
		$Qquery_values = implode('","',$Qfield_str);
		$Qquery = 'INSERT INTO webct ('.$Qquery_fields.') VALUES("'.$Qquery_values.'")';
		// DEBUG: echo $Qquery; //die();mysql_real_escape_string

		$qtype = $Qfield_str[0];
		
		mysql_query($Qquery);
		$qid = mysql_insert_id();
		
		// ANSWER SQL INSERT
		$Aquery_fields = implode(',',$Afield_key);
		$Aquery_values = implode('","',$Afield_str);
		$Aquery = 'INSERT INTO webct_'.$qtype.' (id,'.$Aquery_fields.') VALUES('.$qid.',"'.$Aquery_values.'");';
		//echo $Aquery;
		mysql_query($Aquery);
		//mysql_real_escape_string(). 
		$msg = '<div class="ITS_MESSAGE">Added Question <a href="Question.php?qNum='.$qid.'">'.$qid.'</a>';
		$str = $msg.'<div class="ITS_SQL">'.$Qquery.'</div><div class="ITS_SQL">'.$Aquery.'</div></div>';
		//*/
		//$str = 'ad';
		break;
		//-------------------------------------------//			
			  case 'deleteQuestion':
	  //-------------------------------------------//
		  $data = preg_split('[~]',$Data);
		  $id   = $data[0];
			$type = $data[1];
			//$query = 'DELETE FROM webct WHERE id='.$id.'; DELETE FROM webct_'.$type.' WHERE id='.$id;
			$query = 'DELETE w,wt FROM `webct` w,`webct_'.$type.'` wt WHERE w.id='.$id.' AND wt.id=w.id';
      $res  = & $mdb2->query($query);
			$str  = '<div class="ITS_SQL">'.$query.'</div>'; //'done';
		break;		
		//-------------------------------------------//			
			  case 'editAnswers':
	  //-------------------------------------------//
		  $data = preg_split('[~]',$Data);
			$q_num  = $data[0];  //var_dump($data);die();
			$type   = $data[1];
			$q_type = $data[2];
			$N      = $data[3];  // default number of answers
			$student_id = 1;
      	
		  $obj = new ITS_question($student_id,$db_name,$tb_name);
      //die($type);
  		switch ($type){
			  //---------------//
    	  case 'new':
				//---------------//
  				// data: id,qtype,title,question,image,answers,answers_config,question_config,category
  		    $data = array(NULL,$q_type,'','',NULL,$N,1,1,NULL);
  				$obj->load_DATA($data);
  				$obj->Q_answers_values = array_fill(0,$N,NULL);
  		    $obj->Q_weights_values = array_fill(0,$N,NULL);
    		break;
				//---------------//
    		case 'clone':
				//---------------//
          $obj->load_DATA_from_DB($q_num);
					$obj->get_ANSWERS_data_from_DB();	
		    break;  
			}
			//echo $obj->Q_answers;
		  $class = 'text ui-widget-content ui-corner-all ITS_Q';
      $ans   = '<table id="ITS_Qans" class="ITS_Qans">';
      for ($a=1; $a<=$N; $a++) {   
			   if ($a > $obj->Q_answers) { 
				 		$answerVal = ''; 
						$weightVal = '';		  
				 }
				 else 																		{ 
					 	$answerVal = htmlspecialchars($obj->Q_answers_values[$a-1]); 
				 		$weightVal = htmlspecialchars($obj->Q_weights_values[$a-1]); 	
				 } 
				 $answer_label = '<label for="answer'.$a.'">'.'answer&nbsp;'.$a.'</label>';
         $answer_field = '<input type="text" name="answer'.$a.'" id="answer'.$a.'" value="'.$answerVal.'" class="'.$class.'" />';
         $weight_label = '<label for="weight'.$a.'">'.'weight&nbsp;'.$a.'</label>';
         $weight_field = '<input type="text" name="weight'.$a.'" id="weight'.$a.'" value="'.$weightVal.'" class="'.$class.'" />';
         $ans .= '<tr><td width="10%">'.$answer_label.'</td><td width="60%">'.$answer_field.'</td><td width="10%">'.$weight_label.'</td><td width="5%">'.$weight_field.'</td></tr>';
      }
      $ans .= '</table>';
			$str  = $ans;
		break;			
		//-------------------------------------------//
}
//-----------------------------------------------//
$mdb2->disconnect();
echo $str;
}
?>
