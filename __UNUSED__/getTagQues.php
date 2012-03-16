<?php
//--- begin timer ---//
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
//------------------//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   					 // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 					 // Must do cache-control headers 
header("Pragma: no-cache");

require_once ("config.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
//session_start();

// return to login page if not logged in
//abort_if_unauthenticated();
$status = 'admin';
//------------------------------------------// 
if ($status == 'admin') {
	 global $db_dsn, $db_name, $db_table_users, $db_table_user_state;
	
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}	 
	 	
		//--- EACH USER ------------------------------------------//
		$query = 'SELECT name,question_id FROM tags';
	    $res =& $mdb2->query($query);
		$tags = $res->fetchAll();
		$ix = 1;
		$fp = fopen('ITS_concepts.csv', 'w');
		foreach ($tags as $tag) { 
		    echo '<p>'.$tag[0].' - '.count(explode(',',$tag[1])).'<p>';
				//$query = 'SELECT comment FROM stats_'.$uid.' WHERE question_id=335';
				//echo $query.'<p>';
				/*
				$res =& $mdb2->query($query);
				$answers = $res->fetchCol();
				if (!empty($answers)) {	
				  $ans_str = explode(',',$answers[0]);
					$ans_fix = implode(',',array('5784',$ans_str[1]));
				  echo '<b>'.$ix.'</b> - '.$uid.' - '.$answers[0].' - '.$ans_fix.'<p>'; 
					$ix = $ix+1;
				}*/
				$fields = array($tag[0],count(explode(',',$tag[1])));
				fputcsv($fp, $fields);
		}
//var_dump($list);
    
    /*
    foreach ($list as $fields) {
        fputcsv($fp, $fields);
    }*/
    
    fclose($fp);

		//--- EACH QUESTION --------------------------------------// 
		//--------------------------------------------------------//
		/*
		$query = 'SELECT id,question FROM webct WHERE qtype="C"';
	  //$users =& $mdb2->queryCol($query);		
		
		foreach ($users as $uid) { 
		    //echo '<p>'.$uid.'<p>';
		    //$query = 'ALTER TABLE stats_'.$uid.' ADD time_start INTEGER UNSIGNED, ADD time_end INTEGER UNSIGNED, ADD course_id INT(11)';
				$query = 'SELECT comment FROM stats_'.$uid.' WHERE question_id=335';
				echo $query.'<p>';
				//$res =& $mdb2->query($query);
				//$answers = $res->fetchAll();
		}
		*/
    $mdb2->disconnect();	
	die('da');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Profile</title>
	<!---->
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_profile.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<link rel="stylesheet" href="tagging/ITS_tagging.css" type="text/css" media="screen">
	<link rel="stylesheet" href="rating/ITS_rating.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_test.css" type="text/css" media="screen">
	
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
	$(function() {
      $(".ITS_select").change(function() { document.profile.submit(); });
			$("#select_class").buttonset();
  });
	/*-------------------------------------------------------------------------*/
  $(document).ready(function() { 
     //$("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});

  });
  </script>
</head>
<body>
<?php
//-----------------------------------------------------------//
// ACCOUNT INFO
//-----------------------------------------------------------//
echo '<p>DONE<p>';
//-----------------------------------------------------------//
echo '<p class="center">&nbsp;<b><font color="silver">~&diams;~</font></b>&nbsp;</p>';
//-----------------------------------------------------------//
//--- begin timer ---//
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
//------------------//
echo '<p style="float:right;bottom:0;right:10px">Page created in '.round($totaltime,2).' secs ';
?>
</body>
</html>
