<?php
//--- begin timer ---//
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
//------------------//

require_once ("config.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");
require_once ("classes/ITS_navigation.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

$id     =   $_SESSION['user']->id();
$status =   $_SESSION['user']->status();
$info   = & $_SESSION['user']->info();
//------------------------------------------// 
// NAVIGATION 		 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current,'');    
//---------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>PHP eval</title>
	<!---->
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_users.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
    <link rel="stylesheet" href="css/ITS_search.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_php.css" type="text/css" media="screen">	
            <script type="text/javascript">
            function getfocus() {document.getElementById("ITS_search_box").focus()}       
        </script>
</head>
<body onload="getfocus()">
<div id="framecontent">
<!---************* NAVIGATION ******************--->
<?php echo $nav;?>
<!---******************************************--->
<div class="innertube"></div>
<!---******************************************--->
</div>
<center>
            <form id="ITS_search" action="php.php" method="post">
                <input id="ITS_search_box" type="text" name="keyword">
            </form>
            <?php
            $intro = '<p>Enter a PHP math expression.<ul class="ex"><li>sqrt(pow(3,2) + pow(4,2))</li><li>fmod(7.3,1.3)</li></ul></p>';
            if (isset($_POST['keyword'])) {
                if ($_POST['keyword']) {
                    $keyword = $_POST['keyword'];
					eval("\$php=" . $keyword . ";" ); 
					
					if (is_null($php)) {
						echo '<p>Unable to evaluate the expression:<br><p style="color:red">'.$keyword.'</p></p>'.$intro;
					}
					else {
						$out = '<table class="key">'.
									'<tr>'.
										'<td style="color:#999">'.$keyword.'</td><td> = </td><td class="out">'.$php.'</td>'.
									'</tr>';
					    echo '<center>'.$out.'</center>';
					}
                    } else {
                        echo $intro;
                    }
                     
                } else {
					echo $intro;
				}
//-----------------------------------------------------------//
?>
</center>
</body>
</html>
