<?php
//=====================================================================//
/*
  ITS_question2 class - extension to ITS_question class.

  Constructor: ITS_question2()
  Methods: 


  Author(s): Greg Krudysz | Jul-30-2011
  Last Revision: Jul-30-2011
 */
//=====================================================================//
class ITS_question2 {

    var $student_id;    // ITS student ID
    var $timestamp;
    public $Q_type;     // question type: |S|M|P|C|MC|O|
    var $Q_answers_fields = array();

    // Constructor //======================================================//
    function __construct() {
        //=====================================================================//
        global $db_dsn, $db_name, $tb_name, $db_table_user_state, $mimetex_path, $files_path;

        $this->student_id = '';
    }
//=====================================================================//
	function render_list() {
//=====================================================================//

		$term = 'Spring_2011'; //$this->term;
		
		$query = 'SELECT question_id,answered,qtype,answers,comment,epochtime,duration,rating FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter="'.$chapter.'" AND category IN ("PreLab0'.$chapter.'","Lab'.$chapter.'","Chapter'.$chapter.'"'.$other.') AND qtype IN ("MC","M","C") ORDER BY stats_'.$this->id.'.'.$orderby;

					//echo $query; //	 die();
  				
  				$res = & $this->mdb2->query($query);
  				if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  				$answers = $res->fetchAll();

		for ($t = 0; $t < $Nterms; $t++) {
			
			echo '<h2><font color="royalblue">Chapter #' . $chapter . '</font></h2><p>';
			
			}

/*		
		$Nterms = count($term);
		for ($t = 0; $t < $Nterms; $t++) {
			//echo '<h2><font color="royalblue">Chapter #' . $chapter . '</font></h2><p>';

			$stats_SHOW     = TRUE;
				
			$activity_users = array();	
			$TERMS[$t][0] = $activity_users;
			
			if     ($chapter == 1) { $other = ',"Complex"'; } 
			elseif ($chapter == 13){ $other = ',"PEZ","chapter7DM"'; }
		  else 				           { $other = '';           }

  		//echo '<p>'.$activiy_ACTIVE.' | '.$activity_COMPLETE.' | '.$stats_SHOW.'<p>';
			
  		if ($stats_SHOW) {
  			for ($t = 0; $t < $Nterms; $t++) {
  				//-- Result: question_id | answered => for user's activity and term 
					if ($chapter > 13) {
            $query = 'SELECT question_id,answered,qtype,answers,comment,epochtime,duration,rating FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter="'.$chapter.'" ORDER BY stats_'.$this->id.'.'.$orderby;
						$column = '';
					}
					else {
					  $query = 'SELECT question_id,answered,qtype,answers,comment,epochtime,duration,rating FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter="'.$chapter.'" AND category IN ("PreLab0'.$chapter.'","Lab'.$chapter.'","Chapter'.$chapter.'"'.$other.') AND qtype IN ("MC","M","C") ORDER BY stats_'.$this->id.'.'.$orderby;
  				  $column = '<th style="width:5%;">Score</th>';
					}
					//echo $query; //	 die();
  				
  				$res = & $this->mdb2->query($query);
  				if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  				$answers = $res->fetchAll();
  				$TERMS[$t][1] = $answers;
  			}

  			$list = '';
  
  			$pop = array ($activity_users);
  			//$pop = array($activity_users);
  
	      $optionArr = array('id','score','duration','rating');
				$answerHeader = '<select id="sortProfile" sid="'.$this->id.'" section="'.$term.'" status="'.$this->role.'" ch="'.$chapter.'">';
        foreach ($optionArr as $op ) {
				  			if ($orderby == $op)  { $sel = 'selected="selected"'; } 
		  					else 				          { $sel = '';                    }
				        $answerHeader .= '<option '.$sel.'>'.$op.'</option>';
				}
        $answerHeader .= '</select>';
	      $rateStr = array('Very easy','Easy','Moderate','Difficult','Very difficult');
				
  			//-- LIST of questions (count($answers)-1)
  			$Estr = '<table class="PROFILE">'.
  			        '<tr><th style="width:4%;">No.</th><th style="width:77%;">Question</th><th style="width:14%;">'.$answerHeader.'</th>'.$column.'</tr>';
  			for ($qn = 0; $qn <= (count($answers)-1); $qn++) {
  				$qtype = strtolower($answers[$qn][2]);
  				$Nanswers = $answers[$qn][3];
            
          $score = $this->get_question_score($answers[$qn][0], $answers[$qn][1], $answers[$qn][4], $qtype);
  				$tscore = $this->get_total_score($score, $answers[$qn][1], $qtype);
          
					if ($chapter > 13) { $config = 2; $score = NULL; $tscore = NULL; }
					else				 	     { $config = 1; }
					//echo $score.' '.$answers[$qn][1].' '.$qtype.'<p>'; //$timestamp; die();
							
					if ($qtype=='m') { 
      				// Obtain number of questions
							$fields = 'L1,L2,L3,L4,L5,L6,L7,L8,L9,L10,L11,L12,L13,L14,L15,L16,L17,L18,R19,L20,L21,L22,L23,L24,L25,L26,L27';
							$query = 'SELECT ' . $fields . ' FROM webct_m WHERE id=' .$answers[$qn][0];
      				//die($query);
      				$res = & $this->mdb2->query($query);
      				if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
      				$result = $res->fetchRow(); 
							$Nques  = count(array_filter($result));
							$ansM_arr = explode(',',$answers[$qn][1]);
							$ansM = array_slice($ansM_arr,0,$Nques);
							$ansM_list = implode(',',$ansM);
							//echo $ansM_list.'<p>'.$Nques.'<hr>';
							$ans = $this->render_question_answer($score,$ansM_list, $qtype,0); //##!!
					}
					else {
					    $ans = $this->render_question_answer($score,$answers[$qn][1], $qtype,0); //##!!
					}
					
					//$ans = $this->render_user_answer($ansM_list, $score,'', 2, 0);
          
          $Q = new ITS_question($this->id, $this->db_name, $this->tb_name);
          $Q->load_DATA_from_DB($answers[$qn][0]);
          $QUESTION = $Q->render_QUESTION_check($answers[$qn][4]);
          
          $Q->get_ANSWERS_data_from_DB();
          $ANSWER = $Q->render_ANSWERS('a',0);
          
  				$dist = '';//'-dist-';
          $FEEDBACK = $this->render_user_answer($ans,$score,$dist,$config,0); //##!!

          /*
          $Estr.= '<tr class="PROFILE">'.
          '<td class="PROFILE_IDX" style="width:1%">' . ($qn +1) .'</td>'.
          '<td class="PROFILE">' . $QUESTION . '</td>'.
          '<td class="PROFILE">' . $ANSWER.'<BR>'.$FEEDBACK . '</td>'.
            '</tr>';*/
	/*				// TIMESTAMP	
					if (empty($answers[$qn][5])) { $timestamp = ''; }
					else 												 { $timestamp = '<hr style="border-top:1px dashed silver"><b><font color="darkblue" size="1.2">'.date("M j G:i:s T Y",$answers[$qn][5]).'</font></b>'; }
					// DURATION 
				  if (empty($answers[$qn][6])) { $dur = ''; }
					else 												 { $dur = '<hr style="border-top:1px dashed silver"><font color="blue">'.$answers[$qn][6].' sec</font>'; }				
					// RATING 
				  if (empty($answers[$qn][7])) { $rating = ''; }
					else 												 { $rating = '<hr style="border-top:1px dashed silver"><font color="brown">'.$rateStr[$answers[$qn][7]-1].'</font>'; }				
									
					//echo $answers[$qn][5]; //$timestamp; die();
					$Estr .= '<tr class="PROFILE" id="tablePROFILE">'.
          '<td class="PROFILE" style="background-color:#eee">' . ($qn +1) .'<br><br><a href="Question.php?qNum='.$answers[$qn][0].'" class="ITS_ADMIN">'.$answers[$qn][0].'</a></td>'.
          '<td class="PROFILE" >' . $QUESTION .$ANSWER. '</td>'.
          '<td class="PROFILE" >' . $ans.$timestamp.$dur.$rating.'</td>';
					if (!is_null($tscore)) {
  				   $Estr .= '<td class="PROFILE" >' . $tscore.'</td>';
					}
          $Estr .=  '</tr>';
  			} 
				//die();
				// eof $qn 
				//echo $Estr.'</table>';
				$Estr.= '</table>';
  		} else {
  			$list = '';
  		}
   }
	 */
	 
	 $Estr = 'out here';
	 return $Estr;
	}
	//=====================================================================//
}
//eo:class
?>
