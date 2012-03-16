<?php
header("Cache-Control: no-cache, must-revalidate"); // Must do cache-control headers
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // or IE will pull from cache 100% of time (which is really bad)

include ("classes/ITS_timer.php");

require_once ("config.php");
require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");
/*-- SCORING module -----------------------------------*/
require_once ("classes/ITS_computeScores.php");
require_once ("classes/ITS_book.php");
/*-- TAGGING module -----------------------------------*/
require_once ("tagging/ITS_tagInterface.php");
/*-- RATING module ------------------------------------*/
require_once ("rating/ITS_rating.php");
/*-----------------------------------------------------*/
require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
//$id = 670; //622;508 //
$id = $_SESSION['user']->id();
$status = $_SESSION['user']->status();

if (isset ($_POST['role'])) {$role = $_POST['role'];} 
else                        {$role = $status;}

$screen = new ITS_screen2($id, $role, $status);
//$menu    = new ITS_menu(); //echo $menu->main();
//$message = new ITS_message($screen->lab_number, $screen->lab_active);
$_SESSION['screen'] = $screen;
//------------------------------------------//
//  <script type="text/javascript" src="js/jquery-1p4.js"></script>
//	<script src="http://code.jquery.com/jquery-latest.min.js"></script>

//<!--<script type="text/javascript" src="js/ITS_jquery.js"></script>-->
//<img src="images/matching_example1.png" style="float:left;height:50px;cursor:pointer;border:1px solid #666;" onclick="ITS_showImage(this)">
//<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS</title>
	<!---->
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
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
	<style type="text/css">
  	#feedback { font-size: 1.4em; }
  	#selectable .ui-selecting { background: #FECA40; }
  	#selectable .ui-selected { background: #F39814; color: white; }
  	#selectable { list-style-type: none; margin: 0; padding: 0; }
  	#selectable li { margin: 3px; padding: 1px; float: left; width: 200px; height: 80px; font-size: 4em; text-align: center; }
  	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; margin-right: 10px; }
  	#sortable1 li, #sortable2 li { position: relative; margin: 0; padding: 0px; font-size: 1.2em; width: 100%; display: block; }
    /*#sortable2 { border: 2px solid #eef; }
		#sortable2 li { background: #eef; }*/
	/*
	input.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 2px solid #cccccc; background: #ffffff; color: #080000;}
	input.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus { border: 2px solid blue; background: #ffffff; color: blue; }
  input.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active { background: gold; border: 2px solid #666;}
  .ui-widget :active { outline: none; }
	.ui-button { width: 20px; height: 20px; }
	.ui-button-text-only .ui-button-text { padding: 0.1em; font-size: 80%; }
	*/
	/*.ui-dialog { background: #666; }*/
	.ui-dialog-titlebar { background: #999; border: none; }
/*div.ui-tabs ul li.ui-tabs-selected a {cursor: pointer !important;}*/
 </style>
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
	<script type="text/javascript" src="js/ITS_jquery.js" charset="UTF-8"></script>

<script type="text/javascript">
function UR_Start() {
	UR_Nu = new Date;
	UR_Indhold = showFilled(UR_Nu.getHours()) + ":" + showFilled(UR_Nu.getMinutes()) + ":" + showFilled(UR_Nu.getSeconds());
	document.getElementById("ur").innerHTML = UR_Indhold;
	setTimeout("UR_Start()",1000);
}
function showFilled(Value) { return (Value > 9) ? "" + Value : "0" + Value; }
</script>
<script>
	 var last, diff;
$('#contentContainer.innerHTML').change(function() { 
alert('a');
/*
    var date1 = new Date();
    var ms1 = date1.getTime();
		//alert(ms1);
    sessionStorage.setItem('TIME0',ms1); 
		*/
});
</script>	 
</head>

<body onload="UR_Start()">
<input type="hidden" id="LabData"/>
<div id="pageContainer">

<!-- MENU -------------------------------------------------->
	<div id="menuContainer"> 
	  <div class="logout"><a href="logout.php">Logout</a></div>
	<!--
	  <div class="icon" id="Minst_icon">I</div>
       <p class="ITS_instruction"><img src="images/matching_example1.png" style="position:relative;max-width:100%"></p>
		<div class="icon" id="Tag_icon">Tag</div>
		-->
		<div class="icon" id="instructionIcon" onClick="ITS_MSG(1)"><tt>?</tt></div>
		<div class="icon" id="messageIcon" onClick="ITS_MSG(1)">&para;</div>
	</div> 
<!-- myScore ---------->
    <div id="scoreContainer"><span>&raquo;&nbsp;My Scores</span></div>
  <div id="scoreContainerContent">
<?php
if ($role == 'admin') {$ch = 6;} 
else                  {$ch = 3;}

$score = new ITS_computeScores($id,$ch); //,$ch);
$_SESSION['score'] = $score;

$str = $score->renderChapterScores($ch);
echo $str;
?>
</div>
<!-- NAVIGATION ----------------------------------------------->
<!-- onclick="javascript:ITS_book_select(this)" --------------->
<p>
<div id="bookNavContainer">
<div id="chContainer">
<span id="chText">CHAPTER</span>
<ul id="chList">
<li id="active"><a href="#" class="chapter_index" name="chapter" value="1" id="current"> 1</a></li>
<li><a href="#" class="chapter_index" id="chapter_index2" name="chapter" value="2"> 2</a></li>
<li><a href="#" class="chapter_index" id="chapter_index3" name="chapter" value="3"> 3</a></li>
<?php

//onclick="javascript:ITS_chapter_select(this)"
if ($role == 'admin') {
	echo '<li><a href="#" class="chapter_index" id="chapter_index4" name="chapter" value="4">4</a></li>';
	echo '<li><a href="#" class="chapter_index" id="chapter_index5" name="chapter" value="5">5</a></li>';
	echo '<li><a href="#" class="chapter_index" id="chapter_index6" name="chapter" value="6">6</a></li>';
}
?>
</ul>
</div>
<?php
//-- TEST -------------------------------------------------->
//$s = new ITS_computeScores($id);
//$str = $s->renderLabScores();
//echo $str;
?>
	</div>
	<div id="page">
<!-- CONTENT ----------------------------------------------->
<?php

//echo $screen->main();
//--- resources ---//
//$arr = array(2,"",NULL);
//echo array_sum($arr);die('done');
$ch = 1;
$meta = 'image';

/*
$x = new ITS_book('dspfirst',$ch,$meta,$mimetex_path);
$o = $x->main();
echo $o.'<p>';
*/
//----------------//
?>
<div id="accor" "ITS_ghost">
	<div "accor">
		<h3 class="hel" id="headerQuestion"><a href="#" class="headering">Questions: <span><?php echo 'Chapter '.$ch;?></span></a></h3>
		<div class="ITS_ghost">	
<?php
echo $screen->main();
//--- resources ---//
$ch = 1;
$meta = 'image';
//----------------//
?>
</div>
</div>
	<div "accor">
		<h3 id="headerReview"><a href="#" class="header">Review: <span><?php echo 'Chapter '.$ch;?></span></a></h3>
		<div class="ITS_ghost">
		<div id="reviewContainer">
			<?php echo $screen->reviewMode(1,0);?>
			</div>
		</div>
	</div>	
</div>

</div>
<p>
<!-- FOOTER ----------------------------------------------->
<div id="footerContainer"><p>		
		<ul id="navlist">
		  <li><code>ITS v.111</code></li>
<?php
if ($status == 'admin') {
	$opt_arr = array (
		'admin',
		'student'
	);
	$option = '';
	for ($o = 0; $o < count($opt_arr); $o++) {
		if ($role == $opt_arr[$o]) {
			$sel = 'selected="selected"';
		} else {
			$sel = '';
		}
		$option .= '<option value="' . $opt_arr[$o] . '" ' . $sel . '>' . $opt_arr[$o] . '</option>';
	}
	$user = '<li><form id="role" name="role" action="index.php" method="post">' .
	'<select class="ITS_select" name="role" id="myselectid" onchange="javascript:this.submit()">' .
	$option .
	'</select>' .
	'</form></li>';
	//$user = $status;
	$spacer = '&nbsp;<b><font color="silver">&diams;</font></b>&nbsp;';

	if ($role == 'admin') {
		$admin_list = '<p>' .
		'<li><a href="REVISIONS.html" style="color:#666">Revisions</a></li>' .
		'<li> ' . $spacer . ' <a href="dSPFirst.php" style="color:#666">eDSPFirst</a></li>' .
		'<li> ' . $spacer . ' <a href="survey1.php" style="color:#666">Spring 2010 Survey</a></li> ' .
		'<li> ' . $spacer . ' <a href="Question.php" style="color:#666">Questions</a></li> ' .
		'<li> ' . $spacer . ' <a href="Profile.php?ch=1" style="color:#666">Profiles</a></li> ' .
		'<li> ' . $spacer . ' <a href="DATA/DATA.php" style="color:#666">DATA</a></li> ';
	} else {
		$admin_list = '';
	}
	$ftr = '&bull; ' . $user . $admin_list;
} else {
	$ftr = '&bull; ' . $status . ' <p>';
}
echo $ftr;
?>
    </ul>
	</div>
	<span style="float:right;padding:1.5em 0"><font id="ur" size="2" face="Monospace, sans-serif" color="#666666"></font></span>
</div>
<script type="text/javascript">
    $(function() {
      var stop = false;
      $("#hel h3").click(function(event) {
      	if (stop) {
      		event.stopImmediatePropagation();
      		event.preventDefault();
      		stop = false;
      	}
      });
      $("#accor").accordion({header: "> div > h3"}).sortable({axis: "y",handle: "h3",stop: function(event, ui) {stop = true;}});
      $("#accor").accordion({autoHeight: false,navigation: true}); //active: 1,
      $("#selectable").selectable();
      $("#sortable1").sortable({ connectWith: '.connectedSortable'}).disableSelection();
			$("input.check").button();  
});
</script>
</body>
</html>
