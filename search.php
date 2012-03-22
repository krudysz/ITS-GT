<?php
/* ============================================================= */
$ITS_version = '188j';
$LAST_UPDATE = 'Mar-23-2012';
/* ============================================================= */

//--- begin timer ---//
$mtime     = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
//------------------//
		
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 	       // Must do cache-control headers 
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

$dsn = preg_split("/[\/:@()]+/",$db_dsn);
$db_user = $dsn[1];
$db_pass = $dsn[2];
$host    = $dsn[4];
$db_name = $dsn[6];
$path1 = 'ITS_FILES/SPFIRST/PNGs/';
$path2 = 'ITS_FILES/SPFIRST/solutions/';
//var_dump($host);
//foreach ($dsn as $value) {echo $value.'<br>';}//die('search.php');
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
        <script type="text/javascript">
            function getfocus() {document.getElementById("ITS_search_box").focus()}
            /*---- GOOGLE ANALYTICS ------------------*/
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-16889198-1']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
            /*---- GOOGLE ANALYTICS ------------------*/            
        </script>	 
    </head>

    <body onload="getfocus()">
        <div id="pageContainer">
            <form id="ITS_search" action="search.php" method="post">
                <input id="ITS_search_box" type="text" name="keyword">
            </form>
            <?php
            if (isset($_POST['keyword'])) {
                //echo $_POST['keyword'];
                if ($_POST['keyword']) {
                    $keyword = $_POST['keyword'];
                    MySQL_connect($host,$db_user,$db_pass);
                    MySQL_select_db($db_name);
                    $sql = "
                                SELECT *,
                                    MATCH(keywords) AGAINST('$keyword') AS score
                                    FROM SPF
                                WHERE MATCH(keywords) AGAINST('$keyword')      
                            ";
                    // DEBUG: echo $sql;die(); ORDER BY score DESC
                    $res = MySQL_query($sql);
                    // SELECT *,MATCH (keywords) AGAINST ('fourier') AS score FROM SPF WHERE MATCH(keywords) AGAINST('fourier');			

                    if (MySQL_num_rows($res) > 0) {
                        echo '<div id="ITS_search_query"> " ' . $keyword . ' " [ <a target="_blank" href="http://itsdev4.vip.gatech.edu/admin/tables1.php?table_name=SPF&table_name2=-&entry_nos=-">SPF table</a> ] </div>';
                        ?>
                        <table class="ITS_search">
                            <tr><th>Title</th><th>File</th><th>Solution</th><th>Ch.</th><th>Term</th><th>Year</th></tr>
                            <?php
                            $idx = 1;
                            $tb  = '';
                            while ($row = MySQL_fetch_array($res)) {
								//echo '<pre>';var_dump($row);echo '</pre>';die();
                                $fname = $row['statement'];
                                $solutions = $row['solutions'];
                                $term = $row['term'];
                                switch ($term){
									case 'Spring':
									case 'Summer':
									$t = $term[0].$term[1];
									break;
									case 'Fall':
									case 'Winter':
									$t = $term[0];
									break;
								}
                                $year = $row['year'];
								$f = $path1.strtolower($t).'_'.$year[2].$year[3].'/'.preg_replace('/.pdf/','.png', $fname);

//echo $path;die();
                                $sol_arr = explode(',', $solutions);

                                $sol_list = '';
                                foreach ($sol_arr as $s) {
                                    if (empty($s)) {
                                        $sol_list .= '';
                                    } else {
                                        $sol_list .= '<div class="file"><a href="' . $path2.strtolower($t).'_'.$year[2].$year[3].'/'.$s . '" target="_blank"><img alt="' . $path2.strtolower($t).'_'.$year[2].$year[3].'/'.$s . '" src="admin/icons/png_icon.png" /></a></div>';
                                    }
                                }	
                                //echo '<pre>';var_dump($sol_list);echo '</pre>';die();
                                //$sol  = '<div class="file"><a href="'.$solutions.'" target="_blank"><img alt="'.$solutions.'" src="'.$solutions.'" /></a></div>';

                                $sol = '<div class="file">' . $sol_list . '</div>';
                                $file = '<div class="file"><a href="' . $f . '" target="_blank"><img alt="' . $f . '" src="admin/icons/png_icon.png" /></a></div>';
                                //echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['score']}</td></tr>";   
                                $tb .= "<tr>" .
                                        '<td class="search_title">' . $row["title"] . '</td>' .
                                        "<td>" . $file . "</td>" .
                                        '<td class="search_solution">' . $sol_list . '</td>' .
                                        "<td>{$row['chapter']}</td>" .
                                        "<td>{$row['term']}</td>" .
                                        "<td>{$row['year']}</td>" .
                                        "</tr>";
                            } //while
                            $tb .= "</table>";
                            echo $tb;
                            //$idx = $idx+1;
                        } else {
                            echo '<div id="ITS_search_empty">No records found.<p>... try the following keywords: </p><div class="HACK">*** hack some more ***</span></div>';
                        }
                    } else {
                        echo '<p style="height: 500px">Search for SP First content.</p>';
                    }
                } else {
                    echo '<p style="height: 500px">Search for SP First content.</p>';
                }
                //--- FOOTER ---//
                $status = 'admin';
                $role = 'admin';
                $ITS_version = 'search demo 1.1';
                include '_include/footer.php';
                ?>
        </div>
    </body>
</html>
