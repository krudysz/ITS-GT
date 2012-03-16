<?php
// Make a MySQL Connection
mysql_connect("localhost", "root", "root") or die(mysql_error());
mysql_select_db("test") or die(mysql_error());

// Insert a row of information into the table "example"
mysql_query("INSERT INTO example 
(name, age) VALUES('Timmy Mellowman', '23' ) ") 
or die(mysql_error());  

mysql_query("INSERT INTO example 
(name, age) VALUES('Sandy Smith', '21' ) ") 
or die(mysql_error());  

mysql_query("INSERT INTO example 
(name, age) VALUES('Bobby Wallace', '15' ) ") 
or die(mysql_error());  

echo "Data Inserted in the table 'example'!";

?>

