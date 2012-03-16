<?php
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "Authentication.php");

session_start();

// already logged in
if (isset($_SESSION['auth']) && $_SESSION['auth']->authenticated()){
	/* redirect to index page */
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/index.php");
	exit;
}
session_destroy();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<title>Intelligent Tutoring System - Login</title>
</head>

<body>
<center>
<h1>Welcome to Intelligent Tutoring System</h6><br /></h1><p>
<?php
if (isset($_REQUEST['ask_info'])){
	echo 'Please enter username and password<br />';
}
elseif (isset($_REQUEST['failed'])){
	echo 'Login failed. Please try again<br />';
}
else{
	echo 'Please Login<br />';
}
?>

<br />
<form method="post" action="auth.php">
Username: <input type="text" name="username" /><br />
Password: <input type="password" name="password" /><br />
<input type="submit" value="Login" />
</center>
</form>
</body>
</html>
