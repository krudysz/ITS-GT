<?php
if(PHP_OS == "WINNT") { $pass = '';    } 
else 				 					{ $pass = 'csip';}
$con = mysql_connect("localhost", "root", $pass) or die(mysql_error());
mysql_select_db("its", $con) or die(mysql_error()); 


$query = "SELECT COUNT(id) FROM webct";

$result = mysql_query($query) or die(mysql_error());
$check=-1;

if($row = mysql_fetch_assoc($result)){
    $max = $row['COUNT(id)'];
    
    while($check<0){
        $randomQuestionID = rand(1, $max);
        $query2 = "SELECT webct.id, webct.title, webct.image, webct.answers, webct.question, webct_mc.* FROM webct JOIN webct_mc ON webct.id = webct_mc.id WHERE webct.id=". $randomQuestionID;
        $result2 = mysql_query($query2) or die(mysql_error());
        if($row2 = mysql_fetch_assoc($result2)){
            $output[]=$row2;
            $check=1;
        }
    }
}
$arr = array ('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
echo json_encode($arr);

print(json_encode($output));

mysql_close();
?>