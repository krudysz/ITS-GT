<?php
/* ============================================================= */
$ITS_version = '162d';
$LAST_UPDATE = 'July-14-2011';
/* ============================================================= */
//SELECT *,MATCH(keywords) AGAINST('$keyword') AS score FROM dspfirst WHERE MATCH(keywords) AGAINST('$keyword');
//--- begin timer ---//
$mtime     = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
//------------------//
		
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 	         // Must do cache-control headers 
header("Pragma: no-cache");

include ("classes/ITS_timer.php");
require_once ("config.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_footer.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

$id     =   $_SESSION['user']->id();
$status =   $_SESSION['user']->status();
$info   = & $_SESSION['user']->info();

/* ============================================================= */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>ITS - search</title>
				<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_search.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
				<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
        <script type="text/javascript">
            function getfocus() {document.getElementById("ITS_search_box").focus()}       
        </script>	 
        <style>
         table.key { margin: 5% 0;text-align: center; font-size: 200%; font-weight: bold; font: monospace;}
         table.key td { padding: 0.5em; }
         .out { color: #2A2; }
         .ex { text-align: left; color:#666; margin-left: 40%; }
         .ex li { padding: 0.25em; }
        </style>
    </head>

    <body onload="getfocus()">
        <div id="pageContainer">
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
						echo '<div>Unable to evaluate the expression:<br><p style="color:red">'.$keyword.'</p></div>'.$intro;
					}
					else {
						$out = '<table class="key">'.
									'<tr>'.
										'<td style="color:#999">'.$keyword.'</td><td> = </td><td class="out">'.$php.'</td>'.
									'</tr>';
					    echo '<center>'.$out.'</center>';
					}
                    } else {
                        //echo '<p>Unable to evaluate the expression</p>';
                        echo $intro;
                    }
                     
                } else {
					echo $intro;
				}
                ?>
        </div>
    </body>
</html>
