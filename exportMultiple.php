<?php

include "classes/ITS_QTI.php";

$category = $_REQUEST['category_export'];
$ques_type= $_REQUEST['ques_type'];

$obj = new ITS_QTI(); 
$val = $obj->exportManyQues(0,$category,$ques_type,2); // 2 for multiple questions
echo $val;
?>
