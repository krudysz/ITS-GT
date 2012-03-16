<?php
// Make a MySQL Connection
mysql_connect("localhost", "root", "root") or die(mysql_error());
mysql_select_db("test") or die(mysql_error());

// Create a MySQL table in the selected database
mysql_query("CREATE TABLE example(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 name VARCHAR(30), 
 age INT)")
 or die(mysql_error());  

echo "Table 'example' Created!";

?>

