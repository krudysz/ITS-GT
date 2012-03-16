<?php
/*
Author(s): Greg Krudysz
Date: Sep-29-2011
---------------------------------------------------------------------*/

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate");            // Must do cache-control headers 
header("Pragma: no-cache");

require_once("config.php");
require_once($MDB2_path.'MDB2.php');
include("classes/ITS_table.php");
include("classes/ITS_configure.php");
include("classes/ITS_question.php");
include("classes/ITS_screen2.php");
include("classes/ITS_users.php");

//include("jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js");
session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state;

$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}


$qid 		= $_POST['qid'];
$server_path = '/var/www/ITS/';
$image_dir  = '/images/';
$ques_dir   = $image_dir.'question/'.$qid.'/';
$files_dir  = 'ITS_FILES/'.$ques_dir;
$filename   = basename($_FILES['ITS_image']['name']);
$uploadfile = $server_path . $files_dir . $filename;
echo '<p><font color="blue">'.$uploadfile.'</font></p>';

// dir check
if (file_exists($files_dir)) {
    echo "<p>The dir: $files_dir exists</p>";
} else {
    echo "<p>The dir: $files_dir does not exist</p>";
    mkdir($files_dir,0777);
}

echo '<pre>';
if (move_uploaded_file($_FILES['ITS_image']['tmp_name'], $uploadfile)) {
       echo '<font color="green">File successfully uploaded.</br></font>';} 
else { echo '<font color="red">File upload failed !!<br></font>'; }

print "</pre>";

//var_dump($_POST);			
//echo "Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/Question.php?qNum=".$qid;
				
$query = 'UPDATE webct SET image="'.$ques_dir.$filename.'" WHERE id='.$qid;  //die($query);
//echo '<p>'.$query.'</p>';
$res   = & $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}

//-----------------------------------------------//
$mdb2->disconnect();


		//* redirect to Question page *//
		/*
		header("Location: http://" . $_SERVER['HTTP_HOST']
				. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
				. "/Question.php?qNum=".$qid); */
?>
