<?php
$LAST_UPDATE = 'Feb-14-2012';
//=====================================================================//
/* 					
     Author(s): Gregory Krudysz
     Last Revision: Feb-14-2012
*/
//=====================================================================//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate");            // Must do cache-control headers 
header("Pragma: no-cache");

require_once ("config.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_footer.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");
require_once ("classes/ITS_navigation.php");
//require_once("classes/ITS_question2.php");

session_start();
// return to login page if not logged in
abort_if_unauthenticated();
//--------------------------------------// 
  $mdb2  =& MDB2::connect($db_dsn);
  $query = 'SELECT id,title,question,category,tag_id FROM webct';
  //echo $query; die();

  $res =& $mdb2->query($query);
  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $qs = $res->fetchAll();
  
  // get TAGS
  $query = 'SELECT id,name FROM tags';  //echo $query;
  $res =& $mdb2->query($query);
  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $tags = $res->fetchAll();
  //$tags = implode(',',$tagNames);
  
  for ($i=0; $i < count($qs); $i++) {
	  $query = $qs[$i][1].' '.$qs[$i][2].' '.$qs[$i][3];
	  $tt='';
	  $tArr = array();
	  for ($t=0; $t < count($tags); $t++){
	     if (stristr($query,$tags[$t][1])){ 
			 $tt.=$tags[$t][1].'-'; 
			 array_push($tArr,$tags[$t][0]);
		 }
      }
      // combine tags: old + new
      $tag_ids = implode(',',array_unique(array_merge(explode(',',$qs[$i][4]),$tArr)));
      //echo '<hr>'.$query.'<p style="color:blue">'.$sql.'</p>';
      $sql = 'UPDATE webct SET tag_id="'.$tag_ids.'" WHERE id='.$qs[$i][0];
      echo '<hr>'.$sql;
	  $res =& $mdb2->query($sql);
	  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  }
	  echo 'done';
/*
  $Nqs = count($qs);
  /// QUERY SEARCH
  if ($Nqs){
  $qid = $qs[$qindex][0];
  $tagList = '';
  if (!empty($tags)) {
  for ($i=0; $i < count($tagNames); $i++) {
  //$tagList .= '<input type="button" class="logout" value="'.$tagNames[$i].'">';
  $tagList .= '<span class="ITS_tag">'.$tagNames[$i].'</span>';
  }
  }

  //$tb = new ITS_table('qs',round(sqrt($Nqs)),round(sqrt($Nqs)),$qs,array(),'ITS_ANSWER');
  //echo $tb->str;
  //echo implode(', ',$qs).'<p>';
  //--------------------------------------//
  $Q = new ITS_question(1,$db_name,$tb_name);
  $Q->load_DATA_from_DB($qid);
  //echo $Q->render_QUESTION_check()."<p>";
  $Q->get_ANSWERS_data_from_DB();
  //$Q->get_ANSWERS_solution_from_DB();
  //echo $Q->render_ANSWERS('a',0);
  $meta = $Q->render_data();
  //$mdb2->disconnect();
  //--------------------------------------//
 
$Nqs = 'z';
//--------------------------------------//
$Q = new ITS_question(1, $db_name, $tb_name);
$Q->load_DATA_from_DB($qid);
//echo $Q->render_QUESTION_check()."<p>";
$Q->get_ANSWERS_data_from_DB();
//$Q->get_ANSWERS_solution_from_DB();
//echo $Q->render_ANSWERS('a',0);
$meta = $Q->render_data();

//$mdb2->disconnect();
//--------------------------------------//
    switch ($status) {
    case 'admin':
        $adminNav = $Q->render_Admin_Nav($qid, $Q->Q_type,'ITS_button');
        break;
    default:
        $adminNav = '';
        break;
    }
//--- NAVIGATION ------------------------------// 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current);    
//---------------------------------------------//
//'<div class="ITS_navigate">'
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <script src="js/ITS_AJAX.js"></script>
        <script src="js/ITS_QControl.js"></script>
        <title>Questions Database</title>
        <link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_QTI.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
        <link rel="stylesheet" href="plugins/tagging/ITS_tagging.css" type="text/css" media="screen">
        <link rel="stylesheet" href="plugins/rating/ITS_rating.css" type="text/css" media="screen">	
        <link rel="stylesheet" href="css/ITS_questionCreate.css" type="text/css" media="screen">	
        <link rel="stylesheet" href="css/ITS_index4.css" type="text/css" media="screen">

        <link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
  <!-- <script type="text/javascript" src="MathJax/MathJax.js"></script> -->

        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
<?php include 'js/ITS_Question_jquery.php'; ?>
    </head>

    <body>
<?php 
echo 'hello';
?>
    </body>
</html>*/
?>
