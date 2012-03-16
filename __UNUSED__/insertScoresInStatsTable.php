<?php
echo '<h3>insertScoresInStatsTable.php</h3><p>BEGIN SCORING ...</p>';
require_once ("config.php");
require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");
require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

global $db_dsn,$tb_name;
		
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}

//$query1 = "SELECT id FROM users";
$query1 = 'SELECT id FROM users WHERE status IN ("admin","Fall_2010")';

$res1 = & $mdb2->query($query1);
$mdb2->disconnect();						
while ($row1 = $res1->fetchAll()){
	for($k=0;$k<count($row1);$k++) {
		$uid = $row1[$k][0];
		echo '<p>USER: '.$uid."</p>";
		//object of ITS_statistics
		$s = new ITS_statistics($uid,'Fall_2010','student');
		$usertable = "stats_".$uid;
		$query2 = "SELECT s.question_id, s.answered, s.comment,w.qtype FROM ".$usertable." s, ".$tb_name." w WHERE s.score IS NULL AND s.question_id = w.id";
		$res2 = & $mdb2->query($query2);
		$mdb2->disconnect();	
		while ($row2 = $res2->fetchAll()){
			for($j=0;$j<count($row2);$j++) {
				//for every question
				$qid        = $row2[$j][0];
				$useranswer = $row2[$j][1];
				$config     = $row2[$j][2];
				$qtype      = $row2[$j][3];
				//echo "Qid: ".$qid."| Ans: ".$useranswer." | Qid: ".$qtype.'<br>';
				echo "Qid: ".$qid." | Qtype: ".$qtype.'<br>';
				//compute scores
				$score = ($s->get_question_score($qid,$useranswer,$config,$qtype));
				$tscore = $s->get_total_score($score,$useranswer,$qtype);
				//echo '<pre>';
				//var_dump($score);
				//echo '</pre>';
				//echo '<br><b>'.count($score).'</b></br>';
				//echo " Question ".$qid." - ".$score.'<br>';
				$query3 = "UPDATE ".$usertable." SET score = ".$tscore." WHERE question_id = ".$qid;
				echo $query3.'<br>';
				$res3 = & $mdb2->query($query3);
			}
		}
	}
}
echo '<p>DONE</p>';
?>
