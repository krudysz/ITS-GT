<?php
//=====================================================================//
// REWRITE FOR GENERIC DB CALL
/*
STATS class - Statistics Table

		Constructor: STAT(student_id,db_name,table_name)
		
								 ex. $pstats = new STATS("0000001","its","user_cpt");
								 
	 Author(s): Greg Krudysz |  Apr-10-2008
	 Last Revision: Apr-28-2011
*/
//=====================================================================//
class STATS
{
  var $student_id;				// ITS student ID
	var $db_name;					 	// DB name (e.g. "its")
	var $table_name;			  // STAT table name
	
	// Constructor //------------------------------------------------------
  function STATS($student_id,$db_name,$table_name){
	//---------------------------------------------------------------------
	  $this->student_id  		= $student_id;
		$this->db_name        = $db_name;
    $this->table_name     = $table_name;
  }
	//---------------------------------------------------------------------
	function load() { 	// Loads STAT for given chapter
	//---------------------------------------------------------------------
		// check if table exists
		$res_exists = mysql_query("show table status like '$this->table_name'");
		$table_constr = mysql_num_rows($res_exists) == 0; //""==mysql_error();	// if error: table already exists
		
    $sql = "CREATE TABLE IF NOT EXISTS ".$this->table_name. " (" .
  				 "id		  				  int NOT NULL  AUTO_INCREMENT,				 " .
  			   "question_id		  int,				 " .
  			   "concept_id			  int,				 " .
  			   "current_chapter	int, 				 " .
  				 "answered 				varchar(1024), " .
  				 "score 					  float, 			 " . 
  				 "rating 					int, 				 " .
  				 "comment 					varchar(128)," .
  				 "tags							varchar(256)," .
					 "epochtime       int(10) unsigned," .
					 "duration        int(11)," .
  				 "PRIMARY KEY (id),		 " .
  				 "FOREIGN KEY (concept_id) REFERENCES concept (id) ON DELETE SET NULL " .
  				 ")";				 
				
    echo '<p><span style="color:green">'.$sql.'</span><hr><p>';  // should print out "CREATE TABLE IF NOT EXIST tb_name ..."
    //die();
    
    $res = mysql_query($sql);
    if (!$res){ die('<p>Invalid query:<p> ' . mysql_error()); }
    // else{ echo "<p>" . var_dump($res); } 
    $this->error = mysql_error();
   }				 
	//---------------------------------------------------------------------
} //eo:class

	/*$query2 = 'CREATE TABLE IF NOT EXISTS stats_'.$id[0].' (
	id int NOT NULL AUTO_INCREMENT, 
	question_id int, 
	concept_id int, 
	current_chapter int, 
	answered varchar(1024), 
	score float, 
	rating int, 
	comment varchar(128),
	tags varchar(256), 
	epochtime int(10) unsigned, 
	duration int(11), 
	PRIMARY KEY (id), FOREIGN KEY (concept_id) REFERENCES concept (id) ON DELETE SET NULL )*/
?>
