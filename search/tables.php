<?php
$user = 'root';
$pass = 'root';
$host = 'localhost';
$db   = 'its';

$path1 = 'ITS_FILES/SPFIRST/PNGs/';
$path2 = 'ITS_FILES/SPFIRST/solutions/';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
        <meta content="text/html; charset=ISO-8859-1"
              http-equiv="Content-Type">
        <title>View Table</title>
        <style>
			body { font: Georgia, sans-serif }
            table { align:center;border-collapse:collapse;padding:0.25em; }
            td    { border:1px solid #999;text-align: center;padding:0.25em; }
            select,input { background:#fff;margin:1em;border:1px solid #999;padding:0.25em }
            input:hover { background:#ff8;cursor:pointer}
            span.blue { color:#11f }
            span.none { color:#777 }
        </style>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script type="text/javascript">
$(document).ready(function() {
	/* This is basic - uses default settings */
	$("a#fbimage").fancybox();	
});
</script>   
    </head>    
    <body>
      <table>
                    <form action="tables.php" method="GET">
                        <center>
                            <?php
                            //Connect to the MySQL server
                            if(!($connection = @ mysql_connect($host,$user,$pass)))
                                echo "connect failed<br> ";

                            // Select database
                            if(!(mysql_select_db($db,$connection)))
                                echo "select failed<br>\n";

                            if($_GET["table_name2"]!="-")
                                $table = $_GET["table_name2"];
                            else
                                $table = $_GET["table_name"];
                            if($_GET["entry_nos"]!="-")
                                $num = $_GET["entry_nos"];
                            else
                                $num="NULL";
                            $filter = $_GET["filter"];
                            
                            switch ($filter) {
								case 'chapter':
									$filter = $filter.' + 0';
									break;		
							}
                            echo '<p><center><h2>Table: <span class="blue">'.$table.'</span></h2></center></p>';
                            print '<input type="hidden" name="table_name" value ='.$table.'>';
                            print '<input type="hidden" name="entry_nos"  value ='.$num.'>';
                            print '<input type="hidden" name="table_name2" value ="-">';
	
                            if(!($result = @ mysql_query("DESCRIBE $table",$connection)))
                                echo "query failed<br>";

                            $i=0;
                            $menu = '<center><select name="filter">';
                            while($row = mysql_fetch_array($result)) {
                                $menu .= '<option value="'.$row[0].'"> Order By : '.$row[0].'</option>';
                                $i = $i+1;
                            }
                            $menu .= '</select><input value="Submit" type="submit"></form></center>';
							echo $menu;

                            // Query database for everything
                            if(!($result = @ mysql_query("DESCRIBE $table",$connection)))
                                echo "query failed<br>";
                            echo "<tr>";
                            while($row = mysql_fetch_array($result)) {
                                echo "<th>$row[0]</th>";
                            }
                            echo "</tr>";

                            if($num!="NULL" && $filter!="")
                                $query = "SELECT * FROM $table ORDER BY $filter LIMIT $num ";
                            else if($num=="NULL" && $filter!="")
                                $query = "SELECT * FROM $table ORDER BY $filter ";
                            else if($num!="NULL" && $filter=="")
                                $query = "SELECT * FROM $table LIMIT $num ";
                            else
                                $query = "SELECT * FROM $table ";
	
						    //$query .= ' LIMIT 5';
						    
                            if(!($result = @ mysql_query($query,$connection)))
                                echo "query failed<br>";
                            while($row = mysql_fetch_array($result)) {
                                $i = 0;
                                $fname = $row['statement'];
                                $solutions = $row['solutions'];
                                $term = $row['term'];
                                switch ($term){
									case 'Spring':
									case 'Summer':
									$t = $term[0].$term[1];
									break;
									case 'Fall':
									case 'Winter':
									$t = $term[0];
									break;
								}
                                $year = $row['year'];
                                while($i<mysql_num_fields($result)) {
                                    echo "<td>";
                                    //var_dump($result[1]);
                                    if($row[$i] !=NULL){
                                        if ($i==3) {
												$fname = preg_replace('/.pdf/','.png', $row[$i]);
												$path  = $path1.strtolower($t).'_'.$year[2].$year[3].'/'.$fname;
												if (file_exists($path)) {
													//echo '<a href="'.$path.'" target="_blank">'.$fname.'</a><br>';
													echo '<a id="fbimage" href="'.$path.'" title="'.$path.'">'.$fname.'</a><br>';

												} else {
													echo '<span class="none">'.$fname.'</span>';
												}										
											}
										elseif ($i==6) {
											$links = explode(',',$row[$i]);
											for ($k=0;$k<count($links);$k++){
												$path = $path2.strtolower($t).'_'.$year[2].$year[3].'/'.$links[$k];
												if (file_exists($path)) {
													//echo '<a id="single_image" href="'.$path.'" target="_blank">'.$links[$k].'</a><br>';
													echo '<a id="fbimage" href="'.$path.'" title="'.$path.'">'.$links[$k].'</a><br>';
												} else {
													echo '<span class="none">'.$links[$k].'</span>';
												}
											}											
										}	
										else 				{echo $row[$i];}   
									}
                                    else {
                                        echo "-"; }
                                    
                                    echo "</td>";
                                    $i = $i +1 ;
                                }
                                echo "</tr>";

                            }
                            echo "</table>";
                            ?>
                        </center>
                        <br>
                        </html>
