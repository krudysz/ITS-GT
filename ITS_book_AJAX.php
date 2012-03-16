<?php
/*
ITS_book - script for AJAX ITS_book class

Author(s): Greg Krudysz
Date: Jul-12-2011        
---------------------------------------------------------------------*/
require_once("config.php");
require_once($MDB2_path.'MDB2.php');
include("classes/ITS_table.php");
include("classes/ITS_configure.php");
include("classes/ITS_question.php");
include("classes/ITS_statistics.php");
include("classes/ITS_screen.php");
require_once("classes/ITS_book.php");

session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state, $files_path;

//---------------------------------------//
// AJAX
//---------------------------------------//
//-- Get AJAX arguments
$args = preg_split('[,]',$_GET['ajax_args']);

//-- Get AJAX user data
$Data = rawurldecode($_GET['ajax_data']);
// preprocess before SQL
$Data = str_replace ("'","&#39;",$Data);
//$Data = nl2br($Data);
$action = $args[0];

/*
echo 'action = '.$action.'<p>';
echo 'data   = '.$Data.'<p>';    die();
*/

$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
//-----------------------------------------------//
$data = preg_split('[,]',$Data);

$ch   = $data[0];
$meta = $data[1];
// echo $ch.'  '.$meta;

$x = new ITS_book('dspfirst',$ch,$meta,$mimetex_path);
$o = $x->main();
echo $o.'<p>';
//-----------------------------------------------//
?>