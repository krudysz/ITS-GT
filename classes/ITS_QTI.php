<?php
include("config.php");
require_once('XML/Unserializer.php');
require_once(INCLUDE_DIR . "common.php");

class ITS_QTI{

	// Constructor //======================================================//
    function __construct() {
    //=====================================================================//
       
    }

/*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
Functions for Exporting Multiple Questions
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

function exportManyQues($quesid,$category,$ques_type,$cardinality){
	$host     = "localhost";
	$user     = "root";
	$pass     = "csip";
	$database = "its";
	$linkID = mysql_connect($host,$user,$pass) or die("Could not connect to host.");
	mysql_select_db($database,$linkID) or die("Could not find database.");
	$tables = array('webct');
	$table  = $tables[0];
	$query = "SELECT id,qtype,title,image,question,answers,category,tag_id FROM ".$table;
	
	if($cardinality == 1){
		$query .= " where id=".$quesid;
	}
	else{
		$sub_query_type = "";
		$sub_query_categ = "";
		$category = $_REQUEST['category_export'];
		if($category){
			$sub_query_categ = "category = '".$category."'";
		}

		for ($i=0; $i<count($_REQUEST['ques_type']);$i++) {
		if($i==0)
		   $sub_query_type = " qtype='".$_REQUEST['ques_type'][$i]."'";
		else
		   $sub_query_type .= " or qtype='".$_REQUEST['ques_type'][$i]."'";
		}

		
		if($sub_query_categ){
				$query .= " where ".$sub_query_categ;
				if($sub_query_type)
					$query .= " AND (".$sub_query_type.")";
		}
		else if($sub_query_type){
			$query .= " where ".$sub_query_type;
		}
	}
	$fields_arr = mysql_query($query, $linkID) or die("Data not found for lab7.");
	$obj = new ITS_QTI();
	$date_time = $obj->generateXMLContent($fields_arr, $table, $linkID);
	if($cardinality == 1){
				return '/QTI/zipped_'.$date_time.'.zip'; 
	}
	else{
	$script = '<script>down();function down(){var url="/QTI/zipped_'.$date_time.'.zip";  
					window.open(url,"Download");}</script>';
	return '<html><head></head><body><br><br>the details from last page : '.$category.'<br>to go back to ITS <a href="Question.php">click here</a></body>'.$script.'</html>';
	}
}

function generateXMLContent($fields_arr, $table, $linkID){
	$obj = new ITS_QTI();
	for($x = 0 ; $x < mysql_num_rows($fields_arr); $x++){
			$row = mysql_fetch_assoc($fields_arr);
			$id[$x]       =  $row['id'];
			$qtype[$x]    =  $row['qtype'];
			$title[$x]    =  $row['title'];
			$image[$x]    =  trim($row['image']);
			$ques[$x]     =  $row['question'];
			$anss[$x]     =  $row['answers'];
			$category[$x] =  $row['category'];
			$tag_id[$x]   =  $row['tag_id'];
	}
	$count_ques = $x;
	$QTI = '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n".
				 '<questestinterop>'."\n\t".
				 '<assessment title="test1" ident="A1001">'."\n\t\t".
				 '<section title="'.$section.'" ident="S1002">'."\n\t\t\t";

	// Looping for each question
	for ($i = 0; $i < $count_ques; $i++) {
		
		//$QTI .= '<item title="'.$title[$i].'" ident="QUE_'.$qtype[$i].'">'."\n\t";
		//for category inclusion:-
		$QTI .= '<item title="'.$title[$i].'" ident="QUE_'.$qtype[$i].'" category="'.$category[$i].'">'."\n\t";	

	
	if($qtype[$i] == "mc" || $qtype[$i] == "MC"){
		$QTI .= '<itemmetadata>
			<qmd_itemtype>MC</qmd_itemtype>
			</itemmetadata>';
		$QTI .=	'<presentation>'."\n\t"; 
			  
	/*------ QUESTION -------------------------------------------------------------*/	 
	$QTI .= '<material>'."\n\t".
			'<mattext texttype="text/html"><![CDATA['.$ques[$i].']]></mattext>'."\n\t".
					$obj->get_image_str($image[$i], $id[$i]).
			'<mattext texttype="text/html"></mattext>'.
			'</material>'."\n\t";

	
	/*------ RESPONSES -------------------------------------------------------------*/		
		//Debug: echo $ans.'<p>';
		$ans_name    = array();
		$weight_name = array();
		$image_name = array();
		for ($n = 0; $n < $anss[$i]; $n++) {
			$ans_name[$n]    = 'answer'.($n+1); 
			$weight_name[$n] = 'weight'.($n+1);
			$image_name[$n] = 'image'.($n+1); 
		}

		$ans_str    = implode(',',$ans_name);
		$weight_str = implode(',',$weight_name);
		$image_str = implode(',',$image_name);
		$query      = 'SELECT '.$ans_str.','.$weight_str.','.$image_str.' FROM '.$table.'_'.strtolower($qtype[$i]).' WHERE id='.$id[$i]; 
		$ans_arr = array();
		$ans_arr = mysql_query($query, $linkID) or die("Data not found for above id.");
		$row     = mysql_fetch_assoc($ans_arr);

