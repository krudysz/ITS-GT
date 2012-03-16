<?php
// AUTHORIZATION
// 1. Obtain a message from webauth
// 2. Validate a ticket with 'validate_webauth_ticket' function
// 3. Register user and send session info to auth.php for authentication
// AUTHENTICATION
// 4. see: auth.php & _include/Authentication.php files

error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "Authentication.php");
 
session_start();

//-------------------------------------------------------------//
// already logged in
//-------------------------------------------------------------//
if (isset($_SESSION['auth']) && $_SESSION['auth']->authenticated()){
	// redirect to index page

	echo "ITS>login.php>already logged in";
die("login: already logged in");
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/index.php"); //index.php
	exit;
}
//-------------------------------------------------------------//
session_destroy();

// PUT THE URL OF LOGIN PAGE
$myurl = "http://its.vip.gatech.edu/login.php";

 //-------------------------------------------------------------//
 // 3. Reward a valid ticket by registering user and sending to auth.php
 //-------------------------------------------------------------//
 if (!isset($_GET['ticket']))   {  $ticket = 0;               }
 else                           {  $ticket = $_GET['ticket']; }

 $user = validate_webauth_ticket($ticket,$myurl);
 
 if ($user) {
 session_register("username");
 $_SESSION['username'] = $user;
 
  	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/auth.php");
  exit;
 }
 //-------------------------------------------------------------//
 // 1. Without a message, forward the user to Webauth.
 //-------------------------------------------------------------//
 $message = $_GET['message'];
 if (!$message) {
  header("Location: http://webauth.gatech.edu/login?site=$myurl&bounce");
  exit;
 }
 //-------------------------------------------------------------//
 //========================================================
 // 2. Determine whether a user has a valid webauth ticket.
 function validate_webauth_ticket($ticket, $myurl) {
 //========================================================
 // if ticket found, return 'userID', otherwise return 'false'
  if (!$ticket)
   return false;

  $u = "https://webauth.gatech.edu/validate?ticket="
   . rawurlencode($ticket) . "&site=" . rawurlencode($myurl);
  $r = preg_split("/\s/", file_get_contents($u));

  if ($r[0] == "yes"){  return $r[1];  }
  else               {  return false;  }
 }
//========================================================
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head><title>Intelligent Tutoring System - Login</title>
<link rel="stylesheet" href="css/login.css">
<script>
<!--
function sf(){document.f.account.focus();}
// -->
</script>
</head>
<body onload="sf()">
<center>
<h1>I<font color="gray">ntelligent</font> T<font
color="gray">utoring</font> S<font color="gray">ystem</font><br /></h1>
<p>
<?php
$str = $mimetex_path.'\Large\Longleftrightarrow';
echo '<img src="'.$str.'" alt="o">';
?>
<form action="https://webauth.gatech.edu/login" method="post" name="f">
<input type=hidden name=site value="<?php echo $myurl; ?>">
<input type=hidden name=bounce value="">
<table class=login align=center cellpadding=3>
<tr bgcolor="eeeeee"><td colspan=2 align=center><b><?php echo $message; ?></b>
<tr bgcolor="eeeeee"><td class=wtop align=right><b>GT&nbsp;Account</b>
<td><input name=account type=text style="width:150px"<?php
 if (isset($account)) { echo ' value="' . $account . '"'; }
?>>
<tr bgcolor="eeeeee"><td align=right><b>Password</b>
<td><input name=password type=password style="width:150px">
<tr><td colspan=2 align=center><input type=submit value="Login">
<tr><td class=wtop colspan=2 align=center
><input name=remember type=checkbox<?php
 if (isset($remember)) { echo ' checked'; }
?>>
<b>Remember my account<br>on this machine and browser.</b>
<br><a href="https://webauth.gatech.edu/about.html#remember"><i>Why to be careful
 using this feature</i></a>
</table></form>
</body></html> 
