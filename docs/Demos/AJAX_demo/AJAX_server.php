<?php
/*
ITS_server - script for AJAX "server" file

Author(s): Greg Krudysz                    */
//===================================================================//
//-- Get AJAX arguments
$args = preg_split('[,]',$_GET['ajax_args']);

//-- Get AJAX user data
$Data = rawurldecode($_GET['ajax_data']);
// preprocess before SQL
$Data = str_replace ("'","&#39;",$Data);
//$Data = nl2br($Data);

//echo 'arguments = '.$args.'<p>';
//var_dump($args);
//echo 'data   = '.$Data.'<p>';    //die();
//===================================================================//

// Connect to database
// output data to $str

$str = 'String from AJAX_server.php';

echo $str;							 
?>