		$answers = array();
		$weight  = array();
		$answer_images = array();
		for ($n = 0; $n < $anss[$i]; $n++) {		
				$answers[$n] = $row[$ans_name[$n]]; 
				$weight[$n]  = $row[$weight_name[$n]];
				$answer_images[$n] = $row[$image_name[$n]];	
		}
				$QTI .= '<response_lid ident="QUE_'.$qid.'_RL" rcardinality="Multiple" rtiming="No">'."\n".
				'<render_choice>'."\n";		
						
		$QTI_R = '';				
		$QTI_C = '';

		for ($n = 0; $n < count($answers); $n++) {
			$QTI_R .= '<response_label ident="QUE_'.$id[$i].'_A'.($n+1).'">'."\n".    
						  '<material>'."\n".
					  '<mattext texttype="text/html"><![CDATA['.$answers[$n].']]></mattext>'."\n".
					   $obj->get_image_str($answer_images[$n], $id[$i]).
					  '</material>'."\n".
					  '</response_label>'."\n";
								
				$QTI_C .= '<respcondition>'."\n".
					  '<conditionvar>'."\n".
					  '<varequal respident="QUE_'.$id[$i].'_RL">QUE_'.$id[$i].'_A'.($n+1).'</varequal>'."\n".
					  '</conditionvar>'."\n".
					  '<setvar varname="que_score" action="Add">'.$weight[$n].'</setvar>'."\n".
					  '</respcondition>'."\n";
		}
		$QTI_RP = '<resprocessing>'."\n".
						'<outcomes>'."\n".
					  '<decvar vartype="Integer" defaultval="0" varname="que_score" maxvalue="100" minvalue="0"/>'."\n".
				  '</outcomes>'."\n";
							
