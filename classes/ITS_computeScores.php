<?php
//=====================================================================//
/*
ITS_computeScores - compute user lab scores.

		Constructor: ITS_computeScores( ... )
		
			ex. $scores = new ITS_computeScores( ... );
			* 
		NOTE: requires ITS_query() class
								
	 Author(s):  Gregory A. Krudysz, Nabanita Ghosal
	 Last Update: Mar-14-2012
*/	 
//=====================================================================//
class ITS_computeScores {
	
	public function __construct($userid,$ch,$chArr) {
	
		global $db_dsn,$tb_name;
		
		$this->userid  = $userid;
		$this->db_dsn  = $db_dsn;
		$this->tb_name = $tb_name;
		$this->chapter = $ch;
		$this->term    = 'Spring_2012';
		$this->epochtime = 1325560440;          //1311960440;
		// array of chapters whose scores are to be displayed
		$this->chapterArray = $chArr;
		
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}
		$this->mdb2 = $mdb2;
	}
	//=====================================================================//
	public function computeLabScores(){
	//=====================================================================//
		
		$usertable  = "stats_".$this->userid;
		$useranswer = 0;
		$score      = 0;
		$labname    = "lab";
		$quesArray  = array();
		$questiontype = "";
		$tscore     = array();
		$s = new ITS_statistics($this->userid,$this->term,'student');
		
		//for every lab
		for($i=0;$i<13;$i++) {
			//for every lab, set score to 0
			$score = 0;
			$labname = 'lab'.sprintf("%02d",$i+1);
			
			//echo ' LABNAME = '.$labname;
			$query1 = 'SELECT question_id, qtype FROM activity, webct WHERE active=0 AND term="'.$this->term.'" AND activity.question_id=webct.id AND name = "'.$labname.'"';
			$res1 = & $mdb2->query($query1);
			$mdb2->disconnect();	
			
			while ($row1 = $res1->fetchAll()){
				for($k=0;$k<count($row1);$k++) {
					//for every question in the lab
					$questionid = $row1[$k][0];
					$questiontype = $row1[$k][1];
					$query2 = "SELECT answered FROM ".$usertable." WHERE question_id = ".$questionid;
					$res2 = & $mdb2->query($query2);
					$mdb2->disconnect();
					while ($row2 = $res2->fetchAll()){
				    // print_r($row2);
						for($l=0;$l<count($row2);$l++) {
							$useranswer = $row2[$l][0];
							$scr = ($s->get_question_score($questionid,$useranswer,$questiontype));
							//echo "<p>scr :" . $scr.'<p>';
							$score = $score + $s->get_total_score($scr,$useranswer,$questiontype);
							//echo "<p>score :" .'<p>'.$score;
						}
					}	
				}
			}
			$tscore[] = $score;	
		}
	//	print_r($tscore);   
		return $tscore;		
	}	
	//=====================================================================//
	public function renderLabScores() {
	//=====================================================================//	
      $lab_score_arr = $this->computeLabScores();		
	  $N = count($lab_score_arr);
      $score_arr = array_merge($lab_score_arr,array('<b>'.array_sum($lab_score_arr).'</b>'));
								 
			//$lab_arr = array("Lab 1","Lab 2","Lab 3","Lab 4","Lab 5","Lab 6","Lab 7","Lab 8","Lab 9","Lab 10","Lab 11","Lab 12", "Lab 13");  
			$lab_arr = array();
			
			for ($n=0;$n<$N;$n++){
        $lab_arr[$n] = '<span class="ITS_computeScores">Lab '.($n+1).'</span>';
      }
			$lab_arr[$N] = '<span class="ITS_computeScores_Total">TOTAL</span>';
			
      $weight = array_fill(0,($N+1),100/($N+1));
      $str = array_merge($lab_arr,$score_arr);
      $tb_score =  new ITS_table('a',2,($N+1),$str,$weight,'ITS_mySCORE');
			
			return $tb_score->str;
	}
	//=====================================================================//
	public function computeChapterScores(){
	//=====================================================================//	
		// connect to database
		$mdb2 =& MDB2::connect($this->db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($mdb2->getMessage());}
		
		$chArr = $this->chapterArray;  //$chArr = array(1,2,3);
		//var_dump($chArr);die();
		$ch = $this->chapter; //6;
		
		$usertable = "stats_".$this->userid;
		$s    = new ITS_statistics($this->userid,$this->term,'student');
		$ITSq = new ITS_query();
		//var_dump($chArr);
		//echo '<table class="ITS_backtrace">';	
        //array_walk( debug_backtrace(), create_function( '$a,$b', 'print "<tr><td><font color=\"blue\">". basename( $a[\'file\'] ). "</b></font></td><td><font color=\"red\">{$a[\'line\']}</font></td><td><font color=\"green\">{$a[\'function\']}()</font></td><td>". dirname( $a[\'file\'] ). "/</td></tr>";' ) ); 	
        //echo '</table>';

		$n = count($chArr);
		//for every chapter
		for($i=0;$i<$n;$i++) {
			//for every chapter, set score to 0
			$score = 0;
			//for every chapter, compute score
			$c = $chArr[$i];
			
			$query = $ITSq->getQuery('SUM(score)',$usertable,$c,$this->epochtime);
			$res   = $mdb2->query($query);	

			while ($row = $res->fetchAll()){
				for($j=0;$j<count($row);$j++) {
					$score = $row[$j][0];
				}
			}
			//$score = $res->fetchRow();
			//echo '<p>'.$score.'<p>';
			$tscore[$i]['score'] = $score;	
			
			//var_dump($tscore);
			$query1 = $ITSq->getQuery('count(question_id)',$usertable,$c,$this->epochtime);
			//echo '<p>'.$query1.'</p>';
			//echo 'ITS_computeScores::computeChapterScores():<br><font color="blue">'.$query1.'</font><p>';
			$res1 = & $mdb2->query($query1);
			
			$row1 = $res1->fetchAll();
			$attemptedQuesNum = $row1[0][0];
			$tscore[$i]['attempt']   = $attemptedQuesNum;
			$tscore[$i]['totalques'] = $this->getTotalNumQuestions($i+1);
			
			$totalattempt_ques = $attemptedQuesNum;
			if ($totalattempt_ques == 0) {
				$percentage = 0;
			} else {
				$total = 100*$totalattempt_ques;
				$percentage = ($score/$total)*100;	
			}
			$tscore[$i]['percent'] = $percentage;
		}
		//var_dump($tscore);
		$mdb2->disconnect();
		
		return $tscore;		
	}
  //=====================================================================//
	public function renderChapterScores() {
  //=====================================================================//	
	  //echo 'chapter: '.$this->chapter.'<p>';
    $ch    = $this->chapter; 			//6;
    $chArr = $this->chapterArray;
    $ptsMax = 2400;
    $ptsGrade = 30;
		
    $chapter_score_arr = $this->computeChapterScores();		
    $N = count($chapter_score_arr);
      	
		$chapter_arr = array();
		$chapter_arr[] = '';
		foreach ($chArr as $c) {
        	$chapter_arr[] = '<span class="ITS_computeScores">Mod. '.$c.'</span>';
		}
		$chapter_arr[] = '<span class="ITS_computeScores_Total">TOTAL</span>';
		$score_arr[] = "<b>Score</b>";
	    $attemptedQues[] = '<span class="ITS_smallFont">Attempted/Available</span><br><b>Questions</b>';
	    $percentageArr[] = "<b>Percentage</b>";
	    $gradeArr[]      = "<b>Grade</b>";
		
		$total_score = 0;
		$total_attemptQues = 0;
		$total_ques = 0;
		$totalPercent = 0;
		$totalGrade = 0;
		$idx = 1;
    foreach ($chapter_score_arr as $s) {
        $score_arr[] = round($s['score'],2).'<span class="gray"> pts</span>';
        $total_score = $total_score + $s['score'];
        //Attempted Questions
        $attemptedQues[] = '<span id="qAvail'.$idx.'">'.$s['attempt'].'</span><span class="gray"> / </span>'.$s['totalques'];
        $total_attemptQues = $total_attemptQues + $s['attempt'];
        $total_ques = $total_ques + $s['totalques'];
        //Percentages
        $percentageArr[] = round($s['percent'],2).'<span class="gray"> %</span>';
        //Grade
        $grade = round($ptsGrade*min($s['score'],$ptsMax)/$ptsMax);
        $gradeArr[] = '<span style="color:blue;font-weight:bold">'.$grade.'</span> / <span class="gray">'.$ptsGrade.'</span>';
        $totalGrade = $totalGrade + $grade;
        $idx++;
    }
		$score_arr[] = round($total_score,2).'<span class="gray"> pts</span>';
		$attemptedQues[] = $total_attemptQues.'<span class="gray"> / </span>'.$total_ques;
    if ($total_attemptQues == 0) { $totalPercent = 0;				 	 											}
    else 						 { $totalPercent = $total_score/$total_attemptQues; }
    
		$percentageArr[] = round($totalPercent,2).'<span class="gray"> %</span>';
		$gradeArr[] = '<span style="color:blue;font-weight:bold">'.$totalGrade.'</span> / <span class="gray">'.(($idx-1)*$ptsGrade).'</span>';
		$weight = array_fill(0,($N+3),100/($N+3));
	    $str = array_merge($chapter_arr,$score_arr,$percentageArr,$attemptedQues,$gradeArr);
	    $tb_score =  new ITS_table('a',5,($N+2),$str,$weight,'ITS_mySCORE');
		$score_table = '<center>'.$tb_score->str.'</center>';
		
		//var_dump();
		return $score_table;
	}
	//=====================================================================//
	public function getTotalNumQuestions($ch){
	//=====================================================================//
	  // connect to database
		$mdb2 =& MDB2::connect($this->db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($mdb2->getMessage());}
		
		$ITSq   = new ITS_query();
		$category = $ITSq->getCategory($ch);	
		$query = 'SELECT count(id) FROM webct WHERE '.$category;
		//8-$query1 = 'SELECT count(id) FROM webct WHERE category IN ("Chapter'.$chapterNum.'") AND qtype IN ("MC","M","C")';
		//echo $query1.'<p>';
		$res = & $mdb2->query($query);
		$row = $res->fetchAll();
		//var_dump($row1);
		$totalNumQues = $row[0][0];
		$mdb2->disconnect();
		
		return $totalNumQues;
	}
	//=====================================================================//
	public function getTopScoresByChapter(){
	//=====================================================================//
		$top1[] = 'Top Score 1';
		$top2[] = 'Top Score 2';
		$top3[] = 'Top Score 3';
		
		// connect to database
		$mdb2 =& MDB2::connect($this->db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($mdb2->getMessage());}
		
		$query1 = " select distinct ch1,ch2,ch3,ch4,ch5,ch6 from user_scores order by ch1 desc, ch2 desc, ch3 desc, ch4 desc, ch5 desc, ch6 desc limit 3";
		$res1 = & $mdb2->query($query1);
		$mdb2->disconnect();	
		
		while ($row1 = $res1->fetchAll()){
			for($j=0;$j<6;$j++) {
				$top1[] = $row1[0][$j];
				$top2[] = $row1[1][$j];
				$top3[] = $row1[2][$j];
				//$top4[] = $row1[3][$j];
				//$top5[] = $row1[4][$j];
			}
		}
		$chapterArr = array('','Ch1','Ch2','Ch3','Ch4','Ch5','Ch6');
		$N = 7;
		$weight = array_fill(0,($N),110/($N));
		$str = array_merge($chapterArr,$top1,$top2,$top3);
		$tb_score =  new ITS_table('a',4,$N,$str,$weight,'ITS_mySCORE');
		$top_score_table = '<center>'.$tb_score->str.'</center>';
		
		return $top_score_table;
	}
//=====================================================================//
} //eo:class
//=====================================================================//
?>
