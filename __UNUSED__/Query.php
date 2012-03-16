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

include ("classes/ITS_timer.php");

require_once ("config.php");
require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");
require_once ("classes/ITS_computeScores.php");

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
    //------- CLASS -------------//
    $class_arr = array('Spring_2011','Fall_2010');
		
    if (isset($_GET['class']))   { $section = $_GET['class']; }
    else                         { $section = $class_arr[0];  }
    
    //$class = '<div name="class" id="select_class">Class: ';
		$class = 'Class: <select class="ITS_select" name="class" id="select_class" onchange="javascript:this.submit()">';
    for ($cs=0; $cs<count($class_arr); $cs++) {
          if ($section == $class_arr[$cs]) { $sel = 'selected="selected"'; }
          else                             { $sel = '';          }
    			
    			//$class .= '<input type="checkbox" name="class" id="check'.$cs.'" '.$sel.'/><label for="check'.$cs.'">'.$class_arr[$cs].'</label>';
          $class .= '<option value="'.$class_arr[$cs].'" '.$sel.'>'.$class_arr[$cs].'</option>';
    }
    $class .= '</select>';
		//------- QUESTION -------------//
    if (isset($_GET['q'])) { $q = $_GET['q']; }
    else                   { $q = 1;          } 
    $question = 'Question id: <input type="text" class="ITS_select" name="q" id="select_question" value="'.$q.'" onchange="javascript:this.submit()">';
    
    //------- CHAPTER -------------//
    $ch_max = 13;
    if (isset($_GET['ch']))   { $ch = $_GET['ch']; }
    else                      { $ch = 1;           }
    
    $chapter = 'Chapter #<select class="ITS_select" name="ch" id="select_chapter" onchange="javascript:this.submit()">';
    for ($c=1; $c<=$ch_max; $c++) {
          if ($ch == $c) { $sel = 'selected="selected"'; }
          else           { $sel = '';                    }
          $chapter .= '<option value="'.$c.'" '.$sel.'>'.$c.'</option>';
    }
    $chapter .= '</select>';
    
    //------- USER ---------------//
    if (isset($_GET['sid']))  { $sid = $_GET['sid']; }
    else                      { $sid = $id;          }
    $usertable  = 'stats_'.$sid;
    
    $mdb2  =& MDB2::connect($db_dsn);
    $query = 'SELECT id,last_name,first_name,status FROM users WHERE status IN ("admin","'.$section.'") ORDER BY last_name';
		$res   =& $mdb2->query($query);
	
    $mdb2->disconnect();
    $user_data = $res->fetchAll();
    //class="ITS_select"
    $users = '<select  name="sid" class="ITS_select" id="select_user" onchange="javascript:this.submit()">';
    foreach ($user_data as &$user) {
        if ($sid == $user[0]) { $sel = 'selected="selected"'; $current_user = $user[3]; }
        else                  { $sel = '';                                              }
    		
    		if ($user[3] == 'admin') { $cl  = 'class="highlighted"'; }
        else                     { $cl  = '';                    }  
    		
        $users .= '<option '.$cl.' value="'.$user[0].'" '.$sel.'>'.$user[1].', '.$user[2].'</option>';
    }
    $users .= '</select>';
		//----------------------------//
    $classInfo = preg_replace('/_/',' ',$current_user);
    $form  = $class.' &nbsp; '.$question.' &nbsp; '.$users.' &nbsp; '.$chapter.' &nbsp; '.$classInfo.' &nbsp; <tt>id: </tt>'.$sid;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>QUERY</title>
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
     $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
		/*-------------------------------------------------------------------------*/		
		 $("#deleteButton").live('click', function(event) {
		    var uid = $(this).attr("uid");
		    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		    $( "#deleteDialog:ui-dialog" ).dialog( "destroy" );
	      $( "#deleteDialog" ).dialog({
    			resizable: false,
    			height: 300,
					width: 500,
    			modal: true,
    			buttons: { "Delete Now": function() { $( this ).dialog( "close" );
    									$.get('ITS_admin_AJAX.php', { ajax_args: "deleteDialog", ajax_data: uid}, function(data) {
                			  //alert(data); //$('#contentContainer').html(data); 
            					});
					         },
        				           Cancel: function() { $( this ).dialog( "close" ); }
        			     }
        }); 
		 });
	 /*-------------------------------------------------------------------------*/
  });
  </script>
  <style>
	  #select_class { margin-top: 2em; }
		.ui-widget-header   { background: #aaa; border: 2px solid #666; }
		.ui-dialog-titlebar { background: #aaa; border: 2px solid #666; }
		.ui-dialog-content  { text-align: left; color: #666; padding: 0.5em; }
		.ui-button-text { color: #00a; }
	</style>	
</head>
<body>
<div id="framecontent">
<!---************* NAVIGATION ******************--->
<div id="ITS_navcontainer">
<ul id="ITS_navlist">
<li><a href="logout.php">Logout</a></li>
<li><a href="index.php" >ITS</a></li>
<li><a href="Question.php" >Questions</a></li>
<li><a href="Profile.php">Profiles</a></li>
<li><a href="Logs.php">Logs</a></li>
<li><a>&nbsp;</a></li>
<li><a href="Query.php" id="current">Query</a></li>
</ul>
</div>
<!---******************************************--->
<div class="innertube">
<p>
<div id="deleteButton" uid="<?php echo $id;?>" class="dialogButton">Clear my<br>Profile</div>
	<div id="deleteDialog" title="Delete Account Info?" style="display:none">
	  <B>ALL</B> of your ITS records will be permanently deleted and cannot be recovered.<br>
	  <p style="background: #fff;border:1px solid #666;padding: 3px;margin-top:5px;"><code>mysql>&nbsp;<font color="green">DELETE FROM stats_<?php echo $id;?>;</font></code></p>
  </div>
  <form id="query" name="query" action="Query.php" method="get">
	<?php echo $form;?>
  </form>
</div>
</div>
<!---******************************************--->
</div>
<div id="maincontent">
<?php
//-----------------------------------------------------------//
// ACCOUNT INFO
//-----------------------------------------------------------//
//
//----***--------//
$query = 'SELECT id FROM users WHERE status="' . $section . '"';
$res = & $mdb2->query($query);
if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$users = $res->fetchCol();
//----***--------//

$tb = '<table><tr><th>User id</th><th>Content</th></tr>';
$count = 0;
for ($uid = 0; $uid <= count($users) - 1; $uid++) {
  $query = 'SELECT * FROM stats_'.$users[$uid].' WHERE question_id='.$q;
	//echo $query;
  $res = & $mdb2->query($query);
	if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
	$row = $res->fetchRow();
	//echo '<p>'.$users[$uid].' - '.$row.'<p>';
	if (empty($row)) { $con = ''; $toll = '-'; $score = '';$UPDATEquery='';}
	else 					   { 
	    $con = implode("<b> | </b>",$row); $count++; 
			$toll = abs(1-$row[4]/sqrt(29));
			if ($toll<=0.02) { $score = 100; }
			else 						 { $score = 0;   } 
	    $UPDATEquery = 'UPDATE stats_'.$users[$uid].' SET score='.$score.',comment="-2,5" WHERE question_id=677';	
			//echo $query.'<p>';
			//$UPDATEres = & $mdb2->query($UPDATEquery);
			//if (PEAR :: isError($UPDATEres)) {throw new Question_Control_Exception($res->getMessage());}
	}
	$tb .= '<tr><td>'.$users[$uid].'</td><td>'.$con.'</td><td>'.$toll.'</td><td>'.$score.'</td><td style="color:blue">'.$UPDATEquery.'</td></tr>';
}
$tb .= '</table>';
echo $tb.'<p>Question: '.$q.' answered by <b>'.$count.'/'.count($users).'</b> "<i>'.$section.'</i>" users.';
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
</div>
</body>
</html>