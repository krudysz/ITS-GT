<?php
error_reporting(E_ALL);
require_once("config.php");
session_start();
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Intelligent Tutoring System - Logout</title>
<link rel="stylesheet" href="css/login.css">
</head>
<body onload="if (window != window.top){top.location.href = location.href;}">
<center>
<p>
<h1>I<font color="gray">ntelligent</font> T<font
color="gray">utoring</font> S<font color="gray">ystem</font><br /></h1>
<p>
<?php
$str = $mimetex_path.'\Large\Longleftrightarrow';
echo '<img src="'.$str.'" alt="o">';
?>
<p>
<form>
<table class="login">
<tr class="login"><td class="wbot"><b>You have been logged out successfully.</b></tr>
<tr><td class="wtop">
<tr><td>&nbsp;</td>
<tr><td align="center"><a href="index.php" target="_top">Click here to Login</a>
</table>
</form>
</center>
</body>
</html>
