<?
 // PUT THE URL OF YOUR PAGE HERE
 $myurl = "http://its.vip.gatech.edu/index.php";

 // Determine whether a user has a valid webauth ticket.
 function validate_webauth_ticket($ticket, $myurl) {
  if (!$ticket)
   return false;
  $u = "https://webauth.gatech.edu/validate?ticket=" . rawurlencode($ticket)
   . "&site=" . rawurlencode($myurl);
  $r = preg_split("/\s/", file_get_contents($u));
	
  if ($r[0] == "yes") // r[0] = "yes"/"no, r[1] = username (e.g. gtnnn)
   return $r[1];
  else
   return false;
 }

 // Redirect users to webauth until they present a valid ticket.
 $user = validate_webauth_ticket($_GET['ticket'], $myurl);
 if (!$user) {
  header("Location: https://webauth.gatech.edu/login?site="
   . rawurlencode($myurl));
  exit;
 }

 // User has been authenticated, so show them our content.
 echo("Welcome to the WebAuth test page, " . htmlspecialchars($user) . ". <p>");
?>

