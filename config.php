<?php
/*======== HOST =========================*/
$host = $_SERVER['SERVER_NAME']; //gethostname();
/*=======================================*/

$srv = '';

switch ($host) {
/*--------- LOCAL -----------------------*/
	case 'localhost':
/*---------------------------------------*/
	error_reporting(E_ALL ^ E_DEPRECATED);
	$db_name  = 'its';
	$db_dsn   = 'mysql://root:root@tcp(localhost:3306)/'.$db_name;	
	$srv 	  = ''; //'ITS'
	$CAS_path = '';	
	break;
/*--------- ITS.ECE.GATECH.EDU ----------*/
	case 'its.ece.gatech.edu':
/*---------------------------------------*/
	$db_name = 'ece2025';
	$db_dsn  = 'mysql://ece2025:5202ece@babeldev.ece.gatech.edu/'.$db_name;
	break;
/*--------- ITS.VIP.GATECH.EDU ----------*/
	case 'its.vip.gatech.edu':
	case 'itsdev2.vip.gatech.edu':
	case 'itsdev3.vip.gatech.edu':
	case 'itsdev4.vip.gatech.edu':
	case 'itsdev5.vip.gatech.edu':		
/*---------------------------------------*/
	$db_name   = 'its';
	$db_dsn    = 'mysql://root:csip@tcp(localhost:3306)/'.$db_name;
	$MDB2_path = '';
    $srv 	   = '/html';
	break;
/*---------------------------------------*/
	default:
	
}

define('INCLUDE_DIR','_include/');

    $files_dir = 'ITS_FILES';

/*======= OS TYPE =======================*/
if(PHP_OS == "WINNT") { 
	     $MDB2_path    = 'C:/xampp/php/pear/';
		 $mimetex_path = '/cgi-bin/mimetex.exe?';  // MIMETEX_PATH
		 $files_path   = '..'.$srv.'/ITS_FILES';           // RESOURCE_PATH (images etc.)
		 $CAS_path     = '';
} 
else {
	switch ($host) {
/*--------- LOCAL -----------------------*/
	case 'localhost':
/*---------------------------------------*/
		 $files_path   = $srv.'/'.$files_dir;
		 $files_path   = $files_dir;
	break;
/*--------- ITS.ECE.GATECH.EDU ----------*/
	case 'its.ece.gatech.edu':
/*---------------------------------------*/

	break;
/*--------- ITS.VIP.GATECH.EDU ----------*/
	case 'its.vip.gatech.edu':
	case 'itsdev2.vip.gatech.edu':
	case 'itsdev3.vip.gatech.edu':
	case 'itsdev4.vip.gatech.edu':
	case 'itsdev5.vip.gatech.edu':
/*---------------------------------------*/
		$files_path   = $files_dir;
/*---------------------------------------*/
	default:
}
	     $MDB2_path    = ''; ///usr/share/php/';
		 $mimetex_path = '/cgi-bin/mimetex.cgi?\Large ';
		 	
		 //die($files_path);
		 
	    // check if PEAR folder exists
		// echo '<p style="color:blue">22'.getcwd() . "</p>\n";
			
		//echo $_SERVER['PHP_SELF'].'<br>'.dirname($_SERVER['PHP_SELF']).'<br>';
		$dir = dirname($_SERVER['PHP_SELF']);	
			
			//$t = '/ITS/ajax';
			preg_match('/ajax|admin/', $dir, $ajax_match);
			//var_dump($ajax_match);//die('s');
			
		if (empty($ajax_match)) {	// exclude /ajax dir	
	        $MDB2_path = 'FILES/PEAR/';
			$MDB2_dir  = dir(getcwd().'/'.$MDB2_path);
			
			if (empty($MDB2_dir->handle)){
			  die('<p>in '.getcwd().'/config.php:  <font color="red">MISSING <b>PEAR</b> folder</font>.<p>');
			}
			
			$CAS_path = 'FILES/CAS-1.1.1/'; 
			$CAS_dir  = dir(getcwd().'/'.$CAS_path);
			
			if (empty($CAS_dir->handle)){
			  die('<p>in '.getcwd().'/config.php:  <font color="red">MISSING <b>CAS</b> folder</font>.<p>');
			}
		}		 	 	 
}
/*=======================================*/

// Set time zone: America/New_York
date_default_timezone_set('America/New_York');

$db_table_users      = 'users';
$db_table_user_state = 'stats_';
$db_table_user_cpt   = 'cpt_';
$db_table_question 	 = 'question';
$tb_name 			 = 'webct';

$question_dir 		 = "question";
$question_file_ext   = 'html';
$answer_dir 		 = "answer";
$answer_file_ext 	 = 'html';
$BNT_dir 		     = "Debug";

global $db_dsn,$db_table_user_state,$db_table_user_cpt,$db_name,$tb_name,$mimetex_path,$files_path,$host,$CAS_path;

/*
echo '<table class="ITS_backtrace">';	
array_walk( debug_backtrace(), create_function( '$a,$b', 'print "<tr><td><font color=\"blue\">". basename( $a[\'file\'] ). "</b></font></td><td><font color=\"red\">{$a[\'line\']}</font></td><td><font color=\"green\">{$a[\'function\']}()</font></td><td>". dirname( $a[\'file\'] ). "/</td></tr>";' ) ); 	
echo '</table>';	
*/
/*
	echo '<pre>';
	print_r($data);
	echo '</pre>';
*/
?>
