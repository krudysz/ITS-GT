<?php	
require_once("MDB2.php");
include("classes/ITS_table.php");
include("classes/ITS_question.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title>Questions Database (WebCT)</title>
<link rel="stylesheet" href="css/warmup.css">
</head>
<body class="ITS">
<div class="main">

<?php	 
$qNum    = 93;         // question number [in SQL: SELECT id from webct]

/*========= LOCAL  =========*/
$username = 'root';
$password = 'csip';
$db_name = 'its';      // database name
$tb_name = 'webct';    // question table name
/*==========================*/

// connect to database
$con = mysql_connect("localhost",$username,$password);
if (!$con) { die('Could not connect: ' . mysql_error());}
mysql_select_db($db_name, $con);

// DISPLAY QUESTION NUMBER
echo '<center>QUESTION: <font color="red">'.$qNum.'</font> ( '.$db_name.':'.$tb_name.' )</center>';

	 $Q = new ITS_question(1,$db_name,$tb_name);
	 $Q->load_DATA_from_DB($qNum);
	 
	 // title
	 echo '<HR>'.$Q->render_TITLE().'<HR>';
	 
	 // question
	 echo $Q->render_QUESTION();
	 
	 // answer
	 $Q->get_ANSWERS_data_from_DB();
	 $Q->get_ANSWERS_solution_from_DB();
	 echo $Q->render_ANSWERS('a');

	 // some code
	 mysql_close($con);
?>
</div>
</body>
</html>
