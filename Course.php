<?php
$LAST_UPDATE = 'Mar-13-2012';
//=====================================================================//
/* 	 Author(s): Gregory Krudysz
     Last Revision: Gregory Krudysz, Mar-13-2012                       */
//=====================================================================//
//--- begin timer ---//
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
//-------------------//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   		   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 		   // Must do cache-control headers 
header("Pragma: no-cache");

include ("classes/ITS_timer.php");

require_once ("config.php");
include "file2SQL.php";
include "STATS.php";
require_once ("classes/ITS_navigation.php");
require_once ("classes/ITS_footer.php");
require_once ("classes/ITS_query.php");

require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");

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


if (isset($_GET['c'])) {
    $course = $_GET['c'];
} else {
    $course = 'ece2025';
}
//die($c);
//------------------------------------------// 
if ($status == 'admin' OR $status == 'instructor') {
    global $db_dsn, $db_name, $db_table_users, $db_table_user_state;

    $mdb2 =& MDB2::connect($db_dsn);
    if (PEAR::isError($mdb2)) {
        throw new Question_Control_Exception($mdb2->getMessage());
    }

    $subnav = '';
    switch ($course) {
        //=======================================//
        case 'ece2025':
        //=======================================//
        //------- CHAPTER -------------//
            $ch_max = 14;
            if (isset($_GET['ch'])) {
                $ch = $_GET['ch'];
            }
            else {
                $ch = 1;
            }

            $chapter = 'Module #<select class="ITS_select" name="ch" id="select_chapter" onchange="javascript:this.form.submit()">';
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
            //--- QUESTIONS ------------------------------------------//
            $msg = '';
            $questions = array();

            //--- USERS --- ------------------------------------------//
            $ITSq = new ITS_query();
            $resource_source = $ITSq->getCategory($ch);
            $subnav = '<form id="'.$course.'" name="'.$course.'" action="Course.php" method="GET"><input type="hidden" name="c" value="'.$course.'">'.$chapter.'<noscript><input type="submit" value="Submit"></noscript></form>';
            break;
        //=======================================//
        case 'ece3075':
        //=======================================//
            $resource_source = 'category IN ("ECE3075","ECE 3075")';
            break;
        //=======================================//
        case 'spen':
        case 'vip':
        case 'vip2':
        //=======================================//
            $resource_source = 'category LIKE "%'.$course.'%"';
            break;
        //=======================================//
        default:
            $resource_source = ' id=1';
    }

    $query = 'SELECT id,title,category FROM '.$tb_name.' WHERE '.$resource_source;
    $res   = $mdb2->query($query);
    $ques = $res->fetchAll();
    //----------------------------------//

    //-- LIST of questions (count($answers)-1)
    $Estr = '<table class="PROFILE">'.
            '<tr><th style="width:4%;">No.</th><th style="width:77%;">Question</th><th style="width:14%;">Author</th></tr>';
    for ($qn = 0; $qn <= (count($ques)-1); $qn++) {
        $qid   = $ques[$qn][0];
        $title = $ques[$qn][1];
        $cat   = $ques[$qn][2];
        $Q = new ITS_question($qid,$db_name, 'webct');

        $Q->load_DATA_from_DB($qid);
        //echo $qid;
        $QUESTION = $Q->render_QUESTION(); //_check($answers[$qn][4]);
        $Q->get_ANSWERS_data_from_DB();
        $ANSWER = $Q->render_ANSWERS('a', 2);
        //$ANSWER = $Q->render_ANSWERS('a',0);

        $Estr .= '<tr class="PROFILE" id="tablePROFILE">'.
                '<td class="PROFILE" >' . ($qn +1) .'<br><br><a href="Question.php?qNum='.$qid.'" class="ITS_ADMIN">'.$qid.'</a></td>'.
                '<td class="PROFILE" >' . $QUESTION.$ANSWER . '</td>'.
                '<td class="PROFILE" >' .$title.'<hr><p style="color: grey">'.$cat.'</p></td>';

        $Estr .=  '</tr>';
    }
    $Estr.= '</table>';
    //echo $Estr;
}
//--- NAVIGATION ------------------------------// 
$current = basename(__FILE__,'.php');
$ITS_nav = new ITS_navigation($status);
$nav     = $ITS_nav->render($current,$course);    
//---------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Course</title>
        <!---->
        <link rel="stylesheet" href="css/ITS.css" 		 type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_course.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/login.css" 	 type="text/css" media="screen">
        <link rel="stylesheet" href="css/admin.css" 	 type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_BOOK.css" 	 type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_test.css" 	 type="text/css" media="screen">
        <link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
        <!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
        <script src="js/ITS_admin.js"></script>
        <script src="js/AJAX.js"></script>
        <script src="js/ITS_AJAX.js"></script>
        <script src="js/ITS_screen2.js"></script>
        <script src="js/ITS_QControl.js"></script>
        <script src="js/ITS_book.js"></script>
        <script src="tagging/ITS_tagging.js"></script>
        <script src="rating/forms/star_rating.js"></script>
        <style>
            #select_class { margin-top: 2em; }
            .ui-widget-header   { background: #aaa; border: 2px solid #666; }
            .ui-dialog-titlebar { background: #aaa; border: 2px solid #666; }
            .ui-dialog-content  { text-align: left; color: #666; padding: 0.5em; }
            .ui-button-text { color: #00a; }
        </style>
<?php include 'js/ITS_course_jquery.php';?>
    </head>
    <body>
        <div id="framecontent">
            <!---************* NAVIGATION ******************--->
<?php echo $nav;?>
            <!---******************************************--->
            <div class="innertube">
<?php echo $subnav;?>
            </div>
            <!---******************************************--->
        </div>
        <div id="maincontent">
            <?php
//-----------------------------------------------------------//
            echo '<div id="userProfile">'.$Estr.'</div>';
//echo $section.'--'.$sid.'--'.$status.'--'.$ch.'<p>';
            /*
$c       = new ITS_course('Fall_2011');
$courses = $c->render_courses();
echo '<center>'.$courses.'</center>';

// DELETE COURSE
if ( isset($_POST['delete_class']) ){
  $del = $c->delete();
	echo $del;
}
            */
//-----------------------------------------------------------//
//--- begin timer ---//
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;
            $totaltime = ($endtime - $starttime);
//------------------//
//--- FOOTER ------------------------------------------------//
            $ftr = new ITS_footer($status,$LAST_UPDATE,$totaltime);
            echo $ftr->main();
//-----------------------------------------------------------//
?>
        </div>
    </body>
</html>
