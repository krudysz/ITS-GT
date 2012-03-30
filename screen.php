<?php
/* ============================================================= */
$ITS_version = '189';
$LAST_UPDATE = 'Mar-30-2012';
/* ============================================================= */
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");          // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate");        // Must do cache-control headers 
header("Pragma: no-cache");

include ("classes/ITS_timer.php");

require_once ("config.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_timer.php");
require_once ("classes/ITS_query.php");
require_once ("classes/ITS_statistics.php");
require_once ("classes/ITS_footer.php");
require_once ("classes/ITS_tag.php");

/* -- SCORING module ----------------------------------- */
require_once ("classes/ITS_computeScores.php");
require_once ("classes/ITS_book.php");
/* -- TAGGING module ----------------------------------- */
//require_once ("plugins/tagging/ITS_tagInterface.php");
/* -- RATING module ------------------------------------ */
//require_once ("plugins/rating/ITS_rating.php");
/* ----------------------------------------------------- */

require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
//echo $timer;
session_start();

// return to login page if not logged in
abort_if_unauthenticated();
//##########################################//
//$id = 670; //622;508 //
$id = $_SESSION['user']->id();

//echo $id;die();
$status = $_SESSION['user']->status();
$view   = TRUE;  // VIEW: TRUE | FALSE => "Question" tab closed
$index_max  = 7;

$tset = mktime(11, 0, 0, 01, 01, 2012);  // 11 AM, Dec-12, 2011
//echo date('h-i-s-j-m-y',time()).' - '.$tstart.'<p>';
//echo date("D M j G:i:s T Y",$tstart);

/*  
if (time()<$tset) { $index_hide = 7; }
else 			  { $index_hide = 8; }
*/

$index_hide = 4;

//##########################################//
if (isset($_POST['role'])) {
    $role = $_POST['role'];
} else {
    switch ($status) {
        case 'admin': $role = 'admin';
            break;
        default: $role = 'student';
            break;
    }
}
$screen = new ITS_screen2($id, $role, $status,$index_hide+1);
//$menu    = new ITS_menu(); //echo $menu->main();
//$message = new ITS_message($screen->lab_number, $screen->lab_active);

$_SESSION['screen'] = $screen;
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <META HTTP-EQUIV="Expires" 	    CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
        <META HTTP-EQUIV="Pragma"       CONTENT="no-cache">
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
        <title>ITS</title>
        <!-- rating module -->
        <link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet">

        <link rel="stylesheet" href="css/ITS_logs.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
        <link rel="stylesheet" href="plugins/tagging/ITS_tagging.css" type="text/css" media="screen">
        <link rel="stylesheet" href="plugins/rating/ITS_rating.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_DEBUG.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_index4.css" type="text/css" media="screen">	
        <link rel="stylesheet" href="css/ITS_tag.css" type="text/css" media="screen">        	
        <style type="text/css">
            #feedback { font-size: 1.4em; }
            #selectable .ui-selecting { background: #FECA40; }
            #selectable .ui-selected  { background: #F39814; color: white; }
            #selectable { list-style-type: none; margin: 0; padding: 0;    }
            #selectable li { margin: 3px; padding: 1px; float: left; width: 200px; height: 80px; font-size: 4em; text-align: center; }
        </style>
        <!--[if IE 6]>
      	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
      	<![endif]-->
        <script src="js/ITS_admin.js" type="text/javascript"></script>
        <script src="js/AJAX.js" type="text/javascript"></script>
        <script src="js/ITS_AJAX.js" type="text/javascript"></script>
        <script src="js/ITS_screen2.js" type="text/javascript"></script>
        <script src="js/ITS_QControl.js" type="text/javascript"></script>
        <script src="js/ITS_book.js" type="text/javascript"></script>
        <script src="plugins/tagging/ITS_tagging.js" type="text/javascript"></script>

        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
        <script type="text/javascript">
            function UR_Start() {
                UR_Nu = new Date;
                UR_Indhold = showFilled(UR_Nu.getHours()) + ":" + showFilled(UR_Nu.getMinutes()) + ":" + showFilled(UR_Nu.getSeconds());
                document.getElementById("ur").innerHTML = UR_Indhold;
                setTimeout("UR_Start()",1000);
            }
            function showFilled(Value) { return (Value > 9) ? "" + Value : "0" + Value; }

            var last, diff;
            $('#contentContainer.innerHTML').change(function() { 
                //alert('a');
                /*
                  var date1 = new Date();
                  var ms1 = date1.getTime();  //alert(ms1);
                  sessionStorage.setItem('TIME0',ms1); 
                 */
            });
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
        <link rel="stylesheet" type="text/css" href="plugins/rating/ITS_rating.css" media="screen">
        <link rel="stylesheet" type="text/css" href="plugins/rating/jquery.ui.stars.css?v=3.0.0b38" media="screen">
        <script type="text/javascript" src="plugins/rating/jquery.ui.stars.js?v=3.0.0b38"></script>

        <!--- RATING END   -->
        <?php
        //include 'js/TMP.php';
        include 'js/ITS_jquery.php';
        ?>
        <script type="text/javascript" src="js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        <link rel="stylesheet" type="text/css" href="js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript">
