<?php
//=====================================================================//
/*
ITS_screen - creates user ITS screen.

		Constructor: ITS_screen(name,rows,cols,data,width)
		
								 ex. $ITS_table = new ITS_screen('tableA',2,2,array(1,2,3,4),array(20,30));
								
	 Author(s): Greg Krudysz |  Feb-15-2010
//-----------------------------------------
 TO DO:
 			 	* Answer is not submitted for type 'c'
*/
//=====================================================================//
/*-- TAGGING module ----------------------------------*/
require_once("tagging/ITS_tagInterface.php");
/*-- RATING module -----------------------------------*/
require_once("rating/ITS_rating.php");
/*----------------------------------------------------*/

class ITS_screen {

    public $id;
    public $term;
    public $role;
    public $tb_name;
    public $record;
    public $style;
    public $screen;

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

    //=====================================================================//
    function __construct($id,$term,$role) {
        //=====================================================================//
        global $db_dsn,$db_name,$tb_name,$db_table_user_state;

        $this->id      = $id;
        $this->term    = $term;
        $this->role    = $role;
        $this->db_name = $db_name;
        $this->tb_name = $tb_name;
        $this->tb_user = $db_table_user_state;

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
        $this->chapter_number = 'ALL';
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
    function main() {
        //=====================================================================//
        // AGENDA:
        //--- STATE ---//
        $this->screen   = 1; // (screen)-name = (1)-LAB | (2)-EXERCISES | (3)-CONCEPT

        $main_str = $this->getScreen($this->screen);
        //echo '<img src="phpimg/ITS_signal.php?t=i&d=-1,3" class="ITS_pzplot">';die();

        return $main_str;
    }
    //=====================================================================//
    function getScreen($screen) {
        //=====================================================================//
        //--- HEADER ---//
        $header_str = $this->getHeader($screen);
        $header = '<p><div id="headerContainer">'.$header_str.'</div>';
        //echo $header;die();

        //--- CONTENT ---//
        $content_str = $this->getContent();
        $content = '<div id="contentContainer">'.$content_str.'</div>';

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
        //echo ' -- in getContent :'.$this->screen;die();
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
        }
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

        if ($lab_active > 13) {
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
        if ($this->lab_active > 13) {
            $lab_active = $this->lab_active - 13;
        }
        else {
            $lab_active = $this->lab_active;
        }

        //echo $this->lab_active.'**<p>';
        //$lab_active    = $this->lab_active;
        $la = sprintf("%02d",$lab_active);
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
                            .'<input type="submit" class="ITS_submit" name="submit" value="Submit">'
                            .'</div>'
                            .'</form>';
                    //$index = $this->getLabIndex();
                    $question = $this->getLabQuestion($this->lab_active,$this->lab_index);
                    //-- TAGS -------------------------------//
                    $tagObj = new ITS_tagInterface();
                    $tags   = $tagObj->displayTags($this->id,$qid,$tagObj->getTags($qid));
                    //echo $tags; die();
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
        $question = $this->getQuestion($ques[($lab_idx-1)]);
        //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;

        return $question;
    }
    //=====================================================================//
    function getQuestion($Qnum) {
        //=====================================================================//
        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        //echo '<p>++'.$Qnum;die();
        $Q = new ITS_question(1,$this->db_name,$this->tb_name);
        $Q->load_DATA_from_DB($Qnum);                 //$this->lab_questions[$n]
        $question = $Q->render_QUESTION_check(0);
        $Q->get_ANSWERS_data_from_DB();

        //$Q->get_ANSWERS_solution_from_DB();
        //echo $Q->Q_answers_permutation;

        // ANSWERS
        //$name = $this->lab_name."[".($Qnum)."]"; //[".($an)."];
        //$name = "[".($Qnum)."]"; //[".($an)."];
        $name = 'ITS_'.$Qnum;
        //echo '<p> *********'.$name.'<p>';
        //echo "<center>".$Q->render_ANSWERS($name)."</center>";

        $mode = 0;  // mode inactive
        if ($this->lab_number == $this->lab_active) {
            if (!$this->lab_completed) {
                $mode = 1;
            }
        }
        //die($this->lab_number.' '.$this->lab_active);
        $question = $question.$Q->render_ANSWERS($name,$mode);
        $mdb2->disconnect();

        return $question;
    }
    /*
	  // NAVIGATION
		$nav = $this->getNavigation();
		$tb_activity = new ITS_table('ITS_activity',1,3,array($nav,$list,$nav),array(1,98,1),'ITS_screen_nav');
    $list = $tb_activity->str;
    */
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
        //----            ----//

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
        // echo 'getNavigation() : '.$this->screen; die();
        $lab_index = $this->lab_index;

        $button_onClick  = '';
        $button_content  = '!';

        //$nav = '<img src="phpimg/ITS_button.php?o=f"'
        $next_icon = '>';  // Next
        $prev_icon = '<';  // Previous
//echo '<p><hr>'.$this->screen.'+++';
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

                // CONTENT: nothing | user_stats | submit button
                if ($ACTIVE) {
                    $buttons[1] = TRUE;
                };

                // NEXT (">") ?
                if ($this->lab_index < count($this->lab_questions)) {
                    $buttons[2] = TRUE;
                };

                /*********/

                $container = "'getContent'";

                /*
  		 $qid = $this->lab_questions[$this->lab_index-1];
  		 //echo($qid);
  		 $style3 = '<div name="nav" class="ITS_screen_control" onClick=ITS_lab_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.')>';
  	 
       if ($this->lab_index == count($this->lab_questions)) {
    			//$next   =  $style1.$data.$style2.$next_icon.'</div>';
    				
    			// SUBMIT BUTTON
    			/*
    		    $form   = '<form action="javascript:ITS_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.');" name="ITS_SubmitForm" id="ITS_SubmitForm">';
    		 		$submit = '<div class="center">'
    		 				     .'<input type="submit" style="color:darkgreen;font-weight:bold" name="submit" value="Submit Lab #'.$this->lab_active.'">'	
    							   .'</div>'
                     .'</form>';
    				
    		    $question = $this->getQuestion($qid);
    		    $answer = $form.$question.'<div id="errorContainer" class="ITS_message"></div><div id="answerContainer">'.$submit.'</div>';		
                */
                // -------------- //
                /*
    	 } else {
    	   	$next  = $style3.$next_icon.'</div>';
    	 }
                */
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
        }
        //die($container);
        // Nav style
        $style1 = 'onClick=ITS_AJAX(\'ITS_screen_AJAX.php\','.$container.',';
        $style2 = ',ITS_screen,\'contentContainer\')>';
        //$style3 = 'onClick=ITS_lab_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.')>';

