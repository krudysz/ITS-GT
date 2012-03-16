<?php
//=====================================================================//
/*
ITS_book - assembles the book.

		Constructor: ITS_book(name,chapter,meta)
		
								 ex. $ITS_book = new ITS_book('dspfirst',2,2,array(1,2,3,4),array(20,30));
								
	 Author(s): Greg Krudysz |  Aug-18-2010
//-----------------------------------------*/
/*
CALLS: 

			 require_once ("classes/ITS_book.php");
			 $x = new ITS_book($id);
			 $o = $x->main();

*/
//=====================================================================//

class ITS_book {

		public $name;
		public $chapter;
		public $meta;	
		
	  //public function get_lab_active()        { return $this->lab_active;  }
	
	//=====================================================================//
  	   function __construct($name,$chapter,$meta) {
	//=====================================================================//
	  global $db_dsn,$db_name,$tb_name,$db_table_user_state;
		
		$this->name    = $name;
		$this->chapter = $chapter;
		$this->meta    = $meta;

		$this->db_dsn  = $db_dsn;
	  
		// connect to database
		$mdb2 =& MDB2::connect($db_dsn);
		if (PEAR::isError($mdb2)){ throw new Exception($this->mdb2->getMessage()); }
			
	  $this->mdb2 = $mdb2;
   } 
	//=====================================================================//
	   function main(){
	//=====================================================================//
	  // 
		$this->name   = 1;
		
		$main_str = $this->fcn($this->name);	
				
		return $main_str;
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
