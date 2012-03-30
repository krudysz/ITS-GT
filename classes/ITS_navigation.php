<?php
//=====================================================================//
/*
ITS_navigation - navigation class for tabs.

		Constructor: ITS_navigation( ... )
		
			ex. $nav = new ITS_navigation( ... );
								
	 Author(s):  Gregory A. Krudysz
	 Last Update: Feb-28-2012
*/
//=====================================================================//
class ITS_navigation {
	
	public function __construct($status) {
	
	global $db_dsn,$tb_name;

		$this->userid  = $userid;
		$this->db_dsn  = $db_dsn;
		$this->tb_name = $tb_name;
		$this->status  = $status;
				
	$Arr = array();			
				
	switch ($this->status) {
    case 'admin':		
		$Arr = array( array('User','User.php'),
		array('Database','DB.php'),
		array('Logs','Logs.php') );
    default:
		$tabArr = array( 
		array('Logout','logout.php'),
		array('ITS','screen.php'),
		array('Question','Question.php'),
		array('Image','Image.php'),		
		array('Profile','Profile.php'));
		$tabArr = array_merge($tabArr,$Arr);   
    }				
    $this->tabArr = $tabArr;
	}
	//=====================================================================//
	public function render($current,$item){
	//=====================================================================//
    $tabArr = $this->tabArr;
	switch ($current) {
    case 'Question':
    	$qArr = array( 
		array('Course','Course.php')
				 );      
        $tabArr = array_merge($tabArr,$qArr);
        break;
    case 'Image':        
    	$qArr = array( array('Imagex','Image.php'),);
        //$tabArr = array_merge($tabArr,$qArr);
        break;    
    case 'Course':  
    	$cArr = array( 
		array('VIP','Course.php?c=vip'),
		array('SPEN','Course.php?c=spen'),
		array('ECE 3075','Course.php?c=ece3075'),
		array('ECE 2025','Course.php?c=ece2025'),
		array('Warmup','Course.php?c=warmup'),
				 );          
        $tabArr = array_merge(array_slice($tabArr, 0,3) ,$cArr);				 
        break;           
    case 'vip':
    case 'spen':
    	$Arr = array( 
		array('VIP','vip.php'),
	    array('SPEN','spen.php'),
				 );
        $tabArr = array_merge($tabArr,$Arr);
        break;  
    case 'php':
    	$Arr = array( 
		array('PHP','php.php'),
				 );
        $tabArr = array_merge($tabArr,$Arr);
        break;              
    default:
		$other = '';
        break;
	}
    //ITS_debug($tabArr);  die();//echo $current;

	$tabList = '';	
    foreach ($tabArr as $t) {
	    
		if (strtolower($t[0]) == strtolower($current) ) { $id = 'id="current"';  }	
		else 			  { $id = ''; }
		
		if ($item!=''){
		$match = '/'.$item.'/i';
		if (preg_match($match,$t[1])) { $id = 'id="current"'; }	
		else 			  			  { $id = ''; 			  }
	    }
		
		switch ($t[0]){
			case 'VIP':
			case 'PHP':
			$style = 'style="margin-right: 4em"';
			break;
			default:
			$style = '';
			
		}
		//echo $t[0] .'=='. $current.'=='. $item.'=='.$match.'=='.$t[1].'--'.$id.'<br>';

		$tabList .= '<li><a '.$style.' href="'.$t[1].'" '.$id.'>'.$t[0].'</a></li>';
    }
	$tabs = '<div id="ITS_navigation_container"><ul id="ITS_navlist">'.$tabList.'</ul></div>';
	//die();
	return $tabs;		
	}	
//=====================================================================//
} //eo:class
//=====================================================================//
?>
