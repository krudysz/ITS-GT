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
$tables = array('webct','webct_mc','webct_m','webct_c','webct_p','webct_s');

for($t = 0; $t < count($tables); $t++) {

$table  = $tables[$t];
//$query = 'describe '.$tables[0]; // die($query);

//------------------------------------------//
// TABLE FIELDS
//------------------------------------------//
$query = 'select column_name from information_schema.columns where table_name="'.$table.'"'; 
$fields_arr = mysql_query($query, $linkID) or die("Data not found.");

$fields = array();
for($x = 0 ; $x < mysql_num_rows($fields_arr) ; $x++){
    $row = mysql_fetch_assoc($fields_arr);
		$fields[$x] =  $row['column_name'];
}
$fields_str = implode(",",$fields);
//die($fields_str);
//------------------------------------------//
// TABLE CONTENT
//------------------------------------------//
$query   = 'SELECT '.$fields_str.' FROM '.$table;
// DEBUG: echo $query.'<p>';

$content = mysql_query($query,$linkID) or die("Data not found.");

/*---*/
$xml_output  = "<?xml version=\"1.0\"?>\n";
$xml_output .= '<?xml-stylesheet type="text/xsl" href="'.$table.'.xsl"'."?>\n";

$xml_output .= "<".$table.">\n";
/*---*/
for($r = 0 ; $r < mysql_num_rows($content) ; $r++){
    $row = mysql_fetch_assoc($content);
		
		//var_dump($fields); die();
		
    $xml_output .= "\t<entry>\n";
		// for each field:
		for ($f = 0; $f < count($fields); $f++) {
		    $field = $fields[$f];

        // Escaping illegal characters
        $row[$field] = str_replace("&", "&amp;" , $row[$field]);
        $row[$field] = str_replace("<", "&lt;" , $row[$field]);
        $row[$field] = str_replace(">", "&gt;" , $row[$field]);
        $row[$field] = str_replace("\"", "&quot;" , $row[$field]);
				$row[$field] = str_replace("\'", "&apos;" , $row[$field]);
			  $row[$field] = str_replace("^", "&#94;" , $row[$field]);		
				$row[$field] = str_replace("VH = (sin(pi*nn/M)).ˆ2; %-- VonHann window","VH = (sin(pi*nn/M)).&#94;2; %-- VonHann window" , $row[$field]);			
							
        $xml_output .= "\t\t<".$field.">".$row[$field]."</".$field.">\n";
		}
    $xml_output .= "\t</entry>\n";
}
$xml_output .= "</".$table.">\n";

//echo $xml_output;

// Output to file
$f = fopen('XML/'.$table.'.xml', "w");
fwrite($f,$xml_output);
fclose($f);

echo 'wrote file: XML/'.$table.'.xml<p>';

//------------------------------------------//
// XSL file
//------------------------------------------//
$xsl_output  = '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n";
$xsl_output .= '<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"'.">\n";
$xsl_output .= '<xsl:template match="/">'."\n<html>\n<body>\n<h2>".$table."</h2>\n"
              .'<table border="1">'."\n\t"
							.'<tr class="header">'."\n";
							
for($x = 0; $x < count($fields); $x++) {
   $xsl_output .= "\t\t<th>".$fields[$x]."</th>\n";
}				
							
$xsl_output .= "</tr>\n".'<xsl:for-each select="'.$table.'/entry">'."\n<tr>\n";
			
for ($x = 0; $x < count($fields); $x++) {
   $xsl_output .= '<td><xsl:value-of select="'.$fields[$x].'"/></td>'."\n";
}      
$xsl_output .= "</tr></xsl:for-each></table>\n</body>\n</html>\n</xsl:template>\n</xsl:stylesheet>";

// Output to file
$f = fopen('XML/'.$table.'.xsl', "w");
fwrite($f,$xsl_output);
fclose($f);

echo 'wrote file: XML/'.$table.'.xsl<p><hr>';
}
?>
