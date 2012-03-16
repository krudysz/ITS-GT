<?php
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");
//require_once("MDB2.php");

session_start();
//==============================================================================
// connect to database

$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

$query = 'SELECT id,first_name,last_name,username,status FROM users WHERE status="Spring_2011"';  
$res =& $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$users = $res->fetchAll();

//echo '<p>'.$users[0][0].',"'.$users[0][2].','.$users[0][1].'",'.$users[0][3]

for ($u=0; $u <= count($users)-1; $u++){
  //echo $users[$u];//die();
  $tr = new ITS_statistics($users[$u][0],'Fall_2009','student');
	$record = $tr->get_record();
	$query = 'INSERT INTO records VALUES('.$users[$u][0].',"'.$users[$u][2].','.$users[$u][1].'","'.$users[$u][3].'"';
	// for each question, write records to file
	for ($q=0; $q <= count($record)-1; $q++){
	  $query = $query.','.$record[$q];
		//echo '<p>'.$q.'. '.$record[$q].'<p>';	
	}
	$query = $query.')';
	//echo '<p>'.$query.'<p>';die();
  $res =& $mdb2->query($query);
	if (PEAR::isError($res)) {throw new Grading_Exception($res->getMessage());}
}
//==============================================================================
?>
