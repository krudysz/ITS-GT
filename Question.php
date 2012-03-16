<?php
$LAST_UPDATE = 'Mar-8-2012';
//=====================================================================//
/* 					
     Author(s): Gregory Krudysz, Khyati Shrivastava
     Last Revision: Gregory Krudysz, Mar-8-2012
*/
//=====================================================================//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate");            // Must do cache-control headers 
header("Pragma: no-cache");

include("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");
require_once("classes/ITS_navigation.php");
require_once("classes/ITS_footer.php");
require_once("classes/ITS_tag.php");
require_once("classes/ITS_search.php");
//require_once("classes/ITS_question2.php");

session_start();
// return to login page if not logged in
abort_if_unauthenticated();
//--------------------------------------// 
$status = $_SESSION['user']->status();

if ($status == 'admin' OR $status == 'instructor') {
// connect to database
$mdb2 = & MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)) {
    throw new Question_Control_Exception($mdb2->getMessage());
}
// DEBUG:	echo '<p>GET: '.$_GET['qNum'].'  POST: '.$_POST['qNum'];
//--- determine question number ---//
if (isset($_GET['qNum'])) {
    $qid = $_GET['qNum'];
    $from = 'if';
} elseif (isset($_POST['qNum'])) {
    $qid = $_POST['qNum'];
    $from = 'if';
} elseif (isset($_SESSION['qNum_current'])) {
    $qid = $_SESSION['qNum_current'];
} else {
    $qid = 2;
    $from = 'else';
}
//------- CHAPTER -------------//
$ch_max = 13;

if (isset($_GET['ch'])) {
    $ch = $_GET['ch'];
} else {
    $ch = 0;
}
$chapter = 'Chapter #<select class="ITS_select" name="ch" id="select_chapter" onchange="javascript:this.submit()">';
for ($c = 0; $c <= ($ch_max + 1); $c++) {
    if ($ch == $c) {
        $sel = 'selected="selected"';
    } else {
        $sel = '';
    }
    if ($c == 0) {
        $hc = 'ANY';
        $class_option = 'highlight';
    } elseif ($c == ($ch_max + 1)) {
        $hc = 'ALL';
        $class_option = 'highlight';
    } else {
        $hc = $c;
        $class_option = '';
    }
    $chapter .= '<option class="' . $class_option . '" value="' . $c . '" ' . $sel . '>' . $hc . '</option>';
}
$chapter .= '</select>';

//------- TYPE ---------------//
$Qtype_arr = array('ALL', 'Multiple Choice', 'Matching', 'Calculated', 'Short Answer', 'Paragraph');
$Qtype_db  = array('','MC','M','C','S','P');
if (isset($_GET['type'])) {
    $qt = $_GET['type'];
} else {
    $qt = 0;
}
$type = 'Type <select class="ITS_select" name="type" id="select_type" onchange="javascript:this.submit()">';
for ($t = 0; $t < count($Qtype_arr); $t++) {
    if ($qt == $t) {
        $tsel = 'selected="selected"';
    } else {
        $tsel = '';
    }
    $type .= '<option value="' . $t . '" ' . $tsel . '>' . $Qtype_arr[$t] . '</option>';
}
$type .= '</select>';

