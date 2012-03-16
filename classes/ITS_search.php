<?php
//=====================================================================//
/*
ITS_search - search box class.

		Constructor: ITS_search( ... )
		
								 ex. $search = new ITS_search( ... );
								
	 Author(s): Greg Krudysz
	 Last Update: Nov-16-2011
*/	 
//=====================================================================//
class ITS_search {
	
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
	public function renderBox($rtb,$rid){
	//=====================================================================//
	  //if (empty($rating)) { $rating = 0; }
		$box = '<hr class="ITS_search"><input id="ITS_search_box" type="text" name="keyword" rtb="'.$rtb.'" rid="'.$rid.'">'.
               '<div class="ITS_search"></div></p>';							 
               	 																	
		return $box;	
	}	
//=====================================================================//
} //eo:class
//=====================================================================//
?>
