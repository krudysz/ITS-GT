<?php
	/*---------------------------------*/
include "config.php";
include "classes/ITS_images.php"; 
	/*---------------------------------*/
	session_start();
	if(isset($_SESSION['object']))
		$obj = $_SESSION['object']; 
	/*---------------------------------*/	 	
	$page = $_REQUEST['page'];  // 0 for next ; 1 for previous
	/*---------------------------------*/
	echo 'heel';
	$id = 2;
	$col_name = 3;
	$obj = new ITS_images($id,$col_name);
	$images_for_ques = 0;  // for div - ques_pic_table
	$images_in_db = 1;     // for div - main_table
	$page_num = 0;
	echo  $obj->image_viewer($page,1);
?>
