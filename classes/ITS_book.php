<?php
//=====================================================================//
/*
ITS_book - assembles the book.

		Constructor: ITS_book(name,chapter,meta)
		
								 ex. $ITS_book = new ITS_book('dspfirst',2,'equation');
								
	 Author(s): Greg Krudysz |  Jul-12-2011
//---------------------------------------------------------------------*/

//=====================================================================//

class ITS_book {

		public $name;
		public $chapter;
		public $meta;	
		
	  //public function get_lab_active()        { return $this->lab_active;  }
	
	//=====================================================================//
  	   function __construct($name,$chapter,$meta,$mpath) {
	//=====================================================================//
	  global $db_dsn,$db_name,$tb_name,$db_table_user_state,$mimetex_path,$files_path;
		
		$this->name     = $name;
		$this->chapter  = $chapter;
		$this->meta     = $meta;
		$this->mpath    = $mpath;
		$this->filepath = $files_path;
		$this->db_dsn   = $db_dsn;
	  
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
			
	  $this->mdb2 = $mdb2;
   } 
	//=====================================================================//
	   function main(){
	//=====================================================================// 
		$query = 'SELECT id,chapter,section,paragraph,content,tag_id,name FROM dspfirst WHERE meta="'.$this->meta.'" AND chapter='.$this->chapter;
		// die($query);
		$res = $this->mdb2->query($query);
    if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
    $pars = $res->fetchAll();

    $book = '<div class="ITS_BOOK"><p>';
    //for ($i = 2; $i <= 6; $i++) {  //count($pars)-1
		for ($i = 0; $i <= count($pars)-1; $i++) {
    
    if (empty($pars[$i][5])) { $pars[$i][5] = '""'; }
    
    $query = 'SELECT name FROM tags WHERE id IN ('.$pars[$i][5].')'; // echo '<p>'.$i.' '.$query.'<p>';
    $res = $this->mdb2->query($query);
    if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
    $name = $res->fetchAll();
		
		$fpath = '/FILES/SP1Figures/';
		//
		echo '<p>'.$this->meta.'</p>';
		
    	switch ($this->meta) {
    	//----------------------//
    		case 'paragraph':
    			//----------------------//
    			$tags = '';
          for ($t = 0; $t <= count($name)-1; $t++) {
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
    			//$book = $book.'<div class="ITS_PARAGRAPH">'.$pars[$i][4].'</div><br>'; 
					//echo '<font color=red>'.$pars[$i][4].'</font><hr>';
					
    			$book .= $pars[$i][4].'<div class="ITS_tags">'.$tags.'</div>';		
    			break;
    			//----------------------//
    		case 'equation':
    		  //----------------------//
    			$tags = ''; //array();
          for ($t = 0; $t <= count($name)-1; $t++) { 
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
    			//if ($i==0) { $book .= '<hr class="ITS_hr">';}
    			//$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font></td><td><img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/></td></tr><tr><td colspan="2">'.$tags.'</td></tr></table></div>';
    			
					$book .= '<img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/>'; 
					break;
    			//----------------------//
    		case 'math':
    			//----------------------//
          //$str ="REFERENCE#fig:dtsig#REFERENCE";      
          //$str = preg_replace("/(a)(.*)(d)/","a($2)d",$str);  
          // a(s)dfd a()dsfd a(aaa)da(s)d
					//$book = preg_replace("/I want (\S+) one/", "$1 is the one I want", "I want that one") . "\n";
					
    			$book .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font><td><img class="ITS_EQUATION" src="'.$this->mpath.$pars[$i][4].'"/></td></tr></table></div>';			
					break;
    			//----------------------//
    		case 'image': // NO SCORE
    			//----------------------//			
          $tags = ''; //array();
          for ($t = 0; $t <= count($name)-1; $t++) {
             $tags .= '<span class="ITS_tag">'.$name[$t][0].'</span>';
          }
					
    			//if ($i==0) { $book .= '<hr class="ITS_hr">';}
					$ch  = $pars[$i][1];
					$sec = $pars[$i][2];
					$fig = explode('/',$pars[$i][6]);
					$fN  = count($fig);
					$fname = trim(str_replace('}','',$fig[$fN-1]));
					$chs = sprintf('%02d',$ch);
					$imn = sprintf('%02d',($i+1));

					//$img_source = $this->filepath.'SP1Figures/Ch'.$chs.'/Fig'.$chs.'-'.$imn.'_'.$fname.'.png';
					//$img_source = $fpath.'Ch'.$chs.'/art/'.$fname.'.png';
					$img_source = '../BOOK/BOOK_R/Chapter'.$chs.'/art/'.$fname.'.png';
					
					//echo $img_source.'<p>';
					//die($img_source);
					$caption = $pars[$i][4];
					//$caption = preg_replace("/($)(.*)?($)/U",'<img class="ITS_EQUATION" src="'.$mimetex_path.'$2"/></a>"',$caption);
					$caption = preg_replace("/(REFERENCE#)(.*)?(#REFERENCE)/U","<a>$2</a>",$caption);
					
          $img_str = '<div class="ITS_Image"><img src="'.$img_source.'" alt="'.$img_source.'"><br><div class="ITS_Caption">'.$caption.'</div></div>';
					$book   .= '<div class="sectionContainer"><table class="ITS_BOOK"><tr><td width="5%"><font color="blue">'.$pars[$i][0].'</font></td><td>'.$img_str.'</td></tr><tr><td colspan="2">'.$tags.'</td></tr></table></div>';	
					break;
    			//----------------------//
    	}
    }
    $book = $book.'</div><p>';	
    				
		return $book;
	}
	//=====================================================================//
	function fcn($Qnum) {
  //=====================================================================//
	 // connect to database
    $str = 'fcn out';
		return $str;
	}
	//=====================================================================//
}
?>
