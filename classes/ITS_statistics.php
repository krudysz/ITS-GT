<?php
//=====================================================================//
/*
ITS_statistics class - generate and render statistical displays.

    user_role: 'admin'|'instr'|'student'	
		
		Constructor: ITS_statistics(student_id,class_term,user_role)
		
								 ex. $ITS_stats = new ITS_statistics(45,'Fall_2009','student');
								 
		Methods: 		render_user_answer    ()				
								render_question_answer( $score,$answer,$qtype,$index ) 
								
	 Author(s): Greg Krudysz | Aug-28-2008
	 Last Revision: Mar-9-2012
*/
//=====================================================================//

//==============================================================================
class ITS_statistics {
    //==============================================================================

    private $id;        // Stores user id
    private $user_name; // Stores username
    private $role;      // user role: admin | inst | student
    private $term;      //
    private $stats;     // Stores statistical data to be output
    public  $data; 		// Stores all raw data from database
    public  $hist;
    private $mdb2;

    function __construct($id, $term, $role) {
        global $db_dsn, $tb_name;

        $this->id = $id;
        $this->term = $term;
        $this->role = $role;
        $this->db_name = 'its'; /*$db_name;*/
        $this->tb_name = $tb_name;
        $this->record = array ();

        // connect to database
        $mdb2 = & MDB2 :: connect($db_dsn);
        if (PEAR :: isError($mdb2)) {
            throw new Exception($this->mdb2->getMessage());
        }

        $this->mdb2 = $mdb2;
        //$query = "SELECT name,question_id,answered,score FROM stats_$user_id LEFT JOIN question ON stats_$user_id.question_id=question.id LEFT JOIN concept ON question.concept_id=concept.id";

        //self::main();
    }
    //----------------------------------------------------------------------------
    function main() {
        //----------------------------------------------------------------------------
        /**NAB EDIT START**/
        /*
		if(!isset($_GET['title']))
			$titleVar = "ITS";
		else
			$titleVar = $_GET['title'];
		//echo '<div class="top_header">' . $titleVar .'</div>';

		$lablinks = array();
		$lablinks[] = 'LAB EXERCISES';
		$labs = array();
		$chapterlinks = array();
		$chapterlinks[] = 'CHAPTERS FROM TEXTBOOK';
		$chapters = array();
		$questions = array();
		
		$getActiveLabquery = 'SELECT DISTINCT name,active FROM activity WHERE term="Fall_2009"';
			$getActiveLabres = $this->mdb2->query($getActiveLabquery);
		if (PEAR::isError($getActiveLabres)) {throw new Question_Control_Exception($res->getMessage());}
			$activeLabs_info = $getActiveLabres->fetchAll();
		for ($i = 0; $i <= count($activeLabs_info)-1; $i++) { //count($activiy_info)-1
		  $activeLabs_name = $activeLabs_info[$i][0];
		  //$labs = $activeLabs_name;
		  $labs = $i+1;
		  $lablinks[] = '<a href="Home.php?title='.$labs.'">'.$activeLabs_name.'</a>';
		}
		$LabListTab = new ITS_table('LabList',1,13,$lablinks,0,'InnerTable');			
		echo '<div class="next_header">'.$LabListTab->str.'</div>';
		
		//to display list of appendix
		for($i=65;$i<=67;$i++) {
			$chapters[] = 'Appendix '.chr($i);
			$chapterlinks[] = '<a href="Home.php?title=Appendix '.chr($i).'">Appendix '.chr($i).'</a>';
		}
		$ChapterListTab = new ITS_table('ChapterList',1,4,$chapterlinks,array(20,20,20,20),'InnerTable');
		echo '<div class="next_header">'.$ChapterListTab->str.'</div><br>';
        */
        /**------- NAB EDIT END -------**/
        //-- obtain activity: name --//
        $query = 'SELECT DISTINCT name,active FROM activity WHERE term="' . $this->term . '"';
        $res = $this->mdb2->query($query);
        if (PEAR :: isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $activiy_info = $res->fetchAll();

        // activity header
        //$title = 'Activity';
        //$tb    = new ITS_table('ITS_table_stats',1,4,array('&nbsp;','Answer','Score',preg_replace('[_]',' ',$this->term.' class')),array(10,30,30,30),'ITS_feedback_header');
        //$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$tb->str),array(15,85),'ITS_feedback_header');
        //echo $tb_activity->str;

        //-- For each activity:
        $SUM = 0;
        for ($i = 0; $i <= count($activiy_info) - 1; $i++) { //count($activiy_info)-1
            $activiy_name = $activiy_info[$i][0];
            $activiy_ACTIVE = $activiy_info[$i][1];

            if (($i +1) < 10) {
                $num = '0' . ($i +1);
            }
            else {
                $num = $i +1;
            }

            //----***--------//
            $query = 'SELECT id FROM users WHERE status="' . $this->term . '" AND ' . $activiy_name . ' IS NOT NULL';
            $res = & $this->mdb2->query($query);
            if (PEAR :: isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $activity_users = $res->fetchCol();
            //----***--------//

            // activity completed?
            $query = 'SELECT ' . $activiy_name . ' FROM users WHERE id=' . $this->id;
            $res = & $this->mdb2->query($query);
            if (PEAR :: isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $activity_completed = $res->fetchRow();
            $activity_COMPLETE = $activity_completed[0];

            $stats_SHOW = FALSE;

            if (strcmp($activiy_name, 'survey01') == 0) {
                $title_name = 'Survey';
            }
            else {
                $title_name = 'Exercise #' . ($i +1);
            }
            $title = '<font color="royalblue">' . $title_name . '</font>';

            switch ($this->role) {
                //----------------------//
                case 'admin' :
                //----------------------//
                // TITLE
                    $title = '<a href="ITS_pre_lab.php?activity=' . ($i +1) . '">' . $title . '</a>';
                    $stats_SHOW = FALSE;
                    break;
                //----------------------//
                default :
                //----------------------//
                // TITLE: IF active & !complete => link, ELSE name
                // OTHER: IF !active & complete => show, ELSE nothing
                    if ($activiy_ACTIVE) {
                        if (!($activity_COMPLETE)) {
                            // TITLE
                            $title = '<a href="ITS_pre_lab.php?activity=' . ($i +1) . '">' . $title . '</a>';
                        }
                    } else {
                        if ($activity_COMPLETE) {
                            $stats_SHOW = TRUE;
                        }
                    }
                //----------------------//
            } //eof.switch

            // ACTIVITY TITLE
            /**NAB EDIT START**/
            //$tb_title = new ITS_table('ITS_activity',1,1,array($title),array(100),'ITS_feedback_list');
            //echo $tb_title->str;
            /**NAB EDIT START**/

            //echo '<p>'.$activiy_ACTIVE.' | '.$activity_COMPLETE.' | '.$stats_SHOW.'<p>';
            if ($stats_SHOW) {
                //-- Result: question_id | answered => for user's activity and term
                if ($activity_COMPLETE) {
                    $query = "SELECT activity.question_id,answered,qtype,answers,comment FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) LEFT JOIN stats_$this->id ON (activity.question_id=stats_$this->id.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder";
                } else {
                    $query = "SELECT question_id,NULL,qtype,answers,comment FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder";
                }
                //echo $query;die();
                $res = & $this->mdb2->query($query);
                if (PEAR :: isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $answers = $res->fetchAll();

                $list = '';
                $pop = array (
                        $activity_users
                );
                //-- LIST of questions
                for ($qn = 0; $qn <= count($answers) - 1; $qn++) {
                    $qtype = strtolower($answers[$qn][2]);
                    $Nanswers = $answers[$qn][3];

                    // if NO activity users => NO distribution
                    if (count($activity_users)) {
                        $DATA  = $this->get_question_data($answers[$qn][0],$qtype, $pop);
                        $stats = $this->get_question_stats($DATA, $qtype, $Nanswers);
                        $dist  = $this->get_question_dist($stats, $answers[$qn][0], $qtype,$score);
                    } else {
                        $dist = '';
                    }
                    // if NO user answer => NO answer/score
                    //$score = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
                    if (is_null($answers[$qn][1])) {
                        $ans    = '&nbsp;';
                        $score  = '&nbsp;';
                        $tscore = NULL;
                    } else {
                        //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
                        $score  = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$answers[$qn][4],$qtype);
                        $ans    = $this->render_question_answer($score, $answers[$qn][1], $qtype);
                        $tscore = $this->get_total_score($score, $answers[$qn][1], $qtype);
                    }

                    // add horiz rule
                    if ($qn == count($answers) - 1) {
                        $hr = '';
                    }
                    else {
                        $hr = '<hr class="ITS_feedback">';
                    }

                    // RENDER QUESTION PREVIEW
                    $Pstr = '<table class="ITS_QUESTION_PART">';
                    $part_header = '<h3>' . ($qn +1) . '.</h3>';
                    $Pstr = $Pstr . '<tr><td class="ITS_QUESTION_PART_NUM">' . $part_header . '</td><td class="ITS_QUESTION_PART">';
                    $Q = new ITS_question(1, 'its', $this->tb_name);
                    $Q->load_DATA_from_DB($answers[$qn][0]);
                    $Pstr = $Pstr . $Q->render_QUESTION_check();
                    $Pstr = '<b>' . $Pstr . '</b>';

                    // RECORD: Question Number | Answer | Total Score |
                    //$record = array($answers[$qn][0],$answers[$qn][1],$tscore);
                    $this->record[$i][$qn] = $tscore;
                    $SUM = $SUM + $tscore;

                    if ($i == 12) { //survey
                        if (($qn == count($answers) - 1) OR ($qn == count($answers) - 2)) {
                            $ua_str = '';
                            for ($ua = 0; $ua <= count($DATA) - 1; $ua++) {
                                if (($ua % 2) == 0) {
                                    $style = "ITS_SURVEY_STRIPE";
                                }
                                else {
                                    $style = "ITS_SURVEY";
                                }
                                $ua_str = $ua_str . '<DIV class=' . $style . '>' . $DATA[$ua] . '</DIV>';
                            }
                            $ua_str   = $ua_str;
                            $preview  = $Pstr . '<p>' . $ua_str;
                            $preview  = '<DIV class="ITS_PREVIEW">' . $preview . '</DIV>';
                            $preview .= '</td></tr></table>';
                            $tb = new ITS_table('ITS_survey', 1, 1, array($preview), array(100), 'ITS_feedback');
                            $list = $list . $tb->str . $hr;
                        } else {
                            $Q->get_ANSWERS_data_from_DB();
                            $preview = $Pstr . $Q->render_ANSWERS('a');
                            $preview = '<DIV class="ITS_PREVIEW">' . $preview . '</DIV>';
                            $preview .= '</td></tr></table>';
                            $tb = new ITS_table('ITS_survey',1,2,array($preview,$dist),array(70,30),'ITS_feedback');
                            $list .= $tb->str . $hr;
                        }
                    } else { // not survey
                        $Q->get_ANSWERS_data_from_DB();
                        $preview = $Pstr . $Q->render_ANSWERS('a');
                        $preview = '<DIV class="ITS_PREVIEW">' . $preview . '</DIV>';
                        $preview .= '</td></tr></table>';

                        $user = $this->render_user_answer($ans, $tscore, $dist, 0);
                        $tb = new ITS_table('ITS_table_stats',1,2,array($preview,$user),array(70,30),'ITS_feedback');
                        $list .= $tb->str . $hr;
                    }
                } // eof $qn
            } else {
                $list = '';
            }
            echo $list;
        } // eof $i

        //var_dump(array_sum($this->record));
        echo $SUM;
    }
    //----------------------------------------------------------------------------
    function BOOK() {
        //----------------------------------------------------------------------------
        $query = 'SELECT id,chapter,section,paragraph,content FROM dSPFirst WHERE meta="paragraph"';
        $res = $this->mdb2->query($query);
        if (PEAR :: isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $pars = $res->fetchAll();

        $book = '<div class="ITS_BOOK">';
        for ($i = 0; $i <= count($pars) - 1; $i++) {
            //echo '<p>ch '.$pars[$i][1].'|'.$pars[$i][2].'.'.$pars[$i][3].'<p>';
            $book = $book . $pars[$i][4] . '<p>';
        }
        $book = $book . '</div>';

        //var_dump($pars);
        echo $book;
        die('------ BITTE ------');
    }
    //----------------------------------------------------------------------------
    function render_user_answer($answer, $score, $dist, $config, $score_idx) {
        //----------------------------------------------------------------------------
        //ITS_debug($answer);
        //echo $score.' score idx: '.$score_idx.'|'.$config;
        //echo '<hr>';var_dump($score);echo '<hr>';die();

        $style = 'ITS_SCORE';
        //echo $config;
        switch ($config) {
            //----------------------//
            case 1 :
            //----------------------//
                $user_ans = '<table class="' . $style . '">' .
                        '<tr><td class="' . $style . ' ITS_TEXT">ANSWER</td><td class="' . $style . '">' . $answer . '</td><td rowspan="2" class="ITS_ghost">' . $dist . '</td></tr>' .
                        //'<tr><td class="' . $style . ' ITS_TEXT">Accepted Answer</td><td class="' . $style . '">' . $score[1][0] . '</td><td rowspan="2" class="ITS_ghost">' . $dist . '</td></tr>' .
                        '<tr><td class="' . $style . ' ITS_TEXT">SCORE</td><td class="' . $style . '">' .(sprintf("%d",$score[0])). '</td></tr>' .
                        '</table>';
                break;
            //----------------------//
            case 2 : // NO SCORE
            //----------------------//
                $ANR = explode(",", $answer);
                $str = '';

                if (count($ANR) - 1) {
                    $str = '<ul style="list-style-type: square;text-align:left">';
                    for ($i = 0; $i <= count($ANR) - 1; $i++) {
                        if (empty ($ANR[$i])) {
                            $an = '&nbsp;';
                        } else {
                            $an = $ANR[$i];
                        }
                        $str = $str . '<li style="display:inline"><span style="background:white;border:1px solid black;padding:0.25em">' . $an . '</span></li>';
                    }
                    $str = $str . '</ul>';
                } else {
                    $str = $str . '<div style="background:white;border:2px solid silver;padding: 0.25em">' . $ANR[0] . '</div>';
                }
                $user_ans = '<center><table class="' . $style . '" style="width:200px">' .
                        '<tr><td class="' . $style . '" style="text-align:right;width:150px;border:1px solid pink">Your Answer&nbsp;</td><td class="' . $style . '" style="font-size:large;font-weight:bold;text-align:center;">' . $str . '</td></tr>' .
                        '</table></center>';
                break;
            //----------------------//
            default:
            /*
                $user_ans = '<table class="ITS_SCORE" style="border:3px solid red">' .
                        '<tr colspan="2"><td class="ITS_SCORE" style="text-align:right">Your Answer</td><td class="ITS_SCORE">' . $answer . '</td><td>'.$dist.'</td><td>'.$rate.'</td></tr>' .
                        '<tr><td class="ITS_SCORE" style="width:20%;text-align:right">Score</td><td class="ITS_SCORE">'.(round(10*$score[0])/10).'</td></tr>' .
                        '</table>';
                        */
                $user_ans = '<center><div class="fl"><table class="ITS_SCORE">' .
						'<tr colspan="2"><td class="ITS_SCORE" style="text-align:right">Your Answer</td><td class="ITS_SCORE">' . $answer . '</td>' .
                        '<tr><td class="ITS_SCORE" style="width:20%;text-align:right">Score</td><td class="ITS_SCORE">'.(round(10*$score[0])/10).'</td></tr>' .
                        '</table></div><div class="fl">'.$dist.'</div><div class="fl">'.$rate.'</div></center>';                        
            //----------------------//
        }
        //$user = new ITS_table('ITS_table_stats',2,2,array('Answer',$answer,'Score',$score),array(20,80),'ITS_SCORE');
        return $user_ans;
    }
    //----------------------------------------------------------------------------
    function get_record() {
        //----------------------------------------------------------------------------
        //-- obtain activity: name
        $query = 'SELECT DISTINCT name,active FROM activity WHERE term="' . $this->term . '"';
        $res = $this->mdb2->query($query);
        if (PEAR :: isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $activiy_info = $res->fetchAll();

        //-- For each activity:
        $r = 0;
        for ($i = 0; $i <= count($activiy_info) - 1; $i++) {
            $activiy_name = $activiy_info[$i][0];
            $activiy_ACTIVE = $activiy_info[$i][1];

            if (($i +1) < 10) {
                $num = '0' . ($i +1);
            }
            else {
                $num = $i +1;
            }

            // activity completed?
            $query = 'SELECT ' . $activiy_name . ' FROM users WHERE id=' . $this->id;
            $res = & $this->mdb2->query($query);
            if (PEAR :: isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $activity_completed = $res->fetchRow();
            $activity_COMPLETE = $activity_completed[0];

            //-- Result: question_id | answered => for user's activity and term
            if ($activity_COMPLETE) {
                $query = "SELECT activity.question_id,answered,qtype,answers,comment FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) LEFT JOIN stats_$this->id ON (activity.question_id=stats_$this->id.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder";
            } else {
                $query = "SELECT question_id,NULL,qtype,answers,comment FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder";
            }
            //echo $query;die();
            $res = & $this->mdb2->query($query);
            if (PEAR :: isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $answers = $res->fetchAll();

            $list = '';
            //-- LIST of questions
            for ($qn = 0; $qn <= count($answers) - 1; $qn++) {
                $qtype = strtolower($answers[$qn][2]);
                $Nanswers = $answers[$qn][3];

                // if NO user answer => NO answer/score
                //$score = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
                //echo $answers[$qn][1];
                if (is_null($answers[$qn][1])) {
                    $ans = '&nbsp;';
                    $score = '&nbsp;';
                    $tscore = 'NULL';
                } else {
                    //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
                    $score = $this->get_question_score($answers[$qn][0], $answers[$qn][1], $answers[$qn][4], $qtype);
                    $tscore = $this->get_total_score($score, $answers[$qn][1], $qtype);
                }

                // RECORD: Question Number | Answer | Total Score |
                //$record = array($answers[$qn][0],$answers[$qn][1],$tscore);
                $this->record[$r] = $tscore;
                $r++;
            } // eof $qn
        } // eof $i

        return $this->record;
    }
    //----------------------------------------------------------------------------
    function get_class_stats($class_info,$data) {
        //----------------------------------------------------------------------------
        $DATA = array_count_values($data);
        arsort($DATA);
        $keys = array_keys($DATA);
        $data = array ();

        for ($k = 0; $k < count($keys); $k++) {
            $data[$k] = $DATA[$keys[$k]];
            //$stats[$mc] = round(100*($data_count[chr($mc+65)]/count($data)));
        }
        $data_str = implode("*", $data);
        $label    = implode("*", $keys);

        $img = '<img src="phpimg/ITS_pie.php?data=' . $data_str . '&label=' . $label . '" class="ITS_list"/>';
        $caption = '<DIV class="ITS_CAPTION">' . $class_info . ' Class Composition</DIV>';
        $tb_class = new ITS_table('ITS_table_class_stats',2,1,array($caption,$img),array(100),'ITS_DIST');

        echo '<center>' . $tb_class->str . '</center><p>';
        /*
		// show BMED proportion
		$total = array_sum($d);
		$BMED = $DATA['BMED'];
		echo '<img src="phpimg/ITS_pie.php?data='.$BMED.'*'.($total-$BMED).'&label=BMED*non-BMED"/>';
        */
        //return $data_count;
    }
    //----------------------------------------------------------------------------
    function get_total_score($score, $qanswer, $qtype) {
        //----------------------------------------------------------------------------
        //var_dump($score); die();
        switch (strtolower($qtype)) {
            //-------------------------------
            case 'm' :
            //-------------------------------
            // var_dump($score); //die();
                if (is_array($score)) {
                    $total_score = array_sum($score);
                }  //$score[0]
                else {
                    $total_score = $score;
                }
                break;
            //-------------------------------
            default :
                $total_score = $score;
            //-------------------------------
        }
        // format to display only 2 sig digits
        $total_score = round(100 * $total_score) / 100;

        return $total_score;
    }
    //----------------------------------------------------------------------------
    function get_question_score($qid, $qanswer, $conf, $qtype) {
        //----------------------------------------------------------------------------
        //echo 'get_question_score'; echo 'qid: '.$qid.'| ans: '.$qanswer.'| conf: '.$conf.' | type: '.$qtype.'<p>'; //die();

        $qtype = strtolower($qtype);
        switch ($qtype) {
            //-------------------------------
            case 'mc' :
            //-------------------------------
                if (empty ($qanswer)) {
                    $score = array(0,'');
                } else {
                    $query = 'SELECT weight' . (ord($qanswer) - 64) . ' FROM ' . $this->tb_name . '_' . $qtype . ' WHERE id=' . $qid;
                    //$query = 'SELECT weight' . $qanswer . ' FROM ' . $this->tb_name . '_' . $qtype . ' WHERE id=' . $qid;
                    //echo $query;
                    $res = & $this->mdb2->query($query);
                    if (PEAR :: isError($res)) {
                        throw new Question_Control_Exception($res->getMessage());
                    }
                    $result = $res->fetchRow();
                    $score = array($result[0],(ord($qanswer) - 64));
                }
                break;
            //-------------------------------
            case 'm' :
            //-------------------------------
                $score_arr = array ();
                $answered  = explode(',', $qanswer);
                $config    = explode(',', $conf);

                $idx = array();
                for ($i = 0; $i < count($config); $i++) {
                    if (!empty($config[$i])) {
                        $idx[abs($config[$i])-1] = $config[$i];
                    }
                }
                $N = 0;
                for ($cc = 0; $cc < count($config); $cc++) {
                    if ($config[$cc]>0) {
                        $N++;
                    }
                }
                //echo '<p>ANSWERED: '.implode('_',$answered).'<p>';
                //echo '<p>CONFIG: '.implode('_',$config).'<p>'; //die();

                $vi = 0;
                for ($v = 0; $v < count($answered); $v++) {
                    //echo $answered[0].'+'.$idx.'<p>';
                    if    (empty($idx)) {
                        $score_arr[$v] = NULL;
                    }
                    elseif ($idx[$v]<0) {
                        $score_arr[$v] = NULL;
                    }
                    else {
                        if (empty($answered[$v])) {
                            $score_arr[$v] = 0;
                        } else {
                            $index = (abs($config[$answered[$v]-1]))-1;
                            $score_arr[$v] = 100 * ((int) ($v == $index)/ $N);
                        }
                    }
                }

                $scr   = array_sum($score_arr);
                //echo '<hr><pre>';var_dump($score_arr);echo '</pre><hr>';
                //echo $scr. '---------------';
                $score = array($scr,$score_arr);
                /*
				$query = 'SELECT lower(answered),comment FROM stats_' . $this->id . ' WHERE question_id=' . $qid;
				$res = & $this->mdb2->query($query);
				if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
				$result = $res->fetchAll();
                */
                /*----------------------
        echo '<p>SCORING:<p>';
        print_r($result);
        echo '<p>=======<p>';
        ----------------------*/

                /* USE ONLY LAST ANSWER !! */
                /*
				$score = array ();
				for ($an = 0; $an <= (count($result) - 1); $an++) {
					$answered = explode(',', $result[$an][0]);
					$config   = explode(',', $result[$an][1]);
					
					$idx = array();
					foreach ($config as $ci) {
					   $idx[abs($ci)-1] = $ci;
					}									
					
					$N = 0;
					for ($cc = 0; $cc < count($config); $cc++) {
					  if ($config[$cc]>0) { $N++; }  
					}
	        //echo '<p>ANSWERED: '.implode('_',$answered).'<p>';
	        //echo '<p>CONFIG: '.implode('_',$config).'<p>'; //die();
				
					$vi = 0;
					for ($v = 0; $v < count($answered); $v++) {	
					  if ($idx[$v]<0) {		
						  $score[$an][$v] = NULL;			
					  }
						else {				
    					if (empty($answered[$v])) { 
    						   $score[$an][$v] = 0;
    					} else {
  						     $index = (abs($config[$answered[$v]-1]))-1;
  						     $score[$an][$v] = 100 * ((int) ($v == $index)/ $N);
    					}
					  }
				  }
				}*/
                break;
            //-------------------------------
            case 'c' :
            //-------------------------------
            // user answer:
                $answer = explode(',', $qanswer);
//echo 'qAnswer '.$qanswer.'<p>';

                $query = 'SELECT * FROM ' . $this->tb_name . '_' . $qtype . ' WHERE id=' . $qid;
                $res = & $this->mdb2->query($query);
                if (PEAR :: isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $result = $res->fetchRow();
//echo $query.'<p>';
                // Obtain values and range
                $Nvals = $result[(count($result) - 1)];
//echo 'N '.$Nvals.'<p>';				
                $formula = $result[1]; // formula

                // IF user-stored value exists, use it in formula, ELSE get min_val{i}
                // IT DOES NOT EXIST
                $val_idx = 2;
                $replace = explode(',',$conf);

                for ($v = 0; $v <= ($Nvals-1); $v++) {
                    /*
					if (count($answer) <= 1) {
					  echo 'replace with min';
						$replace[$v] = $result[3 * ($v +1)];
					} // min_val{v}
					else {
						$replace[$v] = $answer[$v+1];
					//}
                    */
                    // echo '<p>REPLACE: '.$result[$val_idx].' * '.$replace[$v].'<p>';
                    $formula = str_replace($result[$val_idx], $replace[$v], $formula);
                    $val_idx = $val_idx + 3;
                }
                //eval("\$cc = \"$formula\";");
                //echo $formula.'<p>';
                //$equation = "(1-0.7**(5+1))/(1-0.7)";
                $pattern = '/([\d.]+)\*\*[\(]([\d\+\-\*\/]+)[\)]/';
                $replacement = 'pow($1,$2)';
                $equation = preg_replace($pattern, $replacement, $formula);
                //echo  'EQUATION: '.$equation;

                //$equation = str_replace($result[$val_idx],'pow($1,$2), $equation);
                eval("\$solution=" . $equation . ";" );
                //echo  'SOLUTION: '.$solution;
                if (empty ($formula)) {
                    $solution = 'NO SOLUTION EXISTS';
                }
                //eval("\$str = \"$str\";");
                //else                  { /*eval("\$solution=" . $formula . ";");*/ }

                //DEBUG: echo 'FORMULA '.$formula.'<br>SOLUTION '.$solution.'<p>';

                if ($solution==0) {
                    $solution = 0;
                }
                elseif (empty($solution)) {
                    $solution = NULL;
                }

                //DEBUG: echo 'FORMULA '.$formula.'<br>SOLUTION '.$solution.'<p>';
                // SCORE: (user_answer-solution)/solution
                $chunks = preg_split("/[\s,=]+/", $answer[0]);
                //var_dump($chunks);die();
                for ($a = 0; $a <= count($chunks) - 1; $a++) {
                    //echo $chunks[$a].'<p>';
                    if (is_numeric($chunks[$a])) {
                        //echo '<p>'.$chunks[$a].' | '.$chunks[$a].'<p>';
                        if ($solution==0) {
                            $toll_array[$a] = abs($chunks[$a]);
                        }
                        else {
                            $toll_array[$a] = abs(1 - ($chunks[$a] / $solution));
                        }
                    } else {
                        $tmp = '';
                        //echo $chunks[$a];//die();
                        eval ('$tmp="' . $chunks[$a] . '";');
                        //echo $tmp;die();
                        if (is_numeric($tmp)) {
                            $toll_array[$a] = abs(1 - ($tmp / $solution));
                        } else {
                            //die('not num');
                            eval ("\$tmp=\"$chunks[$a]\";");

                            //echo 'SOLUTION: '.$solution.'||<p>';
                            $toll_array[$a] = abs(1 - ($chunks[$a] / $solution));
                            //echo '<p>'.$toll_array[$a].' | '.$chunks[$a].'<p>';
                        }
                    }
                }
                // obtain highest tolerance
                // print_r($toll_array);die();
                sort($toll_array);
                $toll = $toll_array[0];

                //echo '<p>score: '.$toll.'</p>';//die();

                // tolerate 0.02 deviation: 2% + 1e-4
                $t = 0.02 + 1e-4;
                //echo '<p><font color="red">| '.$toll.' <= '.($t).' || '.($toll-$t).'</font></p>';

                if ($toll <= $t) {
                    $scr = 100;
                }
                else {
                    $scr = 0;
                }

                // Display
                // DEBUG: echo '<p>score: '.$scr.'</p>';
                if ($solution < 1) {
                    $sol = sprintf("%1.4f", $solution);
                    $div = trim(sprintf("%1.4f",$solution*0.02));
                }
                else {
                    $sol = sprintf("%1.2f", $solution);
                    $div = sprintf("%1.2f",$solution*0.02);
                }
                $score = array($scr,array($sol,$div));
//echo '--debug---';				
                //$score = round(100*exp(-3*$toll));
                //if ($qid==1097){$score=100;};
                //echo '<p>FORMULA: '.$formula.' -- '.$answer[0].' | '.$solution.' | '.$toll.'<p>';
                break;
            //-------------------------------
            default:
                $score = NULL;
            //-------------------------------
        }
        //print_r($score); //die();
        return $score;
    }
    //----------------------------------------------------------------------------
    function get_question_data($qid,$qtype,$user_list_array) {
        //----------------------------------------------------------------------------
		//echo count($user_list_array); die();
        $DATA = array ();
        $DATA_array = array ();

        for ($pop = 0; $pop < count($user_list_array); $pop++) {
            $user_list = $user_list_array[$pop];

            switch (strtolower($qtype)) {
                //-------------------------------
                case 'm' :
                //-------------------------------
                /*
          $N = 0;
          for ($cc = 0; $cc < count($config); $cc++) {
             if ($config[$cc]>0) { $N++; }  
          }
                */
                // iterate thru all users
                    $idx = 0;
                    //var_dump($user_list);
                    for ($u = 0; $u < count($user_list); $u++) {
                        $query = 'SELECT answered,comment FROM stats_' . $user_list[$u] . ' WHERE question_id=' . $qid;
                        $res = & $this->mdb2->query($query);
                        if (PEAR :: isError($res)) {
                            throw new Question_Control_Exception($res->getMessage());
                        }
                        $result = $res->fetchAll();

                        //
                        //die(count($result));
                        for ($aN = 0; $aN < count($result); $aN++) {
                            if (!empty ($result[$aN][0])) {
                                /*
								  var_dump($result[$aN][0]);
									echo '<p>';
									var_dump($result[$aN][1]);
									echo '<hr>';
                                */
                                $answered = explode(',', $result[$aN][0]);
                                $config   = explode(',', $result[$aN][1]);
                                /*
									var_dump($answered);
									echo '<p>';
									var_dump($config);
									echo '<hr>';
                                */
                                $indx = array();
                                foreach ($config as $ci) {
                                    //echo $ci.'<p>';
                                    $indx[abs($ci)-1] = $ci;
                                }
                                //echo 'index: ';var_dump($indx);

                                //------
                                $vi = 0;
                                for ($v = 0; $v < count($answered); $v++) {
                                    if ($indx[$v]<0) {
                                        $arr[$v] = NULL;
                                    }
                                    else {

                                        //$arr[$v] = intval(((abs($config[$answered[$v]-1]))-1));
                                        $xx = (abs($config[$answered[$v]-1]))-1;
                                        $arr[$v] = chr($xx+97); // .'<Br>';
                                    }
                                }
                                //$DATA[$idx] = implode(',',$arr); //
                                $arr_str = implode(',',$arr);
                                //echo '>>>>'.$arr_str.'<<<<';
                                $DATA[$idx] = $arr_str; //$result[$aN][0];
                                $idx = $idx +1;
                            } else {
                                // echo 'empty at: '.$user_list[$u].' | '.$qid.' | '.$result[0].'<p>';
                            }
                        }
                    }
                    break;
                //-------------------------------
                default:
                //-------------------------------
                // iterate thru all users
                    $idx = 0;
                    //var_dump($user_list);
                    for ($u = 0; $u < count($user_list); $u++) {
                        //$query = 'SELECT answered,dept FROM stats_'.$user_list[$u].',users WHERE question_id='.$qid.' AND users.id='.$user_list[$u];
                        $query = 'SELECT answered FROM stats_' . $user_list[$u] . ' WHERE question_id=' . $qid;
                        //echo $query.'<p>';
                        $res = & $this->mdb2->query($query);
                        if (PEAR :: isError($res)) {
                            throw new Question_Control_Exception($res->getMessage());
                        }
                        $result = $res->fetchAll();

                        //var_dump($result[0]);
                        //die(count($result));
                        for ($aN = 0; $aN < count($result); $aN++) {
                            if (!empty ($result[$aN][0])) {
                                $DATA[$idx] = $result[$aN][0];
                                $idx = $idx +1;
                            } else {
                                // echo 'empty at: '.$user_list[$u].' | '.$qid.' | '.$result[0].'<p>';
                            }
                        }
                    }
                    break;
            }

            //var_dump($DATA);
            if (empty($DATA)) {
                $DATA = array();
            }
            $DATA_array[$pop] = $DATA;

            //--- garbage collection ---//
            //$res->free();
            unset ($result);
            unset ($DATA);
            //--------------------------//
        }

        return $DATA_array;
    }
    //----------------------------------------------------------------------------//
    function get_question_stats($data_arr, $qtype, $Nanswers) {
        //----------------------------------------------------------------------------//
        $stats = '';
        switch (strtolower($qtype)) {
            //-------------------------------
            case 'm' :
            //-------------------------------
            // Initialize: DATAT[choice][letter] = 0
                $keys = range('a', chr(($Nanswers -1) + 97));
                $vals = array_fill(0, ($Nanswers), 0);
                $choices = array_combine($keys, $vals);
                $stats = array_fill(0, ($Nanswers), $choices);

                /*
	echo '<hr><pre>';
	var_dump($data_arr);
	echo '</pre><hr>';
	echo 'Nanswers: '.$Nanswers.'   Choices: '.$choices;	//
	die('---');
                */
                for ($i = 0; $i < count($data_arr); $i++) {
                    $data = $data_arr[$i];
                    // for each record
                    $Nrecords = count($data);

                    /*
	echo $Nanswers;				
  echo '<pre>mm';
	print_r($keys);
	echo '</pre>kk';
	die('---');			*/
                    for ($r = 0; $r <= ($Nrecords -1); $r++) {
                        $record = explode(",", strtolower($data[$r]));
                        //echo '<p>|'.$data[$r].'|<p>';
                        //echo '<p>* '.print_r($record).' *#<p>';
                        //echo '<p style="color:blue">'.$Nanswers.'</p>';

                        for ($x = 0; $x <= ($Nanswers -1); $x++) {
                            $letter = $record[$x]; //'A'
                            //print_r($keys);die($keys);
                            //echo '<p>'.$letter.'| - '.in_array($letter,$keys).'<p>';
                            if (!empty ($letter) && in_array($letter, $keys)) {
                                $stats[$x][$letter] = $stats[$x][$letter] + 1 / $Nrecords;
                                //echo '<p>stats['.$x.']['.$letter.']=<p>';
                            }
                        }
                        //die('aaa');
                    }

                    // round to (x%)
                    $N = count($stats);
                    for ($r = 0; $r <= ($N -1); $r++) {
                        for ($x = 0; $x <= ($N -1); $x++) {
                            $stats[$r][chr($x +97)] = round(100 * $stats[$r][chr($x +97)]);
                        }
                    }
                    $stats_arr[$i] = $stats;
                }
                //var_dump($stats_arr); die('bitte');
                break;
            //-------------------------------
            case 'mc' :
            //-------------------------------
                $stats_arr = array ();
                for ($i = 0; $i < count($data_arr); $i++) {
                    $data = $data_arr[$i];
                    //var_dump($data); echo '<p>';
                    $data_count = array_count_values($data);

                    for ($mc = 0; $mc <= ($Nanswers -1); $mc++) {
                        // determine if any answers exist, if not set to 0
                        if (array_key_exists(chr($mc +65), $data_count)) {
                            $stats[$mc] = round(100 * ($data_count[chr($mc +65)] / count($data)));
                        } else {
                            $stats[$mc] = 0;
                        }
                        //$label[$mc]  = chr($mc+65);
                    }
                    $stats_arr[$i] = $stats;
                }
                //var_dump($stats_arr); die('bitte');
                break;
            //-------------------------------
            case 'p' :
            //-------------------------------
            //var_dump($data);
            //die();
                $stats_arr = array ();
                break;
            //-------------------------------
            case 'c' :
            //-------------------------------
                $L = 5; // list length
                $stats_arr = array ();
                for ($i = 0; $i < count($data_arr); $i++) {
                    $data = $data_arr[$i];
                    $data_count = array_count_values($data);
                    arsort($data_count);
                    $data_count = array_slice($data_count, 0, $L);

                    $idx = 0;
                    foreach ($data_count as $key => $value) {
                        $label[$idx] = $key;
                        $values[$idx] = $value;
                        $idx = $idx +1;
                    }
                    if (!empty($values)) {
                        $stats = array ($label,$values);
                        $stats_arr[$i] = $stats;
                    }
                }
                break;
            //-------------------------------
        }
        //var_dump($stats_arr);  //die();

        return $stats_arr;
    }
    //----------------------------------------------------------------------------
    function render_question_answer($score, $answer, $qtype, $index) {
        //----------------------------------------------------------------------------
        /*
		echo '<p>SCORE: '.implode(',',$score).'<p>';
		var_dump($score); //die();
		echo '<p>ANSWER: '.$answer.'<p>';     */

        switch (strtolower($qtype)) {
            //-------------------------------
            case 'm' :
            //-------------------------------
            //echo 'score: '.$score.' answer: '.$answer.' qtype: '.$qtype.' index: '.$index.'<hr><p>';
                $scr = $score[1];
                $ans = '';
                if (empty ($answer)) {
                    $list[0] = '<span class="TextAlphabet">&nbsp;</span><span class="ITS_null">&nbsp;</span><br>';
                } else {
                    $answer = explode(",",$answer);
                    $list   = array();
                    /*
					   echo '<p>-----| render_question_answer |-------<p><pre>';
					   var_dump($answer);
					   echo '</pre><p>$index: '.$index.'<p>------------<p>';//die();
                    */
                    $idx = 0;
                    $k = 0;
                    $ai = 0;
                    //$SR = explode(",", $score);
                    //var_dump($SR);

                    //echo '<hr>N: '.count($score).'<hr><p>'; var_dump($score);

                    $sc = 0;
                    foreach ( $scr as $s ) {
                        if (!is_null($s)) {
                            $sc++;
                        }
                    } //echo $sc;
                    for ($a = 0; $a < $sc; $a++) {  // count($answer)
                        //echo 'THIS: '.$score[0][$a].'<p>';
                        //&if (!is_null($score[$a])) {
                        //echo '<p>null?-'.$score[$index][$a].'<p>';
                        $msg     = '';
                        if (empty ($answer[$a])) {
                            $ans_str = '&nbsp;';
                            $class   = 'ITS_null';
                        }
                        else {
                            //echo ' - '.$answer[$a];
                            $ans_str = chr($answer[$a]+64); //strtoupper($answer[$a]); //
                            if   ( empty ($scr)) {
                                $class = "ITS_null";
                            }
                            else {
                                //echo '<p>SCORE: '.$score[$score_idx][$a].'<p>';
                                if ($scr[$a] == 0) {
                                    $class = 'ITS_incorrect';
                                    $msg = 'Incorrect';
                                }
                                else {
                                    $class = 'ITS_correct';
                                    $msg = '<b>Correct</b>';
                                }
                            }
                        }
                        $list[$k]   = '<b>'.($ai+1).'.</b>';
                        $list[$k+1] = '<span class="' . $class . '">'. $ans_str . '</span>';
                        $list[$k+2] = $msg;
                        $k = $k+3;
                        $ai++;
                        //&}
                    } //
                }
                $tb  = new ITS_table('name',count($list)/3,3,$list,array(33,34,33),'ITS_LIST');
                $ans = '<center>'.$tb->str.'</center>';
                break;
            //-------------------------------
            default :
            //-------------------------------
                if (is_null($score[0])) {
                    $ans = '<div class="ITS_feedback" style="background: #FF9">' . $answer . '</div>';
                }
                elseif ($score[0]==100) {
                    $ans = '<div class="ITS_feedback"><span class="ITS_correct">' . $answer . '</span>&nbsp;&nbsp;<b>Correct</b>&nbsp;</div>';
                }
                elseif ($score[0]==0) {
                    $ans = '<div class="ITS_feedback"><span class="ITS_incorrect">' . $answer . '</span>&nbsp;&nbsp;Incorrect&nbsp;</div>';
                }
                else {
                    $ans = '<div class="ITS_feedback"><span class="ITS_partial">' . $answer . '</span>&nbsp;&nbsp;Partial Credit&nbsp;</div>';
                }
            //-------------------------------
        }
        return $ans;
    }
    //----------------------------------------------------------------------------
    function get_question_dist($stats,$qid,$qtype,$title,$score) {
        //----------------------------------------------------------------------------
        if (!empty($stats)) {
            $dist = array ();
            switch (strtolower($qtype)) {
                //-------------------------------
                case 'm' :
                //-------------------------------
                //echo '<pre>';print_r($stats);echo '</pre>';//die('---');
                // Obtain number of questions
                    $fields = 'L1,L2,L3,L4,L5,L6,L7,L8,L9,L10,L11,L12,L13,L14,L15,L16,L17,L18,R19,L20,L21,L22,L23,L24,L25,L26,L27';
                    $query = 'SELECT ' . $fields . ' FROM webct_m WHERE id=' . $qid;
                    //die($query);
                    $res = & $this->mdb2->query($query);
                    if (PEAR :: isError($res)) {
                        throw new Question_Control_Exception($res->getMessage());
                    }
                    $result = $res->fetchRow();
                    $Nques  = count(array_filter($result));

                    // Obtain number of choices
                    $queryA = 'SELECT answers FROM webct WHERE id=' . $qid;
                    $resA = & $this->mdb2->query($queryA);
                    if (PEAR :: isError($resA)) {
                        throw new Question_Control_Exception($res->getMessage());
                    }
                    $resultA  = $resA->fetchRow();

                    $Nchoices = $resultA[0];  //die($Nchoices);
                    $width = array_fill(0, $Nchoices +1, 100 / $Nchoices);
                    //
                    //echo 'N-choices: '.$Nchoices.'<p>';
                    //var_dump(count($stats));//die();

                    //-- draw distribution /*
                    $values = '';
                    /*
    echo '<pre style="color:red">';
	print_r($stats);
	echo '</pre>';		*/

                    for ($v=0; $v <= (count($stats)-1); $v++) {
                        if ($v==0) {
                            $sep = '';
                        }
                        else {
                            $sep = ',';
                        }
                        //var_dump($stats[$v]);
                        //echo implode(",",$stats[0][$v]).'<p>';
                        $values = $values.$sep.implode(",",$stats[0][$v]);
                    }

                    $data = array ();
                    $data = range('A', chr($Nchoices +64));
                    foreach ($data as & $val) {
                        $val = '<font color="blue"><b>' . $val . '</b></font>';
                    }
                    array_unshift($data, '<font color="green"><b>%</b></font>');

                    $idx = $Nchoices + 1;
                    //var_dump($stats); die();
                    for ($k = 0; $k < count($stats); $k++) {
                        $st = $stats[$k];
                        //print_r($st);die();

                        /***************************/

                        for ($r = 0; $r < (count($st) - 0); $r++) {   ////for ($r = 0; $r < (count($st) - 1); $r++) {
                            $data[$idx] = '<b>' . ($r +1) . '.</b>';
                            $idx++;
                            for ($c = 0; $c < $Nchoices; $c++) {
                                //echo $stats[$r][chr($c+97)].'<p>';
                                $data[$idx] = $st[$r][chr($c +97)];
                                $idx++;
                            }
                        }
                        //die('EE: '.count($st));
                        $tb = new ITS_table('name', ($Nques+1), ($Nchoices +1), $data, $width, 'ITS_SCORE'); //(count($st) + 1)
                        $dist[$k] = $tb->str;
                        unset ($st);
                        unset ($data);
                    }
                    //$dist = '<img src="phpimg/ITS_matrix.php?size='.$Nchoices.'&values='.$values.'" class="ITS_list">';
                    break;
                //-------------------------------
                case 'mc' :
                //-------------------------------
                //-- draw distribution
                    for ($k = 0; $k < count($stats); $k++) {
                        $values = implode(",", $stats[$k]);
                        $d = '<img src="phpimg/ITS_bar.php?values=' . $values . '" class="ITS_list">';
                        $dist[$k] = $d;
                    }
                    break;
                //-------------------------------
                case 'c' :
                //-------------------------------
                    $data[0] = $score[1][0].'<font color="#922"> &plusmn; '.$score[1][1].'</font>'; //((round(1000*$score[1][1]))/1000);
                    //$data[1] = 'X % Got It';
                    $width = array(20,80);
                    $tb = new ITS_table('DIST',1,1,$data,$width,'ITS_SCORE');
                    $dist[0] = $tb->str;
                    /*
      				$idx = 0;
      				for ($k = 0; $k < count($stats); $k++) {
      					$st = $stats[$k];
      					for ($r = 0; $r <= (count($st[0]) - 1); $r++) {
      						$data[$idx] = '<tt>' . $st[1][$r] . '%</tt>';
      						$data[$idx +1] = '<b>' . $st[0][$r] . '</b>';
      						$idx = $idx +2;
      					}
      					$width = array(20,80);
      					$tb = new ITS_table('DIST',count($stats[0]),2,$data,$width,'ITS_SCORE');
      					$dist[$k] = $tb->str;
      					//$dist = '<img src="phpimg/ITS_list.php?values='.$values.'&labels='.$label.'" class="ITS_list">';
      				}*/
                    break;
                //-------------------------------
                default :
                //-------------------------------
                    for ($k = 0; $k < count($stats); $k++) {
                        $dist[$k] = 'default';
                    }
                //-------------------------------
            } //end swtich
            $dist_str = array();
            $nn = count($dist);

            $distSTR = '';
            for ($k = 0; $k < $nn; $k++) {
                $caption = '<DIV class="ITS_CAPTION">'.preg_replace('[_]',' ',$title[$k]).'</DIV>';
                $class = new ITS_table('ITS_table_stats',2,1,array($caption,$dist[$k]),array(100),'ITS_DIST');

                //$dist_str = $dist_str.$class->str;
                //$dist_str[$k] = $class->str;
                $distSTR .= '<div class="ITS_DIST">'.$class->str.'</div>';
            }/*
      		//echo '---<p>'.$nn.'<p>-----'; //die();
      		//var_dump($arr); die();
      		if (!empty ($dist_str)) {
      			$width = array_fill(0, $nn, round(100 / $nn));
      			$dist = new ITS_table('ITS_table_s', 1, $nn, $dist_str, $width, 'ITS_DIST2');
      		}*/				
            $dist = $distSTR;
        } else {
            $dist = '';
        }
        return $dist;
    }
    //----------------------------------------------------------------------------
    function render_survey($term) {
        //----------------------------------------------------------------------------
        $Nterms = count($term);
        for ($t = 0; $t < $Nterms; $t++) {
            //-- obtain activity: name
            $query = 'SELECT DISTINCT name,active FROM activity WHERE term="' . $term[$t] . '"';
            //-echo $query; die();

            $res = $this->mdb2->query($query);
            if (PEAR :: isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $activiy_info = $res->fetchAll();

            // activity header
            $title = 'Activity';
            //$tb    = new ITS_table('ITS_table_stats',1,2,array($term[$t].' SURVEY QUESTIONS',$term[$t].' class'),array(70,30),'ITS_feedback_header');
            //$tb_activity = new ITS_table('ITS_activity',1,2,array($title,$tb->str),array(15,85),'ITS_feedback_header');
            //echo $tb->str;
            //<--+++++-->//
            $class_info = preg_replace('[_]', ' ', $term[$t]);
            //echo '<h2><font color="royalblue">' . $class_info . ' COURSE SURVEY</font></h2><p>';
            //echo '<p style="margin-top:20px"></p>';

            $i = 13; // survey
            $activiy_name = $activiy_info[$t][0];
            $activiy_ACTIVE = 1;        // $activiy_info[$i][1];

            if (($i +1) < 10) {
                $num = '0'.($i +1);
            }
            else {
                $num = $i +1;
            }

            //----***--------//
            $query = 'SELECT id FROM users WHERE status="' . $term[$t] . '"'; // AND ' . $activiy_name . ' IS NOT NULL';
            //$DATA     = $this->get_question_data($answers[$qn][0],$pop);
            //echo $query;die();

            $res = & $this->mdb2->query($query);
            if (PEAR :: isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $activity_users = $res->fetchCol();

            /*
			// CLASS PIE CHART: BY DESIGNATED MAJOR
			$query = 'SELECT dept,id FROM users WHERE status="' . $term[$t] . '"'; //AND ' . $activiy_name . ' IS NOT NULL';
			$res = & $this->mdb2->query($query);
			if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
			$class_dept = $res->fetchCol();
			$this->get_class_stats($class_info, $class_dept);
            */

            /*
			// BMED
			$query_BMED = 'SELECT id FROM users WHERE status="' . $term[$t] . '" AND dept="BMED" AND ' . $activiy_name . ' IS NOT NULL';
			$res_BMED = & $this->mdb2->query($query_BMED);
			if (PEAR :: isError($res)) { throw new Question_Control_Exception($res->getMessage()); }
			$activity_users_BMED = $res_BMED->fetchCol();

			// non-BMED
			$query_nonBMED = 'SELECT id FROM users WHERE status="' . $term[$t] . '" AND dept!="BMED" AND ' . $activiy_name . ' IS NOT NULL';
			$res_nonBMED = & $this->mdb2->query($query_nonBMED);
			if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
			$activity_users_nonBMED = $res_nonBMED->fetchCol();
            */
            //----***--------//
            //var_dump($activity_users).'<p>';

            // activity completed?
            if (count($activity_users)) {
                $query = 'SELECT ' . $activiy_name . ' FROM users WHERE id=' . $this->id;
                $res = & $this->mdb2->query($query);
                if (PEAR :: isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $activity_completed = $res->fetchRow();
                $activity_COMPLETE  = $activity_completed[0];
            }
            $stats_SHOW = FALSE;

            if (strcmp($activiy_name, 'survey01') == 0) {
                $title_name = 'Survey';
            }
            else {
                $title_name = 'Exercise #' . ($i +1);
            }

            $title = '<font color="royalblue">' . $title_name . '</font>';

            // TITLE: IF active & !complete => link, ELSE name
            // OTHER: IF !active & complete => show, ELSE nothing
            if ($activiy_ACTIVE) {
                if (!($activity_COMPLETE)) {
                    // TITLE
                    $title = '<a href="ITS_pre_lab.php?activity=' . ($i +1) . '">' . $title . '</a>';
                }
            } else {
                if ($activity_COMPLETE) {
                    $stats_SHOW = TRUE;
                }
            }

            $activiy_ACTIVE    = FALSE;
            $activity_COMPLETE = TRUE;
            $stats_SHOW        = TRUE;

            $TERMS[$t][0] = $activity_users;
        } //eof ($t)

        //echo '<p>'.$activiy_ACTIVE.' | '.$activity_COMPLETE.' | '.$stats_SHOW.'<p>';
        if ($stats_SHOW) {
            for ($t = 0; $t < $Nterms; $t++) {
                //-- Result: question_id | answered => for user's activity and term
                if ($activity_COMPLETE) {
                    $query = "SELECT activity.question_id,answered,qtype,answers,comment FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) LEFT JOIN stats_$this->id ON (activity.question_id=stats_$this->id.question_id) WHERE name='$activiy_name' AND term='$this->term' GROUP BY qorder";
                } else {
                    $query = "SELECT question_id,NULL,qtype,answers,comment FROM activity LEFT JOIN webct ON (webct.id=activity.question_id) WHERE name='$activiy_name' AND term='$term[$t]' GROUP BY qorder";
                }

                //echo $query;die();
                $res = & $this->mdb2->query($query);
                if (PEAR :: isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $answers = $res->fetchAll();
                $TERMS[$t][1] = $answers;
            }

            $list = '';
            //$pop = array ($activity_users,$activity_users_BMED, $activity_users_nonBMED);
            $pop = array($activity_users);
            //$pop = array($TERMS[0][0],$TERMS[1][0]);

            //-- LIST of questions (count($answers)-1)
            for ($qn = 0; $qn <= (count($answers)-1); $qn++) {
                $qtype    = strtolower($answers[$qn][2]);
                $Nanswers = $answers[$qn][3];

                for ($t = 0; $t < $Nterms; $t++) {
                    // if NO activity users => NO distribution
                    // echo '<p>activity_users = '.count($activity_users).'<p>';

                    /*
	 			  $TERMS[$t][0] = user list
					$TERMS[$t][1] = answer list
                    */
                    $Nusers = count($TERMS[$t][0]);
                    if ($Nusers) {
                        $DATA     = $this->get_question_data($answers[$qn][0],$qtype,$pop);
                        $stats    = $this->get_question_stats($DATA, $qtype, $Nanswers); // $Nanswers
                        /*
echo '<pre>STATS';
print_r($stats);
echo '</pre>';
                        */
                        //$dist[$t] = $this->get_question_dist($stats,$answers[$qn][0],$qtype,$term);
                        $dist = $this->get_question_dist($stats,$answers[$qn][0],$qtype,$term,$score);
                    } else {
                        $dist[$t] = '';
                    }
                } //eof ($t)
                //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
                $score = $this->get_question_score($answers[$qn][0], $answers[$qn][1], $answers[$qn][4], $qtype);
                $ans   = $this->render_question_answer($score, $answers[$qn][1], $qtype,1);

                $Q = new ITS_question(1, $this->db_name, $this->tb_name);
                $Q->load_DATA_from_DB($answers[$qn][0]);
                $Estr = $Q->render_QUESTION_check(0);

                $Estr = '<table class="SURVEY">'
                        .'<tr><td class="SURVEY" style="width:1%"><div class="ITS_QN">' . ($qn +1) . '.</div><a href="Question.php?qNum='.$answers[$qn][0].'" class="ITS_ADMIN">'.$answers[$qn][0].'</a></td><td class="SURVEY"><b>' . $Estr . '</b></td></tr>'
                        .'<tr><td class="SURVEY" colspan="2">';

                switch (strtolower($qtype)) {
                    case 'mc':
                    case 'm':
                        $Q->get_ANSWERS_data_from_DB();
                        $Estr  = $Estr . $Q->render_ANSWERS('a',0);
                        $dists = $dist; //$dists = implode('', $dist);
                        $Estr .= '<p>'.$dists.'</p></td></tr>';
                        break;
                    case 'p':
                        $ua_str = '<table class="ITS_survey">';
                        for ($ua = 0; $ua <= count($DATA[0]) - 1; $ua++) {
                            if (($ua % 2) == 0) {
                                $style = "ITS_SURVEY_STRIPE";
                            }
                            else {
                                $style = "ITS_SURVEY";
                            }
                            $ua_str = $ua_str . '<tr><td class="'.$style.'">'.$DATA[0][$ua].'</td></tr>';
                        }
                        $ua_str .= '</table>';
                        $Estr   .= $ua_str.'</td></tr>';
                        break;
                }
                //echo '<p>'.$qn.'<p>';
                echo $Estr.'</table><hr class="ITS_feedback">';
            } // eof $qn
        } else {
            $list = '';
        }
    }
    //----------------------------------------------------------------------------
    function render_profile() {
        //----------------------------------------------------------------------------
        die('now');
        //ITS_debug();
        //$term = array('Fall_2008','Spring_2009','Fall_2009','Spring_2010');
        $term = array ('Fall_2010');
        $chapter = 1;

        $Nterms = count($term);
        for ($t = 0; $t < $Nterms; $t++) {
            echo '<h2><font color="royalblue">' . $chapter . ' COURSE SURVEY</font></h2><p>';

            //var_dump($activity_users).'<p>';
            // activity completed?
            /*
			if (count($activity_users)) {
				$query = 'SELECT ' . $activiy_name . ' FROM users WHERE id=' . $this->id;
				$res = & $this->mdb2->query($query);
				if (PEAR :: isError($res)) {throw new Question_Control_Exception($res->getMessage());}
				$activity_completed = $res->fetchRow();
				$activity_COMPLETE = $activity_completed[0];
			}*/
            $stats_SHOW = FALSE;

            $activiy_ACTIVE    = FALSE;
            $activity_COMPLETE = TRUE;
            $stats_SHOW        = TRUE;

            $activity_users = array();
            $TERMS[$t][0] = $activity_users;
        } //eof ($t)

        //echo '<p>'.$activiy_ACTIVE.' | '.$activity_COMPLETE.' | '.$stats_SHOW.'<p>';
        if ($stats_SHOW) {
            for ($t = 0; $t < $Nterms; $t++) {
                //-- Result: question_id | answered => for user's activity and term
                $query = "SELECT question_id,answered,qtype,answers,comment FROM stats_$this->id,webct WHERE webct.id=stats_$this->id.question_id";

                //echo $query;die();
                $res = & $this->mdb2->query($query);
                if (PEAR :: isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $answers = $res->fetchAll();
                $TERMS[$t][1] = $answers;
            }

            $list = '';

            $pop = array ($activity_users);
            //$pop = array($activity_users);

            //-- LIST of questions (count($answers)-1)
            for ($qn = 0; $qn <= 3; $qn++) {  // (count($answers)-1)
                $qtype = strtolower($answers[$qn][2]);
                $Nanswers = $answers[$qn][3];

                for ($t = 0; $t < $Nterms; $t++) {
                    // if NO activity users => NO distribution
                    //echo '<p>activity_users = '.count($activity_users).'<p>';
                    /*
					if (count($TERMS[$t][0])) {
						$DATA = $this->get_question_data($answers[$qn][0], $pop);
						$stats = $this->get_question_stats($DATA, $qtype, $Nanswers);
						$dist[$t] = $this->get_question_dist($stats, $answers[$qn][0], $qtype);
					} else {
						$dist[$t] = '';
					}*/
                    $dist[$t] = '';
                } //eof ($t)
                //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
                //get_question_score($qid, $qanswer, $qtype)
                $score = $this->get_question_score($answers[$qn][0], $answers[$qn][1], $answers[$qn][4], $qtype);
                $ans = $this->render_question_answer($score, $answers[$qn][1], $qtype);

                $Q = new ITS_question(1, $this->db_name, $this->tb_name);
                $Q->load_DATA_from_DB($answers[$qn][0]);
                $Estr = $Q->render_QUESTION_check();

                $Estr = '<table class="SURVEY">'
                        .'<tr><td class="SURVEY" style="width:1%"><div class="ITS_QN">' . ($qn +1) . '.</div></td><td class="SURVEY"><b>' . $Estr . '</b></td></tr>'
                        .'<tr><td class="SURVEY" colspan="2">';

                $Q->get_ANSWERS_data_from_DB();
                $Estr = $Estr . $Q->render_ANSWERS('a', 0);

                // add horiz rule
                if ($qn == count($answers) - 1) {
                    $hr = '';
                }
                else {
                    $hr = '<hr class="ITS_feedback">';
                }
                //echo '<p>'.$qn.'<p>';
                $config = 1;
                $ANU = $this->render_user_answer($ans, $score, $dist, $config);
                //$R = $this->get_record();
                //var_dump($R);//die();
                $dists = implode('', $dist);
                $Estr .= 'XXX'.$ANU.'</td></tr>';
                echo $Estr.'</table>'.$hr;
            } // eof $qn
        } else {
            $list = '';
        }
    }
    //----------------------------------------------------------------------------
    function render_profile2($chapter,$orderby) {
        //----------------------------------------------------------------------------
        //die('function render_profile2($chapter,$orderby)');
        $term = $this->term;
        $ITSq = new ITS_query();
        //$term = array('Fall_2010');

        $Nterms = count($term);
        for ($t = 0; $t < $Nterms; $t++) {
            //echo '<h2><font color="royalblue">Chapter #' . $chapter . '</font></h2><p>';

            $stats_SHOW     = TRUE;

            $activity_users = array();
            $TERMS[$t][0] = $activity_users;

            //echo '<p>'.$activiy_ACTIVE.' | '.$activity_COMPLETE.' | '.$stats_SHOW.'<p>';

            if ($stats_SHOW) {
                for ($t = 0; $t < $Nterms; $t++) {
                    //-- Result: question_id | answered => for user's activity and term
                    if ($chapter > 13) {
                        $query = 'SELECT question_id,answered,qtype,answers,comment,epochtime,duration,rating FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter="'.$chapter.'" ORDER BY stats_'.$this->id.'.'.$orderby;
                        $column = '';
                    }
                    else {
                        $category = $ITSq->getCategory($chapter);
                        $query = 'SELECT question_id,answered,qtype,answers,comment,epochtime,duration,rating FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter="'.$chapter.'" AND '.$category.' AND answered IS NOT NULL ORDER BY stats_'.$this->id.'.'.$orderby;
                        //die($query);
                        //AND comment<>"skip"
                        $column = '<th style="width:5%;">Score</th>';
                    }
                    //echo $query; //	 die();

                    $res = & $this->mdb2->query($query);
                    if (PEAR :: isError($res)) {
                        throw new Question_Control_Exception($res->getMessage());
                    }
                    $answers = $res->fetchAll();
                    $TERMS[$t][1] = $answers;
                }

                $list = '';
                $pop  = array ($activity_users);
                //$pop = array($activity_users);

                $optionArr = array('id','score','duration','rating');
                $answerHeader = '<select id="sortProfile" sid="'.$this->id.'" section="'.$term.'" status="'.$this->role.'" ch="'.$chapter.'">';
                foreach ($optionArr as $op ) {
                    if ($orderby == $op) {
                        $sel = 'selected="selected"';
                    }
                    else {
                        $sel = '';
                    }
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

                    $score  = $this->get_question_score($answers[$qn][0], $answers[$qn][1], $answers[$qn][4], $qtype);
                    $tscore = $this->get_total_score($score[0], $answers[$qn][1], $qtype);

                    if ($chapter > 13) {
                        $config = 2;
                        $score = NULL;
                        $tscore = NULL;
                    }
                    else {
                        $config = 1;
                    }
                    //echo $score.' '.$answers[$qn][1].' '.$qtype.'<p>'; //$timestamp; die();

                    if ($qtype=='m') {
                        // Obtain number of questions
                        $fields = 'L1,L2,L3,L4,L5,L6,L7,L8,L9,L10,L11,L12,L13,L14,L15,L16,L17,L18,R19,L20,L21,L22,L23,L24,L25,L26,L27';
                        $query = 'SELECT ' . $fields . ' FROM webct_m WHERE id=' .$answers[$qn][0];
                        //die($query);
                        $res = & $this->mdb2->query($query);
                        if (PEAR :: isError($res)) {
                            throw new Question_Control_Exception($res->getMessage());
                        }
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

                    $dist = '-dist-';//'-dist-';
                    $FEEDBACK = $this->render_user_answer($ans,$score,$dist,$config,0); //##!!
                    /*
          $Estr.= '<tr class="PROFILE">'.
          '<td class="PROFILE_IDX" style="width:1%">' . ($qn +1) .'</td>'.
          '<td class="PROFILE">' . $QUESTION . '</td>'.
          '<td class="PROFILE">' . $ANSWER.'<BR>'.$FEEDBACK . '</td>'.
            '</tr>';*/

                    // COMMENT
                    $action = '';
                    /*
                    if (empty($answers[$qn][4])) {
                        $action = '';
                    }
                    else {
						switch($answers[$qn][4]){
							case 'skip':
                             $action = '<hr style="border-top:1px dashed silver"><font color="red">'.$answers[$qn][4].'</font>';
                             break;
                             default:
                             $action='';
						 }
                    }      
                    * */      
                    // TIMESTAMP
                    if (empty($answers[$qn][5])) {
                        $timestamp = '';
                    }
                    else {
                        $timestamp = '<hr style="border-top:1px dashed silver"><b><font color="darkblue" size="1.2">'.date("M j G:i:s T Y",$answers[$qn][5]).'</font></b>';
                    }
                    // DURATION
                    if (empty($answers[$qn][6])) {
                        $dur = '';
                    }
                    else {
                        $dur = '<hr style="border-top:1px dashed silver"><font color="blue">'.$answers[$qn][6].' sec</font>';
                    }
                    // RATING
                    if (empty($answers[$qn][7])) {
                        $rating = '';
                    }
                    else {
                        $rating = '<hr style="border-top:1px dashed silver"><font color="brown">'.$rateStr[$answers[$qn][7]-1].'</font>';
                    }

                    //echo $answers[$qn][5]; //$timestamp; die();
                    //style="background-color:#eee"
                    $Estr .= '<tr class="PROFILE" id="tablePROFILE">'.
                            '<td class="PROFILE" >' . ($qn+1). '<br><br><a href="Question.php?qNum='.$answers[$qn][0].'" class="ITS_ADMIN">'.$answers[$qn][0].'</a></td>'.
                            '<td class="PROFILE" >' . $QUESTION.$ANSWER. '</td>'.
                            '<td class="PROFILE" >' . $ans.$timestamp.$dur.$rating.$action.'</td>';
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
        return $Estr;
    }
    //----------------------------------------------------------------------------
    function render_class_profile($class_name,$chs) {
        //----------------------------------------------------------------------------
        //echo '<pre>render_class_profile</pre>';die();
        // Set time zone: America/New_York
        date_default_timezone_set('America/New_York');
        $ITSq  = new ITS_query();
        $tMay1 = gmmktime(10, 0, 0, 5, 1, 2011);  // epochtime for: 6am, May 1, 2011
        $epochtime = 1311960440;

        // Variables
        $excel_file 	  = $class_name.'_scores.xls';
        $excel_worksheet  = $class_name.'_scores.xls';
        $current_semester = $class_name;
        $pct = 0;
        $pcount   = array();
        $data     = array();
        $tscore   = array();
        $tattempt = array();
        $scores   = array();
        /*
	echo '<pre>';
	print_r($Nchs);
	echo '</pre>';
	die();*/

        $file_path = 'admin/csv/'.$class_name.'_scores'.$chs[count($chs)-1].'.csv';
        //die($file_path);
        $fp = fopen($file_path, 'w');

        $query = 'SELECT id,first_name,last_name,username FROM users WHERE status="'.$current_semester.'" ORDER BY last_name';
        //echo $query;die();

        $res = & $this->mdb2->query($query);
        if (PEAR :: isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $users = $res->fetchAll();

        //--- HEADER
        switch ($this->role) {
            case 'admin':
                $header = '<tr><th>id</th><th>Name</th>';
                break;
            default:
                $header = '<tr><th>Name</th>';
        }
        for ($h=0; $h<count($chs); $h++) {
            if ($h==8) {
                $header .= '<th><a href="survey1.php?survey=Fall_2011">Survey</a></th>';
            }
            else {
                $header .= '<th>Mod. '.$chs[$h].'</th>';
            }
        }
        $header .= '<th># Practice</th></tr>';
        //die($header);///*

        $fdate = date("F j, Y, g:i a T",time());  //$tMay1
        $fdate = explode(',',$fdate);
        $fdate = $fdate[0].','.$fdate[1].'<br>'.$fdate[2];

        $Estr = '<center><div class="file"><a href="'.$file_path.'" target="_blank"><img alt="Export To Excel" src="css/media/excel_graphic.png" /></a><br><font color="blue">'.date("F j, Y, g:i a T",$tMay1).'</font></div>'.
                '<table class="CPROFILE">'.$header;

        $sts 	  = array_fill(0,count($chs)-1,0);
        $full_sts = array_fill(0,count($chs)-1,0);

        foreach ($users as $key => $user) { //$users as $user){
            //Calculating Scores for this User
            $s = new ITS_statistics($user[0],$class_name,'student');
            $usertable = 'stats_'.$user[0];
            //echo '<p>'.$usertable.'</p>';
            //for every chapter
            for($j=0;$j<count($chs);$j++) {
                $score = 0;											 //for every chapter, set score to 0
                $chaptername = 'Chapter'.sprintf("%02d",$chs[$j]);

                //$q1 = 'SELECT SUM(score) AS sum FROM '.$usertable.' WHERE current_chapter='.$chs[$j].' AND epochtime > '.$epochtime;
                //$q1 = 'SELECT SUM(score) AS sum FROM '.$usertable.',webct WHERE '.$usertable.'.question_id=webct.id AND current_chapter='.$chs[$j].' AND category REGEXP "(SPEN'.$chs[$j].'$|PreLab0'.$chs[$j].'$|Lab'.$chs[$j].'$|Chapter'.$chs[$j].'$|-Mod'.$chs[$j].'$'.$other.')" AND '.$usertable.'.score IS NOT NULL AND epochtime > '.$epochtime;
                $q1    = $ITSq->getQuery('SUM(score) AS sum',$usertable,$chs[$j],$epochtime);
                $r1    = mysql_query($q1);
                $score = mysql_result($r1, 0, "sum");
                //echo '<p>'.$q1.' - '.$score.'<p>';

                //Score for jth chapter
                $totalscore[$key][$j] = round($score,2);
                if ($totalscore[$key][$j]>0) {
                    if ($totalscore[$key][$j]>=2400) {
                        $full_sts[$j]++;
                    }
                    $sts[$j]++;
                }
            }
            //--- "Practice Mode"
            $q2 = 'SELECT count(score) AS p FROM '.$usertable.' WHERE current_chapter<0 AND score IS NOT NULL AND epochtime > '.$epochtime;
            //echo '<p>'.$q2.'</p>';die();
            $r2       = mysql_query($q2);
            $pcount[] = mysql_result($r2, 0, "p");
            //echo  $pcount; die();
            //---
            //die();
            $fields = array($user[0],$user[3],$user[2],$user[1],$totalscore[$key][0],$totalscore[$key][1],$totalscore[$key][2],$totalscore[$key][3],$totalscore[$key][4],$totalscore[$key][5],$totalscore[$key][6],$totalscore[$key][7],$totalscore[$key][8]);
            $data[] = $fields;
            //print_r($fields);
            fputcsv($fp, $fields);
            //http://localhost/ITS/Profile.php?class=Spring_2011&sid=1219

            $td = '';
            $st = '';
            for ($t=0; $t<count($chs); $t++) {
                if ($t==8) {
                    if ($totalscore[$key][$t]>0) {
                        $st = ';background:#ded;text-align:center';
                    }
                }
                $td .= '<td style="text-align:right'.$st.'">'.round($totalscore[$key][$t]).'</td>';
            }
            switch ($this->role) {
                case 'admin':
                    $id_str = '<td class="sid">'.$user[0].'</td>';
                    break;
                default:
                    $id_str = '';
            }
            $Estr.= '<tr>'.
                    $id_str.
                    '<td style="text-align:left"><a href="Profile.php?class='.$current_semester.'&sid='.$user[0].'">'.$user[2].', '.$user[1].'</a></td>'.
                    $td.
                    '<td>'.(empty($pcount[$key]) ? '' : '<font color="#738">'.$pcount[$key].'</font>').'</td></tr>';
        } // eof: foreach $users
        //-- STATS --//
        $pc = 0; //count($pcount);
        foreach ($pcount as $v) {
            echo $v.'<br>';
            if(!empty($v)) {
                $pc++;
            }
        }

        $td_sts = '';
        $td_full_sts = '';
        $id_str = 'yy';
        $N = count($data);
        for ($t=0; $t<count($chs); $t++) {
            $td_sts      .= '<td style="text-align:right;font-weight:bold">'.$sts[$t].'<br>'.round(100*$sts[$t]/$N).' <font color="#669">%</font></td>';
            $td_full_sts .= '<td style="text-align:right;font-weight:bold">'.$full_sts[$t].'<br>'.round(100*$full_sts[$t]/$N).' <font color="#669">%</font></td>';
        }
        $Estr .= '<tr style="border-top:2px solid #999;background:lightyellow">'.
                '<td colspan="2" style="text-align:center"><b>ATTEMPTED</b> / '.$N.'<br><b>%</b></td>'.
                $td_sts.
                '<td colspan="2" style="text-align:center;font-weight:bold">'.$pc.'<br>'.round(100*$pc/$N).'<font color="#669">%</font></td>'.
                '</tr>'.
                '<tr style="border-top:2px solid #999;background:lightyellow">'.
                '<td colspan="2" style="text-align:center"><b>WITH FULL CREDIT</b> (2400 pts) / '.$N.'<br><b>%</b></td>'.
                $td_full_sts.
                '<td style="text-align:center;font-weight:bold"></td>'.
                '</tr>';
        $Estr.= '</table></center>';
        //-- STATS --//
        fclose($fp);

        return $Estr;
    }
    //----------------------------------------------------------------------------
    //==============================================================================
} //End of class ITS_statistics
?>
