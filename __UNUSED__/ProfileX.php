<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); // Must do cache-control headers 
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
	<script type="text/javascript">
  $(document).ready(function() { 
     $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
		/*-------------------------------------------------------------------------*/
		 $("#deleteButton").click(function(){ 		
		  var uid = $(this).attr("uid");
		  // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		  //$( "#dialog:ui-dialog" ).dialog( "destroy" );
	    $( "#deleteDialog" ).dialog({
    			resizable: false,
    			height:240,
    			modal: true,
    			buttons: { "Delete Now": function() { $( this ).dialog( "close" );
    									$.get('ITS_admin_AJAX.php', { ajax_args: "deleteDialog", ajax_data: uid}, function(data) {
                			  //alert(data); 
												//$('#contentContainer').html(data); 
            					});
					         },
        				           Cancel: function() { $( this ).dialog( "close" ); }
        			     }
       }); 
		});
	 /*-------------------------------------------------------------------------*/
  });
		$(function() {
      $(".ITS_select").change(function() { document.profile.submit(); });
			$( "#select_class" ).buttonset();
  });
  </script>
  <style>
	  #select_class { margin-top: 2em; }
	</style>
</head>
<body>
<p>
<?php
		//--- begin timer ---//
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
    //------------------//
		// SLOW: $score = new ITS_computeScores($sid,$status,$ch_max);
    // if ($role == 'admin') {$ch = 13; } 
    // else                  {$ch = 7;  }
		$id = 2;
		$ch = 13;
		$ch_max = $ch;
    $chArr = range(1,$ch);
		$score = new ITS_computeScores($id,$ch,$chArr); //,$ch);
    $str   = $score->renderChapterScores($ch_max);
    //--- begin timer ---//
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime);  
    echo "This page was created in ".$totaltime." seconds";
    //------------------//
		?>
		<!-- myScore ---------->
    <div id="scoreContainer"><span>&raquo;&nbsp;User Scores</span></div>
    <div id="scoreContainerContent"><?php echo $str;?></div>
<!--------------------->

</body>
</html>