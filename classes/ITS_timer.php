<?php
/* Class for handling PHP download timing */
//==============================================================================
class ITS_timer {
//==============================================================================

	private $time0;
			
  function __construct() {
	
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
		
	  $this->time0   = $mtime;
	  
		// self::main();
  }
	//----------------------------------------------------------------------------
  	function etime() {
   		 
			 $mtime = microtime();
   		 $mtime = explode(" ",$mtime);
   		 $mtime = $mtime[1] + $mtime[0];
   		 $totaltime = ($mtime - $this->time0);
			 
   		 echo "<p>Page created in ".$totaltime." seconds";
	  }
	//----------------------------------------------------------------------------
//==============================================================================
}
?> 