<?php
//=====================================================================//
/*
ITS_rating - rate questions.

		Constructor: ITS_rating( ... )
		
								 ex. $rating = new ITS_rating( ... );
								
	 Author(s): Nabanita Ghosal
	 Mods: Greg Krudysz
	 Last Update: Sep-14-2011
*/	 
//=====================================================================//
class ITS_rating {
	
	public function __construct() {
		global $db_dsn,$tb_name;
		
		$this->db_dsn  = $db_dsn;
		$this->tb_name = $tb_name;
		
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}
		$this->mdb2 = $mdb2;
	}
	//=====================================================================//
	public function renderRating($rating){
	//=====================================================================//
	  if (empty($rating)) { $rating = 0; }
		//echo $rating;
		$star_rating = '<form id="ITS_rating" action="" method="post">'.
								   '<div id="stars-cap"></div>'.
									 '<div id="ITS_rate" class="center" rating="" value="">';
									 
		$title = array('Very easy','Easy','Moderate','Difficult','Very difficult');
		
		for ($n=1; $n<=count($title); $n++) {
		   if ($n==$rating) { $chk = 'checked="checked"'; }
			 else             { $chk = ''; }
       $star_rating .='<input type="radio" '.$chk.' name="rate" value="'.$n.'" title="'.$title[$n-1].'" id="rate'.$n.'" /> <label for="rate'.$n.'">'.$title[$n-1].'</label><br />';
		}

		$star_rating .= '<input type="submit" value="Rate it!" />'.
								    '</div>'.
		  					    '</form><p id="ajax_response"></p>';																		
		return $star_rating;	
	}	
//=====================================================================//
} //eo:class
//=====================================================================//
?>
