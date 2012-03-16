<?php
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

session_start();
//==============================================================================
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

$query = 'SELECT id FROM users WHERE status="Spring_2010"';  

$res =& $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$users = $res->fetchCol();

for ($u=0; $u <= count($users)-1; $u++){
//----***--------//
  $query = 'UPDATE stats_'.$users[$u].' SET comment="1,3,4,2,5,6" WHERE question_id=1211';
  //echo $query.'<br>';
	$res =& $mdb2->query($query);
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
	//----***--------//
  $query = 'UPDATE stats_'.$users[$u].' SET comment="2,1,3,4,5" WHERE question_id=1212';
  //echo $query.'<br>';
	$res =& $mdb2->query($query);
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
	//----***--------//
	$query = 'UPDATE stats_'.$users[$u].' SET comment="2,5,6,1,7,4,3" WHERE question_id=1214';
  //echo $query.'<br>';
	$res =& $mdb2->query($query);
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
	//----***--------//
	$query = 'UPDATE stats_'.$users[$u].' SET comment="2,4,3,1,5" WHERE question_id=1220';
  //echo $query.'<br>';
	$res =& $mdb2->query($query);
	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}	
	//----***--------//
}
echo 'finished';
//==============================================================================
?>
