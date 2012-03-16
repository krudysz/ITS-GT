<?php
$LAST_UPDATE = 'May-11-2011';
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
		//$query = 'SELECT id FROM users WHERE id NOT IN (927,948,1005,1026,1065,1070,1127,1173,1188)';
		// 394,457,487,488,531,542,569,575,687,743,744,745,746,747
		$query = 'SELECT id FROM users WHERE status="Fall_2011"';
	    $res   = $mdb2->query($query);
		$users = $res->fetchCol();
		
		$features = array('current_chapter','score','rating','epochtime','duration');	
	  for ($f=0; $f<count($features); $f++) {
		  
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
				$query = 'SELECT question_id,'.$features[$f].' FROM stats_'.$uid.' WHERE current_chapter IN (1,2,3,4,5,6,7,9,11)';
				//echo $query;
				$resq   = $mdb2->query($query);
				$record = $resq->fetchAll();

				foreach ($record as $rid) {
						// add question to "qid" stack		
            if (!in_array($rid[0],$questions)) { array_push($questions,$rid[0]); }
				
						// DATA[uid][rid] = feature 
						//echo '<p style="color:blue">data['.$uid.']['.$rid[0].'] = '.$rid[1].'</p>';
						$data[$uid][$rid[0]] = $rid[1];
				} 
				//------------------------------------------------//				
		}
		sort($questions);
		/*
				echo '<pre>';  print_r($qid);  echo '</pre>'; die('==');
		*/
	//=== SAVE TO FILE =========================================================//			
	
	$file_path = 'admin/csv/DB/'.$features[$f].'.csv';  //die($file_path);
	$fp = fopen($file_path, 'w');
	for  ($r=0; $r<count($questions); $r++) {
	  $fields = array();
	  for  ($c=0; $c<count($users); $c++) {
		  //echo 'empty? data['.$users[$c].']['.$qid[$r].']<br>'; //die(); 
		  if (!isset($data[$users[$c]][$questions[$r]])) { $fields[$c] = '';                                }
			else																	         { $fields[$c] = $data[$users[$c]][$questions[$r]]; }
			$uid_row[$c] = $users[$c];
	  }
		array_push($fields,$questions[$r]);
		if ($r==0) { fputcsv($fp,$uid_row); }  // top row uid
		//echo '<pre>';  print_r($fields);  echo '</pre>';
		fputcsv($fp,$fields);
	}
	fclose($fp);
	$msg .= '<p>Saved: '.$file_path.'</p>';
	//==========================================================================//		
	} // for:features

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
  <style>
	  #select_class { margin-top: 2em; }
		.ui-widget-header   { background: #aaa; border: 2px solid #666; }
		.ui-dialog-titlebar { background: #aaa; border: 2px solid #666; }
		.ui-dialog-content  { text-align: left; color: #666; padding: 0.5em; }
		.ui-button-text { color: #00a; }
	</style>	
<script type="text/javascript">
</script>
</head>
<body>
<?php
echo $msg;
//--- TIMER -------------------------------------------------//
$mtime     = explode(" ",microtime());
$endtime   = $mtime[1] + $mtime[0];
$totaltime = ($endtime - $starttime);
//--- FOOTER ------------------------------------------------//
$ftr = new ITS_footer($status,$LAST_UPDATE,$totaltime);
echo $ftr->main();
//-----------------------------------------------------------//
?>
</body>
</html>
