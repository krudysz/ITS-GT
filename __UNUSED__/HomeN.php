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
//------------------------------------------//
/*
<link rel="stylesheet" href="css/pagestyles.css" type="text/css">
<link rel="stylesheet" href="css/warmup.css" type="text/css" />
<link rel="stylesheet" href="css/question.css" type="text/css" />
*/
//<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Home</title>
<link rel="stylesheet" href="css/main1.css">
<link rel="stylesheet" href="css/question.css">
<script src="js/ITS_admin.js"></script>
<script src="js/ITS_AJAX.js"></script>
<script src="js/ITS_screen.js"></script>
</head>
<body class="ITS">
<div id="pageContainer">
<?php 
//<link rel="stylesheet" href="css/question.css" type="text/css" />
//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
//$id = 729; //622;508 //
$id = $_SESSION['user']->id();
//$id = 1;
$screen  = new ITS_screen($id,'Spring_2010','admin');
//$menu    = new ITS_menu(); //echo $menu->main();
$message = new ITS_message($screen->lab_number,$screen->lab_active);
$_SESSION['screen'] = $screen;
//</div>
?>
<div id="menuContainer">
<div id="messageIcon" onmouseover=HMOUSE(this,1) onmouseout=HMOUSE(this,0) onClick="ITS_MSG(1)">&para;</div>
<div class="Icon" onmouseover=HMOUSE(this,1) onmouseout=HMOUSE(this,0) >&raquo;</div>
<div class="Icon" onmouseover=HMOUSE(this,1) onmouseout=HMOUSE(this,0) >&#35;</div>
<a href="logout.php">Logout</a></div>
<div id="messageContainer"><?php echo $message->main();?></div>

<?php 
echo $screen->main().'<p>';
//$tr = new ITS_statistics($id,'Fall_2009',$status);
//$tr->main();
?>
</div>
</body>
</html>