// update SESSION
$_SESSION['qNum_current'] = $qid;
$form = $chapter . '&nbsp;&nbsp;' . $type;
//--------------------------------------//
/*
  // QUERY
  $ALL = $ch_max+1;
  switch ($ch) {
  case 0:
  $query_chapter = 'AND category NOT IN (';
  for ($n=1;$n<=$ch_max;$n++) {
  if ($n < 10) { $nn = '0'.$n; }
  else         { $nn = $n;     }
  $query_chapter .= '"PreLab'.$nn.'","Lab'.$nn.'","Chapter'.$nn.'"';
  if ($n<$ch_max) { $sep = ','; }
  else 						{ $sep = ')'; }
  $query_chapter .= $sep;
  }
  break;
  case $ALL: $query_chapter = ''; break;
  default:
  if ($ch < 10) { $chs = '0'.$ch; }
  else          { $chs = $ch;  }
  $query_chapter = 'AND category IN ("PreLab'.$chs.'","Lab'.$ch.'","Chapter'.$ch.'")';
  }
  switch ($qt) {
  case 0:  $query_type = 'qtype IN ("MC","M","C","S","P") '; break;
  default: $query_type = 'qtype = "'.$Qtype_db[$qt].'" ';
  }
  $qindex = 0;
  // look for LIST of question
  //$query = 'SELECT id,title,image,category,tag_id FROM webct WHERE '.$query_type.$query_chapter;
  $query = 'SELECT id,title,image,category,answers FROM webct WHERE '.$query_type.$query_chapter;
  //echo $query; die();

  $res =& $mdb2->query($query);
  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $qs = $res->fetchAll();

  if (empty($qs[$qindex][1])) { $title = '';              }
  else 												{ $title = $qs[$qindex][1]; }
  if (empty($qs[$qindex][2])) { $image = '';              }
  else 												{ $image = $qs[$qindex][2]; }
  if (empty($qs[$qindex][3])) { $category = '';              }
  else 												{ $category = $qs[$qindex][3]; }
  if (empty($qs[$qindex][4])) { $answers  = '';              }
  else 												{ $answers  = $qs[$qindex][4]; }
  if (empty($qs[$qindex][5])) { $tags = ''; }
  else {
  $query = 'SELECT name FROM tags WHERE id IN ('.$qs[$qindex][5].')';  //echo $query;
  $res =& $mdb2->query($query);
  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $tagNames  = $res->fetchCol();
  $tags = implode(',',$tagNames);
  }

  $Nqs = count($qs);
  /// QUERY SEARCH
  if ($Nqs){
  $qid = $qs[$qindex][0];
  $tagList = '';
  if (!empty($tags)) {
  for ($i=0; $i < count($tagNames); $i++) {
  //$tagList .= '<input type="button" class="logout" value="'.$tagNames[$i].'">';
  $tagList .= '<span class="ITS_tag">'.$tagNames[$i].'</span>';
  }
  }

  //$tb = new ITS_table('qs',round(sqrt($Nqs)),round(sqrt($Nqs)),$qs,array(),'ITS_ANSWER');
  //echo $tb->str;
  //echo implode(', ',$qs).'<p>';
  //--------------------------------------//
  $Q = new ITS_question(1,$db_name,$tb_name);
  $Q->load_DATA_from_DB($qid);
  //echo $Q->render_QUESTION_check()."<p>";
  $Q->get_ANSWERS_data_from_DB();
  //$Q->get_ANSWERS_solution_from_DB();
  //echo $Q->render_ANSWERS('a',0);
  $meta = $Q->render_data();
  //$mdb2->disconnect();
  //--------------------------------------//
  }
  else { $qid = ''; $meta = '<p><b>- nothing found -</b>'; }
 */

//die();
$Nqs = 'z';
//--------------------------------------//
$Q = new ITS_question(1, $db_name, $tb_name);
$Q->load_DATA_from_DB($qid);
//echo $Q->render_QUESTION_check()."<p>";
$Q->get_ANSWERS_data_from_DB();
//$Q->get_ANSWERS_solution_from_DB();
//echo $Q->render_ANSWERS('a',0);
$meta = $Q->render_data();
//$mdb2->disconnect();
//--------------------------------------//
    switch ($status) {
    case 'admin':
        $adminNav = $Q->render_Admin_Nav($qid, $Q->Q_type,'ITS_button');
        break;
    default:
        $adminNav = '';
        break;
    }
