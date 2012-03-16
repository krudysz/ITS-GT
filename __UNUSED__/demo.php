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
$status  = 'student';
$qNum    = 67;         // question number [in SQL: SELECT id from webct]

/*========= LOCAL  =========*/
$db_name = 'its';      // database name
$tb_name = 'webct';    // question table name
$db_dsn  = 'mysql://root:csip@tcp(localhost:3306)/'.$db_name;
/*==========================*/

// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){
			throw new Question_Control_Exception($mdb2->getMessage());
}
	 $Q = new ITS_question(1,$db_name,$tb_name);
	 $Q->load_DATA_from_DB($qNum);
	 echo $Q->render_TITLE();
	 echo $Q->render_QUESTION_check();
	 $Q->get_ANSWERS_data_from_DB();
	 $Q->get_ANSWERS_solution_from_DB();
	 echo $Q->render_ANSWERS('a');

	 $mdb2->disconnect();
?>
</div>
</body>
</html>
