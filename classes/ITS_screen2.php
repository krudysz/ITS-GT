<?php
//=====================================================================//
/*
ITS_screen2 - creates user ITS screen.

		Constructor: ITS_screen(name,rows,cols,data,width)
		
								 ex. $ITS_table = new ITS_screen('tableA',2,2,array(1,2,3,4),array(20,30));
								
	 Author(s): Greg Krudysz |  Oct-26-2010
	 Last Revision: Jan-03-2012
//-----------------------------------------
 SCHEMA: 
 				 screen.php
 				 - getScreen()        // draw screen
 				 -- getContent()      // draw content 
 				 ---- getChapter()    // setup chapter mode 
 				 ----- getQuestion()  // draws question			 

 TO DO:
 			 	* Answer is not submitted for type 'c'
*/
//=====================================================================//
require_once("classes/ITS_query.php");
/*-- TAGGING module ----------------------------------*/
require_once("plugins/tagging/ITS_tagInterface.php");
/*-- RATING module -----------------------------------*/
//require_once("rating/ITS_rating.php");
//RATING START
require_once("ITS_rating.php");
//RATING END
/*----------------------------------------------------*/

class ITS_screen2 {

    public $id;
    public $term;
    public $term_current;
    public $role;
    public $tb_name;
    public $record;
    public $style;

    public $screen;
    public $mode;	 			 	 // question | review | survey
    public $question_info; // := $Q->Q_answers_permutation;

    //--- LAB ---//
    public $lab_active;
    public $lab_number;
    public $lab_title;
    public $lab_name;
    public $lab_questions;
    public $lab_index;

    //--- CHAPTER ---//
    public $chapter_number;
    public $appendix_number;
    public $chapter_active;
    public $chapter_alpha;

    public function get_lab_active() {
        return $this->lab_active;
    }
    //public function set_chapter_number()    { return $this->lab_active;  }

