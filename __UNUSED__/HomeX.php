<?php
include("classes/ITS_timer.php");

require_once("config.php");
require_once("classes/ITS_question_table.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");
//include("classes/ITS_statistics.php");

$timer = new ITS_timer();
session_start();
// return to login page if not logged in
abort_if_unauthenticated();
//------------------------------------------//
//<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<title>Home</title>
<link rel="stylesheet" href="css/warmup.css">
<link rel="stylesheet" href="css/question.css">
<script src="js/ITS_admin.js"></script>
</head>
<body class="ITS">
<?php include("menu1.php");?>
<div class="main" class="clearfix">
<?php 
//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
//$id = 729; //622;508 //
$id = $_SESSION['user']->id();
$id = 689;
$tr = new ITS_statistics($id,'Fall_2009',$status);
$tr->main();

//$dist = '<img src="ITS_plot.php" class="ITS_list">';
//echo $dist;
//$timer->etime();
//echo '<p><a href="ITS_pre_lab.php?activity=13">Survey</a><p>';
//echo '<p><a href="survey.php">Survey</a><p>';

//----------------------//
//echo str_repeat(" ",256)."<pre>";flush();
//echo "working ...<br>\r\n"; flush(); sleep(1);
  //$dist = '<img src="phpimg/ITS_signal.php?t=tr&d=-1,1,4,5,3&title=p(t)" class="ITS_bar">';
 // echo $dist;
//----------------------//
//$timer->etime();
?>
</div>
</body>
</html>
