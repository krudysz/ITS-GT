<?php
include("config.php");
include "classes/ITS_QTI.php";
require_once(INCLUDE_DIR . "common.php");

$quesid = $_REQUEST['qid'];
$obj = new ITS_QTI();
$val = $obj->exportManyQues($quesid,0,0,1);  // 1 for single question
echo  $val;
?>
