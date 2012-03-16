<html>
<body>
<?php
mysql_connect("localhost", "root", "root") or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db("test") or die(mysql_error());
echo "Connected to Database 'test'";
?>
</body>
</html>