    //=====================================================================//
    function __construct($id,$term,$role,$idx) {
        //=====================================================================//
        $this->debug = FALSE; //TRUE;

        if ($this->debug) {
            echo '<br>'.get_called_class();
        }
        global $db_dsn,$db_name,$tb_name,$db_table_user_state,$mimetex_path;

        $this->id      = $id;
        $this->term    = $term;
        $this->role    = $role;
        $this->db_name = $db_name;
        $this->tb_name = $tb_name;
        $this->tb_user = $db_table_user_state;

        $this->epochtime = 1311960440;

        //echo 'TIME '.$tstart.'<p>'.date("D M j G:i:s T Y",$tstart);
        //--- DURATION ---//
        $t = $this->epochtime; //time();
        //echo '<p>'.date("D M j G:i:s T Y",$t);  //die('done');

        $this->record  = array();
        $this->db_dsn  = $db_dsn;

        // connect to database
        $mdb2 =& MDB2::connect($db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Exception($this->mdb2->getMessage());
        }

        $this->mdb2 = $mdb2;

        // STATE
        // $this->screen = 2;
        $this->mode = 'question'; //'question' | 'review'
        $this->review_number = 0;
        $this->review_count  = 0;
        $this->question_completed = false;
        $this->question_info = array();

        // set LAB parameters
        $this->lab_number = 14;
        $this->lab_active = 14; // current lab
        $this->lab_index  = 1;
        $this->lab_title  = 'Lab #';
        $this->lab_name   = 'Lab #';

        $this->lab_tag    = 'lab';
        $this->lab_questions = array(); //array(1208,1209,1210);//array(2124,2125); //array(1199,1200); //#3array(1196,1197); //array(1193,1194);
        $this->lab_completed = false;

        // set BOOK parameters
        $this->chapter_number = $idx;//'ALL';
        $this->chapter_active = 'ALL';
        $this->chapter_alpha  = 'A';
        $this->appendix_number = 3;

        // set EXERCISES parameters
        $this->exe_active = 2; // ch.2
        $this->con_active = 8; // ch.2

        // set CONCEPTS parameters
        $this->concept_active = 2;

        if ($this->lab_active > 13) {
            $this->lab_tag    = 'survey';
        }
        else {
            $this->lab_tag    = 'lab';
        }
    }
    //=====================================================================//
    function main($mode) {
        //=====================================================================//
        // AGENDA:
        //--- STATE ---//
        $this->mode = $mode;
        $this->screen = 4; // (screen)-name = (1)-LAB | (2)-EXERCISES | (3)-CONCEPT

        $main_str = $this->getScreen($this->screen);
        //echo '<img src="phpimg/ITS_signal.php?t=i&d=-1,3" class="ITS_pzplot">';die();

        return $main_str;
    }
    //=====================================================================//
    function getScreen($screen) {
        //=====================================================================//
        //--- HEADER ---//
        //$header_str = $this->getHeader($screen);
        $header = ''; //'<p><div id="headerContainer">'.$header_str.'</div>';
        //echo $header;die();

        //--- CONTENT ---//
        $content_str = $this->getContent();
        $content = '<div id="contentContainer">'.$content_str.'</div>';
        //$content = 'content'; //$content_str;
        $screen_str = $header.$content;

        return $screen_str;
    }
    //*********************************************************************//
    //                    H E A D E R																			 //
    //*********************************************************************//
    //=====================================================================//
    function getHeader($screen) {
        //=====================================================================//
        // Labs | EXERCISES | : Screen | 1 | 2 |
        //echo 'IN HEADER: '.$screen.'<p>';

        $this->screen = $screen;
        $header_name  = array('LABS','EXERCISES');
        $header_style = array('L','L'); // link
        $N = count($header_name);

        $header_str = '';
        for ($n=0;$n<$N;$n++) {
            if (($screen-1)==$n) { // ACTIVE
                $header_active = $this->getActiveHeader($header_name[$screen-1]);
                $header_content[$n] = '<div id="ITS_screen_A">'.$header_active.'</div>';
            }
            else { // LINK <- INACTIVE
                $header_content[$n] = '<div class="ITS_screen_L" onClick="ITS_Q('.($n+1).')"><p>'.$header_name[$n].'</p></div>';
            }
            $header_str = $header_str.$header_content[$n];
        }
        //$tb_header = new ITS_table('ITS_activity',1,$N,$header_content,array(33,33,33),'ITS_header');

        return $header_str;
    }
    //=====================================================================//
    function getActiveHeader($name) {
        //=====================================================================//
        //echo 'in ActiveHeader :'.$name;die();
        switch ($name) {
            //------------------//
            case "LABS":
            //------------------//
                $Nlabs = $this->lab_number;

                //--- build index list ---//
                /*
		 $strIdx = '<ul id="navlistIdx">';
		  for ($n=0;$n<count($this->lab_questions);$n++){
			  if (($this->lab_index-1)==$n) { // SELECTED
				  $strIdx = $strIdx.'<li class="active">'.($n+1).'</li>';
			  }
			  else{ // NORMAL
			    $strIdx = $strIdx.'<li name="ITS_screen_S" onClick="ITS_changeLab(this);return false;">'.($n+1).'</li>';
				}
		  }
			$strIdx = $strIdx.'</ul>';
                */
                //------------------------//
                // build lab_number list
                $str = '<ul id="navlist"><li id="title">'.$name.':</li>';
                for ($n=0;$n<$Nlabs;$n++) {

                    if (($n+1) > 13) {
                        $name = 'Survey';
                    }
                    else {
                        $name = $n+1;
                    }

                    //echo $name.'<p>';
                    if (($this->lab_active-1)==$n) { // SELECTED
                        $str = $str.'<li name="updateLab_active" id="updateLab_active" class="active">'.$name.'</li>';
                    }
                    else { // NORMAL
                        $str = $str.'<li name="updateLab_active" id="updateLab_active" onClick="ITS_content_select(this)">'.$name.'</li>';
                    }
                }
                $str = $str.'</ul>';
                break;
            //------------------//
            case "EXERCISES":
            //------------------//
            // connect to database
                $mdb2 =& MDB2::connect($this->db_dsn);
                if (PEAR::isError($mdb2)) {
                    throw new Question_Control_Exception($mdb2->getMessage());
                }

                //-- CHAPTERS
                $query = 'SELECT name,section,title FROM toc_1 WHERE section="chapter"';
                $res = & $mdb2->query($query);
                if (PEAR::isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $mdb2->disconnect();

                $chapter = $res->fetchAll();

                $str = '<ul id="navlist"><li id="title">Chapter:</li>';
                for ($n=0;$n<count($chapter);$n++) {
                    if (($this->chapter_number-1)==$n) { // SELECTED
                        $str = $str.'<li name="updateConcept_active" id="updateConcept_active" class="active">'.$chapter[$n][0].'</li>';
                    }
                    else { // NORMAL
                        $str = $str.'<li name="updateConcept_active" id="updateConcept_active" onClick="ITS_content_select(this)">'.$chapter[$n][0].'</li>'; //onClick="ITS_changeConcepts(this)"
                    }
                }

                //-- ALL CHAPTERS
                $str = $str.'<li id="break">&nbsp;</li>'
                        .'<li name="updateConcept_active" id="updateConcept_active" onClick="ITS_content_select(this)">ALL</li>'
                        .'<li id="break">&nbsp;</li>';

                //-- APPENDIX
                $query = 'SELECT name,section,title FROM toc_1 WHERE section="appendix"';
                $res = & $mdb2->query($query);
                if (PEAR::isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $mdb2->disconnect();

                $appendix = $res->fetchAll();
                for ($n=0;$n<count($appendix);$n++) {
                    if (($this->chapter_number-1)==$n) { // SELECTED
                        $str = $str.'<li name="updateConcept_active" id="updateConcept_active" class="active">'.$appendix[$n][0].'</li>';
                    }
                    else { // NORMAL
                        $str = $str.'<li name="updateConcept_active" id="updateConcept_active" onClick="ITS_content_select(this)">'.$appendix[$n][0].'</li>'; //onClick="ITS_changeConcepts(this)"
                    }
                }
                //-- Index
                $str = $str.'<li id="break">&nbsp;</li>'
                        .'<li name="updateConcept_active" id="updateConcept_active" onClick="ITS_content_select(this)">Index</li>';
                break;
            //------------------//
        }
        $active_header = $str;

        return $active_header;
    }
    //=====================================================================//
    function getContent() {
        //=====================================================================//
        // echo ' -- in getContent :'.$this->mode.' '.$this->screen; // die();

        switch ($this->screen) {
            //------------------//
            case 1: // "LABS"
            //------------------//
                $content_str = $this->labsContent();
                break;
            //------------------//
            case 2: // "EXERCISES"
            //------------------//
                $content_str = $this->getConcepts();
                break;
            //------------------//
            case 3: // "CONCEPTS"
            //------------------//
                $content_str = $this->exercisesContent();
                break;
            //------------------//
            case 4: // "FREE"
            //------------------//
            //echo 'FREE: '.$this->chapter_number;
                $this->question_completed = false;
                //echo $this->mode.' -- '.$this->chapter_number;
                //$content_str = 'ch';
                $content_str = $this->getChapter($this->mode,$this->chapter_number);
            //------------------//
        }
        $_SESSION['screen'] = $this;
        return $content_str;
    }
    //*********************************************************************//
    //                    L A B     																			 //
    //*********************************************************************//
    //=====================================================================//
    function lab_check($lab_active) {
        //=====================================================================//
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        if ($lab_active > 13) {
            $lab_active = $lab_active - 13;
        }

        // lab completed?
        $la = sprintf("%02d",$lab_active);
        $query = 'SELECT '.$this->lab_tag.$la.' FROM users WHERE id='.$this->id;
        //die($query);
        $res = & $mdb2->query($query);
        if (PEAR::isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }

        $mdb2->disconnect();

        $completed = $res->fetchRow();

        $str = '';
        if (!empty($completed[0])) {
            $this->lab_completed = TRUE;

            $str = '<p><center><DIV class="ITS_MESSAGE">'
                  .'<p>You have already completed <b>'.$this->lab_title.$lab_active.'</b>.<p>'
                  .'</DIV></center>';
        }
        return $str;
    }
    //=====================================================================//
    function updateLab($lab_active,$lab_index) {
        //=====================================================================//
        //echo ($lab_active.' | '.$lab_index).'##<p>';

        if ($lab_active > 14) {
            $lab_tag    = 'survey';
            ///$lab_active = $this->lab_active - 13;
        }
        else {
            $lab_tag    = 'lab';
        }

        //$this->lab_active = $lab_active;
        $this->lab_tag    = $lab_tag;
        $this->lab_index  = $lab_index;
        $content_str      = $this->labsContent();

        return $content_str;
    }
    //=====================================================================//
    function newChapter($chapter_number,$mode) {
        //=====================================================================//
        // echo '<p>^^^'.$chapter_number.'^^^<p>';// die();
        $this->chapter_number = $chapter_number;
        $this->mode					 = $mode;
        $content_str          = $this->getContent();

        return $content_str;
    }
    //=====================================================================//
    function freeContent() {
        //=====================================================================//
        /*  IF ACTIVE:
	|	  		         IF NOT COMPLETED 
									 				   IF IN INDEX -> FORM ( QUESTION )
													   ELSE        -> MESSAGE "thanks, completed"
	|   						 ELSE      -> MESSAGE "completed"
	|		ELSE INACTIVE:
	|    						 STATS ( QUESTION )   
	*----------------------------------------------------------*/
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        //echo $this->lab_active.'**<p>';
        $chapter_active    = $this->chapter_active;
        $la = sprintf("%02d",$chapter_active);
        $query = 'SELECT question_id,qtype,answers FROM activity,webct WHERE term="'.$this->term.'" AND name="'.$this->lab_tag.$la.'" AND activity.question_id=webct.id';
        //die($query);echo $query.'--1<p>';

        $res =& $mdb2->query($query);
        if (PEAR::isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $ques = $res->fetchAll();

        $this->chapter_questions = $ques;
        //----***--------//

        //echo $this->lab_index.' | '. count($ques).'<p>';
        //var_dump($ques);

        $ACTIVE = TRUE; //($this->lab_number == $this->lab_active);
        //echo 'ACTIVE: '. $ACTIVE.'<p>';

        //--------- ACTIVE -------------//
        if ($ACTIVE) {// echo 'ACTIVE';
            //$str = $this->lab_check($this->lab_active);
            $str = FALSE;
            // NOT COMPLETED
            if ($str) {		 //!$this->lab_completed
                //echo ' - NOT COMPLETED';
                if ($this->lab_index <= $res->rowCount() ) {   // section questions available
                    $qid   = $ques[$this->lab_index-1][0];
                    $qtype = $ques[$this->lab_index-1][1];
                    //echo $qid.' - '.$qtype.'<p>';
                    $form   = '<form action="javascript:ITS_lab_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.',\''.$qtype.'\');" name="ITS_SubmitForm" id="ITS_SubmitForm">';
                    $submit = '<div class="navContainer">'
                            .'<input type="submit" class="javascript:ITS_submit" name="submit" value="Submit---">'
                            .'</div>'
                            .'</form>';
                    //$index = $this->getLabIndex();
                    $question = $this->getLabQuestion($this->lab_active,$this->lab_index);
                    //-- TAGS -------------------------------//
                    $tagObj = new ITS_tagInterface();
                    $tags   = $tagObj->displayTags($this->id,$qid,$tagObj->getTags($qid));
                    //
                    //echo $tags; die('were');
                    //-- RATING -----------------------------//
                    $rateObj = new ITS_rating();
                    $rating  = $rateObj->renderRating();
                    //---------------------------------------//
                    //$question = $this->getQuestion($this->lab_nav);
                    //$navigation_str = $this->getNavigation();
                    /*
                $answer = $form.$question
								         .'<div id="errorContainer" class="ITS_message"></div>'
												 .'<div id="userContainer" class="userContainer">'
												 .'<div id="tagContainer" class="tagContainer">'.$tags.'</div>'
												 .'<div id="ratingContainer" class="ratingContainer">'.$rating.'</div>'
												 .'<div id="answerContainer">'.$submit.'</div>'
												 .'</div></form>';		
                    */
                    $answer = $form.$question
                            .'<div id="errorContainer" class="ITS_message"></div>'
                            .'<div id="tagContainer" class="tagContainer">'.$tags.'</div>'
                            .'<div id="ratingContainer" class="ratingContainer">'.$rating.'</div>'
                            .$submit.'</form>';


                    //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                    //$this->_question_type[$name] = $Q->Q_type;
                    //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
                    $str = $answer;
                } else {
                    // finished lab
                    $time = date("m-d-y, g:i a");
                    $query_str = "UPDATE users SET ".$this->lab_tag.$la."='".$time."' WHERE id=".$this->id; //echo "<p>".$query_str."<p>";die();
                    echo '<p>'.$query_str.'<p>';
                    //die('DIED HERE');
                    $res_time =& $mdb2->query($query_str);
                    if (PEAR::isError($res_time)) {
                        throw new Grading_Exception($res->getMessage());
                    }

                    $this->lab_completed = true;

                    $msg = '<center><DIV class="ITS_MESSAGE" style="background:LemonChiffon">'
                            .'<p>Thanks, your <b>'.ucfirst($this->lab_tag).'</b> answers have been recorded on <font color="purple">'.$time.'</font>'
                            .'</DIV></center>';

                    $this->lab_index = 1;
                    $index = $this->getLabIndex();
                    $qid   = $ques[$this->lab_index-1][0];
                    $qtype = $ques[$this->lab_index-1][1];
                    $question = $this->getLabQuestion($this->lab_active,$this->lab_index);

                    $query = 'SELECT answered from stats_'.$this->id.' WHERE question_id='.$qid;
                    $res   =& $mdb2->query($query);
                    if (PEAR::isError($res)) {
                        throw new Question_Control_Exception($res->getMessage());
                    }
                    $answer = $res->fetchCol(); //var_dump($answer);

                    if (empty($answer)) {
                        $answered = '';
                    }
                    else {
                        $answered = $answer[0];
                    }

                    //$ans = $this->getUserAnswer($qid,$qtype,$answered);
                    $ans = getAnswer($qid,$qtype,$answered,0);
                    //die($ans);
                    $navigation_str = $this->getNavigation($ans);

                    //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                    //$this->_question_type[$name] = $Q->Q_type;
                    //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
                    $str = $msg.$index.$question.$navigation_str;
                }
                // COMPLETED
            }	else { //echo ' - COMPLETED';
                $qid   = $ques[$this->lab_index-1][0];
                $qtype = $ques[$this->lab_index-1][1];
                $index    = $this->getLabIndex();
                $question = $this->getLabQuestion($this->lab_active,$this->lab_index);

                $query = 'SELECT answered from stats_'.$this->id.' WHERE question_id='.$qid;
                $res   =& $mdb2->query($query);
                if (PEAR::isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $answer = $res->fetchCol(); //var_dump($answer);

                if (empty($answer)) {
                    $answered = '';
                }
                else {
                    $answered = $answer[0];
                }

                $ans = $this->getUserAnswer($qid,$qtype,$answered);
                //die($ans);
                $navigation_str = $this->getNavigation($ans);

                //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                //$this->_question_type[$name] = $Q->Q_type;
                //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
                $str = $index.$question.$navigation_str;
            }
        }
        //--------- INACTIVE -------------//
        else {//echo 'INACTIVE ';
            if ($this->lab_index > count($ques)) {
                $this->lab_index = count($ques);
            }
            $qid   = $ques[$this->lab_index-1][0];
            $qtype = $ques[$this->lab_index-1][1];
            $index = $this->getLabIndex();
            $question = $this->getLabQuestion($this->lab_active,$this->lab_index);

            $query = 'SELECT answered from stats_'.$this->id.' WHERE question_id='.$qid;
            //echo $query;
            $res   =& $mdb2->query($query);
            if (PEAR::isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $answer = $res->fetchCol(); //var_dump($answer);

            if (empty($answer)) {
                $answered = '';
            }
            else {
                $answered = $answer[0];
            }

            $ans = $this->getAnswer($qid,$qtype,$answered);
            $navigation_str = $this->getNavigation($ans);

            //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
            //$this->_question_type[$name] = $Q->Q_type;
            //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
            $str = $index.$question.$navigation_str;
        }
        //$navigation_str = $this->getNavigation();
        //-------------------------------//
        return $str;
    }
    //=====================================================================//
    function labsContent() {
        //=====================================================================//
        /*  IF ACTIVE:
	|	  		         IF NOT COMPLETED 
									 				   IF IN INDEX -> FORM ( QUESTION )
													   ELSE        -> MESSAGE "thanks, completed"
	|   						 ELSE      -> MESSAGE "completed"
	|		ELSE INACTIVE:
	|    						 STATS ( QUESTION )   
	*----------------------------------------------------------*/
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }
        /*--SURVEY--*/
        if ($this->lab_active > 14) {
            $lab_active = $this->lab_active - 14;
        }
        else {
            $lab_active = $this->lab_active;
        }

        //echo $this->lab_active.'**<p>';
        //$lab_active    = $this->lab_active;
        $this->term = 'Spring_2010';
        $la = '01'; //sprintf("%02d",$lab_active);
        $this->lab_tag = 'survey';
        $query = 'SELECT question_id,qtype,answers FROM activity,webct WHERE term="'.$this->term.'" AND name="'.$this->lab_tag.$la.'" AND activity.question_id=webct.id';
        // die($query);echo $query.'--1<p>';

        $res =& $mdb2->query($query);
        if (PEAR::isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $ques = $res->fetchAll();

        $this->lab_questions = $ques;
        //----***--------//

        //echo $this->lab_index.' | '. count($ques).'<p>';
        //var_dump($ques);

        $ACTIVE = ($this->lab_number == $this->lab_active);
        //echo 'ACTIVE: '. $ACTIVE.'<p>';

        //--------- ACTIVE -------------//
        if ($ACTIVE) {// echo 'ACTIVE';
            $str = $this->lab_check($this->lab_active);

            // NOT COMPLETED
            if (!$this->lab_completed) {
                //echo ' - NOT COMPLETED';
                if ($this->lab_index <= $res->rowCount() ) {   // section questions available
                    $qid   = $ques[$this->lab_index-1][0];
                    $qtype = $ques[$this->lab_index-1][1];
                    //echo $qid.' - '.$qtype.'<p>';
                    $form   = '<form action="javascript:ITS_lab_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.',\''.$qtype.'\');" name="ITS_SubmitForm" id="ITS_SubmitForm">';
                    $submit = '<div class="navContainer">'
                            .'<input type="submit" class="ITS_submit" name="submit" value="Submitx">'
                            .'</div>'
                            .'</form>';
                    //$index = $this->getLabIndex();
                    $question = $this->getLabQuestion($this->lab_active,$this->lab_index);
                    //-- TAGS -------------------------------//
                    /*
                    $tagObj = new ITS_tagInterface();
                    $tags   = $tagObj->displayTags($this->id,$qid,$tagObj->getTags($qid));
                    //
                    echo '<hr><hr>';
                    echo $tags; die();
                    * */
                    //-- RATING -----------------------------//
                    $rateObj = new ITS_rating();
                    $rating  = $rateObj->renderRating();
                    //---------------------------------------//
                    //$question = $this->getQuestion($this->lab_nav);
                    //$navigation_str = $this->getNavigation();
                    /*
                $answer = $form.$question
								         .'<div id="errorContainer" class="ITS_message"></div>'
												 .'<div id="userContainer" class="userContainer">'
												 .'<div id="tagContainer" class="tagContainer">'.$tags.'</div>'
												 .'<div id="ratingContainer" class="ratingContainer">'.$rating.'</div>'
												 .'<div id="answerContainer">'.$submit.'</div>'
												 .'</div></form>';		
                    */
                    $answer = $form.$question
                            .'<div id="errorContainer" class="ITS_message"></div>'
                            .'<div id="tagContainer" class="tagContainer">'.$tags.'</div>'
                            .'<div id="ratingContainer" class="ratingContainer">'.$rating.'</div>'
                            .$submit.'</form>';


                    //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                    //$this->_question_type[$name] = $Q->Q_type;
                    //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
                    $str = $answer;
                } else {
                    // finished lab
                    $time = date("m-d-y, g:i a");
                    $query_str = "UPDATE users SET ".$this->lab_tag.$la."='".$time."' WHERE id=".$this->id; //echo "<p>".$query_str."<p>";die();
                    //echo '<p>'.$query_str.'<p>';

                    $res_time =& $mdb2->query($query_str);
                    if (PEAR::isError($res_time)) {
                        throw new Grading_Exception($res->getMessage());
                    }

                    $this->lab_completed = true;

                    $msg = '<center><DIV class="ITS_MESSAGE" style="background:LemonChiffon">'
                            .'<p>Thanks, your <b>'.ucfirst($this->lab_tag).'</b> answers have been recorded on <font color="purple">'.$time.'</font>'
                            .'</DIV></center>';

                    $this->lab_index = 1;
                    $index = $this->getLabIndex();
                    $qid   = $ques[$this->lab_index-1][0];
                    $qtype = $ques[$this->lab_index-1][1];
                    $question = $this->getLabQuestion($this->lab_active,$this->lab_index);

                    $query = 'SELECT answered from stats_'.$this->id.' WHERE question_id='.$qid;
                    $res   =& $mdb2->query($query);
                    if (PEAR::isError($res)) {
                        throw new Question_Control_Exception($res->getMessage());
                    }
                    $answer = $res->fetchCol(); //var_dump($answer);

                    if (empty($answer)) {
                        $answered = '';
                    }
                    else {
                        $answered = $answer[0];
                    }

                    $ans = $this->getUserAnswer($qid,$qtype,$answered);
                    //die($ans);
                    $navigation_str = $this->getNavigation($ans,$qid);

                    //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                    //$this->_question_type[$name] = $Q->Q_type;
                    //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
                    $str = $msg.$index.$question.$navigation_str;
                }
                // COMPLETED
            }	else { //echo ' - COMPLETED';
                $qid   		= $ques[$this->lab_index-1][0];
                $qtype 		= $ques[$this->lab_index-1][1];
                $index    = $this->getLabIndex();
                $question = $this->getLabQuestion($this->lab_active,$this->lab_index);

                $query = 'SELECT answered from stats_'.$this->id.' WHERE question_id='.$qid;
                $res   =& $mdb2->query($query);
                if (PEAR::isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $answer = $res->fetchCol(); //var_dump($answer);

                if (empty($answer)) {
                    $answered = '';
                }
                else {
                    $answered = $answer[0];
                }

                //$ans = $this->getUserAnswer($qid,$qtype,$answered);
                $ans = $this->getAnswer($qid,$qtype,$answered,0);
                //die($ans);
                $navigation_str = $this->getNavigation($ans,$qid);

                //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                //$this->_question_type[$name] = $Q->Q_type;
                //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
                $str = $index.$question.$navigation_str;
            }
        }
        //--------- INACTIVE -------------//
        else {//echo 'INACTIVE ';
            if ($this->lab_index > count($ques)) {
                $this->lab_index = count($ques);
            }
            $qid   = $ques[$this->lab_index-1][0];
            $qtype = $ques[$this->lab_index-1][1];
            $index = $this->getLabIndex();
            $question = $this->getLabQuestion($this->lab_active,$this->lab_index);

            $query = 'SELECT answered from stats_'.$this->id.' WHERE question_id='.$qid;
            //echo $query;
            $res   =& $mdb2->query($query);
            if (PEAR::isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $answer = $res->fetchCol(); //var_dump($answer);

            if (empty($answer)) {
                $answered = '';
            }
            else {
                $answered = $answer[0];
            }

            $ans = $this->getAnswer($qid,$qtype,$answered);
            $navigation_str = $this->getNavigation($ans,$qid);

            //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
            //$this->_question_type[$name] = $Q->Q_type;
            //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
            $str = $index.$question.$navigation_str;
        }
        //$navigation_str = $this->getNavigation();
        //-------------------------------//
        return $str;
    }
    //=====================================================================//
    function getLabIndex() {
        //=====================================================================//
        //--- build index list ---//
        $strIdx = '<ul id="navlistIdx">';
        for ($n=0;$n<count($this->lab_questions);$n++) {
            if (($this->lab_index-1)==$n) { // SELECTED
                $strIdx = $strIdx.'<li name="updateLab_index" id="updateLab_index" class="active">'.($n+1).'</li>';
            }
            else { // NORMAL
                $strIdx = $strIdx.'<li name="updateLab_index" id="updateLab_index" onClick="ITS_content_nav(this,this.innerHTML)">'.($n+1).'</li>';
            }
        }
        $strIdx = $strIdx.'</ul>';
        $strIdx = '<div id="ITS_screen_index">'.$strIdx.'</div>';

        return $strIdx;
    }
    //=====================================================================//
    function getLabQuestion($lab_num,$lab_idx) {
        //=====================================================================//
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        if ($this->lab_active > 13) {
            $lab_active = $this->lab_active - 13;
        }
        else {
            $lab_active = $this->lab_active;
        }

        $la = sprintf("%02d",$lab_active);
        $query = 'SELECT question_id FROM activity WHERE term="'.$this->term.'" AND name="'.$this->lab_tag.$la.'"';
        //$query = 'SELECT question_id,qtype FROM activity,webct WHERE term="'.$this->term.'" AND name="'.$this->lab_tag.$this->lab_active.'" AND activity.question_id=webct.id';

        $res =& $mdb2->query($query);
        if (PEAR::isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $mdb2->disconnect();

        $ques = $res->fetchCol();
        $question = $this->getQuestion($ques[($lab_idx-1)],'');
        //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;

        return $question;
    }
    //=====================================================================//
    function getQuestion($Qnum,$conf) {
        //=====================================================================//
        // $conf:
        /*** BACK-TRACE ***//*
	 echo '<table class="ITS_backtrace">';	
	 array_walk( debug_backtrace(), create_function( '$a,$b', 'print "<tr><td><font color=\"blue\">". basename( $a[\'file\'] ). "</b></font></td><td><font color=\"red\">{$a[\'line\']}</font></td><td><font color=\"green\">{$a[\'function\']}()</font></td><td>". dirname( $a[\'file\'] ). "/</td></tr>";' ) ); 	
	 echo '</table>';	
	 /******************/
        //DEBUG: echo '<p>-in getQuestion-<p>'.$Qnum;
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        //echo '<p>++'.$Qnum; //die();
        $Q = new ITS_question($this->id,$this->db_name,$this->tb_name);
        $Q->load_DATA_from_DB($Qnum);                 //$this->lab_questions[$n]
        $question = $Q->render_QUESTION_check($conf);
        $Q->get_ANSWERS_data_from_DB();

        //$Q->get_ANSWERS_solution_from_DB();

        // ANSWERS
        //$name = $this->lab_name."[".($Qnum)."]"; //[".($an)."];
        //$name = "[".($Qnum)."]"; //[".($an)."];
        $name = 'ITS_'.$Qnum;
        //echo '<p> *********'.$name.'<p>';
        //echo "<center>".$Q->render_ANSWERS($name)."</center>";

        $mode = 0;  // mode inactive

        switch ($this->screen) {
            /*---------------*/
            case 1: // LAB
            /*---------------*/
                if ($this->lab_number == $this->lab_active) {
                    if (!$this->lab_completed) {
                        $mode = 1;
                    }
                }
                break;
            /*---------------*/
            case 4:
            /*---------------*/
                if (!$this->question_completed) {
                    $mode = 1;
                } else {
                    $Q->Q_answers_permutation = $this->question_info;
                }
                break;
            /*---------------*/
        }
        //echo 'this: '.$mode;
        //die('this: '.$mode);
        //die($this->lab_number.' '.$this->lab_active);
        /*
	 if (strtoupper($Q->Q_type) == 'M') {
	 
    	 $inst = '&nbsp;<p><span id="matchingInstruction" class="instr">&diams; instruction</span><p>'.
               '<div id="dialog" title="Matching Problems">'.
        	     '<img src="instr/matching_example2.png" alt="instr/matching_example2.png" style="border: 2px solid #999">'.
               '</div>';
	 } else {
	     $inst = '';
	 }*/

        $inst = '';
        $question = '<div id="ITS_instruction">'.$inst.'</div>'.
                '<div id="ITS_QUESTION">'.$question.'</div>'.
                '<div id="ITS_ANSWER">'.$Q->render_ANSWERS($name,$mode).'</div>';
        //echo 'pperm: '.implode($Q->question_info);

        // DEGUG *****************************************************
        //echo '----<p>ITS_screen2::getQuestion *mode='.$mode.' - *perm=';
        //var_dump($Q->Q_answers_permutation);
        //echo 'end of perm<p>-----';
        // DEGUG *****************************************************
        $this->question_info = $Q->Q_answers_permutation;
        $mdb2->disconnect();

        return $question;
    }
    //=====================================================================//
    function updateConcept($chapter_active) {
        //=====================================================================//
        $this->chapter_active = $chapter_active;
        $content_str          = $this->getConcepts();

        return $content_str;
    }
    //=====================================================================//
    function getConcepts() {
        //=====================================================================//
        global $mimetex_path;

        $str = '';  //echo '-'.$this->chapter_active.'<p>';
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        $show_title    = TRUE;
        $show_concepts = TRUE;
        switch ($this->chapter_active) {
            /*---------------*/
            case 'ALL':
            /*---------------*/
                $show_concepts = FALSE;
                $ch_str = 'section="chapter"';
                $query_header = 'SELECT name,section,title FROM toc_1 WHERE '.$ch_str;
                $query = 'SELECT name,pages,chapter_id FROM index_1 ORDER BY name';
                break;
            /*---------------*/
            case 'Index':
            /*---------------*/
                $show_title = FALSE;
                // build ALPHABETICAL list
                //$active = 'D'; //chr(64+rand(1,32));die(rand(1,32));
                $active = $this->chapter_alpha;

                $alph = '<br><ul id="navlistIdx" class="alphIndex">';
                foreach (range('A','Z') as $chara) {
                    if ($chara==$active) {
                        $act = 'class="active"';
                    }
                    else {
                        $act = '';
                    }
                    $alph = $alph.'<li name="updateConcept_alpha" id="updateConcept_alpha" '.$act.' onClick="ITS_content_select(this)">'.$chara.'</li>';
                }
                $alph = $alph.'</ul>';
                $str = $str.'<div id="ITS_alph_index">'.$alph.'</div>';
                //die('<div id="ITS_alph_index">'.$alph.'</div>');

                $query = 'SELECT name,pages,chapter_id FROM index_1 WHERE name LIKE "'.$active.'%"';
                break;
            /*---------------*/
            default:
            /*---------------*/
                $ch_str = 'name="'.$this->chapter_active.'"';
                $query_header = 'SELECT name,section,title FROM toc_1 WHERE '.$ch_str;
                $query = 'SELECT name,pages,chapter_id FROM index_1 WHERE chapter_id="'.$this->chapter_active.'" ORDER BY name';
        }

        // TITLE
        if ($show_title) {
            $res =& $mdb2->query($query_header);
            if (PEAR::isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $TOC = $res->fetchAll();

            for ($ch=0;$ch < count($TOC);$ch++ ) {
                $concepts = '<span class="ITS_figs" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'showConcepts\','.$TOC[$ch][0].',ITS_screen,\'contentContainer\')">concepts</span>';
                $figs = '<span class="ITS_figs" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'showFigures\','.$TOC[$ch][0].',ITS_screen,\'contentContainer\')">figures</span>';
                $str  = $str.'<div name="link" class="ITS_chapter"><span class="nhidden" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'getChapter\',\''.($TOC[$ch][0]).'\',ITS_screen,\'contentContainer\')"><font color="blue">'.$TOC[$ch][1].'&nbsp;'.$TOC[$ch][0].'</font>: <font color="#999">'.$TOC[$ch][2].'</font></span>'.$figs.$concepts.'</div>';
                //$str  = $str.'<div name="link" class="ITS_chapter" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'getChapter\',\''.($TOC[$ch][0]).'\',ITS_screen,\'contentContainer\')"><font color="blue">'.$TOC[$ch][1].'&nbsp;'.$TOC[$ch][0].'</font>: <font color="#999">'.$TOC[$ch][2].'</font>'.$figs.'</div>';
            }
        }
        // CONCEPTS
        if ($show_concepts) {
            $res =& $mdb2->query($query);
            if (PEAR::isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $concepts = $res->fetchAll();
            //----***--------//
            if (count($concepts)) {
                $width = array(0.5,0.5);
                $class = "ITS_index";

                // MATRIX
                $Ncols = 2;
                $Nrows = ceil(count($concepts)/$Ncols);
                $str = $str.'<div><table class='.$class.'>';

                //if (is_null($concepts[$c][1])) { $equa = '';      }
                //else                  { $equa = '<img src="'.$mimetex_path.$concepts[$c][1].'">'; }
                $pg = '';
                for ($r=0;$r < $Nrows;$r++ ) {
                    $str = $str.'<tr>';
                    for ($c=0;$c < $Ncols;$c++) {
                        if (($r+$c*$Nrows) < count($concepts)) {
                            $pages = $concepts[$r+$c*$Nrows][1];
                            if (!empty($pages)) {
                                $pgs = explode(",",$pages);
                                $N = count($pgs);
                                $pg = '';
                                for ($pi=$N; $pi>0; $pi--) {
                                    $pg = $pg.'<span class="hidden" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'showPage\','.$pgs[$pi-1].',ITS_screen,\'contentContainer\')">'.trim($pgs[$pi-1]).'</span>';
                                }
                            }
                            $str = $str.'<td class="'.$class.'"><span class="nhidden" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'getIndex\',\''.$concepts[$r+$c*$Nrows][0].'\',ITS_screen,\'contentContainer\')">&bull;&nbsp;'.$concepts[$r+$c*$Nrows][0].'</span>'.$pg.'</td>'; //<span class="hidden">'.$concepts[$r+$c*$Nrows][2].'</span>
                        }
                    }
                    $str = $str.'</td>';
                }
                $str = $str.'</table></div>';
            }
        }
        $mdb2->disconnect();
        /*----------------------------------------------*/
        /*
		$col = 0;
		$str = $str.'<ul class='.$class.'>';
		for ($c=0;$c < count($concepts);$c++ ) {		
      $str = $str.'<li>'.$concepts[$c][0].'</li>';
		}
		$str = $str.'</ul>';
        */
        /*----------------------------------------------*/
        // TABLE
        /*
		$str = '<table class='.$class.'>';
		foreach ($concepts as $idx){
		  $name = str_replace(":"," ",$idx[0]);
			if (is_null($idx[1])) { $equa = '';      }
			else                  { $equa = '<img src="'.$mimetex_path.$idx[1].'">'; }
			
			$str = $str.'<tr><td class='.$class.'>'.$name.'</td><td class='.$class.'>'.$equa.'</td></tr>';
			//$tb = new ITS_table('tb_Cidx',1,2,array($name,$equa),array(50,50),'ITS_feedback_list');
      //$str = $str.$tb->str.'<p>';
		}
		$str = $str.'</table>';
        */
        return $str;
    }
    //=====================================================================//
    function showPage($pg) {
        //=====================================================================//
        if ($this->role == 'admin') {
            $pg_path = 'SPFIRST/SPFirstPages/SP1_Page_'.str_pad($pg,3,"0",STR_PAD_LEFT).'.png';
            $str = '<img src="'.$pg_path.'">';
        }
        else {
            $pg_path = 'images/dspfirst.jpg';
            $str = '<p style="margin:2em;">'
                    .'<img src="'.$pg_path.'" style="padding:0.5em;border:2px solid #aaa">'
                    .'<p><a href="index.php" style="color:#777;border:1px solid #777;padding:0.5em 1em;margin:1em;text-decoration:none;">back</a><p>&nbsp;';
        }
        return $str;
    }
    //=====================================================================//
    function showFigures($ch) {
        //=====================================================================//
        if (stristr(PHP_OS, 'WIN')) {
            $fig_path = 'SPFIRST\\SP1Figures\\Ch'.str_pad($ch,2,"0",STR_PAD_LEFT).'\\';
            $cmd = 'dir '.$fig_path.'sm* /b';
            $fig_path = str_replace('\\','/',$fig_path);
        } else {
            $fig_path = 'SPFIRST/SP1Figures/Ch'.str_pad($ch,2,"0",STR_PAD_LEFT).'/';
            $cmd = 'ls '.$fig_path.'sm*';
            $fig_path = '';
        }
        exec($cmd,$figures);

        //---- DIV METHOD ----//
        ///*
        $str = '';
        for ($im=0;$im < count($figures);$im++ ) {
            $str = $str.'<div class="thumbnail"><img src="'.$fig_path.$figures[$im].'" onclick="ITS_showImage(this)"></div>';
        }//*/
        //-------------------//

        // FIGURES
        $str = '';
        if (count($figures)) {
            $width = array(0.25,0.25,0.25,0.25);
            $class = "ITS_index";

            // MATRIX
            $Ncols = 4;
            $Nrows = ceil(count($figures)/$Ncols);
            //echo $Ncols.' x '.$Nrows.'<p>';die();
            $str = $str.'<div><table class='.$class.'>';

            //if (is_null($concepts[$c][1])) { $equa = '';      }
            //else                  { $equa = '<img src="'.$mimetex_path.$concepts[$c][1].'">'; }
            $pg = '';
            for ($r=0;$r < $Nrows;$r++ ) {
                $str = $str.'<tr>';
                for ($c=0;$c < $Ncols;$c++) {
                    if (($r+$c*$Nrows) < count($figures)) {//
                        $str = $str.'<td><div class="ITS_figshow"><img src="'.$fig_path.$figures[$r+$c*$Nrows].'" onclick="ITS_showImage(this)"></div>';
                    }
                }
                $str = $str.'</td>';
            }
            $str = $str.'</table></div>';
        }
        //$str = '<img src="'.$pg_path.'">';
        /*
	   $str = '<p style="margin:2em;">'
		       .'<img src="'.$pg_path.'" style="padding:0.5em;border:2px solid #aaa">'
					 .'<a href="index.php" style="color:#777;border:1px solid #777;padding:0.5em 1em;margin:1em;text-decoration:none;">back</a><p>&nbsp;';
        */
        return $str;
    }
    //=====================================================================//
    function getNavigation($nav_content) {
        //=====================================================================//
        // ITS_debug(array($mode,$this->screen));
        // echo 'getNavigation( ,'.$qid.') : ';//.$this->screen; die();
        $lab_index = $this->lab_index;

        $button_onClick  = '';
        $button_content  = '!';

        //$nav = '<img src="phpimg/ITS_button.php?o=f"'
        $next_icon = '&gt;';  // Next
        $prev_icon = '&lt;';  // Previous
        //echo isset($this->screen);
        //echo '<p><hr>'.$this->screen.'+++'; 
        //echo("screen".$this->screen);

        switch ($this->screen) {
            //-------------------------------//
            case 1: // LAB screen
            //-------------------------------//
            //die('<p>'.$this->lab_number.' | '.$this->lab_active);
                $ACTIVE = ($this->lab_number == $this->lab_active);

                // BUTTONS: PREVIOUS | CONTENT | NEXT
                $buttons = array(FALSE,FALSE,FALSE);

                // PREVIOUS ("<") ?
                if ($this->lab_index > 1) {
                    $buttons[0] = TRUE;
                };

                // BUTTONS
                $state = $this->lab_number.','.($lab_index-1);
                $button_onClick = "onClick=ITS_content_nav(this,".($this->lab_index-1).")";
                $prev = $button_onClick.'>'.$prev_icon;

                // CONTENT: nothing | user_stats | submit button
                if ($ACTIVE) {
                    $buttons[1] = TRUE;
                };

                // NEXT (">") ?
                if ($this->lab_index < count($this->lab_questions)) {
                    $buttons[2] = TRUE;
                };

                $state = $this->lab_active.','.($lab_index+1);
                $button_onClick = "onClick=ITS_content_nav(this,".($this->lab_index+1).")";
                $next_str = $button_onClick.$next_icon;

                $container = "'getContent'";

                break;
            //-------------------------------//
            case 2:  // EXERCISES screen
            //-------------------------------//
                $container = "'getChapter'";
                $data      = "'".$this->chapter."'"; //"\'".$this->chapter."\'";

                if ($this->lab_index == count($this->lab_questions)) {
                    $next   =  ''; //'&nbsp;</div>';
                    //$next   =  $style1.$data.$style2.$next_icon.'</div>';

                    // SUBMIT BUTTON
                    $submit = '<div class="center">'
                            .'<input type="submit" style="color:darkgreen;font-weight:bold" name="submit" value="Submit Lab #'.$this->lab_active.'">'
                            .'</div>'
                            .'</form>';
                } else {
                    $next  = $style1.$data.$style2.$next_icon.'</div>';
                }
                break;
            //-------------------------------//
            case 3:
            //-------------------------------//
                $container = "'getContent'";
                break;
            //-------------------------------//
            case 4:
            //-------------------------------//
            //echo '<b>'.$this->mode.' and '.$nav_content.'<p>';

                switch ($this->mode) {
                    /********************/
                    case 'index':
                    case 'practice':
                    case 'question':
                    //case
                    /********************/
                    // BUTTONS: PREVIOUS | CONTENT | NEXT
                        $buttons   = array(FALSE,TRUE,TRUE);
                        $container = "'getContent'";
                        $button_onClick = ''; //"onClick=ITS_question_next()";
                        $next_str = 'name="update_index" class="ITS_navigation" mode="'.$this->mode.'" '.$button_onClick;

                        //--- rating ---//
                        $rateObj  = new ITS_rating();
                        $rating   = $rateObj->renderRating('');
                        $rateBox  = '<div id="ratingContainer" style="float:right">'.$rating.'</div>';
                        $nav_content .= $rateBox;
                        //--------------//
                        break;
                    /********************/
                    case 'review':
                    /********************/
                    // echo '<p>'.$nav_content[0].' - '.$nav_content[1].'<p>';
                    //--- BUTTONS: PREVIOUS | CONTENT | NEXT ---//
                        $idx = $nav_content[0]+1;
                        if ($idx < 2) {
                            $P = FALSE;
                        }
                        else {
                            $P = TRUE;
                        }
                        if ($idx == $nav_content[1]) {
                            $N = FALSE;
                        }
                        else {
                            $N = TRUE;
                        }
                        $buttons = array($P,TRUE,$N);
                        //---------------------------------------------//
                        $nav_content = $nav_content[2];
                        $button_onClickP = '';//"onClick=ITS_review_next(-1)";
                        $prev_str = 'name="updateReview_index" ch="'.$this->chapter_number.'" del="-1" class="ITS_navigation"';
                        $container = "'reviewContainer'";
                        $next_str = 'name="updateReview_index" ch="'.$this->chapter_number.'" del="1" class="ITS_navigation"';
                        break;
                    /********************/
                    case 'surveyX':
                    /********************/
                    // BUTTONS: PREVIOUS | CONTENT | NEXT
                        $buttons   = array(FALSE,TRUE,TRUE);
                        $container = "'getContent'";
                        $button_onClick = ''; //"onClick=ITS_question_next()";
                        $next_str = 'name="updateLab_index" class="ITS_navigation" id="ITS_next" '.$button_onClick.'>'.$next_icon;
                        break;
                    /********************/
                    case 'survey':
                    /********************/
                    //echo '<p>'.$nav_content[0].' - '.$nav_content[1].'<p>';
                    //--- BUTTONS: PREVIOUS | CONTENT | NEXT ---//
                        $idx = $nav_content[0]+1;
                        if ($idx < 2) {
                            $P = FALSE;
                        }
                        else {
                            $P = TRUE;
                        }
                        if ($idx == $nav_content[1]) {
                            $N = FALSE;
                        }
                        else {
                            $N = TRUE;
                        }
                        $buttons = array($P,TRUE,$N);
                        //---------------------------------------------//
                        $nav_content = $nav_content[2];
                        $button_onClickP = '';//"onClick=ITS_review_next(-1)";
                        $prev_str  = 'name="updateReview_index" ch="Survey" del="-1" class="ITS_navigation"';
                        $container = "'reviewContainer'";
                        $next_str  = 'name="updateReview_index" ch="Survey" del="1" class="ITS_navigation"';
                        break;
                }
                break;
        }
        //die($container);

        // Nav style
        $style1 = 'onClick=ITS_AJAX2(\'ITS_screen_AJAX.php\','.$container.',';
        $style2 = ',ITS_screen,\'contentContainer\')>';
        //$style3 = 'onClick=ITS_lab_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.')>';

		//<div name="updateLab_index" class="ITS_navigation"
        $prev = '<div id="ITS_nav_prev" '.$prev_str.'>'.$prev_icon.'</div>';
        $next = '<p><div id="ITS_nav_next" '.$next_str.'>'.$next_icon.'</div>';

        //echo '<p> ###: '.htmlentities($next);//die();
        $width  = array(10,80,10);
        $nav_content = '<div class="navContent">'.$nav_content.'</div>';
        $nav    = array($prev,$nav_content,$next);

        //$navigation = '<div id=navigationContainer></div>'
        /*		$navigation = '<ul id="navQuestion">'
   						    .'<li>'.$prev.'</li>'
   								.'<li class="navContent">'.$nav_content.'</li>'
   								.'<li>'.$next.'</li>'	  
   							.'</ul>';
	 
	 $n = '<div id="navcontainer">'
	 .'<div>'.$prev.'</div>'
	 .'<div id="content">'.$nav_content.'</div>'
	 .'<div>'.$next_icon.'</div>'
	 .'</ul></div>';
        */
        $nav_tb     = new ITS_table('ITS_nav_tb',1,3,$nav,$width,'ITS_screen_nav');
        $navigation = $nav_tb->str; //.'<p><div id="slider"></div>';

        return $navigation;
    }
    //=====================================================================//
    function exercisesContent() {
        //=====================================================================//
        $table = '<font color="red">A</font>';

        // $table = '<font color="royalblue">Complex Numbers</font>';
        // $table = 'APPENDIX <a href="ITS_pre_lab.php?activity=1">'.$table.'</a>: Complex Numbers';
        $table  = '<div name="link" class="ITS_chapter" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'getChapter\',\'A\',ITS_screen,\'contentContainer\')">APPENDIX '.$table.'&nbsp;: Complex Numbers</div>';

        $tb_index = $table;

        /*
    	$Q = new ITS_question($this->id,'its','webct');
    	$Q->load_DATA_from_DB(1);
    	$preview = $Q->render_QUESTION_check();
    	$Q->get_ANSWERS_data_from_DB();
      //$preview = $preview.$Q->render_ANSWERS('a');
    	$preview = '<DIV class="ITS_PREVIEW">'.$preview.'</DIV>';
        */
        $preview = '';
        $tb_labs = new ITS_table('ITS_activity',1,1,array($tb_index),array(100),'ITS_ghost');
        return $tb_labs->str;
    }
    //=====================================================================//
    function recordQuestion($qid,$qtype,$answered,$info,$tstart) {
        //=====================================================================//
        $qTicket = FALSE;
        if (isset($_SESSION['ITSQ_'.$qid]) ) { // OR ticket really old: OR ($tdiff>60)
			$qTicket = TRUE;
			unset($_SESSION['ITSQ_'.$qid]); //var_dump($_SESSION['ITSQ_'.$qid]);
		}
	    if ($qTicket) {
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        $current_chapter = $this->chapter_number;
		switch ($this->mode) {
            case 'survey':
                $scoreArr = array('NULL');
                break;
            case 'practice':
                $current_chapter = -$current_chapter;  // NEGATIVE CHAPTERS
            default:
                $config = implode(",",$info);
                $tr     = new ITS_statistics($this->id,$this->term,$this->role);
               // die('died');
                $scoreArr = $tr->get_question_score($qid,mysql_real_escape_string($answered),$config,$qtype);
              //  die('hiiiiii'.$scoreArr);
                //$scoreArr = 0;
        }
		$dur = time()-$tstart;
		
		switch (strtolower($qtype)) {
            //-------------------------------//
            case 'm':
            //-------------------------------//
            
                $perm_str = implode(',',$info); //$this->_answers_permutation[$name]
				 
				$score = strval($scoreArr[0]); // Was scoreArr
                
                $query_str = 'INSERT IGNORE INTO '.$this->tb_user.$this->id.' (question_id,current_chapter,answered,comment,score,epochtime,duration) VALUES('.$qid.','.$current_chapter.',"'.mysql_real_escape_string($answered).'","'.$perm_str.'",'.$score.','.$tstart.','.$dur.')';
                break;
            //-------------------------------//
            case 'c':
            //-------------------------------//
            
                $score = strval($scoreArr[0]);
                $perm_str = implode(',',$info); //$this->_answers_permutation[$name]
                //$score = $scoreArr; 
                //die('scpre:'.$score);
                $query_str = 'INSERT IGNORE INTO '.$this->tb_user.$this->id.' (question_id,current_chapter,answered,comment,score,epochtime,duration) VALUES('.$qid.','.$current_chapter.',"'.mysql_real_escape_string($answered).'","'.$perm_str.'",'.$score.','.$tstart.','.$dur.')';
                break;
            //-------------------------------//
            default;
            //-------------------------------//
                $score = $scoreArr[0];
                
                $query_str = 'INSERT IGNORE INTO '.$this->tb_user.$this->id.' (question_id,current_chapter,answered,score,epochtime,duration) VALUES('.$qid.','.$current_chapter.',"'.mysql_real_escape_string($answered).'",'.$score.','.$tstart.','.$dur.')';
            //-------------------------------//
        }	
        //OLD
	   //die('died');
		// OLD
		//die('died');
		if (!(is_empty($answered))) {		
			 	
			$query = 'SELECT question_id,answered,epochtime,count(*) FROM '.$this->tb_user.$this->id.' WHERE score IS NOT NULL AND question_id='.$qid.' GROUP BY question_id,answered,epochtime HAVING epochtime BETWEEN '.($tstart-1).' AND '.($tstart+1);
			$res =& $mdb2->query($query);
            if (PEAR::isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $qExists = $res->fetchAll();
            if (empty($qExists)){
            $res = & $mdb2->query($query_str);
            if (PEAR::isError($res)) {
                die('hello');
                throw new Question_Control_Exception($res->getMessage());
                
            }
		    } else { echo '<div class="ITS_error">WARNING: MULTIPLE RECORDS <br>Details: ( '.$qExists[0][0].' | '.$qExists[0][1].' | '.$qExists[0][2].' )</div>'; }
        }
	    } //if:$qTicket
       
    
        $mdb2->disconnect();
       
    }
    //=====================================================================//
    function recordRating($qid,$rating) {
        //=====================================================================//
        if (!empty($rating)) {
            // connect to database
            $mdb2 =& MDB2::connect($this->db_dsn);
            if (PEAR::isError($mdb2)) {
                throw new Question_Control_Exception($mdb2->getMessage());
            }

            $query_str = 'UPDATE '.$this->tb_user.$this->id.' SET rating='.$rating.' WHERE question_id='.$qid;
            //echo $query_str; die();
            $res = & $mdb2->query($query_str);
            if (PEAR::isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
            $mdb2->disconnect();
        }
    }
    //=====================================================================//
    function getAnswer($qid,$qtype,$answered,$config) {     //getUserAnswer
        //=====================================================================//
        /* $config: mode: (0-rand) | (1-DB) parameters */
        //echo $qid,$qtype,$config,$answered
        //echo $qid.' -- '.$qtype.' -- '.$answered.' --> '.$config.'<p>';
       $tr = new ITS_statistics($this->id,$this->term,$this->role);

        $Q = new ITS_question($this->id,$this->db_name,$this->tb_name);
        $Q->load_DATA_from_DB($qid);                 //$this->lab_questions[$n]
        $question = $Q->render_QUESTION_check($config);
        $Q->get_ANSWERS_data_from_DB();

        //$Q->get_ANSWERS_solution_from_DB();
        //echo $Q->Q_answers_permutation;

        // ANSWERS
        $name = 'ITS_'.$qid;
        //echo "<center>".$Q->render_ANSWERS($name)."</center>";

        $mode = 1;  // mode inactive
        $question = $question.$Q->render_ANSWERS($name,$mode);

        //***$list = $tr->render_user_answer($answered,NULL,NULL,2);
        //$list = $tr->render_question_answer($score,$answered,$qtype);

        //$Q->get_ANSWERS_solution_from_DB();
        //echo $Q->Q_answers_permutation;
        //echo 'NULL '.is_null($answered).' EMPTY: '.empty($answered).' OUT '.($answered==0);

        if (is_null($answered)) { //| empty($answered)) {
			
            $ans    = '&nbsp;';
            $score  = 0;
            $tscore = NULL;
        } else {
		
            //DEBUG:
            //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
            //echo $qid.' | '.$answered.' | '.$qtype.'<p>';
            $score  = $tr->get_question_score($qid,$answered,$config,$qtype);

            //ITS_debug($score);
            $index  = count($score)-1;
            $ans    = $tr->render_question_answer($score,$answered,$qtype,$index);
            
            //$tscore = $this->get_total_score($score,$answers[$qn][1],$qtype);
        }
		
        $dist = ''; //'DISTRIBUTION';
        //echo '<p>score: '.$score.'<p>';

        $list = $tr->render_user_answer($ans,$score,$dist,0,$index);

        return $list;
    }
    //=====================================================================//
    function surveyMode($chapter,$delta) {
        //=====================================================================//
        //echo 'surveyMode';
        //echo  'MODE '.$this->mode;
        $chapter = 14;
        $this->chapter_number = $chapter;
        $this->mode = 'survey';

        //echo $chapter_number.' | '.$this->chapter_number.' | '.$delta.'<p>';

        $tr = new ITS_statistics($this->id,$this->term,$this->role);
        $query = 'SELECT question_id,answered,qtype,answers,rating,comment FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter='.$this->chapter_number;
        //echo $chapter.$query; //die();

        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Exception($this->mdb2->getMessage());
        }

        $res = & $mdb2->query($query);
        if (PEAR :: isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $answers = $res->fetchAll();
        //var_dump($answers);
        if (empty($answers)) {
            $review_str = '<div class="ui-widget">'.
                    '<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">'.
                    '<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>'.
                    'You have not yet answered <b>any</b> survey questions.'.
                    '</div>'.
                    '</div>';
        }
        else {
            $this->review_count = count($answers);
            $index_new = $this->review_number + $delta;
            //echo $index_new.'||'.$this->review_count.'<p>';
            if ($index_new <= 0) {
                $this->review_number = 0;
            }
            elseif ($index_new > ($this->review_count-1)) {
                $this->review_number = $this->review_count - 1;
            }
            else {
                $this->review_number = $index_new;
            }

            $list = '';

            //-- LIST of questions (count($answers)-1)
            $Estr = '<table class="PROFILE">';
            //for ($qn = 0; $qn <= (count($answers)-1); $qn++) {
            //die('da'.$this->review_number);
            //echo 'conf: '.$answers[$qn][4].'<p>';
            $qn    = $this->review_number; //count($answers) - 1;
            $qid   = $answers[$qn][0];  //echo 'qID: '.$qid;
            $qtype = strtolower($answers[$qn][2]);
            $Nanswers = $answers[$qn][3];

            //die($qtype);
            //die($answers[$qn][1]);
            $score = 0; //$tr->get_question_score($qid, $answers[$qn][1], $answers[$qn][5],$qtype);
            //$ans   = $tr->render_question_answer($score, $answers[$qn][1], $qtype,$qn);
            $ans = $answers[$qn][1];

            $Q = new ITS_question($this->id, $this->db_name, $this->tb_name);
            $Q->load_DATA_from_DB($qid);
            $QUESTION = $Q->render_QUESTION_check($answers[$qn][5]);

            $Q->get_ANSWERS_data_from_DB();
            $Q->Q_answers_permutation = explode(',',$answers[$qn][5]);
            $ANSWER = $Q->render_ANSWERS('a', 0);

            $config = 1;
            if ( $chapter == 14 ) {
                $score = '';
                $dist = '';
                $rateBox = '';
            }
            else {
                //--- Question Distribution ---------//
                switch (strtoupper($qtype)) {
                    case 'MC':
                    case 'C':
                        $query = 'SELECT id FROM users WHERE status="'.$this->term_current.'"';
                        //echo $query;
                        $res = & $mdb2->query($query);
                        if (PEAR :: isError($res)) {
                            throw new Question_Control_Exception($res->getMessage());
                        }
                        $activity_users = $res->fetchCol();

                        $pop   = array ($activity_users);
                        $DATA  = $tr->get_question_data($qid, $pop);
                        $stats = $tr->get_question_stats($DATA, $qtype, $Nanswers);
                        $dist  = $tr->get_question_dist($stats, $qid, $qtype,array($this->term_current),$score);
                        break;
                    default:
                        $dist = '';
                }
            }
            //+++--------------------------//
            $FEEDBACK = $tr->render_user_answer($ans,$score,$dist,2,$qn);

            $feedback = '<table style="border:1px solid #fff"><tr><td>'.$FEEDBACK.'</td><td style="width:220px">'.$rateBox.'</td></tr></table>';
            $Estr.= '<tr class="PROFILE">'.
                    '<td class="PROFILE_IDX" style="width:1%"><b>' . ($qn+1) .'.</b></td>'.
                    '<td class="PROFILE">' . $QUESTION . '</td>'.
                    '<td class="PROFILE" colspan="2">' . $ANSWER.'<BR>'.$feedback.'</td>'.
                    '</tr>';
            //} // eof $qn
            $Estr .= '</table>';

            $qinfo = $this->getQuestionRef($qid);
            $nav_info = array($this->review_number,$this->review_count,$qinfo);
            $nav = $this->getNavigation($nav_info,$qid);  // implode(',',$nav_info)
            $review_str = $nav.$Estr;
        }
        return $review_str;
    }
    //=====================================================================//
    function getQuestionQuery($chapter) {
        //=====================================================================//
        $this->chapter_number = $chapter;
        $ITSq     = new ITS_query();
        //die($this->chapter_number);
		$category = $ITSq->getCategory($this->chapter_number);
		        //var_dump($ITSq);       
        $query = 'SELECT question_id,answered,qtype,answers,rating,comment,epochtime FROM stats_'.$this->id.',webct WHERE webct.id=stats_'.$this->id.'.question_id AND current_chapter='.$this->chapter_number.' AND '.$category.' AND epochtime > '.$this->epochtime;
        // EEE   echo '<p style="color:red">getQuestionQuery: '.$query.'<p>'; //die();
//die($query);
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Exception($this->mdb2->getMessage());
        }
        $res = & $mdb2->query($query);
        if (PEAR :: isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $answers = $res->fetchAll();
        //var_dump($answers);
        return $answers;
    }
    //=====================================================================//
    function reviewNav($qid,$qAvail) {
        //=====================================================================//     
        $slider = '<label for="qidtext"></label>'.
                  '<div id="reviewNavTxt">'.$qAvail.' / '.$qAvail.'</div>'.
                  '<div id="slider"></div>';

        //echo $qid.' | '.$qAvail.' | '.$this->review_number.' | '.$this->review_count.' | '.$slider.'<p>';

        $nav_info = array($this->review_number,$this->review_count,$slider);
        $nav = $this->getNavigation($nav_info,$qid);  // implode(',',$nav_info)

        return $nav;
    }
    //=====================================================================//
    function reviewQuestion($chapter,$qIndex,$queryList) {
        //=====================================================================//

        $this->review_count = count($queryList);
        //$index_new = $this->review_number + $delta;
        $index_new = $qIndex;

        //echo $index_new.'||'.$this->review_count.'<p>'; die();

        if ($index_new <= 0) {
            $this->review_number = 0;
        }
        elseif ($index_new > ($this->review_count-1)) {
            $this->review_number = $this->review_count - 1;
        }
        else {
            $this->review_number = $index_new;
        }

        $list = '';

        //-- LIST of questions (count($answers)-1)
        $Estr = '<table class="PROFILE">';
        //for ($qn = 0; $qn <= (count($answers)-1); $qn++) {
        //die('da'.$this->review_number);
        //echo 'conf: '.$answers[$qn][4].'<p>';
        $qn    = $this->review_number; //count($answers) - 1;
        $qid   = $queryList[$qn][0];  // echo 'qID: '.$qid;

        $qtype = strtolower($queryList[$qn][2]);
        $Nanswers = $queryList[$qn][3];

        //die($qtype);
        //die($answers[$qn][1]);

        $tr = new ITS_statistics($this->id,$this->term,$this->role);
        //echo "sending ".$qid." : ". $queryList[$qn][1]." : ". $queryList[$qn][5]." : ".$qtype;
        $score = $tr->get_question_score($qid, $queryList[$qn][1], $queryList[$qn][5],$qtype);
       // die("scpre".$score);

        $ans   = $tr->render_question_answer($score, $queryList[$qn][1],$qtype,$qn);

        $Q = new ITS_question($this->id, $this->db_name, $this->tb_name);
        $Q->load_DATA_from_DB($qid);
        $QUESTION = $Q->render_QUESTION_check($queryList[$qn][5]);

        $Q->get_ANSWERS_data_from_DB();
        $Q->Q_answers_permutation = explode(',',$queryList[$qn][5]);
        $ANSWER = $Q->render_ANSWERS('a', 0);

        $config = 1;

        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Exception($this->mdb2->getMessage());
        }

        //--- Question Distribution ---------//
        switch (strtoupper($qtype)) {
            case 'MC':
            case 'M':
            /*					$sc = 0;
					foreach ( $score as $s ) {
						if (!is_null($s)) { $sc++; }
					} //echo $sc;
					$Nanswers = $sc;
            */
            case 'C':
                $query = 'SELECT id FROM users WHERE status="'.$this->term_current.'"';
                //echo '<p style="color:green">'.$query.'</p>'.$Nanswers;
                                $res = & $mdb2->query($query);
                if (PEAR :: isError($res)) {
                    throw new Question_Control_Exception($res->getMessage());
                }
                $activity_users = $res->fetchCol();

                $pop   = array($activity_users);
                $DATA  = $tr->get_question_data($qid,$qtype,$pop);
                $stats = $tr->get_question_stats($DATA, $qtype, $Nanswers);
                $dist  = $tr->get_question_dist($stats, $qid, $qtype,array($this->term_current),$score);

                //DEBUG: ITS_debug($dist);
                break;
            default:
                $dist = '';
        }

        //--- rating ---------//
        $rateObj = new ITS_rating();
        $rated   = $queryList[$qn][4];
        $rating  = $rateObj->renderRating($rated);
        $rateBox = '<div id="ratingContainer" qid="'.$qid.'">'.$rating.'</div>';
        //-------------------//

        //+++--------------------------//
        $FEEDBACK = $tr->render_user_answer($ans,$score,$dist,$config,$qn);
        $feedback = '<table class="FEEDBACK"><tr><td>'.$FEEDBACK.'</td><td style="width:220px">'.$rateBox.'</td></tr></table>';
        $Estr.= '<tr class="PROFILE">'.
                '<td class="PROFILE_IDX" style="width:1%"><b>' . ($qn+1) .'.</b></td>'.
                '<td class="PROFILE">' . $QUESTION . '</td>'.
                '<td class="PROFILE" colspan="2">' . $ANSWER.'<BR>'.$feedback.'</td>'.
                '</tr>';
        //} // eof $qn
        $Estr .= '</table>';

        $Qarr = array($qid,$Estr);

        return $Qarr;
    }     
    //=====================================================================//
    function reviewUpdate($chapter,$qIndex) {
        //=====================================================================//
        //echo $chapter;
        $this->chapter_number = $chapter;
        $this->mode = 'review';
        $queryList = $this->getQuestionQuery($chapter);
        
        //var_dump($queryList);
        if (empty($queryList)) {
            $review_str = '<center><div class="ITS_MESSAGE">'.
                    'You have not yet solved <b>any</b> problems for Module '.$chapter.'.'.
                    '</div></center>';
        }
        else { 
        $Qarr       = $this->reviewQuestion($chapter,$qIndex,$queryList);   // $Qarr['qid']['content']
        $qinfo      = $this->getQuestionRef($Qarr[0]);
        $review_str = $qinfo.$Qarr[1];
	    }

        return $review_str;
    }
    //=====================================================================//
    function reviewMode($chapter,$qIndex) {
        //=====================================================================//
        //echo 'reviewMode('.$chapter.','.$qIndex.')</p>';die();
        
        $this->chapter_number = $chapter;
        $this->mode = 'review';    
        $queryList  = $this->getQuestionQuery($chapter);

        if (empty($queryList)) {
            $content = '<center><div class="ITS_MESSAGE">'.
                       'You have not yet solved <b>any</b> problems for Module '.$chapter.'.'.
                       '</div></center>';
        }
        else {
		
            $Qarr    = $this->reviewQuestion($chapter,$qIndex,$queryList);   // $Qarr['qid']['content']
            
            $qid     = $Qarr[0];
           
            $qAvail  = count($queryList);
            $content = $this->getQuestionRef($qid).$Qarr[1];    
        }
        
        // navigation
        if (empty($queryList)) {
			$nav     = $this->reviewNav(0,0);
        }
        else {
			$nav     = $this->reviewNav($qid,$qAvail);
		}
        
        $review_str = '<div id="N1" qN="'.$qAvail.'">'.$nav.'</div><div id="N2">'.$content.'</div>';
      
        return $review_str;

        //echo $chapter_number.' | '.$this->chapter_number.' | '.$delta.'<p>';
        /*++++++++++++++++++++++++++++++++*/
        /*
			if ($this->lab_index > count($ques)) { $this->lab_index = count($ques); }
	    $qid   = $ques[$this->lab_index-1][0];
			$qtype = $ques[$this->lab_index-1][1];
			$index = $this->getLabIndex();
      $question = $this->getLabQuestion($this->lab_active,$this->lab_index);
			
			$query = 'SELECT answered from stats_'.$this->id.' WHERE question_id='.$qid; 
			//echo $query; 
			$res   =& $mdb2->query($query);	
			if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
	    $answer = $res->fetchCol(); //var_dump($answer);
			
			if (empty($answer)) { $answered = '';         } 
			else                { $answered = $answer[0]; }
		
			$ans = $this->getAnswer($qid,$qtype,$answered);
  		$navigation_str = $this->getNavigation($ans);
				
  	  //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
  	  //$this->_question_type[$name] = $Q->Q_type;
  	  //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
  	  $str = $index.$question.$navigation_str;
        */
        /*++++++++++++++++++++++++++++++*/
    }
    //=====================================================================//
    function getQuestionRef($qid) {
        //=====================================================================//
        //---- ADMIN WINDOW ------------------------------------------//
        $qinfo = '';
        if ($this->term == 'admin') {
            /* $qinfo = '<table class="ITS_ADMIN" style="float: right;">'.
								 				  '<tr><th>qid</th><th>type</th><th>ch</th></tr>'.
								 				  '<tr><td><a href="Question.php?qNum='.$qid.'" class="Qnum">'.$qid.'</a></td><td>'.$qtype.'</td><td>'.$resource_name.'</td></tr>'.
												  '</table>'; 
            */
            $qinfo .= '<table class="ITS_ADMIN" style="float:right;border:1px solid red">'.
                      '<tr><td><a href="Question.php?qNum='.$qid.'" class="ITS_ADMIN">'.$qid.'</a></td></tr>'.
                      '</table>';
        }
        return $qinfo;
    }
    //=====================================================================//
    function updateChapter($chp_num) {
        //=====================================================================//
        $this->chapter_number = $chp_num;
        die($chp_num);
        $content_str          = $this->getChapter();

        return $content_str;
    }
    //=====================================================================//
    function getChapter($resource,$resource_name) {
    //=====================================================================//
        // echo '<p style="color:brown">getChapter(): '.$resource.' | '.$resource_name.'</p>';	   //die();
//*
        $NO_QUESTIONS = FALSE;
        $ITSq = new ITS_query();

        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }
        // echo '<p style="color:blue">'.$resource.'<p>';

        switch ($resource) {
            //-------------------------------//
            case 'review':
            //-------------------------------//
                $msg = 'Review';
                break;
            //-------------------------------//
            case 'practice':
            //-------------------------------//
                $msg = 'Practice';
                /*
					$this->question_completed = FALSE;
		      $la = sprintf("%02d",$resource_name);
					$term = $this->role; //'Spring_2011';
					//die($term);
					$this->lab_tag = 'survey';
    			$query = 'SELECT question_id,qtype,answers,qorder FROM activity,webct WHERE term="'.$term.'" AND name="'.$this->lab_tag.$la.'" AND activity.question_id=webct.id ORDER BY qorder';  
                */
//*                
                //$msg = 'Chapter '.$resource_name;
				//$resource_source = 'category IN ("SPEN'.$resource_name.'","PreLab0'.$resource_name.'","Chapter'.$resource_name.'","Lab'.$resource_name.'"'.$other.') OR category RLIKE "-Mod'.$resource_name.'" AND qtype IN ("M","MC","C")';
			    $resource_source = $ITSq->getCategory($resource_name);

                // AVAILABLE QUESTIONS for chapter
                $query = 'SELECT id FROM webct WHERE '.$resource_source; //die($query);
                //echo '<p>'.$query.'<p>'; // die();
                $res  =& $mdb2->query($query);
                $qarr = $res->fetchCol();
                break;
            //-------------------------------//
            case 'survey':
            //-------------------------------//
                $msg = 'Survey';
                $this->question_completed = FALSE;
                $la = sprintf("%02d",$resource_name);
                $term = $this->term_current; //'Spring_2011';
                //die($term);
                $this->lab_tag = 'survey';
                $query = 'SELECT question_id,qtype,answers,qorder FROM activity,webct WHERE term="'.$term.'" AND name="'.$this->lab_tag.$la.'" AND activity.question_id=webct.id ORDER BY qorder';
                //echo '<p>'.$query.'<p>'; // die();
                $res  =& $mdb2->query($query);
                $qarr = $res->fetchCol();
                //var_dump($qarr);
                break;
            //-------------------------------//
            case 'chapter':
            //-------------------------------//
                $msg = 'Chapter '.$resource_name;
                if (is_numeric($resource_name)) {
                    $resource_source = 'chapter_id = '.$resource_name;
                }
                else {
                    $resource_source = 'chapter_id = "'.$resource_name.'"';
                }

                // AVAILABLE QUESTIONS for chapter
                //$query = 'SELECT question_id FROM questions WHERE '.$resource_source; //die($query);
                $query = 'SELECT id FROM webct WHERE '.$resource_source; //die($query);
                //	echo '<p>'.$query.'<p>'; // die();
                // DEBUG: $query = 'SELECT id FROM webct WHERE category IN ("PreLab06","Chapter6","Lab6") AND qtype IN ("M")';
                $res  =& $mdb2->query($query);
                $qarr = $res->fetchCol();
                //var_dump($qarr);
                break;
            //-------------------------------//
            case 'index':
            case 'question':
            //-------------------------------//
            //echo 'role: '.$this->term;
                $msg = 'Module '.$resource_name;

                /*(8-26-2011)*/
                //echo $this->term;
                /*
                if ($this->term == 'admin') {
                    $resource_source = 'category IN ("PreLab0'.$resource_name.'","Chapter'.$resource_name.'","Lab'.$resource_name.'"'.$other.') AND qtype IN ("M","MC","C")';
                } else {
                    //
                    //$resource_source = 'category IN ("PreLab0'.$resource_name.'","Chapter'.$resource_name.'","Lab'.$resource_name.'"'.$other.') AND qtype IN ("M","MC","C")';
                    //8-
                    $resource_source = 'category IN ("Chapter'.$resource_name.'") AND qtype IN ("M","MC","C")';
                    //$resource_source = 'category IN ("intro") AND qtype IN ("M","MC","C")';
                }*/
//*
                //$resource_source = 'category IN ("SPEN'.$resource_name.'","PreLab0'.$resource_name.'","Chapter'.$resource_name.'","Lab'.$resource_name.'"'.$other.') OR category RLIKE "-Mod'.$resource_name.'"  AND qtype IN ("M","MC","C")';
			    $resource_source = $ITSq->getCategory($resource_name);
			    
                // AVAILABLE QUESTIONS for chapter
                $query = 'SELECT id FROM webct WHERE '.$resource_source;
                //die($query);
                //**  echo '<p>'.$query.'<p>'; // die();
                // DEBUG: $query = 'SELECT id FROM webct WHERE category IN ("PreLab06","Chapter6","Lab6") AND qtype IN ("M")';
                $res  =& $mdb2->query($query);
                $qarr = $res->fetchCol();
                //var_dump($qarr);
                break;
            //-------------------------------//
        }
        //echo $resource.' - '.$msg.'<p></p>';
        //var_dump($qarr);die();

        /*
		 $tag_list = implode(",",$tag_res);
		 $tag_arr = explode(",",$tag_list);

		 $n=0;
		 $tarr = array();
		 foreach ($tag_arr as $val) {
		   if (!empty($val)){
			    $tarr[$n] = $val;
					$n++;
			 }
		 }*/ 
//*		
        //var_dump($qarr);
        if (empty($qarr)) {
            $NO_QUESTIONS = TRUE;
        }
        else {
             /*
    		 $tlist = implode(",",$tag_res);
    		
    		 // questions <= tags
    		 //$query = 'SELECT question_id FROM tags WHERE id IN ('.$tlist.')';
				 $query = 'SELECT question_id FROM tags WHERE id IN ('.$tlist.')';
    		 die($query);
    		 $res   =& $mdb2->query($query);
         $ques_res  = $res->fetchCol();
				 
    		 $n=0;
			   $qarr = array();
    		 foreach ($ques_res as $qlist) {
    		   if (!empty($qlist)){ 
    			  $qarr[$n] = $qlist;
    				$n++;
    			 }
    		 } 
            */
 //*           
            if (empty($qarr)) {
                $NO_QUESTIONS = TRUE;
            }
            else {
                // ALL POSSIBLE QUESTIONS
                $ques_list = implode(",",$qarr);
                $ques_arr  = explode(",",$ques_list);

                // check if already taken
                // EEE echo 'USER: '.$resource;
                switch ($resource) {
                    //-------------------------------//
                    case 'practice':
                    //-------------------------------//
                        $query = 'SELECT id,qtype FROM webct WHERE id IN ('.$ques_list.') AND id NOT IN (SELECT question_id FROM stats_'.$this->id.' WHERE current_chapter='.$resource_name.' OR current_chapter=-'.$resource_name.')';
                        //echo '<p>'.$query; die($query);
                        $res =& $mdb2->query($query);
                        $qAvailable = $res->fetchAll();
                        //echo '<pre>';print_r($qAvailable);echo '</pre>';
                        $K = count($qAvailable);

                        if (empty($K)) { // ALL QUESTIONS TAKEN
                            //echo 'EMPTY K';
                            $queryP = 'SELECT id,qtype FROM webct WHERE id IN ('.$ques_list.')';
                            //echo '<p>'.$query; die($query);
                            $resP =& $mdb2->query($queryP);
                            $qAvailable = $resP->fetchAll();
                            //echo '<pre>';print_r($qAvailable);echo '</pre>';
                            $K = count($qAvailable);
                        }
                        break;
                    //-------------------------------//
                    case 'survey':
                    //-------------------------------//
                    //$query = 'SELECT webct.id,webct.qtype FROM activity,webct WHERE webct.id IN ('.$ques_list.') AND webct.id NOT IN (SELECT question_id FROM stats_'.$this->id.' WHERE score IS NOT NULL) ORDER BY activity.qorder'; //###!!!
                        $query = 'SELECT webct.id,webct.qtype,activity.qorder FROM webct LEFT JOIN activity ON  webct.id=activity.question_id WHERE activity.term="'.$term.'" AND webct.id IN ('.$ques_list.') AND webct.id NOT IN (SELECT question_id FROM stats_'.$this->id.') ORDER BY qorder';
                        //echo '<p>'.$query; die($query);
                        $res =& $mdb2->query($query);
                        $qAvailable = $res->fetchAll();
                        //echo '<pre>';print_r($qAvailable);echo '</pre>';
                        $K   = count($qAvailable);
                        break;
                    //-------------------------------//
                    default:
                        $query = 'SELECT id,qtype FROM webct WHERE id IN ('.$ques_list.') AND id NOT IN (SELECT question_id FROM stats_'.$this->id.' WHERE score IS NOT NULL AND current_chapter='.$resource_name.' AND epochtime > '.$this->epochtime.')  AND qtype IN ("M","MC","C")'; //###!!!
                        //EEE echo '<p><font color="green">'.$query.'</font>';
                        //echo '<p>'.$query; //die($query);
                        $res =& $mdb2->query($query);
                        $qAvailable = $res->fetchAll();
                        //echo '<pre>';print_r($qAvailable);echo '</pre>';
                        $K   = count($qAvailable);
                    //-------------------------------//
                }
                //echo $K . "\n";die();

                if ($K) {   // section questions available
                    // choose random question from ALL POSSIBLE QUESTIONS
                    // $qAvailable = array(581); //492,1211,1212);

                    //----
                    switch ($resource) {
                        //-------------------------------//
                        case 'survey':
                        //-------------------------------//
                        //echo '<pre>';print_r($qAvailable); echo '</pre>';//die('done');
                            $qid   = $qAvailable[0][0];
                            $qtype = $qAvailable[0][1];
                            $this->mode = 'survey';
                            $ch_idx = 14;
                            $skip = '';
                            break;
                        //-------------------------------//
                        default:
                        //-------------------------------//
                            $ques_arr_rand_key = array_rand($qAvailable,1);
                            $qid   = $qAvailable[$ques_arr_rand_key][0];
                            $qtype = $qAvailable[$ques_arr_rand_key][1];
                            $ch_idx = $this->chapter_number;
                            $this->mode = 'question';
                            $skip = '<input type="button" class="ITS_skip" id="ITS_skip" name="skip" value="skip &nbsp;&rsaquo;&rsaquo;" mode="'.$resource.'">';
                            break;
                    }
                    //----

                    //echo '<p>++++ '.$qid.' + '.$qtype.' ++++<p>';die();
                    //==>>	$qid = 1196; $qtype = 'MC';
                    //if ($this->role == 'admin') { echo 'ITS_screen2::getChapter '.$qid.'<p>'; }

                    //echo $qAvailable[$ques_arr_rand_key[0]] . "\n";
                    $qinfo    = $this->getQuestionRef($qid);
                    $question = $this->getQuestion($qid,'');

                    //echo 'NOW: '.empty($this->question_info);
                    if (empty($this->question_info)) {
                        $cstr = '';
                    }
                    else {
                        if     (strtolower($qtype) == 'm') {
                            $cstr = implode(',',$this->question_info);
                        }
                        elseif (strtolower($qtype) == 'c') {
                            $cstr = implode(',',$this->question_info);
                        }
                        else {
                            $cstr = '';
                        }
                    }
                    //var_dump($this->question_info);
                    //var_dump($cstr);
                    //die('aa');
                    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
                    /*
							$tabs = '<div id="bookNavContainer"><ul id="metaList">'
							.'<li><a href="#" onclick="ITS_book_select(this)" name="meta" value="paragraph">book</a></li>'
							.'<li id="active"><a href="#" onclick="ITS_book_select(this)" name="meta" value="equation" id="current">equations</a></li>'
							.'<li><a href="#" onclick="ITS_book_select(this)" name="meta" value="math">math symbols</a></li>'
							.'<li><a href="#" onclick="ITS_book_select(this)" name="meta" value="image">images</a></li>'
							.'</ul></div>';
											
              $ch   = 3;
              $meta = 'equation';
              $mimetex_path = '/cgi-bin/mimetex.exe?';
              $x = new ITS_book('dspfirst',$ch,$meta,$mimetex_path);
              $o = $x->main();
							$resources = $tabs.'<div id="bookContainer">'.$o.'</div>';
              //echo $resources.'<p>';die('done');
                    */
//*                    
                    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
                    $resources = '';
                    //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                    $error     = '<br><div id="errorContainer"></div>';
                    //<form action="javascript:ITS_question_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.',\''.$qtype.'\');" name="ITS_SubmitForm" id="ITS_SubmitForm">                    
                    //*** Prevent Multiple submissions: - 2. Server: Generate question session ticket ***//
//*
                    $token = time(); // based on current time
                    $_SESSION['ITSQ_'.$qid] = $token;
                    //echo $token;var_dump($_SESSION['ITSQ_'.$qid]);
					//***---------------------------------------------------------------------------------***//
///*                    
                    $form   = $qinfo.$question.$error	/*$rateBox*/
                            .'<div class="navContainer" id="navBoxContainer">'
                            .'<input type="submit" class="ITS_submit" id="ITS_submit" name="submit" value="Submit" ch="'.$ch_idx.'" qid="'.$qid.'" qtype="'.$qtype.'" c="'.$cstr.'" t="'.$token.'" mode="'.$resource.'">'
                            .'</div>';

                    //$resource = '<div class="resContainer" id="resBoxContainer">my res</div>';
                    //$answer = $form.'<div id="errorContainer" class="ITS_message"></div><div id="answerContainer" onreset="ITS_obj_timer()">'.$submit.'</div>';
                    $answer = $form.$skip.$resources;
                    //DEBUG: echo '|input type="submit" class="ITS_submit" id="ITS_submit" name="submit" value="Submit" qid="'.$qid.'" qtype="'.$qtype.'" c="'.$cstr.'"';

                    /*-- TAGGING START --/
						$tagBox = new ITS_tagInterface();					
						$tags   = $tagBox->displayTags($this->id,$qid,$tagBox->getTags($qid));
						//$stags  = $tagBox->createSearchAddBox($this->id,$qid);
						$answer = '<br>'.$answer.$tags.$stags.'<br>';//*/
                    /*-- TAGGING END --*/

                    //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                    //$this->_question_type[$name] = $Q->Q_type;
                    //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');
///*
                    if ($this->role == 'admin') {
                        $qEdit = '<input type="button" onclick="javascript:ITS_QCONTROL_EDITMODE(this)" name="editMode" value="Edit" status="true">';
                        //$available_str = '<span class="ITS_available">Available: '.$K.'</span>';
                        $data = array('Question',$qid,'Available',$K);
                        $tb = new ITS_table('ad',2,2,$data,array(20,80),'ADMIN');
                        $admin_str = '<div class="ITS_ADMIN">'.$tb->str.'<br>'.$qEdit.'</div>';
                    }
                    else {
                        $admin_str = '';
                    }
                    $str = $answer;//.$admin_str;
                }else {    // none available
                    $NO_QUESTIONS = TRUE;
                }
            }
        }
        if ($NO_QUESTIONS) {
            $str = '<div class="ITS_MESSAGE">&diams;&nbsp;No more questions available for '.$msg.'.</div>';
            //$str = $this->main();
        }
        $mdb2->disconnect();
        //--------------------//
        
        return $str;
    }
    //=====================================================================//
    function footer() {
        //=====================================================================//
        echo '<div id="footerContainer"> MY FOOTER </div>';
    }
    //=====================================================================//
} //eo:class
//=====================================================================//
function is_empty($var, $allow_false = false, $allow_ws = false) {
    if (!isset($var) || is_null($var) || ($allow_ws == false && trim($var) == "" && !is_bool($var)) || ($allow_false === false && is_bool($var) && $var === false) || (is_array($var) && empty($var))) {   
        return true;
    } else {
        return false;
    }
}
?>
