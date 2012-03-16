<?php
include("classes/ITS_timer.php");

require_once("config.php");
require_once("classes/ITS_question_table.php");
require_once("classes/ITS_survey.php");
require_once("classes/ITS_menu.php");
require_once("classes/ITS_message.php");
require_once("classes/ITS_statistics.php");
require_once("classes/ITS_screen.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

die('ss');
$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
$id = $_SESSION['user']->id();  //$id = 1;
$status = 'admin';
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head> 
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS</title>
	<!---->
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
	<!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
  <script src="js/ITS_admin.js"></script>
	<script src="js/ITS_book.js"></script>
  <script src="js/ITS_AJAX.js"></script>
</head>

<body>
<div id="pageContainer">
<!-- NAVIGATION ----------------------------------------------->
<div id="bookNavContainer">
<div id="chContainer">
<span id="chText">CHAPTER</span>
<ul id="chList">
<li id="active"><a href="#" onclick="ITS_book_select(this)" name="chapter" value="1" id="current"> 1</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="2"> 2</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="3"> 3</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter"value="4"> 4</a></li>
</ul>
</div>
<ul id="metaList"> 
<li id="active"><a href="#" onclick="ITS_book_select(this)" name="meta" value="paragraph" id="current">book</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="meta" value="equation">equations</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="meta" value="math">math symbols</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="meta" value="image">images</a></li>
</ul>
</div>
<!-- CONTENT ----------------------------------------------->
<div id="bookContainer">
<?php
//=========================================================//	
global $db_dsn,$tb_name;
 
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
//=========================================================//	
$ch = 1;
$meta = 'image';

$query = 'SELECT id,chapter,section,paragraph,content,tag_id FROM dSPFirst WHERE meta="'.$meta.'" AND chapter='.$ch;
$res = $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$pars = $res->fetchAll();

$book = '<div class="ITS_BOOK"><p>';	//
for ($i = 0; $i <= count($pars)-1; $i++) {

$query = 'SELECT name FROM tags WHERE id IN ('.$pars[$i][5].')';
$res = $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$name = $res->fetchAll();

//var_dump($name[0][0]); die();
// die(implode(",",$name));
	//echo '<p>ch '.$pars[$i][1].'|'.$pars[$i][2].'.'.$pars[$i][3].'<p>';			 
	switch ($meta) {
	//----------------------//
		case 'paragraph':
			//----------------------//
			$tags = ''; //array();
      for ($t = 0; $t <= count($name)-1; $t++) {
         $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
      }
			//$book = $book.'<div class="ITS_PARAGRAPH">'.$pars[$i][4].'</div><br>';
			$book .= $pars[$i][4].'<br>'.$tags.'<p>';		
			break;
			//----------------------//
		case 'equation':
		case 'math':
			//----------------------//
			if ($i==0) { $book .= '<hr class="ITS_hr">';}
			$book .= '<font color="blue">'.$pars[$i][0].'</font> <img class="ITS_EQUATION" src="/cgi-bin/mimetex.exe?'.$pars[$i][4].'"/>'.$tags.'<hr class="ITS_hr">';
			break;
			//----------------------//
		case 'image': // NO SCORE
			//----------------------//
			$tags = ''; //array();
      for ($t = 0; $t <= count($name)-1; $t++) {
         $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
      }
			//$book = $book.'<font color="blue">'.$pars[$i][0].'</font> <img class="ITS_EQUATION" src="/cgi-bin/mimetex.exe?'.$pars[$i][4].'"/><hr class="ITS_hr">';
			$book .= '<font color="blue">'.$pars[$i][0].'</font> '.$pars[$i][4].'<p>'.$tags.'<hr class="ITS_hr">';
			break;
	}
}
$book = $book.'</div><p>';
echo $book;
?>
</div>
<!-- footer -->
<div id="footerContainer">		
		<ul id="navlist">
		  <li>ITS v.86</li>
    </ul>
	</div>
</div>
</body>
</html>