$(document).ready(function() {
	/* This is basic - uses default settings */
	$("a#single_image").fancybox();	
	/* Using custom settings */	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});
	/* Apply fancybox to multiple items */	
	$("a.group").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false
	});
});
</script>
    </head>
    <body onload="UR_Start()">
        <div id="pageContainer">
            <!-- MENU -------------------------------------------------->
            <div id="menuContainer">
                <div class="logout"><a href="logout.php">Logout</a></div>
                <!--
	  <div class="icon" id="Minst_icon">I</div>
               <p class="ITS_instruction"><img src="images/matching_example1.png" style="position:relative;max-width:100%"></p>
		<div class="icon" id="Tag_icon">Tag</div>
		-->
                <div class="icon" id="instructionIcon" onClick="ITS_MSG(1)"><tt>?</tt></div>
                <div class="icon" id="messageIcon"     onClick="ITS_MSG(1)">&para;</div>
            </div>
            <!-- myScore ---------->
            <?php //die($status);
            switch ($status) {
                case 'BMED6787':
                    $chUser   =  1;
                    $MyScores = '';
                    break;
                default:
                /* -- */
                    $MyScores = '<div id="scoreContainer"><span>&raquo;&nbsp;My Scores</span></div>' .
                            '<div id="scoreContainerContent">';

                    switch ($role) {
                        case 'admin':
                        case 'instructor':
                            $chUser = 1;
                            $chMax = 15;
                            break;
                        default:
                            $chUser = 1;
                            $chMax = $index_max;
                    }

                    $chArr = range(1, $chMax);
                    /* -- */
                    $score = new ITS_computeScores($id, $role, $chArr); //,$ch);

                    $_SESSION['score'] = $score;
                    $str = $score->renderChapterScores();  //($chMax)
                    $MyScores .= $str . '</div>';
            }
            echo $MyScores;
            ?>
            <!-- NAVIGATION ----------------------------------------------->
            <!-- onclick="javascript:ITS_book_select(this)" --------------->
            <p>
            <div id="bookNavContainer">
                <div id="chContainer">
                    <?php
