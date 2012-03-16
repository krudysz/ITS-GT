<?php
//=====================================================================//
/*
ITS_front - creates user front screen.

		Constructor: ITS_front(name,rows,cols,data,width)
		
								 ex. $ITS_table = new ITS_table('tableA',2,2,array(1,2,3,4),array(20,30));
								
	 Author(s): Greg Krudysz |  Jan-18-2010
*/
//=====================================================================//
class ITS_front {

		public $id;
		public $term;
		public $role;
		public $tb_name;
		public $record;
		public $style;
	
	//=====================================================================//
  	   function __construct($id,$term,$role) {
	//=====================================================================//
	  global $db_dsn,$tb_name;
		
		$this->id   = $id;
		$this->term = $term;
		$this->role = $role;
	  //$this->db_name      = $db_name;
    $this->tb_name = $tb_name;
		$this->record = array();
	  
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
			
	  $this->mdb2 = $mdb2;
   } 
	//=====================================================================//
	   function main(){
	//=====================================================================//
		$lab_active = 3;
		$exe_active = 2; // ch.2
		$con_active = 2; // ch.2

		//--- HEADER ---//
		// Labs | EXERCISES | CONCEPTS
		$index_content = array('1','2','3');
		$tb_index = new ITS_table('ITS_activity',1,3,$index_content,array(33,33,33),'ITS_screen_index');
		$header_lab = $tb_index = new ITS_table('ITS_activity',1,2,array('LABS',$tb_index->str),array(50,50),'ITS_ghost');
		$header_str = array($header_lab->str,'EXERCISES','CONCEPTS');
 		$tb_header = new ITS_table('ITS_activity',1,3,$header_str,array(33,33,33),'ITS_ghost');
		$header = $tb_header->str;

		//--- CONTENT ---//
		$content_str = $this->getQuestion();
		$content = '<div id="contentContainer">'.$content_str.'</div>';
		
		//--- NAVIGATION ---//
		$nav1 = '<span class="ITS_screen_control" onClick=ITS_MSG(0)>&gt;</span>';
		$nav = '<img src="phpimg/ITS_button.php?o=f"'
		         .'onmouseover=OVER(this) onmouseout=OUT(this) '
		         .'onClick=ITS_AJAX(\'ITS_screen.php\',\'array(1,2)\',\'2\',ITS_screen,\'screen\')>';
		//---------------//
		
		
		$main_str = $header.$content.$nav;
		
		return $main_str;
	}
	//=====================================================================//
	   function getQuestion(){
	//=====================================================================// 
   $lab_num = 2;
	 $this->_lab_title = 'Lab #2';
	 $this->_lab_num = $lab_num;
	 $this->_lab_name = 'Lab #2';
	 $this->db_name = 'its';
	 $this->tb_name = 'webct';
	 $this->_lab_ques_idx = array(1195,1190);
	 //---------------------------//
   $PRE = '<form action="ITS_pre_lab.php?activity='.$this->_lab_num.'" name="survey" method="POST"><p>';
	//-----------//-------------//
	      $k = 0; // 
	 			//for ($n = 0; $n <= $this->_lab_sec_parts[$k]-1; $n++){
				$n=0;
					$CONTENT = '<table class="ITS_QUESTION_PART">';
					$part_header = '<h3>'.($n+1).'.</h3>';
					$CONTENT = $CONTENT.'<tr><td class="ITS_QUESTION_PART_NUM">'.$part_header.'</td><td class="ITS_QUESTION_PART">';
		 			$Q = new ITS_question(1,$this->db_name,$this->tb_name);
	        $Q->load_DATA_from_DB($this->_lab_ques_idx[$n]);
					$CONTENT = $CONTENT.$Q->render_QUESTION_check();
	        $Q->get_ANSWERS_data_from_DB();
					//$Q->get_ANSWERS_solution_from_DB();
					//echo $Q->Q_answers_permutation;
					// ANSWERS		
					$CONTENT = $CONTENT; //$PRE.

					  //echo '<b>'.$n.'</b><p>';
			      $name = $this->_lab_name."[".($this->_lab_ques_idx[$n])."]"; //[".($an)."];			
			      //echo "<center>".$Q->render_ANSWERS($name)."</center>";
						$CONTENT = $CONTENT.$Q->render_ANSWERS($name);
					$CONTENT = $CONTENT.'</td></tr></table>';
					
					//$this->_answers_permutation[$name] = $Q->Q_answers_permutation;
					//$this->_question_type[$name] = $Q->Q_type;
	      //}
					// SUBMIT BUTTON
	$POST = '<div class="center">'
      .'<input type="submit" name="submit" value="Submit '.$this->_lab_title.'">'
      .'</div>'
      .'</form>';
	
	$STR = $CONTENT;//.$POST;
	return $STR;
	}
	//=====================================================================//
	   function labsContent(){
	//=====================================================================//
	$title = '<div class=header>LABS</div>';        
	                                 					
	$table1 = '<font color="royalblue">Lab #1</font>';
	$table1 = '<a href="ITS_pre_lab.php?activity=1">'.$table1.'</a>';
	$table2 = '<font color="royalblue">Lab #2</font>';
	$table2 = '<a href="ITS_pre_lab.php?activity=2">'.$table2.'</a>';
	$tb_index = new ITS_table('ITS_activity',2,1,array($table1,$table2),array(100),'ITS_feedback_list');
	
	$tb_labs = new ITS_table('ITS_activity',2,1,array($title,$tb_index->str),array(100),'ITS_ghost');	
	return $tb_labs->str;
	}
	//=====================================================================//
	   function exercisesContent(){
	//=====================================================================//
	$exercises = 'Exercises';
	$title = '<div class=header>EXERCISES</div>';    
	$table = '<font color="red">A</font>';  
	$table = 'APPENDIX '.$table.': Complex Numbers';                               					
	//$table = '<font color="royalblue">Complex Numbers</font>';
	//$table = 'APPENDIX <a href="ITS_pre_lab.php?activity=1">'.$table.'</a>: Complex Numbers';
	
	$table1 = '<font color="red">1</font>';  
	$table1 = 'CHAPTER '.$table1.': Introduction'; 
	
	$table2 = 'CHAPTER <font color="red">2</font>: Sinusoids'; 
	$table3 = 'CHAPTER <font color="red">3</font>: Spectrum Representation';
  $table4 = 'CHAPTER <font color="red">4</font>: Sampling and Aliasing';
	$table5 = 'CHAPTER <font color="red">5</font>: FIR Filters';
	$table6 = 'CHAPTER <font color="red">6</font>: Frequency Response of FIR Filters';
	$table7 = 'CHAPTER <font color="red">7</font>: <i>z</i>-Transforms';
	$table8 = 'CHAPTER <font color="red">8</font>: IIR Filters';
	$table9 = 'CHAPTER <font color="red">9</font>: Continuous-Time Signals and LTI Systems';
	$table10 = 'CHAPTER <font color="red">10</font>: Frequency Response';
	$table11 = 'CHAPTER <font color="red">11</font>: Continuous-Time Fourier Transform';
	$table12 = 'CHAPTER <font color="red">12</font>: Filtering, Modulation, and Sampling';
	$table13 = 'CHAPTER <font color="red">13</font>: Computing the Spectrum';
	
	$tb_index = new ITS_table('ITS_activity',14,1,array($table,$table1,$table2,$table3,$table4,$table5,$table6,$table7,$table8,$table9,$table10,$table11,$table12,$table13),array(100),'ITS_feedback_list');
	
	/*
	$Q = new ITS_question(1,'its','webct');
	$Q->load_DATA_from_DB(1);
	$preview = $Q->render_QUESTION_check();
	$Q->get_ANSWERS_data_from_DB();
  //$preview = $preview.$Q->render_ANSWERS('a');
	$preview = '<DIV class="ITS_PREVIEW">'.$preview.'</DIV>';
 */
 $preview = '';

	$tb_labs = new ITS_table('ITS_activity',3,1,array($title,$tb_index->str,$preview),array(100),'ITS_ghost');	
	return $tb_labs->str;
	}
	//=====================================================================//
} //eo:class
//=====================================================================//
?>