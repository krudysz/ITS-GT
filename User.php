<?php
//=====================================================================//
/* User.php					
	 Author(s): Greg Krudysz
	 Last Revision: Feb-14-2012	 
*/
//=====================================================================//
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
require_once ("classes/ITS_user.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");
require_once ("classes/ITS_computeScores.php");
require_once ("classes/ITS_navigation.php");

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
    $k = new ITS_user('j');
		$list = ''; //$k->add_user();
		
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
	<link rel="stylesheet" href="css/ITS_user.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
	
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
	 /*	function doChange() {			
      var sid     = $("#sortProfile").attr("sid");
      var section = $("#sortProfile").attr("section");
      var status  = $("#sortProfile").attr("status");
      var ch      = $("#sortProfile").attr("ch");
      var orderby = $("#sortProfile option:selected").text();
			//alert(sid+'~'+orderby);
      $.get('ITS_admin_AJAX.php', { ajax_args: "orderProfile", ajax_data: sid+'~'+section+'~'+status+'~'+ch+'~'+orderby}, function(data) {
			  //alert(data);
				$("#userProfile").html(data); 
				$("#sortProfile").change(function() { doChange(); });
      });			
    }	
	 /*-------------------------------------------------------------------------*/	 
	 $('#ITS_add_user').live('click', function() {
  	    var fn = $("input[name=first_name]").val();
        var ln = $("input[name=last_name]").val();
        var st = $("input[name=username]").val();
        var de = $("#select_status option:selected").text();
				//alert(fn+'~'+ln+'~'+st+'~'+de);
        $.get('ajax/ITS_admin.php',{ ajax_args: "addUser", ajax_data: fn+'~'+ln+'~'+st+'~'+de}, function(data) {
          //alert(data);
					$('#users').append(data);  	
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
<?php echo $nav;?>
<!---*******************************************--->
<div class="innertube">
</div>
</div>
<!---******************************************--->
</div>
<div id="maincontent">
<center>
<form class="ITS_user" method="POST" action="">
<table class="ITS_user">
	 <tr>
	   <th>FIRST NAME</th><th>LAST NAME</th><th>USERNAME</th><th>DESIGNATION</th>
	 </tr>	
	 <tr> 
	 <td><input type="text" class="ITS_fields" name="first_name" width="200px" /></td>
     <td><input type="text" class="ITS_fields" name="last_name" size="15" /></td>
     <td><input type="text" class="ITS_fields" name="username" size="15" /></td>
     <td>
	 			<select class="ITS_status" id="select_status">
    			<option value="">student</option>
					<option value="">instructor</option>
					<option value="">admin</option>
				</select>
	  </td>
	</tr>
  <tr><td style="width:50px" colspan="4"><input type="button" class="ITS_button" id="ITS_add_user" name="add_user" value="Add User"/></td></tr>
	</table>
</form>
</center>
<?php
//+++++++++++++++++++++++//
//echo 'this is';		
//+++++++++++++++++++++++//
//-----------------------------------------------------------//
// ACCOUNT INFO
//-----------------------------------------------------------//
//echo $section.'--'.$sid.'--'.$status.'--'.$ch.'<p>';
echo '<div id="users">'.$list.'</div>';
//-----------------------------------------------------------//
//--- begin timer ---//
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
//------------------//
//--- FOOTER ------------------------------------------------//

//-----------------------------------------------------------//
?>
</div>
</body>
</html>
