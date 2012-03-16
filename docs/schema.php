<?php
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS Backup schema</title>
	<link rel="stylesheet" href="css/ITS_versions.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/docs.css">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
	<style>
	body { margin: 5% 0 }
	.ITS_version td { text-align:right; }
	.at { color:#666 }
    .f { color:#009; }
	</style>
</head>
<body>
	<center>
	        <h3 class="DATA">BACKUP  &mdash;  SCHEMA</h3>
  <table class="ITS_version" summary="ITS versions">
  	    <!--------------------------------------------------------------------->
			<tr><th>No.</th><th>Script</th><th>Exec On</th><th>At time</th><th>SOURCE</th><th>DESTINATION</th></tr>
			<!--------------------------------------------------------------------->
	    <tr>
	    <td>1.</td>
		  <td>backup_HTML.sh</td>
			<td>its.vip</td>
				<td>3:00</td>
					<td>/var/www/html <span class="at"> @ </span>its.vip</td>
						<td>/ITSdrive/__ITS/backup/<b>HTML</b>/</b><span class="f">ITS_date.zip</span></b> <span class="at"> @ </span>its.vip</td>
		  </tr>	
		  <!--------------------------------------------------------------------->
	    <tr>
	    	    <td>2.</td>
		  <td>backup_SQL.sh</td>
			<td>its.vip</td>
				<td>3:40</td>
					<td>[service:mysql][db:its] <span class="at"> @ </span>its.vip</td>
						<td>/ITSdrive/__ITS/backup/<b>SQL</b>/<span class="f">ITS_date.zip</span><span class="at"> @ </span>its.vip</td>
		  </tr>	
		  <!--------------------------------------------------------------------->
	    <tr>
	    	    <td>3.</td>
		  <td>sync_ITS.sh</td>
			<td>its.vip</td>
				<td>4:40</td>
					<td>/ITSdrive <span class="at"> @ </span>its.vip</td>
						<td>/ITSdrive <span class="at"> @ </span>itsold.vip</td>
		  </tr>	
		  <!--------------------------------------------------------------------->
	    <tr>
	    	    <td>4.</td>
		  <td>sync_ITS.sh</td>
			<td>itsdev2.vip</td>
				<td>5:10</td>
					<td>/ITSdrive <span class="at"> @ </span> its.vip</td>
						<td>/ITSdrive <span class="at"> @ </span>itsdev2.vip</td>
		  </tr>	
		  <!--------------------------------------------------------------------->		  
  </table>
 </center>  
</body>
</html>
