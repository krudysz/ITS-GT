<?php

 // PUT THE URL OF YOUR PAGE HERE
 //$myurl = "http://groo.ece.gatech.edu/PHP/developers/ece2025/loginBounce.php";
$myurl = "http://www-dev3.ece.gatech.edu/ece2025/ITS/php/loginBounce.php";

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
	
  if ($r[0] == "yes")
   return $r[1];
  else
   return false;
 }
//========================================================

 // Reward a valid ticket by welcoming the user.
 if (!isset($_GET['ticket'])){
 		$ticket = 0;
 }else{
 $ticket = $_GET['ticket'];
 }

 $user = validate_webauth_ticket($ticket,$myurl);
 if ($user) {
  echo("Welcome, $user!");
  exit;
 }
 // Without a message or ticket, forward the user to Webauth.
  $message = $_GET['message'];
 if (!$message) {
  header("Location: https://webauth.gatech.edu/login?site=$myurl&bounce");
  exit;
 }
 ?>
	
<html><body>
<form action="https://webauth.gatech.edu/login" method=post>
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
<br><a href="/about.html#remember"><i>Why to be careful
 using this feature</i></a>
</table></form>
</body></html>




