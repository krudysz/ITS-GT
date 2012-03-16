<?php
include "classes/ITS_QTI.php";
// save request parameters. 
$FILES = $_FILES;
$obj = new ITS_QTI(); 
$output = $obj->uploadFile($FILES);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<script src="js/ITS_AJAX.js"></script>
<script src="js/ITS_QControl.js"></script>
<title>Questions Database</title>
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/ITS_QTI.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	
	<style type="text/css">
	  #framecontent   { height:60px; }
    #frameseparator { top: 60px; height: 200px; /*Height of frame div*/ }
    #maincontent    { top: 60px; /*Set top value to HeightOfFrameDiv*/  }
  </style>
	<link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
  <script type="text/javascript" src="plugins/MathJax.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
	<script type="text/javascript">
	$(function() {
      $(".ITS_select").change(function() { document.profile.submit(); });
			$("#select_class").buttonset();
  });
	/*-------------------------------------------------------------------------*/
  $(document).ready(function() { 
     $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
		/*-------------------------------------------------------------------------*/	
		/*-------------------------------------------------------------------------*/						 
/*----------------------*/
  });
	//function doChange() {alert('ch');}
  </script>
 
</head>

<body>
 <div id="framecontent">
<!--************* NAVIGATION ******************-->
 <div id="ITS_navcontainer" style="backgroundcolor:yellow">
<ul id="ITS_navlist">
<li><a href="logout.php">Logout</a></li>
<li><a href="index.php" >ITS</a></li>
<li><a href="Question.php" id="current">Questions</a></li>
<li><a href="Profile.php" >Profiles</a></li>
<li><a href="Logs.php">Logs</a></li>
</ul>
</div> 
<!--******************************************-->
<!-- ********************** -->
<div class="innertube">
</div>
</div>
<!--  ********************** -->
 <div id="maincontent" style="margin: 10px">
<div id="ITS_question_container">

<?php   
 echo $output;
?>
</div>
<p class="center">&nbsp;<b><font color="silver">~&diams;~</font></b>&nbsp;</p>
</div> 
<!--  ********************** -->

</body>
</html>
