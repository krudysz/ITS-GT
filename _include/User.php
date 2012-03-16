<?php
class UserException extends Exception{};

class User
{
private $_chap = '';
private $_info = '';
private $_id = '';
private $_first_name = '';
private $_last_name = '';
private $_status = '';
//==============================================================================
public function __construct($username){
//==============================================================================
	global $db_dsn, $db_table_users, $db_table_user_state;

	$mdb2 = MDB2::connect($db_dsn);
	if (PEAR::isError($mdb2)){ die($mdb2->getMessage()); }
	
	$res = $mdb2->query("SELECT id, first_name, last_name, username, status FROM $db_table_users WHERE username='$username'");
	if (PEAR::isError($res)) { die($res->getMessage()); }
	if ($res->numRows() == 0){
		die("Cannot get user information for: $username");
	}
	$this->_info = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	$this->_id = $this->_info['id'];
	$this->_first_name = $this->_info['first_name'];
	$this->_last_name = $this->_info['last_name'];
	$this->_status = $this->_info['status'];
	$res->free();
	$mdb2->disconnect();
}
//==============================================================================
public function info() {
//==============================================================================
 return $this->_info; }
//==============================================================================
public function first_name() {
//==============================================================================
 return $this->_first_name; }
//==============================================================================
public function id() {
//==============================================================================
 return $this->_id; }
//==============================================================================
public function status()
//==============================================================================
{ return $this->_status; }
}
?>