//id="current" id="active" (in li)
//onclick="javascript:ITS_chapter_select(this)"
//die($status);
                    /* -------------------- */
                    switch ($status) {
                        /* ----------------- */
                        case 'BMED6787':
                        /* ----------------- */
                            $chUser = 1;
                            $mode   = 'survey';
                            $screen->mode = $mode;
                            $Header = 'Survey';
                            $chList = '<ul id="chList">';
                            $chList .= '<li><a href="#" class="chapter_index" id="Survey02" name="chapter" value="1">Survey</a></li>';
                            break;
                        /* ----------------- */
                        default:
                        /* ----------------- */
                            $mode   = 'question';             // index | practice | question
                            $Header = 'AAA ' . $chUser;
                            $chList = '<span id="chText">MODULE</span><ul id="chList">';
                            //$chList .= '<li><a href="#" class="chapter_index" name="chapter" value="0">Introduction</a></li>';
                            switch ($role) {
                                case 'admin':
                                case 'instructor':
                                    for ($i = 1; $i <= $chMax; $i++) {
                                        if ($i==($index_hide+1)) {
                                            $idx_id = 'id="current"';
                                        }
                                        else {
                                            $idx_id = '';
                                        }
                                        $chList .= '<li><a href="#" class="chapter_index" name="chapter" '.$idx_id.' value="' . $i . '">' . $i . '</a></li>';
                                    }
                                    $view = TRUE;
                                    $r = TRUE; // role
                                    break;
                                default:
                                //var_dump($chArr);
                                //die();
                                    for ($i = 0; $i < count($chArr); $i++) {
                                        if ($i==$index_hide) {
                                            $idx_id = 'id="current"';
                                        }
                                        else {
                                            $idx_id = '';
                                        }
                                        $chList .= '<li><a href="#" class="chapter_index" name="chapter" '.$idx_id.' value="' . $chArr[$i] . '">'.$chArr[$i].'</a></li>';
                                    }
                                    $r = FALSE;
                                    break;
                            }
                        //$chList .= '<li><a href="#" class="chapter_index" id="Survey01" name="chapter" value="14">Survey</a></li>';
                        /* -------------------- */
                    }
//chapter_index'.$i.'
//id="chapter_index'.$chArr[$i].'"
                    echo $chList;
//die('da');
                    ?>
                    </ul>
                    <input type="hidden" id="index_hide" value="<?php echo $index_hide;?>">
                </div>
                <?php
//-- TEST -------------------------------------------------->
//$s = new ITS_computeScores($id);
//$str = $s->renderLabScores();
//echo $str;
                ?>
            </div>
            <div id="page">
                <!-- CONTENT ----------------------------------------------->
                <?php
//echo $screen->main();
//--- resources ---//
//$arr = array(2,"",NULL);
//echo array_sum($arr);die('done');
                //--------$screen->chapter_number = $chUser;
                $meta = 'image';

                /*
              $x = new ITS_book('dspfirst',$ch,$meta,$mimetex_path);
              $o = $x->main();
              echo $o.'<p>';

              <li id="Practice" name="header" view="<?php echo intval($view); ?>" r="<?php echo intval($r); ?>"><a href="#">Practice</a></li> 
                */
//----------------//
                ?>
                <div id="navContainer">
                    <ul id="navListQC">                     
                        <li id="Question" name="header" view="<?php echo intval($view); ?>" r="<?php echo intval($r); ?>" ch="<?php echo ($index_hide+1); ?>"><a href="#" id="current">Questions</a></li>
                        <li id="Review"   name="header" view="<?php echo intval($view); ?>" r="<?php echo intval($r); ?>" ch="<?php echo ($index_hide+1); ?>" style="margin-left: 50px;"><a href="#">Review</a></li>                         
                    </ul>
                </div>
                <!-- end div#navContainer -->
                <?php

                //echo '<ul><li>ITS Modules 1 - 4 have closed.</li><li>Your answers for Modules 1-4 are available for review.</li></ul>';
                $screen->screen       = 4;
                $screen->term_current = 'Spring_2012';

                echo $screen->main($mode);
                /*
                echo '<a class="group" href="excel_icon.png"><img src="excel_icon.png" alt=""/></a>'
                    .'<a id="inline" href="#data">This shows content of element who has id="data"</a>'
				    .'<div style="display:none"><div name="data">Some title</div></div>';
                */
//echo $screen->reviewMode(1,0);
                ?>              
            </div>
            <!-- FOOTER -->
            <?php include '_include/footer.php'; ?>
            <!-- end FOOTER -->
        </div>
        <!-- end div#page -->
    </body>
</html>
