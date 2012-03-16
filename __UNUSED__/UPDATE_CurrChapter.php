<?php
error_reporting(E_ALL);
require_once("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

//session_start();
//==============================================================================
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

$query = 'SELECT id FROM users WHERE status="Fall_2010"';   
$res =& $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$users = $res->fetchCol();

for ($u=0; $u <= count($users)-1; $u++){
//----***--------//
  $query = 'SELECT question_id,category FROM stats_'.$users[$u].',webct WHERE current_chapter IS NULL AND question_id=webct.id';    
  $res =& $mdb2->query($query);
  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $qdata = $res->fetchAll();
	
  for ($q=0; $q <= count($qdata)-1; $q++){
		//preg_match('/Warm-up*/',$qdata[$q][1],$warmup);
		
		//echo count($k).'<p>';
	  //$chNum = $qdata[$q][1];
	  $chNum = preg_replace('/[^1-9]/','',$qdata[$q][1]);
		
		if ($chNum) {
      $query = 'UPDATE stats_'.$users[$u].' SET current_chapter='.$chNum.' WHERE question_id='.$qdata[$q][0];
      echo $query.'<br>';
    	$res =& $mdb2->query($query);
    	if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
		}
	}
	echo '<p>';
	//----***--------//
}
echo '# finished';
//==============================================================================
?>
