<?php
include ("classes/ITS_timer.php");

require_once ("config.php");
require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");

require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

$id = $_SESSION['user']->id();
$status = $_SESSION['user']->status();
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Profile</title>
	<!---->
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<link rel="stylesheet" href="tagging/ITS_tagging.css" type="text/css" media="screen">
	<link rel="stylesheet" href="rating/ITS_rating.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_test.css" type="text/css" media="screen">
	
  <link rel="stylesheet" href="css/ITS_survey.css">
	
  <link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
	
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
	<script src="rating/forms/star_rating.js"></script>
	<script type="text/javascript">
   $(document).ready(function(){
   $(".icon#Minst_icon").change(function(){$(this).css("background-color","red");});
   $(".icon#Minst_icon").click(function(){$(".ITS_instruction").slideToggle("normal");});
	 $(".icon#tagg_icon").click(function(){$(".ITS_instruction").slideToggle("normal");});
	 $(".icon#Tag_icon").mouseover(function(){$(".ITS_tags").slideToggle("normal");});
	 $("div.tagsMore#header").click(function(){$("div.tagsMore#list").slideToggle("fast");});
	 $("#messageContainer").click(function(){$("#messageContainerContent").slideToggle("slow");});
	 $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
	 $("#adminContainer").click(function(){$("#adminContainerContent").slideToggle("slow");}); 
	 $('#dateGREG').datepicker();
$("#resizable").resizable({
			handles: "se"
		});
});
</script>
	<script type="text/javascript">
	$(function() {
		$("#radio").buttonset();
	});
	</script>
	<style>
		
	</style>
</head>
<body>
	<form>
		<div id="radio">
			<input type="radio" id="radio1" name="radio" /><label for="radio1">Choice 1</label>
			<input type="radio" id="radio2" name="radio" checked="checked" /><label for="radio2">Choice 2</label>
			<input type="radio" id="radio3" name="radio" /><label for="radio3">Choice 3</label>
		</div>
	</form>


<?php  
$info = & $_SESSION['user']->info();
$ch = $_GET['ch'];
$id = $_SESSION['user']->id();
$status = 'admin';
$tr = new ITS_statistics($id, 'Fall_2010', $status); //Fall_2009
$tr->render_profile2($ch);
?>
</body>
</html>	