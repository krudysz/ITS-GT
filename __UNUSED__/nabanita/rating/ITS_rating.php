<?php
//=====================================================================//
/*

ITS_rating - rate questions.

		Constructor: ITS_rating( ... )
		
								 ex. $rating = new ITS_rating( ... );
								
	 Author(s): Nabanita Ghosal
	 Last Update: Jan-31-2011
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
	public function renderRating(){
	//=====================================================================//
		$star_rating = '<form id="ratings" action="" method="post">
			<input type="radio" name="rate" value="1" title="Very Easy" id="rate1" /> <label for="rate1">Poor</label><br />
			<input type="radio" name="rate" value="2" title="Easy" id="rate2" /> <label for="rate2">Fair</label><br />
			<input type="radio" name="rate" value="3" title="Moderate" id="rate3" /> <label for="rate3">Average</label><br />
			<input type="radio" name="rate" value="4" title="Difficult" id="rate4" /> <label for="rate4">Good</label><br />
			<input type="radio" name="rate" value="5" title="Very Difficult" id="rate5" /> <label for="rate5">Excellent</label><br />
			<input type="submit" value="Rate it!" />
		</form> <p id="ajax_response"></p>';	
		return $star_rating;	
	}	
	
//=====================================================================//
} //eo:class
//=====================================================================//
?>