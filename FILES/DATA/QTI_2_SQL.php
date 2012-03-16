<?php

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('include_path',"C:\wamp\bin\php\php5.3.0\pear");

require_once('XML/Unserializer.php');

//------------------------------------------//
// Extract from QTI
//------------------------------------------//
$QTIxml = file_get_contents('QTI/QTI.xml');

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
echo '<pre>';  
//print_r($QTI_Arr);  
echo '</pre>';   

//Save into arrays
$question = array();
$cnt = 0;
foreach($QTI_Arr['assessment']['section']['item'] as $ques) {
	$question[$cnt]['qcat'] = $ques['title'];
	$question[$cnt]['qtext'] = $ques['presentation']['material']['mattext']['_content'];
	if($ques['presentation']['response_lid']['rcardinality'] == 'Multiple')
		$question[$cnt]['qtype'] = "MC";
	foreach($ques['presentation']['response_lid']['render_choice'] as $qoption) {
		if(array_key_exists('material', $qoption)){
			$question[$cnt]['qoptions'][0] = $qoption['material']['mattext']['_content'];
		} else {
			$j = 0;
			foreach($qoption as $qopt) {
				if(array_key_exists('_content', $qopt['material']['mattext'])) {
					$question[$cnt]['qoptions'][$j] = $qopt['material']['mattext']['_content'];	
				} else {
					$question[$cnt]['qoptions'][$j] = '';
				}
				$j++;
			}
		}	
	}

	$k = 0;
	foreach($ques['resprocessing']['respcondition'] as $qweight) {
		if(array_key_exists('setvar', $qweight)) {
			foreach($qweight as $qw) {
				if(array_key_exists('varname', $qw)){
					if(array_key_exists('_content', $qw)){
						$question[$cnt]['qweight'][$k] = $qw['_content'];
					} else {
						$question[$cnt]['qweight'][$k] = '';
					}
					$k++;
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
echo '<pre>';  
//print_r($question);
echo '</pre>';

//------------------------------------------//
// DB connect
//------------------------------------------//
$host     = "localhost";
$user     = "root";
$pass     = "";
$database = "its";

$linkID = mysql_connect($host,$user,$pass) or die("Could not connect to host.");
mysql_select_db($database,$linkID) or die("Could not find database.");

//------------------------------------------//
// Insert Questions from QTI to DB
//------------------------------------------//
foreach($question as $ques) {
	$opt_count = count($ques['qoptions']);
	//mysql_query("INSERT INTO webct1 (qtype, question, answers, category) VALUES ('".$ques['qtype']."', '".$ques['qtext']."', '".$opt_count."', '".$ques['qcat']."')") or die("Error1");
	switch($ques['qtype']) {
		case 'MC':
			$tblName = "webct1_".strtolower($ques['qtype']);
			for($i=1;$i<=$opt_count;$i++) {
				$var1 = "weight$i";
				$$var1 = $ques['qweight'][$i-1];
				$var2 = "answer$i";
				$$var2 = $ques['qoptions'][$i-1]; 
			}
			for($i=$opt_count;$i<=22;$i++) {
				$var = "weight$i";
				$$var = ""; 
				$var2 = "answer$i";
				$$var2 = "";
			}
			$q = "INSERT INTO ".$tblName." (weight1, answer1, weight2, answer2, weight3, answer3, weight4, answer4, weight5, answer5, weight6, answer6, weight7, answer7, weight8, answer8, weight9, answer9, weight10, answer10, weight11, answer11, weight12, answer12, weight13, answer13, weight14, answer14, weight15, answer15, weight16, answer16, weight17, answer17, weight18, answer18, weight19, answer19, weight20, answer20, weight21, answer21, weight22, answer22) VALUES ('";
			$q = $q.$weight1."', '".$answer1."', '".$weight2."', '".$answer2."', '".$weight3."', '".$answer3."', '".$weight4."', '".$answer4."', '".$weight5."', '".$answer5."', '".$weight6."', '".$answer6."', '".$weight7."', '".$answer7."', '".$weight8."', '".$answer8."', '".$weight9."', '".$answer9."', '".$weight10."', '".$answer10."', '".$weight11."', '".$answer11."', '".$weight12."', '".$answer12."', '";
			$q = $q.$weight13."', '".$answer13."', '".$weight14."', '".$answer14."', '".$weight15."', '".$answer15."', '".$weight16."', '".$answer16."', '".$weight17."', '".$answer17."', '".$weight18."', '".$answer18."', '".$weight19."', '".$answer19."', '".$weight20."', '".$answer20."', '".$weight21."', '".$answer21."', '".$weight22."', '".$answer22."')";
			//mysql_query($q) or die("Error2");
			break;
		default:
			break;
	}
}
echo "Exported to MySQL Database"."<br>";
mysql_close($linkID);
  
?>