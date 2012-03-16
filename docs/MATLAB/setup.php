<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>MATLAB - MYSQL Setup</title>
	<link rel="stylesheet" href="../css/docs.css">
</head>

<body>
<h3>
Instructions for connecting MATLAB with a mySQL database on Unix:
</h3><center>
  <table class="ITS_version" summary="ITS versions">
	    <!--------------------------------------------------------------------->
			<tr><th>ITEM</th><th>ACTION</th></tr>
			<!--------------------------------------------------------------------->
				    <tr>
		  <td>mySQL-JAVA<Br>connector</Br> </td>
			<td class="list">
			  <ol>
				<li><b>Download:</b> <a href="http://dev.mysql.com/downloads/connector/j/">http://dev.mysql.com/downloads/connector/j/</a></li>
				<li>FILE: <code>[path]/mysql-connector-java-5.1.18/mysql-connector-java-5.1.18-bin.jar</code></li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->
	    <tr>
		  <td>MATLAB</td>
			<td class="list">
			  <ol>
				<li><b>Download:</b> <a href="software.oit.gatech.edu">software.oit.gatech.edu</a></li>
				<li>create if necessary and set permissons for: <code>/mnt/disk</code><br><code>mkdir /mnt/disk</code>
				<br><code>chmod 775 /mnt/disk/</code></li>
				<li>mount .iso:<code> sudo mount R2011b_UNIX.iso /mnt/disk -t iso9660 -o loop</code></li>
				<li>install MATLAB: <code>/mnt/disk/install</code></li>
				<li><b>Open:</b> $MATLAB_PATH/toolbox/local/classpath.txt</li>
				<li>Append to <b>classpath.txt</b>: <code>$FULL_PATH/mysql-connector-java-5.1.18/mysql-connector-java-5.1.18-bin.jar</code></li>
				<li>restart MATLAB</li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->			
	    <tr>
		  <td>Test</td>
			<td class="list">
			  <ol>
				<li><a href="mysql.m">mysql.m</a></li>
        </ol>
			</td>
		  </tr>	
		  <!--------------------------------------------------------------------->
  </table></center>
</body>
</html>