		$QTI .=	$QTI_R.
				'</render_choice>'."\n".
				'</response_lid>'."\n".
				'</presentation>'."\n".
				$QTI_RP.$QTI_C."\n".
				'</resprocessing>'."\n";	
		 
	}
		
	else if($qtype[$i] == "m" || $qtype[$i] == "M"){
		$QTI .= '<itemmetadata>'."\n".'<qmd_itemtype>Matching</qmd_itemtype>'."\n".'</itemmetadata>'."\n";
					$QTI .=	'<presentation>'."\n\t"; 
			  
	/*------ QUESTION -------------------------------------------------------------*/	 
	$QTI .= '<material>'."\n\t".
			'<mattext texttype="text/html"><![CDATA['.$ques[$i].']]></mattext>'."\n\t".
					$obj->get_image_str($image[$i], $id[$i]).
			'<mattext texttype="text/html"></mattext>'.
			'</material>'."\n\t";

	
	/*------ RESPONSES -------------------------------------------------------------*/		
					// Answer fetching from DB
		$left_choices    = array();
				$left_images    = array();
				$right_choices = array();
				$right_images = array();

				for ($n = 0; $n < $anss[$i]; $n++) {
					$left_choices[$n]    = 'L'.($n+1); 
					$left_images[$n]    = 'Limage'.($n+1); 
					$right_choices[$n] = 'R'.($n+1);
					$right_images[$n] = 'Rimage'.($n+1);
				}

				$left_str    = implode(',',$left_choices);
				$leftimg_str    = implode(',',$left_images);
				$right_str 	 = implode(',',$right_choices);
				$rightimg_str   = implode(',',$right_images);
				$query_m     = 'SELECT '.$left_str.','.$leftimg_str.','.$right_str.','.$rightimg_str.' FROM '.$table.'_'.strtolower($qtype[$i]).' WHERE id='.$id[$i]; 

				$response_arr = array();
				$response_arr = mysql_query($query_m, $linkID) or die("Data not found.");
				$row     = mysql_fetch_assoc($response_arr);

				$left = array();   // $left choices
				$left_img = array();
				$right  = array();   // right choices
				$left_img = array();
				
				for ($n = 0; $n < $anss[$i]; $n++) {		
					$left[$n] = $row[$left_choices[$n]]; 
					$left_img[$n] = $row[$left_images[$n]];					
					$right[$n]  = $row[$right_choices[$n]];	
					$right_img[$n]  = $row[$right_images[$n]];	
				}



			/*------ RESPONSES -------------------------------------------------------------*/		



			// loop for left choices

			$iden = $id[$i] + 1;
			for($n = 0; $n < $anss[$i]; $n++){
			$QTI .= '<response_lid ident="QUE_'.$iden.'_RL">'."\n".
			'<material>'."\n".
			'<mattext texttype="text/html"><![CDATA['.$left[$n].']]></mattext>'."\n".
			$obj->get_image_str($left_img[$n], $id[$i]).
			'</material>'."\n".
			'<render_choice shuffle="Yes">'."\n";	
				
				for($m = 0; $m < $anss[$i]; $m++){
					$ans_ident = $id[$i]+$m+2;
					$num = $m + 1;
					$QTI .= '<response_label ident="QUE_'.$ans_ident.'_A'.$num.'">'."\n".     
					'<material>'."\n".
					'<mattext texttype="text/html"><![CDATA['.$right[$m].']]></mattext>'."\n".
					$obj->get_image_str($right_img[$m], $id[$i]).
					'</material>'."\n".
					'</response_label>'."\n";
				}			

			$QTI.='</render_choice>'."\n".
			'</response_lid>'."\n";
			$iden = $id[$i]+$anss[$i]+$n+2;
			}// end of outer for loop.

			$QTI .= '</presentation>'."\n";


			$QTI .= '<resprocessing>'."\n".
			'<outcomes>'."\n".
			'<decvar vartype="Integer" defaultval="0" varname="Correct" minvalue="0" maxvalue="'.$anss[$i].'"/>'."\n".
			'<decvar vartype="Integer" defaultval="0" varname="Incorrect" minvalue="0" maxvalue="'.$anss[$i].'"/>'."\n".
			'<decvar vartype="Decimal" defaultval="0" varname="que_score" minvalue="0" maxvalue="100"/>'."\n".
			'</outcomes>'."\n";

			$iden = $id[$i]+1;
			for($j=0; $j<$anss[$i]; $j++){
				for($k=0; $k<$anss[$i]; $k++){
					$ans_ident = $id[$i]+$k+2;
					$num = $j+1;
					$num_answ = $k + 1;
					if($j==$k)
						$result = "Respondus_Correct";
					else
						$result = "Respondus_Incorrect";
					$QTI.='<respcondition title="Matching QUE_'.$iden.'_RL Resp Condition '.$num_answ.'">'."\n".
						'<conditionvar>'."\n".
						'<varequal respident="QUE_'.$iden.'_RL">QUE_'.$ans_ident.'_A'.$num_answ.'</varequal>'."\n".
						'</conditionvar>'."\n".
						'<setvar varname="'.$result.'" action="Add">1</setvar>'."\n".
						'</respcondition>'."\n";
				}
				$iden = $id[$i]+$anss[$i]+$j+2;
			}



			$QTI .='<respcondition>'."\n".
			'<conditionvar>'."\n".
			'<and> <other/> <not><other/></not> </and>'."\n".
			'</conditionvar>'."\n".
			'<setvar varname="que_score" action="Set">0</setvar>'."\n".
			'<setvar varname="que_score" action="Add">Correct</setvar>'."\n".
			'<setvar varname="que_score" action="Divide">'.$anss[$i].'</setvar>'."\n".
			'</respcondition>'."\n".
			'</resprocessing>'."\n";

		


	}
	else if($qtype[$i] == "C" || $qtype[$i] == "c"){
			$QTI .= '<itemmetadata>'."\n".'<qmd_itemtype>Calculated</qmd_itemtype>'."\n".'</itemmetadata>';
		$QTI .=	'<presentation>'."\n\t"; 
			  
	/*------ QUESTION -------------------------------------------------------------*/	 
	$QTI .= '<material>'."\n\t".
			'<mattext texttype="text/html"><![CDATA['.$ques[$i].']]></mattext>'."\n\t".
					$obj->get_image_str($image[$i], $id[$i]).
			'<mattext texttype="text/html"></mattext>'.
			'</material>'."\n\t";

	
	/*------ RESPONSES -------------------------------------------------------------*/		
		// Get all variables from DB
	$query = 'SELECT * FROM '.$table.'_'.strtolower($qtype[$i]).' WHERE id='.$id[$i]; 
	$ans_arr = array();
	$rows = array();
	$ans_arr = mysql_query($query, $linkID) or die("Data not found.");
	//while ($rows = mysql_fetch_assoc($ans_arr)){	
	//echo $rows["formula"];	
	//}
	$rows = mysql_fetch_assoc($ans_arr);
	
	// COde for Formula
	$QTI .= '<response_lid_formula>'."\n".'<material>'."\n".'<mattext texttype="text/html">';
	$QTI .= '<![CDATA['.$rows["formula"].']]>'."\n".'</mattext>'."\n".'</material>'."\n".'</response_lid_formula>';	  
	
		// COde for Precision
	$QTI .= '<response_lid_precision>'."\n".'<material>'."\n".'<mattext texttype="text/html">';
	$QTI .= '<![CDATA[0]]>'."\n".'</mattext>'."\n".'</material>'."\n".'</response_lid_precision>';	  
	
	// COde for Scoring
	$QTI .= '<response_lid_scoring>'."\n".'<material>'."\n".'<mattext texttype="text/html">';
	$QTI .= '<![CDATA[0]]>'."\n".'</mattext>'."\n".'</material>'."\n".'</response_lid_scoring>'."\n";	  
	
	$var_count = $rows["vals"];
	
	$variable = array();
	$min_value = array();
	$max_value = array(); 
	for($j=0;$j<$var_count;$j++){
		$num = $j + 1;
		$variable[$j] = 'val'.$num;
		$min_value[$j] = 'min_val'.$num;
		$max_value[$j] = 'max_val'.$num;
	}
	
	for($j=0;$j<$var_count;$j++){
		$QTI .= '<response_lid>'."\n\t".'<material>'."\n\t\t".'<mattext texttype="text/html"><![CDATA['.$rows[$variable[$j]].']]></mattext>'."\n\t".'</material>'."\n\n";
		$QTI .= '<response_label_min>'."\n\t".'<material>'."\n\t\t".'<mattext texttype="text/html"><![CDATA['.$rows[$min_value[$j]].']]></mattext>'."\n\t".'</material>'."\n\n";
		$QTI .= '</response_label_min>';
		$QTI .= '<response_label_max>'."\n\t".'<material>'."\n\t\t".'<mattext texttype="text/html"><![CDATA['.$rows[$max_value[$j]].']]></mattext>'."\n\t".'</material>'."\n\n";
		$QTI .= '</response_label_max>';

	$QTI .= "\n".'</response_lid>'."\n";

	}

	// XML for variables
			  
	$QTI .= '</presentation>'."\n";
	} // End of type Calculated
	else{
		$QTI .= '';
	}
	$QTI .= '</item>'."\n";
	
 } // for loop for each question ends
 $QTI .= '</section>'."\n".
        '</assessment>'."\n".
        '</questestinterop>'."\n";
       
    
    //Saves the $QTI into an XML file        
        $obj->generateXML($QTI);
    
   // echo '<br> adding to zip <br>';
   
	$cur_dir = getcwd();
	chdir('/tmp');
	$date_time = exec('date \'+%Y%m%d%H%M\'');
	
	exec('zip -r /tmp/zipped_'.$date_time.'.zip content/*' , $ret_zip);
	chdir($cur_dir);
	
	if (!copy('/tmp/zipped_'.$date_time.'.zip','QTI/zipped_'.$date_time.'.zip')) {
    		exec('rm -rf /tmp/content');
    		die("failed to copy zipped file...<br>");
	}
	exec('rm -rf /tmp/content');
    	return $date_time;
}  // End of generate XML Content

