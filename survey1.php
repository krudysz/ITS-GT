<?php
$LAST_UPDATE = 'Mar-19-2012';
//=====================================================================//
/*					
	  Author(s): Gregory Krudysz	 
	  Last Revision: Mar-19-2012
*/
//=====================================================================//
error_reporting(E_ALL); // error_reporting(0);

require_once ("config.php");
require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_footer.php");
require_once ("classes/ITS_navigation.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");
include ("Class_Survey1.php");

session_start();
// return to login page if not logged in
abort_if_unauthenticated();

//--- NAVIGATION ------------------------------// 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current);    
//---------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<title>Survey</title>
<link rel="stylesheet" href="css/ITS_navigation.css">
<link rel="stylesheet" href="css/ITS_question.css">
<link rel="stylesheet" href="css/ITS_survey.css">
<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="css/ITS.css">
<script src="js/ITS_admin.js"></script>
</head>
<body class="ITS">
<div id="framecontent">
<!---************* NAVIGATION *****************--->
<?php echo $nav;?>
<!---******************************************--->
<div class="innertube"></div>
</div>
<!---******************************************--->
</div>
<div id="maincontent">
<?php
$id = $_SESSION['user']->id();

// Course Survey
if (empty($_GET['survey'])) { $survey = 'Spring_2011';  }
else 						{ $survey = $_GET['survey']; }
	 														 
$status = 'student';

//echo '<img src="phpimg/ITS_pie3.php">';
//$term = array('Fall_2008','Spring_2009','Fall_2009','Spring_2010');

$tr   = new ITS_statistics($id,$survey,$survey); // Spring_2010, Fall_2009
$term = array($survey); 
$tr->render_survey($term);

//echo '<p><a href="ITS_pre_lab.php?activity=13">Survey</a><p>';
//echo '<p><a href="survey.php">Survey</a><p>';

//--- FOOTER ------------------------------------------------//
$ftr = new ITS_footer($status,$LAST_UPDATE,'');
echo $ftr->main();
//-----------------------------------------------------------//
?>
</div>
</body>
</html>
