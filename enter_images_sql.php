<?php
include("Image_catalogue.php");

$objCatalogue = new Image_catalogue();  // Capitalize the class name - convention********
$image_files = $objCatalogue->getarray('/var/www/ITS/ITS_FILES/PreLabs');
insert_images($image_files);
function insert_images($image_files){
	
$DBHOST = 'localhost';
$DBUSER = 'root';
$DBPASS = 'root';
$DBNAME = 'its';
$any_image = false;

$con = mysql_connect($DBHOST, $DBUSER, $DBPASS) or die('Could not Connect');
mysql_select_db($DBNAME, $con) or die('Could not select DB');
$count_images = 0;
if(count($image_files)>0){
	$query = "insert into images(name,path,tag) values";
	for($i=0;$i<count($image_files); $i=$i+1){
		$image = trim($image_files[$i]); //echo "image : $image <br>";
		$sub_query = "select path from images where path='".$image."'";
		//echo "sub query".$sub_query."<br>";
		$result = mysql_query($sub_query, $con) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		if ($row) 
		    {//echo "image already exists"; 
				continue;
			}
		else
			{//&& $i!=(count($image_files) -1 )
				if(count($image_files)>1 && $count_images>0 ){
					$query .= ",";	
				}
				$count_images++;
			}
		$any_image = true;  	
		$image_nm = preg_split('/\//', $image, -1);
		//print_r($image_nm);
		$len_file_name = sizeof($image_nm) - 1;
		$query .= "('".$image_nm[$len_file_name]."','".$image."','')";

		// how to make sure that same images are not entered in the table again and again NOT IN (select path from images)
	}
	echo "<br>".$query;
	if($any_image){
		$result = mysql_query($query, $con) or die(mysql_error());
		}
}
}
?>
