<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   					 // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 					 // Must do cache-control headers 
header("Pragma: no-cache");

require_once ("config.php");
require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");
require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
  <META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS</title>
	<!---->
	<link rel="stylesheet" href="css/ITS_logs.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
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
		
  <script src="js/ITS_admin.js"></script>
	<script src="js/AJAX.js"></script>
  <script src="js/ITS_AJAX.js"></script>
  <script src="js/ITS_screen2.js"></script>
	<script src="js/ITS_QControl.js"></script>
	<script src="js/ITS_book.js"></script>
	<script src="tagging/ITS_tagging.js"></script>
	<script src="rating/forms/star_rating.js"></script> 
</head>

<body>
<?php
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}

		//for every lab
		for($i=1205;$i<1411;$i++) {
      $query = 'DROP TABLE IF EXISTS stats_'.$i; //'DELETE FROM users WHERE id='.$i;
  		echo $query.'<p>';
  		
  	  $res =& $mdb2->query($query);
      if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  		//$ques = $res->fetchAll();
		}
?>
</body>
</html>