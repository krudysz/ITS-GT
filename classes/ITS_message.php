<?php
//=====================================================================//
/*
ITS_survey class - lab configuration file.

		Constructor: ITS_survey($user_info,$lab_info,$db_info)
		
		where:
		        $user_info = array($db_dsn,$db_name,$tb_name)
						$lab_info  = array($lab_title,$lab_intro,$lab_num,$lab_sec,$lab_sec_headers, ...
						                   $lab_sec_parts_headers,$lab_sec_parts,$_lab_sec_ques, ...
															 $_lab_sec_ans_rows,$lab_coda)
						$db_info   = array($db_question_id)
	
		ex. $ITS_survey = new ITS_survey(90001,"its","user_cpt");
								
	 Author(s): Greg Krudysz |  Sep-3-2009
	 Last Update: 
*/
//=====================================================================//
class ITS_message
{
  private $db_dsn;
	private $db_name;
	private $tb_name;
	
	private $messages;
	private $lab_current;
	private $lab_active;
	
  //=====================================================================//
  function __construct($lab_current,$lab_active){
  //=====================================================================//
  // MESSAGES:
	 //------------//
	 $quote1 = 'Concepts are the building blocks of thought. Without them, induction would be impossible because everything would be unique.';
   //------------//
	 $img    = '<img border="1" src="images/chessboard.jpeg" height="50px" hspace=10 style="vertical-align:middle" float="left">';
	 $q2_str = 'It has been estimated that world-class chess masters require from 50,000 to 100,000 hours of practice to reach that level of expertise.&nbsp;&nbsp;<tt><font size="2pt" color="grey">[100,000 hours = 11.4 years]</font></tt>';
	 $quote = '<div class="ITS_quote"><span class="ITS_quote">'.$q2_str.'</span></div>';
	 $tb_q2  = new ITS_table('ITS_mess',1,2,array($img,$quote),array(5,95),'ITS_message');
	 $quote2 = $tb_q2->str;
	 //------------//
	 $quote3 = 'An important aspect of learning is to become fluent at recognizing problem types in particular domains.<p>School should be less about preparation for life and more like life itself.';
	 //------------//
	 $quote4 = '&ldquo;A pronounced difference between experts and novices is that experts’ command of concepts shapes  their understanding of new information: it allows them to see patterns, relationships, or discrepancies that are not apparent to novices.&rdquo;';
	 //------------//
	 $quote5 = '<h3>&ldquo;Experience is what you get, when you do not get what you wanted&rdquo;</h3>';
	 //------------//
	 
	 $this->messages = array($quote1,$quote2,$quote3,$quote4,$quote5,$quote1,$quote2,$quote3,$quote4,$quote5,$quote1,$quote2,$quote3,$quote4);
	 $this->lab_current = $lab_current;
	 $this->lab_active  = $lab_active;
	}
	//=====================================================================//
	   function main(){
	//=====================================================================//
	 // MESSAGE
	 $msg = $this->getMessage();
	 $quote = '<div id="ITS_message">'.$msg.'</div>';
	 
	 // CONTROLS
	 $cont = $this->getControls(); 
	 $LN = '<div id="LN">'.$cont[0].'<br>'.$cont[1].'</div>';
	 $RN = '<div id="RN">'.$cont[3].'<br>'.$cont[2].'</div>';
	 //echo $cont[0];die();
	 /*
	 $data = array($cont[0],$quote,$cont[1]);
	 $width = array(1,98,1);
	 $msg_tb = new ITS_table('ITS_message1',1,3,$data,$width,'ITS_message');
	 $message = $msg_tb->str;
	 */
	 $message = $LN.$quote.$RN.'<div class="CL"></div>';
	 
	 return $message;
	}
	//=====================================================================//
	   function getMessage(){
	//=====================================================================//
   $current_msg = $this->messages[$this->lab_active-1];
	 
	 return $current_msg;
	}
	//=====================================================================//
	   function getControls(){
	//=====================================================================//
	 $lab = $this->lab_active;
	 
	 // Nav style
	 $style = '<div name="nav" class="ITS_screen_control" onmouseover=MMOUSE(this,1) onmouseout=MMOUSE(this,0)>';
	 
	 // Next
	 $next_icon = '>';
	 // Previous
	 $prev_icon = '<';

	 //echo $this->lab_current.'<p>'.$this->lab_active;die();
	 // do updates in JAVASCRITP (??)
	 if ($this->lab_current > 1) {
	  //**$prev  = $style.$prev_icon.'</div>';
		$prev = '&nbsp;</div>';
	 } else {
	  $prev = '&nbsp;</div>';
	 }
	 
	 if ($this->lab_current == $this->lab_active) {
	  $next =  '&nbsp;</div>';
	 } else {
	  //**$next  = $style.$next_icon.'</div>';
		$next =  '&nbsp;</div>';
	 }
	 
	 $prev  = $style.$prev_icon.'</div>';
   $next  = $style.$next_icon.'</div>';
	 $close = '<div name="close" class="ITS_screen_control" onmouseover=MMOUSE(this,1) onmouseout=MMOUSE(this,0) onClick=ITS_MSG(0)>x</div>'; //<img src="phpimg/ITS_button.php?o=x" onClick=ITS_MSG_CL(this)>';
	 $emty  = '<div class="ITS_screen_control_ghost">&nbsp;</div>';

	 $controlL = new ITS_table('ITS_messageL',3,1,array('&nbsp;','&nbsp;',$prev),array(20,80,20),'ITS_message');
	 $controlR = new ITS_table('ITS_messageR',3,1,array($close,'&nbsp;',$next),array(20,80,20),'ITS_message');
	 $controls = array($controlL->str,$controlR->str);
   //$controls = array($prev,$close);
	 $controls = array($emty,$prev,$next,$close);
	 
	return $controls;
	}
//=====================================================================//
} //eo:class
//=====================================================================//

