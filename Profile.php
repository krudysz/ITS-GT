<?php
$LAST_UPDATE = 'Mar-22-2012';
//--- begin timer ---//
$mtime     = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
//-------------------//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   		   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 		   // Must do cache-control headers 
header("Pragma: no-cache");

include ("classes/ITS_timer.php");

require_once ("config.php");
//require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_query.php");
require_once ("classes/ITS_statistics.php");
require_once ("classes/ITS_computeScores.php");
require_once ("classes/ITS_navigation.php");
require_once ("classes/ITS_footer.php");

require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

$id     =   $_SESSION['user']->id();
$status =   $_SESSION['user']->status();
$info   = & $_SESSION['user']->info();
//------------------------------------------// 
//echo $_SESSION['screen']-role();
//echo '<pre>';var_dump($_SESSION);echo '</pre>';

//$screen = $_SESSION['screen'];
//echo $screen->status;

if ($status == 'admin' OR $status == 'instructor') {
    //------- CLASS -------------//
    switch ($status) {
    case 'instructor':
        $class_arr = array('Spring_2012','Fall_2011','Spring_2011','Fall_2010','BMED6787','instructor','ta');
        $delButton = '';
        break;
    case 'admin':
        $class_arr = array('Spring_2012','Fall_2011','Spring_2011','Fall_2010','BMED6787','admin','instructor','ta');
        $delButton = '<div id="deleteButton" uid="'.$id.'" class="dialogButton">Clear my<br>Profile</div>'.
                            '<div id="deleteDialog" title="Delete Account Info?" style="display:none">'.
                            '<B>ALL</B> of your ITS records will be permanently deleted and cannot be recovered.<br>'.
                            '<div class="mysql"><code>mysql>&nbsp;<font class="mysql">DELETE FROM stats_'.$id.'</font></code></div>'.
                     '</div>';
        break;
    }

    if (isset($_GET['class'])) {
        $section = $_GET['class'];
    }
    else {
        $section = $class_arr[0];
    }

    //$class = '<div name="class" id="select_class">Class: ';
    $class = 'Class: <select class="ITS_select" name="class" id="select_class" onchange="javascript:this.submit()">';
    for ($cs=0; $cs<count($class_arr); $cs++) {
        if ($section == $class_arr[$cs]) {
            $sel = 'selected="selected"';
            $current_section=$class_arr[$cs];
        }
        else {
            $sel = '';
        }

        //$class .= '<input type="checkbox" name="class" id="check'.$cs.'" '.$sel.'/><label for="check'.$cs.'">'.$class_arr[$cs].'</label>';
        $class .= '<option value="'.$class_arr[$cs].'" '.$sel.'>'.preg_replace('/_/',' ',$class_arr[$cs]).'</option>';
    }
    $class .= '</select>';

    //------- USER ---------------//
    if (isset($_GET['sid'])) {
        $uid = $_GET['sid'];
    }
    else {
        $uid = $id;
    }
    $usertable  = 'stats_'.$uid;

    $mdb2  =& MDB2::connect($db_dsn);
    $query = 'SELECT id,last_name,first_name,status FROM users WHERE status IN ("'.$section.'") ORDER BY last_name';  // "admin",
    $res   =& $mdb2->query($query);

    $mdb2->disconnect();
    $user_data = $res->fetchAll();
    //array_unshift($user_data,array(0,'ALL','',$current_section));
    /*
	echo '<pre>';
	print_r($user_data);
	echo '</pre>';
		die('DONEE');
    */
    //class="ITS_select"
    $users = '<select  name="sid" class="ITS_select" id="select_user" onchange="javascript:this.submit()">';

    //echo $uid.' == '.$user[0].'<p>';
    if ($uid == 0) {
        $sel = 'selected="selected"';
        $current_user = 'ALL';
    }
    else {
        $sel = '';
        $current_user = 'ALL';
    }

    $users .= '<option class="highlighted" value="ALL" '.$sel.'>ALL</option>';

    foreach ($user_data as &$user) {
        if ($uid == $user[0]) {
            $sel = 'selected="selected"';
            $current_user = $user[3];
        }
        else {
            $sel = '';
        }

        if ($user[3] == 'admin') {
            $cl  = 'class="highlighted"';
        }
        else {
            $cl  = '';
        }

        $users .= '<option '.$cl.' value="'.$user[0].'" '.$sel.'>'.$user[1].', '.$user[2].'</option>';
    }
    $users .= '</select>';
    //echo $uid.' -- '.$user[0].' -- '.$current_user.' -- '.strcmp($current_user,'ALL');
    if (strcmp($current_user,'ALL')) { // indiv user
        //--- CHAPTER ---------------------------------//
        $ch_max = 14;
        if (isset($_GET['ch'])) {
            $ch = $_GET['ch'];
        }
        else {
            $ch = 1;
        }

        $chapter = 'Module #<select class="ITS_select" name="ch" id="select_chapter" onchange="javascript:this.submit()">';
        for ($c=1; $c<=$ch_max; $c++) {
            if ($ch == $c) {
                $sel = 'selected="selected"';
            }
            else {
                $sel = '';
            }
            $chapter .= '<option value="'.$c.'" '.$sel.'>'.$c.'</option>';
        }
        $chapter .= '</select>';
        //---------------------------------------------// 
        switch ($status) {
		    case 'admin':
		      $id_str = ' &nbsp; <tt>id: </tt>'.$uid;
		    break;
		    default: 
		      $id_str = '';
	    }       
        $classInfo = '<a href="Profile.php?class='.$current_user.'&sid=0">'.preg_replace('/_/',' ',$current_user).'</a>';
        $form  = $class.' &nbsp; '.$users.' &nbsp; '.$chapter.' &nbsp; '.$classInfo.$id_str;

        //echo '<p>'.$uid.' + '.$ch_max;
        // SLOW: $score = new ITS_computeScores($uid,$status,$ch_max);
        // if ($role == 'admin') { $ch = 13; }
        // else                  { $ch = 7;  }
        $chArr = range(1,$ch_max);
        
        // SCORE
        $score 	 = new ITS_computeScores($uid,$ch,$chArr);
        $str   	 = $score->renderChapterScores($ch_max);
        $myScore = '<div id="scoreContainer"><span>&raquo;&nbsp;User Scores</span></div>'.
                '<div id="scoreContainerContent">'.$str.'</div>';
        $sort = 'id';     
        $tr   = new ITS_statistics($uid,$section,$status);   // Fall_2009     
        $list = $tr->render_profile2($ch,$sort);
         //die('---dd---');
        //----------------------------//
    }
    else {/*
      switch ($section) {
      case 'mc':
      case 'm':*/

        $chs  = array(1,2,3,4,5,6,7,9,11);
        $form = $class.'&nbsp;  Profile:&nbsp;'.$users;
        $myScore = '';

        $tr   = new ITS_statistics($uid,$section,$status);   // Fall_2009
        $list = $tr->render_class_profile($section,$chs);
        /*
        echo '<pre>';
        print_r($list);
        echo '</pre>';
        */
    }
    //--- NAVIGATION ------------------------------// 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current,'');    
    //---------------------------------------------//	
}else {
//* redirect to start page *//
header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']),'/\\')."/screen.php");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Profile</title>
        <!---->
        <link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_profile.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_test.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">

        <link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
        <!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
        <script src="js/ITS_admin.js"></script>
        <script src="js/AJAX.js"></script>
        <script src="js/ITS_AJAX.js"></script>
        <script src="js/ITS_screen2.js"></script>
        <script src="js/ITS_QControl.js"></script>
        <script src="js/ITS_book.js"></script>
        <style>
            #select_class { margin-top: 2em; }
            .ui-widget-header   { background: #aaa; border: 2px solid #666; }
            .ui-dialog-titlebar { background: #aaa; border: 2px solid #666; }
            .ui-dialog-content  { text-align: left; color: #666; padding: 0.5em; }
            .ui-button-text { color: #00a; }
        </style>
        <script type="text/javascript">
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

        <?php include 'js/ITS_Profile_jquery.php';?>
    </head>
    <body>
        <div id="framecontent">
            <!---************* NAVIGATION ******************--->
            <?php echo $nav;?>
            <!---*******************************************--->
            <div class="innertube">
                <table class="DATA"> <tr><td width="85%">
                            <form id="profile" name="profile" action="Profile.php" method="get">
<?php echo $form;?>
                            </form></td>
                        <td><?php echo $delButton;?></td></tr>
                </table>
            </div>
        </div>
        <!---******************************************--->
    </div>
    <div id="maincontent">
<?php
//+++++++++++++++++++++++//
echo $myScore;		
//+++++++++++++++++++++++//
//-----------------------------------------------------------//
// ACCOUNT INFO
//-----------------------------------------------------------//
//echo $section.'--'.$uid.'--'.$status.'--'.$ch.'<p>';

echo '<div id="userProfile">'.$list.'</div>';
//--- TIMER -------------------------------------------------//
$mtime     = explode(" ",microtime());
$endtime   = $mtime[1] + $mtime[0];
$totaltime = ($endtime - $starttime);
//--- FOOTER ------------------------------------------------//
$ftr = new ITS_footer($status,$LAST_UPDATE,$totaltime);
echo $ftr->main();
//-----------------------------------------------------------//
?>
</div>
</body>
</html>
