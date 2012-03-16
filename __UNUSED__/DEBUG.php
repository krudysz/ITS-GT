<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   					 // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 					 // Must do cache-control headers 
header("Pragma: no-cache");


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>DEBUG</title>
	<!---->
	<script type="text/javascript">
</script>
</head>
<body>

<table class="DATA">
	<tr><th>formula</th><th>var</th></tr>
	<tr><td rowspan="3">formula</td><td>var1</td></tr><tr><td>var2</td></tr><tr><td>var3</td></tr>
</table>	

<?php
$footer = 'debug';
echo $footer;
//-----------------------------------------------------------//
?>
</body>
</html>
