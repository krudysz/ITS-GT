<?php
$db = mysql_connect("localhost", "root", "root");
mysql_select_db("its", $db);

//var_dump($_REQUEST);

$tb = 'tags';

$q = $_REQUEST['optional'];
$limit = 10;

if (isset($_REQUEST['optional'])){
	$q = $_REQUEST['optional'];
	$query = mysql_query("SELECT name FROM ".$tb." WHERE name LIKE '$q%'");

	$results = array();
	
	while ($row = mysql_fetch_array($query)){
		array_push($results, $row[0]);
	}
	
	for ($i = 0; $i < sizeof($results); $i++){
		//echo "<p>|".$results[$i]."</p>";
		echo '<div class="ITS_tag_sys"><table><tr><td>'.$results[$i].'</td><td class="tag_del" id="'.$results[$i].'">x</td></tr></table></div>';
	}	
} else {
	$query = mysql_query("SELECT name FROM ".$tb." ORDER BY name");
	$results = array();
	
	while($row = mysql_fetch_array($query)){
		array_push($results, $row[0]);
	}
	$list = '';
	for($i = 0; $i < sizeof($results); $i++){
		//echo $results[$i]."<br>";
		//$list .= '<div class="ITS_tag"><table><tr><td>'.$results[$i].'</td><td><span class="tag_del">x</span></td></tr></table></div>';
		$list .= '<div class="ITS_tag_sys"><table><tr><td>'.$results[$i].'</td><td class="tag_del" id="'.$results[$i].'">x</td></tr></table></div>';
	}
	echo $list;
}
// ADD TAG
if (isset($_POST["add"])){
	$q = $_POST["add"];
	$s = $_REQUEST["tableid"];
	$id = $_REQUEST["questionid"];
	$added = $_REQUEST["optional"];
	//echo $rand;
	// First, check to see if it is in the list:
	$query = "SELECT id FROM tags WHERE name = '$added'";
	$res = mysql_fetch_array(mysql_query($query));
	if ($res[0]){ //if it is present in the list
		$val = $res[0];
		$qinsert = "INSERT INTO $s (question_id, tags) VALUES ($id, $val)";
		$result = mysql_query($qinsert);
		if (!$result) 
			echo '<h3>Error in tagging the question</h3>';
		else
			echo '<h3>You tagged this question as <font color="red">'.$added.'</font></h3>';
	}
	
	// It is not, so insert it, then do what would have happened if it was in there.
	else{
		if($added!="") {
			$getTagId = "SELECT MAX(id) FROM tags";
			$res = mysql_fetch_array(mysql_query($getTagId));
			$newId = $res[0] + 1;
			$tinsert = "INSERT INTO tags (id,name) Values ('$newId','$added')";
			mysql_query($tinsert);
			$query = "SELECT id FROM tags WHERE name = '$added'";
			$res = mysql_fetch_array(mysql_query($query));
			$val = $res[0];
			$qinsert = "INSERT INTO $s (question_id, tags) VALUES ($id, $val)";
			$result = mysql_query($qinsert);
			if (!$result) 
				echo '<h3>Error in tagging the question</h3>';
			else
				echo '<h3>You tagged this question as <font color="red">'.$added.'</font></h3>';		
		}
	}
}
?>
