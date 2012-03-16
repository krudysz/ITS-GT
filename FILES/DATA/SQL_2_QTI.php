<?php
//header("Content-type: text/xml");
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
// select TABLE
//------------------------------------------//
$tables = array('webct');
$table  = $tables[0];
//$query = 'describe '.$tables[0]; // die($query);

//------------------------------------------//
// TABLE FIELDS
//------------------------------------------//
$query      = "SELECT id,qtype,title,image,question,answers,category FROM ".$table." WHERE qtype='MC'"; 
//echo $query.'<p>';
$fields_arr = mysql_query($query, $linkID) or die("Data not found.");
//echo 'num rows: '.mysql_num_rows($fields_arr).'<p>';
for($x = 0 ; $x < mysql_num_rows($fields_arr); $x++){
    $row = mysql_fetch_assoc($fields_arr);
		$id[$x]       =  $row['id'];
		$qtype[$x]    =  $row['qtype'];
		$title[$x]    =  $row['title'];
		$image[$x]    =  $row['image'];
		$ques[$x]     =  $row['question'];
		$anss[$x]     =  $row['answers'];
		$category[$x] =  $row['category'];
}
//var_dump($ans);

//------------------------------------------//
// TABLE CONTENT
//------------------------------------------//
$i   = 0;
$section  = 'section';
$QTI = '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n".
		 	 '<questestinterop>'."\n\t".
			 '<assessment title="test1" ident="A1001">'."\n".
			 '<section title="'.$section.'" ident="S1002">'."\n\t";
			 
