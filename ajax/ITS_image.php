<?php
/*--------------------------------------------------------------------/
ITS_image - script for AJAX image class

Author(s): Greg Krudysz
Date: Feb-28-2012
---------------------------------------------------------------------*/

header("Cache-Control: no-cache, must-revalidate"); // Must do cache-control headers
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   // or IE will pull from cache 100% of time (which is really bad)

//echo getcwd() . "\n";
require_once("../config.php");
include "../classes/ITS_image.php";
$style = '<script type="text/javascript">$(document).ready(function() {$("a#single_image").fancybox();})</script>';

session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state, $files_path, $host;

if ($_POST['upload']) {
	$img = new ITS_image();
	$action = strtolower($_POST['upload']);
} else {
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
$img = $_SESSION['image'];
}

/* DEBUG /
echo 'action = '.$action.'<p>';
echo 'data   = '.$Data.'<p>';  die();
//*/

//-----------------------------------------------//
switch ($action){
    //-------------------------------------------//
	  case 'navigation':
    //-------------------------------------------//	 	
	  $page    = $Data[0];  // 0 for next ; 1 for previous
	  $img_str = $img->image_viewer($page,1);    
	  $str     = '<center>'.$img_str.'</center>';
	  break;			
	//-------------------------------------------//
	  case 'insert':
	//-------------------------------------------//	
	  $data  = preg_split('[~]',$Data);
	  //echo $data[0].'-'.$data[1].'-'.$data[2].'<br>';
	  $query = $img->save_image($data[0],$data[1],$data[2]);
	  $str   = $query;
	  break;	
	//-------------------------------------------//
	  case 'delete':
	//-------------------------------------------//	
	  $data  = preg_split('[~]',$Data);
	  $query = $img->delete_image($data[0],$data[1],$data[2]);
	  $str   = $query;
	  break;	  
    //-------------------------------------------//		
      case 'upload':
	//-------------------------------------------//
	  $qid 		   = $_POST['qid'];
	  $fld 		   = $_POST['fld'];
	  //die($fld);
	  //die($files_path.$host);
	  $server_path = '/var/www'; //html';
	  $ques_dir    = $files_path.'/images/question/'.$qid.'/';
	  $files_dir   = $ques_dir;
	  $filename    = basename($_FILES['ITS_image']['name']);
	  $uploadfile  = $server_path .'/'. $files_dir . $filename;
	  //echo '<p><font color="blue">'.$server_path .'#'. $files_dir .'#'. $filename.'</font></p>';

	  // dir check
	  $path = $server_path.$files_dir;  //die($path);	  
	  if (file_exists($path)) {
		   echo '<p>The dir:'. $path .'exists</p>';
	  } else {
		   echo '<p>The dir:'. $path .'does not exist</p>';
           mkdir($path,0777);
	  }

	  $msg = '<pre>';
	  if (move_uploaded_file($_FILES['ITS_image']['tmp_name'], $uploadfile)) {
        $msg .= '<font color="green">File successfully uploaded.</font>';
      } 
	  else { 
		$msg .= '<font color="red">File upload failed !!</font>'; 
	  }
	  $msg .= '</pre>';
//die();
	  //echo $"Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/Question.php?qNum=".$qid;
	//var_dump($img);
	//
	die($files_dir.$filename);
	  $img->upload_image($qid,$filename,'images/question/'.$qid,$fld);
	  $str = $msg;
	  break;	
    //-------------------------------------------//		
}
//-----------------------------------------------//
echo $style.$str;
?>