function generateXML($QTI){
	if(!file_exists('/tmp/content')){
		$old = umask(0); 
		mkdir('/tmp/content', 0777 ,true);
		umask($old);
	}
	// Output to file
	$f = fopen('/tmp/content/allQues.xml', "w");
	fwrite($f,$QTI);
	fclose($f);
	//echo 'file wriiten';
} // End of generateXML

function save_image($image,$qid,$name){
	if($image!=''){
	if(!file_exists('/tmp/content/media/'.$qid)){
		$old = umask(0); 
		mkdir('/tmp/content/media/'.$qid, 0777 ,true);
		umask($old); 
	}	
	if(!copy('ITS_FILES/'.$image, '/tmp/content/media/'.$qid.'/'.$name))
		echo("failed with $image and /tmp/content/media/".$qid."/".$name);
}
}
      
function get_image_str($img, $qid){
	$obj = new ITS_QTI();
	/*------ IMAGE ---------------------------------------------------------------*/	
	if (empty($img)||(strstr($img,".php")!=false)){
		$QTI_image = '';
	}
	else{
		$img_info = preg_split("/[\.]/",$img);
		$image_nm = preg_split('/\//', $img, -1);
		$len_file_name = sizeof($image_nm) - 1;
		// saving image type
		if (empty($img_info[1])) {
		  $img_type = 'other';
		}
		else {
			switch ($img_info[1]) {
			  case 'gif';
			  case 'png';
			  case 'jpg';
			  case 'jpeg';
				  $img_type = $img_info[1];
			  break;
			  default:
				  $img_type = 'other';
			}
		}
		
	  // Escaping illegal characters
	  $img = str_replace("&" , "&amp;"  , $img);
	  $img = str_replace("<" , "&lt;"   , $img);
	  $img = str_replace(">" , "&gt;"   , $img);
	  $img = str_replace("\"", "&quot;" , $img);
	  $img = str_replace("\'", "&apos;" , $img);
	  $img = str_replace("^" , "&#94;"  , $img);

	  // TODO: also escape the image name once the code is complete 
	  
	  $QTI_image = '<matimage imagtype="'.$img_type.'" uri="/media/'.$qid.'/'.$image_nm[$len_file_name].'"/>'."\n";
	  $obj->save_image($img,$qid,$image_nm[$len_file_name]);

	}
		
	return $QTI_image;		
} // function get image string ends

/*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
Functions for Uploading Questions
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

/*
 * function to upload a file
 */
