<?php
include("classes/ITS_timer.php");

require_once("config.php");
require_once("classes/ITS_question_table.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();
//------------------------------------------//
//<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
//<link rel="stylesheet" href="css/question.css">
//<div id="banner"><?php echo $menu1_str </div>
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<title>Home</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/question.css">
<script src="js/ITS_admin.js"></script>
</head>
<body class="ITS">
<?php 
include("menu1.php");
//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
//$id = 729; //622;508 //
$id = $_SESSION['user']->id();
$id = 689;
$front = new ITS_front($id,'Fall_2009',$status);
?>

<div class="ITS_message"><q>Concepts are the building blocks of thought. Without them, induction would be impossible because everything would be unique.</q></div>
<p>
<div class="main">
		<div id="exercisesContainer">
		<p><?php echo $front->exercisesContent();?></p>
	</div>
	<div id="labsContainer"><?php echo $front->labsContent();?></div>
</div>
<?php 
die();

$tr = new ITS_statistics($id,'Fall_2009',$status);
$tr->main();
/**Nab edit start**/
if(!isset($_GET['title']))
	$titleVar = "ITS";
else
	$titleVar = $_GET['title'];?>
<!-- <div class="top_header"><?php echo $titleVar ?></div> -->
<?php
/*	$lablinks = array();
	$lablinks[] = 'LAB EXERCISES';
	$labs = array();
	$chapterlinks = array();
	$chapterlinks[] = 'CHAPTERS FROM TEXTBOOK';
	$chapters = array();
	$questions = array(); */
	
	// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }		
	//$this->mdb2 = $mdb2;
		
	//-- obtain activity: name
 	/*	$query = 'SELECT DISTINCT name,active FROM activity WHERE term="Fall_2009"';
 		//$res = $this->mdb2->query($query);
		$res = $mdb2->query($query);
		if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
		$activiy_info = $res->fetchAll();
		for ($i = 0; $i <= count($activiy_info)-1; $i++) { //count($activiy_info)-1
			  $activiy_name = $activiy_info[$i][0];
			  $labs = $activiy_name;
			  $lablinks[] = '<a href="http://localhost/ITS/Home.php?title='.$activiy_name.'">'.$activiy_name.'</a>';
		}
	$LabListTab = new ITS_table('LabList',1,14,$lablinks,array(15,15,15,15,15,15,15,15,15,15,15,15,15,15),'InnerTable');
	echo '<div class="next_header">'.$LabListTab->str.'</div>';
	
	for($i=65;$i<=67;$i++) {
		$chapters[] = 'Appendix '.chr($i);
		$chapterlinks[] = '<a href="http://localhost/ITS/Home.php?title=Appendix '.chr($i).'">Appendix '.chr($i).'</a>';
	}
	$ChapterListTab = new ITS_table('ChapterList',1,4,$chapterlinks,array(20,20,20,20),'InnerTable');
	echo '<div class="next_header">'.$ChapterListTab->str.'</div><br>';*/
	
	$query1 = 'SELECT question FROM webct WHERE id IN (SELECT question_id FROM activity WHERE term="Fall_2009" AND name="'.$titleVar.'")';
 		//$res = $this->mdb2->query($query);
		$res1 = $mdb2->query($query1);
		if (PEAR::isError($res1)) {throw new Question_Control_Exception($res1->getMessage());}
		$ques_info = $res1->fetchAll();
		for ($i = 0; $i < count($ques_info); $i++) {
			$ques_name = $ques_info[$i][0];
			$questions[] = $ques_name;
	}
	
	/*$query2 = 'SELECT chapter_name FROM resource_book';
 		//$res = $this->mdb2->query($query);
		$res2 = $mdb2->query($query2);
		if (PEAR::isError($res2)) {throw new Question_Control_Exception($res2->getMessage());}
		$chp_info = $res2->fetchAll();
	for ($i = 0; $i <= count($chp_info)-1; $i++) { //count($activiy_info)-1
			  $chp_name = $chp_info[$i][0];
			  $chapters[] = $chp_name;
			  $chapterlinks[] = '<a href="http://localhost/ITS/Home.php?title='.$chp_name.'">'.$chp_name.'</a>';
	} */
		
	if($titleVar=="Appendix A") {
		$query2 = 'SELECT question FROM webct WHERE category = "Appendix A"';
 		//$res = $this->mdb2->query($query);
		$res2 = $mdb2->query($query2);
		if (PEAR::isError($res2)) {throw new Question_Control_Exception($res2->getMessage());}
		$ques_info = $res2->fetchAll();
		for ($i = 0; $i < count($ques_info); $i++) {
			$ques_name = $ques_info[$i][0];
			$questions[] = $ques_name;
			
		}	
		$iFrameLink = "<iframe src=\"http://www.google.com\" frameborder=\"0\" width=\"1050\" height=\"700\"></iframe>";
	}
	elseif($titleVar>=1&&$titleVar<=12) {
		$iFrameLink = "<iframe src=\"ITS_pre_lab.php?activity='".$titleVar."'\" frameborder=\"0\" width=\"1050\" height=\"1000\"></iframe>";
	}
	
//$labs = array('<a href="http://localhost/ITS/Home.php?title=Lab01">Lab01</a>','<a href="http://localhost/ITS/Home.php">Lab02</a>','<a href="http://localhost/ITS/Home.php">Lab03</a>','<a href="http://localhost/ITS/Home.php">Lab04</a>');
/*for($i=1;$i<=12;$i++) {
	if($i<10)
		$labs[] = '<a href="http://localhost/ITS/Home.php?title=Lab0'.$i.'">Lab0'.$i.'</a>';
	else
		$labs[] = '<a href="http://localhost/ITS/Home.php?title=Lab'.$i.'">Lab'.$i.'</a>';
}*/

//$LabListTab = new ITS_table('LabList',1,13,$lablinks,array(15,15,15,15,15,15,15,15,15,15,15,15,15),'InnerTable');
//$ChapterListTab = new ITS_table('ChapterList',1,4,$chapterlinks,array(20,20,20,20),'InnerTable');
//$QuesListTab = new ITS_table('QuesList',count($questions),1,$questions,array(50),'OuterTable');
//$gg = new ITS_table('a',1,3,array($LabListTab->str,$ChapterListTab->str,$QuesListTab->str),array(12,12,80),'OuterTable');
//echo '<div id="frameContainer" class="top_header">'.$QuesListTab->str.'</div>';?>
<div id="frameContainer" class="top_header">
<script type="text/javascript">
onload=function(){
var el=document.getElementById("frameContainer")
el.innerHTML="<iframe src=\"ITS_pre_lab.php?activity=<?php echo $titleVar; ?>\" frameborder=\"0\" width=\"1050\" height=\"700\"></iframe>"
}
</script>
</div>
<?php

/*echo '<div>';
for($i=0; $i<count($labs); $i++) {
	if(strcmp($titleVar,$labs[$i])) {
		for($i=0;$i<count($questions);$i++) {
			echo "<br>QUESTION". $questions[$i];
		}
	} 
} 
echo '</div>';*/ ?>
<!-- Nab edit end-->

<?php
//$dist = '<img src="ITS_plot.php" class="ITS_list">';
//echo $dist;
//$timer->etime();
//echo '<p><a href="ITS_pre_lab.php?activity=13">Survey</a><p>';
//echo '<p><a href="survey.php">Survey</a><p>';

//----------------------//
//echo str_repeat(" ",256)."<pre>";flush();
//echo "working ...<br>\r\n"; flush(); sleep(1);
  //$dist = '<img src="phpimg/ITS_signal.php?t=tr&d=-1,1,4,5,3&title=p(t)" class="ITS_bar">';
 // echo $dist;
//----------------------//
//$timer->etime();
?>
</div>
</body>
</html>
