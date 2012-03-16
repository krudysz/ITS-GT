<?php	
//=====================================================================//
/* LOG FILES					
	 Author(s): Greg Krudysz
	 Last Revision: May-10-2011	 
*/
//=====================================================================//
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");          // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate");        // Must do cache-control headers 
header("Pragma: no-cache");

include("config.php");
require_once("classes/ITS_navigation.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

session_start();
// return to login page if not logged in
abort_if_unauthenticated();

//--- NAVIGATION ------------------------------// 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current);    
//---------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<title>Database Logs</title>
  <link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_logs.css" type="text/css" media="screen">
	<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	

	<script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
</head>

<body>
<div id="framecontent">
<!---************* NAVIGATION ******************--->
<?php echo $nav;?>
<!---*******************************************--->
<div class="innertube">
</div>
</div>
<div id="frameseparator"></div>

<div id="maincontent">
<div style="clear:both">
<?php 
$status = $_SESSION['user']->status();
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
 
$query = 'SHOW MASTER STATUS'; 
$res =& $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$logInfo = $res->fetchRow();
 
$query = 'SHOW BINLOG EVENTS IN "'.$logInfo[0].'" LIMIT 500';
//select * from table WHERE id >  ((SELECT MAX(id) from table) - 10);
//echo $query;
$res =& $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$logs = $res->fetchAll();
//echo count($logs);

$tb = '<table class="ITS_logs"><tr><th>No.</th><th>Pos</th><th>Event Type</th><th>Info</th>';
for ($u=0; $u < count($logs); $u++){
  //echo '<pre>'; var_dump($logs[$u]);//die();echo '</pre>';
	
	switch ($logs[$u][2]) {
    case "Query": $style = 'background:#bfb;'; break;
		default:      $style = '';				
 }
  if ($logs[$u][5]!='BEGIN') {
  	$tb .= '<tr>'.
  	       '<td style="background:#eee"><b>'.($u+1).'</b></td>'.
  			   '<td>'.$logs[$u][1].'</td>'.
  				 '<td>'.$logs[$u][2].'</td>'.
  				 '<td style="'.$style.'">'.$logs[$u][5].'</td>'.
  				 '</tr>';
  }
}
$tb .= '</table><p>Source: '.$logInfo[0];
echo $tb;
?>
</div>
<?php
//-----------------------------------------------------------//
echo '<p class="center">&nbsp;<b><font color="silver">~&diams;~</font></b>&nbsp;</p>';
//-----------------------------------------------------------//
?>
</div>
<!----------------------------------------------------------->
</body>
</html>
