<?php
//=====================================================================//
/*
ITS_computeScores - compute user lab scores.

		Constructor: ITS_computeScores( ... )
		
								 ex. $scores = new ITS_computeScores( ... );
								
	 Author(s): 
	 Last Update: Sep-09-2010
*/	 
//=====================================================================//
class ITS_computeScores {
	
	public function __construct($userid) {
	
		global $db_dsn,$tb_name;
		
		$this->userid  = $userid;
		$this->db_dsn  = $db_dsn;
		$this->tb_name = $tb_name;
		
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}
		$this->mdb2 = $mdb2;
	}
	//=====================================================================//
	public function computeLabScores(){
	//=====================================================================//
		$mdb2 =& MDB2::connect($this->db_dsn);
		
		$usertable = "stats_".$this->userid;
		$useranswer = 0;
		$score = 0;
		$labname = "lab";
		$quesArray = array();
		$questiontype = "";
		$tscore = array();
		$s = new ITS_statistics($this->userid,'Spring_2010','student');
		
		//for every lab
		for($i=0;$i<13;$i++) {
			//for every lab, set score to 0
			$score = 0;
			$labname = 'lab'.sprintf("%02d",$i+1);
			
			//echo ' LABNAME = '.$labname;
			$query1 = "SELECT question_id, qtype FROM activity, webct WHERE active=0 AND term='Spring_2010' AND activity.question_id=webct.id AND name = '".$labname."'";
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
				//		print_r($row2);
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
		$mdb2 =& MDB2::connect($this->db_dsn);
		
		$usertable = "stats_".$this->userid;
		$s = new ITS_statistics($this->userid,'Spring_2010','student');
		
		//for every chapter
		for($i=0;$i<2;$i++) {
			//for every chapter, set score to 0
			$score = 0;
			$chaptername = 'Chapter'.sprintf("%02d",$i+1);
			$c = $i + 1;
			$query1 = "SELECT question_id FROM ".$usertable." WHERE current_chapter='".$c."'";
			$res1 = & $mdb2->query($query1);
			$mdb2->disconnect();	
			while ($row1 = $res1->fetchAll()){
				//var_dump($row1);
				for($k=0;$k<count($row1);$k++) {
					$questionid = $row1[$k][0];
					$questiontype = 'MC';
					$query2 = "SELECT answered FROM ".$usertable." WHERE question_id = ".$questionid;
					$res2 = & $mdb2->query($query2);
					$mdb2->disconnect();
					while ($row2 = $res2->fetchAll()){
						//var_dump($row2);
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
		//var_dump($tscore);
		return $tscore;		
	}
	
	//=====================================================================//
	public function renderChapterScores() {
	//=====================================================================//	
    $chapter_score_arr = $this->computeChapterScores();		
	  $N = count($chapter_score_arr);
      
	  $temp_score_arr = array_merge($chapter_score_arr,array('<b>'.array_sum($chapter_score_arr).'</b>'));
	  //$score_arr[] = ''; //"<b>Score</b>";
	  $score_arr[] = "<b>Score</b>";
	  foreach ($temp_score_arr as $s) {
	  	$score_arr[] = $s.'<span class="gray"> pts</span>';
	  }
	  
	  $total_attemptQues = 0;
	  $totalQues = 0;
	  $totalPercent = 0;
	  $attemptedQues = array();
	  $percentageArr = array();
	  //$attemptedQues[] = ''; //"<b>Attempted Questions</b>";
	  $attemptedQues[] = '<span class="ITS_smallFont">Attempted/Available</span><br><b>Questions</b>';
	  $percentageArr[] = "<b>Percentage</b>";
	  for ($i=0; $i<2; $i++) {
	  	//Total Questions
		$temp_totalQues = $this->getTotalNumQuestions($i+1);
		$totalQues = $totalQues + $temp_totalQues;
		//Attempted Questions
  	  	$temp_attemptQues = $this->computeAttemptedQuestions($i+1);
  		$total_attemptQues = $total_attemptQues + $temp_attemptQues;
		$attemptedQues[] = $temp_attemptQues.'<span class="gray"> / </span>'.$temp_totalQues;
		//Percentages
		$percent = $this->computePercentageScores($i+1);
		$percentageArr[] = round($percent,2).'<span class="gray"> %</span>';
	  }
		if ($total_attemptQues == 0)
			 $totalPercent = 0;
		else
	  	 $totalPercent = array_sum($chapter_score_arr)/$total_attemptQues;
	  $percentageArr[] = round($totalPercent,2).'<span class="gray"> %</span>';
	  $attemptedQues[] = $total_attemptQues.'<span class="gray"> / </span>'.$totalQues;  //.'<br><font size=2>(attempted/available)</font>'
	  
		//var_dump($score_arr);
			//$lab_arr = array("Lab 1","Lab 2","Lab 3","Lab 4","Lab 5","Lab 6","Lab 7","Lab 8","Lab 9","Lab 10","Lab 11","Lab 12", "Lab 13");  
			$chapter_arr = array();
			$chapter_arr[] = ''; //"<b>Chapter</b>";
			for ($n=0;$n<$N;$n++) {
        	//$chapter_arr[] = '<span class="ITS_computeScores"><a href="Profile.php?ch='.($n+1).'">Ch.'.($n+1).'</a></span>';
          $chapter_arr[] = '<span class="ITS_computeScores">Ch. '.($n+1).'</span>';

			}
			$chapter_arr[] = '<span class="ITS_computeScores_Total">TOTAL</span>';
			
      $weight = array_fill(0,($N+2),100/($N+2));
      $str = array_merge($chapter_arr,$score_arr,$percentageArr,$attemptedQues);
      $tb_score =  new ITS_table('a',4,($N+2),$str,$weight,'ITS_mySCORE');
			$score_table = '<center>'.$tb_score->str.'</center>';
			return $score_table;
	}
	
	//=====================================================================//
	public function computeAttemptedQuestions($chapterNum){
	//=====================================================================//
		$mdb2 =& MDB2::connect($this->db_dsn);
	
		$usertable = "stats_".$this->userid;
		$query1 = "SELECT count(question_id) FROM ".$usertable." WHERE current_chapter='".$chapterNum."'";
		$res1 = & $mdb2->query($query1);
		$mdb2->disconnect();
		$row1 = $res1->fetchAll();
		//var_dump($row1);
		$attemptedQuesNum = $row1[0][0];
		return $attemptedQuesNum;
	}
	//=====================================================================//
	public function computePercentageScores($chapterNum){
	//=====================================================================//
		$chapter_score_arr = $this->computeChapterScores();
		$score = $chapter_score_arr[$chapterNum-1];
		$total_ques = $this->computeAttemptedQuestions($chapterNum);
		if ($total_ques == 0) {
			$percentage = 0;
		} else {
			$total = 100*$total_ques;
			$percentage = ($score/$total)*100;	
		}
		return $percentage;
	}
	//=====================================================================//
	public function getTotalNumQuestions($chapterNum){
	//=====================================================================//
		$mdb2 =& MDB2::connect($this->db_dsn);
		//$query1 = "SELECT count(id) FROM questions WHERE name = 'ch0".$chapterNum."'";
		$query1 = 'SELECT count(id) FROM webct WHERE category IN ("PreLab0'.$chapterNum.'","Lab'.$chapterNum.'","Chapter'.$chapterNum.'") AND qtype="MC"';
		$res1 = & $mdb2->query($query1);
		$mdb2->disconnect();
		$row1 = $res1->fetchAll();
		//var_dump($row1);
		$totalNumQues = $row1[0][0];
		return $totalNumQues;
	}
//=====================================================================//
} //eo:class
//=====================================================================//
?>