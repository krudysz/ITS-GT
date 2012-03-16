 <?php
 //connect to database
	$id=1;	
	$dbname="test";
	$server="localhost";
	$username="root";
	$password="";
	$tbname="test";
 include_once("class_rating_main.php"); 
 new rating_main($id,$dbname,$server,$username,$password,$tbname); 
 
 ?>