for ($i = 0; $i < mysql_num_rows($fields_arr); $i++) {
// NEED 
$section  = 'section';
$title    = $category[$i];
$qid      = $id[$i];
$question = $ques[$i];
$ans      = $anss[$i];
$img      = $image[$i];

//Debug: echo $ans.'<p>';
$ans_name    = array();
$weight_name = array();

for ($n = 0; $n < $ans; $n++) {
    $ans_name[$n]    = 'answer'.($n+1); 
		$weight_name[$n] = 'weight'.($n+1);
}

$ans_str    = implode(',',$ans_name);
$weight_str = implode(',',$weight_name);
$query      = 'SELECT '.$ans_str.','.$weight_str.' FROM '.$table.'_'.strtolower($qtype[$i]).' WHERE id='.$qid; 

// Debug: echo $query.'<p>';
$ans_arr = array();
$ans_arr = mysql_query($query, $linkID) or die("Data not found.");
$row     = mysql_fetch_assoc($ans_arr);

$answers = array();
$weight  = array();

for ($n = 0; $n < $ans; $n++) {		
    $answers[$n] = $row[$ans_name[$n]]; 
		$weight[$n]  = $row[$weight_name[$n]];
}
/*
$QTI .= '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n".
		 	  '<questestinterop>'."\n\t".
			  '<assessment title="test1" ident="A1001">'."\n".
			  '<section title="'.$section.'" ident="S1002">'."\n\t".
        '<item title="'.$title.'" ident="QUE_'.$qid.'">'."\n\t".
			  '<presentation>'."\n\t";
*/

$QTI .= '<item title="'.$title.'" ident="QUE_'.$qid.'">'."\n\t".
			  '<presentation>'."\n\t"; 

//echo "str: $img_str; type: $img_type;<br />\n";
//die($img);
/*------ IMAGE ---------------------------------------------------------------*/	
if (!empty($img)) {
  // Debug: echo $qid.' '.$img.'<p>';
	$img_info = preg_split("/[\.]/",$img);
	
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
	// Debug: echo '<p>'.$img.' - '.$img_info[0].' ('.$img_info[1].')<p>';
	
  // Escaping illegal characters
  $img = str_replace("&" , "&amp;"  , $img);
  $img = str_replace("<" , "&lt;"   , $img);
  $img = str_replace(">" , "&gt;"   , $img);
  $img = str_replace("\"", "&quot;" , $img);
  $img = str_replace("\'", "&apos;" , $img);
  $img = str_replace("^" , "&#94;"  , $img);

	$QTI_image = '<matimage imagtype="'.$img_type.'" uri="'.$img.'"/>'."\n";
}
else {
  $QTI_image = '';
}				 			 
/*------ QUESTION -------------------------------------------------------------*/	 
$QTI .= '<material>'."\n".
        '<mattext texttype="text/html"><![CDATA['.$question.']]></mattext>'."\n".
				$QTI_image.
        '</material>'."\n";
/*------ RESPONSES -------------------------------------------------------------*/		
$QTI .= '<response_lid ident="QUE_'.$qid.'_RL" rcardinality="Multiple" rtiming="No">'."\n".
        '<render_choice>'."\n";		
				
$QTI_R = '';				
$QTI_C = '';

for ($n = 0; $n < count($answers); $n++) {
    $QTI_R .= '<response_label ident="QUE_'.$qid.'_A'.($n+1).'">'."\n".    
		          '<material>'."\n".
              '<mattext texttype="text/html"><![CDATA['.$answers[$n].']]></mattext>'."\n".
              '</material>'."\n".
              '</response_label>'."\n";
						
		$QTI_C .= '<respcondition>'."\n".
              '<conditionvar>'."\n".
              '<varequal respident="QUE_'.$qid.'_RL">QUE_'.$qid.'_A'.($n+1).'</varequal>'."\n".
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
/*----------------------------------------------------------------------*/
/*
$QTI .= '</item>'."\n".
		    '</section>'."\n".
 				'</assessment>'."\n".
				'</questestinterop>'."\n";
				*/
$QTI .= '</item>'."\n";
/*
// Escaping illegal characters
$row[$field] = str_replace("&", "&amp;" , $row[$field]);
$row[$field] = str_replace("<", "&lt;" , $row[$field]);
$row[$field] = str_replace(">", "&gt;" , $row[$field]);
$row[$field] = str_replace("\"", "&quot;" , $row[$field]);
$row[$field] = str_replace("\'", "&apos;" , $row[$field]);
$row[$field] = str_replace("^", "&#94;" , $row[$field]);		
$row[$field] = str_replace("VH = (sin(pi*nn/M)).ˆ2; %-- VonHann window","VH = (sin(pi*nn/M)).&#94;2; %-- VonHann window" , $row[$field]);			
*/				
}
$QTI .= '</section>'."\n".
        '</assessment>'."\n".
        '</questestinterop>'."\n";
				
$table = 'QTI';

// Output to file
$f = fopen('QTI/'.$table.'.xml', "w");
fwrite($f,$QTI);
fclose($f);

echo 'wrote file: QTI/'.$table.'.xml<p>';

//------------------------------------------//
// XSL file
//------------------------------------------//
$xsl_output  = '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n";
$xsl_output .= '<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"'.">\n";
$xsl_output .= '<xsl:template match="/">'."\n<html>\n<body>\n<h2>".$table."</h2>\n"
              .'<table border="1">'."\n\t"
							.'<tr class="header">'."\n"
							.'<td><xsl:value-of select="'.$question.'"/></td>'."\n"
							.'</tr>'
							."</xsl:for-each></table>\n</body>\n</html>\n</xsl:template>\n</xsl:stylesheet>";
//------------------------------------------//							
/*
$xsl_output .= "</tr>\n".'<xsl:for-each select="'.$table.'/entry">'."\n<tr>\n";
			
for ($x = 0; $x < count($answers); $x++) {
   $xsl_output .= '<td><xsl:value-of select="'.$answers[$x].'"/></td>'."\n";
}      
$xsl_output .= "</tr></xsl:for-each></table>\n</body>\n</html>\n</xsl:template>\n</xsl:stylesheet>";
*/
// Output to file
/*
$f = fopen('QTI/'.$table.'.xsl', "w");
fwrite($f,$xsl_output);
fclose($f);

echo 'wrote file: QTI/'.$table.'.xsl';
*/
?>
