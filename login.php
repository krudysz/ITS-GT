<?php 
/*
login.php - LOCAL login for ITS 

Author(s): Greg Krudysz
Last Update: Jul-26-2011
=============================================*/
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "Authentication.php");

session_start();

// already logged in
if (isset($_SESSION['auth']) && $_SESSION['auth']->authenticated()){
	/* redirect to index page */
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/screen.php");
	exit;
}
session_destroy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<title>Intelligent Tutoring System - Login</title>
<link rel="stylesheet" href="css/login.css">
<script>
function sf(){document.f.username.focus();}
function submitf(obj){
  obj.innerHTML = 'loading ...';
  document.f.submit();
}
</script>
</head>

<body onload="sf()">
<center>
<p>
<h1>I<font color="gray">ntelligent</font> T<font
color="gray">utoring</font> S<font color="gray">ystem</font><br /></h1>
<p>
<img src="/cgi-bin/mimetex.exe?\Large\Longleftrightarrow" alt=".">
</p>
<?php
if (isset($_REQUEST['ask_info'])){
  $msg = 'Please enter username and password<br />';
}
elseif (isset($_REQUEST['failed'])){
  $msg = 'Login failed. Please try again<br />';
}
else{
  $msg = 'Please Login<br />';
}
//<input type="submit" value="Login">
?>
<form action="auth.php" method="post" name="f">
<table class="login" align="center" cellpadding="3" cellspacing="0">
  <tr class="login"><td class="wbot" colspan="2" align="center"><b><?php echo $msg; ?></b>
  <tr class="login"><td align="right"><b>GT&nbsp;Account</b>
  <td><input class="login" name="username" type="text" value="gte269x">
  <tr class="login"><td align="right"><b>Password</b>
  <td><input class="login" name="password" type="password" value="gte269x">
  <tr><td class="wtop" colspan="2" align="center">
  <tr><td colspan="2" align="center">
</table>
<p>
<div class="login" onClick="submitf(this)">Login</div>
</p>
</form>
<table style="border:1px solid #999" class="ITS">
<tr><td>Greg</td><td>gte269x</td></tr>
<tr><td>admin</td><td>iaia3</td></tr>
<tr><td>student</td><td>isis3</td></tr>
</table>
</center>
</body>
</html>
