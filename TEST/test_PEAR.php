<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>test PEAR</title>
</head>
<body>

<?php
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('include_path',"C:\wamp\www\pear");
require_once("MDB2.php");
echo '<hr>MDB2 include WORKS ...';

$link = mysql_connect('localhost', 'root', 'csip');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo '<hr><p>mysql_connect() WORKS ...</p>';
mysql_close($link);

// connect to database with MDB2
$db_name   = 'its';
$db_dsn    = 'mysql://root:csip@tcp(localhost:3306)/'.$db_name;

$mdb2 =& MDB2::connect($db_dsn);
var_dump($mdb2);
//if (PEAR::isError($mdb2)){ throw new Exception($mdb2->getMessage()); }

$query = 'SELECT username from stats_1';

echo '<hr><p'.$query.'</p>';
$res = $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
?> 
</body>
</html>
