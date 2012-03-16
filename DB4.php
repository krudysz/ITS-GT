<?php
$LAST_UPDATE = 'Sep-30-2011';
//--- begin timer ---//
$mtime     = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
//------------------//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   					 // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 					 // Must do cache-control headers 
header("Pragma: no-cache");

include ("classes/ITS_timer.php");
require_once ("config.php");

require_once ("classes/ITS_statistics.php");
require_once ("classes/ITS_computeScores.php");
require_once ("classes/ITS_footer.php");

require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

$id     =   $_SESSION['user']->id();
$status =   $_SESSION['user']->status();
$info   = & $_SESSION['user']->info();
//------------------------------------------// 
if ($status == 'admin') {

	 global $db_dsn, $db_name, $db_table_users, $db_table_user_state;
	
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}	 
	  
		//--- QUESTIONS ------------------------------------------//
		$msg = '';
		$questions = array();
		
		//--- USERS --- ------------------------------------------//
		$query = 'SELECT id,last_name,first_name FROM users WHERE status="Fall_2010"';
	    $res   = $mdb2->query($query);
		$users = $res->fetchAll();
		$idx = 1;
		$tb = '<table class="ITS_backtrace"><th>No.</th><th>Student</th><th>Question Id</th>';
        foreach ($users as $uid) { 	  
			
		    //echo '<p>'.$uid.'<p>';
				// ALTER TABLE stats_1 ADD datetime INTEGER UNSIGNED
				// UPDATE stats_1 SET datetime = FROM_UNIXTIME(1298095257) WHERE question_id=470
				// SELECT UNIX_TIMESTAMP(datetime1) FROM stats_1 WHERE question_id=470
				//$tnow = time();echo $tnow;die();
				//------------------------------------------------//
				/*
		    $query = 'ALTER TABLE stats_'.$uid.' ADD epochtime INTEGER UNSIGNED, ADD duration INTEGER';
				echo $query.'<p>';
				$res =& $mdb2->query($query);
				
				$query = 'SELECT comment FROM stats_'.$uid.' WHERE question_id=335';
				
				$answers = $res->fetchCol();
				if (!empty($answers)) {	
				  $ans_str = explode(',',$answers[0]);
					$ans_fix = implode(',',array('5784',$ans_str[1]));
				  echo '<b>'.$ix.'</b> - '.$uid.' - '.$answers[0].' - '.$ans_fix.'<p>'; 
					$ix = $ix+1;
				}
				*/
				//------------------------------------------------//
				$query = 'SELECT question_id,answered,epochtime,score FROM stats_'.$uid[0].' WHERE question_id=2156';
				//echo $query;
				$resq   = $mdb2->query($query);
				$record = $resq->fetchAll();
				
				if (!empty($record)){
				  $qtb = '<table class="ITS_backtrace">';
				  foreach ($record as $rid) {
					$qtb .= '<tr><td>'.$rid[0].'</td><td>'.$rid[1].'</td><td><font color="brown">'.date("D M j G:i:s T Y",$rid[2]).'</font></td><td>'.$rid[3].'</td></tr>';
				  } 
				  $qtb .= '</table>';
				$tb .= '<tr><td><b>'.$idx.'.</b></td><td>'.$uid[0].'<br><font color="blue">'.$uid[1].','.$uid[2].'</td><td>'.$qtb.'</font></td></tr>';
				$idx++;
			    }
 
				//------------------------------------------------//				
		}
		$tb .= '</table>';
		//sort($questions);
		/*
				echo '<pre>';  print_r($qid);  echo '</pre>'; die('==');
		*/

		/*
		//--- EACH QUESTION --------------------------------------// 
		//--------------------------------------------------------//
		//$query = 'SELECT id,question FROM webct WHERE qtype="C"';
	  //$users =& $mdb2->queryCol($query);		
		$users = range(1,1200);
		foreach ($users as $uid) { 
		    //echo '<p>'.$uid.'<p>';
		    //$query = 'ALTER TABLE stats_'.$uid.' ADD time_start INTEGER UNSIGNED, ADD time_end INTEGER UNSIGNED, ADD course_id INT(11)';
				//$query = 'SELECT comment FROM stats_'.$uid.' WHERE question_id=335';
				$query = 'SELECT id FROM webct WHERE id='.$uid;
				//echo $query.'<p>';
				$res =& $mdb2->query($query);
				$answers = $res->fetchAll();
				echo $uid.' - '.$answers[0].'<p>';
		}
		*/
  $mdb2->disconnect();		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>DATABASE</title>
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
		<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
</head>
<body>
<?php
echo $tb;
//--- TIMER -------------------------------------------------//
$mtime     = explode(" ",microtime());
$endtime   = $mtime[1] + $mtime[0];
$totaltime = ($endtime - $starttime);
//--- FOOTER ------------------------------------------------//
//$ftr = new ITS_footer($status,$LAST_UPDATE,$totaltime);
//echo $ftr->main();
//-----------------------------------------------------------//
?>
</body>
</html>
