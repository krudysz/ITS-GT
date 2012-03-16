<?php
/*
ITS_book - script for AJAX ITS_book class

Author(s): Greg Krudysz
Date: Jul-12-2011        
---------------------------------------------------------------------*/
require_once("config.php");

require_once($MDB2_path.'MDB2.php');
include("classes/ITS_table.php");

include("classes/ITS_configure.php");
include("classes/ITS_question.php");
include("classes/ITS_statistics.php");
include("classes/ITS_screen2.php");
include("classes/ITS_tag.php");
require_once("classes/ITS_book.php");

session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state, $files_path;

//---------------------------------------//
// AJAX
//---------------------------------------//

//-- Get AJAX arguments
$args = preg_split('[,]',$_GET['ajax_args']);

//-- Get AJAX user data
$Data = rawurldecode($_GET['ajax_data']);
// preprocess before SQL
$Data = str_replace ("'","&#39;",$Data);
//$Data = nl2br($Data);
$action = $args[0];

/*
echo 'action = '.$action.'<p>';
echo 'data   = '.$Data.'<p>';    die();
*/

$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}

//-----------------------------------------------//
switch ($action){ 
      //-------------------------------------------//
	  case 'addTAG':
	  //-------------------------------------------//		  
		  $data = preg_split('[~]',$Data);
		  $t    = new ITS_tag('tags');
		  $tag  = $t->addToQues($data[0],$data[1],$data[2],$data[3]);
		  $str  = $tag;
		  break;
    //-------------------------------------------//
	  case 'submit':
	  //-------------------------------------------//
		  $data = preg_split('[~]',$Data);
		  $t    = new ITS_tag('tags');
		  
		  $Ques_tag_arr  = $t->getByResource($data[1],$data[2]);
		  //var_dump($Ques_tag_arr);		  die('stop');
		  $Keyw_tag_list = '';
          $Keyw_tag_arr  = $t->query($data[0],$Ques_tag_arr);     
          if (empty($Keyw_tag_arr[0])) { 
			  $Keyw_tag_list = $t->add($data[0]);
		  }

		  //die($list);
		  $str = $Keyw_tag_list;
		  break;		  
	  //-------------------------------------------//
}
//-----------------------------------------------//
$mdb2->disconnect();
echo $style.$str;

//-----------------------------------------------//
/*
$data = preg_split('[,]',$Data);

$tid   = $data[0];
$tname = $data[1];

                $ids   = 'SELECT dspfirst_ids FROM dspfirst_map WHERE tag_id='.$tid;
                $query = 'SELECT meta,content FROM dspfirst WHERE id IN ('.$ids.')';
                //echo $query;
                $res = mysql_query($query);
                if (!$res) {die('Query execution problem in ITS_tag_AJAX: ' . msql_error());}
                
                
    while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    switch ($row['meta']) {
    case 'paragraph':
        echo '<br>'.$row["content"].'</br>';
        break;
        case 'equation':
		echo $mimetex_path.$row["content"].'<img class="ITS_EQUATION" src="'.$mimetex_path.$row["content"].'"/>';
        break;
    default:
        echo "default: ".$row['meta'];
        break;
}
    
}
*/

//-----------------------------------------------//
/*
$ch = 1;
$meta = 'math';

$x = new ITS_book('dspfirst',$ch,$meta,$mimetex_path);
$o = $x->main();
echo $o.'<p>';
*/
//-----------------------------------------------//
?>