//--- NAVIGATION ------------------------------// 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current);    
//---------------------------------------------//
//'<div class="ITS_navigate">'
$nav2 = '<input id="previousQuestion" class="ITS_navigate_button" type="button" onclick="ITS_QCONTROL(\'PREV\',\'ITS_question_container\')"  name="question_nav" value="<<" qid="' . $qid . '">' .
        '<input type="text" class="ITS_navigate" onkeypress=ITS_QCONTROL(\'TEXT\',\'ITS_question_container\') name="qNum" value="' . $qid . '" id="ITS_QCONTROL_TEXT" Q_num="' . $qid . '">' .
        '<input id="nextQuestion" class="ITS_navigate_button" type="button" onclick="ITS_QCONTROL(\'NEXT\',\'ITS_question_container\')" name="question_nav" value="&gt;&gt;">';
} else {
//* redirect to start page *//
header("Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']),'/\\')."/screen.php");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <script src="js/ITS_AJAX.js"></script>
        <script src="js/ITS_QControl.js"></script>
        <title>Questions Database</title>
        <link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_QTI.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
        <link rel="stylesheet" href="plugins/tagging/ITS_tagging.css" type="text/css" media="screen">
        <link rel="stylesheet" href="plugins/rating/ITS_rating.css" type="text/css" media="screen">	
        <link rel="stylesheet" href="css/ITS_questionCreate.css" type="text/css" media="screen">	
        <link rel="stylesheet" href="css/ITS_index4.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_tag.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ITS_search.css" type="text/css" media="screen">
        <link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
		<!-- <script type="text/javascript" src="MathJax/MathJax.js"></script> -->

        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
        <!-- QTI IMPORTER start ----------------------------------->
        <link href="css/ITS_QTI.css"   rel="stylesheet" type="text/css" />
        <link href="css/swfupload.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="swfupload/swfupload.js"></script>
        <script type="text/javascript" src="plugins/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
        <link href="plugins/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="plugins/uploadify/swfobject.js"></script>
        <!-- CodeMirror ----------------------------------->
        <script src="plugins/CodeMirror-2.0/lib/codemirror.js"></script>
        <link rel="stylesheet" href="plugins/CodeMirror-2.0/lib/codemirror.css">
        <script src="plugins/CodeMirror-2.0/mode/javascript/javascript.js"></script>
        <link rel="stylesheet" href="plugins/CodeMirror-2.0/mode/javascript/javascript.css">
        <!------------------------------------------------->
        <style>
            #dialog-form { background: red; }
            .ui-dialog-form { background: #e1e; }
            .ui-widget-header { background: red; border: 2px solid #666; }
            .ui-dialog-title { background: #aaa;}
            .ui-dialog-titlebar { background: #aaa; border: 2px solid #666; color: #fff; font-size:12pt}
            .ui-dialog-content  { text-align: left; color: #000; padding: 0.5em; }
            .ui-button-text { color: #00a; }
            #myDialog { background: #fff; border-bottom: 2px solid #666; zindex: 5;}
        </style>	
		<?php 
		include 'js/ITS_Question_jquery.php';
		include 'js/ITS_search_jquery.php';
		 ?>
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
              	// export function copied from Nabanita's file
            $("#exportQuestion").live('click', function(event) {
		    var qid = $(this).attr("qid");
			$.post("export.php", { "qid": qid }, function(url){
				url = url.trim();
				if(url.match(/.zip$/)){
					var host = "<?php echo $host?>";
					url='http://'+host+'/'+url;
					window.open(url,"Download","width=350,height=250");
				}
				else
					alert('File was not Found on the server, please retry. Thank you.\n Error Message:'+ url);
			});
		 });		
		 
		 /* Export Many code  */
		  $("#exportManyQuestion").live('click', function(event) {
        $("#exportManyQuestionContainer").css("display","inline");
       
    });      
		 
		 /* Export many code ends */	
        </script>
    </head>
    <body>
        <!---===========================================--->
        <div id="framecontent">
            <!---************* NAVIGATION ******************--->
            <?php echo $nav;?>
            <!---******************************************--->
            <div class="innertube">
                <!---******************************************--->
                <form id="question" name="question" action="Question.php" method="get">
                <?php echo $form . $nav2 . ' &nbsp;Available: <b>' . $Nqs . '</b>'; ?>
                <noscript><input type="submit" value="Submit"></noscript>
                </form>
            </div>
            <!---******************************************--->
        </div>
        <!---===========================================--->
        <div id="maincontent">
            <div id="ITS_question_container">
                <?php echo $adminNav;?>
                <!-- QTI IMPORTER start ----------------------------------->
                <div id="importQuestionContainer" style="display:none">
                    <form id="QTI2form" action="upload_QTIfile.php" enctype="multipart/form-data" method="post">
                        <table><tr>
                                <td><label for="files">QTI file</label></td>
                                <td><input type="file" name="file" id="file"></td>
                                <td><input id="file_upload" name="file_upload" type="file"></td>
                                <td><input type="submit" name="submit" value="Submit" id="QTIsubmit"></td>
                            </tr></table><noscript><input type="submit" value="Submit"></noscript></form>
                </div>
                <!-- QTI IMPORTER end ----------------------------------->
<!-- QTI EXPORTER Many start *********************************-->
                <div id="exportManyQuestionContainer" style="display:none">
                    <form id="exportManyForm" action="exportMultiple.php" enctype="multipart/form-data" method="post">
                        <table><tr>
                                <td><label>Category</label></td>
                                <td><input type="text" name="category_export" id="category_export"></td>
                                <td> Select Question type:</td><td><input type="checkbox" name="ques_type[]" value="MC" /> MC <input type="checkbox" name="ques_type[]" value="C" /> C
                                <input type="checkbox" name="ques_type[]" value="M" />M
                                </td>
                                <td><input type="submit" name="submit" value="Submit" id="QTIExportSubmit"></td>
                            </tr></table><noscript><input type="submit" value="Submit"></noscript></form>
                </div>
                <!-- QTI EXPORTER Many end **********************************-->
                
<?php
//$Q2 = new ITS_question2();
//echo $Q2->render_list();

// RENDER QUESTION
if (!empty($qid)) {
    echo $Q->render_QUESTION() .'<p>'.$Q->render_ANSWERS('a', 2);
}
echo '<div id="metaContainer" class="ITS_meta">'. $meta.'</div><p>';
echo '</div>';

//--- FOOTER ------------------------------------------------//
$ftr = new ITS_footer($status, $LAST_UPDATE, '');
echo $ftr->main();
//-----------------------------------------------------------//
?>
            </div>
            <!----------------------------------------------------------->
    </body>
</html>
