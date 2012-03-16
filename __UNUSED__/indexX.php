<?php
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");     // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   					 // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 					 // Must do cache-control headers 
header("Pragma: no-cache");

include ("classes/ITS_timer.php");

require_once ("config.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_timer.php");
require_once ("classes/ITS_statistics.php");
/*-- SCORING module -----------------------------------*/
require_once ("classes/ITS_computeScores.php");
require_once ("classes/ITS_book.php");
/*-- TAGGING module -----------------------------------*/
//require_once ("tagging/ITS_tagInterface.php");
/*-- RATING module ------------------------------------*/
//require_once ("rating/ITS_rating.php");
/*-----------------------------------------------------*/
require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
//echo $timer;
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
//$id = 670; //622;508 //
$id = $_SESSION['user']->id();
$status = $_SESSION['user']->status();
//echo $status;

if (isset ($_POST['role'])) {$role = $_POST['role'];} 
else {
	switch ($status) {
	 case 'admin': $role = 'admin';   break;
	 default:      $role = 'student'; break;
	}
}
$screen = new ITS_screen2($id, $role, $status);
//$menu    = new ITS_menu(); //echo $menu->main();
//$message = new ITS_message($screen->lab_number, $screen->lab_active);
$_SESSION['screen'] = $screen;

//------------------------------------------//

//<!--<script type="text/javascript" src="js/ITS_jquery.js"></script>-->
//<img src="images/matching_example1.png" style="float:left;height:50px;cursor:pointer;border:1px solid #666;" onclick="ITS_showImage(this)">
//<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	

	//<link rel="stylesheet" href="css/ITS_DEBUG.css" type="text/css" media="screen">
	//<link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
  <META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS</title>
			<!-- rating module-->
	<link rel="stylesheet" href="plugins/star-rating/jquery.rating.css" type="text/css" media="screen">
	<script type="text/javascript" src="plugins/star-rating/jquery.js"></script>
	<script type="text/javascript" src="plugins/star-rating/jquery.rating.js"></script>
	<script type="text/javascript" src="plugins/star-rating/jquery.rating.pack.js"></script>
	<script type="text/javascript" src="plugins/star-rating/jquery.MetaData.js"></script>
	<link rel="stylesheet" href="css/ITS_logs.css" type="text/css" media="screen">
	
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<link rel="stylesheet" href="tagging/ITS_tagging.css" type="text/css" media="screen">
	<link rel="stylesheet" href="rating/ITS_rating.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/ITS_DEBUG.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">	
	<style type="text/css">
  	#feedback { font-size: 1.4em; }
  	#selectable .ui-selecting { background: #FECA40; }
  	#selectable .ui-selected  { background: #F39814; color: white; }
  	#selectable { list-style-type: none; margin: 0; padding: 0;    }
  	#selectable li { margin: 3px; padding: 1px; float: left; width: 200px; height: 80px; font-size: 4em; text-align: center; }
 </style>
	<!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
  <script src="js/ITS_admin.js"></script>
	<script src="js/AJAX.js"></script>
  <script src="js/ITS_AJAX.js"></script>
  <script src="js/ITS_screen2.js"></script>
	<script src="js/ITS_QControl.js"></script>
	<script src="js/ITS_book.js"></script>
	<script src="tagging/ITS_tagging.js"></script>
	
	<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
  <link rel="stylesheet" type="text/css" href="plugins/rating/ITS_rating.css" media="screen">
	<link rel="stylesheet" type="text/css" href="plugins/rating/jquery.ui.stars.css?v=3.0.0b38" media="screen">
	<script type="text/javascript" src="plugins/rating/jquery.ui.stars.js?v=3.0.0b38"></script>
</head>

<body>
<input type="hidden" id="LabData"/>
<div id="pageContainer">

<!-- myScore ---------->
    <div id="scoreContainer"><span>&raquo;&nbsp;My Scores</span></div>
    <div id="scoreContainerContent">
<?php
$chUser = 4;
if ($role == 'admin') {$chMax = 13;       } 
else                  {$chMax = $chUser;  }
$chArr = range(1,$chMax);

//==$score = new ITS_computeScores($id,$role,$chArr); //,$ch);
//==$_SESSION['score'] = $score;

//$str = $score->renderChapterScores($chMax);
$str = 'score';
echo $str;

?>
</div>
<div id="page">
<!-- CONTENT ----------------------------------------------->
<?php
$screen->chapter_number = $chUser;
$meta = 'image';
?>
<div id="accor">
	<div class="accor">
		<h3 class="hel" id="headerQuestion" view="<?php echo $role=='student';?>"><a href="#" class="headering">Questions: <span name="Question" ch="<?php echo $chUser;?>"><?php echo 'Chapter '.$chUser;?></span></a></h3>
		 <div class="ITS_ghost">		
				 <?php 
				 echo 'MAIN';
				 /*
				 if ($role == 'admin') { echo $screen->main(); }
				 else { 		 
				   //$time = explode(':',date('G:i'));
				   //if ($time[0] < 3) { echo $screen->main(); }  //date("l") == 'Tuesday' AND 
				   //else {
					   echo '<h1>ITS has closed.<p>Your answers for Chapter 1-2 are available for review.</p></h1>'; 
				   //}
				 }*/
				 ?>
		</div>		 
  </div>
	<div class="accor">
		<h3 id="headerReview"><a href="#" class="header">Review: <span name="Review" qid="" ch="<?php echo $chUser;?>"><?php echo 'Chapter '.$chUser;?></span></a></h3>
		<div class="ITS_ghost">
		<div id="reviewContainer">
			<?php 
			 //$screen->screen = 4;
			 //$screen->term_current = 'Spring_2011';
			 //echo $screen->reviewMode(1,0);
			 echo 'REVIEW';
			?>
			</div>
		</div>
	</div>	
</div>

</div>
<p>
<!-- FOOTER ----------------------------------------------->
<?php
echo 'FOOTER';
?>
</body>
</html>
