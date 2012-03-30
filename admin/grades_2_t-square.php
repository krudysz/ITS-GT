<?php
//=====================================================================//
/*   Scirpt to enter grades into T-square grade file.
 * 
 * INPUT:  $tsquare_file (csv) - from T-square
 * INPUT:  $its_file     (csv) - from ITS
 * OUTPUT: $gradebook_ITS.csv  -  to  T-square
								
	 Author(s):  Gregory A. Krudysz
	 Last Update: Mar-22-2012										   */	 
//=====================================================================//

$class_name    = 'Spring_2012';
$tsquare_file  = 'csv/gradebook-22463.201202-2012-03-21.csv';
$its_file      = 'csv/'.$class_name.'_grades.csv';
$output_file   = 'csv/gradebook-'.date("Y-m-d").'.csv';
$gradebook     = array();
$its  	 	   = array();

$fp = fopen($output_file,'w');
//--------------------------------------------------------------------//
// GRADEBOOK
//Student ID,Student Name,Final [200.0],Homework and ITS (Semester average) [10.0],Homework and ITS (Semester average) pct [10.0],ITS 01 [30.0],ITS 02 [30.0],ITS 03 [30.0],ITS 04 [30.0],ITS 05 [30.0],ITS 06 [30.0],ITS 07 [30.0],ITS 08 [30.0],Lab [25.0],Lab (Semester average) pct [25.0],Quiz 1 [100.0],Quiz 2 [100.0],Quiz 3 [100.0],Recitation [5.0]
/*
$handle = @fopen($tsquare_file, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
		echo '<hr><pre>';
		var_dump($buffer);
		echo '</pre>';
		
		//$s = explode(',',$buffer);
		$s = preg_split("/[\s,^\",]+/",$buffer);  // [\s]*[,][\s]
		echo '<hr><pre color="red">';
		var_dump($s);
		echo '</pre>';
		$gradebook[] = $s;
    }
    if (!feof($handle)) {echo "Error: unexpected fgets() fail\n";}
    fclose($handle);
}
*/
$row = 1;
if (($handle = fopen($tsquare_file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$gradebook[] = $data;
        ///*
        $num = count($data); 
        echo "<p style=color:red> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
        //*/
    }
    fclose($handle);
}

//die('opened gradebook');
//echo $its_file.'<hr>';
//--------------------------------------------------------------------//
// ITS
$handle = @fopen($its_file, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
		$s = explode(',',$buffer);
		$its[] = $s;
    }
    if (!feof($handle)) {echo "Error: unexpected fgets() fail\n";}
    fclose($handle);
}
//--------------------------------------------------------------------//
// GRADEBOOK with ITS 
$N = count($its);
for($k=0;$k<count($gradebook);$k++) {
	//echo $gradebook[$k][0].'   '.$its[$k][0].'<br>';
	//$key = array_search($gradebook[$k][0], $its);
	//v/ar_dump($key);
	//echo 'key '.$key.'<hr>';
	$stop = 0;
	for( $idx=0;$idx<$N;$idx++ ){  
		if ($gradebook[$k][0]==$its[$idx][0]){		
			$gradebook[$k][5] = $its[$idx][3];
			$gradebook[$k][6] = $its[$idx][4];
			$gradebook[$k][7] = $its[$idx][5];
			$gradebook[$k][8] = $its[$idx][6];
			//echo $gradebook[$k][0].' '.$its[$idx][0].'<br>';
			$stop = 1;
		}
		if ($stop){break;}
		//echo $idx.'<br>';
	}
}
//--------------------------------------------------------------------//

foreach ($gradebook as $student) {	
	//var_dump($student);echo '<hr>';
	fputcsv($fp,$student);
}
fclose($fp);
echo '<p>File saved: <b>'.$output_file.'</b> - '.date("F j, Y, g:i a").'</p>';
/*		
        $query = 'SELECT id,first_name,last_name,username FROM users WHERE status="'.$current_semester.'" ORDER BY last_name';
        //echo $query;die();

        $res = & $this->mdb2->query($query);
        if (PEAR :: isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $users = $res->fetchAll();

        $fdate = date("F j, Y, g:i a T",time());  // $tMay1
        $fdate = explode(',',$fdate);
        $fdate = $fdate[0].','.$fdate[1].'<br>'.$fdate[2];;

        $grade    = array_fill(0,count($chs)-1,0); 
        $ptsMax   = 2400;
		$ptsGrade = 30;

        foreach ($users as $key => $user) { //$users as $user){
            //Calculating Scores for this User
            $s = new ITS_statistics($user[0],$class_name,'student');
            $usertable = 'stats_'.$user[0];
            //echo '<p>'.$usertable.'</p>';
            //for every chapter
            for($j=0;$j<count($chs);$j++) {
                $score = 0;								//for every chapter, set score to 0
                $chaptername = 'Chapter'.sprintf("%02d",$chs[$j]);
                $q1    = $ITSq->getQuery('SUM(score) AS sum',$usertable,$chs[$j],$epochtime);
                $r1    = mysql_query($q1);
                $score = mysql_result($r1, 0, "sum");
                //echo '<p>'.$q1.' - '.$score.'<p>';

                // Score for jth chapter
                $totalscore[$key][$j] = round($score,2);
                if ($totalscore[$key][$j]>0) {
                    if ($totalscore[$key][$j]>=2400) {
                        $full_sts[$j]++;
                    }
                    $sts[$j]++;
                }
                // Grade for jth chapter
                
                $grade[$j] = round($ptsGrade*min($totalscore[$key][$j],$ptsMax)/$ptsMax);
            }
            //--- "Practice Mode"
            $q2 = 'SELECT count(score) AS p FROM '.$usertable.' WHERE current_chapter<0 AND score IS NOT NULL AND epochtime > '.$epochtime;
            //echo '<p>'.$q2.'</p>';die();
            $r2       = mysql_query($q2);
            $pcount[] = mysql_result($r2, 0, "p");
            
            $fields = array($user[0],$user[3],$user[2],$user[1],$totalscore[$key][0],$totalscore[$key][1],$totalscore[$key][2],$totalscore[$key][3],$totalscore[$key][4],$totalscore[$key][5],$totalscore[$key][6],$totalscore[$key][7],$totalscore[$key][8]);
            $data[] = $fields;
            
            fputcsv($fp, $fields);
            $grades = array_merge(array($user[3],$user[2],$user[1]),$grade);
            fputcsv($fp1, $grades);
            //http://localhost/ITS/Profile.php?class=Spring_2011&sid=1219

        fclose($fp);
        * */
?>
