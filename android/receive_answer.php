<?php 
    // Connects to database
		if(PHP_OS == "WINNT") { $pass = '';    } 
    else 				 					{ $pass = 'csip';}
    $con = mysql_connect("localhost", "root", $pass) or die(mysql_error());
		$DB = 'its';
    mysql_select_db($DB, $con) or die(mysql_error()); 

    //$uid = $_POST["uid"];
    //$qid = $_POST["qid"];
    //$answer = $_POST["answer"];
    
    $uid = 1194;
    $qid = 76;
    $answer = 1;
    
    $query = "SELECT weight". $answer ." FROM webct_mc WHERE id = ".$qid;

    $result = mysql_query($query) or die(mysql_error());
    
    if($row = mysql_fetch_array($result)){
        $score = $row["weight".$answer];
        //echo "here:  weight".$answer;
        //echo "and here:  ".$row["weight".$answer];
        $output[]=$row;
    }
    
    $stat_num = 'STATS_';
    $stat_num .= $uid;

    mysql_select_db($DB, $con) or die(mysql_error()); 
    
    //insert the stat into table
    mysql_query("INSERT INTO ". $stat_num ." (question_id, answered, score) VALUES ('". $qid ."', '". $answer ."', '". $score ."')");
    
    //encode to JSON format
    print(json_encode($output));
    
    //close the connection
    mysql_close($con);
?>
