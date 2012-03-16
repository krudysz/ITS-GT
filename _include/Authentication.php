<?php
//echo ini_get('include_path').'<p>';
//echo get_include_path();
//require_once("MDB2.php");
//ini_set('include_path',"C:\wamp\bin\php\php5.3.0\pear");
//echo 'in Authentication.php<p>';
//echo $MDB2_path.'MDB2.php<p>';

require_once($MDB2_path.'MDB2.php');

class AuthException extends Exception {}
class Authentication {
private $_username = '';
private $_password = '';
private $_authenticated = false;

//==============================================================================
public function __construct($username, $password){
//==============================================================================
	$this->_username = $username;
	$this->_password = $password;
}
//==============================================================================
public function check_auth(){
//==============================================================================
	global $db_dsn, $db_table_users;
	
	//die($db_dsn);
	$mdb2 =& MDB2::connect($db_dsn);
	//var_dump($mdb2); die();
	if (PEAR::isError($mdb2)){
		die('My Message:'.$mdb2->getMessage());
	}
	$query = "SELECT * FROM $db_table_users WHERE username='$this->_username'";

//echo "in check auth <p>".$query."<p>"; die();
	$res =& $mdb2->query($query);
	
//echo "<p>check_auth: query: ".$query."<p>".$res->numRows()."<p>";
//die();

	if (PEAR::isError($res)) {
		die($res->getMessage());
	}

	if ($res->numRows() == 0){
		$this->_authenticated =  false;
	}
	else{
		$this->_authenticated = true;
	}
	
	$res->free();
	$mdb2->disconnect();
	
	return $this->_authenticated;
}
//==============================================================================
public function update_passwd($old_passwd, $new_passwd){
//==============================================================================
	// TODO: more specific error message?
	// check empty new password. and old password not match
	if (!$new_passwd || $old_passwd != $this->_password){
		return false;
	}
	$success = true;

	global $db_dsn, $db_table_user;
	$mdb2 =& MDB2::connect($db_dsn);
	if (PEAR::isError($mdb2)){
		die($mdb2->getMessage());
	}

	$mdb2->query("UPDATE $db_table_user SET password='$new_passwd' WHERE username='$this->_username'");
	
	if (PEAR::isError($res)) {
		die($res->getMessage());
	}

	// check UPDATE faild
	if ($mdb2->affectedRows() == 0){
		$success = false;
	}
	
	$mdb2->disconnect();
	
	if ($success){
		$this->_password = $new_passwd;
	}
	
	return $success;
}
//==============================================================================
public function authenticated(){
//==============================================================================
	return $this->_authenticated;
}
//==============================================================================
public function username(){
//==============================================================================
	return $this->_username;
}
//==============================================================================
}
?> 