function uploadFile($FILES){
	$obj = new ITS_QTI();
	$qtifilepath = "ITS_FILES/QTI";
$imgpath     = "";
$img_path = "/QTI/images";
$output = '';
$files_ret = array();
//Check if valid file format

if (($FILES["file"]["type"] == "application/zip")||($FILES["file"]["type"] == "text/xml")||($FILES["file"]["type"] == "application/octet-stream")) {
	if ($FILES["file"]["error"] > 0) {
    	//echo "Return Code: " . $FILES["file"]["error"] . "<br />";
    } else {
	    // Save zipped or QTI.xml file in specified folder
	    if (file_exists($qtifilepath ."/" . $FILES["file"]["name"])) {
	   		//echo $FILES["file"]["name"] . " already exists. ";
	    } else {
	    	//move file(s) to specified location
	      	move_uploaded_file($FILES["file"]["tmp_name"],$qtifilepath. "/" . $FILES["file"]["name"]);
     	    }
		
		if(($FILES["file"]["type"] == "application/zip")||($FILES["file"]["type"] == "application/octet-stream")) {
	     	exec('unzip '.$qtifilepath. '/' . $FILES["file"]["name"] .' -d '.$qtifilepath. '/unzipped12', $ret_unzip);
		exec('chmod -R 777 '.$qtifilepath.'/unzipped12');
	      	$dir_path = $qtifilepath. '/unzipped12/content';
	       	$QTI_FILES[] = $qtifilepath."/unzipped12/content/allQues.xml";
		} else {
			//not a zipped file, xml file
			$QTI_FILES[] = $qtifilepath."/".$FILES["file"]["name"];
		}
		//for every file in the array, repeat
	 	foreach($QTI_FILES as $QTIfile) {
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
			     exec('rm -rf '.$qtifilepath. '/unzipped12/') ;
			     die("The file included some special characters that could not be parsed succesfully by the XML parser<br><br>Error: ".$status->getMessage()); 
		  
			}  		
			
			$QTI_Arr = $Unserializer->getUnserializedData();	
			// Display the PHP data structure  
			//Save into arrays
			$question = array();
			$cnt = 0;
				$single_cnt = 0;
				foreach($QTI_Arr['assessment']['section']['item'] as $ques) {
					if(!array_key_exists(0,$QTI_Arr['assessment']['section']['item'])) {
						//single question
						if($single_cnt == 1) {
							break;
						}
						$ques = $QTI_Arr['assessment']['section']['item'];
						$single_cnt++;
					} 
			
					$question[$cnt]['qcat'] = $ques['category'];  
					$question[$cnt]['qtitle'] = $ques['title']; 
					
						if (array_key_exists('itemmetadata', $ques)) {
							$question_type = $ques['itemmetadata']['qmd_itemtype'];
						if(strcmp($question_type,"Matching")==0)
							$question_type = "M";
						else if (strcmp($question_type,"Calculated")==0)
							$question_type = "C";
						
						$question[$cnt]['qtype'] = $question_type;
						
					} 
					else{
						die("Question was found  of unexpected type");
					}
					
					//--MULTIPLE CHOICE QUESTION--//
					if($question[$cnt]['qtype'] == "MC") {
						//Save the question description
						if(array_key_exists('mattext', $ques['presentation']['material']) && array_key_exists('matimage', $ques['presentation']['material'])) {
							
							$question[$cnt]['qtext'] = '';
							$question[$cnt]['qimg'] = '';
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
							$qtextcount = 0;
							if($textcount>$imagecount)
								$qtextcount = $textcount;
							else
								$qtextcount = $imagecount;
							for($i=0;$i<$qtextcount;$i++) {
								if(array_key_exists($i, $ques['presentation']['material']['mattext'])) {
									$question[$cnt]['qtext'] = $question[$cnt]['qtext'].$ques['presentation']['material']['mattext'][$i]['_content'];
									
								}
								if(is_array($ques['presentation']['material']['matimage'])) {
									if(array_key_exists('imagtype', $ques['presentation']['material']['matimage'])) {
										if($i==0)
											$question[$cnt]['qimg'] = $question[$cnt]['qimg'].$imgpath.'/'.$ques['presentation']['material']['matimage']['uri'];
											//echo $question[$cnt]['qimg'] ;
											
									} else if(array_key_exists($i, $ques['presentation']['material']['matimage'])) {
										$question[$cnt]['qimg'] = $question[$cnt]['qimg'].'<img src = "'.$imgpath.'/'.$ques['presentation']['material']['matimage'][$i]['uri'].'" />';
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
										if(array_key_exists('mattext', $qopt['material']) && array_key_exists('matimage', $qopt['material'])) {
										//render text and image
										$question[$cnt]['qoptions'][$j] = '';
										$question[$cnt]['qoptions_image'][$j] = '';
										if(array_key_exists('mattext', $qopt['material'])){
												$question[$cnt]['qoptions'][$j] = $qopt['material']['mattext']['_content'];
										}
										if(is_array($qopt['material']['matimage'])) {
											if(array_key_exists('imagtype', $qopt['material']['matimage'])) {
													$question[$cnt]['qoptions_image'][$j] = $imgpath.'/'.$qopt['material']['matimage']['uri'];
											}
										}
									} else {
										//render either text or image
										if(array_key_exists('mattext', $qopt['material'])){
												$question[$cnt]['qoptions'][$j] = $qopt['material']['mattext']['_content'];
											}
										if(array_key_exists('matimage', $qopt['material'])){
											$question[$cnt]['qoptions_image'][$j] = $imgpath.'/'.$qopt['material']['matimage']['uri'];
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
					} 
					//--MULTIPLE CHOICE QUESTION END--//
					
					//--MATCHING QUESTION BEGIN--//
					if($question[$cnt]['qtype'] == "M") {
						
						//Matching Questions
						//Save question description
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
											$question[$cnt]['qimg'] = $question[$cnt]['qimg'].$imgpath.'/'.$ques['presentation']['material']['matimage']['uri'];
											echo $question[$cnt]['qimg'];
									} else if(array_key_exists($i, $ques['presentation']['material']['matimage'])) {
										$question[$cnt]['qimg'] = $question[$cnt]['qimg'].'<img src = "'.$imgpath.'/'.$ques['presentation']['material']['matimage'][$i]['uri'].'" />';
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
						//End of saving question
					$leftopt = array();
						$rightopt = array();
						$j = 0;
						$temp_Larr = array();
						
						foreach($ques['presentation']['response_lid'] as $qLoption) {
							$leftopt[$qLoption['ident']] = '';
							$leftimg[$qLoption['ident']] = '';
							if(array_key_exists('mattext', $qLoption['material']) && array_key_exists('matimage', $qLoption['material'])) {
								//render text and image
								$temp_Larr[$j] = '';
								$temp_Limg[$j] = '';
								if(array_key_exists('mattext', $qLoption['material'])){
									$temp_Larr[$j] = $qLoption['material']['mattext']['_content'];
								}
								if(array_key_exists('matimage', $qLoption['material'])){
									$temp_Limg[$j] = $qLoption['material']['matimage']['uri'];
								}
							} else {
								//render either text or image
								if(array_key_exists('mattext', $qLoption['material'])){
									$temp_Larr[$j] = $qLoption['material']['mattext']['_content'];
								}
								if(array_key_exists('matimage', $qLoption['material'])){
									$temp_Limg[$j] = $qLoption['material']['matimage']['uri'];
								}	
							}
							
							$leftopt[$qLoption['ident']] = $temp_Larr[$j];
							$leftimg[$qLoption['ident']] = $temp_Limg[$j];
							
							if($j == 0){
								// Get the right hand options for the matching question
								$k = 0;
								$temp_arr = array();
								$temp_img = array();
								$temp_arr[0] = '';
								$temp_img[0] = '';
								foreach($qLoption['render_choice']['response_label'] as $qRoption) {	
									$temp_arr[$k] = '';
									$temp_img[$k] = '';					
									$rightopt[$qRoption['ident']] = '';
									$rightimg[$qRoption['ident']] = '';
									if(array_key_exists('mattext', $qRoption['material']) && array_key_exists('matimage', $qRoption['material'])) {
										//render text and image
										if(array_key_exists('mattext', $qRoption['material'])){
											$temp_arr[$k] = $qRoption['material']['mattext']['_content'];
										}
										if(array_key_exists('matimage', $qRoption['material'])){
											$temp_img[$k] = $qRoption['material']['matimage']['uri'];
										}
									} else {
										//render either text or image
										if(array_key_exists('mattext', $qRoption['material'])){
											$temp_arr[$k] = $qRoption['material']['mattext']['_content'];
										}
										if(array_key_exists('matimage', $qRoption['material'])){
											$temp_img[$k] = $qRoption['material']['matimage']['uri'];
										}	
									}
									
									$rightopt[$qRoption['ident']] = $temp_arr[$k];
									$rightimg[$qRoption['ident']] = $temp_img[$k];
								}
								$k++;
							}
							$j++;
						}
						
						
						$res_arr = array();
						$k = 0;
						foreach($ques['resprocessing']['respcondition'] as $qres) {
							if(array_key_exists('varname', $qres['setvar'])){
								if($qres['setvar']['varname'] == "Respondus_Correct") {
									$lkey = $qres['conditionvar']['varequal']['respident'];
									$rkey = $qres['conditionvar']['varequal']['_content'];
									$res_arr[$lkey] = $rkey;
									$question[$cnt]['qleftoptions'][$k] = $leftopt[$lkey];
									$question[$cnt]['qleftimages'][$k] = $leftimg[$lkey];
									$question[$cnt]['qrightoptions'][$k] = $rightopt[$rkey];
									$question[$cnt]['qrightimages'][$k] = $rightimg[$rkey];
									$k++;
								}	
							}
								
						}
						
					}
					//--MATCHING QUESTION END--//
					
					if($question[$cnt]['qtype'] == "C") {
							//Calculated Questions
						//Save question description
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
											$question[$cnt]['qimg'] = $question[$cnt]['qimg'].$imgpath.'/'.$ques['presentation']['material']['matimage']['uri'];
											echo $question[$cnt]['qimg'];
									} else if(array_key_exists($i, $ques['presentation']['material']['matimage'])) {
										$question[$cnt]['qimg'] = $question[$cnt]['qimg'].'<img src = "'.$imgpath.'/'.$ques['presentation']['material']['matimage'][$i]['uri'].'" />';
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
						//End of saving question
												
						if(array_key_exists('mattext', $ques['presentation']['response_lid_formula']['material'])) {
							$question[$cnt]['qformula'] = $ques['presentation']['response_lid_formula']['material']['mattext']['_content'];
						}
					
						if(array_key_exists('mattext', $ques['presentation']['response_lid_precision']['material'])) {
							$question[$cnt]['qprecision'] = $ques['presentation']['response_lid_precision']['material']['mattext']['_content'];
						}
						
						if(array_key_exists('mattext', $ques['presentation']['response_lid_scoring']['material'])) {
							$question[$cnt]['qscoring'] = $ques['presentation']['response_lid_scoring']['material']['mattext']['_content'];
							//$question[$cnt]['var_count'] = 0;
						}
						$var_count = 0;
						foreach($ques['presentation']['response_lid'] as $qvar){
							$question[$cnt]['qval_name'][$var_count] = '';
							// first fetch the mattext as the 
							if(array_key_exists('mattext', $qvar['material'])) {
								$question[$cnt]['qval_name'][$var_count] = $qvar['material']['mattext']['_content'];
							//echo "\n".$ques['qval_name'][$var_count];
							}
							
							// then fetch the min value as
							if(array_key_exists('mattext', $qvar['response_label_min']['material'])) {
								$question[$cnt]['qmin_value'][$var_count] = $qvar['response_label_min']['material']['mattext']['_content'];
							//echo "\n".$ques['qmin_value'][$var_count];
							}
													
							// at last fetch max value
							if(array_key_exists('mattext', $qvar['response_label_max']['material'])) {
								$question[$cnt]['qmax_value'][$var_count] = $qvar['response_label_max']['material']['mattext']['_content'];
								//echo $ques['qmax_value'][$var_count];
							}
							//echo "var coutn inside :  ".$var_count; 
							$var_count++;
						}
						//echo "\n\n".$var_count;
						
						$question[$cnt]['var_count'] = $var_count;
							
					} // Calculate Code ends
					$cnt++;
				} 
				//echo '<br>'." value after".$question[0]['qval_name'][1]."  ".$question[0]['qmin_value'][1]."  ".$question[0]['qmax_value'][1].'<br>';	
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
				//echo '<br> inside foreach <br>';
				if($ques['qtype'] == "MC") {
					$opt_count = count($ques['qoptions']);
				} else if($ques['qtype'] == "M") {
					$opt_count = count($ques['qleftoptions']);
				}
				else if($ques['qtype'] == "C") {
					$opt_count = 1;  // !!!!!!! change it when ITS is extended for multiple amswers !!!!!!!
				}
					$escaped_ques = mysql_real_escape_string($ques['qtext']);
					$query_question = "INSERT INTO webct (qtype,title, question, image, answers, category) VALUES ('".$ques['qtype']."','".$ques['qtitle']."', '".$escaped_ques."', '".$ques['qimg']."','".$opt_count."', '".$ques['qcat']."')";
					mysql_query($query_question) or die("Error1");
					$id = mysql_insert_id();
					// update the image row, runt the query -  with new image [path = $imgpath.'/'.$id.$IMAGE_NAME_WITH_EXTENSION;
				if(!(empty($ques['qimg']))){
				
					//echo "::::::::::::ID $id ::::::::::";
					$image = $obj->copy_image_uploading($qtifilepath. '/unzipped12/content'.$ques['qimg'], $id,$qtifilepath );
					$new_image_path = '/images/question/'.$id.'/'.$image;
				    $query_update = "UPDATE webct set image='".$new_image_path."' where id=".$id;
					mysql_query($query_update) or die("error in updating image path");
				}
					switch($ques['qtype']) {
						case 'MC':
							$opt_count = count($ques['qoptions']);
							$tblName = "webct_".strtolower($ques['qtype']);
							for($i=0;$i<$opt_count;$i++) {
							$weight_array[$i] = $ques['qweight'][$i];
							$answer_array[$i]= $ques['qoptions'][$i];
							if($ques['qoptions_image'][$i]!=''){
								$try_image = $obj->copy_image_uploading($qtifilepath. '/unzipped12/content'.$ques['qoptions_image'][$i],$id);
								$ques['qoptions_image'][$i] = '/images/question/'.$id.'/'.$try_image;
							}
							$image_array[$i]  = $ques['qoptions_image'][$i];
							}
							$temp = $opt_count;
							for($i=$temp;$i<22;$i++) {
								$weight_array[$i] = '';
								$image_array[$i] = '';
								$answer_array[$i] = '';
							}
							$insert_query ='';
							for($k=0;$k<22;$k++){
								$insert_query .= "','".$weight_array[$k];
								$insert_query .= "','".$answer_array[$k];
								$insert_query .= "','".$image_array[$k];
								
							}
							$columns_query ='';
							for($k=1;$k<=22;$k++){
								$columns_query .= ", weight".$k." , answer".$k.", image".$k;
							}
							
							//$insert_query = mysql_escape_string($insert_query);
							$q = "INSERT INTO ".$tblName." (id".$columns_query;
							$q = $q.") VALUES ('".$id;
							$q .= $insert_query."')";
							mysql_query($q) or die("in MC for $q  <br> error: ".mysql_error());
							break;

						case 'M':
							$opt_count = count($ques['qleftoptions']);
							$tblName = "webct_".strtolower($ques['qtype']);
							for($i=0;$i<=$opt_count;$i++) {
								$left_array[$i] = $ques['qleftoptions'][$i];
								$right_array[$i]= $ques['qrightoptions'][$i]; 
								if($ques['qleftimages'][$i]!=''){
									$try_image = $obj->copy_image_uploading($qtifilepath. '/unzipped12/content/'.$ques['qleftimages'][$i],$id);
									$ques['qleftimages'][$i] = '/images/question/'.$id.'/'.$try_image;
								}
								if($ques['qrightimages'][$i]!=''){
									$try_image = $obj->copy_image_uploading($qtifilepath. '/unzipped12/content/'.$ques['qrightimages'][$i],$id);
									$ques['qrightimages'][$i] = '/images/question/'.$id.'/'.$try_image;
								}
								$leftimg_array[$i]  = $ques['qleftimages'][$i];
								$rightimg_array[$i]  = $ques['qrightimages'][$i];
							}
							$temp = $opt_count;
							for($i=$temp;$i<27;$i++) {
								$left_array[$i] = '';
								$right_array[$i]= ''; 
								$leftimg_array[$i]  = '';
								$rightimg_array[$i]  = '';
							}
							
							$columns_query_m ='';
							for($k=1;$k<=22;$k++){
								$columns_query_m .= ", L".$k." , Limage".$k.", R".$k . ", Rimage".$k;
							}
							
							$insert_query_m ='';
							for($k=0;$k<22;$k++){
								$insert_query_m .= "','".$left_array[$k];
								$insert_query_m .= "','".$leftimg_array[$k];
								$insert_query_m .= "','".$right_array[$k]; 
								$insert_query_m .= "','".$rightimg_array[$k];
							}
							
							
							$q = "INSERT INTO ".$tblName." (id".$columns_query_m;
							$q = $q.") VALUES ('".$id;
							$q .= $insert_query_m."')";
							mysql_query($q) or die(" In C: for $q  <br> error: ".mysql_error());
							break;

						case 'C':
							$opt_count = $ques['var_count'] ; 
							$vals = $opt_count ;
							$tblName = "webct_".strtolower($ques['qtype']);
							$val = array();
							$min = array();
							$max = array();
							for($i=0;$i<$opt_count;$i++) {
								echo '<br>'." value ".$ques['qval_name'][$i].$ques['qmin_value'][$i].$ques['qmax_value'][$i];
								$val[$i] = $ques['qval_name'][$i];
								$min[$i] = $ques['qmin_value'][$i];
								$max[$i] = $ques['qmax_value'][$i];
							}
							
							for($i=$opt_count;$i<10;$i++) {
								$val[$i] = "";
								$min[$i] = "";
								$max[$i] = "";
							}
							$q = '';
							$q = "INSERT INTO ".$tblName." (id, formula, val1, min_val1, max_val1, val2, min_val2, max_val2, val3, min_val3, max_val3, val4, min_val4, max_val4, val5, min_val5, max_val5, val6, min_val6, max_val6, val7, min_val7, max_val7, val8, min_val8, max_val8, val9, min_val9, max_val9, val10, min_val10, max_val10, vals) VALUES (";
							$q = $q."'".$id."','".$ques['qformula']."'";
							for($i=0;$i<10;$i++){
								$q .= ",'".$val[$i]."','". $min[$i]."','".$max[$i]."'";
							}
							
							$q .= ",'".$vals."')";
							
						
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
				//}
			}	
			$output .= "Exported to MySQL Database"."<br>";
			mysql_close($linkID);
		}
		//deleting the file created during the process
		
		exec('rm -rf '.$qtifilepath.'/unzipped12');

    }// End of the block where the input file was valid- 
  } else {
	$output = "Invalid file";
}
	return $output;
}

	
/*
 * function to copy the image into the fodler from where the 
 * database will refer it to
 */

function copy_image_uploading($src,$question_id,$qtifilepath ){
	$image_nm = preg_split('/\//', $src, -1);
	$len_file_name = sizeof($image_nm) - 1;
	$dest = '/var/www/html/ITS_FILES/images/question/'.$question_id.'/';
	
	if(!file_exists($dest)){
		$old = umask(0); 
		mkdir($dest, 0777 ,true);
		umask($old);
	}
	if (!copy($src,$dest.'/'.$image_nm[$len_file_name])) {
    		//exec('rm -rf '.$qtifilepath.'/unzipped12');
		echo "DELETED: $qtifilepath/unzipped12 failed to copy $image_nm[$len_file_name] from $src...\n";
	}	
	//else
		//echo "copied";
	return $image_nm[$len_file_name];
}
}// Class ends

?>
