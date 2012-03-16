<?php
//=====================================================================//
/*
ITS_rating class - render the rating system.

		Constructor: ITS_rating(...)
		
								 ex. $tagObj = new ITS_rating(...);
								
	 Author(s): 
	
*/
//=====================================================================//
class ITS_rating {

	var $variable1;
	var $variable2;
	//---------------------------------------------------------------------//
	public function __construct() {
	//---------------------------------------------------------------------//
		global $db_dsn;
		$this->db_dsn  = $db_dsn;
		
		$this->mdb2 =& MDB2::connect($this->db_dsn);
		
		$this->variable1 = array();
		$this->variable2 = array();
	}
	//---------------------------------------------------------------------// 
	public function renderRating() {
	//---------------------------------------------------------------------//
  	 //  Render "star" object 
  	$rating = '<div class="ratingHeader"><span>Difficulty?</span></div>'
   					 .'<div id="rating_system" class="ITS_rate" onload="loadStars()">'
						 .'<div id="vote" class="vote">&nbsp;</div>';

   	for($i=1;$i<=5;$i++) {
       $rating = $rating.'<img src="rating/icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="'.$i.'">';
    }
	
    $rating = $rating.'</div>'
  				   .'<div id="current rating"><script language="Javascript" src="forms/star_rating.js"></script></div>';
						 
						 // die(htmlspecialchars($rating));

    return $rating;
	}
	//---------------------------------------------------------------------//
	public function function2() {
	//---------------------------------------------------------------------//
		
		return $str;
	}		
	//---------------------------------------------------------------------//
}
//=====================================================================//
?>