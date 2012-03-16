<?php
function file2SQL($file,$table){	
//------------------------------------------//
// file2SQL.php - loads either a .txt or 
//				a .cvs file into SQL
//------------------------------------------//
/*
$database="test_php"; 
mysql_connect ("localhost", "root", "csip"); 
mysql_select_db($database);
$result = mysql_query( "SELECT * FROM user_cpt" );  
$num_rows = mysql_num_rows($result); 
print "There are $num_rows records.<P>"; 
print "<table width=400 border=1>\n"; 
while ($get_info = mysql_fetch_row($result)){ 
print "<tr>\n"; 
foreach ($get_info as $field) 
if ($field == -1){
print "\t<td><font color=gray face=arial size=1/>$field</font></td>\n";
}else{
print "\t<td><font color=red face=arial size=1/>$field</font></td>\n";
} 
print "</tr>\n"; 
} 
print "</table>\n";  
//var_dump($res[1]);
die();
*/
//------------------------------------------//	
$t1 = time();
		 	$handle = fopen($file,"r");

 		 	while(($data = fgetcsv($handle,1000,",")) !== FALSE)
		 	{
		 	 $colnum = count($data);
			 $attributes = array();
			 
			 // build string of attributes to be inserted
       	for($col=0; $col < $colnum; $col++)
          $attributes[] = "`p".$col."`";
					
				$sql = "INSERT INTO ".$table." (".implode(",", $attributes)
						  . ") VALUES (".implode(",",$data).")";
				$res = mysql_query($sql);
			//var_dump($sql);
			//die();	 	
    		}
				return (time()-$t1);
//------------------------------------------//
}
?> 

