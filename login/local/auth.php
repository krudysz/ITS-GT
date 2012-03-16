<?php
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "Authentication.php");
require_once(INCLUDE_DIR . "User.php");
//require_once(INCLUDE_DIR . "common.php");

session_start();
//var_dump($_POST);
//die("auth: begin");
$user = $_POST['username'];
//-------------------------------------------------------------------
// already logged in
//-------------------------------------------------------------------
if (isset($_SESSION['auth']) && $_SESSION['auth']->authenticated()){
	// redirect to index page 

	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/index.php");
	print "ITS>auth.php> already logged in";
	exit;
}
//-------------------------------------------------------------------
$_SESSION = array();
session_destroy();		// release resources if not already logged in.
//-------------------------------------------------------------------
// username or passowrd doesn't exist in $_POST
//-------------------------------------------------------------------
if (!isset($user)){ 			//|| !isset($_POST['password'])){
//echo "<p>user: ".$user."<p>";
//die();
	/* redirect to login page */
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/login.php");
	print "ITS>auth.php>username or passowrd is not set in $_SESSION";
	exit;
}
//-------------------------------------------------------------------
// username or passowrd are empty
//-------------------------------------------------------------------
if (empty($user)){ 		     //|| empty($_POST['password'])){
	// redirect to login page
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/login.php?ask_info=true");
	print "ITS>username or passowrd empty in $_SESSION";
	exit;
}
//-------------------------------------------------------------------

$auth = & new Authentication($user,"");
//die("aut: 1");
//-------------------------------------------------------------------
// login in failed
//-------------------------------------------------------------------
if (!$auth->check_auth()){	
	//-- redirect to login page --//
	session_start();
	session_register("message");
	$_SESSION['message'] = "You are unauthorized to access ITS <br> Please contact ITS administrator <br><H4><tt>krudysz@ece.gatech.edu</tt></H4>";
	
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/login.php?failed=true");
	print "ITS>auth - login failed";
	exit;
}
//-------------------------------------------------------------------
else{	// login successful
//-------------------------------------------------------------------
	session_start();
	
	$_SESSION = array();	// get a clean $_SESSION
	$_SESSION['auth'] = $auth;
	
	$_SESSION['user'] = new User($auth->username());	// auth->username <-> user id
	
	/* redirect to index page */
	header("Location: http://" . $_SERVER['HTTP_HOST']
			. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
			. "/index.php");
	exit;
}
?> 
