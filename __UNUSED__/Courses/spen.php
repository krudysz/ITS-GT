<?php
$LAST_UPDATE = 'Oct-11-2011';
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
require_once ("classes/ITS_navigation.php");
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

if ($status == 'admin' OR $status == 'instructor') {
	 global $db_dsn, $db_name, $db_table_users, $db_table_user_state;
	
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}	 
	  
		//--- QUESTIONS ------------------------------------------//
		$msg = '';
		$questions = array();
		
		//--- USERS --- ------------------------------------------//
		//$query = 'SELECT id FROM users WHERE id NOT IN (927,948,1005,1026,1065,1070,1127,1173,1188)';
		// 394,457,487,488,531,542,569,575,687,743,744,745,746,747
		$query = 'SELECT id FROM users WHERE status="admin"';
		//die($query);
	    $res   = $mdb2->query($query);
		$users = $res->fetchCol();
		//die('d');
		//$features = array('current_chapter','score','rating','epochtime','duration');	
		$query = 'SELECT id,title FROM webct WHERE category LIKE "%SPEN%"';
	    $res   = $mdb2->query($query);
		$ques = $res->fetchAll();
		
/*
        $query = 'SELECT question_id,answered,qtype,answers,comment,epochtime,duration,rating FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter="'.$chapter.'" AND category IN ("PreLab0'.$chapter.'","Lab'.$chapter.'","Chapter'.$chapter.'"'.$other.') AND qtype IN ("MC","M","C") ORDER BY stats_'.$this->id.'.'.$orderby;
  				$res = & $this->mdb2->query($query);
  				if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  				$answers = $res->fetchAll();
  */				
		
	   // for ($qid=0; $qid<count($ques); $qid++) {  echo '<p>'.$ques[$qid].'<p>'; }
	  //----------------------------------//
	  
  			//-- LIST of questions (count($answers)-1)
  			$Estr = '<table class="PROFILE">'.
  			        '<tr><th style="width:4%;">No.</th><th style="width:77%;">Question</th><th style="width:14%;">Author</th></tr>';
  			for ($qn = 0; $qn <= (count($ques)-1); $qn++) {
  				//$qtype = strtolower($answers[$qn][2]);
  				//$Nanswers = $answers[$qn][3];
          					
          					/*	
					if ($qtype=='m') { 
      				// Obtain number of questions
							$fields = 'L1,L2,L3,L4,L5,L6,L7,L8,L9,L10,L11,L12,L13,L14,L15,L16,L17,L18,R19,L20,L21,L22,L23,L24,L25,L26,L27';
							$query = 'SELECT ' . $fields . ' FROM webct_m WHERE id=' .$answers[$qn][0];
      				//die($query);
      				$res = & $this->mdb2->query($query);
      				if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
      				$result = $res->fetchRow(); 
							$Nques  = count(array_filter($result));
							$ansM_arr = explode(',',$answers[$qn][1]);
							$ansM = array_slice($ansM_arr,0,$Nques);
							$ansM_list = implode(',',$ansM);
							//echo $ansM_list.'<p>'.$Nques.'<hr>';
							$ans = $this->render_question_answer($score,$ansM_list, $qtype,0); //##!!
					}
					else {
					    $ans = $this->render_question_answer($score,$answers[$qn][1], $qtype,0); //##!!
					}
					
*/
    

          $qid = $ques[$qn][0];
          $user = $ques[$qn][1];
          $Q = new ITS_question($qid,$db_name, 'webct');

          $Q->load_DATA_from_DB($qid);
 //echo $qid;
          $QUESTION = $Q->render_QUESTION(); //_check($answers[$qn][4]);    
          $Q->get_ANSWERS_data_from_DB();
          $ANSWER = $Q->render_ANSWERS('a', 2);
          //$ANSWER = $Q->render_ANSWERS('a',0);
			
					$Estr .= '<tr class="PROFILE" id="tablePROFILE">'.
          '<td class="PROFILE" >' . ($qn +1) .'<br><br><a href="Question.php?qNum='.$qid.'" class="ITS_ADMIN">'.$qid.'</a></td>'.
          '<td class="PROFILE" >' . $QUESTION.$ANSWER . '</td>'.
          '<td class="PROFILE" >'.$user.'</td>';

          $Estr .=  '</tr>';
  			} 
				$Estr.= '</table>';
				//echo $Estr;
			
//--- NAVIGATION ------------------------------// 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current);    
//---------------------------------------------//
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
	<link rel="stylesheet" href="css/ITS_profile.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<link rel="stylesheet" href="tagging/ITS_tagging.css" type="text/css" media="screen">
	<link rel="stylesheet" href="rating/ITS_rating.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_test.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	
	<link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
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
    <style>
	  #select_class { margin-top: 2em; }
		.ui-widget-header   { background: #aaa; border: 2px solid #666; }
		.ui-dialog-titlebar { background: #aaa; border: 2px solid #666; }
		.ui-dialog-content  { text-align: left; color: #666; padding: 0.5em; }
		.ui-button-text { color: #00a; }
	</style>	
<script type="text/javascript">
/*---- GOOGLE ANALYTICS ------------------*/
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16889198-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
/*---- GOOGLE ANALYTICS ------------------*/	
</script>

<?php include 'js/ITS_Profile_jquery.php';?>
</head>
<body>
<div id="framecontent">
<!---************* NAVIGATION ******************--->
            <?php echo $nav;?>
<!---******************************************--->
</div>
<!---******************************************--->
</div>
<div id="maincontent">
<?php
//-----------------------------------------------------------//
// ACCOUNT INFO
//-----------------------------------------------------------//
//echo $section.'--'.$sid.'--'.$status.'--'.$ch.'<p>';
echo '<div id="userProfile">'.$Estr.'</div>';
//--- TIMER -------------------------------------------------//
$mtime     = explode(" ",microtime());
$endtime   = $mtime[1] + $mtime[0];
$totaltime = ($endtime - $starttime);
//--- FOOTER ------------------------------------------------//
$ftr = new ITS_footer($status,$LAST_UPDATE,$totaltime);
echo $ftr->main();
//-----------------------------------------------------------//
?>
</div>
</body>
</html>
