<?php
$u=$_GET["uid"];
$q=$_GET["qid"];
$t=$_GET["tid"];
$tag = $_GET["tnm"];

$con = mysql_connect("localhost","root","csip");
if (!$con){ die('Could not connect: ' . mysql_error()); }
mysql_select_db("its", $con);
		
$query = "INSERT INTO stats_".$u."(question_id, tags) VALUES ('".$q."','".$t."')";
$result = mysql_query($query);
if (!$result) 
	echo '<h3>Error in tagging the question</h3>';
else
	echo '<h3>You tagged this question as <font color="red">'.$tag.'</font></h3>';
mysql_close($con);
?>