        // BUTTONS
        if ($buttons[0]) {
            $state = $this->lab_number.','.($lab_index-1);
            $button_onClick = "onClick=ITS_content_nav(this,".($this->lab_index-1).")";
            $prev = '<div name="updateLab_index" class="ITS_navigation"'.
                    $button_onClick.'>'.
                    $prev_icon.
                    '</div>';
        } else {
            $prev = '&nbsp';
        }
        if ($buttons[1]) {
            //$nav_content = $nav_content;
        }
        if ($buttons[2]) {
            $state = $this->lab_active.','.($lab_index+1);
            $button_onClick = "onClick=ITS_content_nav(this,".($this->lab_index+1).")";
            $next = '<div name="updateLab_index" class="ITS_navigation"'.
                    $button_onClick.'>'.
                    $next_icon.
                    '</div>';
        } else {
            $next = '&nbsp';
        }

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
        $nav_tb = new ITS_table('ITS_nav_tb',1,3,$nav,$width,'ITS_screen_nav');
        $navigation = $nav_tb->str;

        return $navigation;
    }
    //=====================================================================//
    function exercisesContent() {
        //=====================================================================//
        $table = '<font color="red">A</font>';

        //$table = '<font color="royalblue">Complex Numbers</font>';
        //$table = 'APPENDIX <a href="ITS_pre_lab.php?activity=1">'.$table.'</a>: Complex Numbers';
        $table  = '<div name="link" class="ITS_chapter" onClick="ITS_AJAX(\'ITS_screen_AJAX.php\',\'getChapter\',\'A\',ITS_screen,\'contentContainer\')">APPENDIX '.$table.'&nbsp;: Complex Numbers</div>';

        $tb_index = $table;

        /*
	$Q = new ITS_question(1,'its','webct');
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
    function recordQuestion($qid,$qtype,$answered) {
        //=====================================================================//
        //var_dump($this->_answers_permutation); die();
        //echo $qid.' - '.$qid.' - '.$answered;

        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        switch (strtolower($qtype)) {
            //-------------------------------//
            case 'm':
            //-------------------------------//
                $query_str = 'INSERT IGNORE INTO '.$this->tb_user.$this->id.' (question_id,answered) VALUES('.$qid.',"'.mysql_real_escape_string($answered).'")';
                //die($query_str);
                //$perm_str = implode(',',$this->_answers_permutation[$name]);
                //$query_str = 'INSERT IGNORE INTO '.$this->tb_user.$this->id.' (question_id,answered,comment) VALUES('.$qid.',"'.mysql_real_escape_string($answered).'","'.$perm_str.'")';
                break;
            //-------------------------------//
            default;
            //-------------------------------//
                $query_str = 'INSERT IGNORE INTO '.$this->tb_user.$this->id.' (question_id,answered) VALUES('.$qid.',"'.mysql_real_escape_string($answered).'")';
            //-------------------------------//
        }
        //echo 'ANSWERS: '.$ans." | ".is_string($ans)."<p> ";die();
        if (!empty($answered)) {
            //echo $query_str."<p>"; die();
            $res = & $mdb2->query($query_str);
            if (PEAR::isError($res)) {
                throw new Question_Control_Exception($res->getMessage());
            }
        }
        $mdb2->disconnect();
    }
    //=====================================================================//
    function getUserAnswer($qid,$qtype,$answered) {
        //=====================================================================//
        $tr = new ITS_statistics($this->id,$this->term,$this->role);

        // connect to database
        //$mdb2 =& MDB2::connect($this->db_dsn);
        //if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

        /*
	  if (is_null($answered)) {
			 $ans   = '&nbsp;';
	  } else {
			 //$ans    = $tr->render_question_answer($score,$answered,$qtype);
			 $ans = 'hello';
	  } */

        $list = $tr->render_user_answer($answered,NULL,NULL,2,0);
        //$list = $tr->render_question_answer($score,$answered,$qtype);

        //$Q->get_ANSWERS_solution_from_DB();
        //echo $Q->Q_answers_permutation;

        return $list;
    }
    //=====================================================================//
    function getAnswer($qid,$qtype,$answered) {
        //=====================================================================//
        //echo $this->id.' [] '.$this->term.' [] '.$this->role;
        $tr = new ITS_statistics($this->id,$this->term,$this->role);

        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        $query = 'SELECT id FROM users WHERE status="'.$this->term.'"';

        $res =& $mdb2->query($query);
        if (PEAR::isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $activity_users = $res->fetchCol();
        //----***--------//
        //var_dump($activity_users);die();
        $pop = array($activity_users);
        // if NO activity users => NO distribution
        if (count($activity_users)) {
            $Nanswers = $this->lab_questions[$this->lab_index-1][2];
            $DATA  = $tr->get_question_data($qid,$pop);
            $stats = $tr->get_question_stats($DATA,$qtype,$Nanswers);
            $dist  = $tr->get_question_dist($stats,$qid,$qtype);
        } else {
            $dist  = '';
        }
        // if NO user answer => NO answer/score
        //$score = $this->get_question_score($answers[$qn][0],$answers[$qn][1],$qtype);
        if (is_null($answered) | empty($answered)) {
            $ans   = '&nbsp;';
            $score = 0;
            $tscore = NULL;
        } else {
            //echo 'SCORE: '.$answers[$qn][0].' | '.$answers[$qn][1].'<p>';
            $score  = $tr->get_question_score($qid,$answered,$qtype);
            $ans    = $tr->render_question_answer($score,$answered,$qtype);
            //$tscore = $this->get_total_score($score,$answers[$qn][1],$qtype);
        }
        $list = $tr->render_user_answer($ans,$score,$dist,1);
        //$tb = new ITS_table('ITS_table_stats',1,3,array($ans,$score,$dist),array(40,30,30),'ITS_feedback');

        //$Q->get_ANSWERS_solution_from_DB();
        //echo $Q->Q_answers_permutation;

        return $list;
    }
    //=====================================================================//
    function updateChapter($chp_num) {
        //=====================================================================//
        $this->chapter_number = $chp_num;
        $content_str          = $this->getChapter();

        return $content_str;
    }
    //=====================================================================//
    function getChapter($resource,$resource_name) {
        //=====================================================================//
        //echo 'getting chapter ...'.$chp_num.'<p>';	   die();
        $NO_QUESTIONS = FALSE;

        switch ($resource) {
            //-------------------------------//
            case 'chapter':
            //-------------------------------//
                if (is_numeric($resource_name)) {
                    $resource_source = 'chapter_id = '.$resource_name;
                }
                else {
                    $resource_source = 'chapter_id = "'.$resource_name.'"';
                }
                break;
            //-------------------------------//
            case 'index':
            //-------------------------------//
                $resource_source = 'name = "'.$resource_name.'"';
                break;
            //-------------------------------//
        }

        // connect to database
        $mdb2 =& MDB2::connect($this->db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Question_Control_Exception($mdb2->getMessage());
        }

        // tags <= index_1(chapter)
        $query = 'SELECT tag_id FROM index_1 WHERE '.$resource_source; //die($query);
        $res =& $mdb2->query($query);
        $tag_res  = $res->fetchCol();
        $tag_list = implode(",",$tag_res);
        $tag_arr = explode(",",$tag_list);

        $n=0;
        $tarr = array();
        foreach ($tag_arr as $val) {
            if (!empty($val)) {
                $tarr[$n] = $val;
                $n++;
            }
        } //var_dump($tarr);
        if (empty($tarr)) {
            $NO_QUESTIONS = TRUE;
        }
        else {
            $tlist = implode(",",$tarr);

            // questions <= tags
            $query = 'SELECT question_id FROM tags WHERE id IN ('.$tlist.')';
            //die($query);
            $res   =& $mdb2->query($query);
            $ques_res  = $res->fetchCol();

            $n=0;
            $qarr = array();
            foreach ($ques_res as $qlist) {
                if (!empty($qlist)) {
                    $qarr[$n] = $qlist;
                    $n++;
                }
            }

            if (empty($qarr)) {
                $NO_QUESTIONS = TRUE;
            }
            else {
                // ALL POSSIBLE QUESTIONS
                $ques_list = implode(",",$qarr);
                $ques_arr  = explode(",",$ques_list);

                // check if already taken
                $query = 'SELECT id FROM webct WHERE id IN ('.$ques_list.') AND id NOT IN (SELECT question_id FROM stats_'.$this->id.')';
                $res =& $mdb2->query($query);
                $qAvailable = $res->fetchCol();
                $K   = count($qAvailable);

                if ($K) {   // section questions available
                    // choose random question from ALL POSSIBLE QUESTIONS
                    $ques_arr_rand_key = array_rand($qAvailable,1);
                    $qid = $qAvailable[$ques_arr_rand_key];
                    //echo $qAvailable[$ques_arr_rand_key[0]] . "\n";

                    $form   = '<form action="javascript:ITS_submit(document.getElementById(\'ITS_SubmitForm\'),'.$qid.');" name="ITS_SubmitForm" id="ITS_SubmitForm">';
                    $submit = '<div class="center">'
                            .'<input type="submit" style="color:darkgreen;font-weight:bold" name="submit" value="Submit">'
                            .'</div>'
                            .'</form>';

                    $question = $this->getQuestion($qid);
                    $answer = $form.$question.'<div id="errorContainer" class="ITS_message"></div><div id="answerContainer">'.$submit.'</div>';

                    /*-- TAGGING START --*/
                    $tagBox = new ITS_tagInterface();
                    $tags   = $tagBox->displayTags($this->id,$qid,$tagBox->getTags($qid));
                    $answer = '<br>'.$answer.$tags.'<br>';
                    /*-- TAGGING END --*/

                    //$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
                    //$this->_question_type[$name] = $Q->Q_type;
                    //$tb = new ITS_table('ITS_pres',2,1,array($question,$answer),array(99,1),'ITS_ghost');


                    if ($this->role == 'admin') {

                        $qEdit = '<input type="button" onclick="ITS_QCONTROL_EDITMODE(this)" name="editMode" value="Edit" status="true">';
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
            echo '<div class="ITS_MESSAGE" style="color:green;padding:5px">&diams;&nbsp;No more questions available for this section.</div>';
            $str = $this->getConcepts();
        }
        $mdb2->disconnect();
        //--------------------//
        return $str;
    }
    //=====================================================================//
} //eo:class
//=====================================================================//
/*
	 if(0) { //isset($_POST['submit'])) {
	 	//if answers are submitted
	 		$score = 0;
			for($cnt=1;$cnt<=6;$cnt++) {
				if(!empty($_POST[$cnt])) {
					//$_POST[$cnt] is the student answer; $_POST[$Ans] is the correct answer
					$Ans = "A".$cnt;
					if($_POST[$cnt]==$_POST[$Ans]) {
						//correct answer
						$query = "INSERT INTO stats_".$this->user_id." (question_id, answered, score) VALUES(".$_POST['qid'].",".chr($_POST[$cnt]+64).",100)";
						$score = $score+100;
					}
					else {
						//wrong answer
						$query = "INSERT INTO stats_".$this->user_id." (question_id, answered, score) VALUES(".$_POST['qid'].",".chr($_POST[$cnt]+64).",0)";
					}				}
			}
			echo "Your score is : ".$score."/600";
	 }
	 else {
		 $cnt=1;
		 //echo '<h3>Each Question carries 100 points.</h3><br>';
	 	 echo '<form action='.$_SERVER["PHP_SELF"].' method="post">';
	 
		 if ($chp_num=="A")
			 $query = "SELECT id, question, qtype, answers FROM webct WHERE category = 'AppendixA'";
	 
		  $res = $mdb2->query($query);
		  while($row=$res->fetchRow()) {
	 		$question_id = $row[0];
			$question = $row[1];
			$qtype = $row[2];
			$answer = $row[3];
		
			echo '<div class="header">'.$question.'<br>';
			/*if($qtype=="P") {
				$query_ans = "SELECT answer FROM webct_p WHERE id = ".$question_id;
				$res_ans =& $mdb2->query($query_ans);
				while($row2=$res_ans->fetchRow()) {
					echo '<input name="A'.$cnt.'" type="hidden" value="'.$row2[0].'" />';
				}
				echo '<div class="MATLAB">';
				echo 'Enter your answer: <input type="text" name="'.$cnt.'" /><br>';
				echo '</div>';
				$cnt++;
			}*/
/*--			
			if($qtype=="MC") {
				$query_ans = "SELECT answer1, answer2, answer3, answer4, answer5, answer6, answer7, answer8, answer9, answer10, answer11, answer12, answer13, answer14, answer15, answer16, answer17, answer18, answer19, answer20 FROM webct_mc WHERE id=".$question_id;
				$res_ans =& $mdb2->query($query_ans);
				echo '<div class="MATLAB">';
				while($row2=$res_ans->fetchRow()) {
					for($j=0;$j<20;$j++) {
						if($j==($answer-1)) {
							$str_ans = $row2[$j];
							$k=$j+1;
							echo '<input name="A'.$cnt.'" type="hidden" value="'.$k.'" />';
						}
						if($row2[$j]!="") {
							$k=$j+1;
							echo '<input type="Radio" name="'.$cnt.'" value="'. $k .'">'.$row2[$j].'</input><br>';
						}
					}
				}
				echo '</div>';
				$cnt++;
			}
			echo '</div><br>';		
		}
		 echo '<input name="chapter" type="hidden" value="'.$chp_num.'" />';
		 echo '<input name="qid" type="hidden" value="'.$question_id.'" />';
		 echo '<input name="submit" type="submit" value="SUBMIT" />';
    	 echo '</form>';		 
	 }
	 $mdb2->disconnect();
*/
?>
