<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>VM - UBUNTU</title>
	<link rel="stylesheet" href="../css/docs.css">
</head>
<body>
<h3>
Instructions for installing developer's tools on Ubuntu:
</h3><center>
  <table class="ITS_version" summary="ITS versions">
	    <!--------------------------------------------------------------------->
			<tr><th>ITEM</th><th>ACTION</th></tr>
	    <!--------------------------------------------------------------------->
	    <tr>
		  <td>VM Install</td>
			<td class="list">
			  <ol>
				<li><b>Download:</b> <a href="ubuntu.com">ubuntu.com</a> Ubuntu server</li>
				<li>Install desktop:<code><br>sudo apt-get install ubuntu-desktop<br>sudo apt-get install gdm<br>sudo /etc/init.d/gdm start<br>sudo dpkg-reconfigure xserver-xorg</code></li>			
				<li>Install classic destkop: <code>sudo apt-get install gnome-panel</code></li>
				<li>Install tools: <code><br>sudo apt-get install geany<br>sudo apt-get install gftp<br>sudo apt-get install kdiff3</code></li>
				<li>Developers tools: <code><br>sudo apt-get install apache2<br>sudo apt-get install php5 libapache2-mod-php5<br>sudo apt-get install mysql-server</code></li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->			
  </table></center>
</body>
</html>
