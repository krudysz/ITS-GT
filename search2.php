<?php
die('ss');
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

//die('ss');
//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

$id     =   $_SESSION['user']->id();
$status =   $_SESSION['user']->status();
$info   = & $_SESSION['user']->info();
$img_path = '/ITS_FILES/BOOK/';
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
            <form id="ITS_search" action="search2.php" method="post">
                <input id="ITS_search_box" type="text" name="keyword">
            </form>
            <?php
            //die('now');
            if (isset($_POST['keyword'])) {
                //echo $_POST['keyword'];
                if ($_POST['keyword']) {
                    $keyword = $_POST['keyword'];
                    MySQL_connect("localhost", "root", "csip");
                    MySQL_select_db("its");
                    $sql = "
                                SELECT *,
                                    MATCH(keywords) AGAINST('$keyword') AS score
                                    FROM dspfirst
                                WHERE MATCH(keywords) AGAINST('$keyword')      
                           ";
                    // DEBUG: echo $sql;die(); ORDER BY score DESC
                    $res = MySQL	_query($sql);

                    // SELECT *,MATCH (keywords) AGAINST ('fourier') AS score FROM SPF WHERE MATCH(keywords) AGAINST('fourier');			
                    if (MySQL_num_rows($res) > 0) {
                        //echo '<div id="ITS_search_query"> " ' . $keyword . ' "</div>';
                        ?>
                        <table class="ITS_search">
                            <tr><th>meta</th><th>&nbsp;</th><th>content</th></tr>
                            <?php
                            $idx = 1;
                            $tb  = '';
                            while ($row = MySQL_fetch_array($res)) {		
																$meta    = $row['meta'];
    														$content = $row['content'];
																//---- IMAGE ----//
    														if ($meta=='image') { 
																  $name  = $row['name'];
																	$fname = str_replace('_5in','',$name);
																  $fname = trim(str_replace('}','',$fname));
																	//echo '<pre>'.$fname.'</pre>';
																	$content = '<div class="ITS_Image"><img src="../BOOK/BOOK2/'.$fname.'.png" alt="'.$fname.'"></div><br>'.$content; 
																	
																	$tb .= '<tr>' .
                                         '<td><b>'.$meta.'</b></td>' .
																				 '<td>&nbsp;</td>' .
                                         '<td>'.$content.'</td>' .
                                       '</tr>';
																}
																//echo $meta.'<br>';
																//---- EQUATION ----//
    														if ($meta=='equation') { 
																  $name = trim(str_replace('}','',$row['name']));
																	//echo '<pre>'.var_dump($name).'<pre>'.count($name);
																	
																	
																  $tags = ''; //array();
                                  for ($t = 0; $t <= count($name)-1; $t++) { 
                                     $tags .= '<span class="ITS_tag">'.$name[$t].'</span>';
                                  }
                            			//if ($i==0) { $book .= '<hr class="ITS_hr">';}
                            			//$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font></td><td><img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/></td></tr><tr><td colspan="2">'.$tags.'</td></tr></table></div>';
                        					//$book .= '<img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/>';
																  
																	$content = '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td><img class="ITS_EQUATION" src="'.$mimetex_path.$content.'"/></td></tr></table></div>';
																	//echo '<font color="blue">'.$meta.'</font><br>';
																	//echo '<pre>'.$name.$tags.'</pre>';
																	//$content = '<font color="blue">'.$fname.'</font><br>'.$content; 
																	                                //$sol  = '<div class="file">' . $sol_list . '</div>';
                                  //$file = '<div class="file"><a href="' . $fname . '" target="_blank"><img alt="' . $fname . '" src="admin/icons/pdf_icon.gif" /></a></div>';
                                  //echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['score']}</td></tr>";   
                                  $tb .= '<tr>' .
                                         '<td><b>'.$meta.'</b></td>' .
																				 '<td>&nbsp;</td>' .
                                         '<td>'.$content.'</td>' .
                                       '</tr>';
																}
																											
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
