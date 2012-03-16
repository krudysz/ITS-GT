<?php
//--- begin timer ---//
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
//------------------//
$path = '../';
require_once ($path."config.php");
require_once ($path.INCLUDE_DIR . "common.php");
require_once ($path.INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

$id     =   $_SESSION['user']->id();
$status =   $_SESSION['user']->status();
$info   = & $_SESSION['user']->info();
//------------------------------------------// 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>PHP eval</title>
	<!---->
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_users.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
    <link rel="stylesheet" href="css/ITS_search.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
	
	<link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
	<!--[if IE 6]>
	    <script src="http://code.jquery.com/jquery-latest.js"></script>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
	<script type="text/javascript">
	/*
	$(function() {
      $(".ITS_select").change(function() { document.profile.submit(); });
			$("#select_class").buttonset();
  });*/
	/*-------------------------------------------------------------------------*/
  $(document).ready(function() { 
	 /*-------------------------------------------------------------------------*/ 
	 $('#ITS_add_user').live('click', function() {
  	    var fn = $("input[name=first_name]").val();
        var ln = $("input[name=last_name]").val();
        var st = $("input[name=username]").val();
        var de = $("#select_status option:selected").text();
				//alert(fn+'~'+ln+'~'+st+'~'+de);
        $.get('ITS_admin_AJAX.php',{ ajax_args: "addUser", ajax_data: fn+'~'+ln+'~'+st+'~'+de}, function(data) {
          //alert(data);
					$('#users').append(data);  	
        });
	 });
	 /*-------------------------------------------------------------------------*/		 
  });
    </script>
            <script type="text/javascript">
            function getfocus() {document.getElementById("ITS_search_box").focus()}       
        </script>	
  	<style>
  	     p { margin: 10px; padding: 5px;font-family:"Times New Roman", Times, serif; font-size: 120%;}
         table.key { margin: 5% 0;text-align: center; font-size: 200%; font-weight: bold; font: monospace;}
         table.key td { padding: 0.5em; }
         .out { color: #2A2; }
         .ex { text-align: left; color:#666; margin-left: 40%; }
         .ex li { padding: 0.25em; }
	</style>
</head>
<body onload="getfocus()">
<div id="framecontent">
<!---************* NAVIGATION ******************--->
<div id="ITS_navcontainer">
<ul id="ITS_navlist">
<li><a href="logout.php">Logout</a></li>
<li><a href="index.php">ITS</a></li>
<li><a href="Question.php">Questions</a></li>
<li><a href="Profile.php">Profiles</a></li>
<li><a href="User.php">Users</a></li>
<li><a style="margin-right:10%" href="/eval/index.php" id="current">PHP EVAL</a></li>
</ul>
</div>
<!---******************************************--->
<div class="innertube">
</div>
</div>
<!---******************************************--->
</div>
<div id="maincontent">
<center>
            <form id="ITS_search" action="index.php" method="post">
                <input id="ITS_search_box" type="text" name="keyword">
            </form>
            <?php
            $intro = '<p>Enter a PHP math expression.<ul class="ex"><li>sqrt(pow(3,2) + pow(4,2))</li><li>fmod(7.3,1.3)</li></ul></p>';
            if (isset($_POST['keyword'])) {
                if ($_POST['keyword']) {
                    $keyword = $_POST['keyword'];
					eval("\$php=" . $keyword . ";" ); 
					
					if (is_null($php)) {
						echo '<p>Unable to evaluate the expression:<br><p style="color:red">'.$keyword.'</p></p>'.$intro;
					}
					else {
						$out = '<table class="key">'.
									'<tr>'.
										'<td style="color:#999">'.$keyword.'</td><td> = </td><td class="out">'.$php.'</td>'.
									'</tr>';
					    echo '<center>'.$out.'</center>';
					}
                    } else {
                        echo $intro;
                    }
                     
                } else {
					echo $intro;
				}
//-----------------------------------------------------------//
//--- begin timer ---//
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
//------------------//
//--- FOOTER ------------------------------------------------//

//----------------------------------------------------------//

?>
</center>
</div>
</body>
</html>
