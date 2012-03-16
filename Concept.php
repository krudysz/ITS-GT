<?php
error_reporting(E_ALL);
include("classes/ITS_timer.php");
$timer = new ITS_timer();

require_once("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");
//include("classes/ITS_statistics.php");

session_start();
// return to login page if not logged in
abort_if_unauthenticated();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title>Concept</title>
<link rel="stylesheet" href="css/ITS.css">
<link rel="stylesheet" href="tagging/ITS_tagging.css">
<script src="js/ITS_admin.js"></script>
</head>
<body class="ITS">
<div class="main">
<?php							
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }

  //-- CHAPTERS 
  $query = 'SELECT name,code,id FROM concept3';
  $res   = & $mdb2->query($query);
  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $mdb2->disconnect();

  $concepts = $res->fetchAll();
	//echo $concepts[1].'<p>';
  //echo str_replace('\\','\\\\',$concepts[1]);
	for ($n=1;$n<=count($concepts)-1;$n++) {
	  if (empty($concepts[$n][1])){
		  echo $concepts[$n][0].'<p>'; 
		} else {
	    echo '<font color=red>'.$concepts[$n][2].'</font> '.$concepts[$n][0].'=> '.'<img class="ITS_tag" src="'.$mimetex_path.$concepts[$n][1].'"><p>';
	  }
	}
	
	//die();
$mimetex_path = '/cgi-bin/mimetex.exe?';		 
$tag1  = '<img class="ITS_tag" src="'.$mimetex_path.'x[n]=x(n/fs)">'; //array('one','two','three','four');
$tag2  = '<img class="ITS_tag" src="'.$mimetex_path.'\hat\omega=2\pi\frac{f_0}{f_s}+2\pi l">';
$tag3  = '<img class="ITS_tag" src="'.$mimetex_path.'y(t)=x[nf_s]">';
$tag4  = '<img class="ITS_tag" src="'.$mimetex_path.'\omega=2\pi f=\hat\omega f_s">';
$width = array(10,90);
$chk   = '<input type=radio name="A" value="'.chr(0+64).'">';
$data1 = array($chk,$tag1); 
$data2 = array($chk,$tag2);
$data3 = array($chk,$tag3);
$data4 = array($chk,$tag4);
		$tb1   = new ITS_table('TAGS',1,2,$data1,$width,'ITS_tag');
$tb2   = new ITS_table('TAGS',1,2,$data2,$width,'ITS_tag');
$tb3   = new ITS_table('TAGS',1,2,$data3,$width,'ITS_tag');
$tb4   = new ITS_table('TAGS',1,2,$data4,$width,'ITS_tag');
$tb    = new ITS_table('TAGS',1,4,array($tb1->str,$tb2->str,$tb3->str,$tb4->str),array(0.25,0.25,0.25,0.25),'ITS_tag');
	echo '<p><i>Select the most relevant equation associated with your answer:</i>'.$tb->str.'<br><hr>';
?>
</body>
</html>
