<?php

include("classes/ITS_timer.php");

require_once("config.php");
require_once("classes/ITS_question_table.php");
require_once("classes/ITS_survey.php");
require_once("classes/ITS_menu.php");
require_once("classes/ITS_message.php");
require_once("classes/ITS_statistics.php");
require_once("classes/ITS_screen.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();
///*
//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
//$id = 729; //622;508 //
$id = $_SESSION['user']->id();
//$id = 1;
$status = 'Spring_2010';
$screen  = new ITS_screen($id,'Spring_2010',$status);
//$menu    = new ITS_menu(); //echo $menu->main();
$message = new ITS_message($screen->lab_number,$screen->lab_active);
$_SESSION['screen'] = $screen;
//*/
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS</title>
<style type="text/css">
body /*TH,CAPTION */
{
	margin: 0;
	padding: 0;
	font: 12pt Verdana, Geneva, sans-serif;
	/*font-family: arial,sans-serif; /*font: 100% Georgia, serif; /*font-family: arial; */
	text-align: center;
	color: black;
	background: gainsboro;
	/*background: #000 url(ML.png) repeat 0 0;*/
}
#pageContainer
{
  postion:absolute;
	width: 95%;
	margin-left:-100%;
	margin:0 auto; /*Centers the Container*/
	background: pink;
}
#contentContainer{
  position: relative;
  padding:0; 
	margin:0 auto ;
	background:white;
	border:2px solid red;
	width: 100%;
	overflow:auto;
}
/*
div.other {
  position: absolute;
  padding:0; 
	margin:0;
	border:2px solid purple;
	display: inline;
}*/
div.ITS_question_container
  {
	float:left;
  margin:2px;
  border:1px solid #0000ff;
  width:45%;
  text-align:center;
  }
div.ITS_question_container img
  {
	position: relative;
	width:100%;
	height:auto;
  border:1px solid pink;
  }
div.ITS_question_container img:hover
  {
  border:1px solid red;
  }
	/*---------------*/
</style>
	<!--
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->

  <script src="js/ITS_admin.js"></script>
  <script src="js/ITS_AJAX.js"></script>
  <script src="js/ITS_screen.js"></script>
</head>

<body>
<div id="pageContainer">
<!--
	<div id="menuContainer">
	  <div id="messageIcon" onmouseover=HMOUSE(this,1) onmouseout=HMOUSE(this,0) onClick="ITS_MSG(1)">&para;</div>
		<div id="iconMenu"><a href="#content">ICON MENU</a></div>
		<div class="logout"><a href="logout.php">Logout</a></div>
	</div> 
	<div id="messageContainer">-->
	<?php
	//php //echo $message->main();
	///*?>
		<div id="headerContainer">
    <ul id="navlist">
        <li id="active"><a href="#" id="current">LABS</a></li>
        <li><a href="#">EXERCISES</a></li>
    </ul>
</div>
	<div id="contents">
		<div class="blogentry">
			<p>
				<img class="imagefloat" src="flower.jpg" alt="" width="100" height="100" border="0">
			</p>
		</div>
	</div></div>


<div id="contentContainer">
    <div class="ITS_question_container">
     This text taht should be palced here in this space Some text taht should be palced here in this space
    </div>
    <div class="ITS_question_container">
      <img src="PreLabs/ITSspecgramLab5.png">
    </div>
</div>
<?php 
echo $screen->main().'<p>';
$tr = new ITS_statistics($id,'Fall_2009',$status);
$tr->main();
//<li><a href="#"><b>ITS</b> version 66</a></li>
//        <li><a href="#"></a></li>
?>
	<!--<div id="footerContainer">
		<ul id="navlist">
		  <li>ITS version 66</li>
        <li><a href="ITS_versions.php">ITS version 66</a></li>
				<li><a>help</a></li>	  
    </ul>
	</div>
	-->
</div>
</body>
</html>
