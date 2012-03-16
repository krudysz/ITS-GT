<?php
/* ============================================================= */
$ITS_version = '165';
$LAST_UPDATE = 'July-13-2011';
/* ============================================================= */

include("classes/ITS_timer.php");

require_once("config.php");
require_once("classes/ITS_statistics.php");
require_once("classes/ITS_screen2.php");
require_once("classes/ITS_book.php");
require_once("classes/ITS_footer.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
$id     = $_SESSION['user']->id();  //$id = 1;
$status = 'admin';
$role   = 'admin';
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
    <script src="js/ITS_admin.js" type="text/javascript"></script>
	<script src="js/ITS_book.js"  type="text/javascript"></script>
    <script src="js/ITS_AJAX.js"  type="text/javascript"></script>
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
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="4"> 4</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="5"> 5</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="6"> 6</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="7"> 7</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="8"> 8</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="9"> 9</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="10"> 10</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="11"> 11</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="12"> 12</a></li>
<li><a href="#" onclick="ITS_book_select(this)" name="chapter" value="13"> 13</a></li>
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
<div id="bookContainer" >
<?php
//=========================================================//	
global $db_dsn,$tb_name,$mimetex_path;
 
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
//=========================================================//	
$ch = 1;
$meta = 'equation';
$x = new ITS_book('dspfirst',$ch,$meta,$mimetex_path);
$o = $x->main();
echo $o.'<p>';
//-----------------
	//=====================================================================//
	  /*
		$query = 'SELECT id,chapter,section,paragraph,content,tag_id,name FROM dspfirst WHERE meta="'.$meta.'" AND chapter='.$ch;
    $res = $mdb2->query($query);
    if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
    $pars = $res->fetchAll();

    $book = '<div class="ITS_BOOK"><p>';	//
    for ($i = 0; $i <= count($pars)-1; $i++) {
    
    if (empty($pars[$i][5])) { $pars[$i][5] = '""'; }
    
    $query = 'SELECT name FROM tags WHERE id IN ('.$pars[$i][5].')'; // echo '<p>'.$i.' '.$query.'<p>';
    $res = $mdb2->query($query);
    if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
    $name = $res->fetchAll();
			 
    	switch ($meta) {
    	//----------------------//
    		case 'paragraph':
    			//----------------------//
    			$tags = '';
          for ($t = 0; $t <= count($name)-1; $t++) {
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
    			//$book = $book.'<div class="ITS_PARAGRAPH">'.$pars[$i][4].'</div><br>';
    			$book .= $pars[$i][4].'<br>'.$tags.'<p>';		
    			break;
    			//----------------------//
    		case 'equation':
    		  //----------------------//
    			$tags = ''; //array();
          for ($t = 0; $t <= count($name)-1; $t++) {
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
    			//if ($i==0) { $book .= '<hr class="ITS_hr">';}
    			$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font></td><td><img class="ITS_EQUATION" src="'.$mimetex_path.$pars[$i][4].'"/></td></tr><tr><td colspan="2">'.$tags.'</td></tr></table></div>';
    			break;
    			//----------------------//
    		case 'math':
    			//----------------------//
					
          //$str ="REFERENCE#fig:dtsig#REFERENCE";      
          //$str = preg_replace("/(a)(.*)(d)/","a($2)d",$str);  
          // a(s)dfd a()dsfd a(aaa)da(s)d
					//$book = preg_replace("/I want (\S+) one/", "$1 is the one I want", "I want that one") . "\n";
					
    			$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font><td><img class="ITS_EQUATION" src="'.$mimetex_path.$pars[$i][4].'"/></td></tr></table></div>';			
					break;
    			//----------------------//
    		case 'image': // NO SCORE
    			//----------------------//			
          $tags = ''; //array();
          for ($t = 0; $t <= count($name)-1; $t++) {
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
					
    			//if ($i==0) { $book .= '<hr class="ITS_hr">';}
					$ch = $pars[$i][1];
					$sec = $pars[$i][2];
					$fig = explode('/',$pars[$i][6]);
					$fN = count($fig);
					$fname = trim(str_replace('}','',$fig[$fN-1]));
					$chs = sprintf('%02d',$ch);
					$imn = sprintf('%02d',($i+1));

					$img_source = 'FILES/SP1Figures/Ch'.$chs.'/Fig'.$chs.'-'.$imn.'_'.$fname.'.png';
					//echo $img_source.'<p>';
					//die($img_source);
					$caption = $pars[$i][4];
					//$caption = preg_replace("/($)(.*)?($)/U",'<img class="ITS_EQUATION" src="'.$mimetex_path.'$2"/></a>"',$caption);
					$caption = preg_replace("/(REFERENCE#)(.*)?(#REFERENCE)/U","<a>$2</a>",$caption);
					
          $img_str = '<div class="ITS_Image"><center><img src="'.$img_source.'" alt="'.$img_source.'"></center><br><div class="ITS_Caption">'.$caption.'</span></div></div>';
					$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font></td><td>'.$img_str.'</td></tr><tr><td colspan="2">'.$tags.'</td></tr></table></div>';	
					break;
    			//----------------------//
    	}
    }
    $book = $book.'</div><p>';	
    				
		echo $book;
		*/
?>
</div>
<!-- footer -->
  <div id="footerContainer">		
    		<?php include '_include/footer.php';?>
	</div>
</div>
</body>
</html>
