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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/main2.css">
<title>Untitled Document</title>
</head>
<body>
<div id="pageContainer">
<?php 
$id = $_SESSION['user']->id();
//$id = 729;
$screen  = new ITS_screen($id,'Spring_2010','admin');
//$menu    = new ITS_menu(); //echo $menu->main();
$message = new ITS_message($screen->lab_number,$screen->lab_active);
$_SESSION['screen'] = $screen;
?>
<div id="menuContainer"><a href="logout.php">Logout</a></div>
<div id="messageContainer"><?php echo $message->main();?></div>
</body>
</html>
