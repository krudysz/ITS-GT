<?php
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "Authentication.php");

session_start();
//echo "in login ...";
//exit;

// already logged in
if (isset($_SESSION['auth']) && $_SESSION['auth']->authenticated()){
	/* redirect to index page */
	echo "ITS>login.php>already logged in";
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/index.php");
echo "hello";
	exit;
}
session_destroy();

 // PUT THE URL OF YOUR PAGE HERE
 //$myurl = "http://groo.ece.gatech.edu/PHP/developers/ece2025/ITS11/php/login.php";
 $myurl = "http://www-dev3.ece.gatech.edu/ece2025/ITS/php/login_old.php";
echo $myurl;
 if (isset($_SESSION['message'])){
 		$message = $_SESSION['message'];
 }

// Determine whether a user has a valid webauth ticket.
//========================================================
 function validate_webauth_ticket($ticket, $myurl) {
//========================================================
// if ticket found, return 'userID', otherwise return 'false'
  if (!$ticket)
   return false;
  $u = "https://webauth.gatech.edu/validate?ticket="
   . rawurlencode($ticket) . "&site=" . rawurlencode($myurl);
  $r = preg_split("/\s/", file_get_contents($u));
	echo "heh";
  if ($r[0] == "yes")
   return $r[1];
  else
   return false;
 }
//========================================================
 // Reward a valid ticket by welcoming the user.
 if (!isset($_GET['ticket']))
 		$ticket = 0;
echo "heh";
 $user = validate_webauth_ticket($ticket,$myurl);
 if ($user) {
 session_register("username");
 $_SESSION['username'] = $user;

  	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/login_old.php");  //index.php
  echo("Welcome, $user!");
  exit;
 }
 // Without a message or ticket, forward the user to Webauth.
 if (!$message) {
  header("Location: https://webauth.gatech.edu/login?site=$myurl&bounce");
  //exit;
 }
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head><title>Intelligent Tutoring System - Login</title></head>
<body>
<center>
<h1>Welcome to Intelligent Tutoring System<br /></h1>
<!--  gtg111x <p> -->
<br/>
<form action="https://webauth.gatech.edu/login" method="post">
<input type=hidden name=site value="<?php echo $myurl; ?>">
<input type=hidden name=bounce value="">
<table class=login align=center cellpadding=3>
<tr><td colspan=2 align=center><b><?php echo $message; ?></b>
<tr><td class=wtop align=right><b>GT&nbsp;Account</b>
<td><input name=account type=text size=20<?php
 if (isset($account)) { echo ' value="' . $account . '"'; }
?>>
<tr><td align=right><b>Password</b>
<td><input name=password type=password size=20>
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
