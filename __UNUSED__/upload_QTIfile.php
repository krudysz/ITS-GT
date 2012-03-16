<?php
//=====================================================================//
/*					
	 Last Revision: Dec-06-2010	 
*/
//=====================================================================//
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('include_path',"C:\wamp\bin\php\php5.3.0\pear");

require_once('XML/Unserializer.php');
//require_once('admin/QTI/QTI_2_SQL.php');

$imgpath     = "ITS_FILES/QTI/images";
$qtifilepath = "ITS_FILES/QTI";

$output = '';

//Check if valid file format
if (($_FILES["file"]["type"] == "text/xml")){
  if ($_FILES["file"]["error"] > 0) {
    $output .= "Return Code: " . $_FILES["file"]["error"] . "<br />";
  } 
	else {
    $output .= "Upload: " . $_FILES["file"]["name"] . "<br />"
              ."Type: " . $_FILES["file"]["type"] . "<br />"
              ."Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />"
              ."Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

	// Save QTI.xml file in specified folder
    if (file_exists($qtifilepath ."/" . $_FILES["file"]["name"])) {
      $output .= $_FILES["file"]["name"] . " already exists. ";
    }
    else {
			//DEBUG: echo  $_FILES["file"]["tmp_name"],$qtifilepath. "/" . $_FILES["file"]["name"];
      move_uploaded_file($_FILES["file"]["tmp_name"],$qtifilepath. "/" . $_FILES["file"]["name"]);
      $output .= "Stored in: " . $qtifilepath . "/" . $_FILES["file"]["name"];
	  //copy all files from the directory to the desired folder
	  /*$dirname = dirname($_FILES["file"]["tmp_name"]);
	  $handle = opendir($dirname);
	  echo "<br>DIR-".$dirname;
	  while (false !== ($file = readdir($handle))) {
        echo $file."<br>";
		$newfile = "admin/images/".basename($file).".jpg";
		//if (!copy($file, $newfile)) {
    		//echo "failed to copy $file...\n";
		//}
      }*/
     }
	  $QTIfile = $qtifilepath . "/" . $_FILES["file"]["name"];
	  
	  //Convert QTI to database questions
	  	//------------------------------------------//
	// Extract from QTI
	//------------------------------------------//
	$QTIxml = file_get_contents($QTIfile);
	$options = array('parseAttributes' => TRUE);   
	// Instantiate the serializer  
	$Unserializer = new XML_Unserializer($options);  		 
	// Serialize the data structure  
	$status = $Unserializer->unserialize($QTIxml, FALSE);  		 
	// Check whether serialization worked  
	if (PEAR::isError($status)) {  
	   die($status->getMessage());  
	}  		
	$QTI_Arr = $Unserializer->getUnserializedData();		 
	// Display the PHP data structure  
	//DEBUG: echo '<pre>';  //print_r($QTI_Arr);  echo '</pre>';   
	
	//Save into arrays
	$question = array();
	$cnt = 0;
	foreach($QTI_Arr['assessment']['section']['item'] as $ques) {
		//echo '<pre>';  
		//print_r($ques);  
		//echo '</pre>';
		$question[$cnt]['qcat'] = $ques['title'];  
		 
		if (array_key_exists('itemmetadata', $ques)) {
			$question[$cnt]['qtype'] = $ques['itemmetadata']['qmd_itemtype']['_content'];
		} else {
			$question[$cnt]['qtype'] = "MC";
		}
		
		if(array_key_exists('mattext', $ques['presentation']['material']) && array_key_exists('matimage', $ques['presentation']['material'])) {
			//render text and images
			$question[$cnt]['qtext'] = '';
			
			$textcount = 0;
			$imagecount = 0;
			if(array_key_exists('texttype', $ques['presentation']['material']['mattext'])) {
				$textcount = 1;
			} else {
				foreach($ques['presentation']['material']['mattext'] as $qt) {
					$textcount++;
				}	
			}
			if(array_key_exists('imagtype', $ques['presentation']['material']['matimage'])) {
				$imagecount = 1;
			} else {
				foreach($ques['presentation']['material']['matimage'] as $qi) {
					$imagecount++;
				}	
			}
			//echo 'x'.$imagecount.' ';
			$qtextcount = 0;
			if($textcount>$imagecount)
				$qtextcount = $textcount;
			else
				$qtextcount = $imagecount;
			//echo "x".$qtextcount;
			for($i=0;$i<$qtextcount;$i++) {
				if(array_key_exists($i, $ques['presentation']['material']['mattext'])) {
					$question[$cnt]['qtext'] = $question[$cnt]['qtext'].$ques['presentation']['material']['mattext'][$i]['_content'];
				}
				if(is_array($ques['presentation']['material']['matimage'])) {
					if(array_key_exists('imagtype', $ques['presentation']['material']['matimage'])) {
						if($i==0)
							$question[$cnt]['qtext'] = $question[$cnt]['qtext'].'<img src = "'.$imgpath.'/'.$ques['presentation']['material']['matimage']['uri'].'" />';
					} else if(array_key_exists($i, $ques['presentation']['material']['matimage'])) {
						$question[$cnt]['qtext'] = $question[$cnt]['qtext'].'<img src = "'.$imgpath.'/'.$ques['presentation']['material']['matimage'][$i]['uri'].'" />';
					}
				}
			}	
		} else {
			//render only text
			if(array_key_exists('_content', $ques['presentation']['material']['mattext'])) {
				$question[$cnt]['qtext'] = $ques['presentation']['material']['mattext']['_content'];
			} else {
				if(is_array($ques['presentation']['material']['mattext'])) {
					$question[$cnt]['qtext'] = '';
					foreach($ques['presentation']['material']['mattext'] as $qt) {
						$question[$cnt]['qtext'] = $question[$cnt]['qtext'].$qt['_content'];
					}
				}
			}
		}
		
		if(array_key_exists('rcardinality', $ques['presentation']['response_lid'])) {
			foreach($ques['presentation']['response_lid']['render_choice'] as $qoption) {
				$j = 0;
				foreach($qoption as $qopt) {
					//DEBUG: echo '<pre>';//var_dump($qopt);echo '</pre>';
					if(array_key_exists('mattext', $qopt['material']) && array_key_exists('matimage', $qopt['material'])) {
						//render text and image
						$question[$cnt]['qoptions'][$j] = '';
						$textcount = 0;
						$imagecount = 0;
						if(array_key_exists('texttype', $qopt['material']['mattext'])) {
							$textcount = 1;
						} else {
							foreach($qopt['material']['mattext'] as $qt) {
								$textcount++;
							}	
						}
						if(array_key_exists('imagtype', $qopt['material']['matimage'])) {
							$imagecount = 1;
						} else {
							foreach($qopt['material']['matimage'] as $qi) {
								$imagecount++;
							}	
						}
						//echo 'x'.$imagecount.' ';
						$qtextcount = 0;
						if($textcount>$imagecount)
							$qtextcount = $textcount;
						else
							$qtextcount = $imagecount;
						//echo "x".$qtextcount;
						for($i=0;$i<$qtextcount;$i++) {
							if(array_key_exists($i, $qopt['material']['mattext'])) {
								$question[$cnt]['qoptions'][$j] = $question[$cnt]['qoptions'][$j].$qopt['material']['mattext'][$i]['_content'];
							}
							if(is_array($qopt['material']['matimage'])) {
								if(array_key_exists('imagtype', $qopt['material']['matimage'])) {
									if($i==0)
										$question[$cnt]['qoptions'][$j] = $question[$cnt]['qoptions'][$j].'<img src = "'.$imgpath.'/'.$qopt['material']['matimage']['uri'].'" />';
								} else if(array_key_exists($i, $qopt['material']['matimage'])) {
									$question[$cnt]['qoptions'][$j] = $question[$cnt]['qoptions'][$j].'<img src = "'.$imgpath.'/'.$qopt['material']['matimage'][$i]['uri'].'" />';
								}
							}
						}	
					} else {
						//render either text or image
						if(array_key_exists('mattext', $qopt['material'])){
							$question[$cnt]['qoptions'][$j] = $qopt['material']['mattext']['_content'];
						}
						if(array_key_exists('matimage', $qopt['material'])){
							$question[$cnt]['qoptions'][$j] = '<img src = "'.$imgpath.'/'.$qopt['material']['matimage']['uri'].'" />';
						}	
					}
					$j++;
				}
				
			}	
		}
	
		$k = 0;
		foreach($ques['resprocessing']['respcondition'] as $qweight) {
			if(array_key_exists('setvar', $qweight)) {
				
					foreach($qweight as $qw) {
						if(is_array($qw)) {
							if(array_key_exists('varname', $qw)){
								if(array_key_exists('_content', $qw)){
									$question[$cnt]['qweight'][$k] = $qw['_content'];
								} else {
									$question[$cnt]['qweight'][$k] = '';
								}
								$k++;
							}
						}
					}		
			} else {
				if(array_key_exists('varname', $qweight)) {
					$question[$cnt]['qweight'][$k] = $qweight['_content'];
					$k++;	
				}
			}	
		}
		$cnt++;
	}
	//echo '<pre>';  
	//print_r($question);
	//echo '</pre>';
	//return $question;
	
	//------------------------------------------//
	// DB connect
	//------------------------------------------//
	$host     = "localhost";
	$user     = "root";
	$pass     = "csip";
	$database = "its";
	
	$linkID = mysql_connect($host,$user,$pass) or die("Could not connect to host.");
	mysql_select_db($database,$linkID) or die("Could not find database.");
	
	//------------------------------------------//
	// Insert Questions from QTI to DB
	//------------------------------------------//
	foreach($question as $ques) {
		if($ques['qtype'] == "MC") {
			$opt_count = count($ques['qoptions']);
			//echo '<font color="red">'.$opt_count.'</font>';
			mysql_query("INSERT INTO webct (qtype, question, answers, category) VALUES ('".$ques['qtype']."', '".$ques['qtext']."', '".$opt_count."', '".$ques['qcat']."')") or die("Error1");
			$id = mysql_insert_id();
			switch($ques['qtype']) {
				case 'MC':
					$tblName = "webct_".strtolower($ques['qtype']);
					for($i=1;$i<=$opt_count;$i++) {
						$var1 = "weight$i";
						$$var1 = $ques['qweight'][$i-1];
						$var2 = "answer$i";
						$$var2 = $ques['qoptions'][$i-1]; 
					}
					$temp = $opt_count + 1;
					for($i=$temp;$i<=22;$i++) {
						$var = "weight$i";
						$$var = ""; 
						$var2 = "answer$i";
						$$var2 = "";
					}
					$q = "INSERT INTO ".$tblName." (id, weight1, answer1, weight2, answer2, weight3, answer3, weight4, answer4, weight5, answer5, weight6, answer6, weight7, answer7, weight8, answer8, weight9, answer9, weight10, answer10, weight11, answer11, weight12, answer12, weight13, answer13, weight14, answer14, weight15, answer15, weight16, answer16, weight17, answer17, weight18, answer18, weight19, answer19, weight20, answer20, weight21, answer21, weight22, answer22) VALUES ('";
					$q = $q.$id."', '".$weight1."', '".$answer1."', '".$weight2."', '".$answer2."', '".$weight3."', '".$answer3."', '".$weight4."', '".$answer4."', '".$weight5."', '".$answer5."', '".$weight6."', '".$answer6."', '".$weight7."', '".$answer7."', '".$weight8."', '".$answer8."', '".$weight9."', '".$answer9."', '".$weight10."', '".$answer10."', '".$weight11."', '".$answer11."', '".$weight12."', '".$answer12."', '";
					$q = $q.$weight13."', '".$answer13."', '".$weight14."', '".$answer14."', '".$weight15."', '".$answer15."', '".$weight16."', '".$answer16."', '".$weight17."', '".$answer17."', '".$weight18."', '".$answer18."', '".$weight19."', '".$answer19."', '".$weight20."', '".$answer20."', '".$weight21."', '".$answer21."', '".$weight22."', '".$answer22."')";
					//echo "<br>".$q;
					mysql_query($q) or die("Error2");
					break;
				default:
					break;
			}
			$quer = "INSERT INTO uploads (type, type_id, method, timestamp) VALUES ('question', '".$id."', 'qti', CURRENT_TIMESTAMP)";
			//echo "<br>".$quer;
			mysql_query($quer) or die("Error3");
			$output .= '<div class="ITS_QTI"><div style="float:left;background:#fff;border:1px solid #666;padding: 0.1em;">Question ID <a href="Question.php?qNum='.$id.'">'.$id.'</a></div>'
			        .   $ques['qtext']
			        .  '</div>';
		}
	}
		$output .= "Exported to MySQL Database"."<br>";
		mysql_close($linkID);
    }
  }
else {
  $output = "Invalid file";
}
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
	<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
  <script type="text/javascript" src="MathJax/MathJax.js"></script>
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
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
<!---************* NAVIGATION ******************--->
<div id="ITS_navcontainer" style="backgroundcolor:yellow">
<ul id="ITS_navlist">
<li><a href="logout.php">Logout</a></li>
<li><a href="index.php" >ITS</a></li>
<li><a href="Question.php" id="current">Questions</a></li>
<li><a href="Profile.php" >Profiles</a></li>
<li><a href="Logs.php">Logs</a></li>
</ul>
</div>
<!---******************************************--->
<!----------------------------------------------------------->
<div class="innertube">
</div>
</div>
<!--------------------------------------------------------->
<div id="maincontent" style="margin: 10px">
<div id="ITS_question_container">

<?php   
echo $output;
//action="upload_QTIfile.php"
?>
</div>
<p class="center">&nbsp;<b><font color="silver">~&diams;~</font></b>&nbsp;</p>
</div>
<!----------------------------------------------------------->

</body>
</html>
