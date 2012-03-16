<?php
//=====================================================================//
/*
ITS_query - query DB for resources.

		Constructor: ITS_query(ch)
		
								 ex. $query = new ITS_query('tableA',2,2,array(1,2,3,4),array(20,30));
								
	 Author(s): Greg Krudysz |  Nov-07-2011
	 Last Revision: Nov-07-2011
//-----------------------------------------
*/
//=====================================================================//

class ITS_query {

    public $id;
    public $term;
    public $chapter_number;

    //=====================================================================//
    function __construct() {
        //=====================================================================//
        $this->debug = FALSE; //TRUE;

        if ($this->debug) {
            echo '<br>'.get_called_class();
        }
        global $db_dsn,$db_name,$tb_name,$db_table_user_state,$mimetex_path;

        $this->record  = array();
        $this->db_dsn  = $db_dsn;

        // connect to database
        $mdb2 =& MDB2::connect($db_dsn);
        if (PEAR::isError($mdb2)) {
            throw new Exception($this->mdb2->getMessage());
        }

        $this->mdb2 = $mdb2;

    }
    //=====================================================================//
    function getQuery($qet,$usertable,$ch,$epochtime) {
        //=====================================================================//
			if ($ch == 1)     { $other = '|Complex$';          } 
			elseif ($ch == 13){ $other = '|PEZ$|chapter7DM$';  }
		    else 			  { $other = '';                   }
		    
			$query = 'SELECT '.$qet.' FROM '.$usertable.',webct WHERE '.$usertable.'.question_id=webct.id AND current_chapter='.$ch.' AND category REGEXP "(SPEN'.$ch.'$|PreLab0'.$ch.'$|Lab'.$ch.'$|Chapter'.$ch.'$|-Mod'.$ch.'$'.$other.')" AND '.$usertable.'.score IS NOT NULL AND epochtime > '.$epochtime;
        
        return $query;
    }
    //=====================================================================//
    function getCategory($ch) {
        //=====================================================================//  
        //die($ch);   
			if ($ch == 1)     { $other = '|Complex$';          } 
			elseif ($ch == 13){ $other = '|PEZ$|chapter7DM$';  }
		    else 			  { $other = '';                   }
		    
			$query = 'category REGEXP "(SPEN'.$ch.'$|PreLab0'.$ch.'$|Lab'.$ch.'$|Chapter'.$ch.'$|-Mod'.$ch.'$'.$other.')" AND qtype IN ("MC","M","C")';
        
        return $query;
    }
    //=====================================================================//
} //eo:class
//=====================================================================//
?>
