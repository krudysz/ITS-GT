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

$id     = $_SESSION['user']->id();
$status = $_SESSION['user']->status();
$info   = & $_SESSION['user']->info();
//------------------------------------------// 
if ($status == 'admin') {
    //------- CLASS -------------//
    $ch_max = 3;
    
    /*
    if (isset($_GET['class']))   { $section = $_GET['class']; }
    else                         { $section = $status;        }
    
    $class_arr = array('admin','Fall_2010');
    $class = '<div name="class" id="select_class">Class: ';
    for ($cs=0; $cs<count($class_arr); $cs++) {
          if ($section == $class_arr[$cs]) { $sel = ' checked '; }
          else                             { $sel = '';          }
    			
    			$class .= '<input type="checkbox" name="class" id="check'.$cs.'" '.$sel.'/><label for="check'.$cs.'">'.$class_arr[$cs].'</label>';
    //<option value="'.$cs.'" '.$sel.'>'.$class_arr[$cs].'</option>';
    }
    $class .= '</div>';
    */
    //------- CHAPTER -------------//
    $ch_max = 3;
    if (isset($_GET['ch']))   { $ch = $_GET['ch']; }
    else                      { $ch = 1;           }
    
    $chapter = 'Chapter #<select class="ITS_select" name="ch" id="select_chapter" onchange="javascript:this.submit()">';
    for ($c=1; $c<=$ch_max; $c++) {
          if ($ch == $c) { $sel = 'selected="selected"'; }
          else                { $sel = '';                    }
          $chapter .= '<option value="'.$c.'" '.$sel.'>'.$c.'</option>';
    }
    $chapter .= '</select>';
    
    //------- USER ---------------//
    if (isset($_GET['sid']))  { $sid = $_GET['sid']; }
    else                      { $sid = $id;          }
    $usertable  = 'stats_'.$sid;
    
    $mdb2 =& MDB2::connect($db_dsn);
    $query = 'SELECT id,last_name,first_name,status FROM users WHERE status IN ("admin","Fall_2010") ORDER BY last_name';
    $res   = & $mdb2->query($query);
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
    $class = preg_replace('/_/',' ',$current_user);
    $form = $chapter.' &nbsp; '.$users.' &nbsp; '.$class;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Profile</title>
	<!---->
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
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
  <script type="text/javascript" src="js/jquery-1p4.js"></script>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript">
	$(function() {
      $(".ITS_select").change(function() { document.profile.submit(); });
			$( "#select_class" ).buttonset();
  });
  </script>
  <style>
	  #select_class { margin-top: 2em; }
	</style>
<style type="text/css">

body{
margin:0;
padding:0;
line-height: 1.5em;
}

b{font-size: 110%;}
em{color: red;}

#maincontainer{
width: 840px; /*Width of main container*/
margin: 0 auto; /*Center container on page*/
}

#topsection{
background: #EAEAEA;
height: 90px; /*Height of top section*/
}

#topsection h1{
margin: 0;
padding-top: 15px;
}

#contentwrapper{
float: left;
width: 100%;
}

#contentcolumn{
position: relative;
margin: 0 190px 0 180px; /*Margins for content column. Should be "0 RightColumnWidth 0 LeftColumnWidth*/
}

#leftcolumn{
float: left;
width: 180px; /*Width of left column in pixel*/
margin-left: -840px; /*Set margin to that of -(MainContainerWidth)*/
background: #C8FC98;
}

#rightcolumn{
float: left;
width: 190px; /*Width of right column*/
margin-left: -190px; /*Set left margin to -(RightColumnWidth)*/
background: #FDE95E;
}

#footer{
clear: left;
width: 100%;
background: black;
color: #FFF;
text-align: center;
padding: 4px 0;
}
#footer a{
color: #FFFF80;
}
.innertube{
margin: 10px; /*Margins for inner DIV inside each column (to provide padding)*/
margin-top: 0;
}

</style>
</head>
<body>
<div id="maincontainer">

<div id="topsection"><div class="innertube"><h1>CSS Fixed Layout #3.1- (Fixed-Fixed-Fixed)</h1></div></div>

<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">
<?php
$tr = new ITS_statistics($sid,'Fall_2010',$status); //Fall_2009
$tr->render_profile2($ch);
?>
 <script type="text/javascript">filltext(10)</script></div>
</div>
</div>

<div id="footer"><a href="http://www.dynamicdrive.com/style/">Dynamic Drive CSS Library</a></div>

</div>
</body>
</html>

